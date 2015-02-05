<?
	require "dbFunctions.php";
	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();

	fd_connect();
	fd_import_request_variables("gp", "form_");
	fd_filter_batch(array("form_festID", "form_numSimilar"),
		true, true, false);
	if(isset($form_genHeuristic)) {
		$explain = generateHeuristic($form_festID, true, true);
		$msg = "Heuristic: <br>";
		foreach($explain as $str)
			$msg .= $str . "<br>";
	} elseif(isset($form_genAllHeuristic)) {
		$festIDQuery = fd_query("select ID from fests order by heuristic");
		while($row = mysql_fetch_assoc($festIDQuery))
			generateHeuristic($row["ID"]);
		$msg = "Generated heuristics for all festivals";
	} elseif(isset($form_setSimFests)) {
		setSimilarFests($form_festID, $form_numSimilar);
		$msg = "Similar fests set";
	}

	if(isset($form_order))
		$form_order = fd_filter($form_order);
	else
		$form_order = "heuristic";
	$festQuery = fd_query("select ID, title, heuristic from fests order by $form_order")
?>
<html>

<head>
  <title>Similar Festivals</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<p class="title">Similar Festivals</p>
<?if(isset($msg)) echo "<p class='error'>$msg</p>"; ?>

<form action="adminSimilarFests.php">
	<select name="festID">
<?while($row = mysql_fetch_assoc($festQuery)) { ?>
			<option value="<?= $row["ID"] ?>"
<?	if(isset($form_festID) && $form_festID == $row["ID"]) echo "selected"; ?>>
<?	if($form_order == "heuristic") { ?>
				<?= $row["heuristic"] ?> - <?= $row["title"] ?>
<?	} else { ?>
				<?= $row["title"] ?> - <?= $row["heuristic"] ?>
<? 	} ?>
<?} ?>
	</select>
	<br>Order by <select name="order" onChange="this.form.submit();">
		<option value="title" <? if($form_order == "title") echo "selected"; ?>>title
		<option value="heuristic" <? if($form_order == "heuristic") echo "selected"; ?>>heuristic
	</select>
	<p><input type="submit" class="button" name="seeSimilar" value="See similar fests">
		<input type="submit" class="button" name="gotoFest" value="Goto Fest Profile"
			onClick="location.href = 'info.php?preview&ID=' + this.form.festID.options[this.form.festID.selectedIndex].value; return false;">
	<P><input type="submit" class="button" name="genHeuristic" value="Generate Heuristic">
		<input type="submit" class="button" name="genAllHeuristic" value="Generate ALL heuristics">
	<p>
	<select name="numSimilar">
<?for($i = 0; $i <= 500; $i += 25) { ?>
		<option value="<?= $i ?>"><?= $i ?>
<?} ?>
		<input type="submit" class="button" name="setSimFests" value="Set similar festivals">
</form>

<?if(isset($form_festID)) {
	  $simQuery = fd_query("select ID, title, heuristic from fests inner join similarFest
	    on similarFestID = fests.ID where originFestID = $form_festID
			order by heuristic");
		echo "current similar fests: " . mysql_num_rows($simQuery);
		$heuristic = 0;
		while($row = mysql_fetch_assoc($simQuery)) {
			$heuristic += $row["heuristic"]; ?>
<br><?= $row["heuristic"] ?> -
	<a href="info.php?preview&ID=<?= $row["ID"] ?>"><?= $row["title"] ?></a>
<?	}
		if(mysql_num_rows($simQuery) > 0) {
			$avg = $heuristic / mysql_num_rows($simQuery);
			echo "<p><b>average heuristic = $avg</b>";
		}
	} ?>


</body>

</html>