<?php 
/*session_start();

if (empty($_SESSION['user_name'])) {
        header('Location: index.php');
        exit;
}

/*
   code snippet from the following post and adapted for my use
   Email retrieval:
   davidwalsh.name/gmail-php-imap
   Email decoding:
   www.sitepoint.com/exploring-phps-imap-library-1/
   IPv4 range matching:
   stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php-5
   IPv6 range matching:
   stackoverflow.com/questions/7951061/matching-ipv6-address-to-a-cidr-subnet
   Note:
   error reporting turned off for inet_to_bits function.
*/

require '../../confs/ars-variables.php';


$countprocessed = 0;
$countimported = 0;
$ipv4regex = '/(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/';

// open log file 
$logtime = date(DATE_RFC2822);
//$emfetchlog = fopen("../fetchlog.html", "w") or die("Unable to open file!");
//fwrite($emfetchlog, "Emails Fetched $logtime</br>");
echo "Emails Fetched $logtime</br>";

// see if ip lies within block functions
function cidr_matchv4($ip, $cidr) {
	list($subnet, $mask) = explode('/', $cidr);
	if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet)) {
		return true;
	}
	return false;
}

/* IPv6....some day i will have YOUUU!!!!
   $ipv6regex = '/(\A([0-9a-f]{1,4}:){1,1}(:[0-9a-f]{1,4}){1,6}\Z)|(\A([0-9a-f]{1,4}:){1,2}(:[0-9a-f]{1,4}){1,5}\Z)|(\A([0-9a-f]{1,4}:){1,3}(:[0-9a-f]{1,4}){1,4}\Z)|(\A([0-9a-f]{1,4}:){1,4}(:[0-9a-f]{1,4}){1,3}\Z)|(\A([0-9a-f]{1,4}:){1,5}(:[0-9a-f]{1,4}){1,2}\Z)|(\A([0-9a-f]{1,4}:){1,6}(:[0-9a-f]{1,4}){1,1}\Z)|(\A(([0-9a-f]{1,4}:){1,7}|:):\Z)|(\A:(:[0-9a-f]{1,4}){1,7}\Z)|(\A((([0-9a-f]{1,4}:){6})(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3})\Z)|(\A(([0-9a-f]{1,4}:){5}[0-9a-f]{1,4}:(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3})\Z)|(\A([0-9a-f]{1,4}:){5}:[0-9a-f]{1,4}:(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\Z)|(\A([0-9a-f]{1,4}:){1,1}(:[0-9a-f]{1,4}){1,4}:(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\Z)|(\A([0-9a-f]{1,4}:){1,2}(:[0-9a-f]{1,4}){1,3}:(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\Z)|(\A([0-9a-f]{1,4}:){1,3}(:[0-9a-f]{1,4}){1,2}:(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\Z)|(\A([0-9a-f]{1,4}:){1,4}(:[0-9a-f]{1,4}){1,1}:(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\Z)|(\A(([0-9a-f]{1,4}:){1,5}|:):(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\Z)|(\A:(:[0-9a-f]{1,4}){1,5}:(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}\Z)/';
   function inet_to_bits($inet)
   {
   $unpacked = unpack('A16', $inet);
   $unpacked = str_split($unpacked[1]);
   $binaryip = '';
   foreach ($unpacked as $char) {
   $binaryip .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
   error_reporting(0);
   }
   return $binaryip;
   }

   function cidr_matchv6($ip, $cidr) {
   $ip = inet_pton($ip);
   $binaryip = inet_to_bits($ip);

   list($net,$maskbits) = explode('/',$cidr);
   $net = inet_pton($net);
   $binarynet = inet_to_bits($net);

   $ip_net_bits = substr($binaryip,0,$maskbits);
   $net_bits = substr($binarynet,0,$maskbits);

   if ($ip_net_bits!==$net_bits) {
   return false;
   } else {
   return true;
   }
   }

   End IPv6 comment block */

// email decoding functions. this is so incredibly hard to do!
function getBody($uid, $imap) {
	$body = get_part($imap, $uid, "TEXT/PLAIN");
	// if HTML body is empty, try getting text body
	if ($body == "") {
		$body = get_part($imap, $uid, "TEXT/HTML");
	}
	return $body;
}

function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
	if (!$structure) {
		$structure = imap_fetchstructure($imap, $uid, FT_UID);
	}
	if ($structure) {
		if ($mimetype == get_mime_type($structure)) {
			if (!$partNumber) {
				$partNumber = 1;
			}
			$text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
			switch ($structure->encoding) {
				case 3: return imap_base64($text);
				case 4: return imap_qprint($text);
				default: return $text;
			}
		}

		// multipart 
		if ($structure->type == 1) {
			foreach ($structure->parts as $index => $subStruct) {
				$prefix = "";
				if ($partNumber) {
					$prefix = $partNumber . ".";
				}
				$data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
				if ($data) {
					return $data;
				}
			}
		}
	}
	return false;
}

function get_mime_type($structure) {
	$primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

	if ($structure->subtype) {
		return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
	}
	return "TEXT/PLAIN";
}

