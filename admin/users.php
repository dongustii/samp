<?
If ($_REQUEST[id]){
	if ($_REQUEST[submit]){
		$form_email = mysql_real_escape_string($_REQUEST['email']);
		$form_azonosito = mysql_real_escape_string($_REQUEST['azonositox']);
		$form_jog = mysql_real_escape_string($_REQUEST['jog']);
		$form_uj_jelszo = mysql_real_escape_string($_REQUEST['uj_jelszo']);
		$form_uj_jelszo_md5 = md5($form_uj_jelszo);
		
		If ($_REQUEST[id] !='x'){
			$sql = "UPDATE ".$_SESSION[adatbazis_etag]."_regisztralt SET email='$form_email', azonosito='$form_azonosito', jog='$form_jog' WHERE sorszam = $_REQUEST[id]";
			mysql_query($sql);
			if ($_REQUEST['uj_jelszo']){
				$sql = "UPDATE ".$_SESSION[adatbazis_etag]."_regisztralt SET jelszo='$form_uj_jelszo_md5' WHERE sorszam = $_REQUEST[id]";
				mysql_query($sql);
			}
		} else {
			$sql = "INSERT INTO ".$_SESSION[adatbazis_etag]."_regisztralt (azonosito, email,  jog, jelszo) VALUES ('$form_azonosito', '$form_email', '$form_jog', '$form_uj_jelszo_md5')";
			mysql_query($sql);
			$result = mysql_query("SELECT sorszam FROM ".$_SESSION[adatbazis_etag]."_regisztralt WHERE azonosito = '$form_azonosito'");
			$a = mysql_fetch_row($result);
			$idx = $a[0];
		}
		
	}

	$users_gomb = '<input type="submit" name="submit" value="Mentés" class="a_form_mentes" />';
	If ($_REQUEST[id] !='x'){
		$result = mysql_query("SELECT sorszam, azonosito, email, jog FROM ".$_SESSION[adatbazis_etag]."_regisztralt WHERE sorszam = $_REQUEST[id]");
		$a = mysql_fetch_row($result);
		$idx = $a[0];
	} else {
		if (!$idx){$idx = 'x';}
	}
	$users = '
	<div class="a_form_user">
		<input type="text" name="id" style="display:none;" value="'.$idx.'" />
		<label>Azonosító:</label>
		<input type="text" name="azonositox" value="'.$a[1].'" />
		<label>Email:</label>
		<input type="text" name="email" value="'.$a[2].'" />
		<label>Jog:</label>
		<input type="text" name="jog" value="'.$a[3].'" />
		<label>Új jelszó:</label>
		<input type="text" name="uj_jelszo" value="" />
		<br style="clear: both;" />
		<a href="?tartalom=users">vissza</a>
	</div>';
	}
else {
	$result = mysql_query("SELECT sorszam, azonosito, email, jog FROM ".$_SESSION[adatbazis_etag]."_regisztralt");
	while ($next_element = mysql_fetch_array($result)){
		if ($next_element[jog] == 0){$jog = 'felhasználó';}
		if ($next_element[jog] == 1){$jog = 'adminisztrátor';}
		$users .= '
		<a href="?tartalom=users&id='.$next_element[sorszam].'" class="admin_form_sor">
			<span>'.$next_element[azonosito] .'</span>
			<span>'.$next_element[email] .'</span>
			<span>'.$jog.'</span>
		</a>';
	}
	$users .= '<br /><a href="?tartalom=users&id=x">új felhasználó</a>';
}

$admin_torzs = '
	<form action="" enctype="multipart/form-data" method="post" class="admin_form">
		<div class="a_form_fej">
			Felhasználók
			'.$users_gomb.'
		</div>
		<div class="a_form_beldiv">
			<div class="a_form_beldiv" style="border: 0px;">
				'.$users.'
			</div>
			<br style="clear:both;" />
		</div>
	</form>';
?>