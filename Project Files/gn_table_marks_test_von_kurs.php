<?php

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
echo 
'<table cellspacing="0" id="notenTabelle">
	<thead>
		<tr>
			<th>Test</th>
			<th>Datum</th>
			<th>Durchschnitt</th>
		</tr>
	</thead>
	<tbody>';

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

while($row = mysql_fetch_assoc($res))
{
	if(is_null($row["durchschnitt"]))
		$out = "Keine Noten";
	else
		$out = $row["durchschnitt"];

	echo 
	'<tr>
		<td>
			<a href="marks.php?kurs='.$_GET["kurs"].'&test='.$row["testid"].'">'.$row["testname"].'</a>
		</td>
		<td>'.$row["testdatum"].'</td>
		<td>'.$out.'</td>
	</tr>';
}

echo
	'</tbody>
</table>';
	
?>