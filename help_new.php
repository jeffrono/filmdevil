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
	<title>filmd rev0.3:help</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="styles/listing_new.css">
	<script src="mouseTrack.js"></script>

<script language="Javascript"><!--

function searchUpcoming() {
	// setStillAccepting();
	var date = new Date();
	document.form1.startmonth.value = date.getMonth() + 1;
	document.form1.startday.value = date.getDate();
	document.form1.startyear.value = date.getFullYear();
		document.form1.submit();
}

function searchCheap() {
	// setStillAccepting();
	document.form1.fee.value = "20";
		document.form1.submit();
}

function searchHiRate() {
	// setStillAccepting();
	document.form1.minRatingButton.value = "4";
	// setRadio("minRatingButton", "4");
		document.form1.submit();
}

function searchStudent() {
	// setStillAccepting();
	document.form1.studentfriendly.value = "4";
	// document.all("studentfriendly").checked = true	;
		document.form1.submit();
}

function searchFestList() {
	// setStillAccepting();
	document.form1.festList.value = "true";
	// document.all("festList").checked = true	;
		document.form1.submit();
}

//--></script>

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
			<div class="box_title">filmdevil.com: help</div>
			<div class="box_inner" style="padding:5px;">
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; title</p>
				</div>

				<div class="section_text">
				<p>
					Please enter the exact title of the festival. Do not use words like "a" or "the" in the beginning of the title. Also avoid using abbreviations, extraneous punctuation, or any kind of numbers, dates, or years in the title.
Bad title: " The 2nd Annual NY Int'l Independent Film & Video Festival, in LA, Feb .2002 "
Good title "New York International Independent Film and Video Festival, The"
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; venue</p>
				</div>

				<div class="section_text">
				<p>
					Please provide as much information as possible about where the films will be played. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; organization</p>
				</div>

				<div class="section_text">
				<p>
					Please provide the name, address, and telephone number by which people can contact the festival and submit their films. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; submissions</p>
				</div>

				<div class="section_text">
				<p>
					Although some festivals do not accept film submissions, like the Maryland Film Fest, which is "by invite only", we would like those festivals to update as much of their information as possible to ensure that we have the most complete database of fests. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; web</p>
				</div>

				<div class="section_text">
				<p><ul>
					<li>Email: the email of the primary contact for the festivals through whom more information can be requested. </li>
				</p><p>
					<li>URL: Full Web address of the Festival's website including "http://". </li>
				</p><p>
					<li>Logo URL: Please enter the full web address of the Festival's logo (if available), including "http://". Ideally the logo should be as close to a square as possible, and in JPEG format, not exceeding 200 pixels in either dimension.
					<br />Example: "http://www.filmdevil.com/images/logo.jpg" </li>
				</p><p>
					<li>Application URL: Full Web address of the Festival's submission application (PDF, DOC, HTML, TEXT formats, etc.).
					<br />Example: "http://www.filmdevil.com/images/submissionform.doc" </li></ul>
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; dates</p>
				</div>

				<div class="section_text">
				<p>
					Please enter the next start and end date of the Festival. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; submission deadlines</p>
				</div>

				<div class="section_text">
				<p>
					Please enter the early, normal, and late submission deadlines. It is required that you enter the normal deadline. The early and late ones are optional and can be left blank. Make sure to fill out the month, day, and year on each line. If one of the deadlines doesn't exist, make sure it's blank.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; statistics</p>
				</div>

				<div class="section_text">
				<p>
<ul>
	<li>Please enter the number of films submitted to the festival last year </li>
	<li>Please enter the number of official selections (films that were actually shown last year)</li>
