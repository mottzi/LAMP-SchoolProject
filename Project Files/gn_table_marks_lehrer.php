<?php
/*
if(isset($_POST["new_submit"]) && !empty($_POST["new_submit"]))
{
	$_POST["new_newname"] = mysql_real_escape_string($_POST["new_newname"]);
	$_POST["new_newdate"] = mysql_real_escape_string($_POST["new_newdate"]);

	$query = 
	"INSERT INTO 
		test (id, name, kursid, datum)
	VALUES 
		('',
		'".$_POST["new_newname"]."','".
		$_GET["kurs"]."',
		'".$_POST["new_newdate"]."')";

	mysql_query($query);
}*/
if(isset($_POST["edit_submit"]) && !empty($_POST["edit_submit"]))
{
	$_POST["edit_mark"] = mysql_real_escape_string($_POST["edit_mark"]);
	$_POST["edit_userid"] = mysql_real_escape_string($_POST["edit_userid"]);

	if(gn_has_user_note($_GET["test"], $_POST["edit_userid"]))
	{
		$query = 
		"UPDATE 
			note
		SET
			note = '".$_POST["edit_mark"]."'
		WHERE userid = '".$_POST["edit_userid"]."'";
	}
	else
	{
		$query = 
		"INSERT INTO 
			note (id, note, userid, testid)
		VALUES 
			('',
			'".$_POST["edit_mark"]."',
			'".$_POST["edit_userid"]."',
			'".$_GET["test"]."')";
	}
	
	mysql_query($query);
}
/*
else if(isset($_POST["remove_submit"]) && !empty($_POST["remove_submit"]))
{
	$_POST["edit_testid"] = mysql_real_escape_string($_POST["edit_testid"]);

	$query = 
	"DELETE FROM
		test
	WHERE id = ".$_POST["edit_testid"];

	mysql_query($query);
}
*/
$query = 'SELECT name, datum FROM test WHERE id = '.$_GET["test"];
$res = mysql_query($query);
$row = mysql_fetch_assoc($res);

echo 
'<div id="tableTitle">Notentabelle '.$row["datum"].' <span id="boldTitle">'.$row["name"].'</span></div>';
echo 
'<table cellspacing="0" id="notenTabelle">
	<thead>
		<tr>
			<th>Schüler</th>
			<th>Note</th>
			<th>Aktion</th>
		</tr>
	</thead>
	<tbody>';

$query = 
'SELECT 
	u.username,
	u.id, 
	IF(n.note IS NULL, "Keine Note", n.note) AS note
FROM 
	test AS t 
	INNER JOIN kurs AS ku ON ku.id = t.kursid
	INNER JOIN klassenmitglieder As km ON ku.klasse = km.klasse
	INNER JOIN user AS u ON km.userid = u.id
	LEFT JOIN note AS n ON (n.testid = t.id AND n.userid = u.id)
WHERE t.id = '.$_GET["test"].' 
ORDER BY username';

$res = mysql_query($query);

while($row = mysql_fetch_assoc($res))
{
	if(isset($_GET["user"]) && $_GET["user"] == $row["id"])
	{
		echo 
		'<form action="marks.php?kurs='.$_GET["kurs"].'&test='.$_GET["test"].'" method="post">
		<tr>
			<td>
				'.$row["username"].'
			</td>
			<td>
				<input type="text" value="'.$row["note"].'" name="edit_mark">
				<input type="hidden" value="'.$_GET["user"].'" name="edit_userid">
			</td>
			<td>
				<input type="submit" value="Save" name="edit_submit" class="button_table">
				<input type="submit" value="Remove" name="remove_submit" class="button_table">
			</td>
		</tr>';
	}
	else
	{
		echo 
		'<tr>
			<td>
				'.$row["username"].'
			</td>
			<td>'.$row["note"].'</td>
			<td>
				<div class="button_table">
					<a href="'."marks.php?kurs=".$_GET["kurs"]."&test=".$_GET["test"]."&user=".$row["id"].'">Edit</a>
				</div>
			</td>
		</tr>';
	}
}

echo
	'</tbody>
</table>';
	
?>