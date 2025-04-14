<?php

// TODO: switch(kind)

// Wurde Antworten-Formular verwendet?
// -> Antwort in Datenbank speichern
if(isset($_POST["btn_submit"]))
{
	$query = "
	INSERT INTO nachricht 
	(
		id,
		replyid,
		message,
		betreff,
		fromid,
		toid,
		zeit,
		kind
	)
	VALUES 
	(
		NULL,  
		'".$_POST["replyid"]."',  
		'".trim($_POST["tb_msg"])."',  
		'".$_POST["betreff"]."',  
		'".$_SESSION["userid"]."',  
		'".$_POST["toid"]."',  
		NOW(),  
		'1'
	)";

	mysql_query($query);
	
	if(isset($_POST["attachments"]) && strlen($_POST["attachments"]) > 0)
	{
		$attachment = json_decode($_POST["attachments"]);
		$attachment = implode(",", $attachment);

		$query = "UPDATE attachment SET contextid = ".mysql_insert_id()." WHERE id IN (".$attachment.")";

		mysql_query($query);
	}

	// Wenn der Partner Nachricht gelöscht hat, lass wieder anzeigen
	$query = "
	DELETE FROM nachrichtremove WHERE mid = ".$_POST["nullid"];

	mysql_query($query);
}

// Anfangsnachricht einholen
$query = 
"SELECT 
	n.*,
	ufrom.username AS fromname,
	uto.username AS toname
FROM 
	nachricht AS n
INNER JOIN 
	user AS ufrom ON ufrom.id = n.fromid
INNER JOIN 
	user AS uto ON uto.id = n.toid
WHERE n.replyid IS NULL AND n.id = ".$firstID." 
AND (n.fromid = ".$_SESSION["userid"]." OR n.toid = ".$_SESSION["userid"].")";

$res = mysql_query($query);

