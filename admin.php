<?
session_start();
require_once('parameters.php');
require_once('class/class.php');

$adatkapcsolat = new data_connect;
$adatkapcsolat->connect();

$user = new user;
$user->login();
	
$admin = new admin;
$admin->login_admin();
		
echo $admin->html_code;
?>