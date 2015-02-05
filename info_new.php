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

	  $date = mysql_fetch_array($date); }

?>

<html>

<head>
<link rel="stylesheet" type="text/css" href="styles/listing_new.css">
<script language="Javascript" src="quicksearch.js"></script>

	<title>filmd rev0.3: <? print $fest["title"] ?></title>
</head>

<body>

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
<div id="center" class="box_structure" style="display:inline; width:75%;">
	<div class="box_structure">
		<div class="box_inner_structure">
			<div class="box_title">
				<div style="float:left; width:100%; padding:0px;">
					<div style="float:left;"><? print $fest["title"] ?></div>	<div style="float:right;"><?= getDisplayLocation($fest) ?></div>
				</div>

				<div style="float:left; width:100%; padding:0px;">
					<div style="float:left; font-size:70%; padding:0px;">&nbsp; <a href="action.php?type=3&id=<? print $fest["ID"] ?>" style="text-decoration:underline; color:#FFFFFF;">Festival Website</a></div>	<div style="float:right; font-size:70%; padding:0px;">&nbsp; <a href="action.php?type=5&id=<?= $fest["ID"] ?>" style="text-decoration:underline; color:#FFFFFF;">Contact this Fest</a></div>
				</div>
			</div>
			<div class="box_inner" style="padding:5px;">
			<div style="width:100%; background-color:#EEEEEE;">
				<div style="float:left;">Rating: <? print round($fest["rating"]) ?> / 5</div><div style="float:right;"><a href="#" style="font-size:80%; text-decoration:underline;"><?= $fest["numReviews"] ?> Reviews</a></div>
			</div>

			<div class="information_box_positioner">
				<div style="float:left;"><a href="action.php?type=6&id=<?= $id ?>" style=" text-decoration:underline;">Add to my FestList</a></div>

				<div style="float:right;"><a href="writereview.php?ID=<?= $fest["ID"] ?>" style="font-size:80%; text-decoration:underline;">
					<? 	if($fest["numReviews"] > 0) echo "Write a Review"; else echo "Be the First to Write a Review!" ?>
				</a></div>

			</div>

			<div style="float:right;"><a href="action.php?type=4&id=<? print $fest["ID"] ?>" style="text-decoration:underline;">Get the Application</a></div>
			<br /><br />

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Dates</div>
					<div class="information_text">
					<?	if(empty($fest["startDate"]) || empty($fest["endDate"])) {
							echo "Unavailable";
						} else {
							echo date("F j, Y", strtotime($fest["startDate"])) . " - "
								. date("F j, Y", strtotime($fest["endDate"]));
						}	?>
					</div>
				</div>
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Students</div>
					<div class="information_text">
						<? if ($fest["stFriend"] == 1) { ?>
						  	<img src="images/stfriendwhite.gif" align="absmiddle" alt="Student Friendly!">This festival has special consideration for students!
						<? } else print "There is no special student consideration"; ?>
					</div>
				</div>
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Submission</div>
					<div class="information_text">
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
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Organizer</div>
					<div class="information_text">
						<?
							if(!empty($fest["oName"])) print $fest["oName"] . "<br />";
							if(empty($fest["oAdr"]) && empty($fest["oCity"]) && empty($fest["oState"]))
							 	echo "Address Unavailable<br />";
							else {
								if(!empty($fest["oAdr"])) $fest["oAdr"] . "<br />";
								if(!empty($fest["oCity"])) echo $fest["oCity"] . ", ";
							 	echo $fest["oState"] . " " . $fest["oZip"] . "<br />";
								if($fest["oTel"] != "") print "tel:" . $fest["oTel"] . "<br />";
								if($fest["oFax"] != "") print "fax:" . $fest["oFax"] . "<br />";
							}
						?>
					</div>
				</div>
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Venue</div>
					<div class="information_text">
						<?
							if($fest["oName"] != "") print $fest["oName"] . "<br />";
							if(empty($fest["vAdr"]) && empty($fest["vCity"]) && empty($fest["vState"])
								&& empty($fest["country"]))
							 	echo "Address Unavailable<br />";
							else {
								if(!empty($fest["vAdr"])) echo $fest["vAdr"] . "<br />";
								if(!empty($fest["vCity"])) echo $fest["vCity"] . ", ";
								echo $fest["vState"] . " " . $fest["vZip"];
								if($fest["country"] != "") print $fest["country"] . "<br />";
							}
						?>
								<? if($fest["vTel"] != "") print "tel:" . $fest["vTel"]; ?><br />
					</div>
				</div>
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Film Categories</div>
					<div class="information_text">
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
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Projection Capability</div>
					<div class="information_text">
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
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Description</div>
					<div class="information_text">
						<? print $fest["descriptGen"] ?>
					</div>
				</div>
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Deadlines & Fees</div>
					<div class="information_text">

<!-- legacy code -->

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

<!-- legacy code -->
					</div>
				</div>
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="information_box">
				<div class="information_box_positioner">
					<div class="information_heading">Awards & Prizes</div>
					<div class="information_text">
						<ul>
							<? 	if($fest["prizes"]!='') {
										print "<li>" . ereg_replace("<br />", "<li>", $fest["prizes"]);
									} elseif($fest["award"] == 1)	{
								  	print "This festival gives out awards, but no prizes.";
								  } elseif($fest["award"] == 2) {
								  	print "This festival gives out awards and prizes.";
								  }
							?>
						</ul>
					</div>
				</div>
			</div>

					<div class="spacer_style" style="color:#FFFFFF;">...</div>

			<div class="last_updated">
				<? if($fest["lastDate"] != "0000-00-00")
						echo "Last Updated: ".formatDate(strtotime($fest["lastDate"]));
				?>
			</div>

		<div style="width:100%;">
			<div style="float:left;"><a href="#">Contact Us</a> - <a href="#">Terms of Use</a></div>
			<div style="float:right;">[ <a href="javascript:history.go(-1);">back</a> ]</div>
		</div>

			<div style="width:100%; text-align:right; font-size:70%;">Copyright 2003 &copy; FilmDevil.</div>
			</div>
		</div>
	</div>

</div>
<!-- center panel -->

</body>

</html>