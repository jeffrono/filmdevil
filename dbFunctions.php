<?php
require_once "config.php";
require_once "session.php";

onPageRequest();

// CODE TO RUN EACH PAGE REQUEST
function onPageRequest() {
	// we will do our own error handling
	error_reporting(0);
	$old_error_handler = set_error_handler("userErrorHandler");
	register_shutdown_function("printErrors");
	handleSession(); // in session.php
}

// similar to import_request_variables, buth WORKS with the login redirect system
// takes all variables in the types and extracts them with the prefix
// types are g -> GET, p -> POST, c-> COOKIE, r -> REQUEST (all of them)
function fd_import_request_variables($types = "r", $prefix = "") {
	for($i = 0; $i < strlen($types); $i++) {
		$type = substr($types, $i, 1);
		if($type == "r" || $type == "R")
			$arr = $_REQUEST;
		elseif($type == "g" || $type == "G")
			$arr = $_GET;
		elseif($type == "p" || $type == "P")
			$arr = $_POST;
		elseif($type == "c" || $type == "C")
			$arr = $_COOKIE;
		else {
			trigger_error("Invalid type '$type' in fd_import_request_variables");
			return false;
		}
		$keys = array_keys($arr);
		$values = array_values($arr);
		for($j = 0; $j < count($arr); $j++) {
			// Create the var
			$GLOBALS[$prefix . $keys[$j]] = $values[$j];
			//echo "importing " . $prefix . $keys[$j] . " = " . $values[$j] . ".";
		}
	}
}

