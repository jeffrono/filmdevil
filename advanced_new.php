<?
require_once "dbFunctions.php";
fd_connect();

$category = fd_query ("SELECT * FROM categories");
$projection = fd_query ("SELECT * FROM projections");

$countall = fd_query ("SELECT COUNT(*) FROM fests");
$countall2 = mysql_fetch_array($countall);

$category = fd_query ("SELECT * FROM categories ORDER BY cat ASC");
$projection = fd_query ("SELECT * FROM projections ORDER BY proj ASC");

$locationBox = mysql_fetch_assoc(fd_query("select data from data where id = 'locationBox'"));

$cathelp = "Select a category which best describes your film. Numbers indicate how many fests have this category.";
$projhelp = "Select the type of projection capability your film requires. Numbers indicate how many fests project this format.";
$datehelp = "Select a range of festival dates to narrow your search.";
$deadhelp = "Select the earliest date you'll be ready to submit your film.";
$loocationhelp = "You can search for festivals by continent, country, region (US and Canada only), and even US state. The numbers indicate how many fests are in that location.";
$feehelp = "Type the maximum amount you would pay for submitting your film";
$ratinghelp = "FilmDevil users rate the festivals on several criteria. You can restrict your search to high-rated festivals";
$reviewhelp = "You can restrict the search to festivals that have been reviewed by FilmDevil users ";
$studenthelp = "If you are a student, you might restict your search to festivals have special categories or prizes for students";
$texthelp = "Search for a phrase in the title or description of the festival";
$prizehelp = "If you're in search of money, you can restrict your search to fests that offer cash prizes";
$festlisthelp = "Search on the fests that you have put in your FestList";
?>
<html>

<head>
<link rel="stylesheet" type="text/css" href="styles/listing_new.css">
	<title>filmd rev0.3:advanced search page</title>

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

<!-- search panel -->
<div id="left" style="display:inline; width:100%; float:left; background-color:#EEEEEE;">
	<div style="width:100%; float:left; background-color:#EEEEEE;">
		<div style="width:100%; border-width:1px; border-style:dotted; border-color:#000000;">
			<div class="box_title">festival advanced search</div>
			<div class="box_inner" style="font-size:80%;">
	<form name="form1" method="post" action="listing_new.php">
				<div style="width:100%; background-color:#EEEEEE; text-align:left; font-family:arial; color:#000000;">&laquo; <a href="listing_new.php" style="font-family:arial; font-weight:bold; font-size:85%; color:#000000; text-decoration:underline;">simple search</a></li></div>
				<div style="border-color:#EEEEEE; border-width:1px; border-style:dotted; padding:5px;">
					<div style="background-color:#EEEEEE; font-size:130%;">Category &nbsp;
<!-- category -->
  <select name="category" onChange="this.form.submit()">
    <option value="Any" selected>Any</option>
    <? while($row = mysql_fetch_array($category)) { ?>
    <option value="<? print($row["ID"]) ?>">
    <? print($row["cat"]);
		$categorynum=mysql_fetch_array(fd_query("select count(*) from cattable where CID=".$row["ID"]));
		   print " (".$categorynum[0].")";
		 ?>
    </option>
    <? } ?>
  </select>
<!-- category -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Projection &nbsp;
<!-- projection -->
  <select name="projection" onChange="this.form.submit()">
    <option value="Any" selected>Any</option>
    <? while($row = mysql_fetch_array($projection)) { ?>
    <option value="<? print($row["ID"]) ?>">
    <? print($row["proj"]);
		$projectionnum=mysql_fetch_array(fd_query("select count(*) from projtable where PID=".$row["ID"]));
		   print " (".$projectionnum[0].")";
		  ?>
    </option>
    <? } ?>
  </select>