// (Anfangsnachricht) GET-NachrichtID validieren
if(mysql_num_rows($res))
{
	// Anfangsnachricht-Daten einholen
	$row_first = mysql_fetch_assoc($res);

	$id_last = $row_first["id"];

	// Nachrichten in Array
	$arrayp = array();
	// Nachrichten IDs in Array
	$arrayid = array();

	$pos_right = $row_first["fromid"] == $_SESSION["userid"];

	// Anfangsnachricht aufbauen
	$print = 
	'<div class="nachricht_container">
		<div class="';
		$print .= $pos_right ? 'nachricht_from_right">' : 'nachricht_from">';
		$print .=
			'<span class="nachticht_from_span">'.$row_first["fromname"].'</span>
		</div>
		<div class="nachricht_body_container">';
		if($pos_right)
			$print .=
			'<div class="nachricht_body_message">';
			$print .=
				'<span class="nachricht_zeit_span">'
					.$row_first["zeit"].
				'</span>
				<br><br>'
				.nl2br(htmlspecialchars($row_first["message"]));
		if($pos_right)
			$print .=
			'</div>
			<div style="clear:both;"></div>';
		
		$print .=
			'<div class="attachments_container">';

				$query = "SELECT * FROM attachment WHERE contextkind = 1 AND contextid = ".$row_first["id"];
				$r_a = mysql_query($query);

				while($row_a = mysql_fetch_assoc($r_a))
				{
					$print .= '<a href="/useruploads/'. $row_first["fromname"] . "/" . $row_a["path"].'" target="_blank">'.$row_a["path"].'</a><br>';
				}

		$print .=
			'</div>
		</div>
	</div>';

	// und in Array speichern
	array_push($arrayp, $print);
	// NachrichtID speichern
	array_push($arrayid, $row_first["id"]);

	// Nächst höhere Nachticht im Baum abspeichern
	$searchid = $row_first["id"];
	
	// Nachfolgende Nachrichten einholen
	while(true)
	{
		// User der Anfangsnachrichten als WHERE clause
		$querry = 
		"SELECT 
			n.*,
			ufrom.username AS fromname,
			uto.username AS toname
		FROM 
			nachricht AS n
		INNER JOIN 
			user AS ufrom ON ufrom.id = n.fromid
		INNER JOIN 
			user AS uto ON uto.id = n.toid 
		WHERE n.replyid = ".$searchid." 
		AND (n.fromid = ".$row_first["fromid"]." OR n.fromid = ".$row_first["toid"].") AND (n.toid = ".$row_first["fromid"]." OR n.toid = ".$row_first["toid"].")";

		$r = mysql_query($querry);

		// Wenn keine obere Nachricht gefunden, Rekursion abbrechen
		if(mysql_num_rows($r) <= 0)
		{
			break;
		}

		// Daten der Nachricht einholen
		$row = mysql_fetch_assoc($r);

		// Position des Name-Headers einer Nachricht
		$pos_right = $row["fromid"] == $_SESSION["userid"];

		// Nachricht aufbauen
		$print = 
		'<div class="nachricht_container">
			<div class="';
			$print .= $pos_right ? 'nachricht_from_right">' : 'nachricht_from">';
			$print .=
				'<span class="nachticht_from_span">'.$row["fromname"].'</span>
			</div>
			<div class="nachricht_body_container">';
			if($pos_right)
				$print .=
				'<div class="nachricht_body_message">';
				$print .=
					'<span class="nachricht_zeit_span">'
						.$row["zeit"].
					'</span>
					<br><br>'
					.nl2br(htmlspecialchars($row["message"]));
			if($pos_right)
				$print .=
				'</div>
				<div style="clear:both;"></div>
				';
			$print .=
				'<div class="attachments_container">';

				$query = "SELECT * FROM attachment WHERE contextkind = 1 AND contextid = ".$row["id"];
				$r_a = mysql_query($query);

				while($row_a = mysql_fetch_assoc($r_a))
				{
					$print .= '<a href="/useruploads/'. $row["fromname"] . "/" . $row_a["path"].'" target="_blank">'.$row_a["path"].'</a><br>';
				}

			$print .=
				'</div>
			</div>
		</div>';

		// und in Array abspeichern
		array_unshift($arrayp, $print);
		// NachrichtID speichern
		array_unshift($arrayid, $row["id"]);
	
		// ID für die nächste Rekursion abspeichern
		$searchid = $row["id"];
	} 

	// ID der obersten Nachricht abspeichern
	$id_first = $arrayid[0];

	// Antworten-Button
	echo 
	'<div id="reply_show_button" class="button_table" onclick="toggleReplyField()">
		Antworten
	</div>';

	// Löschen-Button
	echo 
	'<a href="messages.php?delmsg='.$_GET['msgid'].'">
		<div id="reply_show_button" class="button_table">
			Löschen
		</div>
	</a>';

	// toid speichern
	if($row_first["fromid"] == $_SESSION["userid"])
	{
		$u = $row_first["toid"];
	}
	else if($row_first["toid"] == $_SESSION["userid"])
	{
		$u = $row_first["fromid"];
	}

	// Formular: Antworten
	echo 
	'<div class="nachricht_container" id="reply_container_main">
		<div class="nachricht_from">
			<span class="nachticht_from_span">Antworten <a href="javascript:toggleReplyField()">x</a></span>
		</div>
		<div class="nachricht_body_container">
			<form id="form_reply_msg" action="messages.php?msgid='.$_GET["msgid"].'" method="post">
				<input type="hidden" name="replyid" value="'.$id_first.'">
				<input type="hidden" name="nullid" value="'.$id_last.'">
				<input type="hidden" name="betreff" value="'.$row_first["betreff"].'">
				<input type="hidden" name="toid" value="'.$u.'">
	 			<textarea name="tb_msg" id="tb_msg" wrap="hard"></textarea>'
	 			.gn_print_attachment_form().'
	 			<input type="submit" id="btn_submit" class="button_table" name="btn_submit">
	 		</form>
		</div>
	</div>';

	// Array mit Nachrichten ausgeben
	foreach($arrayp as $p)
	{
		echo $p;
	}
}
else
{
	echo "Diese Nachricht gehört nicht ihnen!";
}
?>