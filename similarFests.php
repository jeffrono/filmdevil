<?
	require "dbFunctions.php";
	if(!isLoggedIn())
		redirectToLogin();

	fd_import_request_variables("gp", "form_");
	fd_filter_batch(array("form_festID", "form_setSimilar"),
		true, true, false);
	if(!festEditAuthorized($_SESSION["user_id"], $form_festID)) {
		trigger_error("access error");
		die("access error");
	}

	fd_connect();

	$festQuery = fd_query("select ID, title, heuristic from fests order by title")
?>
<html>

<head>
  <title>Similar Festivals</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<?include "festMenu.php";
	printFestMenu(); ?>

<p class="title">Similar Festivals</p>

<p>These are the festivals on whose profile pages you currently appear as similar.

<p>
<table>
<?$simQuery = fd_query("select ID, title, heuristic from fests inner join similarFest
    on similarFestID = fests.ID where originFestID = $form_festID
    order by title");
	$rowNum = 0;
	while($row = mysql_fetch_assoc($simQuery)) { ?>
	<tr class="alt<?= $rowNum++ % 2 + 1 ?>">
		<td><a href="info.php?preview&ID=<?= $row["ID"] ?>"><?= $row["title"] ?></a>
<?} ?>
</table>

</body>

</html>