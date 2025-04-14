<?php

echo 
'<div id="tableTitle">Kurstabelle <span id="boldTitle">Übersicht</span></div>';
echo 
'<table cellspacing="0" id="notenTabelle">
	<thead>
		<tr>
			<th>Kurs</th>
			<th>Anzahl Tests</th>
		</tr>
	</thead>
	<tbody>';

$query = 
'SELECT 
	ku.id AS kursid, 
    ku.name AS kursname
FROM   
	klassenmitglieder AS km 
    	JOIN user AS u 
         	ON u.id = km.userid 
       	JOIN kursteilnehmer AS kt 
         	ON kt.klasse = km.klasse 
       	JOIN kurs AS ku 
         	ON kt.kursid = ku.id 
WHERE u.id = '.$_SESSION["userid"];

$res = mysql_query($query);

while($row = mysql_fetch_assoc($res))
{
	$query = 
	'SELECT COUNT(*) AS anzahlTests FROM test AS t
	WHERE t.kursid = '.$row["kursid"];

	$res_count = mysql_query($query);
	$row_count = mysql_fetch_assoc($res_count);

	echo 
		'<tr>
			<td><a href="'.$to."=".$row["kursid"].'">'.$row["kursname"].'</a></td>
			<td>'.$row_count["anzahlTests"].'</td>
		</tr>';
}

echo
	'</tbody>
</table>';
	
?>