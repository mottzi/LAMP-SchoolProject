<?php
	include('inc_gymnetz.php');

	gn_only_login_once();
	gn_mysql_connect();
	$success = gn_validate_login();
?>

<html>
	<head>
		<meta charset="utf-8"> 
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="style_login.css">
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
				<td><a href="index.php">Gym & Netz</a></td>
				<td><a href="messages.php">Nachrichten</a></td>
				<td class="navigation_main_current" style="border-top-right-radius: 4px;"><a href="login.php">Login</a></td>
			</table>
		</div>
		<div id="navigation_sub">
			<table id="table_navigation_sub">
				<td style="border-bottom-left-radius: 4px;"><a href="#">A</a></td>
				<td><a href="#">B</a></td>
				<td style="border-bottom-right-radius: 4px;"><a href="#">C</a></td>
			</table>
		</div>
		<div id="container_content_main">
			<div id="container_loginbox">
				<ul id="list_loginbox">
					<form method="post" action="login.php">
						<?php
							if($success === false)
							{
								echo '<span id="span_login_failed">Zugriff verweigert!</span>';
							}
						?>
						<li>
							<span>Benutzername</span>
							<input type="text" name="tb_username">
						</li>
						<li>
						<span>Passwort</span>
							<input type="password" name="tb_password">
						</li>

						<input type="submit" id="submit_login" name="btn_submit">
					</form>
				</ul>
			</div>
		</div>
	</body>
</html>