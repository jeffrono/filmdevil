<?
	require_once "dbFunctions.php";
?>
<html>
<head>
<title>Contact Us</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
</head>

<body bgcolor="#000000" text="#FFFFFF" class="description">
Please use this form to contact us.  We will try to respond within 24 hours.
<form name="form3" method="post" action="contact2.php" class="description">
  <table border="0" cellspacing="5" cellpadding="" class="description" width="100%">
    <tr>
      <td>Name:</td>
      <td><input type="text" name="name" class="select" size="30"
				<? if(isLoggedIn()) echo "value='" . $_SESSION["user_displayName"] ."'" ?>></td>
    </tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" name="email" class="select" size="30"
				<? if(isLoggedIn()) echo "value='" . $_SESSION["user_email"] ."'" ?>></td>
		</tr>
		<tr>
			<td>Subject:</td>
      <td><select name="subject" class="select">
        <option value="Adding a Festival">Adding a Festival</option>
        <option value="Not Able to Log In">Not Able to Log In</option>
        <option value="Reporting an Error">Reporting an Error</option>
        <option value="Reporting Inapproprate Use">Reporting Inapproprate Use</option>
        <option value="Need Help">Need Help</option>
        <option value="Just a Question">Just a Question</option>
        <option value="Advertising">Advertising</option>
        <option value="Something Else">Something Else</option>
      </select></td>
		</tr>
		<tr>
			<td>Body:</td>
			<td><textarea name="body" rows="6" cols="40" wrap="VIRTUAL" class="select"></textarea>
		</tr>
		<tr>
			<td colspan="2" align="center">
        <input type="submit" name="Submit" value="Send" class="select">
      </td>
    </tr>
  </table>
  </form>
</body>
</html>