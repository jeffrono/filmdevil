<?
	$cacheLimiter = "nocache";
	require "dbFunctions.php";
	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();

	fd_connect();
	fd_import_request_variables("gp", "form_");
	if(isset($form_verify)) {
		foreach($form_purchase as $purchaseID) {
			fd_query("update purchase set status = 'activated', datePaid = now()
				where id = $purchaseID");
			$result = mysql_fetch_assoc(fd_query("select * from purchase inner join fests
				on purchase.festID = fests.ID where purchase.id = $purchaseID"));
	    $subject = "Your purchase has been verified";
	    $body = "Hi, \n\nWe would like to inform you that the features you have purchased "
				. "for " . $result["title"] . " have been activated. You can see the "
				. "status of all your promotional features, as well as your purchase history "
				. "by logging into your account and clicking 'Promote Fest' on the top. "
				. "\n\nThanks again for using FilmDevil";
		  fd_mail($result["email"], $subject, $body);
		}
		file_get_contents(URL_ROOT . "generateSimilarFestivals.php");
		$msg = "purchases verified and similar listings regenerated";
	} elseif(isset($form_delete)) {
		foreach($form_purchase as $purchaseID)
			fd_query("update purchase set status = 'disapproved' where id = $purchaseID");
		$msg = "purchases disapproved";
	}

	$purchaseResult = fd_query("select purchase.*, fests.title
		from purchase inner join fests on purchase.festID = fests.ID
		where purchase.status = 'notPaid' order by datePurchased");
?>

<html>

<head>
  <title>Verify Purchases</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<script language="javascript">
	function selectAll(value) {
		for(var i = 0; i < document.all("purchase[]").length; i++)
			document.all("purchase[]", i).checked = value;
	}
</script>

<?if(isset($msg)) echo "<p class='error'>$msg</p>"; ?>
<form action="verifyPurchase.php" method="post">
	<table cellspacing="0" cellpadding="5">
		<tr>
			<th><input type="checkbox" class="radio" onClick="selectAll(this.checked);"></th>
			<th>Date</th>
			<th>Fest</th>
			<th>Cost</th>
		</tr>
<?$rowNum = 0;
	while($row = mysql_fetch_assoc($purchaseResult)) {
		$rowNum++; ?>
		<tr class='alt<?= $rowNum % 2 + 1 ?>'>
			<td><input type="checkbox" class="radio" name="purchase[]"
				value="<?= $row["id"] ?>"></td>
			<td><?= formatDate(strtotime($row["datePurchased"])) ?></td>
			<td><?= $row["title"] ?></td>
			<td>$<?= $row["cost"] ?></td>
		</tr>
<?} ?>
		<tr>
			<td colspan="4" align="center">
				<input type="submit" class="button" name="verify" value="verify"
					onClick="return confirm('Are you sure you want to VERIFY these purchases?');">
				<input type="submit" class="button" name="delete" value="disapprove"
					onClick="return confirm('Are you sure you want to DELETE these purchases?');">
			</td>
		</tr>
</table>

</form>
</body>

</html>