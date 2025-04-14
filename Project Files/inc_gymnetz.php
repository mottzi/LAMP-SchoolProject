<?php

define('SCHUELER', '3');
define('LEHRER', '2');
define('ATTACHMENT_PATH', '/Applications/MAMP/htdocs/useruploads/');
define('ATTACHMENT_MSG', '1');

function gn_mysql_connect()
{
	@mysql_connect('localhost', 'root', 'root');
	mysql_select_db('gymnetz');
}

function gn_validate_login()
{
	if(isset($_POST["btn_submit"]))
	{
		if(isset($_POST["tb_username"]) && !empty($_POST["tb_username"])
		&& isset($_POST["tb_password"]) && !empty($_POST["tb_password"]))
		{
			$_POST["tb_username"] = mysql_real_escape_string($_POST["tb_username"]);
			$query = "SELECT * FROM user WHERE username = '".$_POST["tb_username"]."' AND password = '".md5($_POST["tb_password"])."'";
			
			$res = mysql_query($query);

			if($res)
			{
				if(mysql_num_rows($res) > 0)
				{
					$row = mysql_fetch_assoc($res);

					session_start();
					session_regenerate_id(TRUE);

					$_SESSION["loggedin"] = true;
					$_SESSION["username"] = $row["username"];
					$_SESSION["userid"] = $row["id"];
					$_SESSION["password"] = $row["password"];
					$_SESSION["rights"] = $row["rights"];

					header("Location: profile.php");
					exit;
				}
				else
				{

					return false;
				}
			}
		}
	}
	
	return;
}

function gn_only_login_once()
{
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
	{
		header("Location: profile.php");
		exit;
	}
}

function gn_require_login()
{
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
	{
		header("Location: login.php");
		exit;
	}
}

function gn_add_login_profile_navigation($isProfilePage = false)
{
	
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
	{
		if($isProfilePage)
		{
			echo '<td style="border-top-right-radius: 4px;" class="navigation_main_current"><a href="profile.php">'.$_SESSION["username"].'</a></td>';
		}
		else
		{
			echo '<td style="border-top-right-radius: 4px;"><a href="profile.php">'.$_SESSION["username"].'</a></td>';
		}
	}
	else
	{
		echo '<td style="border-top-right-radius: 4px;"><a href="login.php">Login</a></td>';
	}
}

function gn_table_schueler_kurs_avg($to)
{
	require("gn_table_schueler_kurs_avg.php");
}

function gn_table_schueler_noten_kurs($to)
{
	require("gn_table_schueler_noten_kurs.php");
}
function gn_table_schueler_tests_kurs($to)
{
	require("gn_table_schueler_tests_kurs.php");
}

function gn_table_lehrer_kurse($title, $to)
{
	require("gn_table_lehrer_kurse.php");
}

function gn_table_lehrer_tests_von_kurs()
{
	require("gn_table_lehrer_tests_von_kurs.php");
}

function gn_button_new_test()
{
	echo 
	'<div class="button_table">
		<a href="tests.php?kurs='.$_GET["kurs"].'&newtest">+ Prüfung hinzufügen</a>
	</div>';
}

function gn_form_new_test()
{
	echo 
	'<form method="post" action="tests.php?kurs='.$_GET["kurs"].'">
		<div id="newTestContainer">
			Name: <input type="text" class="newTestInput" name="new_newname">Datum: <input type="date" class="newTestInput" name="new_newdate">
			<input type="submit" value="Save" class="button_table button_newtest_save" name="new_submit">
		</div>
	</form>';
}

function gn_get_lehrer_of_kurs($kurs)
{
	$query =
	"SELECT 
		u.id AS lehrer
	FROM 
		kurs AS k
		INNER JOIN user AS u 
			ON k.lehrer = u.id
	WHERE k.id = ".$kurs;

	$res = mysql_query($query);
	$row = mysql_fetch_assoc($res);

	return $row["lehrer"];
}

function gn_table_marks_test_von_kurs()
{
	require("gn_table_marks_test_von_kurs.php");
}

function gn_table_marks_lehrer()
{
	require("gn_table_marks_lehrer.php");
}

function gn_has_user_note($test, $user)
{
	$query =
	"SELECT * FROM note WHERE testid = ".$test." AND userid = ".$user;

	return mysql_num_rows(mysql_query($query)) > 0 ? true : false;
}

function gn_table_schueler_kurs_testcount($to)
{

	require("gn_table_schueler_kurs_testcount.php");
}

function gn_print_messagetree($firstID)
{
	require("gn_print_messagetree.php");
}

function gn_print_eingang()
{
	require("gn_print_eingang.php");
}

