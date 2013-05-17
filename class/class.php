<?
class data_connect{
	public $domain;

	function connect(){
		$domain = $_SERVER['HTTP_HOST'];
		if ($domain == 'localhost'){
			$kapcsolat = mysql_connect("localhost", LOCALHOST_DB_USER, LOCALHOST_DB_PASSWORD);
			$adatbazis = mysql_select_db(LOCALHOST_DB_NAME);}
		else {
			$kapcsolat = mysql_connect("localhost", DOMAIN_DB_USER, DOMAIN_DB_PASSWORD);
			$adatbazis = mysql_select_db(DOMAIN_DB_NAME);
		}

		if (!$kapcsolat) { die('Hiba a MySQL szerverhez kapcsolódás közben: ' . mysql_error());}
		if (!$adatbazis) { die('Hiba az adatbázis elérésekor: ' . mysql_error());}

		$ekezet = mysql_set_charset("latin2",$kapcsolat);
	}
}

class user{
	public $sorszam;
	public $nev;
	public $jog;
	public $email;
	public $csoport;
	public $belephiba;
	public $html_code;

	function login(){
		$jel = mysql_real_escape_string($_REQUEST['jelszo']);
		$azon = mysql_real_escape_string($_REQUEST['azonosito']);
		if (!$_REQUEST['azonosito']){$azon = $_SESSION["sessfelhasznaloazonosito"];}
		$jel = md5($jel);

		If ($_REQUEST['logout'] == 1) {
			unset($_SESSION["sessfelhasznalo"]);
			unset($_SESSION["sessfelhasznalosorszam"]);
			unset($_SESSION["sessfelhasznaloazonosito"]);
			unset($_SESSION["sessfelhasznalojog"]);
		}

		If ($_REQUEST['azonosito'] != "") {
			$result = mysql_query("SELECT sorszam, azonosito, jog, email, csoport FROM ".$_SESSION[adatbazis_etag]."_regisztralt WHERE azonosito = '$azon' AND jelszo = '$jel'");	
			$s = mysql_fetch_row($result);
		} else {
			$result = mysql_query("SELECT sorszam, azonosito, jog, email, csoport FROM ".$_SESSION[adatbazis_etag]."_regisztralt WHERE sorszam = '$_SESSION[sessfelhasznalosorszam]'");	
			$s = mysql_fetch_row($result);
		}
			if ($s[2] != ""){
				$this->sorszam = $s[0];
				$this->nev = $s[1];
				$this->jog = $s[2];
				$this->email = $s[3];
				$this->csoport = $s[4];
				$_SESSION["sessfelhasznalo"] = $s[1];
				$_SESSION["sessfelhasznalosorszam"] = $s[0];
				$_SESSION["sessfelhasznaloazonosito"] = $s[1];
				$_SESSION["sessfelhasznalojog"] = $s[2];
			} else {
				$this->belephiba = "Rossz felhasználónév, vagy jelszó!";
			}
		
	}
}

class admin{

   public $menu;
   
	function login_admin(){

		if ($_SESSION["sessfelhasznalojog"] == "1") {
		
			//belép
			$array = array('adminmenu' => $adminmenu);

			$admin_menuuj = new html_blokk;
			$admin_menuuj->load_template_file("template/admin_menu.tpl",$array);
			$this->menu = $admin_menuuj->html_code;

			
			
			}
		else {
			//nem lép be
			if ($_REQUEST[submit]){ $belephiba = "<tr><td colspan='2' class='cedula_ar'>Rossz felhasználónév, vagy jelszó!</td><tr>";	}
			$array = array('belephiba' => $belephiba);
			
			$admin_htmluj = new html_blokk;
			$admin_htmluj->load_template_file("template/login.tpl",$array);
			$admin_html = $admin_htmluj->html_code;
			
			$array = array('admin_torzs' => $admin_html);
			$admin_htmluj->load_template_file("template/admin.tpl",$array);
			$this->html_code = $admin_htmluj->html_code;	
			
		}

	}
}

class html_blokk{
	public $html_code;
	
