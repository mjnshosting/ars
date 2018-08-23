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
	$result_count = $db->query('SELECT COUNT(*) FROM routes');
	$num_rows = $result_count->fetchColumn();

	// Create list for page
	$result_list = $db->query("SELECT * FROM routes ORDER BY route_dev REGEXP '^\d*[^\da-z&\.\' \-\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, route_dev+0, route_dev LIMIT " . $get100number . "00,100");
	$get100numberMax = floor($num_rows / 100);

	if ($get100numberPrevious <= 0) {
		$get100numberPrevious = 0;
	}

	switch ($get100number) {
		case $get100numberMax :
			echo "<div id='list-routes-100'>";
			echo "<img class='form-submit'  onclick='get100route(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  onclick='get100route(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> routes listed in this view </br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: left' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Route Type</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Via</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['route_dev'] . "</div></td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_intlong'] . "</td><td style='text-align:center'>" . $row['route_via'] . "</td></tr>";
			}

			echo "</table></div>";
			echo "<img class='form-submit'  onclick='get100route(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  onclick='get100route(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> </br></br>";
			echo "</br>...and Mike is cool";
			echo "</div>";
			break;

		case 0 :
			echo "<div id='list-routes-100'>";
			echo "There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> routes listed in this view <img class='form-submit'  onclick='get100route(1);' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100route(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: left' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Route Type</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Via</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['route_dev'] . "</div></td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_intlong'] . "</td><td style='text-align:center'>" . $row['route_via'] . "</td></tr>";
			}

			echo "</table></div>";
			echo "<img class='form-submit'  onclick='get100route(1);' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100route(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "</br>...and Mike is cool";
			echo "</div>";
			break;

		default :
			echo "<div id='list-routes-100'>";
			echo "<img class='form-submit'  onclick='get100route(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  onclick='get100route(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> There are <i style='font-weight: bold; color: #fb6d51'>$num_rows</i> routes listed in this view <img class='form-submit'  onclick='get100route(" . $get100numberNext . ");' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100route(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
			echo "<div class='table-responsive clearfix'><table class='table table-striped'><tr><th style='text-align: left' class='text-regular text-dark big'>Device</th><th style='text-align: center' class='text-regular text-dark big'>Route Type</th><th style='text-align: center' class='text-regular text-dark big'>Route</th><th style='text-align: center' class='text-regular text-dark big'>Interface</th><th style='text-align: center' class='text-regular text-dark big'>Via</th></tr>";

			foreach($result_list as $row) {
				echo "<tr style='text-align:left'><td><div class='few_chars'>" . $row['route_dev'] . "</div></td><td style='text-align:center'>" . $row['route_type'] . "</td><td style='text-align:center'>" . $row['route_route'] . "</td><td style='text-align:center'>" . $row['route_intlong'] . "</td><td style='text-align:center'>" . $row['route_via'] . "</td></tr>";
			}

			echo "</table></div>";
			echo "<img class='form-submit'  onclick='get100route(0);' src='images/first-arrow-left.svg' height='24' width='24' alt='first arrow'> <img class='form-submit'  onclick='get100route(" . $get100numberPrevious . ");' src='images/next-arrow-left.svg' height='24' width='24' alt='next arrow previous'> <img class='form-submit'  onclick='get100route(" . $get100numberNext . ");' src='images/next-arrow-right.svg' height='24' width='24' alt='next arrow next'> <img class='form-submit'  onclick='get100route(" . $get100numberMax . ");' src='images/last-arrow-right.svg' height='24' width='24' alt='last arrow'></br></br>";
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
