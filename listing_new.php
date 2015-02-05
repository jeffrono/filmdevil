<?
	require "dbFunctions.php";

function printRow($row) {
	$showPopUp = isPromoActive($row["ID"], "flyingSlogan");
	$showLogo = isPromoActive($row["ID"], "logoListing");
	if(isPromoActive($row["ID"], "distinctBG")) {
		$listType = "distinctBG";
		$normalClass = "distinctBG";
		$highlightClass = "distinctBGHighlighted";
		$selectedClass = "distinctBGSelected";
	} elseif(isPromoActive($row["ID"], "boldListing")) {
		$listType = "boldListing";
		$normalClass = "boldListing";
		$highlightClass = "boldListingHighlighted";
		$selectedClass = "boldListingSelected";
	} else {
		$listType = "normal";
		$normalClass = "notHighlighted";
		$highlightClass = "highlighted";
		$selectedClass = "selected";
	}

$this_row = $row["ID"];

$fest = mysql_fetch_array(fd_query("select * from fests where ID=$this_row"));

?>

<!-- inserted listing code -->

				<!-- result listing -->
				<div class="result_box">
					<div class="result_inner">
						<div class="result_rating"><? print round($row["rating"]); ?>/5</div>
					<div>

<a id="titleLink" style="float:left;" class="search_result" href="action_new.php?type=2&id=<? print $row["ID"] ?>">
	<?= limitWordLength($row["title"], 16) ?>
</a>
						</div>
						<div style="float:right; padding:0px;" class="search_result"><?= getDisplayLocation($row) ?></div>
					</div>
					<div class="feature_text" style="padding:0px;">&nbsp; <? print $fest["descriptGen"] ?></div>
				</div>
				<!-- result listing -->

				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->

<!-- inserted listing code -->

<? if($showPopUp) {
	$extraInfos = $GLOBALS["extraInfos"];
	$extraInfos[] = array("id" => $row["ID"], "tagline" => $row["tagline"]);
	}
}
?>

<html>

<head>
	<title>filmd rev0.3:listing page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="styles/listing_new.css">
	<script language="Javascript" src="quicksearch.js"></script>
</head>

<body>

