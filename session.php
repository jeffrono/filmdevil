<?php

// Call this from every page
// Set $cacheLimiter if you don't want the default of "nocache"
function handleSession() {
	if(!isset($GLOBALS["cacheLimiter"]))
		$GLOBALS["cacheLimiter"] = CACHE_LIMITER;
	session_cache_expire($GLOBALS["cacheLimiter"]);
	session_cache_expire(CACHE_TIMEOUT);
	session_start();
	if(hasSessionTimedOut())
	    logout(true);
    else
    	refreshSession();
	if(shouldRestoreRedirectVars()) {
		restoreRedirectVars();
	}
}

function isLoggedIn() {
	return isset($_SESSION["user_id"]);
}

function refreshSession() {
	//echo "refresh called";
	$_SESSION["lastTime"] = time();
}

function hasSessionTimedOut() {
	if(isset($_SESSION["lastTime"])) {
		$diff = time() - $_SESSION["lastTime"];
		if($diff > SESSION_TIMEOUT * 60)
            return true;
    }
    return false;
}

// Returns true if user is authorized to edit the festival
function festEditAuthorized($userID, $festID) {
	fd_connect();
    $userID = fd_filter($userID, true);
    $festID = fd_filter($festID, true);
	$result = mysqli_fetch_assoc(fd_query("select userID from userFest where userID = "
    	. $userID . " and festID = " . $festID
        . " and relation = 'admin'"));
    return !empty($result);
}

// redirects the request through the login page
function redirectToLogin() {
	/*if(isset($_SESSION["gotoPage"]))
		trigger_error("There is already a page to redirect to");*/
	session_start();
	$_SESSION["gotoPage"] = $_SERVER['SCRIPT_NAME'];
	storeRedirectVars();
	if(headers_sent())
		trigger_error("Can't redirect to login because headers have already been sent");
	header("Location: " . URL_ROOT . "loginUserPage.php?error=redirect");
}

// redirects the request to the original page
function redirectAfterLogin() {
	session_start();
	if(!isset($_SESSION["gotoPage"]))
		trigger_error("There is no redirect page");
	//trigger_error("going to " . $_SESSION["gotoPage"]);
	header("Location: " . $_SESSION["gotoPage"]);
}

function storeRedirectVars() {
	$_SESSION["gotoRequest"] = $_REQUEST;
	$_SESSION["gotoGet"] = $_GET;
	$_SESSION["gotoPost"] = $_POST;
}

function restoreRedirectVars() {
	$_GET += $_SESSION["gotoGet"];
	$_POST += $_SESSION["gotoPost"];
	$_REQUEST += $_SESSION["gotoRequest"];
	$_REQUEST["big"] = "test";
	unset($_SESSION["gotoGet"]);
	unset($_SESSION["gotoPost"]);
	unset($_SESSION["gotoRequest"]);
	unset($_SESSION["gotoPage"]);
	unset($_SESSION["gotoDone"]);
}

function isRedirected() {
	return isset($_SESSION["gotoPage"]);
}

function shouldRestoreRedirectVars() {
	return isset($_SESSION["gotoDone"]);
}

// Returns true if login successful, false otherwise
function login($email, $password) {
	fd_connect();
  $email = fd_filter($email);
	$password = fd_filter($password);
  $result = mysqli_fetch_assoc(fd_query(
    "select * from user where email = '$email' and password = '$password' and createKey = ''"));
  if($result == "") return false;

  // Create Session
	// save login redirect from being destroyed
	if(isset($_SESSION["gotoPage"])) {
		$gotoPage = $_SESSION["gotoPage"];
		$gotoGet = $_SESSION["gotoGet"];
		$gotoPost = $_SESSION["gotoGet"];
		$gotoRequest = $_SESSION["gotoRequest"];
	}
	logout();
	session_start();
	//session_regenerate_id();
	refreshSession();
	// reset login redirect
	if(isset($gotoPage)) {
		$_SESSION["gotoPage"] = $gotoPage;
		$_SESSION["gotoGet"] = $gotoGet;
		$_SESSION["gotoPost"] = $gotoPost;
		$_SESSION["gotoRequest"] = $gotoRequest;
		$_SESSION["gotoDone"] = true;
	}

	foreach($result as $key => $val) {
    $_SESSION["user_" . $key] = $val;
  }

	// Determine display name
	$result = fd_query("select title from userFest inner join fests
		on fests.id = userFest.festID where userID = " . $_SESSION["user_id"]
		. " and relation = 'admin'");
	if(mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_assoc($result);
		$_SESSION["user_displayName"] = $row["title"];
	} elseif(getDisplayName($userRow) != "Anonymous")
		$_SESSION["user_displayName"] = getDisplayName($userRow, "user_");
	else
		$_SESSION["user_displayName"] = $_SESSION["user_email"];

	// Log login into db
	fd_query("update user set lastLogin = now(), numLogins = numLogins + 1 where id = " . $_SESSION["user_id"]);
	return true;
}

function logout($saveNonUserData = false) {
	//echo "logout called.";
	if(!isset($_SESSION["lastTime"])) return false; // Not logged in

	if($saveNonUserData) {
		foreach($_SESSION as $key => $value) {
			if(strncmp($key, "user_", 5) == 0)
				unset($_SESSION[$key]);
		}
	} else {
	  session_unset();
	  session_destroy();
	}
  return true;
    //echo "logout done.";
}

function canUpdateFest($festID) {
	fd_connect();
	$result = mysqli_fetch_assoc(fd_query(
    	"select * from permission where festID = $festID and userID = " . $_SESSION["userID"]));
    return $result != "";
}

function getDisplayName($userRow, $prefix = "") {
  if(!empty($userRow[$prefix . "username"]))
    return $userRow[$prefix . "username"];
  elseif(!empty($userRow[$prefix . "firstName"])
		|| !empty($userRow[$prefix . "lastName"]))
    return $userRow[$prefix . "firstName"] . " " . $userRow[$prefix . "lastName"];
  else
	  return "Anonymous";
}

?>