function gn_send_message_to_person($to, $betreff, $msg, $at)
{
	$query = "INSERT INTO nachricht VALUES
	(
		'', 
		NULL, 
		'".trim($msg)."', 
		'".trim($betreff)."', 
		'".$_SESSION['userid']."', 
		'".$to."',
		NOW(),
		'1'
	)";

	mysql_query($query);

	$attachment = json_decode($at);
	$attachment = implode(",", $attachment);

	$query = "UPDATE attachment SET contextid = ".mysql_insert_id()." WHERE id IN (".$attachment.")";

	mysql_query($query);
}

function gn_form_send_message()
{
	$receivers = json_decode(urldecode($_POST["receivers"]));
	$len = count($receivers);
	$msg = mysql_real_escape_string($_POST["tb_msg_new"]);
	$betreff = mysql_real_escape_string($_POST["betreff"]);

	if($len > 0 && strlen($msg) > 0 && strlen($betreff) > 0)
	{
		for($i = 0; $i < $len; $i++)
		{
			switch($receivers[$i][2])
			{
				case 2:
				{
					gn_send_message_to_person($receivers[$i][1], $betreff, $msg, $_POST["attachments"]);
					break;
				}
				case 3:
				{
					gn_send_message_to_person($receivers[$i][1], $betreff, $msg, $_POST["attachments"]);
					break;
				}
				case 4:
				{

					break;
				}
			}
		}
	}
}

function gn_print_new_msg_page()
{
	echo 
	'<div id="msg_container_main">
		<div id="msg_container_eingang">';
				gn_print_eingang();
	echo 
		'</div>
		<div id="msg_container">
			<div class="nachricht_container">
				<div class="nachricht_from">
					<span class="nachticht_from_span">Neue Nachricht senden</a></span>
				</div>
				<div class="nachricht_body_container">
					<form id="form_new_msg" action="messages.php" method="post">
						<div id="sendedToContainer"></div>
						Suche: <input type="text" id="searchBar" autocomplete="off" onkeyup="searchKeyUp(this.value)">
						<div id="searchHints"></div>
			 			
			 			<br>
			 			Betreff: <input type="text" name="betreff" id="betrefftb">
			 			<textarea name="tb_msg_new" id="tb_msg" wrap="hard"></textarea>'
			 			.gn_print_attachment_form().'
			 			<input type="submit" id="btn_submit" class="button_table" name="btn_submit_msg">
			 		</form>
				</div>
			</div>
		</div>
	</div>';
}

function gn_print_show_msg_page()
{
	echo 
	'<div id="msg_container_main">
		<div id="msg_container_eingang">';
			gn_print_eingang();
	echo	
		'<a href="messages.php?newmsg">
				<div class="button_table button_new_msg">+ Neue Nachricht</div>
			</a>				
		</div>
		<div id="msg_container">';
		if(isset($_GET["msgid"]) && !empty($_GET["msgid"]))
		{
			gn_print_messagetree($_GET["msgid"]);
		}
	echo
		'</div>
	</div>';
}

function gn_mark_msg_removed($id)
{
	$query = 
	"SELECT * FROM nachrichtremove WHERE uid != ".$_SESSION["userid"]." AND mid = ".$id;

	$res = mysql_query($query);

	if(mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_assoc($res);
		$query = "DELETE FROM nachrichtremove WHERE id = ".$row["id"];
		mysql_query($query);

		gn_delete_messagetree($id);
	}
	else
	{
		$query = 
		"INSERT INTO nachrichtremove VALUES('', '".$id."', '".$_SESSION["userid"]."')";

		mysql_query($query);
	}
}

function gn_delete_messagetree($first)
{
	$searchid = $first;

	$query = "DELETE FROM nachricht WHERE id = ".$searchid;
	mysql_query($query);

	while(true)
	{
		$query = 
		"SELECT 
			*
		FROM 
			nachricht AS n
		WHERE n.replyid = ".$searchid;

		$res = mysql_query($query) OR die(mysql_error());

		if(mysql_num_rows($res) <= 0)
		{
			break;
		}

		$row = mysql_fetch_assoc($res);
		$searchid = $row["id"];

		$query = "DELETE FROM nachricht WHERE id = ".$searchid;
		mysql_query($query);
	}
}

function gn_print_attachment_form()
{
	return 
	'<div id="form_attach">
   	 	<div class="fileUpload btn-primary">
		    <span>Datei anhängen</span>
		    <input name="f[]" type="file" multiple="multiple" class="upload" onchange="selectionChanged(this)" />
			<div class="progress"></div>
		</div>
	</div>';
}

?>