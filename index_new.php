<?

	require_once "dbFunctions.php";



function printCalendarFest($row) {

	if($GLOBALS["calendarDayNum"]++ > 0) ?>
	<div style="padding:0px;"><a class="calendar_fest" href="action.php?type=2&id=<?= $row["ID"] ?>" title="<?= formatDate(strtotime($row["startDate"])) ?> - <?= formatDate(strtotime($row["endDate"])) ?>"><?= $row["title"] ?></a></div>

<? }

function printCalendarDay($day) {

	$GLOBALS["calendarDayNum"] = 0; ?>

<div class="calendar_day" style="padding:0px;"><?= $day ?></div>
<?	$calendarArray = $GLOBALS["calendarArray"];
	if(isset($calendarArray[$day]))
		{
		foreach($calendarArray[$day] as $festRow)
		{
			printCalendarFest($festRow);
		}
?>
<?			}

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
<link rel="stylesheet" type="text/css" href="styles/listing_new.css">
	<title>filmd rev0.3</title>
</head>

<body>

<!-- logo -->
<div class="box_structure" style="float:none;">
<a href="#" class="devil_title">filmdevil.com</a><br />
<span class="tagline">&raquo;&raquo; filmdevil.com: the internet's largest independent film festival database</span>
</div>
<!-- logo -->

<!-- top menu -->
	<div class="topmenu">
		<div class="topmenu_box"><a class="topmenu_link" href="#">login</a></div>
		&nbsp;-&nbsp;
		<div class="topmenu_box"><a class="topmenu_link" href="#">submit your film</a></div>
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
<div id="left" class="box_structure" style="width:18%; display:inline; float:left;">
	<div class="box_inner_structure" style="background-color:#330000;">
		<div class="box_title">filmdevil next five deadlines</div>
		<div class="box_inner" style="display:list-item;">
		<ol class="festival">
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
				      <li><a class="festival_left" href="action.php?type=2&id=<?= $row["ID"] ?>"
				        title="Deadline: <?= formatDate($deadline) ?>">
				        <?= $row["title"] ?></a></li>
			<!-- spacer --><div class="spacer_style" style="color:#FFFFFF;">...</div><!-- spacer -->
			<?} ?>
		</ol>
		</div>
	</div>
	<div class="spacer_style">...</div>
	<div class="box_inner_structure" style="background-color:#330000;">
		<div class="box_title">filmdevil database stats</div>
		<div class="box_inner" style="font-size:80%;">
			total fests: <?= $totalFestsRow["count"] ?><br />
			this week: <br>
				&nbsp added: <?= $addedFestsRow["count"] ?><br />
				&nbsp updated: <?= $updatedFestsRow["count"] ?><br />
		</div>
	</div>
</div>

<!-- left panel -->

<!-- center panel -->
<div id="center" style="display:inline; float:left; width:57%; background-color:#660000;">
	<div class="box_structure">
		<div class="box_inner_structure" style="background-color:#330000;">
			<div class="box_title">filmdevil featured fest</div>
			<div class="box_inner">
				<div class="feature_title"><?= $topFeaturedFest["title"] ?></div>
				<div class="feature_text"><?= $topFeaturedFest["descriptGen"] ?> [ <a href="action.php?type=2&id=<?= $topFeaturedFest["ID"] ?>">more</a> ] </div>
			</div>
		</div>
	</div>

	<div class="box_inner_structure" style="background-color:#330000;">
		<div class="box_title">filmdevil festival calendar</div>
		<div class="box_inner" style="text-align:center;">
				<div class="calendar_box" style="display:inline; background-color:#EEEEEE;">
					<? printCalendarDay("Sunday"); ?>
				</div>
				<div class="calendar_box" style="display:inline; background-color:#FFFFFF; font-size:1px; width:1px;">.</div>
				<div class="calendar_box">
					<? printCalendarDay("Monday"); ?>
				</div>
				<div class="calendar_box" style="display:inline; background-color:#FFFFFF; font-size:1px; width:1px;">.</div>
				<div class="calendar_box">
					<? printCalendarDay("Tuesday"); ?>
				</div>
				<div class="calendar_box" style="display:inline; background-color:#FFFFFF; font-size:1px; width:1px;">.</div>
				<div class="calendar_box">
					<? printCalendarDay("Wednesday"); ?>
				</div>
				<div class="calendar_box" style="display:inline; background-color:#FFFFFF; font-size:1px; width:1px;">.</div>
				<div class="calendar_box">
					<? printCalendarDay("Thursday"); ?>
				</div>
				<div class="calendar_box" style="display:inline; background-color:#FFFFFF; font-size:1px; width:1px;">.</div>
				<div class="calendar_box">
					<? printCalendarDay("Friday"); ?>
				</div>
				<div class="calendar_box" style="display:inline; background-color:#FFFFFF; font-size:1px; width:1px;">.</div>
				<div class="calendar_box">
					<? printCalendarDay("Saturday"); ?>
				</div>

