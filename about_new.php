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
	<title>filmd rev0.3:about</title>
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
			<div class="box_title">filmdevil.com: about us</div>
			<div class="box_inner" style="padding:5px;">
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; what we do</p>
				</div>

				<div class="section_text">
				<p>
					For the independent filmmaker, finding festivals can be a daunting task. FilmDevil.com recognizes the filmmaking community's need for a one-stop tool to find key information on any festival in the world. ALL FOR FREE! With FilmDevil, filmmakers can find festivals that suit their specific needs by quickly and easily answering questions like: 
				</p>

				<p style="font-style:italic;">
					"Show me all the festivals still accepting submissions that accept Feature Documentaries, cost less than $50 to submit to, are in the Northeast Region of the U.S., and can project BetaSP." 
				</p>

				<p>
					Previously, questions like these could only be answered after poring over dozens of film festival lists and visiting hundreds of web sites. We've already done the work for you - we've indexed over 1600 festivals. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; festlist</p>
				</div>

				<div class="section_text">
				<p>
					Our most exciting feature is FestList - Filmmakers can keep an eye on festivals of interest by adding them to a personalized list. FilmDevil will not only have these festivals profiles available whenever you log on, but will email updates, submission deadlines approaching, and other useful information so you'll never miss another deadline. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; review festivals</p>
				</div>

				<div class="section_text">
				<p>
					FilmDevil also lets you write a festival review. Did you enjoy the films? Was it well organized? If you've gone to a festival, you can help other filmmakers make an informed decision on whether or not it is appropriate for them. Did the festival make an effort to promote your film? What was the audience like? The location? What could have been improved? We hope filmmakers and enthusiasts will write about their experiences at the festivals they have attended. 
				</p>
				<p>
					If you are a filmmaker looking to submit your film to festivals, then you can trust FilmDevil.com to help you choose the right ones for you.
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; unique features for festivals</p>
				</div>

				<div class="section_text">
				<p>
				FilmDevil.com isn't only useful for filmmakers. For no charge, every festival in the world has a listing in our database, and the ability to log in and update their information. Our unique search engine provides an equal opportunity playing field for film festivals to gain visibility. The more information festivals contribute to their profile, the more users will find the festival in their searches. 
				</p>
				<p>
				We also provide free monthly emails that allow festivals to monitor how well they are doing on our site, including statistics like: how many people viewed your festival profile, how this compares with the rest of the festivals, and how many people went from your FilmDevil profile to your festival web site. 
				</p>
				<p>
				If a festival wants to stand out from the crowd, we offer a wide variety of affordable advertising options. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; connecting filmmakers to film festivals</p>
				</div>

				<div class="section_text">
				<p>
				Filmdevil.com connects filmmakers and enthusiasts to film festivals, making it easier than ever before to find festivals using the most comprehensive festival resource in the world. 
				</p>
				</div>
				<!-- spacer --><div style="height:25px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack" style="background-color:#FFFFFF;">
					<p style="text-transform:none; color:#000000; font-weight:bold;">Who We Are</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; jeff novich - founder, developer</p>
				</div>

				<div class="section_text">
				<p>
				After making a feature film in college, Jeff began his quest to submit his film to festivals. Unfortunately, he ran into the same problem that all filmmakers face - outdated lists, poorly designed festival websites, and not knowing which festivals were appropriate. He realized there had to be a better way, and began work on FilmDevil.com. 
				</p>
				<p>
				Jeff graduated from Johns Hopkins University in 2002 with a double major in computer science and physics, and a very low GPA. He is currently attending the Columbia School of Journalism in New York City. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; jesse himmelstein - developer</p>
				</div>

				<div class="section_text">
				<p>
				Jesse is a computer guru and has developed the backend for FilmDevil.com. In fact, he also came up with Jeff's movies. He has worked on dozens of websites in the past. He will one day create awesome and unique video games with his cousin, Nathan Ruell (otherwise known as AlphaNate). 
				</p>
				<p>
				Jesse graduated from Johns Hopkins University in 2002 with a degree in computer engineering. He is currently attending the INSA in Toulouse, France, and living with his beautiful girlfriend. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; zak volnyanskiy - graphics</p>
				</div>

				<div class="section_text">
				<p>
				Zak is the laziest guy in the world, but he also happens to be a talented artist, and created all the cool graphics for FilmDevil.com. 
				</p>
				<p>
				Zak graduated from Iona College in 2002 with an economics degree, and currently works at Citigroup, pretending to do IT work for them that he really has no idea how to do. 
				</p>
				</div>
				<!-- spacer --><div style="height:15px; font-size:2px; color:#FFFFFF;">...</div><!-- spacer -->				
				<div class="box_structure_stack">
					<p class="header_style">&nbsp; ron bell - web designer</p>
				</div>

				<div class="section_text">
				<p>
				RJ is responsible for the look of FilmDevil.com and laying out the web pages. He has worked on several web sites in recent years. 
				</p>
				</div>

			</div>
		</div>
	</div>

</div>
<!-- center panel -->

</body>

</html>