<?php
include('inc_gymnetz.php');
gn_mysql_connect();

if(isset($_FILES["f"]))
{
	$a = array();

	session_id($_POST["u"]);
	session_start();

	if(!file_exists(ATTACHMENT_PATH . $_SESSION["username"]))
	{
		mkdir(ATTACHMENT_PATH . $_SESSION["username"], 0777, true);
	}

   	foreach($_FILES["f"]["error"] as $key => $error) 
   	{
		if ($error == UPLOAD_ERR_OK) 
		{
			$path = ATTACHMENT_PATH . $_SESSION["username"] . "/" . $_FILES["f"]['name'][$key];

	   		if(move_uploaded_file($_FILES["f"]['tmp_name'][$key], $path))
	   		{
	   			$query = 
	   			"INSERT INTO attachment VALUES 
	   			(
	   				'',
	   				'',
	   				'".ATTACHMENT_MSG."',
	   				'".$_SESSION["userid"]."',
	   				'".$_FILES["f"]['name'][$key]."'
	   			)";

				mysql_query($query);
				array_push($a, mysql_insert_id());
	   		}
	   	}
   	}

   	echo json_encode($a);
}
?>