<?
	fd_connect();

	$querys="select distinct fests.id, fests.* as count from fests";

	#projtable p, cattable c, fees e
	$tables = "";
	$query1 = "";
	$searchDesc = array();
	$category = "Any"; // In case it is not set below
	while(list($key, $val) = each($_REQUEST)) {
		if ($key == 'category' && $val != 'Any') {
			$category = fd_filter($val, true);
			$tables.=", cattable";
			$query1.=" AND cattable.CID = $category AND cattable.FID = fests.ID";
			$catResult = mysql_fetch_assoc(fd_query("select cat from categories where ID = "
				. $category));
			$searchDesc[] = "accept <b>" . $catResult["cat"] . "</b> films";
		}
		if ($key == 'projection' && $val != 'Any') {
			$projection = fd_filter($val, true);
			$query1.=" AND projtable.PID = $projection AND projtable.FID = fests.ID";
			$tables.=", projtable";
			$catResult = mysql_fetch_assoc(fd_query("select proj from projections where ID = "
				. $projection));
			$searchDesc[] = "project <b>" . $catResult["proj"] . "</b> films";
		}
		if ($key == 'startmonth') {$startmonth= fd_filter($val, true);}
		if ($key == 'startday') {$startday=fd_filter($val, true);}
		if ($key == 'startyear') {$startyear=fd_filter($val, true);}
		if ($key == 'startmonth2') {$startmonth2=fd_filter($val, true);}
		if ($key == 'startday2') {$startday2=fd_filter($val, true);}
		if ($key == 'startyear2') {$startyear2=fd_filter($val, true);}
		if ($key == 'deadmonth') {$deadmonth=fd_filter($val, true);}
		if ($key == 'deadday') {$deadday=fd_filter($val, true);}
		if ($key == 'deadyear') {$deadyear=fd_filter($val, true);}
		if ($key == 'location' && $val != 'Any') {
        	$val = fd_filter($val);
        	$query1.=" AND (fests.continent = '$val' OR fests.region = '$val' OR fests.country = '$val' OR fests.vState = '$val')";
					$searchDesc[] = "are in <b>$val</b>";
    }
		if ($key == 'fee' && $val != "") {
			$fee= fd_filter($val, true);
			$searchDesc[] = "have a submission fee of <b>less than \$$fee</b>";
		}
		if ($key == 'cash') {
			$query1.=" AND fests.award=2";
			$searchDesc[] = "offer <b>cash prizes</b>";
		}
		if ($key == 'minRatingButton' && $val != 0) {
			$rating = fd_filter($val, true);
    	$query1.= " AND fests.rating >= $rating";
			$searchDesc[] = "have at least a <b>$rating out of 5 rating</b>";
		}
		if ($key == 'numreviews' && $val != 0) {
			$numReviews = fd_filter($val, true);
    	$query1.=" AND fests.numReviews >= $numReviews";
			$searchDesc[] = "have at least <b>$numReviews reviews</b>";
		}
		if ($key == 'studentfriendly' && $val != "") {
			$query1.=" AND fests.stFriend = 1";
			$searchDesc[] = "have special consideration for <b>students</b>";
		}
		if ($key == 'textfield' && $val != '') {
    	$val = fd_filter($val);
			$query1.=" AND (fests.subCheck LIKE '%$val%' OR fests.title LIKE '%$val%' "
                . "OR fests.descriptGen LIKE '%$val%' OR fests.descriptStu LIKE '%$val%' "
                . "OR fests.descriptPro LIKE '%$val%' OR fests.theme LIKE '%$val%' "
                . "OR fests.eligibility LIKE '%$val%' OR fests.feeNote LIKE '%$val%')";
			$searchDesc[] = "contain the term <b>'$val' in the description</b>";
    }
		if($key == "festList" && $val != "") {
			if(isLoggedIn()) {
	      $tables .= ", userFest";
	      $query1 .= " AND userFest.userID = " . $_SESSION["user_id"]
	    		. " AND userFest.festID = fests.ID AND userFest.relation = 'festList'";
			$searchDesc[] = "are in <b>my FestList</b>";
			} else {
				$msg = "You must first login to search festivals that are on your FestList";
			}
		}
		if($key == "startOn") {
			$startOn = fd_filter($val, true);
		}
		if($key == "count") {
			$count = fd_filter($val, true);
		}
}

$startDateExists = false;
$endDateExists = false;
if (isset($startmonth) && $startmonth != 0 && $startday != 0 && $startyear != 'any') {
	$query1.=" AND fests.startDate >= '$startyear-$startmonth-$startday'";
	$startDateExists = true;
}

if (isset($startmonth2) && $startmonth2 != 0 && $startday2 != 0 && $startyear2 != 'any') {
	$query1.=" AND fests.startDate <= '$startyear2-$startmonth2-$startday2'";
	$endDateExists = true;
}

if($startDateExists && $endDateExists) {
	$searchDesc[] = "run between <b>"
		. formatDate(mktime(0, 0, 0, $startmonth, $startday, $startyear))
		. " and " . formatDate(mktime(0, 0, 0, $startmonth2, $startday2, $startyear2))
		. "</b>";
} elseif($startDateExists) {
	$searchDesc[] = "run after <b>"
		. formatDate(mktime(0, 0, 0, $startmonth, $startday, $startyear)) . "</b>";
} elseif($endDateExists) {
	$searchDesc[] = "run before <b>"
		. formatDate(mktime(0, 0, 0, $startmonth2, $startday2, $startyear2)) . "</b>";
}

if (isset($deadmonth) && $deadmonth != 0 && $deadday != 0 && $deadyear != 'any') {
	$query1.=" AND fests.lDead >= '$deadyear-$deadmonth-$deadday'";
	$searchDesc[] = "have a <b>submission deadline after "
		. formatDate(mktime(0, 0, 0, $deadmonth, $deadday, $deadyear)) . "</b>";
}


