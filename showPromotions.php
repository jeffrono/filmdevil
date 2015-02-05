<?
	$cacheLimiter = "nocache";
	require_once "dbFunctions.php";
	require_once "showFestSwitch.php";
	fd_connect();
?>
<html>

<head>
  <title>Promote A Festival</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<?require("header.php"); ?>
<?if(isLoggedIn() && ($_SESSION["user_primaryType"] == "administrator"
		|| $_SESSION["user_primaryType"] == "devil")) {
		include "festMenu.php";
		printFestMenu();
	}?>

<p class="title" align="center">Promote A Festival</p>
<?
	if(isLoggedIn() && ($_SESSION["user_primaryType"] == "administrator"
		|| $_SESSION["user_primaryType"] == "devil")) {
		$festID = showFestSwitch("showPromotions.php");
		// assert: $festID has the fest that we're editing and they have permission
		// DISPLAY their status and their options
		$festRow = mysql_fetch_assoc(fd_query("select * from fests where ID = $festID"));
		$crazyJoinQuery = "select * from purchase
			inner join purchasePackage on purchase.id = purchasePackage.purchaseID
			inner join package on purchasePackage.packageID = package.id
			inner join purchasePackagePromotion
			on purchasePackagePromotion.purchasePackageID = purchasePackage.id
			inner join promotion on purchasePackagePromotion.promotionID = promotion.id ";
		$runningResult = fd_query($crazyJoinQuery .
			"where status = 'activated' and festID = $festID
			and startDate <= now() and endDate >= now() order by startDate");
		$futureResult = fd_query($crazyJoinQuery .
			"where festID = $festID and
			status = 'activated' and startDate > now() order by startDate");
		$purchasedResult = fd_query("select * from purchase
			where festID = $festID and status != 'inProgress'
			order by datePaid");
?>
	<p>Here are the promotional features for <b><?= $festRow["title"] ?></b> that
		have already been purchased. Would you like to
		<a href="promoteFest.php?festID=<?= $festID ?>">purchase additional features</a>?

	<div class="section">
	  <span class="sectionHeading">Activated Features</span>
	  <div class="sectionBody">
<?	if(mysql_num_rows($runningResult) == 0)
			echo "There are no features activated at this time. You can purchase features below.";
		else { ?>
			These features are currently active and can be seen on the site.<br>
			<table cellspacing="0" cellpadding="5">
				<tr>
					<th>Feature</th>
					<th>Running Time</th>
					<th>Comments</th>
				</tr>
<?		$rowNum = 0;
			while($runningRow = mysql_fetch_assoc($runningResult)) {
				$rowNum++; ?>
				<tr class='alt<?= $rowNum % 2 + 1 ?>'>
					<td>
<?			$listedFeature = preg_match("/listed/", $runningRow["shortName"]);
				if($listedFeature) { ?>
					<a href="similarFests.php?festID=<?= $festID ?>">
<?			} ?>
					<?= $runningRow["longName"] ?>
<?			if($listedFeature) { ?>
					</a>
<?			} ?>
					<td><?= formatDate(strtotime($runningRow["startDate"])) ?> -
						<?= formatDate(strtotime($runningRow["endDate"])) ?></td>
					<td>Part of the <i><?= $runningRow["name"] ?></i> package
					</td>
				</tr>
<? 		} ?>
			</table>
<?	} ?>

	<div class="section">
	  <span class="sectionHeading">Planned Features</span>
	  <div class="sectionBody">
<?	if(mysql_num_rows($futureResult) == 0)
			echo "You have no planned features for this festival. You can purchase features below.";
		else { ?>
			These features will be activated where their running time is reached.<br>
			<table cellspacing="0" cellpadding="5">
				<tr>
					<th>Feature</th>
					<th>Running Time</th>
					<th>Comments</th>
				</tr>
<?		$rowNum = 0;
			while($futureRow = mysql_fetch_assoc($futureResult)) {
				$rowNum++; ?>
				<tr class='alt<?= $rowNum % 2 + 1 ?>'>
					<td><?= $futureRow["longName"] ?></td>
					<td><?= formatDate(strtotime($futureRow["startDate"])) ?> -
						<?= formatDate(strtotime($futureRow["endDate"])) ?></td>
					<td>Part of the <i><?= $futureRow["name"] ?></i> package
					</td>
				</tr>
<? 		} ?>
			</table>
<?	} ?>

		</div>
	</div>

	<div class="section">
	  <span class="sectionHeading">Purchase History</span>
	  <div class="sectionBody">
<?	if(mysql_num_rows($purchasedResult) == 0)
			echo "You have never bought any promotional features for this festival.";
		else { ?>
			<super>*</super> Note: it may take up to several days to process your payments.
			<table cellspacing="0" cellpadding="5">
				<tr>
					<th>Purchased</th>
					<th>Payment Recieved </th>
					<th>Cost</th>
					<th>Included Features</th>
				</tr>
<?		$rowNum = 0;
			while($purchasedRow = mysql_fetch_assoc($purchasedResult)) {
				$rowNum++; ?>
				<tr class='alt<?= $rowNum % 2 + 1 ?>'>
					<td><?= formatDate(strtotime($purchasedRow["datePurchased"])) ?></td>
					<td>
<?			if($purchasedRow["status"] == "notPaid") { ?>
						<span class="deadlinePassed">Payment not received<super>*</super></span>
<?			} elseif($purchasedRow["status"] == "disapproved") { ?>
						<span class="deadlinePassed">Disapproved</span>
<?			} else {
						echo formatDate(strtotime($purchasedRow["datePaid"]));
				} ?>
					</td>
					<td>
<?			if($purchasedRow["status"] == "notPaid") { ?>
					<span class="deadlinePassed"><?= formatMoney($purchasedRow["cost"]) ?>
<?			} else
						echo formatMoney($purchasedRow["cost"]); ?>
					</td>
					<td>
<?			$featureResult = fd_query("select * from
					purchasePackage inner join purchasePackagePromotion
					on purchasePackage.id = purchasePackagePromotion.purchasePackageID
					inner join promotion
					on purchasePackagePromotion.promotionID = promotion.id
					where purchaseID = " . $purchasedRow["id"]);
				$featureNum = 0;
				while($featureRow = mysql_fetch_assoc($featureResult)) {
					if($featureNum > 0)
						echo "<br>"; ?>
					<?= $featureRow["longName"] ?> (<?= formatDate(strtotime($featureRow["startDate"])) ?> -
						<?= formatDate(strtotime($featureRow["endDate"])) ?>)
<?				$featureNum++;
				} ?>
					</td>
				</tr>
<? 		} ?>
			</table>
<?	} ?>
		</div>
	</div>

	<p>Would you like to
		<a href="promoteFest.php?festID=<?= $festID ?>">purchase additional features</a>?

<? } else { ?>
	FilmDevil offers many choices for festival promotions to fit all budgets and
	marketing goals. If you run a festival, please login and return to this page
	to see what we can offer you.
<? }
	include "footer.php";
?>
</body>
</html>