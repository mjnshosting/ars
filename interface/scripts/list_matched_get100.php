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
	$result_count = $db->query('SELECT COUNT(*) FROM customers INNER JOIN routes ON customers.cus_id=routes.route_cus_id');
	$num_rows = $result_count->fetchColumn();

	// Create list for page
	$result_list = $db->query("SELECT customers.cus_id, customers.cus_name, customers.desc_dev, customers.desc_intlong, customers.desc_intshort, customers.cus_email, routes.route_route, routes.route_type, routes.route_dev, routes.route_intlong FROM customers INNER JOIN routes ON customers.cus_id=routes.route_cus_id ORDER BY cus_name REGEXP '^\d*[^\da-z&\.\' \-\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, cus_name+0, cus_name LIMIT " . $get100number . "00,100");
	$get100numberMax = floor($num_rows / 100);

	if ($get100numberPrevious <= 0) {
		$get100numberPrevious = 0;
	}

	switch ($get100number) {
		case $get100numberMax :
			echo "<div id='list-matched-100'>";
			echo "<img onclick='get100matched(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img onclick='get100matched(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> customer entries with matching routes listed in this view </br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Type</th><th style='text-align: center' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Email Address</th><th style='text-align: center' class='text-regular text-dark big'>Violations</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['cus_name'] . "</div></td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['desc_dev'] . "</td><td style='text-align:center'>" . $row['desc_intshort'] . " | " . $row['desc_intlong'] . "</td><td class='text-bold text-primary p' style='text-align:center'><div class='email_link' id='editemail-" . $row['cus_id'] . "'><a href='#/' onclick='ece(" . $row['cus_id'] . ");'>" . $row['cus_email'] . "</a></div><div class='hide-input' id='ei-" . $row['cus_id'] . "'><img id='deleteemail-" . $row['cus_id'] . "' class='form-submit' onclick='dce(" . $row['cus_id'] . ");' src='images/delete.svg' height='24' width='24' alt='delete email'> <input type='email' name=" . $row['cus_id'] . " value='" . $row['cus_email'] . "'> <img id='saveemail-" . $row['cus_id'] . "' class='form-submit' onclick='sce(" . $row['cus_id'] . ");' src='images/save.svg' height='24' width='24' alt='save email'></div></td><td style='text-align:center'>" . $row['cus_violations'] . "</td></tr>";
			}

			echo "</table></div>";
			echo "<img onclick='get100matched(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img onclick='get100matched(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> </br></br>";
			echo "</br>...and Mike is cool";
			echo "</div>";
			break;

		case 0 :
			echo "<div id='list-matched-100'>";
			echo "There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> customer entries with matching routes listed in this view <img onclick='get100matched(1);' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img onclick='get100matched(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Type</th><th style='text-align: center' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Email Address</th><th style='text-align: center' class='text-regular text-dark big'>Violations</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['cus_name'] . "</div></td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['desc_dev'] . "</td><td style='text-align:center'>" . $row['desc_intshort'] . " | " . $row['desc_intlong'] . "</td><td class='text-bold text-primary p' style='text-align:center'><div class='email_link' id='editemail-" . $row['cus_id'] . "'><a href='#/' onclick='ece(" . $row['cus_id'] . ");'>" . $row['cus_email'] . "</a></div><div class='hide-input' id='ei-" . $row['cus_id'] . "'><img id='deleteemail-" . $row['cus_id'] . "' class='form-submit' onclick='dce(" . $row['cus_id'] . ");' src='images/delete.svg' height='24' width='24' alt='delete email'> <input type='email' name=" . $row['cus_id'] . " value='" . $row['cus_email'] . "'> <img id='saveemail-" . $row['cus_id'] . "' class='form-submit' onclick='sce(" . $row['cus_id'] . ");' src='images/save.svg' height='24' width='24' alt='save email'></div></td><td style='text-align:center'>" . $row['cus_violations'] . "</td></tr>";
			}

			echo "</table></div>";
			echo "<img onclick='get100matched(1);' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img onclick='get100matched(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "</br>...and Mike is cool";
			echo "</div>";
			break;

		default :
			echo "<div id='list-matched-100'>";
			echo "<img onclick='get100matched(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img onclick='get100matched(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> customer entries with matching routes listed in this view <img onclick='get100matched(" . $get100numberNext . ");' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img onclick='get100matched(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: center' class='text-regular text-dark big'>Customer</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Type</th><th style='text-align: center' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Email Address</th><th style='text-align: center' class='text-regular text-dark big'>Violations</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['cus_name'] . "</div></td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['desc_dev'] . "</td><td style='text-align:center'>" . $row['desc_intshort'] . " | " . $row['desc_intlong'] . "</td><td class='text-bold text-primary p' style='text-align:center'><div class='email_link' id='editemail-" . $row['cus_id'] . "'><a href='#/' onclick='ece(" . $row['cus_id'] . ");'>" . $row['cus_email'] . "</a></div><div class='hide-input' id='ei-" . $row['cus_id'] . "'><img id='deleteemail-" . $row['cus_id'] . "' class='form-submit' onclick='dce(" . $row['cus_id'] . ");' src='images/delete.svg' height='24' width='24' alt='delete email'> <input type='email' name=" . $row['cus_id'] . " value='" . $row['cus_email'] . "'> <img id='saveemail-" . $row['cus_id'] . "' class='form-submit' onclick='sce(" . $row['cus_id'] . ");' src='images/save.svg' height='24' width='24' alt='save email'></div></td><td style='text-align:center'>" . $row['cus_violations'] . "</td></tr>";
			}

			echo "</table></div>";
			echo "<img onclick='get100matched(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img onclick='get100matched(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> <img onclick='get100matched(" . $get100numberNext . ");' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img onclick='get100matched(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "</br>...and Mike is cool";
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