	function load_template_file($fajlnev,$tomb) {
 
		if(file_exists($fajlnev) > 0) {
			$temp = fopen($fajlnev,"r");
			$tartalom = fread($temp, filesize($fajlnev));
			fclose($temp);
	 
			$tartalom = preg_replace("/{(.*?)}/si","{\$tomb[\\1]}",$tartalom);
	 
			eval("\$tartalom = \"" . addslashes($tartalom) . "\";");
			$tartalom = str_replace("\'", "'", $tartalom);
			$this->html_code = $tartalom . "\n";
		}
 
	}
}

class cikkszoveg {
	public $html_code;
	public $cikksorszam;
	public $cikkcim;
	
	function mysql_read($cikksorszam, $nyelv){
		if (($nyelv == '') OR ($nyelv == 'hu')){
			$nyelvszures = "AND nyelv = 'hu'";}
		else {
			$nyelvszures = "AND nyelv = '".$nyelv."'";
		}
		$r = mysql_query("SELECT tartalom, cim, archiv FROM ".$_SESSION[adatbazis_etag]."_szoveg WHERE sorszam =" . $cikksorszam . " ".$nyelvszures."");	
		$a = mysql_fetch_row($r);
		$cikkszoveg = $a[0];
		$cikkcim = $a[1];
		$cikkarchiv = $a[2];
		$this->cikksorszam = $cikksorszam;
		$this->cikkcim = $cikkcim;
		if ($cikkarchiv == 1){
			$this->html_code= '
			<h2 class="lapcim">Hiba történt!</h2>
			<div class="szovegblokk">
				A keresett oldal nem található!
			</div>';
		}
		
		//ha keresés eredménye a cikk, akkor a keresett szöveget megjelöli
		if ($_REQUEST[s]){
			$cikkszoveg = str_replace ($_REQUEST[s],'<span class="keres_span">'.$_REQUEST[s].'</span>',$cikkszoveg);
		}
		
		
			$this->html_code= '
			<h1 class="list_head">'.$cikkcim.'</h1>
			<div class="szovegblokk">
			' . $cikkszoveg. '
			
			</div>';
		}
	}
	
class cikklista {
	public $html_code;
	
	function mysql_read($kategoria, $limit){
	
	if ($kategoria != '99'){ $kateg_szur = " AND kategoria = '".$kategoria."'";}
    #if ($kategoria == '1'){ $kateg_szur = " AND (kiemelt = '0' OR kiemelt IS NULL)";}
    if ($kategoria == '1000'){ 
        $kiemelt_szur = " AND kiemelt = '1'";
        $kateg_szur = '';
        }
//	if ($limit != 0){$limit = ' LIMIT ' . $limit ;}
//	else {$limit = '';}
	$idopont = date("Y-m-d H:i:s");
    
   if ($kategoria != 1){ 
      $db_peroldal = 6;
      $adminpublic = 'public';
      $ujnavsav = new navsav();
      $ujnavsav->create_navsav("SELECT sorszam FROM ".$_SESSION[adatbazis_etag]."_szoveg WHERE hir='1' AND megjelenes <= NOW() AND archiv = '0'".$kateg_szur."".$kiemelt_szur."", $_REQUEST['lap'], $db_peroldal, $_REQUEST[kategoriaszures], $adminpublic);
      $lapszam = "LIMIT $ujnavsav->tol, $ujnavsav->ig";

         $lapszam_blokk = $ujnavsav->lapszamsor;
      if ($kategoria == 1000){
         $lapszam = "LIMIT ".$limit;
      } 
   } else {
      $lapszam = "LIMIT ".$limit;
   }
    
		$result = mysql_query("SELECT menunev, cim, tartalom, bevezeto, sorszam FROM ".$_SESSION[adatbazis_etag]."_szoveg WHERE hir='1' AND megjelenes <= NOW() AND archiv = '0'".$kateg_szur."".$kiemelt_szur." ORDER BY kelt desc ".$lapszam);	
		while ($next_element = mysql_fetch_array($result)){
			$cikksorszam = $next_element['sorszam'];
			$cikkcim = $next_element['cim'];
			$cikktartalom = $next_element['tartalom'];
			$cikkbevezeto = $next_element['bevezeto'];
			$menu_cikkek .= '
			<div class="hir_listaban">
				<h2 class="hirlista_cim">'.$cikkcim.'</h2>
				'.$cikkbevezeto.'
				<a href="?c='.$cikksorszam.'" class="more_info">További információ...</a>
				<br style="clear: both;" />
			</div>';
		}
		
		$this->html_code = $lapszam_blokk . $menu_cikkek;
	}
}

