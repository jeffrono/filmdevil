<?php

require_once "dbFunctions.php";

define("USE_HTML_MAIL", false);

if(isset($_POST["Login"])) {
	// Attempt to login user
	$email = fd_filter($_POST["email"]);
	$password = fd_filter($_POST["password"]);
	if(login($email, $password)) {
		// if a redirect page exists, take them there
		//trigger_error("is redirected is " . isRedirected());
		if(isRedirected()) {
			$cacheLimiter = "nocache";
			redirectAfterLogin();
			die();
		}
		// redirect them to the correct page for their type
		header("Location: " . URL_ROOT . getWelcomePage());
		/*echo "<br>you are logged in as " . $_SESSION["email"];
    var_dump($_SESSION);*/
  } else {
    // Login FAILED
     header("Location: " . URL_ROOT . "loginUserPage.php?error=pass&email=$email");
	}
} elseif(isset($_POST["Create"])) {
	// Create user
	import_request_variables("gp", "form_");
	$email = fd_filter($form_email);
	$password = fd_filter($form_password);

    fd_connect();
    $result = fd_query("select id from user where email = '$email'")->fetch_assoc();
    if($result != "") {
		header("Location: " . URL_ROOT . "loginUserPage.php?error=dupEmail");
	} else {
    	// No existing account- create it
		fd_query("insert into user (newEmail, password, created, status) values "
        	. "('$email', '$password', now(), 'unvalidated')");
        $id = mysql_insert_id();
		header("Location: " . URL_ROOT . "loginUser2.php?validate&id=$id");
	}
} elseif(isset($_GET["validate"])) {
	$id = fd_filter($_GET["id"]);

    fd_connect();
    $result = fd_query("select newEmail, status from user where id = $id")->fetch_assoc();
	if($result == "")
    	die("There is no such account");

  	$email = $result["newEmail"];

    $createKey = rand();
	// update db
    fd_query("update user set createKey = '$createKey' where id = $id");

	// Send email
    $subject = "Filmdevil - Please verify your email address";
    $bodyText = "Hi,\n\nThanks for using FilmDevil. "
        . "In order to get verify your email address, please type in the number below into the box on our web page you saw immediately after hitting 'Create Me'. "
        . "\n\nNumber: $createKey"
        . "\n\nIf you are no longer at this page you can go to "
        . "" . URL_ROOT . "loginUser2.php?email=$email&createKey=$createKey "
        . "either by clicking the link above or cutting and pasting it into your browser's address bar."
        . "\n\nHope to hear from you soon,"
        . "\nThe folks at FilmDevil";
    $bodyHTML = "Hi,<p>Thanks forusing FilmDevil. "
        . "In order to get started, please type in the number below into the box on our web page you saw immediately after hitting 'Create Me'. "
        . "<p>Number: $createKey"
        . "<p>If you are no longer at this page you can go to "
        . "<a href='" . URL_ROOT . "loginUser2.php?email=$email&createKey=$createKey'>"
        . "" . URL_ROOT . "loginUser2.php?email=$email&createKey=$createKey</a> "
        . "either by clicking the link above or cutting and pasting it into your browser's address bar."
        . "<p>Hope to hear from you soon,"
        . "<br>The folks at FilmDevil";

    if(USE_HTML_MAIL) {
        mail($email, $subject, $bodyHTML,
            "From: support@filmdevil.com\n"
            . "Reply-To: support@filmdevil.com\n"
            . "X-Mailer: PHP/" . phpversion() . "\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-type: text/html; charset=iso-8859-1\r\n");
    } else {
        mail($email, $subject, $bodyText,
            "From: support@filmdevil.com\n"
            . "Reply-To: support@filmdevil.com\n"
            . "X-Mailer: PHP/" . phpversion());
    }

    header("Location: " . URL_ROOT . "loginUserPage.php?page2&inputKey&email=$email");
} elseif(isset($_GET["createKey"]) || isset($_POST["createKey"])) {
	// Try to log in the user with the create key
	import_request_variables("gp", "form_");
    $email = fd_filter($form_email);
	$createKey = fd_filter($form_createKey);
	fd_connect();
    $result = fd_query("select id, password, status from user where newEmail = '$email' "
    	. "and createKey = '$createKey'")->fetch_assoc();
    if($result == "")
		header("Location: " . URL_ROOT . "loginUserPage.php?page2&inputKey&email=$email&error=wrongKey");
	else {
		fd_query("update user set lastLogin = now(), createKey = '', "
        	. "email = '$email', newEmail = '', status = 'ok' where id = " . $result["id"]);
        if(login($email, $result["password"])) {
        	if($result["status"] == "unvalidated")
        		header("Location: " . URL_ROOT . "editUser.php?id=" . $result["id"]);
            else
            	header("Location: " . URL_ROOT . "welcome.php");
        } else
        	trigger_error("big problems creating user");
    }
} elseif(isset($_GET["inputKey"])) {
	$email = fd_filter($_GET["email"]);
?>

<style type="text/css">
<!--
.error {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #FF0000;
	background-color: #000000;
}
-->
</style>
<form name="form1" method="post" action="loginUser2.php">
<table width="39" border="0" background="images/bg.gif" class="border">
  <tr>
    <td colspan="2"><div align="center" class="border">Validate Email</div>
	<? if(isset($_GET["error"])) { ?>
		<div align="center" class="error">
	      Sorry, we can't find that number and email combination. Did you type it in correctly?</div>
	<? } ?>
	</td>
  </tr>
  <tr>
    <td colspan="2" class="description">Please enter the number that is being emailed to you now,
      or follow the link (by clicking or cutting and pasting it into your browser's address bar)
	</td>
  </tr>
  <tr>
    <td width="23%" class="description">Email</td>
    <td width="77%"><input type="text" class="select" name="email" size="30" value="<?= $email ?>">
    </td>
  </tr>
  <tr>
    <td class="description">Number</td>
    <td><input name="createKey" type="text" class="select" id="password2" size="30">
    </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
        <input name="submitKey" type="submit" class="select" value="Submit number">
      </div>
	</td>
  </tr>
</table>
</form>

<?
} else { // Forgot password
	$email = fd_filter($_POST["email"]);
	fd_connect();
    $result = fd_query("select password, status from user where email = '$email'")->fetch_assoc();
	if($result == "") {
		header("Location: " . URL_ROOT . "loginUserPage.php?error=email");
	} elseif($result["createKey"] != "") {
		// They never validated their email
		//fd_query("delete from user where email = '$email'");
		header("Location: " . URL_ROOT . "loginUserPage.php?error=never");
    } else {
	// Send password
	    $subject = "Filmdevil - Trouble logging in?";
	    $body = "Hi,\n\nIt seems you have forgotten your password. Well here we go:"
	        . "\n\nEmail: $email"
	        . "\nPassword: " . $result["password"]
	        . "\n\nThanks again for using filmdevil,"
	        . "\nThe folks at Filmdevil";
	    mail($email, $subject, $body,
	            "From: support@filmdevil.com\n"
	            . "Reply-To: support@filmdevil.com\n"
	            . "X-Mailer: PHP/" . phpversion());
	    header("Location: " . URL_ROOT . "loginUserPage.php?error=sentPass&email=$email");
	}
}
?>