if(isset($fee) && $fee!=''){
	if($category == 'Any'){
		$query1.=" AND (fees.Other<$fee AND fees.Other>-1 AND fees.festID = fests.ID) OR (fees.Feature<$fee and fees.Feature>-1 AND fees.festID = fests.ID) OR (fees.Student<$fee AND fees.Student>-1 AND fees.festID = fests.ID) OR (fees.Short<$fee AND fees.Short>-1 AND fees.festID = fests.ID)";
		$tables.=", fees";
	}
	elseif($category == '11'){
		$query1.=" AND fees.festID=fests.ID AND fees.Student<$fee AND fees.Student>-1";
		$tables.=", fees";
	}
	elseif($category == '1'){
		$query1.=" AND fees.festID=fests.ID AND fees.Feature<$fee AND fees.Feature>-1";
		$tables.=", fees";
	}
	elseif($category == '4'){
		$query1.=" AND fees.festID=fests.ID AND fees.Short<$fee AND fees.Short>-1";
		$tables.=", fees";
	}
	else{
		$query1.=" AND fees.festID=fests.ID AND fees.Other<$fee AND fees.Other>-1";
		$tables.=", fees";
	}
}

if(!isset($startOn))
	$startOn = 0;

$endOn = $startOn + MAX_SEARCH_ROWS;
$queryOrder = " ORDER BY fests.title ASC ";
$queryLimit = " LIMIT $startOn, " . MAX_SEARCH_ROWS;

$tablesTop = $tables . " left join purchase on purchase.festID = fests.ID
	left join purchasePackage on purchasePackage.purchaseID = purchase.ID
	left join purchasePackagePromotion
	on purchasePackagePromotion.purchasePackageID = purchasePackage.id
	left join promotion on purchasePackagePromotion.promotionID = promotion.ID ";
$whereTop = " and purchasePackagePromotion.startDate <= now()
	and purchasePackagePromotion.endDate >= now()
	and shortName = 'topSearch' and purchase.status = 'activated'";
$queryTop = $querys . $tablesTop . " where 1=1 " . $query1 . $whereTop
	. $queryOrder;

if(!isset($count)) {
	$countResult = mysql_fetch_assoc(fd_query("select count(id) as count from fests "
		. $tables . " where 1 = 1 " . $query1 . $queryOrder));
	$count = $countResult["count"];
	if($count == "") $count = 0;
	$_REQUEST["count"] = $count;
}
if($endOn > $count)
	$endOn = $count;

//echo $queryTop . "<p>";
//$result = fd_query ("(" . $queryTop . ") UNION (" . $queryNormal . ")");
$resultTop = fd_query($queryTop);

$topFests = "";
while($row = mysql_fetch_assoc($resultTop)) {
	if($topFests != "")
		$topFests .= ", ";
	$topFests .= $row["ID"];
}

if(mysql_num_rows($resultTop) > 0) {
	mysql_data_seek($resultTop, 0);
	$removeTop = " and fests.ID not in ($topFests)";
}	else
	$removeTop = "";

$queryNormal = $querys . $tables . " where 1=1 " . $query1
	. $removeTop . $queryOrder . $queryLimit;
$result = fd_query($queryNormal);

?>

<form name="form1" method="post" action="listing_new.php">
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

<div id="left" class="box_structure" style="display:inline; width:25%;">
	<div class="box_structure">
		<div class="box_inner_structure">
			<div class="box_title">festival search</div>
			<div class="box_inner" style="font-size:80%;">
				<div class="box_structure" style="text-align:right; font-family:arial; background-color:#EEEEEE; color:#000000;">&raquo; <a href="advanced_new.php" class="advanced_search">advanced search</a></li></div>
<!-- temporary links -->
		<input type="hidden" name="startmonth" value="">
		<input type="hidden" name="startday" value="">
		<input type="hidden" name="startyear" value="">
		<input type="hidden" name="fee" value="">
		<input type="hidden" name="minRatingButton" value="">
		<input type="hidden" name="studentfriendly" value="">
		<input type="hidden" name="festList" value="">

			<div class="quick_search_text">&raquo; <a onClick="searchUpcoming(); return false;" accesskey="u" href="#" class="quick_search_link">upcoming</a></li></div>
			<div class="quick_search_text">&raquo; <a onClick="searchCheap(); return false;" accesskey="c" href="#" class="quick_search_link">cheap</a></li></div>
			<div class="quick_search_text">&raquo; <a onClick="searchHiRate(); return false;" accesskey="h" href="#" class="quick_search_link">highly rated</a></li></div>
			<div class="quick_search_text">&raquo; <a onClick="searchStudent(); return false;" accesskey="s" href="#" class="quick_search_link">student-friendly</a></li></div>
			<div class="quick_search_text">&raquo; <a onClick="searchFestList(); return false;" accesskey="o" href="#" class="quick_search_link">on my FestList</a></li></div>
