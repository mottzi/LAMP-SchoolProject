<?php
	include('inc_gymnetz.php');
	gn_mysql_connect();

	// String von messages.php (js)
	$msg = mysql_real_escape_string($_GET["str"]);

	// In dieses Array kommen die Resultate
	$array = array();

	/*
		* Zuerst user duchsuchsen
	*/
	$query = "SELECT * FROM user AS u WHERE u.username LIKE '%$msg%'";

	$res = mysql_query($query);

	if(mysql_num_rows($res))
	{
		while($row = mysql_fetch_assoc($res))
		{
			array_push($array, array("name"=>$row["username"], "id"=>$row["id"], "kind"=>$row["rights"]));
		}
	}

	/*
		* Dann klasse
	*/
	$query = "SELECT * FROM klasse AS k WHERE k.name LIKE '%$msg%'";

	$res = mysql_query($query);

	if(mysql_num_rows($res))
	{
		while($row = mysql_fetch_assoc($res))
		{
			array_push($array, array("name"=>$row["name"], "id"=>$row["id"], "kind"=>4));
		}
	}

	// JSON-Object an AJAX zurückschicken
	echo json_encode($array);
?>