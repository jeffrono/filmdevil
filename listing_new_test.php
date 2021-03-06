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
?>
    <tr id="tableRow" onClick="selectRow(this.rowIndex, '<?= $selectedClass ?>', '<?= $normalClass ?>');"
			onMouseOver="highlightRow(this, '<?= $highlightClass ?>');
				<? if($showPopUp) echo "showExtraInfo(" . $row['ID'] . ", event);"; ?>"
			onMouseOut="unHighlightRow(this, '<?= $normalClass ?>');
				<? if($showPopUp) echo "hideExtraInfo(" . $row['ID'] . ");"; ?>"
			onMouseMove="<? if($showPopUp) echo "showExtraInfo(" . $row['ID'] . ", event);"; ?>"
			class="<?= $normalClass ?>">
      <td>

<!-- inserted listing code -->

				<!-- result listing -->
				<div class="result_box">
					<div class="result_inner">
						<div class="result_rating"><? print round($row["rating"]); ?>/5</div>
						<div>

<!-- <a href="devil2-result0.htm" style="float:left;" class="search_result">17e Festival Van De Fantastische Film</a> -->

<a id="titleLink" href="action.php?type=2&id=<? print $row["ID"] ?>" target="infoFrame">
	<?= limitWordLength($row["title"], 16) ?>
</a>
						</div>
						<div style="float:right; padding:0px;" class="search_result"><?= getDisplayLocation($row) ?></div>
					</div>
					<div class="feature_text" style="padding:0px;">&nbsp; xyz fest is a great example of impeccable planning and flawless execution...</div>
				</div>
				<!-- result listing -->

<!-- inserted listing code -->

      </td>
			<td width="20%" id="locationCell" class="location">
				<? if($listType == "distinctBG") echo "<br>"; ?>
				<?= getDisplayLocation($row) ?>
				<? if($listType == "distinctBG") echo "<br><br>"; ?></td>
    </tr>
<? if($showPopUp) {
	$extraInfos = $GLOBALS["extraInfos"];
	$extraInfos[] = array("id" => $row["ID"], "tagline" => $row["tagline"]);
	}
}
?>

<html>

<head>
	<title>Listing</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="styles/search.css" type="text/css">
	<!-- <link rel="stylesheet" href="styles/listing.css" type="text/css"> -->

<link rel="stylesheet" type="text/css" href="styles/listing-new.css">

	<script src="mouseTrack.js"></script>
  <STYLE>

		#loadingLayer {
		  position: absolute;
		  z-index: 100;
	 	 	top: 30px;
	 	 	visibility: visible;
			text-align: center;
		}

	  .festExtraInfo {
	    font-family: Arial, Helvetica, sans-serif;
	    font-size: 14px;
			font-style: italic;
			text-align: center;
	    color: #FFFFFF;
	    border: 1px #CC0000 solid;
	    background-color: #000000;
			padding: 3px;
	    position: absolute;
	    z-index: 100;
	    top: 0px;
	    left: 10px;
			/* width: 105px; */
	    visibility: hidden;
	  }

  </STYLE>
</head>

