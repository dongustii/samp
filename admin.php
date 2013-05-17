<?
session_start();
require_once('parameters.php');
require_once('class/class.php');

$adatkapcsolat = new data_connect;
$adatkapcsolat->connect();

$loging = new log_db;

$user = new user;
$user->login();
	
$admin = new admin;
$admin->login_admin();

if ($_REQUEST[tartalom]){
   require('admin/'.$_REQUEST[tartalom].'.php');
} else {
   require('admin/admin_cimlap.php');
}
			
$admin_html = new html_blokk;
$array = array('admin_torzs' => $admin_torzs,
               'admin_menu' => $admin->menu);
							
$admin_html->load_template_file("template/admin.tpl",$array);
		
echo $admin_html->html_code;
?>