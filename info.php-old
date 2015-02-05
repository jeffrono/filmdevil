<?

$cacheLimiter = "nocache";
require_once "dbFunctions.php";

function rewriteLinks($buffer) {
	return preg_replace("/(href|src|background)=([\"\'])/i", "\$1=\$2" . URL_ROOT, $buffer);
}

fd_connect();
fd_import_request_variables("r", "form_");

$id = fd_filter($form_ID, true);
if(!empty($form_view))
  $view = fd_filter($form_view);
else
  $view = "info";

$isPreview = isset($form_preview);

$fest = mysql_fetch_array(fd_query("select * from fests where ID=$id"));
if(empty($fest)) {
	trigger_error("There is no fest with id " . $id);
	die("There is no fest for that ID");
}

$similarFestResult = fd_query("select ID, title from similarFest inner join
  fests on similarFest.originFestID = fests.ID where similarFestID = $id");

if(isLoggedIn()) {
	$festList = fd_query("select * from userFest where userID = "
		. $_SESSION["user_id"] . " and festID = $id and relation = 'festList'");
	$isOnFestList = mysql_num_rows($festList) > 0;
} else
	$isOnFestList = false;

if(isset($useAbsoluteLinks)) {
	ob_start("rewriteLinks");
}
?>

<html>

<head>

<title>Info for <? print $fest["title"] ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="styles/central.css" type="text/css">

<script language="JavaScript">

<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}

//-->

</script>
<script src="findDOMNested.js"></script>
<script src="common.js"></script>

<script language="JavaScript">
<?if(!noFrames()) { ?>

function previousExists() {
	return !window.top.frames('mainFrame').frames('listingFrame').isFirstRow();
}

function nextExists() {
	return !window.top.frames('mainFrame').frames('listingFrame').isLastRow();
}

function previous() {
	window.top.frames('mainFrame').frames('listingFrame').previousRow();
}

function next() {
	window.top.frames('mainFrame').frames('listingFrame').nextRow();
}

function switchView() {
	window.top.frames('mainFrame').switchView();
}

var isMaximized = false;
function maxMin() {
	if(isMaximized)
  	window.top.restoreMidFrame();
  else
		window.top.maximizeMidFrame();
	isMaximized = !isMaximized;
}
<?} ?>
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<? include("header.php"); ?>

<?if($isPreview) {
		include "festMenu.php";
		printFestMenu();
	} ?>

<?if(!$isPreview) { ?>
<MAP NAME="1">
<?	if($view == "review") { ?>
	<AREA SHAPE="rect" ALT="Info" COORDS="0,0,100,94" href="info.php?ID=<?= $id ?>&view=info<? if(noFrames()) echo "&noFrames"; ?>">
<? 	} else { ?>
	<AREA SHAPE="rect" ALT="Reviews" COORDS="200,0,299,94" href="info.php?ID=<?= $id ?>&view=review<? if(noFrames()) echo "&noFrames"; ?>">
<? 	} ?>
</MAP>

<table>
	<tr>
		<td><IMG SRC="images/<? if($view == "info") echo "info.gif"; else echo "reviews.gif"; ?>" WIDTH=300 HEIGHT=94 BORDER=0 USEMAP="#1">
<?	if(!noFrames()) { ?>
		<td>
	    <script language="javascript">
	    if(inSearchFrameset()) {
	      document.writeln("<img src='images/previousfest.gif' onClick='previous();'>");
	      document.writeln("<img src='images/nextfest.gif' onClick='next()'>");
	      if(!isMac) {
	        document.writeln("<img src='images/minmax.gif' onClick='maxMin()'>");
	        document.writeln("<img src='images/switchview.gif' onClick='switchView()'>");
	      }
	      document.writeln("<br>");
	    }
	    </script>
<?	} ?>
	</tr>
</table>
<?} ?>

<table width="100%" border="0" cellspacing="5" cellpadding="0">
	<tr>
	  <td align="center" valign="middle" width="100">
	  <? if ($fest["logoURL"] != '' && ONLINE) {
	    $imgSize = getScaledImageSize($fest["logoURL"], 100, 100);
		?>
	    <img src="<? print $fest["logoURL"] ?>" width=<?= $imgSize["width"] ?> height=<?= $imgSize["height"] ?> >
	  <? } else { ?>
	     <img src="images/plainface.gif">
	  <? } ?>
	  </td>
	  <td valign="top">
	    <div class="title"><?= $fest["title"] ?></div>
	    <? if($fest["tagline"] != "") { ?><div class="tagline">"<? print $fest["tagline"] ?>"</div><? } ?>
	    <div class="location">
				<?= getDisplayLocation($fest) ?>
			</div>
	  </td>
