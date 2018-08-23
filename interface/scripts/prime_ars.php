<?php
session_start();

if (empty($_SESSION['user_name'])) {
	header('Location: index.php');
	exit;
}

require '../../confs/ars-variables.php';


try {
	// Create databases and open connections

	$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Set errormode to exceptions
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Create table customers
	$db->exec("CREATE TABLE IF NOT EXISTS customers (
		cus_id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
		       cus_name TEXT NULL,
		       cus_email TEXT NULL,
		       cus_violations INTEGER NULL,
		       desc_dev TEXT NULL,
		       desc_intshort TEXT NULL,
		       desc_intlong TEXT NULL)");

	// Create table routes
	$db->exec("CREATE TABLE IF NOT EXISTS routes (
		route_id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
			 route_dev TEXT NULL,
			 route_type TEXT NULL,
			 route_route TEXT NULL,
			 route_network TEXT NULL,
			 route_cidr TEXT NULL,
			 route_via TEXT NULL,
			 route_intlong TEXT NULL,
			 route_cus_id INTEGER NULL)");

	// Create table settings
	$db->exec("CREATE TABLE IF NOT EXISTS settings (
		set_id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
		       set_email TEXT NULL,
		       set_pass TEXT NULL,
		       set_imap_server TEXT NULL,
		       set_imap_server_ssl INTEGER NULL,
		       set_imap_port INTEGER NULL,
		       set_folder TEXT NULL,
		       set_replyto TEXT NULL,
		       set_smtp_server TEXT NULL,
		       set_smtp_server_ssl INTEGER NULL,
		       set_smtp_port INTEGER NULL)");


	// Create table violations
	$db->exec("CREATE TABLE IF NOT EXISTS violations (
		vio_id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
		       vio_ip TEXT NULL,
		       vio_from TEXT NULL,
		       vio_to TEXT NULL,
		       vio_timestamp TEXT NULL,
		       vio_subject TEXT NULL,
		       vio_body MEDIUMTEXT NULL,
		       vio_body_orig MEDIUMTEXT NULL,
		       vio_cus_id INTEGER NULL,
		       vio_cus_name TEXT NULL,
		       vio_route_id INTEGER NULL,
		       vio_email_id INTEGER NULL,
		       vio_msg_id TEXT NULL,
		       vio_status INTEGER NULL)");

	// Open files and insert information from files.
	$descs_file = fopen("../../ip-block-search/route/cleaned-up-descs.txt", "r") or die("Unable to open file!");
	$routes_file = fopen("../../ip-block-search/route/cleaned-up-routes.txt", "r") or die("Unable to open file!");

	// Insert information into customers table
	$numberrow = 0;
	if ($descs_file) {
		while (($line = fgets($descs_file)) !== false) {
			$descs_parts = explode("~", $line);
			//Yes a case/switch statement would be cleaner but I had problems using strpos to find 
			if (strpos($descs_parts[1], 'Fa') !== false) {
				$desc_intlong = str_replace('Fa', 'FastEthernet', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Gi') !== false) {
				$desc_intlong = str_replace('Gi', 'GigabitEthernet', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Te') !== false) {
				$desc_intlong = str_replace('Te', 'TenGigabitEthernet', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Vl') !== false) {
				$desc_intlong = str_replace('Vl', 'Vlan', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Lo') !== false) {
				$desc_intlong = str_replace('Lo', 'Loopback', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Po') !== false) {
				$desc_intlong = str_replace('Po', 'Port-Channel', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Se') !== false) {
				$desc_intlong = str_replace('Se', 'Serial', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Mu') !== false) {
				$desc_intlong = str_replace('Mu', 'Multilink', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'BE') !== false) {
				$desc_intlong = str_replace('BE', 'Bundle-Ether', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'BV') !== false) {
				$desc_intlong = str_replace('BV', 'BVI', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Nu') !== false) {
				$desc_intlong = str_replace('Nu', 'Null', rtrim($descs_parts[1]));
			} elseif (strpos($descs_parts[1], 'Mg') !== false) {
				$desc_intlong = str_replace('Mg', 'MgmtEth', rtrim($descs_parts[1]));
			} else {
				$desc_intlong = rtrim($descs_parts[1]);
			}

			//Catch and escape single quotes in file and remove new line characters to prep for searching.
			$catch_single_quote = addslashes($descs_parts[2]);

			// Query Customer table to look for matches.
			$descs_match_list = $db->query("SELECT COUNT(*) FROM customers WHERE `cus_name` = '$catch_single_quote' AND `desc_dev` = '$descs_parts[0]' AND `desc_intshort` = '$descs_parts[1]'");
			if ($descs_match_list->fetchColumn() > 0) {
				echo "$numberrow - Match - $descs_parts[0] - $descs_parts[1] - $descs_parts[2]";
				$numberrow++;
			} else {
				//Insert data into the customers table.
				$insert = $db->prepare('INSERT INTO customers (cus_name, cus_email, desc_dev, desc_intshort, desc_intlong) VALUES (?, ?, ?, ?, ?)');
				$insert->execute(array("$descs_parts[2]", "empty", "$descs_parts[0]", "$descs_parts[1]", "$desc_intlong"));
			}
		}
	}

	// Insert information into routes table
	$numberrow = 0;
	if ($routes_file) {
		while (($line = fgets($routes_file)) !== false) {
			$routes_parts = explode("~", $line);
			$routes_parts_cidr_split = explode("/", $routes_parts[2]);

			// Query Customer table to look for matches.
			$routes_match_list = $db->query("SELECT COUNT(*) FROM routes WHERE `route_dev` = '$routes_parts[0]' AND `route_type` = '$routes_parts[1]' AND `route_route` = '$routes_parts[2]'");
			if ($routes_match_list->fetchColumn() > 0) {
				echo "$numberrow - Match - $routes_parts[0] - $routes_parts[1] - $routes_parts[2] - " . rtrim($routes_parts[3]) . "\n";
				$numberrow++;
			} else {
				if (strpos($line, 'via-') !== false) {
					//Insert data into the routes table that has via- in the line.
					$trimedroutes_parts4 = rtrim($routes_parts[4]);
					$insert = $db->prepare('INSERT INTO routes (route_dev, route_type, route_route, route_network, route_cidr, route_via, route_intlong) VALUES (?, ?, ?, ?, ?, ?, ?)');
					$insert->execute(array("$routes_parts[0]", "$routes_parts[1]", "$routes_parts[2]", "$routes_parts_cidr_split[0]", "$routes_parts_cidr_split[1]", "$routes_parts[3]", "$trimedroutes_parts4"));
					echo "$numberrow\n";
					$numberrow++;
				} else {
					//Insert data into the routes table that doesnt have via- in the line.
					$trimedroutes_parts3 = rtrim($routes_parts[3]);
					$insert = $db->prepare('INSERT INTO routes (route_dev, route_type, route_route, route_network, route_cidr, route_intlong) VALUES (?, ?, ?, ?, ?, ?)');
					$insert->execute(array("$routes_parts[0]", "$routes_parts[1]", "$routes_parts[2]", "$routes_parts_cidr_split[0]", "$routes_parts_cidr_split[1]", "$trimedroutes_parts3"));
					echo "$numberrow\n";
					$numberrow++;
				}
			}

		}
	}

	// Match customers to routes.
	$translate_routes_customers = $db->query("SELECT * FROM `routes` WHERE `route_type` != 'L'");
	foreach($translate_routes_customers as $route_row) {
		$route_getcusid = $db->query("SELECT `cus_id` FROM `customers` WHERE `desc_dev` = '" . $route_row['route_dev'] . "' AND `desc_intlong` = '" . rtrim($route_row['route_intlong']) . "'");
		foreach($route_getcusid as $customerid_row) {
			$update_routecusid = $db->prepare("UPDATE `routes` SET `route_cus_id` = '" . $customerid_row['cus_id'] . "' WHERE `route_id` = '" . $route_row['route_id'] . "'");
			$update_routecusid->execute();
		}
	}

	// Close file
	fclose($descs_file);
	fclose($routes_file);

	// Close file db connection
	$db = null;
}
catch(PDOException $e) {
	// Print PDOException smam
	echo $e->getMessage();
}

?>
