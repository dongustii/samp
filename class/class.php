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

		if (!$kapcsolat) { die('Hiba a MySQL szerverhez kapcsol�d�s k�zben: ' . mysql_error());}
		if (!$adatbazis) { die('Hiba az adatb�zis el�r�sekor: ' . mysql_error());}

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
				$this->belephiba = "Rossz felhaszn�l�n�v, vagy jelsz�!";
			}
		
	}
}

class admin{

	function login_admin(){

		if ($_SESSION["sessfelhasznalojog"] == "1") {
		
			//bel�p
			$array = array('adminmenu' => $adminmenu);

			$admin_menuuj = new html_blokk;
			$admin_menuuj->load_template_file("template/admin_menu.tpl",$array);
			$admin_menu = $admin_menuuj->html_code;

			//modul kiv�laszt�sa
			if ($_REQUEST[tartalom]){
				include('admin/'.$_REQUEST[tartalom].'.php');
			} else {
				include('admin/admin_cimlap.php');
			}
			
			$admin_htmluj = new html_blokk;
			$array = array('admin_torzs' => $admin_torzs,
								'admin_menu' => $admin_menu);
								
			$admin_htmluj->load_template_file("template/admin.tpl",$array);
			$this->html_code = $admin_htmluj->html_code;	
			
			}
		else {
			//nem l�p be
			if ($_REQUEST[submit]){ $belephiba = "<tr><td colspan='2' class='cedula_ar'>Rossz felhaszn�l�n�v, vagy jelsz�!</td><tr>";	}
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
			<h2 class="lapcim">Hiba t�rt�nt!</h2>
			<div class="szovegblokk">
				A keresett oldal nem tal�lhat�!
			</div>';
		}
		
		//ha keres�s eredm�nye a cikk, akkor a keresett sz�veget megjel�li
		if ($_REQUEST[s]){
			$cikkszoveg = str_replace ($_REQUEST[s],'<span class="keres_span">'.$_REQUEST[s].'</span>',$cikkszoveg);
		}
		
		
			$this->html_code= '
			<h1 class="list_head">'.$cikkcim.'</h1>
			<div class="szovegblokk">
			' . $cikkszoveg. '
			<!-- Lockerz Share BEGIN -->
<a class="a2a_dd" href="http://www.addtoany.com/share_save?linkurl=www.hegesztesportal.hu%2F%3Fc%3D'.$cikksorszam.'&amp;linkname=Hegeszt%C3%A9sport%C3%A1l"><img src="http://static.addtoany.com/buttons/share_save_171_16.png" width="171" height="16" border="0" alt="Share"/></a>
<script type="text/javascript">
var a2a_config = a2a_config || {};
a2a_config.linkname = "Hegeszt�sport�l";
a2a_config.linkurl = "www.hegesztesportal.hu/?c='.$cikksorszam.'";
a2a_config.num_services = 6;
</script>
<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
<!-- Lockerz Share END -->
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
				<a href="?c='.$cikksorszam.'" class="more_info">Tov�bbi inform�ci�...</a>
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
         $error_message = '<h1>Az �rlap az al�bbi hib�kat tartalmazza:</h1>
            <ul>'.$error_list.'</ul>';
         }
         else {
            $error_message = '<h1>�zenet:</h1>
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
	//egy lista navig�ci�s s�vj�nak elk�sz�t�se (v�rt adat az sql, melyik lapon vagyunk)
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
		#10 sz�mos oldalsz�mblokk elemei
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
			$this->lapszamsor = '<a href="'.$cel.'1'.$kategoriaszuresxx.'" class="oldalszam" title="els�">&#60;&#60; </a> <a href="'.$cel.$elozooldal.$kategoriaszuresxx.'" class="oldalszam" title="el�z�" style="margin-right: 10px;"> &#60; </a>' . $this->lapszamsor . '<a href="'.$cel.$kovetkezooldal.$kategoriaszuresxx.'" class="oldalszam" style="margin-left: 10px;" title="k�vetkez�"> &#62;</a> <a href="'.$cel.(round($this->termekdb/12,0)+1).$kategoriaszuresxx.'" class="oldalszam" title="utols�"> &#62;&#62;</a>';
            $this->lapszamsor = '<div class="admin_lapszamsor">'.$this->lapszamsor.'</div>';
		}
		
	}
}
?>