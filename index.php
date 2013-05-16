<?php
session_start();
require_once('parameters.php');
require_once('class/class.php');

$adatkapcsolat = new data_connect;
$adatkapcsolat->connect();

require_once('public/content.php');
        
$array = array('tartalom' => $tartalom,
			   'alcim' => $alcim);
	 
$index_html = new html_blokk;
$index_html->load_template_file("template/index.html",$array);
echo $index_html->html_code;
?>