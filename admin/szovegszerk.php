<?
If ($_REQUEST['check_torles'] == "on") {
	$result = "DELETE FROM ".$_SESSION[adatbazis_etag]."_szoveg WHERE sorszam = $_POST[sorszam] LIMIT 1";
	mysql_query($result);
}
If ($_POST['ujcim'] != "") {
	$result = mysql_query("SELECT MAX(sorszam) FROM ".$_SESSION[adatbazis_etag]."_szoveg");
	$row = mysql_fetch_array($result); 
	$num_rows=$row[0];
	$num_rows++;
	$result = mysql_query("SELECT MAX(sorrend) FROM ".$_SESSION[adatbazis_etag]."_szoveg");
	$row = mysql_fetch_array($result); 
	$num_sorrend=$row[0];
	$num_sorrend++;

	$result = "INSERT INTO ".$_SESSION[adatbazis_etag]."_szoveg  
		(sorszam, sorrend, archiv, nyelv, cim)
		VALUES
		($num_rows, $num_sorrend, '1', 'hu', '$_POST[ujcim]')";
	mysql_query($result);
}

If ($_POST['cim'] != "") {
	If ($_REQUEST['kategoria'] == "") {
		if ($_POST[check_menu] == 'on'){$ment_menu = 1;}
			else {$ment_menu = 0;}
		if ($_POST[check_hir] == 'on'){$ment_hir = 1;}
			else {$ment_hir = 0;}
		if ($_POST[check_aktiv] == 'on'){$ment_aktiv = 0;}
			else {$ment_aktiv = 1;}
		$sql2 = "UPDATE ".$_SESSION[adatbazis_etag]."_szoveg SET tartalom='$_POST[content]', bevezeto='$_POST[bevezeto]', cim='$_POST[cim]', menunev='$_POST[menunev]', sorrend='$_POST[sorrend]', nyelv='$_POST[cikk_nyelv]', archiv='$ment_aktiv', szulo='$_POST[szulo]', menu_fent='$ment_menu', hir='$ment_hir' WHERE sorszam='$_POST[sorszam]'";
		mysql_query($sql2);
	} else {
		$sql2 = "UPDATE ".$_SESSION[adatbazis_etag]."_kategoriak SET szoveg='$_POST[content]' WHERE sorszam='$_REQUEST[kategoria]'";
		mysql_query($sql2);
	}
}

If ($_REQUEST['kategoria'] == "") {
	If ($_REQUEST[valaszt] != ''){
		$r = mysql_query("SELECT tartalom, sorszam, cim, menunev, nyelv, menu_fent, archiv, sorrend, hir, bevezeto, szulo FROM ".$_SESSION[adatbazis_etag]."_szoveg WHERE sorszam = $_REQUEST[valaszt]");
		$h = mysql_fetch_row($r);  
		$szoveg_tartalom = $h[0];
		$szoveg_nyelv = $h[4];
		$szoveg_menu = $h[5];
		$szoveg_archiv = $h[6];
		$szoveg_hir = $h[8];
		$szoveg_bevezeto = $h[9];
		if (($szoveg_nyelv == 'hu') OR ($szoveg_nyelv == '')){ $cikknyelvcombo_hu = 'selected="selected"';}
		if ($szoveg_nyelv == 'en') { $cikknyelvcombo_en = 'selected="selected"';}
		if ($szoveg_menu == 1) {$cikkmenu_check = 'checked="checked"';}
		if ($szoveg_hir == 1) {$cikkhir_check = 'checked="checked"';}
		if (($szoveg_archiv == 0) OR ($szoveg_archiv == '')) {$cikkarchiv_check = 'checked="checked"';}
		
		$valaszt = 'valaszt';
		$menu_nev = '<tr><td>Menünév:</td><td><input name="menunev" type="text" value="' . $h[3] . '" /></td></tr>';
		}
	}
else {
	$r = mysql_query("SELECT szoveg, sorszam, kategorianev FROM ".$_SESSION[adatbazis_etag]."_kategoriak WHERE sorszam = $_REQUEST[kategoria]");
	$h = mysql_fetch_row($r);  
	$szoveg_tartalom = $h[0];
	
	$valaszt = 'kategoria';
	$menu_nev= '';
}

$result = mysql_query("SELECT sorszam, cim, nyelv, archiv, menu_fent, sorrend, hir FROM ".$_SESSION[adatbazis_etag]."_szoveg ORDER BY sorrend");
$sor = 0;
while ($next_element = mysql_fetch_array($result)){
	$sor++;
	
	if ($next_element[archiv] == 0){
		$archivkapcs = 'igen';
	} else {
		$archivkapcs = 'nem';
	}
	
	if ($next_element[menu_fent] == 0){
		$menukapcs = 'nem';
	} else {
		$menukapcs = 'igen';
	}
	
	$szoveglista .= '
	<a href="?tartalom=szovegszerk&valaszt='.$next_element[sorszam].'" class="cedula_admintermeklista">
		<span style="width: 26px;">'. $sor.'</span>
		<span style="width: 320px; text-align: left;">'. $next_element[cim].'</span>
		<span style="width: 50px; text-align: center;">'. $next_element[sorszam].'</span>
		<span style="width: 50px; text-align: center;">'. $next_element[nyelv].'</span>
		<span style="width: 50px; text-align: center;">'.$menukapcs.'</span>
		<span style="width: 50px; text-align: center;">'.$archivkapcs.'</span>
		<span style="width: 50px; text-align: center;">'.$next_element[sorrend].'</span>
	</a>';
}

