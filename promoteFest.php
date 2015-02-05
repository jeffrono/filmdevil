<?
	$cacheLimiter = "nocache";
	require_once "dbFunctions.php";
	require_once "showFestSwitch.php";

	function printPromoRow($promoName) {
		$row = mysql_fetch_assoc(fd_query("select * from promotion
			where shortName = '$promoName'")); ?>
		<tr class="alt<?= $GLOBALS["rowNum"] % 2 + 1 ?>">
			<td><input type="checkbox" class="radio" name="feature_<?= $promoName ?>"
				onClick="updateMonthlyCostCheckbox(this, <?= $row["price"] ?>);"></td>
			<td><?= $row["longName"] ?></td>
			<td><?= $row["description"] ?></td>
			<td><?= formatMoney($row["price"]) ?> per month</td>
		</tr>
<?	$GLOBALS["rowNum"]++;
	}

	function printPromoOptionRow($promoName, $optionName) {
		$row = mysql_fetch_assoc(fd_query("select * from promotion
			where shortName = '$promoName'")); ?>
		<tr class="alt<?= $GLOBALS["rowNum"] % 2 + 1 ?>">
			<td><input type="radio" class="radio" name="<?= $optionName ?>"
				value="<?= $promoName ?>"
				onClick="updateMonthlyCostRadio(this, <?= $row["price"] ?>);"></td>
			<td><?= $row["longName"] ?></td>
			<td><?= $row["description"] ?></td>
			<td><?= formatMoney($row["price"]) ?> per month</td>
		</tr>
<?	$GLOBALS["rowNum"]++;
	}

	function printHeadingRow($heading) {
		$GLOBALS["rowNum"] = 0; ?>
		<tr>
			<td colspan="4" class="sectionHeading"><?= $heading ?></td>
		</tr>
<?
	}

	function printPackageCell($packageID, $festID) {
		$row = mysql_fetch_assoc(
			fd_query("select * from package where id = '$packageID'"));
		$description = str_replace("\n", "<li>", $row["description"]); ?>
		<td valign="top" style="background-color: #DDDDDD">
			<a href="promoteFest.php?action=setDate&packageID=<?= $packageID ?>&festID=<?= $festID ?>">
				<span class="sectionHeading"><?= $row["name"] ?></span></a>
			<br>
			<ul><li><?= $description ?></ul>
			<br><b>Only $<?= $row["price"] ?></b>
			<br><br><div align="center">
				<a href="promoteFest.php?action=setDate&packageID=<?= $packageID ?>&festID=<?= $festID ?>">Choose this package</a></div>
		</td>
<?}

	// If $length is positive, $baseDate is startDate, else $baseDate is endDate
	function addPromotion($purchasePackageID, $promoName, $baseDate, $length) {
		$promoRow = mysql_fetch_assoc(fd_query("select id from promotion
			where shortName = '$promoName'"));
		$promotionID = $promoRow["id"];

		$baseDateParts = getdate($baseDate);
		$extendDate = mktime(0, 0, 0, $baseDateParts["mon"] + $length,
			$baseDateParts["mday"], $baseDateParts["year"]);
		if($length > 0) {
			$startDate = $baseDate;
			$endDate = $extendDate;
		} else {
			$startDate = $extendDate;
			$endDate = $baseDate;
		}

		fd_query("insert into purchasePackagePromotion (purchasePackageID,
			promotionID, startDate, endDate) values ($purchasePackageID, $promotionID, "
			. formatSQLDate($startDate) . ", " . formatSQLDate($endDate) . ")");
	}

	function getPromoCost($promoName) {
		$costRow = mysql_fetch_assoc(fd_query("select price from promotion where
			shortName = '$promoName'"));
		return $costRow["price"];
	}

	// ***  BEGIN PAGE CONTENT
	if(!isLoggedIn())
		redirectToLogin();

	fd_connect();
	$row = mysql_fetch_assoc(fd_query("select max(id) as max from promotion"));
	$maxPromoID = $row["max"];

  fd_import_request_variables("gp", "form_");
	fd_filter_batch(array("form_action"), false, true, true);

	if($form_action == "finishPackage") {
		fd_filter_batch(array("form_festID", "form_month", "form_day", "form_year",
			"form_length"), true, true, false);
    if(!festEditAuthorized($_SESSION["user_id"], $form_festID)) {
      trigger_error("Access Error");
      die("You are not authorized to update this fest's info");
    }

		if(isset($form_packageID)) {
	  	fd_filter_batch(array("form_packageID"));

			// Create purchaseID if it doesn't exist
      if(!isset($_SESSION["purchaseID"])) {
				// Delete old inProgress purchases
				fd_query("delete from purchase where status = 'inProgress' and
					festID = $form_festID");
        fd_query("insert into purchase (festID) values ($form_festID)");
        $_SESSION["purchaseID"] = mysql_insert_id();
			}
			if($form_packageID == "custom") {
				// Its a custom package
	      fd_query("insert into purchasePackage (purchaseID, packageID) values
	        (" . $_SESSION["purchaseID"] . ", '$form_packageID')");
	      $purchasePackageID = mysql_insert_id();

				// Get date
	      $startDate = mktime(0, 0, 0, $form_month, $form_day, $form_year);

				// Go through all features adding promotions and getting prices
				$monthlyCost = 0;
				foreach($_REQUEST as $key => $value) {
					$matches = array();
					if(preg_match("/feature_(.*)/", $key, $matches)) {
						$monthlyCost += getPromoCost($matches[1]);
						addPromotion($purchasePackageID, $matches[1], $startDate, $form_length);
					} elseif(preg_match("/listingType|similarFests/", $key)) {
						if($value != "normal") {
	            $monthlyCost += getPromoCost($value);
	            addPromotion($purchasePackageID, $value, $startDate, $form_length);
						}
					}
				}

				// Update cost
				$totalCost = $monthlyCost * $form_length;
	      fd_query("update purchasePackage set price = $totalCost
					where id = $purchasePackageID");

			} else {
	      // Its a standard package purchase
	      $costRow = mysql_fetch_assoc(
	        fd_query("select price from package where id = '$form_packageID'"));
	      fd_query("insert into purchasePackage (purchaseID, packageID, price) values
	        (" . $_SESSION["purchaseID"] . ", '$form_packageID', " . $costRow["price"] . ")");
	      $purchasePackageID = mysql_insert_id();

	      // Setup promotions
	      $endDate = mktime(0, 0, 0, $form_month, $form_day, $form_year);
	      if($form_packageID == "bronze") {
	        addPromotion($purchasePackageID, "boldListing", $endDate, -3);
	        addPromotion($purchasePackageID, "listed25",  $endDate, -1);
	      } elseif($form_packageID == "silver") {
	        addPromotion($purchasePackageID, "distinctBG", $endDate, -2);
	        addPromotion($purchasePackageID, "topSearch", $endDate, -2);
	        addPromotion($purchasePackageID, "listed50",  $endDate, -2);
	      } elseif($form_packageID == "gold") {
	        addPromotion($purchasePackageID, "distinctBG", $endDate, -3);
	        addPromotion($purchasePackageID, "topSearch", $endDate, -3);
	        addPromotion($purchasePackageID, "featured", $endDate, -1);
	        addPromotion($purchasePackageID, "listed100",  $endDate, -2);
	      } else { // ($form_packageID == "platinum") {
	        addPromotion($purchasePackageID, "distinctBG", $endDate, -3);
	        addPromotion($purchasePackageID, "topSearch", $endDate, -3);
	        addPromotion($purchasePackageID, "featured", $endDate, -2);
	        addPromotion($purchasePackageID, "listed200",  $endDate, -3);
	      }
			}
		} elseif(isset($form_remove)) {
			fd_filter_batch(array("form_remove"), true);
			fd_query("delete from purchasePackagePromotion
				where purchasePackageID = $form_remove");
			fd_query("delete from purchasePackage
				where id = $form_remove");
		}

		$purchaseResult = fd_query("select *, purchasePackage.price as ppPrice,
			purchasePackage.id as ppID from purchasePackage
			inner join package on package.id = purchasePackage.packageID
			where purchaseID = " . $_SESSION["purchaseID"]);
?>
<html>

<head>
  <title>Purchase Promotional Features</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<?include "festMenu.php";
	printFestMenu(); ?>

<p class="title" align="center">Purchase Promotional Features</p>
<p>Here is a list of the promotional packages you have selected. If you would like
to add another package, click "Add another package". If you would like to remove a package,
click the "Remove" link next to the package you would like to remove.
<p>When you are ready to purchase these features, click "Purchase Features" below.
<p>
<form name="completelyUselessFormToGetAroundIE4Bug">
<table cellspacing="0" cellpadding="10">
	<tr>
		<th>Package Name</th>
		<th>Description</th>
		<th>Cost
	</tr>
<?	$totalCost = 0;
		$rowNum = 0;
		while($row = mysql_fetch_assoc($purchaseResult)) {
			$totalCost += $row["ppPrice"];
      $promotionResult = fd_query("select * from purchasePackagePromotion
        inner join promotion
        on purchasePackagePromotion.promotionID = promotion.id
        where purchasePackageID = " . $row["ppID"]);
?>
	<tr class="alt<?= $rowNum++ % 2 + 1 ?>">
		<td><?= $row["name"] ?>
		<td>
<?		$promoNum = 0;
      while($promoRow = mysql_fetch_assoc($promotionResult)) {
      	if($promoNum++ > 0)
         	echo  "<br>";?>
				<?= $promoRow["longName"] ?> (<?= formatDate(strtotime($promoRow["startDate"])) ?> -
						<?= formatDate(strtotime($promoRow["endDate"])) ?>)
<?    } ?>
		<td>$<?= $row["ppPrice"] ?>
		<td><a href="promoteFest.php?action=finishPackage&festID=<?= $form_festID ?>&remove=<?= $row["ppID"]?>">Remove</a>
	</tr>
<?	} ?>
	<tr>
		<td colspan="5"><hr color="#000000"></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><b>Total Cost:</b>
		<td><b>$<?= $totalCost ?></b>
	</tr>
	<tr>
		<td colspan="5" align="center"><button onClick="location.href='promoteFest.php?festID=<?= $form_festID ?>'">
				Add another package</button>
			<button onClick="location.href='promoteFest.php?action=finishPurchase&festID=<?= $form_festID ?>'">
				Purchase Features</button>
</table>
</form>

<?} elseif($form_action == "finishPurchase") {
    fd_filter_batch(array("form_festID"), true);
    if(!festEditAuthorized($_SESSION["user_id"], $form_festID)) {
      trigger_error("Access Error");
      die("You are not authorized to update this fest's info");
    }
		// assert authorized to edit info
	  $festRow = mysql_fetch_assoc(fd_query("select * from fests where ID = $form_festID"));

		// Get total price
		$ppResult = fd_query("select price from purchasePackage where
			purchaseID = " . $_SESSION["purchaseID"]);
		$totalCost = 0;
		while($row = mysql_fetch_assoc($ppResult))
			$totalCost += $row["price"];

		// Set purchase status, date, and cost
		fd_query("update purchase set status = 'notPaid', cost = $totalCost,
			datePurchased = now()
			where	id = " . $_SESSION["purchaseID"]);
?>
<html>
<head>
  <title>Payment of Promotional Purchase</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<? include("header.php"); ?>

<?include "festMenu.php";
	printFestMenu(); ?>

<p class="title">Payment of Promotional Purchase</p>

<p>Thank you for purchasing these promotional features.
<p>We use PayPal<super>TM</super> for handling our purchases, because it is
	secure, easy, and fast. To use it, please
	cick on the button below to log into PayPal
	and send the amount of <b>$<?= $totalCost ?></b> to <b><?= PAYPAL_EMAIL ?></b>.
<p>Please note that it might take up to several days to register your payment
	and activate your feature. As soon as we register your payment, we will inform
	you by email.

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?= PAYPAL_EMAIL ?>">
<input type="hidden" name="amount" value="<?= $totalCost ?>">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="return" value="http://www.filmdevil.com/paypalReturn.php?return=success">
<input type="hidden" name="cancel_return" value="http://www.filmdevil.com/paypalReturn.php?return=cancel">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="item_name"
	value="FilmDevil Promotional Features for <?= $festRow["title"] ?>">
<input type="hidden" name="item_number" value="purchaseID=<?= $_SESSION['purchaseID'] ?>">
<input class="image" type="image" src="https://www.paypal.com/images/x-click-but23.gif"
	border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>

<?
		$subject = "Thank you for your purchase";
		$body = "Hi, " . $_SESSION["user_displayName"] . " thank you for purchasing
promotional features for " . $festRow["title"] . ". In order to pay for
these features, please log into PayPal(tm) at http://www.paypal.com and
send the amount of \$$totalCost to " . PAYPAL_EMAIL . ".";
	  fd_mail($_SESSION["user_email"], $subject, $body, PROMOTION_EMAIL,
			PROMOTION_CONTACT);

		$subject = "Promotional Purchase Made";
		$body = "The user " . $_SESSION["user_displayName"] . " has purchased
promotional features for " . $festRow["title"] . " (#" . $form_festID
. ") for the total cost of \$$totalCost.
Goto " . URL_ROOT . "verifyPurchase.php to verify the payment. ";
	  fd_mail(PAYPAL_EMAIL, $subject, $body);

		// Now drop the purchaseID session var
		unset($_SESSION["purchaseID"]);

	} elseif($form_action == "createCustom") {
	  $festRow = mysql_fetch_assoc(fd_query("select * from fests where ID = $form_festID"));
		$nDead = getdate(strtotime($festRow["nDead"]));
		$preDead = getdate(mktime(0, 0, 0, $nDead["mon"] - 3, $nDead["mday"], $nDead["year"]));
?>
<html>

<head>
  <title>Create Custom Promotional Campaign</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body onLoad="setBoxes();">
<script language="javascript" src="common.js"></script>
<script language="javascript1.2">
	var monthlyCost = 0;
	var totalCost = 0;
	var currentNumMonths = 3;

	var radioCosts = new Array();
	function updateMonthlyCostRadio(box, cost) {
		if(radioCosts[box.name] == null)
			radioCosts[box.name] = 0;
		monthlyCost -= radioCosts[box.name];
		monthlyCost += cost;
		radioCosts[box.name] = cost;
		updateMonthlyCost();
	}

	function updateMonthlyCostCheckbox(box, cost) {
		if(box.checked) monthlyCost += cost;
		else monthlyCost -= cost;

		updateMonthlyCost();
	}

	function updateMonthlyCost() {
		document.all("monthlyCost").innerText = monthlyCost;
		updateTotalCost();
	}

	// numMonths can be null
	function updateTotalCost(numMonths) {
		if(numMonths)
			currentNumMonths = numMonths;
		totalCost = monthlyCost * currentNumMonths;
		document.all("totalCost").innerText = totalCost;
	}

	function resetCost() {
		monthlyCost = 0;
		radioCosts = new Array();
		updateMonthlyCost();
		updateTotalCost();
	}

	function verifyPurchase() {
		if(totalCost == 0) {
			alert("You have not selected any features");
			return false;
		}
	}

	function setBoxes() {
		setOption(purchaseForm.month, <?= $preDead["mon"] ?>);
		setOption(purchaseForm.day, <?= $preDead["mday"] ?>);
		setOption(purchaseForm.month, <?= $preDead["year"] ?>);
		setOption(purchaseForm.length, 3);
	}
</script>

<?include "festMenu.php";
	printFestMenu(); ?>

<p class="title" align="center">Create Custom Promotional Campaign</p>
<?
	  if($_SESSION["user_primaryType"] == "administrator"
	 		|| $_SESSION["user_primaryType"] == "devil") {
	    $festID = showFestSwitch("promoteFest.php");
	    // assert: $festID has the fest that we're editing and they have permission
	    // DISPLAY their options
	    $festRow = mysql_fetch_assoc(fd_query("select * from fests where ID = $festID"));
?>
<p>Here are the promotional options for <b><?= $festRow["title"] ?></b>.
We think you'll be happy with our wide selection of advertising services.
Your window for submissions is a crucial time and FilmDevil can provide the
kind of Web presence that will boost numbers and quality of submissions.
Our advertising options are served a la carte, so you can pick and choose
the type of promotions appropriate for your festival.  You can specify a
start date and number of months that you want the promotion to run for.
We've set the default start date to 3 months before your submission deadline.

<p>We have plans for building dozens of new features into FilmDevil in
the coming months, and that means more promotion options for your festival.
We will keep you notified as they are developed.

<form name="purchaseForm" action="promoteFest.php" method="post"
	onSubmit="return verifyPurchase();">
	<input type="hidden" name="festID" value="<?= $festID ?>">
	<input type="hidden" name="action" value="finishPackage">
	<input type="hidden" name="packageID" value="custom">

	<table cellpadding="5" cellspacing="0">
<?	  printHeadingRow("In the Search Results Section:"); ?>
		<tr>
			<td>
			<td colspan="3">If <?= $festRow["title"] ?> meets the search criteria,
				here is how it will be listed:
		</tr>
		<tr>
			<td><input name="listingType" type="radio" class="radio" value="normal" checked>
			<td>Normal Listing
			<td>
			<td>Free!
		</tr>
<?
			printPromoOptionRow("boldListing", "listingType");
			printPromoOptionRow("distinctBG", "listingType");
			printPromoOptionRow("logoListing", "listingType"); ?>
	<tr>
		<td colspan="4">&nbsp;
	</tr>
		<tr>
			<td>
			<td colspan="3">The following options can be added to increase your
				presence on FilmDevil:
		</tr>

<?		printPromoRow("topSearch");
			printPromoRow("flyingSlogan"); ?>
	<tr>
		<td colspan="4">&nbsp;
	</tr>

<?    printHeadingRow("On the Front Page as Featured Festival");
	    printPromoRow("featured"); ?>
	<tr>
		<td colspan="4">&nbsp;
	</tr>
<?    printHeadingRow("In the Profile Page");
?>
		<tr>
			<td>
			<td colspan="3">Each festival has a profile page.
				Filmmakers learning about one festival are interested in finding
				other festivals that are of the same caliber (of comparable costs,
				length, and size as well as other factors)
				<P>Your festival can be listed in the profile pages of
				similar festivals under a section called "discover similar festivals"
				Having a listing in this section means gaining even in more exposure
				when a filmmaker isn't looking at your profile.
		</tr>
<?		printPromoOptionRow("listed25", "similarFests");
			printPromoOptionRow("listed50", "similarFests");
			printPromoOptionRow("listed100", "similarFests");
			printPromoOptionRow("listed200", "similarFests");
			printPromoOptionRow("listed500", "similarFests"); ?>
	<tr>
		<td colspan="4"><hr color="#000000"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="right"><b>Cost per month:</b></td>
		<td><b>$<span id="monthlyCost">0.00</span></b>
	</tr>
	<tr>
		<td colspan="4">Now tell us when you would like to start your promotion,
			and for how long you would like it to run:</td>
	</tr>
	<tr>
		<td colspan="4">Start on
      <select name="month">
				<option value="1">Jan
	     	<option value="2">Feb
	     	<option value="3">Mar
	     	<option value="4">Apr
	     	<option value="5">May
	     	<option value="6">Jun
	     	<option value="7">Jul
	     	<option value="8">Aug
	     	<option value="9">Sep
	     	<option value="10">Oct
	     	<option value="11">Nov
	     	<option value="12">Dec
	    </select>
      <select name="day">
<?		for($i = 1; $i <= 31; $i++) { ?>
				<option value="<?= $i ?>"><?= $i ?>
<?		} ?>
      </select>
      <select name="year">
        <?	$date = getdate();
			for($i = $date["year"]; $i <= $date["year"] + 10; $i++) { ?>
            <option value="<?= $i ?>"><?= $i ?></option>
<? 		} ?>
      </select>
			and run for
			<select name="length" onChange="updateTotalCost(this.value);">
<?		for($i = 1; $i <= 12; $i++) { ?>
				<option value="<?= $i ?>"><?= $i ?>
<?		} ?>
      </select> months
    </td>
	</tr>
	<tr>
		<td colspan="4"><hr color="#000000"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="right"><b>Total Cost:</b></td>
		<td><b>$<span id="totalCost">0.00</span></b>
	</tr>
	<tr>
		<td colspan="4" align="center">
      <input type="submit" class="button" name="purchase" value="Purchase Features"
        onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">
      <input type="reset" class="button" name="reset" value="Reset"
        onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';"
				onClick="resetCost();">
    </td>
	</table>
</form>
<?	} else {
			trigger_error("Access error to festival " . $festRow["ID"]);
			die("Access Error");
		}
	} elseif($form_action == "setDate") {
		// They chose a standard package so give them the time options
		fd_filter_batch(array("form_packageID"));
		fd_filter_batch(array("form_festID"), true);
		$packageRow = mysql_fetch_assoc(
			fd_query("select * from package where id = '$form_packageID'"));
		$festRow = mysql_fetch_assoc(
			fd_query("select nDead from fests where id = $form_festID"));

		$nDead = getdate(strtotime($festRow["nDead"]));
		$preDead = getdate(mktime(0, 0, 0, $nDead["mon"], $nDead["mday"], $nDead["year"]));
?>
<html>

<head>
  <title>Purchase Promotional Package</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body onLoad="setBoxes();">
<script language="javascript" src="common.js"></script>
<script language="javascript">
	function setBoxes() {
		setOption(form.month, <?= $preDead["mon"] ?>);
		setOption(form.day, <?= $preDead["mday"] ?>);
		setOption(form.month, <?= $preDead["year"] ?>);
	}
</script>
<?include "festMenu.php";
	printFestMenu(); ?>

<p class="title" align="center">Purchase Promotional Package</p>
<p>You can specify the date that the you want the <?= $packageRow["name"] ?> Promotional Package to end on.
We've set the default end date to be your submission deadline.
Please click "Next" when done.
<form name="form" action="promoteFest.php" action="post">
	<input type="hidden" name="festID" value="<?= $form_festID ?>">
	<input type="hidden" name="packageID" value="<?= $form_packageID ?>">
	<input type="hidden" name="action" value="finishPackage">
<p>End on
  <select name="month">
    <option value="1">Jan
    <option value="2">Feb
    <option value="3">Mar
    <option value="4">Apr
    <option value="5">May
    <option value="6">Jun
    <option value="7">Jul
    <option value="8">Aug
    <option value="9">Sep
    <option value="10">Oct
    <option value="11">Nov
    <option value="12">Dec
  </select>
  <select name="day">
<?    for($i = 1; $i <= 31; $i++) { ?>
    <option value="<?= $i ?>"><?= $i ?>
<?    } ?>
  </select>
  <select name="year">
    <?  $date = getdate();
  for($i = $date["year"]; $i <= $date["year"] + 10; $i++) { ?>
        <option value="<?= $i ?>"><?= $i ?></option>
<?    } ?>
  </select>
  <br><br><input type="submit" class="button" value="Next"
    onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">
</form>

<?} else {
		// STARTING PAGE
?>
<html>

<head>
  <title>Purchase Promotional Features</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<?include "festMenu.php";
	printFestMenu(); ?>

<p class="title" align="center">Purchase Promotional Features</p>
<?  $festID = showFestSwitch("promoteFest.php");
    $festRow = mysql_fetch_assoc(fd_query("select * from fests where ID = $festID"));
?>
<p>Here are the promotional options for <b><?= $festRow["title"] ?></b>.
We think you'll be happy with our wide selection of advertising services.
Your window for submissions is a crucial time and FilmDevil can provide the
kind of Web presence that will boost numbers and quality of submissions.
<p>For a limited time we are offering heavily discounted promotional packages,
displayed here. If you would rather create a customized "à la carte" promotional
campaign to fit your specific needs, goto the
<a href="promoteFest.php?action=createCustom&festID=<?= $festID ?>">customized promotional
campaign</a> page.
<p>We have plans for building dozens of new features into FilmDevil in
the coming months, and that means more promotion options for your festival.
We will keep you notified as they are developed.
<p>
<table width="70%" align="center" cellspacing="10" cellpadding="5">
	<tr>
<? 	printPackageCell("bronze", $festID);
		printPackageCell("silver", $festID); ?>
	</tr>
	<tr>
<? 	printPackageCell("gold", $festID);
		printPackageCell("platinum", $festID); ?>
	</tr>
	<tr>
		<td colspan="2" style="background-color: #DDDDDD">
			<a href="promoteFest.php?action=createCustom&festID=<?= $festID ?>">
				<span class="sectionHeading">Custom Promotional Campaign</span></a>
			<br><br>Choose the promotional features that will best fit your fest
			<br><br><a href="promoteFest.php?action=createCustom&festID=<?= $festID ?>">Choose the custom package</a>
		</td>
	</tr>
</table>
<?}
	include "footer.php";
?>
</body>
</html>