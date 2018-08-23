<?php
session_start();

if (empty($_SESSION['user_name'])) {
	header('Location: index.php');
	exit;
}

require '../../confs/ars-variables.php';

$get100number = escapeshellcmd($_POST['get100number']);
$get100numberPrevious = $get100number - 1;
$get100numberNext = $get100number + 1;

try {
	/**************************************
	 * Create databases and                *
	 * open connections                    *
	 **************************************/

	// Create (connect to) SQLite database in file
	$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Set errormode to exceptions
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Query DB for the amount rows in table.
	$result_count = $db->query('SELECT COUNT(*) FROM violations');
	$num_rows = $result_count->fetchColumn();

	// Create list for page
	//    $result_list = $db->query("SELECT * FROM violations ORDER BY vio_ip REGEXP '^\d*[^\da-z&\.\' \-\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, vio_ip+0, vio_ip LIMIT " . $get100number . "00,100");
	$result_list = $db->query("SELECT * FROM violations ORDER BY vio_id DESC LIMIT " . $get100number . "00,100");
	$get100numberMax = floor($num_rows / 100);

	if ($get100numberPrevious <= 0) {
		$get100numberPrevious = 0;
	}

	switch ($get100number) {
		case $get100numberMax :
			echo "<div id='list-violations-100'>";
			echo "<img class='form-submit'  onclick='get100violation(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  onclick='get100violation(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> violation entries listed in this view </br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>IP</th><th style='text-align: center' class='text-regular text-dark big'>From</th><th style='text-align: center' class='text-regular text-dark big'>To</th><th style='text-align: center' class='text-regular text-dark big'>Subject</th><th style='text-align: center' class='text-regular text-dark big'>Timestamp</th><th style='text-align: center' class='text-regular text-dark big'>View</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td>" . $row['vio_cus_name'] . "</td><td style='text-align:center'>" . $row['vio_ip'] . "</td><td><div class='vio_email'>" . $row['vio_from'] . "</div></td><td><div class='vio_email'>" . $row['vio_to'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_subject'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_timestamp'] . "</div></td><td><img class='form-submit'  onclick='viewvio(" . $row['vio_id'] . ");' src='images/envelope.svg' height='24' width='24' alt='view violation entry' class='form-submit'></td></tr>";
			}
			echo "</table></div>";
			echo "<img class='form-submit'  onclick='get100violation(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'><img class='form-submit'  onclick='get100violation(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> </br></br>";
			echo "...and Mike is cool";
			echo "</div>";
			break;

		case 0 :
			echo "<div id='list-violations-100'>";
			echo "There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> violation entries listed in this view <img class='form-submit'  onclick='get100violation(1);' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100violation(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>IP</th><th style='text-align: center' class='text-regular text-dark big'>From</th><th style='text-align: center' class='text-regular text-dark big'>To</th><th style='text-align: center' class='text-regular text-dark big'>Subject</th><th style='text-align: center' class='text-regular text-dark big'>Timestamp</th><th style='text-align: center' class='text-regular text-dark big'>Options</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td>" . $row['vio_cus_name'] . "</td><td style='text-align:center'>" . $row['vio_ip'] . "</td><td><div class='vio_email'>" . $row['vio_from'] . "</div></td><td><div class='vio_email'>" . $row['vio_to'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_subject'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_timestamp'] . "</div></td><td><img class='form-submit'  onclick='viewvio(" . $row['vio_id'] . ");' src='images/envelope.svg' height='24' width='24' alt='view violation entry' class='form-submit'></td></tr>";
			}

			echo "</table></div>";
			echo "<img class='form-submit'  onclick='get100violation(1);' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100violation(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow' class='form-submit'></br></br>";
			echo "...and Mike is cool";
			echo "</div>";
			break;

		default :
			echo "<div id='list-violations-100'>";
			echo "<img class='form-submit'  onclick='get100violation(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  onclick='get100violation(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> violation entries listed in this view <img class='form-submit'  onclick='get100violation(" . $get100numberNext . ");' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100violation(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>IP</th><th style='text-align: center' class='text-regular text-dark big'>From</th><th style='text-align: center' class='text-regular text-dark big'>To</th><th style='text-align: center' class='text-regular text-dark big'>Subject</th><th style='text-align: center' class='text-regular text-dark big'>Timestamp</th><th style='text-align: center' class='text-regular text-dark big'>Options</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td>" . $row['vio_cus_name'] . "</td><td style='text-align:center'>" . $row['vio_ip'] . "</td><td><div class='vio_email'>" . $row['vio_from'] . "</div></td><td><div class='vio_email'>" . $row['vio_to'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_subject'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_timestamp'] . "</div></td><td><img class='form-submit'  onclick='viewvio(" . $row['vio_id'] . ");' src='images/envelope.svg' height='24' width='24' alt='view violation entry' class='form-submit'></td></tr>";
			}

			echo "</table></div>";
			echo "<img class='form-submit'  onclick='get100violation(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  onclick='get100violation(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> <img class='form-submit'  onclick='get100violation(" . $get100numberNext . ");' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100violation(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow' class='form-submit'></br></br>";
			echo "...and Mike is cool";
			echo "</div>";
			break;
	}

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
