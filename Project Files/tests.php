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
				<td style="border-top-left-radius: 4px;"><a href="marks.php">Noten</a></td>
				<td class="navigation_main_current"><a href="tests.php">Prüfungen</a></td>
				<td><a href="index.php">Gym & Netz</a></td>
				<td><a href="messages.php">Nachrichten</a></td>
				<?php gn_add_login_profile_navigation(); ?>
			</table>
		</div>
		<div id="navigation_sub">
			<table id="table_navigation_sub">
				<td class="navigation_sub_current" style="border-bottom-left-radius: 4px;"><a href="tests.php">Prüfungen</a></td>
				<td><a href="#">A</a></td>
				<td style="border-bottom-right-radius: 4px;"><a href="#">B</a></td>
			</table>
		</div>
		<div id="container_content_main">

			<?php
			switch($_SESSION["rights"])
			{
				case SCHUELER:
				{
					echo '<div id="container_table">';

					// Notentabelle des Kurses detailiert
					if(isset($_GET["kurs"]) && !empty($_GET["kurs"]))
					{
						gn_table_schueler_tests_kurs("testinfo.php?test");
					}
					// Kurstabelle mit Anzahl Tests
					else
					{
						gn_table_schueler_kurs_testcount("tests.php?kurs");
					}

					echo "</div>";
					break;
				}
				case LEHRER:
				{
					echo '<div id="container_table">';
					// Testtabelle
					if(isset($_GET["kurs"]) && !empty($_GET["kurs"]))
					{
						// Validiere User
						if(gn_get_lehrer_of_kurs($_GET["kurs"]) == $_SESSION["userid"])
						{
							// Testtabelle Edit
							gn_table_lehrer_tests_von_kurs();	

							// New Test Button
							if(!isset($_GET["newtest"]))
							{
								gn_button_new_test();
							}	
							// New Test Form
							else
							{
								gn_form_new_test();
							}
						}
						else
						{
							echo "Sie sind nicht Lehrer dieses Kurses!";
						}
					}
					// Kurstabelle
					else
					{
						gn_table_lehrer_kurse("Prüfungen", "tests.php?kurs");
					}

					echo "</div>";
					break;
				}
			}
			?>


		</div>
	</body>
</html>