<!-- projection -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Festival Date &nbsp;
<!-- festival date -->
  <span id="festDate">
				<br><select name="startmonth"  onchange="this.form.submit()">
    <option value="0" selected></option>
    <option value="1">Jan</option>
    <option value="2">Feb</option>
    <option value="3">Mar</option>
    <option value="4">Apr</option>
    <option value="5">May</option>
    <option value="6">Jun</option>
    <option value="7">Jul</option>
    <option value="8">Aug</option>
    <option value="9">Sep</option>
    <option value="10">Oct</option>
    <option value="11">Nov</option>
    <option value="12">Dec</option>
  </select>
  <select name="startday" >
    <option value="0" selected></option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
    <option value="15">15</option>
    <option value="16">16</option>
    <option value="17">17</option>
    <option value="18">18</option>
    <option value="19">19</option>
    <option value="20">20</option>
    <option value="21">21</option>
    <option value="22">22</option>
    <option value="23">23</option>
    <option value="24">24</option>
    <option value="25">25</option>
    <option value="26">26</option>
    <option value="27">27</option>
    <option value="28">28</option>
    <option value="29">29</option>
    <option value="30">30</option>
    <option value="31">31</option>
  </select>
  <select name="startyear"  onchange="this.form.submit()">
    <option value="any" selected></option>
    <option value="1999">99</option>
    <option value="2000">00</option>
    <option value="2001">01</option>
    <option value="2002">02</option>
    <option value="2003">03</option>
    <option value="2004">04</option>
  </select>
  &nbsp;to<br>
  <select name="startmonth2"  onchange="this.form.submit()">
    <option value="0" selected></option>
    <option value="1">Jan</option>
    <option value="2">Feb</option>
    <option value="3">Mar</option>
    <option value="4">Apr</option>
    <option value="5">May</option>
    <option value="6">Jun</option>
    <option value="7">Jul</option>
    <option value="8">Aug</option>
    <option value="9">Sep</option>
    <option value="10">Oct</option>
    <option value="11">Nov</option>
    <option value="12">Dec</option>
  </select>
  <select name="startday2"  onchange="this.form.submit()">
    <option value="0" selected></option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
    <option value="15">15</option>
    <option value="16">16</option>
    <option value="17">17</option>
    <option value="18">18</option>
    <option value="19">19</option>
    <option value="20">20</option>
    <option value="21">21</option>
    <option value="22">22</option>
    <option value="23">23</option>
    <option value="24">24</option>
    <option value="25">25</option>
    <option value="26">26</option>
    <option value="27">27</option>
    <option value="28">28</option>
    <option value="29">29</option>
    <option value="30">30</option>
    <option value="31">31</option>
  </select>
  <select name="startyear2"  onchange="this.form.submit()">
    <option value="any" selected></option>
    <option value="1999">99</option>
    <option value="2000">00</option>
    <option value="2001">01</option>
    <option value="2002">02</option>
    <option value="2003">03</option>
    <option value="2004">04</option>
  </select></span>
<!-- festival date -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Application Deadline &nbsp;
					          <span id="appDead">Deadline after <br>
							<select name="deadmonth"  onchange="this.form.submit()">
					            <option value="0" selected></option>
					            <option value="1">Jan</option>
					            <option value="2">Feb</option>
					            <option value="3">Mar</option>
					            <option value="4">Apr</option>
					            <option value="5">May</option>
					            <option value="6">Jun</option>
					            <option value="7">Jul</option>
					            <option value="8">Aug</option>
					            <option value="9">Sep</option>
					            <option value="10">Oct</option>
					            <option value="11">Nov</option>
					            <option value="12">Dec</option>
					          </select>
					          <select name="deadday"  onchange="this.form.submit()">
					            <option value="0" selected> </option>
					            <option value="1">1</option>
					            <option value="2">2</option>
					            <option value="3">3</option>
					            <option value="4">4</option>
					            <option value="5">5</option>
					            <option value="6">6</option>
					            <option value="7">7</option>
					            <option value="8">8</option>
					            <option value="9">9</option>
					            <option value="10">10</option>
					            <option value="11">11</option>
					            <option value="12">12</option>
					            <option value="13">13</option>
					            <option value="14">14</option>
					            <option value="15">15</option>
					            <option value="16">16</option>
					            <option value="17">17</option>
					            <option value="18">18</option>
					            <option value="19">19</option>
					            <option value="20">20</option>
					            <option value="21">21</option>
					            <option value="22">22</option>
					            <option value="23">23</option>
					            <option value="24">24</option>
					            <option value="25">25</option>
					            <option value="26">26</option>
					            <option value="27">27</option>
					            <option value="28">28</option>
					            <option value="29">29</option>
					            <option value="30">30</option>
					            <option value="31">31</option>
					          </select>
					          <select name="deadyear"  onchange="this.form.submit()">
					            <option value="any" selected></option>
					            <option value="1999">99</option>
					            <option value="2000">00</option>
					            <option value="2001">01</option>
					            <option value="2002">02</option>
					            <option value="2003">03</option>
					            <option value="2004">04</option>
					          </select>
					          </span>
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Submission Fee &nbsp;
						<span id="feeUnder"><br>$<input type="text" name="fee" size="3" maxlength="3"></span>
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>


					<div style="background-color:#EEEEEE; font-size:130%;">Location &nbsp;
					          <select name="location" "onchange="this.form.submit()">
											<?= $locationBox["data"] ?>
					          </select>
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Minimum Rating &nbsp;
<!-- minimum rating -->

