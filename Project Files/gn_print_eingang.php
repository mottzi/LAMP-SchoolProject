<?php
$query = 
"SELECT 
	n.id,
	n.betreff,
	n.zeit,
	n.fromid,
	n.toid,
	ufrom.username AS fromname,
	uto.username AS toname,
	rm.mid,
	rm.uid
FROM nachricht AS n
INNER JOIN 
	user AS ufrom ON ufrom.id = n.fromid
INNER JOIN 
	user AS uto ON uto.id = n.toid
LEFT JOIN nachrichtremove AS rm ON rm.uid = ".$_SESSION["userid"]." AND rm.mid = n.id
WHERE n.replyid IS NULL AND (n.fromid = ".$_SESSION["userid"]." OR n.toid = ".$_SESSION["userid"].") AND rm.mid IS NULL
ORDER BY n.zeit";

$res = mysql_query($query);

if(mysql_num_rows($res) <= 0)
{
	echo 
	'<div class="msg_eingang_eintrag" style="border-top-left-radius: 3px;">
		<span class="msg_name">Keine Nachrichten</span><br>
		<span class="msg_betreff"></span>
	</div>';
}

while($row = mysql_fetch_assoc($res))
{
	$from = $row["fromid"] == $_SESSION["userid"] ? $row["toname"] : $row["fromname"];
	
	if(isset($_GET["msgid"]) && $row["id"] == $_GET["msgid"])
	{
		echo 
		'<a href="messages.php?msgid='.$row["id"].'">
			<div class="msg_eingang_eintrag" id="msg_current" style="border-top-left-radius: 3px;">
				<span class="msg_name">'.$from.'</span><br>
				<span class="msg_betreff">'.$row["betreff"].'</span>
			</div>
		</a>';
	}
	else
	{
		echo 
		'<a href="messages.php?msgid='.$row["id"].'">
			<div class="msg_eingang_eintrag" style="border-top-left-radius: 3px;">
				<span class="msg_name">'.$from.'</span><br>
				<span class="msg_betreff">'.$row["betreff"].'</span>
			</div>
		</a>';
	}

}
?>