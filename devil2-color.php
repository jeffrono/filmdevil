<?
	require_once "dbFunctions.php";

	function printCalendarFest($row) { ?>
		<li><a class="festival" href="action.php?type=2&id=<?= $row["ID"] ?>"
	     title="<?= formatDate(strtotime($row["startDate"])) ?> - <?= formatDate(strtotime($row["endDate"])) ?>">
	     <?= $row["title"] ?></a>
<?}

	function printCalendarDay($day) { ?>
					<td class="calendar" valign="top"><div class="day_style"><?= $day ?></div>
<?	$calendarArray = $GLOBALS["calendarArray"];
		if(isset($calendarArray[$day])) {
			foreach($calendarArray[$day] as $festRow)
				printCalendarFest($festRow); ?>
					</td>
<?	}
	}

	fd_connect();

	$nextDeadlineResult = fd_query("select ID, title, eDead, nDead, lDead from fests
		where eDead >= NOW() or nDead >= NOW() or lDead >= NOW()
		order by lDead, nDead, eDead limit 5");

	$bestRatingResult = fd_query("select distinct(fests.ID), fests.title from fests
		order by rating desc limit 5");

	$mostViewedResult = fd_query("select distinct(fests.ID), fests.title from fests
		inner join stat on stat.festID = fests.ID
		where statTypeID = 'profile' and stat.startDate >= now() and stat.endDate <= now()
		order by rating desc limit 5");

	$recentReviewRow = mysql_fetch_assoc(fd_query("select reviews.*, fests.title as
		festTitle from reviews inner join
		fests on reviews.festID = fests.ID order
		by date desc limit 1"));

	$totalFestsRow = mysql_fetch_assoc(fd_query("select count(id) as count from fests"));
	$updatedFestsRow = mysql_fetch_assoc(fd_query("select count(id) as count from fests
		where to_days(now()) - to_days(lastDate) <= 7"));
	$addedFestsRow = mysql_fetch_assoc(fd_query("select count(id) as count from fests
		where to_days(now()) - to_days(created) <= 7"));

	$currentDate = getdate();
	$startWeek = mktime(0, 0, 0, $currentDate["mon"],
		$currentDate["mday"] - $currentDate["wday"]);
	$calendarResult = fd_query("select dayname(startDate) as dayName,
		dayofmonth(startDate) as dayOfMonth, startDate,
		endDate, ID, title from fests where startDate >= "
		. formatSQLDate($startWeek) . " and startDate < date_add("
		. formatSQLDate($startWeek) . ", interval 7 day) order by startDate");
	$calendarArray = array();
	while($row = mysql_fetch_assoc($calendarResult)) {
		$calendarArray[$row["dayName"]][] = $row;
	}

	$featuredFestsArray = randomizeArray(makeDblArray(fd_query("select distinct(fests.ID),
		fests.*
		from fests inner join purchase on purchase.festID = fests.ID
		inner join purchasePackage on purchase.ID = purchasePackage.purchaseID
		inner join purchasePackagePromotion
		on purchasePackage.ID = purchasePackagePromotion.purchasePackageID
		inner join promotion on promotion.ID = purchasePackagePromotion.promotionID
		where purchase.status = 'activated' and purchasePackagePromotion.startDate <= now()
		and purchasePackagePromotion.endDate >= now() and promotion.shortName = 'featured'")));
	if(count($featuredFestsArray) > 0)
		$topFeaturedFest = array_shift($featuredFestsArray);

	$locationBox = mysql_fetch_assoc(fd_query("select data from data where id = 'locationBox'"));
?>

<html>

<head>
<link rel="stylesheet" type="text/css" href="listing.css">
	<title>filmd rev0.2</title>

<style type="text/css"><!--

body {background-color:#660000;}

a {color:#000000; font-size:100%;}
	.devil_title {letter-spacing:5px; font-size:120%; color:#FFFFFF;}
	.topmenu_box {display:inline;}
	.topmenu_link {letter-spacing:1px; font-size:90%; font-weight:normal; color:#CCCCFF;}
	.festival {font-family:trebuchet ms; font-size:80%;}
	/*	.feature_title {font-size:105%; background-color:#000066; color:#CCCCFF;} */
		.feature_text, .review_text {font-size:90%;}
		.feature_title, .review_title {font-size:90%; font-family:helvetica; font-weight:bold; letter-spacing:1.5px; color:#FFFFFF; background-color:#660000;}
		.reviewer_link {font-family:trebuchet ms;}

.tagline {font-size:10pt; color:#FFFFFF; background-color:#660033;}

.calendar {font-size:9pt; color:#440000; background-color:#660000;}

div {padding:2px; font-family:trebuchet ms;}

.box_title {width:100%; background-color:#000000; font-size:100%; color:#FFFFCC; font-weight:bold; padding:5px;}
.box_inner {padding:5px; width:100%; background-color:#FFFFFF;}

.day_style {color:#FFFFFF;}

ol {margin-left:1.7em;}

option, input {color:#0000FF; background-color:#EEEEEE; }

--></style>

</head>

<body>

<!-- logo -->
<div style="width:100%; background-color:#660000;">
<a href="#" class="devil_title">filmdevil.com</a> <br />
<span class="tagline">&raquo;&raquo; filmdevil.com: the internet's largest independent film festival database</span>
</div>
<!-- logo -->

<!-- top menu -->
	<div style="width:100%; background-color:#000000; text-align:center;">
		<div class="topmenu_box"><a class="topmenu_link" href="#">login</a></div>
		&nbsp;-&nbsp;
		<div class="topmenu_box"><a class="topmenu_link" href="#">advertise with us</a></div>
		&nbsp;-&nbsp;
		<div class="topmenu_box"><a class="topmenu_link" href="#">find a fest</a></div>
		&nbsp;-&nbsp;
		<div class="topmenu_box"><a class="topmenu_link" href="#">promote your fest</a></div>
		&nbsp;-&nbsp;
		<div class="topmenu_box"><a class="topmenu_link" href="#">about filmdevil.com</a></div>
	</div>
<!-- top menu -->

<!-- left panel -->
<div id="left" style="display:inline; width:20%; float:left; background-color:#660000;">
	<div style="width:100%; float:left; background-color:#660000;">
		<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
			<div class="box_title">filmdevil next five deadlines</div>
			<div class="box_inner" style="display:list-item;">
			<ol>
<?while($row = mysql_fetch_assoc($nextDeadlineResult)) {
	  $eDead = strtotime($row["eDead"]);
	  $nDead = strtotime($row["nDead"]);
	  $lDead = strtotime($row["lDead"]);
	  if($eDead > time())
	    $deadline = $eDead;
	  elseif($nDead > time())
	    $deadline = $nDead;
	  else
	    $deadline = $lDead; ?>
				<li><a class="festival" href="action.php?type=2&id=<?= $row["ID"] ?>"
					title="Deadline: <?= formatDate($deadline) ?>">
					<?= $row["title"] ?></a></li>
<?} ?>
			</ol>
			</div>
		</div>
		<div style="height:2px; font-size:1px; color:#660000;">...</div>
		<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
			<div class="box_title">filmdevil database stats</div>
			<div class="box_inner" style="font-size:80%;">
				total fests:<?= $totalFestsRow["count"] ?> <br />
				<span style="font-weight:bold; font-size:80%;">this week</span>
				<br>
				<li>added: <?= $addedFestsRow["count"] ?></li><br>
				<li>updated: <?= $updatedFestsRow["count"] ?></li><br>
			</div>
		</div>
	</div>
</div>

<!-- left panel -->

<!-- center panel -->
<?if(isset($topFeaturedFest)) { ?>
	<!-- start featured fest -->

<div id="center" style="display:inline; float:left; width:60%; background-color:#660000;">
	<div style="width:100%; float:left; background-color:#660000;">
		<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
			<div class="box_title">filmdevil featured fest</div>
			<div class="box_inner">
				<div class="feature_title"><?= $topFeaturedFest["title"] ?></div>
				<div class="feature_text">
<!-- featured fest information -->

	<!-- logo image -->
	<a href="action.php?type=2&id=<?= $topFeaturedFest["ID"] ?>" title="<?= $topFeaturedFest["tagline"] ?>">

	<? if ($topFeaturedFest["logoURL"] != '' && ONLINE)
		{
			$imgSize = getScaledImageSize($topFeaturedFest["logoURL"], 100, 100); ?>

	<img src="<? print $topFeaturedFest["logoURL"] ?>" width=<?= $imgSize["width"] ?> height=<?= $imgSize["height"] ?> border="0">

	<?	} else {	?>

	<img src="images/logosmall.jpg" border="0">

	<?	}	?>

	</a>
	<!-- logo image -->

	<!-- fest description -->
	<?= $topFeaturedFest["descriptGen"] ?>
	[ <a href="action.php?type=2&id=<?= $topFeaturedFest["ID"] ?>">more</a> ]
	<!-- fest description -->

<!-- xyz fest is a great example of impeccable planning and flawless execution.
				with wide variety and an innovative approach, the xyz festival has all the signs of being the show of the millenium. [ <a href="#">more</a> ] -->

<!-- featured fest information -->
				</div>
	
			</div>
		</div>
	</div>

<!-- "other featured fests" section 

<?	if(count($featuredFestsArray) > 0) { ?>
				<div class="feature_title">other featured festivals</div>
				<div class="feature_text">
<?		foreach($featuredFestsArray as $fest) { ?>
	            <span style="width: 100px; text-align: center;">
								<a href="action.php?type=2&id=<?= $fest["ID"] ?>"
									 title="<?= $fest["tagline"] ?>">
<? 			if ($fest["logoURL"] != '' && ONLINE) {
        	$imgSize = getScaledImageSize($fest["logoURL"], 100, 100); ?>
               		<img src="<? print $fest["logoURL"] ?>" width=<?= $imgSize["width"] ?> height=<?= $imgSize["height"] ?> border="0">
<? 			} else { ?>
               		<img src="images/logosmall.jpg" border="0">
<? 			} ?>

									<?= $fest["title"] ?>
								</a>
							</span>
<?		} ?>
	      </div>
<?	} ?>
			</div>
		</div>
	</div>

"other featured fests" section -->

	<div style="height:2px; font-size:1px; color:#660000;">...</div>

<?} ?>

	<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
		<div class="box_title">filmdevil festival calendar</div>
		<div class="box_inner" style="text-align:center;">
<!-- calendar -->

<table width="100%">
	<tr>
		<?printCalendarDay("Sunday"); ?>
		<?printCalendarDay("Monday"); ?>
		<?printCalendarDay("Tuesday"); ?>
		<?printCalendarDay("Wednesday"); ?>
		<?printCalendarDay("Thursday"); ?>
		<?printCalendarDay("Friday"); ?>
		<?printCalendarDay("Saturday"); ?>

<!-- 	<li style="display:inline;"><a href="#" class="calendar">&nbsp;<span class="day_style">sunday</span>&nbsp;</a></li> -->

	</tr>
</table>

<!-- calendar -->
		</div>
	</div>

	<div style="height:2px; font-size:1px; color:#660000;">...</div>

<!-- calendar -->

<table width="100%">
	<tr>
		<?printCalendarDay("Sunday"); ?>
		<?printCalendarDay("Monday"); ?>
		<?printCalendarDay("Tuesday"); ?>
		<?printCalendarDay("Wednesday"); ?>
		<?printCalendarDay("Thursday"); ?>
		<?printCalendarDay("Friday"); ?>
		<?printCalendarDay("Saturday"); ?>

<!-- 	<li style="display:inline;"><a href="#" class="calendar">&nbsp;<span class="day_style">sunday</span>&nbsp;</a></li> -->

	</tr>
</table>

<!-- calendar -->
		</div>
	</div>

	<div style="height:2px; font-size:1px; color:#660000;">...</div>

	<div style="width:100%; float:left; background-color:#660000;">
		<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
			<div class="box_title">filmdevil recent review</div>
			<div class="box_inner">
				<div class="review_title"><?= $recentReviewRow["title"] ?></div>
				<div class="posted_by">posted by <a class="reviewer_link" href="#" onClick="showUser(<?= $recentReviewRow["id"] ?>); return false;">
					<?= $recentReviewRow["name"] ?></a> on <?= formatDate(strtotime($recentReviewRow["date"])) ?>
					about <a href="action.php?type=2&id=<?= $recentReviewRow["festID"] ?>">
				<div class="review_text"><?= $recentReviewRow["body"] ?></div>
	
			</div>
		</div>
	</div>
</div>
<!-- center panel -->

<!-- right panel -->
<div id="right" style="display:inline; float:right; width:20%; background-color:#660000;">
<div style="width:100%; float:right; background-color:#660000;">
	<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
		<div class="box_title">filmdevil find a fest near you</div>
		<div class="box_inner">
		<?if(noFrames()) { ?>
			<form action="indexTop.php" method="get">
		<?} else { ?>
			<form action="searchFrameset.php" method="get">
		<?} ?>
			<input type="hidden" name="goto" value="searchFrameset">
			<select style="font-size: 12px" name="location" onChange="this.form.submit();">
				<?= $locationBox["data"] ?>
			</select>
		</div>
	</div></form>

	<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
		<div class="box_title">filmdevil top five</div>
		<div class="box_inner">
		<ol>
			<?while($row = mysql_fetch_assoc($mostViewedResult)) { ?>
      			<li>
				 <a class="festival" href="action.php?type=2&id=<?= $row["ID"] ?>">	<?= $row["title"] ?></a>
			</li> <?} ?>
		</ol>
		</div>
	</div>
</div>
</div>
<!-- right panel -->

<!-- noframes notice -->
	    <div style="width:400; background-color:#660000;">
	      <div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000; background-color:#330033;">
	        <div class="box_inner" style="text-align: center">
	    <?if(noFrames()) { ?>
	        <p>Please see <a href="listAllFests.php?noFrames">our list of festivals</a>
	    <?}
	      include "footer.php"; ?>
	      </div>
	    </div>
<!-- noframes notice -->

</body>

</html>