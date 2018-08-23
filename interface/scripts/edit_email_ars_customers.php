<?php
session_start();

if (empty($_SESSION['user_name'])) {
	header('Location: index.php');
	exit;
}

require '../../confs/ars-variables.php';

$cusid = escapeshellcmd($_POST['cusid']);
$editcusemail = escapeshellcmd($_POST['editcusemail']);

try {
	/**************************************
	 * Create databases and                *
	 * open connections                    *
	 **************************************/

	// Create (connect to) SQLite database in file
	$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Set errormode to exceptions
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// validate email address
	$ve = $db->query("SELECT `cus_email` FROM `customers` WHERE `cus_id` = '" . $cusid . "'");
	$fve = $ve->fetchColumn();

	// Check if input is emtpy or space then fill with empty
	if ((filter_var($editcusemail, FILTER_VALIDATE_EMAIL)) == false) {
		$editcusemail = $fve;
	}

	// Edit email address
	$ee = $db->query("UPDATE `customers` SET `cus_email` = '" . $editcusemail . "' WHERE `customers`.`cus_id` ='" . $cusid . "'");
	$ee->execute();

	$ge = $db->query("SELECT `cus_email` FROM `customers` WHERE `cus_id` = '" . $cusid . "'");
	$fge = $ge->fetchColumn();

	// spit out replace code.
	echo "<a href='#/' onclick='ece($cusid);'>$fge</a>";

	/**************************************
	 * Close db connections                *
	 **************************************/

	// Close file db connection
	$db = null;
}
catch(PDOException $e) {
	// Print PDOException smam
	echo $e->getMessage();
}

?>
