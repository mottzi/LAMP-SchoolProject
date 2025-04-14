<?php

$query = 'SELECT name FROM kurs WHERE id = '.$_GET["kurs"];
$res = mysql_query($query);
$row = mysql_fetch_assoc($res);

echo 
'<div id="tableTitle">Notentabelle <span id="boldTitle">'.$row["name"].'</span></div>';
echo 
'<table cellspacing="0" id="notenTabelle">
	<thead>
		<tr>
			<th>Kurs</th>
			<th>Durchschnitt</th>
		</tr>
	</thead>
	<tbody>';

$query = 
'SELECT 
	t.name,
	n.note, 
	t.id, 
	t.datum
FROM 
	note AS n
	JOIN user AS u 
		ON u.id = n.userid
	JOIN test as t 
		ON n.testid = t.id
	JOIN kurs AS k 
		ON k.id = t.kursid
WHERE u.id = '.$_SESSION["userid"].' AND k.id = '.$_GET["kurs"];

$res = mysql_query($query);

while($row = mysql_fetch_assoc($res))
{
	echo 
		'<tr>
			<td>'.
				$row["datum"].'<br>
				<a href="'.$to."=".$row["id"].'">'.$row["name"].'</a>
			</td>
			<td>'.$row["note"].'</td>
		</tr>';
}

$query = 
'SELECT 
	AVG(note) AS durchschnitt
FROM note AS n
	JOIN user AS u 
		ON n.userid = u.id
	JOIN test AS t 
		ON t.id = n.testid
	JOIN kurs AS ku 
		ON ku.id = t.kursid
WHERE ku.id = '.$_GET["kurs"].' AND u.id = '.$_SESSION["userid"];

$res_avg = mysql_query($query);
$row_avg = mysql_fetch_assoc($res_avg);

if(is_null($row_avg["durchschnitt"]))
	$out = "Keine Noten";
else
	$out = $row_avg["durchschnitt"];

	echo 
		'<tr>
			<td style="color: rgb(216, 64, 58); font-weight: bold;">Durchschnitt</td>
			<td>'.$out.'</td>
		</tr>';

echo
	'</tbody>
</table>';
	
?>