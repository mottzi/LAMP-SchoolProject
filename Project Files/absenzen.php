<?php
	include('inc_gymnetz.php');
	session_start();

	gn_require_login();

	// Kurse zeit -> {"Mon":[1,2],"Tue":[2,3],"Wed":[4,5],"Thu":[6,7],"Fri":[8,9],"Sat":[10,11],"Sun":[12,13]}
	// http://ch1.php.net/manual/en/function.date.php date() with D
	// date("N", mktime(0, 0, 0, 7, 1, 2000));

	/*
	$v = json_decode
		(
			'{"Mon":[1,2],"Tue":[2,3],"Wed":[4,5],"Thu":[6,7],"Fri":[8,9],"Sat":[10,11],"Sun":[12,13]}',
			true
		);
	
	$day = date("D", mktime(0, 0, 0, 3, 2, 2014));

	$array_mit_lektionen = $v[$day];
	*/
?>

<html>
	<head>
		<meta charset="utf-8"> 
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="marks.css">
		<link rel="stylesheet" type="text/css" href="messages.css">
		<link rel="stylesheet" type="text/css" href="attachment.css">
		<link href='http://fonts.googleapis.com/css?family=Della+Respira' rel='stylesheet' type='text/css'>

		<script type="text/javascript">
			function getAjaxObject()
			{
				var httpReq = null;

				if(window.XMLHttpRequest)
				{
					httpReq = new XMLHttpRequest();
				}

				return httpReq;
			}

			function searchKeyUp(str)
			{
				var hintContainer = document.getElementById("searchHints");

				if(str.length == 0)
				{
					hintContainer.style.display = "none";
					return;
				}

				var req = getAjaxObject();

				if(req)
				{
					req.onreadystatechange = function() 
					{
						if(req.readyState == 4 && req.status == 200)
						{
							var a = JSON.parse(req.responseText);
							var l = a.length;

							var tempString;
							hintContainer.innerHTML = "";

							for(var i = 0; i < l; i++)
							{
								tempString = ' \
								<a href="javascript:setReceiver(\''+a[i].name+'\', '+a[i].id+')"> \
									<div class="resultcontainer"> \
										<span class="resuletkind">Schüler</span> '+a[i].name+' \
									</div> \
								</a>';
								hintContainer.innerHTML += tempString;
							}

							if(l > 0)
							{
								hintContainer.style.display = "block";
							}
							else
							{
								hintContainer.style.display = "none";
							}
						}
					};

					req.open("get", "ajax_search_report_absence.php?str=" + str, true);
					req.send(null);
				}
			}

			function setReceiver(name, id)
			{
				var hintContainer = document.getElementById("searchHints");
				hintContainer.style.display = "none";

				var receiverContainer = document.getElementById("sendedToContainer");
				receiverContainer.style.display = "block";

				receiverContainer.innerHTML = ' \
				<div class="resultcontainer"> \
					<span class="resuletkind">Schüler</span> '+name+' \
				</div>';

				var form = document.getElementById("form_report_absence");
				form.innerHTML += '<input type="hidden" name="schuelerid" value="' + id + '">';
			}

			function selectionChanged(element)
			{
				alert(element.value);
			}

		</script>
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
				<td class="navigation_main_current"><a href="absenzen.php">Absenzen</a></td>
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
			<div class="container_form">
				<form id="form_report_absence" action="absenzen.php" method="post">
					<div id="sendedToContainer"></div>
					Suche: <input type="text" id="searchBar" autocomplete="off" onkeyup="searchKeyUp(this.value)">
					<div id="searchHints"></div>
		 			
		 			<br><br>
		 			Datum: <input type="date" name="date" id="datepicker" onchange="selectionChanged(this)">
		 			<br>
		 			<input type="submit" id="submit_absenzen" class="button_table" name="submit_absenzen">
		 		</form>
			</div>
		</div>
	</body>
</html>