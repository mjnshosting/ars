<?php
session_start();

if (empty($_SESSION['user_name'])) {
	header('Location: index.php');
	exit;
}

require '../../confs/ars-variables.php';

$violation_search = escapeshellcmd($_POST['violation_search']);
$get10number = escapeshellcmd($_POST['get10number']);
$get10numberPrevious = $get10number - 1;
$get10numberNext = $get10number + 1;

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
	$result_count_search = $db->query("SELECT COUNT(*) FROM `violations` WHERE  UPPER(`vio_ip`) LIKE  UPPER('%" . $violation_search . "%') OR  UPPER(`vio_from`) LIKE  UPPER('%" . $violation_search . "%') OR  UPPER(`vio_to`) LIKE  UPPER('%" . $violation_search . "%') OR  UPPER(`vio_subject`) LIKE  UPPER('%" . $violation_search . "%') OR  UPPER(`vio_cus_name`) LIKE  UPPER('%" . $violation_search . "%')");
	$num_rows = $result_count_search->fetchColumn();

	$get10numberMax = floor($num_rows / 10);
	$get10numberMaxPg = floor(ceil($num_rows / 10));

	if ($get10numberPrevious <= 0) {
		$get10numberPrevious = 0;
	}

	// Create list for page
	if (empty($violation_search)) {
		echo "<h3 style='color:#3c88c6'>Please enter something into the search field above.</h3>";
	}
	else {
		$result_search = $db->query("SELECT * FROM `violations` WHERE  UPPER(`vio_ip`) LIKE  UPPER('%" . $violation_search . "%') OR  UPPER(`vio_from`) LIKE  UPPER('%" . $violation_search . "%') OR  UPPER(`vio_to`) LIKE  UPPER('%" . $violation_search . "%') OR  UPPER(`vio_subject`) LIKE  UPPER('%" . $violation_search . "%')  OR  UPPER(`vio_cus_name`) LIKE  UPPER('%" . $violation_search . "%') ORDER BY vio_ip REGEXP '^\d*[^\da-z&\.\' \-\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, vio_ip+0, vio_ip LIMIT " . $get10number . "0,10");
		$result_search->execute();

		switch ($get10number) {
			case $get10numberMax :
				echo "<div id='list-violations-search-10'>";
				echo "<img class='form-submit'  id='get10search-arrow-first' value='0' onclick='get10scfv();' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  id='get10search-arrow-prev' value='" . $get10numberPrevious . "' onclick='get10scpv();' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> </br>";
				echo "<i style='font-weight: bold; color: #fb6d51'>$num_rows</i> matching entries found. (Pg. $get10numberMaxPg of $get10numberMaxPg)</br>";
				echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>IP</th><th style='text-align: center' class='text-regular text-dark big'>From</th><th style='text-align: center' class='text-regular text-dark big'>To</th><th style='text-align: center' class='text-regular text-dark big'>Subject</th><th style='text-align: center' class='text-regular text-dark big'>Timestamp</th><th style='text-align: center' class='text-regular text-dark big'>View</th></tr>";

				foreach($result_search as $row) {
					echo "<tr style='text-align:left'><td>" . $row['vio_cus_name'] . "</td><td style='text-align:center'>" . $row['vio_ip'] . "</td><td><div class='vio_email'>" . $row['vio_from'] . "</div></td><td><div class='vio_email'>" . $row['vio_to'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_subject'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_timestamp'] . "</div></td><td><img class='form-submit'  onclick='viewvio(" . $row['vio_id'] . ");' src='images/envelope.svg' height='24' width='24' alt='view violation entry' class='form-submit'></td></tr>";
				}

				echo "</table></div>";
				echo "</div>";
				break;

			case 0 :
				echo "<div id='list-violations-search-10'>";
				echo "<img class='form-submit'  id='get10search-arrow-next' value='1' onclick='get10scnv();' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  id='get10search-arrow-last' value='" . $get10numberMax . "' onclick='get10sclv();' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br>";
				echo "<i style='font-weight: bold; color: #fb6d51'>$num_rows</i> matching entries found. (Pg. $get10numberNext of $get10numberMaxPg)</br>";
				echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>IP</th><th style='text-align: center' class='text-regular text-dark big'>From</th><th style='text-align: center' class='text-regular text-dark big'>To</th><th style='text-align: center' class='text-regular text-dark big'>Subject</th><th style='text-align: center' class='text-regular text-dark big'>Timestamp</th><th style='text-align: center' class='text-regular text-dark big'>View</th></tr>";

				foreach($result_search as $row) {
					echo "<tr style='text-align:left'><td>" . $row['vio_cus_name'] . "</td><td style='text-align:center'>" . $row['vio_ip'] . "</td><td><div class='vio_email'>" . $row['vio_from'] . "</div></td><td><div class='vio_email'>" . $row['vio_to'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_subject'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_timestamp'] . "</div></td><td><img class='form-submit'  onclick='viewvio(" . $row['vio_id'] . ");' src='images/envelope.svg' height='24' width='24' alt='view violation entry' class='form-submit'></td></tr>";
				}

				echo "</table></div>";
				echo "</div>";
				break;

			default :
				echo "<div id='list-violations-search-10'>";
				echo "<img class='form-submit'  id='get10search-arrow-first' value='0' onclick='get10scfv();' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  id='get10search-arrow-prev' value='" . $get10numberPrevious . "' onclick='get10scpv();' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> <img class='form-submit'  id='get10search-arrow-next' value='" . $get10numberNext . "' onclick='get10scnv();' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  id='get10search-arrow-last' value='" . $get10numberMax . "' onclick='get10sclv();' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br>";
				echo "<i style='font-weight: bold; color: #fb6d51'>$num_rows</i> matching entries found. (Pg. $get10numberNext of $get10numberMaxPg)</br>";
				echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>IP</th><th style='text-align: center' class='text-regular text-dark big'>From</th><th style='text-align: center' class='text-regular text-dark big'>To</th><th style='text-align: center' class='text-regular text-dark big'>Subject</th><th style='text-align: center' class='text-regular text-dark big'>Timestamp</th><th style='text-align: center' class='text-regular text-dark big'>View</th></tr>";

				foreach($result_search as $row) {
					echo "<tr style='text-align:left'><td>" . $row['vio_cus_name'] . "</td><td style='text-align:center'>" . $row['vio_ip'] . "</td><td><div class='vio_email'>" . $row['vio_from'] . "</div></td><td><div class='vio_email'>" . $row['vio_to'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_subject'] . "</div></td><td><div class='few_chars_vio'>" . $row['vio_timestamp'] . "</div></td><td><img class='form-submit'  onclick='viewvio(" . $row['vio_id'] . ");' src='images/envelope.svg' height='24' width='24' alt='view violation entry' class='form-submit'></td></tr>";
				}

				echo "</table></div>";
				echo "</div>";
				break;
		}
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
