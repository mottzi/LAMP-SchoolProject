<?php
	include('inc_gymnetz.php');
	session_start();

	gn_require_login();
	gn_mysql_connect();
?>

<html>
	<head>
		<meta charset="utf-8"> 
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="marks.css">
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
				<td class="navigation_main_current" style="border-top-left-radius: 4px;"><a href="marks.php">Noten</a></td>
				<td><a href="tests.php">Prüfungen</a></td>
				<td><a href="index.php">Gym & Netz</a></td>
				<td><a href="messages.php">Nachrichten</a></td>
				<?php gn_add_login_profile_navigation(); ?>
			</table>
		</div>
		<div id="navigation_sub">
			<table id="table_navigation_sub">
				<td class="navigation_sub_current" style="border-bottom-left-radius: 4px;"><a href="marks.php">Noten</a></td>
				<td><a href="#">B</a></td>
				<td style="border-bottom-right-radius: 4px;"><a href="#">C</a></td>
			</table>
		</div>
		<div id="container_content_main">

			<?php
			switch($_SESSION["rights"])
			{
				case SCHUELER:
				{
					echo '<div id="container_table">';

					// Notentabelle Detailiert
					if(isset($_GET["kurs"]) && !empty($_GET["kurs"]))
					{
						gn_table_schueler_noten_kurs("testinfo.php?test");
					}
					// Kurstabelle mit Notendurchschnitt
					else
					{
						gn_table_schueler_kurs_avg("marks.php?kurs");
					}

					echo "</div>";
					break;
				}
				case LEHRER:
				{
					echo '<div id="container_table">';

					// Testübersicht
					if(isset($_GET["kurs"]) && !empty($_GET["kurs"]))
					{
						if(gn_get_lehrer_of_kurs($_GET["kurs"]) == $_SESSION["userid"])
						{
							if(isset($_GET["test"]) && !empty($_GET["test"]))
							{
								gn_table_marks_lehrer();
							}
							else
							{
								gn_table_marks_test_von_kurs();
							}
						}
						else
						{
							echo "Sie sind nicht Lehrer dieses Kurses!";
						}
					}
					// Kursübersicht
					else
					{
						gn_table_lehrer_kurse("Noten", "marks.php?kurs");
					}

					echo "</div>";
					break;
				}
			}
			?>


		</div>
	</body>
</html>