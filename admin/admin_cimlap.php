<?
$result = mysql_query("SELECT sorszam FROM ".$_SESSION[adatbazis_etag]."_regisztralt");
$felhasznalok_db = mysql_num_rows($result);

$result = mysql_query("SELECT sorszam FROM ".$_SESSION[adatbazis_etag]."_szoveg");
$szoveg_db = mysql_num_rows($result);

$admin_torzs = '
<div class="admin_xtabla">
<FORM ACTION="" enctype="multipart/form-data" METHOD="POST" NAME="jellemzok" style="width: 800px;" class="admin_form">
<div class="a_form_fej">C�mlap</div>
	<table class="cimlap_table" style="float: left; margin: 10px 10px 10px 10px;">
		<tr><th colspan="2" align="center">STATISZTIKA</th></tr>
		<tr><td align="left">R�gz�tett cikkek:</td><td align="right">'.$szoveg_db.'</td></tr>
		<tr><td align="left">Felhaszn�l�k:</td><td align="right">'.$felhasznalok_db.'</td></tr>
	</table>
    <div id="control">
      <a href="?tartalom=szovegszerk"><img src="graphics/document-icon.png" />CIKKEK SZERKESZT�SE</a>
      <a href="?tartalom=users"><img src="graphics/customers.png" />FELHASZN�L�K KARBANTART�SA</a>
    </div>
	<br style="clear: both;" />
</FORM>
</div>';
