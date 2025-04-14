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
		<link rel="stylesheet" type="text/css" href="messages.css">
		<link rel="stylesheet" type="text/css" href="attachment.css">
		<link href='http://fonts.googleapis.com/css?family=Della+Respira' rel='stylesheet' type='text/css'>

		<!--<script type="text/javascript" src="attachment.js"></script>-->

		<script type="text/javascript">
			var attachments_input = new Array();

			function upload(element)
			{
				var ul = "";
				var data = new FormData();
				var _file = element;

				var bar = element.parentElement.getElementsByTagName('div')[0];
				bar.style.width = "0px";
				bar.style.display = "block";

			    for(var j = 0; j < _file.files.length; j++)
			    {
			    	data.append('f[]', _file.files[j]);
			    	ul += "<li>" + _file.files[j].name + "</li>";
			    }

			    var list = document.createElement('ol');
			    list.innerHTML = ul;
			    list.style.listStyleType = "decimal";
			    list.style.margin = "20px";

			    element.parentElement.appendChild(list);
				element.parentElement.style.width = "300px";
			    element.parentElement.getElementsByTagName('span')[0].style.display = "none";

			    var request = new XMLHttpRequest();

			    request.onreadystatechange = function()
			    {
			        if(request.readyState == 4 && request.status == 200)
			        {
			            var resp = JSON.parse(request.responseText);

			            attachments_input = attachments_input.concat(resp);

			            var json_new = JSON.stringify(attachments_input);

			            var inputfield = document.getElementById("attachments");

			            if(document.contains(inputfield))
			            {
			            	inputfield.value = json_new;
			            }
			            else
			            {
			            	var input = document.createElement("input");
			            	input.setAttribute("id", "attachments");
			            	input.setAttribute("name", "attachments");
							input.type = "hidden";
							input.value = json_new;

							var form = document.getElementById("form_new_msg");
							
							if(document.contains(form))
							{
								form.appendChild(input);
							}
							else
							{
								var form = document.getElementById("form_reply_msg");
								
								if(document.contains(form))
								{
									form.appendChild(input);
								}
							}
			            }
			        }
			    };

			    request.upload.addEventListener('progress', function(e)
			    {
			    	var _progress = element.parentElement.getElementsByTagName('div')[0];
			    	var val = Math.ceil(e.loaded / e.total * 100)

			        _progress.innerHTML =  val + '%';
			        _progress.style.width = val + '%';
			    }, false);

			    <?php echo 'data.append("u", "'.session_id().'");'; ?>
			    
			    request.open('POST', 'ajax_upload_files_msg.php');
			    request.send(data);
			}

			function selectionChanged(element) 
			{
				var filecount = element.files.length;
				
				if(filecount)
				{
					upload(element);
					element.style.display = "none";
				}
			}
		</script>

		<script type="text/javascript">
			function toggleReplyField()
			{
				var replyContainer = document.getElementById("reply_container_main");
				var replyButton = document.getElementById("reply_show_button");

				if(replyContainer.style.display == "none" || replyContainer.style.display == "")
				{
					document.getElementById("reply_container_main").style.display = "block";
					document.getElementById("reply_show_button").style.display = "none";
				}
				else
				{
					document.getElementById("reply_container_main").style.display = "none";
					document.getElementById("reply_show_button").style.display = "";
				}
			}

			function is_new_msg_page() 
			{
  				return decodeURI(window.location.search).indexOf("?newmsg") != -1;
			}

			var bezeichnungen = new Array('', '', 'Lehrer', 'Schüler', 'Klasse');

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
								<a href="javascript:addToReceiverList(\''+a[i].name+'\', '+a[i].id+', '+a[i].kind+')"> \
									<div class="resultcontainer"> \
										<span class="resuletkind">'+bezeichnungen[a[i].kind]+'</span> '+a[i].name+' \
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
					}

					req.open("get", "ajax_search_newmsg.php?str=" + str, true);
					req.send(null);
				}
			}

			function getAjaxObject()
			{
				var httpReq = null;

				if(window.XMLHttpRequest)
				{
					httpReq = new XMLHttpRequest();
				}

				return httpReq;
			}

			var receivers = new Array();

			function addToReceiverList(name, id, kind)
			{
				var found = false;
				var l = receivers.length;

				for(var i = 0; i < l; i++)
				{
					if(receivers[i][1] == id && receivers[i][2] == kind)
					{
						found = true;
						break;
					}
				}

				if(!found)
				{
					receivers[receivers.length] = new Array(name, id, kind);
				
					updateReceiversList();
				}

				clearInputs();
			}

			function updateReceiversList()
			{
				var receiverContainer = document.getElementById("sendedToContainer");
				receiverContainer.innerHTML = "";

				var l = receivers.length;
				var count = 0;

				for(var i = 0; i < l; i++)
				{
					receiverContainer.innerHTML += ' \
					<div class="resultcontainer"> \
						<span class="resuletkind">'+bezeichnungen[receivers[i][2]] +'</span> '+receivers[i][0]+' \
						<a href="javascript:removeFromReceiverList('+receivers[i][1]+', '+receivers[i][2]+')">x</a> \
					</div>';

					count++;
				}

				receiverContainer.innerHTML += '<input type="hidden" name="receivers" value="'+encodeURI(JSON.stringify(receivers))+'">';
				receiverContainer.style.display = count ? "block" : "none";
			}

			function removeFromReceiverList(id, kind)
			{
				var l = receivers.length;

				for(var i = 0; i < l; i++)
				{
					if(receivers[i][1] == id && receivers[i][2] == kind)
					{
						receivers.splice(i, 1);
						break;
					}
				}

				updateReceiversList();
			}

			function clearInputs()
			{
				var hintContainer = document.getElementById("searchHints");
				hintContainer.innerHTML = "";
				hintContainer.style.display = "none";

				var searchField = document.getElementById("searchBar");
				searchField.value = "";
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
				<td class="navigation_main_current"><a href="messages.php">Nachrichten</a></td>
				<?php 
					gn_add_login_profile_navigation(); 
				?>
			</table>
		</div>
		<div id="navigation_sub">
			<table id="table_navigation_sub">
				<td class="navigation_sub_current" style="border-bottom-left-radius: 4px;"><a href="messages.php">Nachrichten</a></td>
				<td><a href="#">A</a></td>
				<td style="border-bottom-right-radius: 4px;"><a href="#">B</a></td>
			</table>
		</div>
		<div id="container_content_main">
		<?php
			if(isset($_POST["btn_submit_msg"]))
			{
				gn_form_send_message();
			}
			else if(isset($_GET["delmsg"]) && !empty($_GET["delmsg"]))
			{
				gn_mark_msg_removed($_GET["delmsg"]);

				// Weiterleiten (wegen Reload-Gefahr)
				echo 
				'<script type="text/javascript">
					window.location.href = "messages.php";
				</script>';
			}
			
			if(isset($_GET["newmsg"]))
			{
				gn_print_new_msg_page();
			}
			else
			{
				gn_print_show_msg_page();
			}
		?>
		</div>
	</body>
</html>