<?
require_once "dbFunctions.php";
fd_connect();

fd_import_request_variables("p", "form_");
fd_filter_batch(array("form_name", "form_email", "form_URL", "form_title",
    "form_body", "form_type", "form_favMov", "form_favWeb"));
fd_filter_batch(array("form_filmRating", "form_locationRating",
    "form_peopleRating", "form_id"), true);

$genRating = ($form_filmRating + $form_locationRating + $form_peopleRating
    + $form_orgRating + 2) / 4;
$today1 = getdate();
$month1 = $today1['mon'];
$mday1 = $today1['mday'];
$year1 = $today1['year'];

$query = "insert into reviews (festID, name, email, URL, title, date, body, "
  . "type, genRating, filmRating, peopleRating, locationRating, orgRating, "
  . "favMov, favWeb, userID) "
  . "values ($form_id, '$form_name',"
  . "'$form_email', '$form_URL', '$form_title', '$year1-$month1-$mday1',"
  . "'$form_body', '$form_type', $genRating, $form_filmRating,"
  . "$form_peopleRating, $form_locationRating, $form_orgRating, "
  . "'$form_favMov', '$form_favWeb', ";

if(isLoggedIn())
	$query .= $_SESSION["user_id"];
else
	$query .= "''";

$query .= ")";

fd_query($query);

$result = mysql_fetch_array(fd_query("select avg(genRating) AS avg from reviews where festID = $form_id"));
# mysql_query("select numReviews from fests for update");
$avg=$result["avg"];
$numreviews=mysql_fetch_array(fd_query("select count(*) from reviews where festID=$form_id"));
fd_query("update fests set rating = $avg, numReviews = $numreviews[0] where id = $form_id");

?>
<html>
<head>
<title>Thanks for the review</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<?include "header.php" ?>

<p class="title">Thanks for the review</p>

<p> Thank you for taking the time to fill out a review. Your feedback is
  what makes this site useful for filmmakers and festival goers.</p>

<? include "footer.php"; ?>

</body>
</html>