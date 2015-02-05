<?
	require_once("dbFunctions.php");
	fd_import_request_variables("gp", "form_");
	if(!isset($form_return) || $form_return == "cancel")
		$returnVal = "notPaid";
	else
		$returnVal = "paid";
?>

<html>
<head>
  <title>Payment on FilmDevil</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>
<body>

<?if($returnVal == "paid") { ?>
<p class="title" align="center">Payment Successfull</p>
<p>Thanks again for purchasing your promotional features at FilmDevil. We will inform
	you by email as soon as we validate your purchase.
</p>
<?} else { ?>
<p class="title" align="center">Payment Cancelled</p>
<p>It seems you have asked to cancel the payment process on PayPal.
	If there was a problem, please don't hesitate to
	<a href="contact1.php">inform us</a>.
</p>
<?} ?>

<p><a href="" onClick="window.close();">Close Window</a>

</body>
</html>