class message_div{
   public $html_code;
   public $run_js;
   var $error_message = array();
   var $error_count = 0;

    function add_message($message, $error){
       $this->error_message[] = $message;
       if ($error == 1){ $this->error_count++; }
    }

    function create_html(){
      
      if (count($this->error_message) > 0){
         foreach ($this->error_message as $i => $value) {
            $error_list .= '<li>'. $this->error_message[$i].'</li>';
         } 
         
         if ($this->error_count > 0){
         $error_message = '<h1>Az ûrlap az alábbi hibákat tartalmazza:</h1>
            <ul>'.$error_list.'</ul>';
         }
         else {
            $error_message = '<h1>Üzenet:</h1>
            <ul>'.$error_list.'</ul>';
         }
         
         $this->html_code = '
         <div id="messagediv" style="display: none;">
            <div>
               '.$error_message.'
               <img src="graphics/ok.png" alt="ok" onclick="divdisp_on(\'messagediv\');" />
               <br style="clear: both;" />
            </div>
        </div>';

         $this->run_js = ', divdisp_on(\'messagediv\')';
      }
    }
    
}

class navsav{
	//egy lista navigációs sávjának elkészítése (várt adat az sql, melyik lapon vagyunk)
	public $tol;
	public $ig;
	public $lap;
	public $termekdb;
	public $lapszamsor;
	
	function create_navsav($sql_query, $lap, $db_peroldal, $xkategoriaszures, $adminpublic){
		$result = mysql_query($sql_query);
		$this->termekdb = mysql_num_rows($result);
		
		If (($lap == "") OR ($lap == 1)) {
			$this->tol = 0;
			$this->ig = $db_peroldal;}
		else {
			$this->tol = $db_peroldal * ($lap-1);
			$this->ig = $db_peroldal;
		}
		
		$olddb = 0;
		$oldelemdb = 0;
		#10 számos oldalszámblokk elemei
		if ($lap != ""){
			$kapott_oldal = $lap;}
		else {
			$kapott_oldal = 1;
		}
			
		$kapott_oldal_m = $kapott_oldal;
		$kapott_oldal_p = $kapott_oldal;

		for ($i = 0; 10>$i; $i++){
			If (($kapott_oldal_m %10 == 0) OR ($kapott_oldal_m == 1)) {
				if ($min_oldal == ""){
					$min_oldal = $kapott_oldal_m;
				}
			}
			If ($kapott_oldal_p %10 == 0) {
				if ($max_oldal == ""){
				$max_oldal = $kapott_oldal_p;
				}
			}
			$kapott_oldal_m--;
			$kapott_oldal_p++;
		}
		
		if (($adminpublic == 'public') OR ($adminpublic == '')) {$cel = '?x=hirfolyam&lap=';}
		if ($adminpublic == 'admin') {$cel = 'admin.php?tartalom=szovegszerk&amp;lap=';}
		
		If ($this->termekdb > $db_peroldal){
			$olddb = ($min_oldal-1);
			for ($i = ($min_oldal-1); $i <= $this->termekdb; $i++){
				If (($i %$db_peroldal == 0) OR ($i == 0)) {
					$olddb++;
					$oldelemdb++;
					$oldvalt = "oldalszam";
					If ($olddb == $lap){$oldvalt = "oldalszamv";}
					If (($lap == "") AND ($i == 0)) {$oldvalt = "oldalszamv";}
					if ($xkategoriaszures != "") {$kategoriaszuresxx = '&amp;kategoriaszures='.$xkategoriaszures;}
                    
                    if ($_REQUEST[lap] == $olddb){
                       $szam_szin = 'style="color: red; font-weight: bold;"';}
                    else {
                       $szam_szin = '';
                    }
                    
					$this->lapszamsor .= '<a class="'.$oldvalt.'" href="'.$cel.$olddb.$kategoriaszuresxx.'"'.$szam_szin.'> '.$olddb.'</a>';}
					if ($oldelemdb == 10) {break;}
					if ($olddb == round($this->termekdb/$db_peroldal,0)+1){break;}
				}
		}
		
		if ($this->lapszamsor != ""){
			$elozooldal = $kapott_oldal-1;
			$kovetkezooldal = $kapott_oldal+1;
			if ($elozooldal < 1) {$elozooldal = 1;}
			if ($kovetkezooldal > round($this->termekdb/$db_peroldal,0)){ $kovetkezooldal = (round($this->termekdb/$db_peroldal,0)+1);}
			if ($_REQUEST[kategoriaszures] != "") {$kategoriaszuresxx = '&amp;kategoriaszures='.$_REQUEST[kategoriaszures];}
			$this->lapszamsor = '<a href="'.$cel.'1'.$kategoriaszuresxx.'" class="oldalszam" title="elsõ">&#60;&#60; </a> <a href="'.$cel.$elozooldal.$kategoriaszuresxx.'" class="oldalszam" title="elõzõ" style="margin-right: 10px;"> &#60; </a>' . $this->lapszamsor . '<a href="'.$cel.$kovetkezooldal.$kategoriaszuresxx.'" class="oldalszam" style="margin-left: 10px;" title="következõ"> &#62;</a> <a href="'.$cel.(round($this->termekdb/12,0)+1).$kategoriaszuresxx.'" class="oldalszam" title="utolsó"> &#62;&#62;</a>';
            $this->lapszamsor = '<div class="admin_lapszamsor">'.$this->lapszamsor.'</div>';
		}
		
	}
}

