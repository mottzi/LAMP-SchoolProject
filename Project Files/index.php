<?php
	include('inc_gymnetz.php');
	session_start();
?>

<html>
	<head>
		<meta charset="utf-8"> 
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href='http://fonts.googleapis.com/css?family=Della+Respira' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="header">
			<table id="table_topright_links">
				<td>AGBs</td>
				<td>Benutzungsbestimmungen</td>
				<td>Funktionen</td>
			</table>
			<div id="container_logo">
				Gym<span style="color: rgb(216, 64, 58);">Netz</span>
			</div>
		</div>
		<div id="navigation_main">
			<table id="table_navigation">
				<td style="border-top-left-radius: 4px;"><a href="marks.php">Noten</a></td>
				<td><a href="tests.php">Prüfungen</a></td>
				<td class="navigation_main_current"><a href="index.php">Gym & Netz</a></td>
				<!--<td><a href="absenzen.php">Absenzen</a></td>-->
				<td><a href="messages.php">Nachrichten</a></td>
				<?php gn_add_login_profile_navigation(); ?>
			</table>
		</div>
		<div id="navigation_sub">
			<table id="table_navigation_sub">
				<td style="border-bottom-left-radius: 4px;"><a href="#">A</a></td>
				<td class="navigation_sub_current"><a href="#">B</a></td>
				<td style="border-bottom-right-radius: 4px;"><a href="#">C</a></td>
			</table>
		</div>
		<div id="container_content_main">
			
		</div>
	</body>
</html>