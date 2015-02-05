<?
	require "gzdoc.php";
	require_once "dbFunctions.php";

	function printCalendarFest($row) {
		if($GLOBALS["calendarDayNum"]++ > 0) echo "<li>"; ?>
		<a class="festival" href="action.php?type=2&id=<?= $row["ID"] ?>"
	     title="<?= formatDate(strtotime($row["startDate"])) ?> - <?= formatDate(strtotime($row["endDate"])) ?>">
	     <?= $row["title"] ?></a>
<?}

	function printCalendarDay($day) {
		$GLOBALS["calendarDayNum"] = 0; ?>
					<td class="calendar" valign="top"><div class="day_style"><?= $day ?></div>
<?	$calendarArray = $GLOBALS["calendarArray"];
		if(isset($calendarArray[$day])) {
			foreach($calendarArray[$day] as $festRow)
				printCalendarFest($festRow); ?>
					</td>
<?	}
	}

	fd_connect();

	$timerArray = array();
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
	else {
		$featuredFestsArray = randomizeArray(makeDblArray(fd_query("select * from fests where
			status = 'ok' and startDate > now() and descriptGen <> '' and logoURL <> ''
			order by startDate limit 20")));
		$topFeaturedFest = array_shift($featuredFestsArray);
		$featuredFestsArray = array();
	}

	$locationBox = mysql_fetch_assoc(fd_query("select data from data where id = 'locationBox'"));

?>

<html>

<head>
<link rel="stylesheet" type="text/css" href="listing.css">
	<title>Welcome to FilmDevil</title>

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

.tagline {font-size:10pt; color:#FFFFFF; border-style:dotted; border-style:dotted; border:#663300; background-color:#660033;}

.calendar {font-size:9pt; color:#000000; background-color:#DDDDDD;}

div {padding:2px; font-family:trebuchet ms; }

.box_title {width:100%; background-color:#000000; font-size:100%; color:#FFFFCC; font-weight:bold; padding:5px;}
.box_inner {padding:5px; width:100%; background-color:#FFFFFF;}

.day_style {background-color: #660000, color:#FFFFFF;}

ol {margin-left:1.7em;}

option, input {color:#0000FF; background-color:#EEEEEE; }

.question {
	/* font-style: italic; */
}

.box {
	width:100%;
	border-width:1px;
	border-style:dotted;
	border-color:#000000;
	background-color:#330033;
}

.separator {
	height:2px;
	font-size:1px;
	color:#660000;
}
--></style>

</head>

<body>
<? include "header.php";	startTimer($timerArray, true); ?>

<script>
	function showUser(reviewID) {
		var display = document.all("userInfo" + reviewID).style.display == 'block'
			? 'none' : 'block';
		document.all("userInfo" + reviewID).style.display = display;
	}
</script>

<table cellpadding="2" cellspacing="0" width="100%">
<tr>
	<td valign="top" width="20%">
<!-- left panel-->
	  <div class="box">
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

		<div class="separator">&nbsp;</div>

		<div class="box">
			<div class="box_title">filmdevil database stats</div>
			<div class="box_inner" style="font-size:80%;">
				total fests: <?= $totalFestsRow["count"] ?><br>
				this week: <br>
				&nbsp; added: <?= $addedFestsRow["count"] ?><br>
				&nbsp; updated: <?= $updatedFestsRow["count"] ?><br>
			</div>
		</div>
	<!-- left panel-->
	</td>
	<!-- middle panel -->
	<td valign="top" width="50%">
		<div class="box">
			<div class="box_title">the most comprehensive film database ever</div>
			<div class="box_inner" style="font-size:80%;">
				<div class="question">Are you a <b>filmmaker</b> looking to <b>submit your film?</b>
					<a href="help.php#filmmaker">[more]</a></div>
				<div class="question">Are you a <b>festival</b> looking to <b>promote yourself?</b>
					<a href="showPromotions.php">[more]</a></div>
			</div>
		</div>
		<div class="separator">&nbsp;</div>
<?if(isset($topFeaturedFest)) { ?>
		<!-- start featured fest -->
		<div class="box">
			<div class="box_title">filmdevil featured fest</div>
			<div class="box_inner">
				<!-- start main featured fest -->
				<div class="feature_title"><?= $topFeaturedFest["title"] ?></div>
				<div class="feature_text">
	        <table width="100%" border="0" cellspacing="5" cellpadding="0">
	          <tr>
	            <td align="center" valign="middle" width="100">
								<a href="action.php?type=2&id=<?= $topFeaturedFest["ID"] ?>"
									title="<?= $topFeaturedFest["tagline"] ?>">
	             <? if ($topFeaturedFest["logoURL"] != '' && ONLINE) {
                  $imgSize = getScaledImageSize($topFeaturedFest["logoURL"], 100, 100);
               ?>
               		<img src="<? print $topFeaturedFest["logoURL"] ?>" width=<?= $imgSize["width"] ?> height=<?= $imgSize["height"] ?> border="0">
               <? } else { ?>
               		<img src="images/plainface.gif" border="0">
               <? } ?>
								</a>
							</td>
         			<td><div class="feature_text"><?= $topFeaturedFest["descriptGen"] ?>
							[ <a href="action.php?type=2&id=<?= $topFeaturedFest["ID"] ?>">more</a> ]
							</div></td>
						</tr>
					</table>
	     	</div>
				<!-- end main featured fest -->

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
               		<img src="images/plainface.gif" border="0">
<? 			} ?>

									<?= $fest["title"] ?>
								</a>
							</span>
<?		} ?>
	      </div>
<?	} ?>
			</div> <!-- end box_inner --->
		</div>
		<!-- end featured fest -->

		<div class="separator">&nbsp;</div>
<?} ?>

	  <div class="box">
	    <div class="box_title">filmdevil festival calendar</div>
	    <div class="box_inner" style="text-align:center;">
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
	    </div>
	  </div>

	  <div class="separator">&nbsp;</div>

	  <div class="box">
	    <div class="box_title">filmdevil recent review</div>
	    <div class="box_inner">
	      <div class="review_title"><?= $recentReviewRow["title"] ?></div>
<?$displayName = $recentReviewRow["name"];
	if(empty($displayName)) $displayName = "Anonymous"; ?>
	      <div class="posted_by">posted by <a class="reviewer_link" href="#" onClick="showUser(<?= $recentReviewRow["id"] ?>); return false;">
	        <?= $displayName ?></a> on <?= formatDate(strtotime($recentReviewRow["date"])) ?>
	        about <a href="action.php?type=2&id=<?= $recentReviewRow["festID"] ?>">
	        <?= $recentReviewRow["festTitle"] ?></a></div>
	      <div id="userInfo<?= $recentReviewRow["id"] ?>" style="display: none">
<?if(!empty($recentReviewRow["email"])) { ?>
	        <a href="#" onClick="MM_openBrWindow('contactUser1.php?type=5&id=<?= $recentReviewRow["id"] ?>','ContactUser','resizable=yes,width=450,height=300'); return false;">
	          Contact this User</a>
	        <br>
<?} ?>
	        <? if($recentReviewRow["URL"] != ''){ ?>
	        Website: <a href="<? print $recentReviewRow["URL"] ?>" target="_blank">
	        <? print $recentReviewRow["URL"] ?></a>
	        <br>
	        <? } ?>
	        <? if($recentReviewRow["type"] != ''){ ?>
	        <? print $recentReviewRow["type"] ?>
	        <br>
	        <? } ?>
	        <? if($recentReviewRow["favMov"] != ''){ ?>
	        Favorite film: </a><a href="http://us.imdb.com/Find?for='<? print $recentReviewRow["favMov"] ?>'" target="_blank">
	        <? print $recentReviewRow["favMov"] ?></a>
	        <br>
	        <? } ?>
	        <? if($recentReviewRow["favWeb"] != ''){ ?>
	        Favorite film website: </a> <a href="<?= getAbsoluteURL($recentReviewRow["favWeb"]) ?>" target="_blank">
	        <? print $recentReviewRow["favWeb"] ?></a>
	        <br>
	        <? } ?>
	      </div>

        <table width="100%">
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
        </table>

	      <div class="review_text"><?= $recentReviewRow["body"] ?></div>
	    </div>
	  </div>
	<!-- center panel -->
	</td>
	<td valign="top" width="30%">
	<!-- right panel -->
		<div class="box">
	    <div class="box_title">filmdevil find a fest near you</div>
	    <div class="box_inner" style="width: 100%">
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

	  <div class="box">
	    <div class="box_title">filmdevil top five reviewed</div>
	    <div class="box_inner">
	      <ol>
<?while($row = mysql_fetch_assoc($bestRatingResult)) { ?>
	      <li><a class="festival" href="action.php?type=2&id=<?= $row["ID"] ?>">
	        <?= $row["title"] ?></a></li>
<?} ?>
	      </ol>
	    </div>
	  </div>

		<div class="separator">&nbsp;</div>

	  <div class="box">
	    <div class="box_title">filmdevil top five viewed today</div>
	    <div class="box_inner">
	      <ol>
<?while($row = mysql_fetch_assoc($mostViewedResult)) { ?>
	      <li><a class="festival" href="action.php?type=2&id=<?= $row["ID"] ?>">
	        <?= $row["title"] ?></a></li>
<?} ?>
	      </ol>
	    </div>
		</div>
		<!-- right panel -->
	</td>
</tr>
<tr>
		<td colspan="3" align="center">
	    <div style="width:400; background-color:#660000;">
	      <div class="box">
	        <div class="box_inner" style="text-align: center">
	    <?if(noFrames()) { ?>
	        <p>Please see <a href="listAllFests.php?noFrames">our list of festivals</a>
	    <?}
	      include "footer.php"; ?>
	      </div>
	    </div>
		</td>
	</tr>
</table>
<?	endTimer($timerArray, true); ?>
</body>

</html>
<? gzdocout(1, 1); ?>