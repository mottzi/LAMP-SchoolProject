<?php

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
}
else if(isset($_POST["edit_submit"]) && !empty($_POST["edit_submit"]))
{
	$_POST["edit_newname"] = mysql_real_escape_string($_POST["edit_newname"]);
	$_POST["edit_newdate"] = mysql_real_escape_string($_POST["edit_newdate"]);
	$_POST["edit_testid"] = mysql_real_escape_string($_POST["edit_testid"]);

	$query = 
	"UPDATE 
		test
	SET
		name = '".$_POST["edit_newname"]."',
		datum = '".$_POST["edit_newdate"]."'
	WHERE id = '".$_POST["edit_testid"]."'";

	mysql_query($query);
}
else if(isset($_POST["remove_submit"]) && !empty($_POST["remove_submit"]))
{
	$_POST["edit_testid"] = mysql_real_escape_string($_POST["edit_testid"]);

	$query = 
	"DELETE FROM
		test
	WHERE id = ".$_POST["edit_testid"];

	mysql_query($query);
}

$query = 
'SELECT 
	ku.name AS kursname,
	 k.name AS klassenname
FROM kurs AS ku
	JOIN klasse AS k ON ku.klasse = k.id
WHERE ku.id = '.$_GET["kurs"];

$res = mysql_query($query);
$row = mysql_fetch_assoc($res);

echo 
'<div id="tableTitle">Testtabelle <span id="boldTitle">'.$row["kursname"].'</span> '.$row["klassenname"].'</div>';

$query = 
'SELECT 
	t.id AS testid,
	t.name AS testname,
	t.datum AS testdatum,
	AVG(n.note) AS durchschnitt
FROM 
	test AS t
	LEFT JOIN note AS n 
		ON n.testid = t.id
	LEFT JOIN kurs AS k 
		ON k.id = t.kursid
WHERE k.id = '.$_GET["kurs"].' 
GROUP BY t.id 
ORDER BY testdatum DESC';

$res = mysql_query($query);

if(mysql_num_rows($res))
{
echo 
'<table cellspacing="0" id="notenTabelle">
	<thead>
		<tr>
			<th>Test</th>
			<th>Datum</th>
			<th>Durchschnitt</th>
			<th>Aktion</th>
		</tr>
	</thead>
	<tbody>';

while($row = mysql_fetch_assoc($res))
{
	if(is_null($row["durchschnitt"]))
		$out = "Keine Noten";
	else
		$out = $row["durchschnitt"];

	if(isset($_GET["test"]) && $_GET["test"] == $row["testid"])
	{
		echo 
		'<form action="tests.php?kurs='.$_GET["kurs"].'" method="post">
		<tr>
			<td>
				<input type="text" value="'.$row["testname"].'" name="edit_newname">
				<input type="hidden" value="'.$_GET["test"].'" name="edit_testid">
			</td>
			<td>
				<input type="date" value="'.$row["testdatum"].'" name="edit_newdate">
			</td>
			<td>'.$out.'</td>
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
				'.$row["testname"].'
			</td>
			<td>'.$row["testdatum"].'</td>
			<td>'.$out.'</td>
			<td>
				<div class="button_table">
					<a href="'."tests.php?kurs=".$_GET["kurs"]."&test=".$row["testid"].'">Edit</a>
				</div>
			</td>
		</tr>';
	}
}

echo
	'</tbody>
</table>';
}
	
?>