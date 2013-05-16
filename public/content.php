<?php
if ($_REQUEST[c] != ''){
   if (is_numeric($_REQUEST[c])){
      $cikk = new cikkszoveg;
      $cikk->mysql_read($_REQUEST[c], 'hu');
   } else {
      $cikk = new html_blokk();
      $cikk->load_template_file("template/".$_REQUEST[c].".html",$array);
   }
} else {
   $cikk = new cikkszoveg;
   $cikk->mysql_read(20, 'hu');
}

if ($cikk->cikkcim){
   $alcim = ' - '. $cikk->cikkcim;
}
$tartalom = $cikk->html_code;
?>