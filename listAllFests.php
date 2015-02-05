<?require_once "dbFunctions.php";
	fd_connect();
	$result = fd_query("select title, vCity, vState, region, country from
		fests order by title");
?>

<html>
<head>
	<title>List All Festivals on FilmDevil</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<? include "header.php"; ?>

<p class="title">List All Festivals</p>
<p>This is a giant list of all the festivals we have in our database
in alphebetical order. It's MUCH FASTER
to use our specialized search engine by clicking "Find A Fest" above.
However you can browse this list by clicking a letter below to jump to that portion
of the list.

<p align="center">
<?for($i = ord("A"); $i <= ord("Z"); $i++) {
		$letter = chr($i);
		echo "<a href='#$letter'>$letter</a> ";
	}	 ?>
<p>
<table cellspacing="0" cellpadding="5">
<?$firstLetter = "";
	$rowNum = 0;
	while($row = mysql_fetch_assoc($result)) {
		/* For links to festRedirect.php, all spaces are turned into dashes, and
			all dashes into three underscores */
		$linkName = str_replace("-", "___", $row["title"]);
		$linkName = str_replace(" ", "-", $linkName);
		$linkName = htmlentities(urlencode($linkName)) . ".php";
		if(FESTS_URL_REWRITE)
			$linkName = "fests/" . $linkName;
		else
			$linkName = "festRedirect.php?url=" . $linkName;
		if(substr($row["title"], 0, 1) != $firstLetter &
			preg_match("/[A-Z]/i", substr($row["title"], 0, 1)) ) {
			$firstLetter = substr($row["title"], 0, 1); ?>
	<tr>
		<td colspan="2" class="sectionHeading" align="center">
			<a name="<?= $firstLetter ?>"><?= $firstLetter ?></a>
	</tr>
<?	} ?>
	<tr class="alt<?= $rowNum % 2 + 1 ?>">
		<td width="70%"><a href="<?= $linkName ?>"><?= $row["title"] ?></a>
		<td width="30%"><?= getDisplayLocation($row) ?>
	</tr>
<?	$rowNum++;
	} ?>
</table>
<?include "footer.php"; ?>
</body>

</html>