<!-- 			<li style="display:inline;"><a href="#" class="calendar">&nbsp;tuesday&nbsp;</a></li>
			<li style="display:inline;"><a href="#" class="calendar">&nbsp;wednesday&nbsp;</a></li>
			<li style="display:inline;"><a href="#" class="calendar">&nbsp;thursday&nbsp;</a></li>
			<li style="display:inline;"><a href="#" class="calendar">&nbsp;friday&nbsp;</a></li>
			<li style="display:inline;"><a href="#" class="calendar">&nbsp;saturday&nbsp;</a></li>
			<li style="display:inline;"><a href="#" class="calendar">&nbsp;sunday&nbsp;</a></li> -->
		</div>
	</div>

	<div class="box_structure">
		<div class="box_inner_structure" style="background-color:#330000;">
			<div class="box_title">filmdevil recent review</div>
			<div class="box_inner">

<div class="review_title"><?= $recentReviewRow["title"] ?></div>
<?$displayName = $recentReviewRow["name"];
if(empty($displayName)) $displayName = "Anonymous"; ?>
	<div class="posted_by">posted by <a class="reviewer_link" href="#" onClick="showUser(<?= $recentReviewRow["id"] ?>); return false;">
		<?= $displayName ?></a> on <?= formatDate(strtotime($recentReviewRow["date"])) ?>
	about <a href="action.php?type=2&id=<?= $recentReviewRow["festID"] ?>">
		<?= $recentReviewRow["festTitle"] ?></a></div>

<!-- legacy code        <table width="100%">
          <tr>
            <td>Films:
            <td><img src="images/icon<? print $recentReviewRow["filmRating"] ?>white.gif" align="absmiddle">
            <td>Location:
            <td><img src="images/icon<? print $recentReviewRow["locationRating"] ?>white.gif" align="absmiddle">
          </tr>
          <tr>
            <td>Organization:
            <td><img src="images/icon<? print $recentReviewRow["orgRating"] ?>white.gif" align="absmiddle">
            <td>People:
            <td><img src="images/icon<? print $recentReviewRow["peopleRating"] ?>white.gif" align="absmiddle">
          </tr>
        </table> legacy code -->

<!-- review ratings -->

<div style="width:100%;">

<!-- row one -->
<div class="review_box">
	<div class="review_box_inner">
	<div class="title_position">	<div class="review_header">Films:</div>		</div>
		<div style="display:inline; float:right;"><img src="images/icon<? print $recentReviewRow["filmRating"] ?>white.gif" align="absmiddle"></div>
	</div>
		<div style="width:4%; display:inline; float:left;"></div>
	<div class="review_box_inner">
	<div class="title_position">	<div class="review_header">Organization:</div>	</div>
		<div style="display:inline; float:right;"><img src="images/icon<? print $recentReviewRow["orgRating"] ?>white.gif" align="absmiddle"></div>
	</div>
</div>
<!-- row one -->

<!-- row two -->
<div class="review_box">
	<div class="review_box_inner">
	<div class="title_position">	<div class="review_header">Location:</div>	</div>
		<div style="display:inline; float:right;"><img src="images/icon<? print $recentReviewRow["locationRating"] ?>white.gif" align="absmiddle"></div>
	</div>
		<div style="width:4%; display:inline; float:left;"></div>
	<div class="review_box_inner">
	<div class="title_position">	<div class="review_header">People:</div>	</div>
		<div style="display:inline; float:right;"><img src="images/icon<? print $recentReviewRow["peopleRating"] ?>white.gif" align="absmiddle"></div>
	</div>
</div>
<!-- row two -->

</div>
<!-- review ratings -->

	      <div class="review_text"><?= $recentReviewRow["body"] ?></div>
			</div>
		</div>
	</div>
</div>
<!-- center panel -->

<!-- right panel -->
<div id="right" class="header_bgcolor" style="display:inline; float:right; width:25%;">
<div class="box_structure" style="float:right;">
	<div class="box_inner_structure" style="background-color:#330000;">
		<div class="box_title">filmdevil find a fest near you</div>
		<div class="box_inner">
			<form action="listing_new.php" method="get">
				<select style="font-size: 12px" name="location" onChange="this.form.submit();">
				  <?= $locationBox["data"] ?>
				</select>
		</div>
	</div></form>

	<div class="box_inner_structure" style="background-color:#330000;">
		<div class="box_title">filmdevil top five</div>
		<div class="box_inner">
		<ol>
			<?while($row = mysql_fetch_assoc($bestRatingResult)) { ?>
			      <li><a class="festival" href="action.php?type=2&id=<?= $row["ID"] ?>">
			        <?= $row["title"] ?></a></li>
			<?} ?>
		</ol>
		</div>
	</div>
</div>
</div>
<!-- right panel -->

</body>

</html>