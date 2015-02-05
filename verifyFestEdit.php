<?
	$cacheLimiter = "nocache";
	require "dbFunctions.php";
	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();

	fd_connect();
	fd_import_request_variables("gp", "form_");
	if(isset($form_verify)) {
		foreach($form_edit as $editID) {
			fd_query("update edit set status = 'ok', datePaid = now()
				where ID = $editID");
		}
		$msg = "edits verified";
	} elseif(isset($form_undo)) {
    foreach($form_edit as $editID) {
      $undoResult = mysql_fetch_assoc(fd_query("select undoSQL from fests where
        ID = $editID"));
      if(!empty($undoResult["undoSQL"])) {
        fd_query($undoResult["undoSQL"]);
        fd_query("update fests set status = 'ok' where ID = $editID");
      } else {
        $noUndoExists = true;
      }
    }
    if(isset($noUndoExists))
			$msg = "Undo didn't exist";
		else
			$msg = "edits undone";
	}

	$editResult = fd_query("select * from fests	where status = 'unverified'
		order by lastDate");
?>

<html>

<head>
  <title>Verify Fest Edit</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<script language="javascript">
	function selectAll(value) {
		for(var i = 0; i < document.all("edit[]").length; i++)
			document.all("edit[]", i).checked = value;
	}
</script>

<?if(isset($msg)) echo "<p class='error'>$msg</p>"; ?>
<form action="verifyFestEdit.php" method="post">
	<table cellspacing="0" cellpadding="5">
		<tr>
			<th><input type="checkbox" class="radio" onClick="selectAll(this.checked);"></th>
			<th>Date</th>
			<th>Fest</th>
			<th>Undo Exists?</th>
		</tr>
<?$rowNum = 0;
	while($row = mysql_fetch_assoc($editResult)) {
		$rowNum++; ?>
		<tr class='alt<?= $rowNum % 2 + 1 ?>'>
			<td><input type="checkbox" class="radio" name="edit[]"
				value="<?= $row["ID"] ?>"></td>
			<td><?= formatDate(strtotime($row["lastDate"])) ?></td>
			<td><a target="preview" href="info.php?noframes&ID=<?= $row["ID"] ?>">
				<?= $row["title"] ?></a></td>
			<td><? if(empty($row["undoSQL"])) echo "No"; else echo "Yes"; ?>
		</tr>
<?} ?>
		<tr>
			<td colspan="4" align="center">
				<input type="submit" class="button" name="verify" value="verify"
					onClick="return confirm('Are you sure you want to VERIFY these edits?');">
				<input type="submit" class="button" name="undo" value="undo"
					onClick="return confirm('Are you sure you want to UNDO these edits?');">
			</td>
		</tr>
</table>

</form>
</body>

</html>