function isPromoActive($festID, $promoName) {
	fd_connect();
	$promoName = fd_filter($promoName);
	$festID = fd_filter($festID, true);
	$result = fd_query("select purchase.id from
		purchase inner join purchasePackage
		on purchase.id = purchasePackage.purchaseID
		inner join purchasePackagePromotion
		on purchasePackagePromotion.purchasePackageID = purchasePackage.ID
		inner join promotion
		on promotion.id = purchasePackagePromotion.promotionID
		where shortName = '$promoName' and festID = $festID
		and startDate <= now() and endDate >= now() and status = 'activated'");
	return mysql_num_rows($result) > 0;
}

function setSimilarFests($festID, $num) {
	if($num == 0) {
		fd_query("delete from similarFest where originFestID = $festID");
		return true;
	}

	fd_connect();
	$festRow = mysql_fetch_assoc(fd_query("select heuristic from fests where
		ID = $festID"));
	// Get similar heuristic values
	$step = 5;
	$upperBound = $festRow["heuristic"];
	$lowerBound = $festRow["heuristic"];
	$festsFound = 0;
	while($festsFound < $num) {
		$upperBound += $step;
		$lowerBound -= $step;
		$similarResult = fd_query("select ID from fests where heuristic >= $lowerBound
			&& heuristic <= $upperBound");
		$festsFound = mysql_num_rows($similarResult);
	}
  $similarArray = makeDblArray($similarResult);
	$randKeys = array_rand($similarArray, $num);
	// delete current values
	fd_query("delete from similarFest where originFestID = $festID");
	// set new values
	foreach($randKeys as $key) {
		fd_query("insert into similarFest (originFestID, similarFestID, created)
			values ($festID, " . $similarArray[$key]["ID"] . ", now())");
	}
}

function generateHeuristic($festID, $writeToDB = true, $explain = false) {
	/*  (.9)* length in days  +  (.9)*avg fee price
		+ (.6)*(student = 1, no student = 2)
		+ (.3)*(no awards = 1, no prizes = 2, cash prize = 5) */
	$explainArray = array();

	fd_connect();
	$heuristic = 0;
	$festRow = mysql_fetch_assoc(fd_query("select * from fests where
		ID = $festID"));

	if($festRow["stFriend"] == 1) {
		$heuristic += .6;
		$explainArray[] = "studentFriendly: yes = +.6";
	} else {
		$heuristic += .12;
		$explainArray[] = "studentFriendly: no = +.12";
	}

	if($festRow["prizes"] == 1) { // no prizes
		$heuristic += .6;
		$explainArray[] = "prizes: no cash = +.6";
	} elseif($festRow["prizes"] == 2) { // cash monies
		$heuristic += 1.5;
		$explainArray[] = "prizes: cash cash = +1.5";
	} else {
		$heuristic += 1;
		$explainArray[] = "prizes: none = +.3";
	}

	$feeRow = mysql_fetch_assoc(fd_query("select AVG(Feature) as feature,
		AVG(Short) as short, AVG(Student) as student, AVG(Other) as other
		from fees where festID = $festID"));
	$averagePrices = array();
	if($feeRow["feature"] != 0) $averagePrices[] = $feeRow["feature"];
	if($feeRow["short"] != 0) $averagePrices[] = $feeRow["short"];
	if($feeRow["student"] != 0) $averagePrices[] = $feeRow["student"];
	if($feeRow["other"] != 0) $averagePrices[] = $feeRow["other"];
	if(count($averagePrices) > 0) {
		$avgPrice = array_sum($averagePrices) / count($averagePrices);
		$heuristic += .9 * $avgPrice;
		$explainArray[] = "averagePrices: $avgPrice = +" . 0.9 * $avgPrice;
	} else {
		$explainArray[] = "averagePrices: 0";
	}

	$lengthRow = mysql_fetch_assoc(fd_query("select 1 + TO_DAYS(endDate)
		- TO_DAYS(startDate) as days from fests where ID = $festID"));
	$heuristic += .9 * $lengthRow["days"];
	if(!isset($lengthRow["days"]))
		$explainArray[] = "invalid dates";
	else
		$explainArray[] = "length in days: " . $lengthRow["days"] . " = +"
		. 0.9 * $lengthRow["days"];

	$explainArray[] = "final heuristic: $heuristic";
	// Write heuristic to db
	if($writeToDB)
 		fd_query("update fests set heuristic = $heuristic where ID = $festID");
	if($explain)
		return $explainArray;
	else
		return $heuristic;
}

function makeRequestString() {
	$listingString = "";
	foreach($_REQUEST as $name => $val) {
		if($name == "PHPSESSID") continue;
		if($listingString != "")
			$listingString .= "&";
		else
			$listingString .= "?";
		$listingString .= urlencode($name) . "=" . urlencode($val);
	}
	return $listingString;
}

// Adds footer
function fd_mail($to, $subject, $body, $fromName = SUPPORT_CONTACT,
		$fromEmail = SUPPORT_EMAIL) {
	if(SEND_EMAIL) {
	  mail($to, $subject, $body . EMAIL_FOOTER,
  	   "From: \"$fromName\" <$fromEmail>\n"
    	."X-Mailer: PHP/" . phpversion());
	}
	if(LOG_EMAIL) {
		$mail = fd_filter("\n ** \nDate: " . formatDate(mktime()) .
			"\nTo: $to\nFrom: \"$fromName\" <$fromEmail>\nBody: $body"
			. EMAIL_FOOTER . "\n");
	  fd_connect();
	  fd_query("update data set data = CONCAT(data, '$mail') where id = 2");
	}
}

// Gets the reduced image size that stays within the supplied limits,
// keeping the original scale
// Returns an associative array with 'width' and 'height' keys
function getScaledImageSize($imageURL, $maxWidth, $maxHeight) {
	$newSize = array("width" => $maxWidth, "height" => $maxHeight);
	if(!ONLINE) return $newSize;
  $origSize = getimagesize($imageURL);
	if($origSize === false) return $newSize;
	$widthScale = $maxWidth / $origSize[0];
  $heightScale = $maxHeight / $origSize[1];
  if($widthScale < $heightScale) {
    // width bigger than height
    $newSize["height"] = $origSize[1] * $widthScale;
	} elseif($widthScale > $heightScale) {
   // height bigger than width
   $newSize["width"] = $origSize[0] * $heightScale;
  }
  return $newSize;
}

function urlEncodeArray($arr) {
	$url = "";
	$i = 0;
	foreach($arr as $key => $value) {
		if($i > 0)
			$url .= "&";
		$url .= "$key=" . urlencode($value);
		$i++;
		//echo "$key = $value";
	}
	return $url;
}

function noFrames() {
	return isset($_REQUEST["noFrames"]);
}

function getWelcomePage() {
	if(!isLoggedIn()) return "";
	if($_SESSION["user_primaryType"] == "administrator")
  	return "welcomeFest.php";
  elseif($_SESSION["user_primaryType"] == "devil")
    return "welcomeAdmin.php";
  else
  	return "welcome.php";
}

// *** Timer functions
function startTimer(&$timerArray) {
	$timerArray[] = gettimeofday();
}

// returns time in milliseconds and stops the timer
function endTimer(&$timerArray, $printIt = false) {
	if(count($timerArray) == 0)
		trigger_error("There's no timer to stop");
	$newTime = gettimeofday();
	$oldTime = array_pop($timerArray);
	$diff = (int) ( (($newTime["sec"] - $oldTime["sec"]) * 1000)
		+ (($newTime["usec"] - $oldTime["usec"]) / 1000) );
	if($printIt)
		echo "<br>Timer = $diff msec\n";
	return $diff;
}


// *** FORMAT FUNCTIONS ***
function limitWordLength($str, $maxLength) {
	$words = explode(" ", $str);
	$newStr = "";
	foreach($words as $word) {
		$newStr .= limitStringLength($word, $maxLength) . " ";
	}
	return $newStr;
}

function limitStringLength($str, $maxLength) {
  if(strlen($str) > $maxLength)
    return substr($str, 0, $maxLength - 3) . "...";
  else
    return $str;
}

function getAbsoluteURL($url) {
	if(preg_match("/^http/", $url)) return $url;
	else return "http://" . $url;
}

function formatMoney($amount) {
	return "$" . number_format($amount, 2);
}

function formatDate($timestamp) {
	return date("F j, Y", $timestamp);
}

function formatSQLDate($timestamp) {
	return date("'Y-m-d'", $timestamp);
}

// Returns the printable location for the fest given a row from the "fests" table
function getDisplayLocation($festRow) {
	$loc = "";
	if(!empty($festRow["vCity"]))
		$loc .= $festRow["vCity"] . ", ";
	if(!empty($festRow["vState"]))
		$loc .= $festRow["vState"];
	elseif(!empty($festRow["region"]))
		$loc .= $festRow["region"];
	if(!empty($festRow["country"]))
		$loc .= " " . $festRow["country"];
	return $loc;
}


// *** DATABASE FUNCTIONS ***
function fd_connect() {
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	mysql_select_db(DB_NAME);
	return $link;
}

function fd_close() {
	mysql_close();
}

// If isNumeric is false(default), returns the string with html special characters
// transated. Otherwise the argument is translated returned as either a float
// or an int.
function fd_filter($val, $isNumeric = false) {
	if($isNumeric)
    	if(is_float($val))
        	return floatval($val);
    	else
        	return intval($val);
    else
    	return htmlspecialchars($val, ENT_QUOTES);
}

// Runs the filter for each variable in the array
// if createOpt is true, nonexistant vars will be created with a empty string or
// value of 0. if false, they will not be created
function fd_filter_batch($arr, $isNumeric = false, $optional = false,
		$createOptional = true) {
	foreach ($arr as $value) {
		if(!$optional || isset($GLOBALS[$value]))
    		$GLOBALS[$value] = fd_filter($GLOBALS[$value], $isNumeric);
		else {
			// assert $optional = true
			if($createOptional)
				$GLOBALS[$value] = $isNumeric ? 0 : "";
		}
	}
}

function fd_query($query) {
    $result = mysql_query($query);
    if($result === false) {
       	$code = mysql_errno();
        $error = mysql_error();
        if(ECHO_ERROR)
        	echo $error . " in query " . $query . "<br>";
		if(LOG_ERROR) mysql_query("INSERT INTO error (type, code, info, page, query) VALUES ("
			. "'SQL',"
            . $code . ", '"
            . mysql_real_escape_string($error) . "'"
			. ", '" . mysql_real_escape_string($_SERVER['PHP_SELF']) . "',"
            . "'" . mysql_real_escape_string($query) . "')"
        );
        if(EMAIL_ERROR) mail(ERROR_CONTACT,
        	"FILMDEVIL - Error DB", "Code: " . $code . "\nDescription: " . $error
			. "\nPage: " . $_SERVER['PHP_SELF']
            . "\nQuery: " . $query,
            "From: support@filmdevil.com\n"
            . "Reply-To: support@filmdevil.com\n"
        	. "X-Mailer: PHP/" . phpversion());
    } else
    	return $result;
}

/* Returns the sql update statement which would set the value of the row back to
	the current state
	It returns the row using the key name and value specified, excluding the
	columns specified (as array)
	This won't work if there's no cols besides the key and those in excludeCols*/
function createUndo($tableName, $keyName, $keyValue, $excludeCols = array()) {
	fd_connect();
	$colResult = fd_query("show columns from " . $tableName);
	if(!is_int($keyValue))
		$keyValue = "'" . $keyValue . "'";
	$valueRow = mysql_fetch_assoc(fd_query("select * from $tableName where
		$keyName = $keyValue"));
	if(empty($valueRow)) {
		trigger_error("Couldn't find value row for that key");
		return "";
	}

	$updateQuery = "update $tableName set";
	$rowNum = 0;
	while($colRow = mysql_fetch_assoc($colResult)) {
		if($colRow["Field"] != $keyName
			&& array_search($colRow["Field"], $excludeCols) === false) {
			$colValue = $valueRow[$colRow["Field"]];
			if($colValue == "")
				$colValue = "NULL";
			else {
				$colValue = fd_filter($colValue);
	      $numericPattern = "/int|bit|bool|real|double|float|decimal|numeric/i";
	      if(preg_match($numericPattern, $colRow["Type"]) == 0)
	        $colValue = "'" . $colValue . "'";
			}
			if($rowNum > 0) $updateQuery .= ",";
			$updateQuery .= " " . $colRow["Field"] . " = " . $colValue;
			$rowNum++;
		}
	}

	$updateQuery .= " where $keyName = $keyValue";
	return $updateQuery;
}

function makeDblArray($mysqlResult) {
	$dblArray = array();
	while($row = mysql_fetch_assoc($mysqlResult)) {
		$dblArray[] = $row;
	}
	return $dblArray;
}

function randomizeArray($arr) {
	if(count($arr) <= 1)
		return $arr;

	$keys = array_rand($arr, count($arr));
	$newArray = array();
  foreach($keys as $key)
    $newArray[$key] = $arr[$key];
	return $newArray;
}


/*** ERROR FUNCTIONS */

// user defined error handling function
function userErrorHandler ($errno, $errmsg, $filename, $linenum, $vars) {
    // timestamp for the error entry
    $dt = date("Y-m-d H:i:s (T)");

    // define an assoc array of error string
    // in reality the only entries we should
    // consider are 2,8,256,512 and 1024
    $errortype = array (
                1   =>  "Error",
                2   =>  "Warning",
                4   =>  "Parsing Error",
                8   =>  "Notice",
                16  =>  "Core Error",
                32  =>  "Core Warning",
                64  =>  "Compile Error",
                128 =>  "Compile Warning",
                256 =>  "User Error",
                512 =>  "User Warning",
                1024=>  "User Notice"
                );
    // set of errors for which a var trace will be saved
    $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

    $err = "<errorentry>\n";
    $err .= "\t<datetime>".$dt."</datetime>\n";
    $err .= "\t<errornum>".$errno."</errornum>\n";
    $err .= "\t<errortype>".$errortype[$errno]."</errortype>\n";
    $err .= "\t<errormsg>".$errmsg."</errormsg>\n";
    $err .= "\t<scriptname>".$filename."</scriptname>\n";
    $err .= "\t<scriptlinenum>".$linenum."</scriptlinenum>\n";

    if (USE_WDDX && in_array($errno, $user_errors))
        $err .= "\t<vartrace>".wddx_serialize_value($vars,"Variables")."</vartrace>\n";
    $err .= "</errorentry>\n\n";

    // for testing
    if(ECHO_ERROR) {
			if(headers_sent())
				echo $err . "<br>";
			else {
				$GLOBALS["errorQueue"][] = $err; // Print later in printErrors()
			}
		}

    // save to the error log, and e-mail me if there is a critical user error
    // error_log($err, 3, "/usr/local/php4/error.log");
    //if ($errno == E_USER_ERROR)
	if(LOG_ERROR) {
    	fd_connect();
        mysql_query("INSERT INTO error (type, code, info, page) VALUES ("
			. "'" . mysql_real_escape_string($errortype[$errno]) . "',"
            . $errno . ","
            . "'" . mysql_real_escape_string($errmsg) . "'"
			. ", '" . $filename . " - line " . $linenum . "')"
        );
    }

    if(EMAIL_ERROR) mail(ERROR_CONTACT,"FILMDEVIL - Error User",$err);
}

// Called as a shutdown function
function printErrors() {
	//echo "print errors called " . isset($GLOBALS["errorQueue"]);
	if(isset($GLOBALS["errorQueue"])) {
		foreach($GLOBALS["errorQueue"] as $error)
			echo $error . "<br>";
	}
}

?>