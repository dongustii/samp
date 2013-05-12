<?php

try
{
	//Open database connection
	$domain = $_SERVER['HTTP_HOST'];
	if ($domain == 'localhost'){
      $con = mysql_connect("localhost","root","");
      mysql_select_db("adminx", $con);}
    else{
      $con = mysql_connect("localhost","klimaglo_user","jegesmaci12");
      mysql_select_db("klimaglo_data", $con);
    }
    
    mysql_set_charset("latin2",$con);
    $data_table = $_GET["data_table"];
	//Getting records (listAction)
	if($_GET["action"] == "list")
	{
		//Get records from database
		$result = mysql_query("SELECT * FROM $data_table;");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
           //latin2-es mezõ miatt
           $row['ertek']=iconv('ISO-8859-2','UTF-8',$row['ertek']);
		   $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	//Creating a new record (createAction)
	else if($_GET["action"] == "create")
	{
		//Insert record into database
        $ertek_string =iconv('UTF-8','ISO-8859-2',$_POST["ertek"]);
		$result = mysql_query("INSERT INTO $data_table (ertek, status) VALUES('" . $ertek_string . "', '" . $_POST["status"] . "');");
		
		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM $data_table WHERE sorszam = LAST_INSERT_ID();");
		$row = mysql_fetch_array($result);
        $row['ertek']=iconv('ISO-8859-2','UTF-8',$row['ertek']);
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
        
		print json_encode($jTableResult);
	}
	//Updating a record (updateAction)
	else if($_GET["action"] == "update")
	{
		//Update record in database
        $ertek_string =iconv('UTF-8','ISO-8859-2',$_POST["ertek"]);
		$result = mysql_query("UPDATE $data_table SET ertek = '" . $ertek_string . "', status = " . $_POST["status"] . " WHERE sorszam = " . $_POST["sorszam"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{
		//Delete from database
		$result = mysql_query("DELETE FROM $data_table WHERE sorszam = " . $_POST["sorszam"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}

	//Close database connection
	mysql_close($con);

}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}
	
?>