<?	if(mysql_num_rows($similarFestResult) > 0) {
			$similarArray = randomizeArray(makeDblArray($similarFestResult));
?>
		<td class="similarFestBox">
			<span class="title">Similar Festivals:</span><br>
<?		foreach($similarArray as $similarRow) { ?>
				<br><a href="info.php?ID=<?= $similarRow["ID"] ?><? if(noFrames()) echo "&noFrames" ?>">
					<?= $similarRow["title"] ?></a>
<?		} ?>
		</td>
<?	} ?>
	</tr>
</table>

<? if($fest["numReviews"] > 0) { ?>
  	Rating: <img src="images/icon<? print round($fest["rating"]) ?>white.gif" alt="<? if (round($fest["rating"]) == 0) { ?>Not Yet Rated<? } else { print round($fest["rating"]); ?> out of 5<? } ?>" align="absmiddle">&nbsp;&nbsp;&nbsp;
          # of reviews:
          <? print $fest["numReviews"] ?>
          &nbsp;<img src="images/readwhite.gif" border="0" alt="Read Reviews" align="absmiddle">
<? } ?>

<? if(isLoggedIn() && festEditAuthorized($_SESSION["user_id"], $id)) { ?>
<a href="prelogin.php?festID=<?= $id ?>&operation=update" <? if(!noFrames()) echo "target='mainFrame'"; ?>>Update this information</a><br><br>
<? } ?>

<?if(!$isPreview) { ?>
<table width="100%">
	<tr>
		<td>
<?	if($isOnFestList) { ?>
			<a href="festList.php?remove=<?= $id ?>">Remove from my FestList</a>
<? 	} else { ?>
			<a href="action.php?type=6&id=<?= $id ?>">Add to my FestList</a>
<? 	} ?>
</td>
		<td><a href="writereview.php?ID=<?= $fest["ID"] ?>">
			<img src="images/writewhite.gif" border="0" alt="Write a Review" align="absmiddle">
<? 	if($fest["numReviews"] > 0) echo "Write a review"; else echo "Be the first to write a review!" ?>
			</a></td>
	</tr>
</table>
<?} ?>

