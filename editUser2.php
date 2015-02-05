<?php
require "dbFunctions.php";

fd_import_request_variables("p", "form_");
if(!isLoggedIn() || $form_id != $_SESSION["user_id"]) trigger_error("You can't access this user's info");

fd_filter_batch(array("form_password1", "form_password2",
	"form_lastName", "form_firstName", "form_webSite", "form_primaryType",
  "form_city", "form_postalCode", "form_oldEmail",
  "form_continent", "form_country", "form_region", "form_state",
  "form_favFilm", "form_favWebSite", "form_commLevel", "form_howFound",
  "form_mailFormat", "form_username"));
fd_filter_batch(array("form_submittingFilm", "form_month",	"form_day",
	"form_year", "form_id"), true);
$birthday = "$form_year-$form_month-$form_day";

$updateString = "UPDATE user SET lastName = '$form_lastName',
	firstName = '$form_firstName', webSite = '$form_webSite',
	city = '$form_city', postalCode = '$form_postalCode', continent = '$form_continent',
  country = '$form_country', region = '$form_region',
  state = '$form_state', favFilm = '$form_favFilm', favWebSite = '$form_favWebSite',
  howFound = '$form_howFound', commLevel = '$form_commLevel',
	mailFormat = '$form_mailFormat', submittingFilm = $form_submittingFilm,
	birthday = '$birthday', username = '$form_username'";

if($form_primaryType != "devil")
	$updateString .= ", primaryType = '$form_primaryType'";

if($form_password1 != "")
	$updateString .= ", password = '$form_password1'";

if($form_email != $form_oldEmail) {
	$updateString .= ", status = 'revalidate', newEmail = '$form_email'";
	$mustValidateEmail = true;
} else
	$mustValidateEmail = false;

$updateString .= " where id = $form_id;";
fd_connect();
fd_query($updateString);

$result = mysql_fetch_assoc(fd_query("select email, password from user where id = $form_id"));

if(!login($result["email"], $result["password"]))
	trigger_error("Unable to login user after info update");

if($mustValidateEmail)
	header("Location: " . URL_ROOT . "loginUser2.php?validate&id=" . $form_id);
?>



<html>

<head>
  <title>Edit User Information - All done</title>
  <link href="styles/central.css" rel="stylesheet" type="text/css">
</head>
<body>

Thanks for updating your account information, <b><?= $_SESSION["user_displayName"] ?></b>.

<p>Go back to <a href="<?= getWelcomePage() ?>">welcome page</a>

<? include "footer.php"; ?>
</body>

</html>