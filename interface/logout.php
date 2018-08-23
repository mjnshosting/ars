<?php
session_start();

if (!empty($_SESSION['user_name'])) {
	session_unset();
	session_destroy();
	header('Location: index.php');
	exit;
    }
?>