class log_db {

   function __construct(){
      $sql = mysql_query("SHOW TABLES LIKE 'gps_ugyfelkapu_log'");
      $value = mysql_fetch_row($sql);
      if (!$value[0]) {$this->create_database();}
   }
   
   function write($user, $message) {
        $idopont = date("Y-m-d H:i:s");
        $sql = "INSERT INTO ".$_SESSION[adatbazis_etag]."_log (idopont, user, uri, message, host, user_agent, ip)
            VALUES ('$idopont', '$user', '$_SERVER[REQUEST_URI]', '$message', '$_SERVER[REMOTE_HOST]', '$_SERVER[HTTP_USER_AGENT]', '$_SERVER[REMOTE_ADDR]')";
            mysql_query($sql);
   }
   
   function create_database() {
      $sql2 = "CREATE TABLE IF NOT EXISTS `".$_SESSION[adatbazis_etag]."_log` (
            `sorszam` int(11) NOT NULL AUTO_INCREMENT,
            `idopont` datetime DEFAULT NULL,
            `user` int(11) DEFAULT NULL,
            `uri` varchar(200) CHARACTER SET latin2 COLLATE latin2_hungarian_ci DEFAULT NULL,
            `message` text CHARACTER SET latin2 COLLATE latin2_hungarian_ci,
            `user_agent` varchar(150) CHARACTER SET latin2 COLLATE latin2_hungarian_ci DEFAULT NULL,
            `host` varchar(150) CHARACTER SET latin2 COLLATE latin2_hungarian_ci DEFAULT NULL,
            `ip` varchar(30) DEFAULT NULL,
            PRIMARY KEY (`sorszam`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56;";
      mysql_query($sql2);
   }
   
   function admin_form(){
      return '
         <form method="post" action="" name="log" class="admin_form">
            <div class="a_form_fej">Napló kezelése <input type="submit" value="OK" class="a_form_mentes" /></div>
            <label>Szöveg:</label><input type="text" name="szoveg" />
            <label>Felhasználó:</label><input type="text" name="felhasznalo" />
            <label>Dátumtól:</label><input type="text" name="datumtol" />
            <label>Dátumig:</label><input type="text" name="datumig" />
         </form>';
   }
}
?>