<!-- background="images/bg.gif" -->
<body background="images/bg.gif" onload="/*toggleVisibility('loadingLayer','hidden','hidden','hidden'); */ handleView(); selectRow(0);">
<!--<DIV id="loadingLayer" align="center"><img src="images/loading.gif" border="0"></DIV>-->

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
		if ($key == 'studentfriendly') {
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
		if($key == "festList") {
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

<script src="common.js"></script>
<script language="JavaScript">
function toggleVisibility(id, NNtype, IEtype, WC3type) {
   if (document.getElementById) {
       eval("document.getElementById(id).style.visibility = \"" + WC3type + "\"");
   } else {
       if (document.layers) {
           document.layers[id].visibility = NNtype;
       } else {
           if (document.all) {
               eval("document.all." + id + ".style.visibility = \"" + IEtype + "\"");
           }
       }
   }
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function switchExtraCols(display) {
	var length = 1;
	if(document.all("locationCell").length)
		length = document.all("locationCell").length;
	for(var i = 0; i < length; i++)
		document.all("locationCell", i).style.display = display;
/*	var padding = "0px";
	if(display == "none") padding = "5px";
	for(var i = 0; i < length; i++) {
    document.all("tableRow", i).style.paddingTop = padding;
    document.all("tableRow", i).style.paddingBottom = padding;
	}*/
	if(display == "none")
		document.all("festTable").cellPadding = 5;
	else
		document.all("festTable").cellPadding = 0;
}

function handleView() {
	if(!inSearchFrameset()) return;
	if(!window.top.frames("mainFrame").searchInRows)
		switchExtraCols("none");
}

var selectedRowIndex = -1;
var selectedRowClass = "";
function selectRow(index, selectedClass, unselectedClass) {
	table = document.all("festTable");
	// check that the new row is a valid one
  if(index < 0 || index >= table.rows.length) return false;

	// deselect the old selected row (but only if there was one)
  if(selectedRowIndex >= 0)
   	setRowStyle(table.rows(selectedRowIndex), selectedRowClass);

	// Select the new one
  selectedRowIndex = index;
	if(!selectedClass) {
		selectedClass = "selected";
		unselectedClass = "notHighlighted";
	}
	selectedRowClass = unselectedClass;

	setRowStyle(table.rows(index), selectedClass);
	table.rows(index).all("titleLink").click();

	var rowTop = table.offsetTop + table.rows(index).offsetTop;
	var rowHeight = table.rows(index).clientHeight;
	var rowBottom = rowTop + rowHeight;
	var windowTop = document.body.scrollTop;
	var windowHeight = document.body.clientHeight;
	var windowBottom = windowTop + windowHeight;
	/* window.status = "row: " + rowTop + "," + rowBottom
		+ " window: " + windowTop + ", " + windowBottom; */
	if(rowTop < windowTop)
		document.body.scrollTop = rowTop;
	if(rowBottom > windowBottom)
		document.body.scrollTop = rowBottom - windowHeight;
}

function highlightRow(row, className) {
	if(row.rowIndex == selectedRowIndex) return false;
	if(!className) className = "highlighted";
	setRowStyle(row, className);
}

function unHighlightRow(row, className) {
	if(row.rowIndex == selectedRowIndex) return false;
	if(!className) className = "notHighlighted";
	setRowStyle(row, className);
}

function setRowStyle(row, className) {
	row.className = className;
	//row.all("titleLink").className = className;
  //row.all("reviewLink").className = className;
}

function previousRow() {
	selectRow(selectedRowIndex - 1);
}

function nextRow() {
	selectRow(selectedRowIndex + 1);
}

function isLastRow() {
	return selectedRowIndex == document.all("festTable").rows.length - 1;
}

function isFirstRow() {
	return selectedRowIndex == 0;
}

function getNextName() {
	return document.all("festTable").rows[selectedRowIndex].table.rows(index).all("titleLink").innerText;
}

function showExtraInfo(festID, event) {
	info = document.all("extraInfo" + festID);
	if(info == null) return false;

	var mouseCoords = getMouseCoords(event);
	if(event.clientY > info.clientHeight + 30)
		var offset = -30 - info.clientHeight;
	else
		var offset = 30;
	info.style.top = (mouseCoords[1] + offset) + "px";
	info.style.visibility = "visible";
}

function hideExtraInfo(festID) {
	info = document.all("extraInfo" + festID);
	if(info == null) return false;

	info.style.visibility = "hidden";
}
</script>
<?

?>

	<div id="resultCount" style="font-size: 14px; padding: 0px; margin: 5px;">
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
	      Displaying festivals "<?= limitStringLength($firstFest["title"], 20) ?>" through
	        "<?= limitStringLength($lastFest["title"], 30) ?>"
<?		}
			if($startOn > 0) {
				$_REQUEST["startOn"] = $startOn - MAX_SEARCH_ROWS; ?>
			<a href="listing.php?<?= urlEncodeArray($_REQUEST) ?>">
				<span class="arrow">&lt;&lt;</span></a>
<?		}
			if($count > $endOn) {
				$_REQUEST["startOn"] = $endOn; ?>
			<a href="listing.php?<?= urlEncodeArray($_REQUEST) ?>">
				<span  class="arrow">&gt;&gt;</span></a>
<?		}
		} ?>
	</div>
	<br>
<? if(!empty($msg)) { ?>
	<div class="error"><?= $msg ?></div>
<? }

?>
  <table id="festTable" style="padding: 0px; margin: 0px;" width="100%" border="0" cellspacing="" cellpadding="0" align="left" valign="top">

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
</table>
<? foreach($extraInfos as $info) {
	 	if($info["tagline"] != "") {?>
<span id="extraInfo<?= $info["id"] ?>" class="festExtraInfo"><?= $info["tagline"] ?></span>
<?	}
	}
?>

</body>

</html>