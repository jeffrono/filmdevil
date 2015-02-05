<?
	require "dbFunctions.php";
	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();

	fd_connect();
	fd_import_request_variables("gp", "form_");
	if(isset($form_confirm) && isset($form_festID)) {
		fd_filter_batch(array("form_festID"), true);
		fd_query("delete from cattable where FID = $form_festID");
		fd_query("delete from fees where festID = $form_festID");
		fd_query("delete from projtable where FID = $form_festID");
		fd_query("delete from reviews where festID = $form_festID");
		fd_query("delete from fests where ID = $form_festID");
		$msg = "fest deleted";
	}

	$festQuery = fd_query("select ID, title from fests order by title")
?>
<html>

<head>
  <title>Delete Fest</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<p class="title">Delete Fest</p>
<?if(isset($msg)) echo "<p class='error'>$msg</p>"; ?>

<b>!!Be careful!!</b>

<form action="deleteFest.php">
	<select name="festID">
		<option value="0">
<?	while($row = mysql_fetch_assoc($festQuery)) { ?>
			<option value="<?= $row["ID"] ?>"><?= $row["title"] ?>
<?	} ?>
	</select>
	<br><input type="submit" class="button" name="confirm" value="delete"
		 onClick="return confirm('Are you SUPER SUPER FUCKING SURE?');">
</form>

</body>

</html>