<!-- temporary links -->
					<div class="spacer_style" style="color:#FFFFFF;">...</div>
				<div style="display:none; background-color:#EEEEEE; font-weight:bold;"><input type="submit" value="go."></div>

				</div>
			</div>
		</div>
		<div class="spacer_style" style="color:#660000;">...</div>
	</div>
</div>

<!-- left panel -->

<!-- center panel -->
<div id="center" class="box_structure" style="width:75%;">
	<div class="box_structure">
		<div class="box_inner_structure">
			<div class="box_title">filmdevil fest search results</div>
			<div class="box_inner" style="padding:5px;">
				<div class="box_structure" style="background-color:#EEEEEE;">
<? if(mysql_num_rows($result) == 1) { ?>
	There is <b>1</b> festival
<? } else { ?>
	There are <b><?= $count ?></b> festivals
<? }
	  if(!empty($searchDesc)) {
			echo " that ";
	    for($i = 0; $i < count($searchDesc); $i++) {
	      if($i > "0") {
					if($i != count($searchDesc) - 1) echo ", ";
					else echo " and ";
				}
	      echo $searchDesc[$i];
	      $first = false;
	    }
			echo ". ";
		}
?>
<?	if(mysql_num_rows($result) == 0)
			echo "<p>Sorry, there were no results for that search. <p>Please search again.";
		else {
			if($count > $endOn || $startOn > 0) {
	      $firstFest = mysql_fetch_assoc($result);
	      mysql_data_seek($result, mysql_num_rows($result) - 1);
	      $lastFest = mysql_fetch_assoc($result);
	      mysql_data_seek($result, 0); ?>
	      <!--Displaying festivals <?= $startOn + 1 ?> through <?= $endOn ?>.-->
	      <br /><span style="font-size:75%;">Displaying festivals "<?= limitStringLength($firstFest["title"], 20) ?>" through
	        "<?= limitStringLength($lastFest["title"], 30) ?>"
<?		}
			if($startOn > 0) {
				$_REQUEST["startOn"] = $startOn - MAX_SEARCH_ROWS; ?>
			<a href="listing_new.php?<?= urlEncodeArray($_REQUEST) ?>">
				<span class="arrow">&lt;&lt;</span></a>
<?		}
			if($count > $endOn) {
				$_REQUEST["startOn"] = $endOn; ?>
			<a href="listing_new.php?<?= urlEncodeArray($_REQUEST) ?>">
				<span  class="arrow">&gt;&gt;</span></a>
<?		}
		} ?></span>
				</div>
<? if(!empty($msg)) { ?>
	<div class="error"><?= $msg ?></div>
<? }

?>
<!-- spacer --><div class="spacer_style" style="color:#FFFFFF;">...</div><!-- spacer -->
<?
$extraInfos = array();

if(mysql_num_rows($resultTop) > 0) {
	// Print top rows randomly
	$topArray = makeDblArray($resultTop);
	$resultTopKeys = array_rand($topArray, count($topArray));
	if(is_array($resultTopKeys)) {
	  foreach($resultTopKeys as $key)
	    printRow($topArray[$key]);
	} else
			printRow($topArray[$resultTopKeys]);
}

// Print regular rows in alphebetical order
while($row = mysql_fetch_array($result))
	printRow($row);
?>
<? foreach($extraInfos as $info) {
	 	if($info["tagline"] != "") {?>
<span id="extraInfo<?= $info["id"] ?>" class="festExtraInfo"><?= $info["tagline"] ?></span>
<?	}
	}
?>

<!-- results php code block -->

				<!-- <div style="float:right;">[ next page ]</div> -->
			</div>
		</div>
	</div>

</div>
<!-- center panel -->

</body>

</html>