$szoveglista = '
	<form method="post" action="?tartalom=szovegszerk" name="szoveg" class="admin_form">
		<div class="a_form_fej">Cikkszerkesztõ</div>
		<div style="width: 630px; height: 20px; color: #444444; font-weight: bold; font-size: 12px; margin: 0px auto 0px auto;">
			<span style="width: 26px;">&nbsp;</span>
			<span style="width: 320px; text-align: center;">cím</span>
			<span style="width: 50px; text-align: center;">id</span>
			<span style="width: 50px; text-align: center;">nyelv</span>
			<span style="width: 50px; text-align: center;">menüelem</span>
			<span style="width: 50px; text-align: center;">aktív</span>
			<span style="width: 50px; text-align: center;">sorrend</span>
		</div>
		'.$szoveglista.'
		<div style="float:left; height: 40px; margin: 16px 0px 6px 36px;">
			Új szöveg címe:<input name="ujcim" type="text" value="" />
			<input type="submit" value="Létrehoz" />
		</div>
		<br style="clear: both;" />
		</div>
	</form>';


if (($_REQUEST['valaszt'] == '') OR ($_REQUEST['check_torles'] == "on")){
	$admin_torzs = '
	<div>
		'.$szoveglista .'
	</div>';
} else {
	$admin_torzs = '
	<script type="text/javascript" src="tinymce/tinymce_mod.js"></script>
	<form method="post" action="?tartalom=szovegszerk&'.$valaszt.'='. $h[1] .'&lang='.$nyelv.'" name="szoveg" class="admin_form">
		<div class="a_form_fej">Szövegszerkesztés <input type="submit" value="Mentés" class="a_form_mentes" /></div>
		<div style="width: 600px; float: left; text-align: left; margin: 20px 0px 0px 20px;">
			<a href="#" onclick="divdisp_on(\'admin_cikkadatlap\');" id="a_cikk_adatlap">ADATLAP</a>
			<a href="#" onclick="divdisp_on(\'admin_cikkszoveg\');" id="a_cikk_szoveg">SZÖVEG</a>
		</div>
		<div id="admin_cikkadatlap">
		<table class="a_form_beldiv">
			<tr><td>Cím:</td><td><input name="cim" type="text" value="' . $h[2] . '" /></span><br style="clear: both;"/></td></tr>
			<tr><td><input name="sorszam" type="text" value="' . $h[1] . '" size="2" style="display:none;" /></td></tr>
			'.$menu_nev.'
			<tr><td>Sorrend:</td><td><input name="sorrend" type="text" value="' . $h[7] . '" onkeyup="numerikusCheck(szoveg.sorrend)" /></span><br style="clear: both;"/></td></tr>
			<tr><td>Szulo:</td><td><input name="szulo" type="text" value="' . $h[10] . '" onkeyup="numerikusCheck(szoveg.szulo)" /></span><br style="clear: both;"/></td></tr>
			<tr><td>Nyelv:</td>
				<td>
				<select name="cikk_nyelv">
					<option '.$cikknyelvcombo_hu.'value="hu">hu</option>
				</select>
				</td>
			</tr>
			<tr><td>Menü elem:</td><td><input name="check_menu" type="checkbox" '.$cikkmenu_check.'/></td></tr>
			<tr><td>Kiemelt:</td><td><input name="check_hir" type="checkbox" '.$cikkhir_check.'/></td></tr>
			<tr><td>Aktív:</td><td><input name="check_aktiv" type="checkbox" '.$cikkarchiv_check.'/></td></tr>
			<tr><td>Végleges törlés:</td><td><input name="check_torles" type="checkbox" onclick="megerosites_x('.$h[1].', \'szoveg\')" /></td></tr>
		</table>
		</div>
		
		<div id="admin_cikkszoveg" style="display: none;">
		<div class="a_form_beldiv">
			<!--
			<p style="font-size: 14px; font-weight: bold; text-align: left;">Bevezetõ</p>
			<textarea name="bevezeto" style="width:672px; height:200px;">' . $szoveg_bevezeto . '</textarea>
			<br style="clear:both;" />
			<p style="font-size: 14px; font-weight: bold; text-align: left;">Tartalom</p>
			-->
			<textarea name="content" style="width:672px; height:550px;">' . $szoveg_tartalom . '</textarea>
			<br style="clear:both;" />
		</div>
		</div>
	</form>';
}

If ($kikapcs == 1) {$admin_torzs = '<div class="tabla_k">Ehhez a menüponthoz nem tartozik szerkeszthetõ tartalom!</div>';}
?>