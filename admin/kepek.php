<?
If ($_REQUEST[ujkepment] == '1'){
	if ($_REQUEST[f5] == $_SESSION[f5vv]){
		#k�pfile felt�lt�se
		$result = mysql_query("SELECT MAX(id) FROM ".$_SESSION[adatbazis_etag]."_kepek");
		if (is_resource($result)) {
			$row = mysql_fetch_array($result); 
			$num_rows=$row[0];
			$num_rows++;}
		else {
			$num_rows = 1;}
		
		$fajlnev_n = $num_rows . $_SESSION["f5"] .'.jpg';
		$konyvtar = 'images/';
		foreach($_FILES as $allomanynev => $all_tomb) {
			move_uploaded_file($all_tomb['tmp_name'], "$konyvtar/$fajlnev_n");
			If ($all_tomb['tmp_name'] == "") {
				$nincsfajl=1;
			}
			$filenev = "images/".$all_tomb['name'];
			$filename = $all_tomb['name'];
			settype ($filenev, 'string');
			$uzenet = $all_tomb['tmp_name'];
		}
		
		#a k�p adatainak r�gz�t�se az adatb�zisba
		If ($nincsfajl != 1) {
			$sql2 = "INSERT INTO ".$_SESSION[adatbazis_etag]."_kepek (id, file) values ('$num_rows', '$fajlnev_n')";
			mysql_query($sql2);
		}	
	}
}

#k�p t�rl�s
	If ($_REQUEST['keptorles'] != ""){
		$result = mysql_query("SELECT file FROM ".$_SESSION[adatbazis_etag]."_kepek WHERE id = $_REQUEST[keptorles]");
		$row = mysql_fetch_array($result); 
		$filenev=$row[0];
		$sql = "DELETE FROM ".$_SESSION[adatbazis_etag]."_kepek WHERE id = $_REQUEST[keptorles]";
		mysql_query($sql);
		unlink("images/".$filenev);
	}
	
$result = mysql_query("SELECT id, file, title FROM ".$_SESSION[adatbazis_etag]."_kepek");
if (is_resource($result)) {
	while ($next_element = mysql_fetch_array($result)){
			$kepeklista .= '
				<div style="float: left; margin: 10px;">
					<img src="images/'.$next_element[file].'" width="120" alt="k�p" /><br />
					<input name="linktext" id="linktext" type="text" style="" value="images/'.$next_element[file].'" readonly="readonly" /><br />
					<img src="graphics/icon_del.png" title="k�p t�rl�se" border="0" width="23" style="margin: 5px 0 0 0;" onclick="megerosites_x('.$next_element[id].', \'kepek\')" alt="k�p t�rl�se" />
				</div>';
	}
}
f5();
$admin_torzs = '
	<form action="admin.php?tartalom=kepek&amp;ujkepment=1" enctype="multipart/form-data" method="post" class="admin_form">
		<div class="a_form_fej">
			K�pek felt�lt�se, karbantart�sa
			<!-- <input type="submit" name="Submit" value="Ment�s" class="a_form_mentes" /> -->
		</div>
		<div class="a_form_beldiv">
			<div class="a_form_beldiv" style="border: 0px;">
				�j k�p felt�lt�se:<input name="file" type="file" size="30" accept="image/*" maxlength="150" /><br style="clear:both;" />
				<input type="submit" name="submit" value="Felt�lt�s" />
				<input name="f5" id="f5" type="text" style="display: none;" value="'.$_SESSION[f5vv].'" />				
			</div>
			<div>Felt�lt�tt k�pek</div>
			'.$kepeklista.'
			<br style="clear:both;" />
		</div>
	</form>';
?>