</ul>
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; prizes / competition</p>
				</div>

				<div class="section_text">
				<p>
					Please describe in as much detail as possible the kinds of prizes and/or awards that are given out in each category of competition. Prizes may include, cash, equipment, scholarships, gift certificates, etc.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; theme</p>
				</div>

				<div class="section_text">
				<p>
					Please describe in a few sentences the festival's theme for this year. Please leave it blank if the festival has no theme and accepts films about any subject matter.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; tagline</p>
				</div>

				<div class="section_text">
				<p>
					Please provide your festival's slogan (25 words or less).
					<br />Example: "World's Friendliest Film Festival"
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; description</p>
				</div>

				<div class="section_text">
				<p>
					Please describe the festival in as much detail as possible. Include things like when the festival was founded, what its main purpose is, what types of films and audiences it caters to, etc.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; professional description</p>
				</div>

				<div class="section_text">
				<p>
					Please address the concerns of professional filmmakers applying to your festival regarding competition, prizes, rules, limitations, etc. Also answer questions like: "Will investors, distributors, or studio representatives be attending, and how can I get them to attend my screening, and network with them?", "What kind of distribution deals have past films gotten?"
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; student description</p>
				</div>

				<div class="section_text">
				<p>
					Please address the concerns of student and amateur filmmakers applying to your festival regarding discounts, student categories, and special considerations, etc. Also answer questions like the following: "How many college filmmakers get in or attend this festival?", "My film caters to a college audience, how young will the screening committee be?", "I couldn't afford Tom Hanks or big explosions, by what criteria are you going to judge my low budget student film?", "How easy is it to network with other students?", etc.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; student oriented?</p>
				</div>

				<div class="section_text">
				<p>
					Check this box if the festival has special consideration for student and ameteur filmmakers, such as discounts, separate student competitions or categories, or if the festival takes place on a college campus.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; submission checklist</p>
				</div>

				<div class="section_text">
				<p>
					Please list out the contents of a complete application for this film Festival, (IE production stills, director's biography, etc.).
				</p>
				</div>

				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				<div class="box_structure_stack">
					<p class="header_style">&nbsp; eligibility requirements</p>
				</div>

				<div class="section_text">
				<p>
					Please describe the requirements for submitting to this festival or for getting special discounts or consideration. Please include language, subtitle, or location requirements for the film. (IE "must be a Kansas City resident for a 15% discount", "Entries must have a bona fide connection to Westchester County, which includes any ONE of the following conditions to satisfy entry requirements: Entry shot on location in Westchester County OR Directed, produced, shot (DP) or edited by a Westchester County resident OR Written by a Westchester County resident")
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; categories</p>
				</div>

				<div class="section_text">
				<p>
					Please check all the categories in which you accept films. If a category is not listed, please mention it in the suggestion box at the end of the update procedure.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; projections</p>
				</div>

				<div class="section_text">
				<p>
					Please check all the formats that your festival is capable of projecting in. If a projection format is not listed, please mention it in the suggestion box at the end of the update procedure.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; fees</p>
				</div>

				<div class="section_text">
				<p>
					Please use this fee table to represent your festival's specific fee structure as closely as possible. If you offer discounts for certain groups of people, other than students (IE Westchester County residents, etc.) fill in the 'OTHER' field with the discounted fee. We understand this isn't as complete a fee table as some festivals require, but we want to offer filmmakers an approximate, but concise table of fees, to make this tedious process easier.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; fee note</p>
				</div>

				<div class="section_text">
				<p>
					Use this field to explain any of the fees, the 'OTHER' field, and any special discounts or rates. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; what sets you apart</p>
				</div>

				<div class="section_text">
				<p>
					What is your festival best known for? Have you premiered any films that became very successful? How's the food, the scenery, the city life, night life? What sets you apart? Is it the type of filmmakers that attend? Is it the free promotional material? Feel free to be as creative and original as you like.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; party scene</p>
				</div>

				<div class="section_text">
				<p>
					Please describe how wild your parties get. Is there one every night? Or just a big bash at the end? Free booze? Does it take place in the Playboy Mansion?
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				 
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; press coverage</p>
				</div>

				<div class="section_text">
				<p>
					What kind of media attention does your festival get? (Magazines, newspapers, websites, radio, TV stations, etc.) How far-reaching is this coverage? 
				</p>
				</div>


			</div>
		</div>
	</div>

</div>
<!-- center panel -->

</body>

</html>