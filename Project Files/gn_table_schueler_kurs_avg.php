<?php

echo 
'<div id="tableTitle">Kurstabelle <span id="boldTitle">Übersicht</span></div>';
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
	'SELECT 
		AVG(note) AS durchschnitt
	FROM note AS n
		JOIN user AS u 
			ON n.userid = u.id
		JOIN test AS t 
			ON t.id = n.testid
		JOIN kurs AS ku 
			ON ku.id = t.kursid
	WHERE ku.id = '.$row["kursid"].' AND u.id = '.$_SESSION["userid"];

	$res_avg = mysql_query($query);
	$row_avg = mysql_fetch_assoc($res_avg);

	if(is_null($row_avg["durchschnitt"]))
		$out = "Keine Noten";
	else
		$out = $row_avg["durchschnitt"];

	echo 
		'<tr>
			<td><a href="'.$to."=".$row["kursid"].'">'.$row["kursname"].'</a></td>
			<td>'.$out.'</td>
		</tr>';
}

echo
	'</tbody>
</table>';
	
?>