<?php
	session_start();
	echo 'test';
	$usr = $_SESSION['username'];
	echo $usr;
	print_r($_SESSION);