<?
	if($view == "info") {
	  $projections1 = fd_query ("select * from projtable p, projections r where p.PID = r.ID AND p.FID = $id");
	  $categories1 = fd_query ("select * from cattable c, categories r where c.CID = r.ID AND c.FID = $id");
	  $fees1 = fd_query ("select * from fees where festID = $id AND dateType = 'Early'");
	  $fees2 = fd_query ("select * from fees where festID = $id AND dateType = 'Normal'");
	  $fees3 = fd_query ("select * from fees where festID = $id AND dateType = 'Late'");
	  $date = fd_query ("SELECT DATE_FORMAT(startDate, '%M %e, %Y') AS date1, DATE_FORMAT(endDate, '%M %e, %Y') AS date2, DATE_FORMAT(eDead, '%c/%e/%y') AS date3, DATE_FORMAT(nDead, '%c/%e/%y') AS date4, DATE_FORMAT(lDead, '%c/%e/%y') AS date5 FROM fests WHERE ID=$id");
	  $feese = mysql_fetch_array($fees1);
	  $feesn = mysql_fetch_array($fees2);
	  $feesl = mysql_fetch_array($fees3);
	  $date = mysql_fetch_array($date);
?>

<?	if ($fest["descriptGen"] != '') { ?>

<div class="section">
	<a class="sectionHeading">Description</a><br>
	<div class="sectionBody"><? print $fest["descriptGen"] ?></div>
</div>
<? 	} ?>

<div class="section">
	<a class="sectionHeading">Dates</a><br>
	<div class="sectionBody">
<?
	if(empty($fest["startDate"]) || empty($fest["endDate"])) {
		echo "Unavailable";
	} else {
		echo date("F j, Y", strtotime($fest["startDate"])) . " - "
			. date("F j, Y", strtotime($fest["endDate"]));
	}
?>
</div></div>

<div class="section">
	<a class="sectionHeading">Links</a>
	<div class="sectionBody">
		<table cellpadding="10">
	   <tr>
	     <td><a href="action.php?type=3" onClick="MM_openBrWindow('action.php?type=5&id=<?= $fest["ID"] ?>','ContactFest','resizable=yes,width=450,height=300'); return false;">Contact this Fest</a>
	     <td><a href="action.php?type=3&id=<? print $fest["ID"] ?>" target="_blank">Festival Website</a>
	   </tr>
	   <tr>
	     <td><a href="action.php?type=4&id=<? print $fest["ID"] ?>" target="_blank">Get the Application</a>
	     <td><a href="action.php?type=1&id=<? print $fest["ID"] ?>" target="_blank"><img src="images/google.gif" align="absmiddle" border="0"> this fest</a>
	   </tr>
	  </table>
	</div>
</div>

<div class="section">
	<a class="sectionHeading">Students</a><br>
	<div class="sectionBody">
	<? if ($fest["stFriend"] == 1) { ?>
  	<img src="images/stfriendwhite.gif" align="absmiddle" alt="Student Friendly!">This festival has special consideration for students!
  <? } else print "There is no special student consideration"; ?>
</div></div>

<div class="section">
	<a class="sectionHeading">Submitting</a><br>
	<div class="sectionBody">
<?	if($fest["submission"] == 0)
			print "This festival does not accept submissions";
		else {
			if($fest["numAccepted"] != 0 && $fest["numEntries"] != 0) {
?>
        Official Selections: <? print $fest["numAccepted"] ?><br>
				Number of Entries: <? print $fest["numEntries"] ?><br>
				<a class="heading">Acceptance Rate: </a>
  			<?= number_format(100 * ($fest["numAccepted"] / $fest["numEntries"]), 0) ?>%
<? 		} else { ?>
				Accepts submissions, but the number of entries and officials selections
				last year is unknown
<?		}
		}
?>
	</div>
</div>

<div class="section">
	<a class="sectionHeading">Organizer</a><br>
	<div class="sectionBody">
<?
	if(!empty($fest["oName"])) print $fest["oName"] . "<br>";
	if(empty($fest["oAdr"]) && empty($fest["oCity"]) && empty($fest["oState"]))
	 	echo "Address Unavailable<br>";
	else {
		if(!empty($fest["oAdr"])) $fest["oAdr"] . "<br>";
		if(!empty($fest["oCity"])) echo $fest["oCity"] . ", ";
	 	echo $fest["oState"] . " " . $fest["oZip"] . "<br>";
		if($fest["oTel"] != "") print "tel:" . $fest["oTel"] . "<br>";
		if($fest["oFax"] != "") print "fax:" . $fest["oFax"] . "<br>";
	}
?>
	</div>
</div>

<div class="section">
	<a class="sectionHeading">Venue</a><br>
	<div class="sectionBody">
<?
	if($fest["oName"] != "") print $fest["oName"] . "<br>";
	if(empty($fest["vAdr"]) && empty($fest["vCity"]) && empty($fest["vState"])
		&& empty($fest["country"]))
	 	echo "Address Unavailable<br>";
	else {
		if(!empty($fest["vAdr"])) echo $fest["vAdr"] . "<br>";
		if(!empty($fest["vCity"])) echo $fest["vCity"] . ", ";
		echo $fest["vState"] . " " . $fest["vZip"];
		if($fest["country"] != "") print $fest["country"] . "<br>";
	}
?>
		<? if($fest["vTel"] != "") print "tel:" . $fest["vTel"]; ?><br>
	</div>
</div>

<div class="section">
	<a class="sectionHeading">Film Categories</a><br>
	<div class="sectionBody">
<? if(mysql_num_rows($categories1) == 0)
	 	echo "Unavailable";
	 else {
?>
		<ul>
<?
		while($category = mysql_fetch_array($categories1)) { ?>
		<li><?= $category["cat"] ?>
<? 	} ?>
	 </ul>
<? } ?>
	</div>
</div>

<div class="section">
	<a class="sectionHeading">Projection Capability</a><br>
	<div class="sectionBody">
<? if(mysql_num_rows($projections1) == 0)
	 	echo "Unavailable";
	 else {
?>
		<ul>
<? while($projection = mysql_fetch_array($projections1)){ ?>
		<li><?= $projection["proj"] ?>
<? } ?>
	 </ul>
<? } ?>
	</div>
</div>


		<? if ($fest["theme"] != '') { ?>

<div class="section">
	<a class="sectionHeading">Theme</a><br>
	<div class="sectionBody"><?= $fest["theme"] ?>
</div>

		<? }
		if ($fest["distinguish"] != '') { ?>

<div class="section">
	<a class="sectionHeading">What sets us apart</a><br>
	<div class="sectionBody"><?= $fest["distinguish"] ?>
</div>

		<? }
		if ($fest["press"] != '') { ?>

<div class="section">
	<a class="sectionHeading">Press Description</a><br>
	<div class="sectionBody"><? print $fest["press"] ?></div>
</div>
		<? }
		if ($fest["descriptStu"] != '') { ?>

<div class="section">
	<a class="sectionHeading">Student Description</a><br>
	<div class="sectionBody"><? print $fest["descriptStu"] ?></div>
</div>
		<? }
		if ($fest["descriptPro"] != '') { ?>

<div class="section">
	<a class="sectionHeading">Professional Description</a><br>
	<div class="sectionBody"><? print $fest["descriptPro"] ?></div>
</div>
	<? } ?>

<div class="section">
	<a class="sectionHeading">Deadlines & Fees</a><br>
	<div class="sectionBody">
  	<table border="0" cellspacing="0" cellpadding="10">
	<? if ($fest["eDead"] != '0000-00-00') { ?>
			<tr>
				<td colspan="5">By <?= date("F j, Y", strtotime($date["date3"])) ?> (Early deadline)</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
		 <? if($feese["Feature"] >= 0) {
					print "<td>";
					if($feese["Feature"] == 0)
						print "<b>Free</b> for features";
					else print "<b>$" . $feese["Feature"] . "</b></b> per feature";
					print "</td>";
				}
		 	  if($feese["Short"] >= 0) {
					print "<td>";
					if($feese["Short"] == 0) print "<b>Free</b> for shorts";
					else print "<b>$" . $feese["Short"] . "</b> per short"; print "</td>";
				}
		 		if($feese["Student"] >= 0) {
					print "<td>";
					if($feese["Student"] == 0) print "<b>Free</b> for students";
					else print "<b>$" . $feese["Student"] . "</b> per student film";
					print "</td>";
				}
				if($feese["Other"] >= 0) {
					print "<td>";
					if($feese["Other"] == 0) print "<b>Free</b> for other films";
					else  print "<b>$" . $feese["Other"] . "</b> per other film";
					print "</td>";
				} ?>
			</tr>
  <? } ?>
	<? if ($fest["nDead"] != '0000-00-00') { ?>
      <tr>
        <td colspan="5">By <?= date("F j, Y", strtotime($date["date4"])) ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
		 <? if($feesn["Feature"] >= 0) {
					print "<td>";
					if($feesn["Feature"] == 0)
						print "<b>Free</b> for features";
					else print "<b>$" . $feesn["Feature"] . "</b></b> per feature";
					print "</td>";
				}
		 	  if($feesn["Short"] >= 0) {
					print "<td>";
					if($feesn["Short"] == 0) print "<b>Free</b> for shorts";
					else print "<b>$" . $feesn["Short"] . "</b> per short"; print "</td>";
				}
		 		if($feesn["Student"] >= 0) {
					print "<td>";
					if($feesn["Student"] == 0) print "<b>Free</b> for students";
					else print "<b>$" . $feesn["Student"] . "</b> per student film";
					print "</td>";
				}
				if($feesn["Other"] >= 0) {
					print "<td>";
					if($feesn["Other"] == 0) print "<b>Free</b> for other films";
					else  print "<b>$" . $feesn["Other"] . "</b> per other film";
					print "</td>";
				} ?>
			</tr>
	<? } else echo "Unavailable"; ?>
  <? if ($fest["lDead"] != '0000-00-00') { ?>
      <tr>
        <td colspan="5">By <?= date("F j, Y", strtotime($date["date5"])) ?> (Late deadline)</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
		 <? if($feesl["Feature"] >= 0) {
					print "<td>";
					if($feesl["Feature"] == 0)
						print "<b>Free</b> for features";
					else print "<b>$" . $feesl["Feature"] . "</b></b> per feature";
					print "</td>";
				}
		 	  if($feesl["Short"] >= 0) {
					print "<td>";
					if($feesl["Short"] == 0) print "<b>Free</b> for shorts";
					else print "<b>$" . $feesl["Short"] . "</b> per short"; print "</td>";
				}
		 		if($feesl["Student"] >= 0) {
					print "<td>";
					if($feesl["Student"] == 0) print "<b>Free</b> for students";
					else print "<b>$" . $feesl["Student"] . "</b> per student film";
					print "</td>";
				}
				if($feesl["Other"] >= 0) {
					print "<td>";
					if($feesl["Other"] == 0) print "<b>Free</b> for other films";
					else  print "<b>$" . $feesl["Other"] . "</b> per other film";
					print "</td>";
				} ?>
			</tr>
  <? } ?>
	  </table>
	</div>
</div>

  <? if ($fest["feeNote"] != '') { ?>
<div class="section">
	<a class="sectionHeading">Fee Notes</a>
	<div class="sectionBody"><?= $fest["feeNote"] ?></div>
</div>
  <? } ?>


  <? if ($fest["subCheck"] != '') { ?>
<div class="section">
	<a class="sectionHeading">Submission Checklist</a>
	<div class="sectionBody"><ul><li><?= ereg_replace("<br />", "<li>", $fest["subCheck"]) ?>
		</ul></div>
</div>
  <? } ?>

	<? if ($fest["eligibility"] != '') { ?>
<div class="section">
	<a class="sectionHeading">Eligilibity</a><br>
	<div class="sectionBody"><ul><li><?= ereg_replace("<br />", "<li>", $fest["eligibility"]); ?>
		</ul></div>
</div>

	<? } ?>

<div class="section">
	<a class="sectionHeading">Awards & Prizes</a>
	<div class="sectionBody"><ul>
<? 	if($fest["prizes"]!='') {
			print "<li>" . ereg_replace("<br />", "<li>", $fest["prizes"]);
		} elseif($fest["award"] == 1)	{
	  	print "This festival gives out awards, but no prizes.";
	  } elseif($fest["award"] == 2) {
	  	print "This festival gives out awards and prizes.";
	  }
?>
	</ul></div>
</div>

<? if($fest["lastDate"] != "0000-00-00")
		echo "Last Updated: " . formatDate(strtotime($fest["lastDate"]));

	} else { // show reviews
		$reviews = fd_query("select reviews.*, user.firstName, user.lastName,
			user.username, DATE_FORMAT(date, '%M %e, %Y') AS date from
			reviews left join user
			on reviews.userID = user.ID
			where festID=$id ORDER BY date ASC");

		if(mysql_num_rows($reviews) == 0) {
			echo "No one has reviewed this festival yet.";
		} else {
	 		while ($review = mysql_fetch_assoc($reviews)) {
?>

<script>
	function showUser(reviewID) {
		var display = document.all("userInfo" + reviewID).style.display == 'block'
			? 'none' : 'block';
		document.all("userInfo" + reviewID).style.display = display;
	}
</script>

<div class="section">
	<div class="title">
		<img src="images/icon<? print $review["genRating"] ?>white.gif" align="absmiddle">
		<?= $review["title"] ?>
	</div>
	<div class="sectionBody">
		<div>Posted by <a href="#" onClick="showUser(<?= $review["id"] ?>); return false;">
<?		$displayName = $review["name"];
			if(empty($displayName)) $displayName = "Anonymous"; ?>
			<?= $displayName ?></a> on <?= $review["date"] ?></div>
		<div id="userInfo<?= $review["id"] ?>" class="userInfo">
			<? if(!empty($review["email"])) { ?>
			<a href="#" onClick="MM_openBrWindow('contactUser1.php?type=5&id=<?= $review["id"] ?>','ContactUser','resizable=yes,width=450,height=300'); return false;">
				Contact this User</a>
			<br>
			<? } ?>
			<? if($review["URL"] != ''){ ?>
	    Website: <a href="<? print $review["URL"] ?>" target="_blank">
	    <? print $review["URL"] ?></a>
			<br>
			<? } ?>
	    <? if($review["type"] != ''){ ?>
	    <? print $review["type"] ?>
	    <br>
	    <? } ?>
	    <? if($review["favMov"] != ''){ ?>
	    Favorite film: </a><a href="http://us.imdb.com/Find?for='<? print $review["favMov"] ?>'" target="_blank">
	    <? print $review["favMov"] ?></a>
	    <br>
	    <? } ?>
	    <? if($review["favWeb"] != ''){ ?>
	    Favorite film website: </a> <a href="<?= getAbsoluteURL($review["favWeb"]) ?>" target="_blank">
	    <? print $review["favWeb"] ?></a>
	    <br>
	    <? } ?>
		</div>
		<div>
      Films: <img src="images/icon<? print $review["filmRating"] ?>white.gif" align="absmiddle">
      Location:
      <img src="images/icon<? print $review["locationRating"] ?>white.gif" align="absmiddle">
      Organization:
      <img src="images/icon<? print $review["orgRating"] ?>white.gif" align="absmiddle">
      People:
      <img src="images/icon<? print $review["peopleRating"] ?>white.gif" align="absmiddle">
		</div>
		<div>
	<?= $review["body"] ?>
		</div>
</div>
<? 		} // end while(reviews)
		} // end if(count(reviews) == 0)
	} // end if(review tab chosen)

include("footer.php");

if(isset($useAbsoluteLinks)) {
	ob_end_flush();
}

?>

</body>

</html>