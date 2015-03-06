<?php

		# start the session
	ob_start();
	error_reporting(E_ALL);
	
	# read any flash message before unset
	session_start();
	session_unset();
	session_destroy();
	
	session_start();
	$_SESSION['flash-message'] = array("type" => "notice", "msg" => "You have logged out successfully");
	header("Location: login.php");
	
?>