<table id="minRating">
	<tr>
		<td><input type="radio" class="radio" name="minRatingButton" value="0" onclick="this.form.submit()" checked></td>
		<td><input type="radio" class="radio" name="minRatingButton" value="1" onclick="this.form.submit()"></td>
<td><input type="radio" class="radio" name="minRatingButton" value="2" onclick="this.form.submit()"></td>
<td><input type="radio" class="radio" name="minRatingButton" value="3" onclick="this.form.submit()"></td>
<td><input type="radio" class="radio" name="minRatingButton" value="4" onclick="this.form.submit()"></td>
<td><input type="radio" class="radio" name="minRatingButton" value="5" onClick="this.form.submit()"></td>
	</tr>
	<tr>
		<td>N/A</td>
		<td><img src="images/icon1.gif" width="20" height="24"></td>
		<td><img src="images/icon2.gif" width="20" height="24"></td>
<td><img src="images/icon3.gif" width="20" height="24"></td>
		<td><img src="images/icon4.gif" width="20" height="24"></td>
<td><img src="images/icon5.gif" width="20" height="24"> </td>
	</tr>
</table>

<!-- minimum rating -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Reviews &nbsp;
<!-- reviews -->
  <span id="reviews"><br>At least <select name="numreviews"  onchange="this.form.submit()">
    <option value="0" selected>0</option>
    <option value="1">1</option>
    <option value="5">5</option>
    <option value="10">10</option>
  </select>
  reviews</span>
<!-- reviews -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Student-Friendly &nbsp;
<!-- student-friendly -->
<span id="student">
<br><input type="checkbox" class="radio" name="studentfriendly" value="student" onclick="this.form.submit()" >
Must be student friendly</span>
<!-- student-friendly -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Prizes &nbsp;
<!-- cash prizes -->
<span id="prizes">
	<br><input type="checkbox" class="radio" name="cash" value="2"  onclick="this.form.submit()">
	Must have cash prizes
</span>
<!-- cash prizes -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">Text Search &nbsp;
<!-- text search -->
<span id="text">
	<br>Description contains:
	<input type="text" name="textfield" size="10" maxlength="255" >
</span>
<!-- text search -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">FestList &nbsp;
<!-- festlist -->
<span id="festListDiv">
	<br><input type="checkbox" class="radio" name="festList" value="festList" onclick="this.form.submit()" >
	On my FestList
</span>
<!-- festlist -->
					</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>
					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>
					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

<!--					<div style="background-color:#CCCCCC; font-weight:bold;">Date</div>
					<div style="background-color:#EEEEEE;">In the next <input type="text" size="2"> days</div>

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#CCCCCC; font-weight:bold;">Rating <span style="font-weight:normal;">[out of 5]</div>
					<div style="background-color:#EEEEEE;">at least 
					<select><br />
						<option>2</option>	<option>3</option>
						<option>4</option>	<option>5</option>
					</select>
					 stars</div> -->

					<div style="height:2px; font-size:2px; color:#FFFFFF;">...</div>

					<div style="background-color:#EEEEEE; font-size:130%;">
					<input type="submit" class="button" name="Submit" value="Search" onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">
					</div>

				</div>

				</div>
			</div>
		</div>
		<div style="height:2px; font-size:2px; color:#EEEEEE;">...</div>
	</div>
</div>

<!-- search panel -->

</body>

</html>