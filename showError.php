<?
	$cacheLimiter = "nocache";
	require "dbFunctions.php";
	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();

	fd_connect();
	fd_import_request_variables("gp", "form_");
	if(isset($form_delete)) {
		foreach($form_error as $errorID)
			fd_query("delete from error where id = $errorID");
		$msg = "errors deleted";
	} elseif(isset($form_deleteAll)) {
		fd_query("delete from error");
		$msg = "All errors deleted";
	}

	$errorResult = fd_query("select * from error order by time desc");
?>

<html>

<head>
  <title>Show Error Log</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<?if(isset($msg)) echo "<p class='error'>$msg</p>"; ?>
<form action="showError.php" method="post">
	<table cellspacing="0" cellpadding="5">
		<tr>
			<td colspan="7" align="center">
				<a href="showError.php">Refresh</a>
				<input type="submit" class="button" name="delete" value="delete">
				<input type="submit" class="button" name="deleteAll" value="deleteAll">
			</td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th>Date</th>
			<th>Type</th>
			<th>Code</th>
			<th>Page</th>
			<th>Query</th>
			<th>Info</th>
		</tr>
<?$rowNum = 0;
	while($row = mysql_fetch_assoc($errorResult)) {
		$rowNum++; ?>
		<tr class='alt<?= $rowNum % 2 + 1 ?>'>
			<td><input type="checkbox" class="radio" name="error[]"
				value="<?= $row["id"] ?>"></td>
			<td><?= $row["time"] ?></td>
			<td><?= $row["type"] ?></td>
			<td><?= $row["code"] ?></td>
			<td><?= $row["page"] ?></td>
			<td><?= $row["query"] ?></td>
			<td><?= $row["info"] ?></td>
		</tr>
<?} ?>
		<tr>
			<td colspan="7" align="center">
				<a href="showError.php">Refresh</a>
				<input type="submit" class="button" name="delete" value="delete">
				<input type="submit" class="button" name="deleteAll" value="deleteAll">
			</td>
		</tr>
</table>

</form>
</body>

</html>