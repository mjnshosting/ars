<?php
session_start();

if (empty($_SESSION['user_name'])) {
	header('Location: index.php');
	exit;
}

require '../../confs/ars-variables.php';

$arsid = escapeshellcmd($_POST['arsid']);

try {
	/**************************************
	 * Create databases and                *
	 * open connections                    *
	 **************************************/

	// Create (connect to) SQLite database in file
	$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Set errormode to exceptions
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Create list for page
	$result_list = $db->query("SELECT * FROM violations WHERE `vio_id` = $arsid ");

	echo "<div class='pull-constraint'><h2 style='font-weight: 300; font-size: 33px; line-height: 35px;'><img onclick='backtoviolations();' src='images/first-arrow-left.svg' height='30' width='36' alt='first arrow' style='vertical-align: bottom;' class='form-submit'> violation</h2></div></br>";
	echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>IP</th><th style='text-align: center' class='text-regular text-dark big'>From</th><th style='text-align: center' class='text-regular text-dark big'>To</th><th style='text-align: center' class='text-regular text-dark big'>Subject</th><th style='text-align: center' class='text-regular text-dark big'>Timestamp</th><th style='text-align: center' class='text-regular text-dark big'>Options</th></tr>";
	foreach ($result_list as $result_list) {
		echo "<tr style='text-align:left'><td>" . $result_list['vio_cus_name'] . "</td><td style='text-align:center'>" . $result_list['vio_ip'] . "</td><td><div class='vio_email'>" . $result_list['vio_from'] . "</div></td><td><div class='vio_email'>" . $result_list['vio_to'] . "</div></td><td><div>" . $result_list['vio_subject'] . "</div></td><td><div>" . $result_list['vio_timestamp'] . "</div></td><td><img onclick='delvio(" . $arsid . ");' src='images/delete.svg' height='24' width='24' alt='delete violation entry' class='form-submit'></td></tr>";
		echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Message Body</th>";
		echo "<tr style='text-align:left'><td>" . html_entity_decode($result_list['vio_body']) . "</td></tr></div>";
	}
	echo "</table></div";

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
