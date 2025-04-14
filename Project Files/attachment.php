<?php
Header("content-type: application/x-javascript");
?>

alert("hi");
var _submit = document.getElementById('submit'); 
            var list = document.getElementsByClassName("upload");

            var attachments_input = new Array();

            var upload = function(element)
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
                                alert("NEW MSG JSON: " + input.value);
                            }
                            else
                            {
                                var form = document.getElementById("form_reply_msg");
                                console.log("X");
                                if(document.contains(form))
                                {
                                    form.appendChild(input);
                                    alert("REPLY MSG JSON: " + input.value);
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

                request.upload.addEventListener('load', function()
                {
                    //var div = element.parentElement;
                });

                <?php echo 'data.append("u", "'.session_id().'");'; ?>
                
                request.open('POST', 'upload.php');
                request.send(data);
            }

            //_submit.addEventListener('click', upload);
            function selectionChanged(element) 
            {
                var filecount = element.files.length;
                
                if(filecount)
                {
                    upload(element);
                    element.style.display = "none";
                }
            }