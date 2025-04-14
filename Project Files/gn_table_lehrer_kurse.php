<?php

echo 
'<div id="tableTitle">Kurstabelle <span id="boldTitle">'.$title.'</span></div>';
echo 
'<table cellspacing="0" id="notenTabelle">
	<thead>
		<tr>
			<th>Kurs</th>
			<th>Klasse</th>
			<th>Anzahl Tests</th>
		</tr>
	</thead>
	<tbody>';

$query = 
'SELECT 
	ku.name AS kursname, 
	ku.id AS kursid, 
	kl.name AS klassenname,
	IF(t.id IS NULL, 0, COUNT(*)) AS anzahltests
FROM kurs AS ku
	JOIN klasse AS kl 
		ON ku.klasse = kl.id
	LEFT JOIN test AS t 
		ON t.kursid = ku.id
WHERE ku.lehrer = '.$_SESSION["userid"].' 
GROUP BY ku.id 
ORDER BY klassenname';

$res = mysql_query($query);

while($row = mysql_fetch_assoc($res))
{
	echo 
		'<tr>
			<td><a href="'.$to."=".$row["kursid"].'">'.$row["kursname"].'</a></td>
			<td>'.$row["klassenname"].'</td>
			<td>'.$row["anzahltests"].'</td>
		</tr>';
}

echo
	'</tbody>
</table>';
	
?>