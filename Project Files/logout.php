<?php
	include('inc_gymnetz.php');

	session_start();
	session_destroy();

	$_SESSION = array();

	gn_require_login();
?>