try {
	// Create databases and open connections

	$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

	// Set errormode to exceptions
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	// connect to email server
	$hostname = "{" . $email_host . ":" . $email_port . "/" . $email_type . "/" . $email_ssl . "}" . $email_folder;

	// try to connect
	$inbox = imap_open($hostname,$email_user,$email_pass, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'PLAIN')) or die("Cannot connect to $email_host: " . imap_last_error());

	// grab emails
	$yesterday = date('j-M-Y', strtotime('-20 days', strtotime(date('j-M-Y'))));
	$since = "SINCE $yesterday";
	$emails = imap_search($inbox,$since);
	//$emails = imap_search($inbox,'ALL');

	// if emails are returned, cycle through each...
	if($emails) {

		// for every email...
		foreach($emails as $email_number) {

			// get information specific to this email
			$overview = imap_fetch_overview($inbox,$email_number,0);

			// get message 
			$orig_message = getBody($email_number,$inbox);

			// make the message browser friendly by replacing line breaks with HTML breaks
			$message = htmlentities(str_replace("\n", '</br>', $orig_message));

			// find valid email addresses
			preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $overview[0]->to, $tofield);
			preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $overview[0]->from, $fromfield);

			// map subject to variable just in case I need to escape special chars
			$subjectfield = $overview[0]->subject;

			// find valid IP addresses
			preg_match($ipv4regex, $message, $ipv4);
			//preg_match($ipv6regex, $message, $ipv6);

			// query vialations database for matches
			$emails_match_list = $db->query("SELECT COUNT(*) FROM violations WHERE `vio_msg_id` = '{$overview[0]->message_id}' AND `vio_timestamp` = '{$overview[0]->date}'");
			switch (isset($ipv4[0])) {
				case 0 :
					if ($emails_match_list->fetchColumn() > 0) {
//						fwrite($emfetchlog, "Match - {$overview[0]->date} - {$overview[0]->subject} - {$overview[0]->message_id}</br>");
						echo "Match - {$overview[0]->date} - {$overview[0]->subject} - {$overview[0]->message_id}</br>";
						$countprocessed++;
					} else {
						// insert data into the violations table.
						$msgheader = htmlentities("IP: N/A</br>From: $fromfield[0]</br>Timestamp: {$overview[0]->date}</br>To: $tofield[0]</br>Subject: $subjectfield</br></br>");
						$insert = $db->prepare("INSERT INTO violations (vio_ip, vio_msg_id, vio_timestamp, vio_to, vio_from, vio_subject, vio_body, vio_body_orig, vio_cus_id, vio_cus_name) VALUES (?,?,?,?,?,?,?,?,?,?)");
						$insert->execute(array('N/A',"{$overview[0]->message_id}","{$overview[0]->date}","$tofield[0]","$fromfield[0]","$subjectfield","$msgheader $message","$orig_message","0",'N/A'));
						$countprocessed++;
						$countimported++;
					}
					break;
				case 1 :
					if ($emails_match_list->fetchColumn() > 0) {
						echo "Match - {$overview[0]->date} - {$overview[0]->subject} - {$overview[0]->message_id}</br>";
						$countprocessed++;
					} else {
						// match ip address to route and the find the associted customer name.
						$route_results = $db->query("SELECT * FROM `routes` WHERE `route_type` != 'L' AND `route_route` != '64.135.0.0/17' AND `route_route` != '75.119.128.0/18' AND `route_route` != '216.242.0.0/16' AND `route_route` != '63.135.64.0/20' AND `route_route` != '64.118.224.0/19' AND `route_route` != '66.115.0.0/18' AND `route_route` != '66.216.0.0/18'");
						foreach ($route_results as $matchip) {
							$route = $matchip['route_route'];
							$found = cidr_matchv4($ipv4[0], $route);
							if ($found == true) {
								$resolve_found = $db->query("SELECT cus_name FROM `customers` WHERE cus_id = '" . $matchip['route_cus_id'] . "'");
								foreach ($resolve_found as $mf) {
									$mfcusname = $mf['cus_name'];
									$mfcusid = $matchip['route_cus_id'];
								}
							}
						}
						if (!isset($mfcusid)) {
							$mfcusname = 'N/A';
							$mfcusid = "0";
						} 
						// need to make this auto populate super blocks from the settings table.
						$msgheader = htmlentities("IP: $ipv4[0]</br>From: $fromfield[0]</br>Timestamp: {$overview[0]->date}</br>To: $tofield[0]</br>Subject: $subjectfield</br></br>");
						$insert = $db->prepare("INSERT INTO violations (vio_ip, vio_msg_id, vio_timestamp, vio_to, vio_from, vio_subject, vio_body, vio_body_orig, vio_cus_id, vio_cus_name) VALUES (?,?,?,?,?,?,?,?,?,?)");
						$insert->execute(array("$ipv4[0]","{$overview[0]->message_id}","{$overview[0]->date}","$tofield[0]","$fromfield[0]","$subjectfield","$msgheader $message","$orig_message","$mfcusid","$mfcusname"));
						$countprocessed++;
						$countimported++;
						$mfcusname = 'N/A';
						$mfcusid = "0";
					}
					break;
			}
		}

		// close the connection
		imap_close($inbox);
	}


	// Close file db connection
	$db = null;
}
	catch(PDOException $e) {
	// Print PDOException smam
	echo $e->getMessage();

}

echo "</br>$countprocessed Emails Processed</br>$countimported New Abuse Emails Processed and Imported</br>Mike is cool....</br></br>";
fclose($emfetchlog);

/*
echo "\n$countprocessed Emails Processed\n";
echo "$countimported New Abuse Emails Processed and Imported\n";
echo "Mike is cool....\n\n";
*/
?>
