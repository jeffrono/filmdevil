<?php
	require_once "dbFunctions.php";

	if(isset($_GET['email'])) {
		$email = fd_filter($_GET['email']);
	} else {
		$email = "";
  }

  if(isLoggedIn()) {
    echo("<div class='description'>You are currently logged in as "
        . "<a href='welcomeFest.php'>"
          . $_SESSION["user_email"] . "</a><div>");
  }
?>

<script language="javascript">
<!--
	function validateEmail() {
		var reg = new RegExp("@");
		//var reg = new RegExp("^[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]@[\w-\.]*[a-zA-Z0-9]\.[a-zA-Z]{2,7}$", "g");
    	if(document.form1.email.value.match(reg)) return true;
		alert("Sorry, this does not appear to be a real email address. Please check your spelling.");
        return false;
	}

	function validatePassword() {
    	if(document.form1.password.value.length >= 4) return true;
        alert("Sorry, the password must be four or more letters, numbers, and symbols long.");
        return false;
	}
//-->
</script>

<style type="text/css">
<!--
.error {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #FF0000;
	background-color: #000000;
}
-->
</style>

<form name="form1" method="post" action="loginUser2.php">
<table width="39" border="0" background="images/bg.gif" class="border">
  <tr>
    <td colspan="2"><div align="center" class="border">Login</div>
	<? if(isset($_GET["error"])) { ?>
		<div align="center" class="error">
	    <? if($_GET["error"] == "pass") { ?>
	        Incorrect email/password. <br>
	        You can click "I Forget My Password" and we'll send it to you over email
	    <? } elseif($_GET["error"] == "email") {?>
	        Sorry, we have no account for that email
	    <? } elseif($_GET["error"] == "dupEmail") {?>
	        Sorry, that email is already being used.
	      	Please click &quot;I Forget My Password&quot;
			<? } elseif($_GET["error"] == "never") {?>
	        You never validated your email. Please try again.
	    <? } elseif($_GET["error"] == "sentPass") {?>
	        Your password has been sent to <i><?= $email ?>
			<? } elseif($_GET["error"] == "redirect") {?>
	        You must login to access this feature.
					<br>If you don't have a login, please create one.
	    <? } ?>
    </div>
	<? } ?>
	</td>
  </tr>
  <tr>
    <td width="23%" class="description">Email</td>
    <td width="77%"><input name="email" type="text" class="select" id="email2" size="30" value="<?= $email ?>">
    </td>
  </tr>
  <tr>
    <td class="description">Password</td>
    <td><input name="password" type="password" class="select" id="password2" size="30">
    </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
        <input name="Login" type="submit" class="select" id="Login" value="Log Me In"
        	onclick="return validateEmail() && validatePassword();">
        &nbsp;&nbsp;&nbsp;
       <input name="Create" type="submit" class="select" id="Create" value="Create Me"
        	onclick="return validateEmail() && validatePassword();">
      </div>
    </td>
  </tr>
  <tr>
  	<td colspan="2"><div align="center">
  	  <input name="Forgot" type="submit" class="select" value="I Forgot My Password"
        	onclick="return validateEmail();">
	  </div></td>
  </tr>
</table>
</form>