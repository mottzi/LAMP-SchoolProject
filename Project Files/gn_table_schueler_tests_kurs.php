<?php

$query = 'SELECT name FROM kurs WHERE id = '.$_GET["kurs"];
$res = mysql_query($query);
$row = mysql_fetch_assoc($res);

echo 
'<div id="tableTitle">Prüfungstabelle <span id="boldTitle">'.$row["name"].'</span></div>';
echo 
'<table cellspacing="0" id="notenTabelle">
	<thead>
		<tr>
			<th>Test</th>
			<th>Datum</th>
		</tr>
	</thead>
	<tbody>';

$query = 
'SELECT 
	t.name,
	t.id, 
	t.datum
FROM 
	test AS t
WHERE t.kursid = '.$_GET["kurs"];

$res = mysql_query($query);

while($row = mysql_fetch_assoc($res))
{
	echo 
		'<tr>
			<td>
				<a href="'.$to."=".$row["id"].'">'.$row["name"].'</a>
			</td>
			<td>
			 '.$row["datum"].'
			</td>
			
		</tr>';
}

echo
	'</tbody>
</table>';
	
?>