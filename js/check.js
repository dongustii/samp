function divdisp_on(id){
    if (document.getElementById(id).style.display == 'block'){
		document.getElementById(id).style.display = 'none';}
	else {
		document.getElementById(id).style.display = 'block';}
   
	if (id == 'admin_cikkadatlap'){
		document.getElementById('admin_cikkadatlap').style.display = 'block';
		document.getElementById('admin_cikkszoveg').style.display = 'none';
	}
	if (id == 'admin_cikkszoveg'){
		document.getElementById('admin_cikkadatlap').style.display = 'none';
		document.getElementById('admin_cikkszoveg').style.display = 'block';
	}
    
    
}



function divdisp_onx(id){
	if (document.getElementById(id).style.display == 'block'){
		document.getElementById(id).style.display = 'none';}
	else {
		document.getElementById(id).style.display = 'block';}
}

function divdisp_off(id){
   document.getElementById(id).style.display = 'none';
}

function emailCheck(emailmezo){
	var elsopont = emailmezo.value.indexOf('.',0);
	var lastpont = emailmezo.value.lastIndexOf('.');
	var kukac = emailmezo.value.indexOf('@',0);
	var last_kukac = emailmezo.value.lastIndexOf('@');
	var email_size = emailmezo.value.length;

	if (emailmezo.value=='' || kukac==(-1) || kukac==0 || elsopont==0 

|| elsopont==(-1) || (lastpont==(email_size-1)) || (kukac==(email_size-1)) || 

((kukac+1)==elsopont) || (kukac!=last_kukac))
    {
     alert('Hibás email cím formátum!')
     emailmezo.focus()
     emailmezo.select()
	 emailmezo.value = '';
     return false;
    }
    if (emailmezo.name == "cimzett") {
		alert("Az adatlap e-mailben elküldve!");}
	
	return true;
}

function tesztv(adat,minta){
  for (var i=0; i<adat.length; i++)
    if (minta.indexOf(adat.charAt(i)) == -1)
      return false;
  return true;
}

function numerikusCheck(mezo){
  if (!tesztv(mezo.value,"+ /-()1234567890")){
    alert("A mezõ csak ilyen karaktert nem tartalmazhat!");
	mezo.value = "";
    return false;
  }
  else{
    return true;
  }
}

function passwd_check(){
	password = document.getElementById("jelszo1").value;
	if (password.length > 3){
		document.getElementById("passwd_strong1").style.backgroundColor = "#cecece";
		document.getElementById("passwd_strong2").style.backgroundColor = "#15FE58";
		document.getElementById("passwd_strong3").style.backgroundColor = "#cecece";
	} else {
		document.getElementById("passwd_strong1").style.backgroundColor = "#b73400";
		document.getElementById("passwd_strong2").style.backgroundColor = "#cecece";
		document.getElementById("passwd_strong3").style.backgroundColor = "#cecece";
	}
	
	if (password.length > 7){
		document.getElementById("passwd_strong2").style.backgroundColor = "#cecece";
		document.getElementById("passwd_strong3").style.backgroundColor = "#15FE58";
	}
}

function megerosites_x(torolszam, formnev, termek) {
	
	if (formnev == "kepek") {
		var answer = confirm ("Ön a KÉP TÖRLÉSÉT választotta.\n Biztosan szeretné?");
		if (answer) { window.location="admin.php?tartalom=kepek&keptorles="+torolszam;}
	}
	if (formnev == "diak") {
		var answer = confirm ("Ön a KÉP TÖRLÉSÉT választotta.\n Biztosan szeretné?");
		if (answer) { window.location="admin.php?tartalom=diak&keptorles="+torolszam;}
	}
	if (formnev == "galeriakep") {
		var answer = confirm ("Ön a KÉP TÖRLÉSÉT választotta.\n Biztosan szeretné?");
		if (answer) { window.location="admin.php?tartalom=galeria&csoport="+termek+"&kepment=2&torol="+torolszam;}
	}
	
	if (formnev == "termek_jellemzo") {
		var answer = confirm ("Ön a JELLEMZÕ TÖRLÉSÉT választotta.\n Biztosan szeretné?");
		if (answer) { window.location="admin.php?tartalom=termek&termek="+termek+"&jellemzotorles="+torolszam;}
	}
	if (formnev == "menupont") {
		var answer = confirm ("Ön a MENÜPONT TÖRLÉSÉT választotta.\n Biztosan szeretné?");
		if (answer) { window.location="admin.php?tartalom=menuk&torles="+torolszam;}
	}
}

function torol() {
    if (keres.menukeres.value=="keresés a weboldalon")
        keres.menukeres.value="";
    return true;
}

