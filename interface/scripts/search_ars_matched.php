<?php
session_start();

if (empty($_SESSION['user_name'])) {
	header('Location: index.php');
	exit;
}

require '../../confs/ars-variables.php';

$matchedsearch = escapeshellcmd($_POST['matchedsearch']);
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
	$result_count_search = $db->query("SELECT COUNT(*) FROM `customers` INNER JOIN `routes` ON customers.cus_id=routes.route_cus_id WHERE  UPPER(`cus_name`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_dev`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_intshort`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_intlong`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`cus_email`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_intlong`) LIKE UPPER('%" . $matchedsearch . "%') OR `route_route` LIKE '%" . $matchedsearch . "%'");
	$num_rows = $result_count_search->fetchColumn();

	$get10numberMax = floor($num_rows / 10);
	$get10numberMaxPg = floor(ceil($num_rows / 10));

	if ($get10numberPrevious <= 0) {
		$get10numberPrevious = 0;
	}

	/*
	//When Max is divisible by 10. So I wont create an empty list.
	if (($get10numberMax % 10) == 0) {
	$get10numberMax = $get10numberMax - 1;
	}
	 */

	// Create list for page
	if (empty($matchedsearch)) {
		echo "<h3 style='color:#3c88c6'>Please enter something into the search field above.</h3>";
	}
	else {
		$result_search = $db->query("SELECT customers.cus_id, customers.cus_name, customers.desc_dev, customers.desc_intlong, customers.desc_intshort, customers.cus_email, routes.route_route, routes.route_type, routes.route_dev, routes.route_intlong FROM `customers` INNER JOIN `routes` ON customers.cus_id=routes.route_cus_id WHERE  UPPER(`cus_name`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_dev`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_intshort`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_intlong`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`cus_email`) LIKE  UPPER('%" . $matchedsearch . "%') OR  UPPER(`desc_intlong`) LIKE UPPER('%" . $matchedsearch . "%') OR `route_route` LIKE '%" . $matchedsearch . "%' OR UPPER(`cus_email`) LIKE UPPER('%" . $matchedsearch . "%') ORDER BY cus_name REGEXP '^\d*[^\da-z&\.\' \-\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, cus_name+0, cus_name LIMIT " . $get10number . "0,10");
		$result_search->execute();

		switch ($get10number) {
			case $get10numberMax :
				echo "<div id='list-matched-search-10'>";
				echo "<img id='get10search-arrow-first' value='0' onclick='get10scfm();' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img id='get10search-arrow-prev' value='" . $get10numberPrevious . "' onclick='get10scpm();' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> </br>";
				echo "<i style='font-weight: bold; color: #fb6d51'>$num_rows</i> matching entries found. (Pg. $get10numberMaxPg of $get10numberMaxPg)</br>";
				echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Type</th><th style='text-align: center' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Email Address</th><th style='text-align: center' class='text-regular text-dark big'>Violations</th></tr>";

				foreach($result_search as $row) {
					echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['cus_name'] . "</div></td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['desc_dev'] . "</td><td style='text-align:center'>" . $row['desc_intshort'] . " | " . $row['desc_intlong'] . "</td><td class='text-bold text-primary p' style='text-align:center'><div class='email_link' id='editemail-" . $row['cus_id'] . "'><a href='#/' onclick='ece(" . $row['cus_id'] . ");'>" . $row['cus_email'] . "</a></div><div class='hide-input' id='ei-" . $row['cus_id'] . "'><img id='deleteemail-" . $row['cus_id'] . "' class='form-submit' onclick='dce(" . $row['cus_id'] . ");' src='images/delete.svg' height='24' width='24' alt='delete email'> <input type='email' name=" . $row['cus_id'] . " value='" . $row['cus_email'] . "'> <img id='saveemail-" . $row['cus_id'] . "' class='form-submit' onclick='sce(" . $row['cus_id'] . ");' src='images/save.svg' height='24' width='24' alt='save email'></div></td><td style='text-align:center'>" . $row['cus_violations'] . "</td></tr>";
				}

				echo "</table></div>";
				echo "</div>";
				break;

			case 0 :
				echo "<div id='list-matched-search-10'>";
				echo "<img id='get10search-arrow-next' value='1' onclick='get10scnm();' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img id='get10search-arrow-last' value='" . $get10numberMax . "' onclick='get10sclm();' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br>";
				echo "<i style='font-weight: bold; color: #fb6d51'>$num_rows</i> matching entries found. (Pg. $get10numberNext of $get10numberMaxPg)</br>";
				echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Type</th><th style='text-align: center' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Email Address</th><th style='text-align: center' class='text-regular text-dark big'>Violations</th></tr>";

				foreach($result_search as $row) {
					echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['cus_name'] . "</div></td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['desc_dev'] . "</td><td style='text-align:center'>" . $row['desc_intshort'] . " | " . $row['desc_intlong'] . "</td><td class='text-bold text-primary p' style='text-align:center'><div class='email_link' id='editemail-" . $row['cus_id'] . "'><a href='#/' onclick='ece(" . $row['cus_id'] . ");'>" . $row['cus_email'] . "</a></div><div class='hide-input' id='ei-" . $row['cus_id'] . "'><img id='deleteemail-" . $row['cus_id'] . "' class='form-submit' onclick='dce(" . $row['cus_id'] . ");' src='images/delete.svg' height='24' width='24' alt='delete email'> <input type='email' name=" . $row['cus_id'] . " value='" . $row['cus_email'] . "'> <img id='saveemail-" . $row['cus_id'] . "' class='form-submit' onclick='sce(" . $row['cus_id'] . ");' src='images/save.svg' height='24' width='24' alt='save email'></div></td><td style='text-align:center'>" . $row['cus_violations'] . "</td></tr>";
				}

				echo "</table></div>";
				echo "</div>";
				break;

			default :
				echo "<div id='list-matched-search-10'>";
				echo "<img id='get10search-arrow-first' value='0' onclick='get10scfm();' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img id='get10search-arrow-prev' value='" . $get10numberPrevious . "' onclick='get10scpm();' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> <img id='get10search-arrow-next' value='" . $get10numberNext . "' onclick='get10scnm();' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img id='get10search-arrow-last' value='" . $get10numberMax . "' onclick='get10sclm();' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br>";
				echo "<i style='font-weight: bold; color: #fb6d51'>$num_rows</i> matching entries found. (Pg. $get10numberNext of $get10numberMaxPg)</br>";
				echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Type</th><th style='text-align: center' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Email Address</th><th style='text-align: center' class='text-regular text-dark big'>Violations</th></tr>";

				foreach($result_search as $row) {
					echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['cus_name'] . "</div></td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['desc_dev'] . "</td><td style='text-align:center'>" . $row['desc_intshort'] . " | " . $row['desc_intlong'] . "</td><td class='text-bold text-primary p' style='text-align:center'><div class='email_link' id='editemail-" . $row['cus_id'] . "'><a href='#/' onclick='ece(" . $row['cus_id'] . ");'>" . $row['cus_email'] . "</a></div><div class='hide-input' id='ei-" . $row['cus_id'] . "'><img id='deleteemail-" . $row['cus_id'] . "' class='form-submit' onclick='dce(" . $row['cus_id'] . ");' src='images/delete.svg' height='24' width='24' alt='delete email'> <input type='email' name=" . $row['cus_id'] . " value='" . $row['cus_email'] . "'> <img id='saveemail-" . $row['cus_id'] . "' class='form-submit' onclick='sce(" . $row['cus_id'] . ");' src='images/save.svg' height='24' width='24' alt='save email'></div></td><td style='text-align:center'>" . $row['cus_violations'] . "</td></tr>";
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