function load_km(){
  var km = document.getElementById('kiszallas').value;
  var munkaido = document.getElementById('munkaido').value;
  if (document.getElementById('tipus_l').checked == true){
     var tipus = 'l';
  } else {
     var tipus = 'v';
  }
		{
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{  
               kapottstring = xmlhttp.responseText;
               if (kapottstring != ''){
                  var ajax_string=kapottstring.split("--");
                  document.getElementById('kiszallas_ft').value=ajax_string[0];
                  document.getElementById('munkadij').value=ajax_string[1];
                  munkalaposszegzes();
               }
			}
		  }
        
         xmlhttp.open("GET","admin/ajax_load_km.php?val="+km+"&tipus="+tipus+"&munkaido="+munkaido,true);
         xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-2");
         xmlhttp.send();
        
		}
        
}

function load_munka_szoveg(){
   munka = document.getElementById('munka').value;
   kapottstring = '10';
   
   if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{  
               kapottstring = xmlhttp.responseText;
               if (kapottstring != ''){
                  document.getElementById('megjegyzes').value = kapottstring;
                }
			}
		  }
        
         xmlhttp.open("GET","admin/ajax_load_munka.php?val="+munka+"",true);
         xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-2");
         xmlhttp.send();
         
        
}

function show_php_in_div(div, php)
		{
		if (php=="")
		  {
		  document.getElementById(div).innerHTML="";
		  return;
		  }
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			document.getElementById(div).innerHTML=xmlhttp.responseText;
			}
		  }
        
		xmlhttp.open("GET",php,true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-2");
		xmlhttp.send();
        divdisp_on(div);
		}
        
function torol() {
    if (document.getElementById('menukeres').value=="KERESÉS")
        document.getElementById('menukeres').value="";
    return true;
}

function popup_keres(div, php)
		{
		if (php=="")
		  {
		  document.getElementById(div).innerHTML="";
		  return;
		  }
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			document.getElementById(div).innerHTML=xmlhttp.responseText;
			}
		  }
        q = document.getElementById('menukeres').value;
		xmlhttp.open("GET",php+"?q="+q,true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-2");
		xmlhttp.send();
}

function add_termek(id, megnevezes, ar){
   divdisp_off('popup');
   $('#hozzaad').after('<label>&nbsp;</label>'+'\
   <input type="text" name="darab_'+id+'" id="darab_'+id+'" onkeyup="db_szamol(\'darab_'+id+'\', \'ar_'+id+'\'); munkalaposszegzes();" style="width: 30px; float: left; margin-right: 2px;" value="1" />'+'\
   <input type="text" name="alkatresz_'+id+'" id="alkatresz_'+id+'" style="width: 200px; float: left; margin-right: 2px;" readonly="readonly" value="'+megnevezes+'" />'+'\
   <input type="text" name="ar_'+id+'" id="ar_'+id+'" style="width: 50px; float: left; margin-right: 5px;" readonly="readonly" value="'+ar+'" />');
   
   //document.getElementById('uj_tetel').value = id;
   
   var anyagkoltseg = Number(document.getElementById('anyagkoltseg').value);
   anyagkoltsegx = anyagkoltseg + Number(ar);
   document.getElementById('anyagkoltseg').value = anyagkoltsegx;
   
   load_cikk();
}

function munkalaposszegzes(){
   var munkalaposszeg;
   anyagkoltseg = Number(document.getElementById('anyagkoltseg').value);
   kiszallas_ft = Number(document.getElementById('kiszallas_ft').value);
   egyebkoltseg = Number(document.getElementById('egyebkoltseg').value);
   afa_mertek = Number(document.getElementById('munkalap_afamertek').value);
   munkadij = Number(document.getElementById('munkadij').value);
   munkalaposszeg = anyagkoltseg + kiszallas_ft + egyebkoltseg + munkadij;
   munkalaposszeg_brutto = munkalaposszeg * (1+(afa_mertek / 100));
   munkalaposszeg_brutto = Math.round(munkalaposszeg_brutto);
   document.getElementById('munkalaposszeg').value = munkalaposszeg;
   document.getElementById('munkalaposszeg_brutto').value = munkalaposszeg_brutto;
}

function db_szamol(id, ar){
   
   anyagkoltsegx = document.getElementById('anyagkoltseg').value;
   
   darab = document.getElementById(id).value;
   arx = document.getElementById(ar).value;
   
   var idx=id.split("_");
   
   if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{  
               kapottar = xmlhttp.responseText;
               ujosszeg = darab*kapottar;
               document.getElementById(ar).value = ujosszeg;
               anyagkoltsegx = anyagkoltsegx-arx+ujosszeg;
               document.getElementById('anyagkoltseg').value = anyagkoltsegx;
			}
		  }
        
         xmlhttp.open("GET","admin/ajax_load_alkatreszar.php?val="+idx[1]+"",true);
         xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=iso-8859-2");
         xmlhttp.send();
   
   
   

}