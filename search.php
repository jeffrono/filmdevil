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

<title>Untitled Document</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="styles/outside.css" type="text/css">



<script language="JavaScript">

<!--

function MM_preloadImages() { //v3.0

  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();

    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)

    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}

}



function MM_swapImgRestore() { //v3.0

  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;

}



function MM_findObj(n, d) { //v4.0

  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {

    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}

  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];

  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);

  if(!x && document.getElementById) x=document.getElementById(n); return x;

}



function MM_swapImage() { //v3.0

  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)

   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}

}



function MM_reloadPage(init) {  //reloads the window if Nav4 resized

  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {

    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}

  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();

}

MM_reloadPage(true);

// -->



</script>

	<script src="findDOM.js"></script>

	<script>

function findLivePageWidth() {

	if (window.innerWidth != null)

		return window.innerWidth;

	if (document.body.clientWidth != null)

		return document.body.clientWidth;

	return (null);

}



function popUp(evt,objectID){

	if (isDHTML) { // Makes sure this is a DHTML browser

		var livePageWidth = findLivePageWidth();

		//alert(livePageWidth);

		domStyle = findDOM(objectID,1);

		dom = findDOM(objectID,0);

		state = domStyle.visibility;

		if (dom.offsetWidth) elemWidth = dom.offsetWidth;

		else { if (dom.clip.width)	elemWidth = dom.clip.width; }

		if (state == "visible" || state == "show")  { domStyle.visibility = "hidden"; }

		else {

			if (evt.pageY) { //Calculates the position for Navigator 4

				topVal = 2;

				leftVal = 2;

			}

			else {

				if (evt.y) { // Calculates the position for IE4

					topVal = 2;

					leftVal = 2;

				}

			}

		/*If the element goes off the page to the left, this moves it back */

			if(leftVal < 2) { leftVal = 2; }

			else {

				if ((leftVal + elemWidth) > livePageWidth) { leftVal = leftVal - (elemWidth / 2); }

			}

			domStyle.top = topVal; // Positions the element from the top

			domStyle.left = leftVal; // Positions the element from the left

			domStyle.visibility = "visible"; // Makes the element visable

		}

	}

}



function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}



// Returns true if option was found and selected, false otherwise

function setOption(selectBox, option) {

    for(i = 0; i < selectBox.options.length; i++) {

    	if(selectBox.options(i).value == option)

            break;

    }

    if(i == selectBox.options.length) {

    	//alert("Couldn't find option: " + option + " for select box " + selectBox.name);

        return false;

    }

    selectBox.selectedIndex = i;

    return true;

}



// Returns true if radio was found and selected, false otherwise

function setRadio(radioSeries, value) {

    for(i = 0; i < document.all(radioSeries).length; i++) {

    	if(document.all(radioSeries, i).value == value)

            break;

    }

    if(i == document.all(radioSeries).length) {

    		alert("Couldn't find value: " + value + " for radio series " + radioSeries);

        return false;

    }

    document.all(radioSeries, i).click();

    return true;

}



function setStillAccepting() {

	if(document.all("mustAccept").checked) {

		var date = new Date();

    setOption(document.form1.all("deadmonth"), date.getMonth() + 1);

    setOption(document.form1.all("deadday"), date.getDate());

    setOption(document.form1.all("deadyear"), date.getFullYear());

		return true;

	} else {

		document.form1.all("deadmonth").selectedIndex = 0;

		document.form1.all("deadday").selectedIndex = 0;

		document.form1.all("deadyear").selectedIndex = 0;

		return false;

	}

}



function searchUpcoming() {

	document.form1.all("reset").click();

	setStillAccepting();



	var date = new Date();

	setOption(document.form1.all("startmonth"), date.getMonth() + 1);

	setOption(document.form1.all("startday"), date.getDate());

	setOption(document.form1.all("startyear"), date.getFullYear());

	document.form1.all("submit").click();

}



function searchCheap() {

	document.form1.all("reset").click();

	setStillAccepting();



	document.form1.all("fee").value = "20";

	document.form1.all("submit").click();

}



function searchHiRate() {

	document.form1.all("reset").click();

	setStillAccepting();



	setRadio("minRatingButton", "4");

	document.form1.all("submit").click();

}



function searchStudent() {

	document.form1.all("reset").click();

	setStillAccepting();



	document.all("studentfriendly").checked = true	;

	document.form1.all("submit").click();

}



function searchFestList() {

	document.form1.all("reset").click();

	setStillAccepting();



	document.all("festList").checked = true	;

	document.form1.all("submit").click();

}



function showHide(boxName) {

	var obj = document.all(boxName);

	if(obj.style.display == "none") {

		obj.style.display = "inline";

		window.event.srcElement.innerText = "-" +

			window.event.srcElement.innerText.substr(1);

	} else {

		obj.style.display = "none";

		window.event.srcElement.innerText = "+" +

			window.event.srcElement.innerText.substr(1);

	}

}



var searchMode = "quick";



function switchMode() {

	if(searchMode == "quick") {

		document.all("quickTab").className = "tabNotSelected";

		document.all("advancedTab").className = "tabSelected";

		document.all("quickSearch").style.display = "none";

		document.all("advancedSearch").style.display = "inline";

		searchMode = "advanced";

	} else {

		document.all("quickTab").className = "tabSelected";

		document.all("advancedTab").className = "tabNotSelected";

		document.all("advancedSearch").style.display = "none";

		document.all("quickSearch").style.display = "inline";

		searchMode = "quick";

	}

}

//-->

</script>

</head>





<body onLoad="MM_preloadImages('images/qdown.jpg');" >

<SPAN ID="h1" CLASS="searchhelp"><? print ($cathelp); ?></SPAN>

<SPAN ID="h2" CLASS="searchhelp"><? print ($projhelp); ?></SPAN>

<SPAN ID="h3" CLASS="searchhelp"><? print ($datehelp); ?></SPAN>

<SPAN ID="h4" CLASS="searchhelp"><? print ($deadhelp); ?></SPAN>

<SPAN ID="h5" CLASS="searchhelp"><? print ($loocationhelp); ?></SPAN>

<SPAN ID="h6" CLASS="searchhelp"><? print ($feehelp); ?></SPAN>

<SPAN ID="h7" CLASS="searchhelp"><? print ($ratinghelp); ?></SPAN>

<SPAN ID="h8" CLASS="searchhelp"><? print ($reviewhelp); ?></SPAN>

<SPAN ID="h9" CLASS="searchhelp"><? print ($studenthelp); ?></SPAN>

<SPAN ID="h10" CLASS="searchhelp"><? print ($texthelp); ?></SPAN>

<SPAN ID="h11" CLASS="searchhelp"><? print ($prizehelp); ?></SPAN>

<SPAN ID="h12" CLASS="searchhelp"><? print ($festlisthelp); ?></SPAN>



<img src="images/search.gif"><br>



<table class="tabBar" cellspacing="0" cellpadding="0" width="170">

	<tr>

		<td valign="bottom">

	  <span id="quickTab" class="tabSelected"

	    onMouseOver="if(searchMode != 'quick') this.className = 'tabHighlighted';"

	    onMouseOut="if(searchMode != 'quick') this.className = 'tabNotSelected';"

	    onClick="if(searchMode != 'quick') switchMode();" style="font-family:trebuchet ms, arial; font-size:20px;">Quick</span>

	    </td><td valign="bottom"><span

	  id="advancedTab" class="tabNotSelected"

	    onMouseOver="if(searchMode == 'quick') this.className = 'tabHighlighted';"

	    onMouseOut="if(searchMode == 'quick') this.className = 'tabNotSelected';"

	    onClick="if(searchMode == 'quick') { switchMode(); document.form1.all('reset').click(); }"  style="font-family:trebuchet ms, arial; font-size:20px;">

	    Custom</span>

	  </td>

	  <td width="100%" class="tabEmpty" valign="bottom">&nbsp;</td>

	</tr>

</table>



<table id="quickSearch" style="position: absolute; left: 0px; top: 76px; font-size:130%;">

	<tr>

		<td colspan="2">Find festivals that are:

	</tr>

	<tr>

		<td>&nbsp;</td>

		<td><li><a href="a" onClick="searchUpcoming(); return false;">Upcoming</a></li></td>

	</tr>

	<tr>

		<td>&nbsp;</td>

		<td><li><a href="a" onClick="searchCheap(); return false;">Cheap</a></li></td>

	</tr>

	<tr>

		<td>&nbsp;</td>

		<td><li><a href="a" onClick="searchHiRate(); return false;">Highly rated</a></li></td>

	</tr>

	<tr>

		<td>&nbsp;</td>

		<td><li><a href="a" onClick="searchStudent(); return false;">Student-friendly</a></li></td>

	</tr>

	<tr>

		<td>&nbsp;</td>

		<td><li><a href="a" onClick="searchFestList(); return false;">On my FestList</a></li></td>

	</tr>

	<tr>

		<td>&nbsp;</td>

		<td style="font-size:80%; font-weight:bold;"><input name="mustAccept" type="checkbox" class="radio"

			onClick="setStillAccepting(); document.form1.all('submit').click();">
			Still accepting submissions</td>

	</tr>

</table>



<div id="advancedSearch" style="width: 170px; display: none; position: absolute; left: 0px; top: 76px; font-family:trebuchet ms, arial;">

	Custom search steps:

	<table style="font-family:trebuchet ms, arial;">

		<tr>

			<td valign="top">1.

			<td>Click a search criteria to expand it

		</tr>

		<tr>

			<td valign="top">2.

			<td>Specify the criteria

		</tr>

		<tr>

			<td valign="top">3.

			<td>The search will be conducted automatically

		</tr>

	</table>



	<form name="form1" method="post" action="listing.php" target="listingFrame">

          <a href="a" onClick="showHide('category'); return false;">+ Category:</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h1')" onMouseOver="popUp(event,'h1')"><span class="help">&nbsp;?&nbsp;</span></a>

          <select name="category" style="display: none;" onChange="this.form.submit()">

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



					<br>

					<a href="a" onClick="showHide('projection'); return false;">+ Projection:</a> <a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h2')" onMouseOver="popUp(event,'h2')"><span class="help">&nbsp;?&nbsp;</span></a>

          <select name="projection" style="display: none;" onChange="this.form.submit()">

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



         	<br>

					<a href="a" onClick="showHide('festDate'); return false;">+ Festival Date:</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h3')" onMouseOver="popUp(event,'h3')"><span class="help">&nbsp;?&nbsp;</span></a>

          <span id="festDate" style="display: none;">

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



					<br>

					<a href="a" onClick="showHide('appDead'); return false;">+ Application deadline</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h4')" onMouseOver="popUp(event,'h4')"><span class="help">&nbsp;?&nbsp;</span></a>

          <span id="appDead" style="display: none;">Deadline after <br>

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



					<br>

          <a href="a" onClick="showHide('feeUnder'); return false;">+ Submission fee</a>

					<span id="feeUnder" style="display: none;"><br>$<input type="text" name="fee" size="3" maxlength="3"></span>



					<br>

					<a href="a" onClick="showHide('location'); return false;">+ Location:</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h5')" onMouseOver="popUp(event,'h5')"><span class="help">&nbsp;?&nbsp;</span></a>

          <select name="location" style="display: none;"onchange="this.form.submit()">

						<?= $locationBox["data"] ?>

          </select>



					<br>

					<a href="a" onClick="showHide('minRating'); return false;">+ Minimum Rating:</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h7')" onMouseOver="popUp(event,'h7')"><span class="help">&nbsp;?&nbsp;</span></a>

					<table id="minRating" style="display: none;">

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



					<br>

          <a href="a" onClick="showHide('reviews'); return false;">+ Reviews</a> <a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h8')" onMouseOver="popUp(event,'h8')"><span class="help">&nbsp;?&nbsp;</span></a>

          <span id="reviews" style="display: none"><br>At least <select name="numreviews"  onchange="this.form.submit()">

            <option value="0" selected>0</option>

            <option value="1">1</option>

            <option value="5">5</option>

            <option value="10">10</option>

          </select>

          reviews</span>



          <br>

					<a href="a" onClick="showHide('student'); return false;">+ Student:</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h9')" onMouseOver="popUp(event,'h9')"><span class="help">&nbsp;?&nbsp;</span></a>

					<span id="student" style="display: none">

          <br><input type="checkbox" class="radio" name="studentfriendly" value="student" onclick="this.form.submit()" >

          Must be student friendly</span>



					<br>

					<a href="a" onClick="showHide('prizes'); return false;">+ Prizes</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h11')" onMouseOver="popUp(event,'h11')"><span class="help">&nbsp;?&nbsp;</span></a>

					<span id="prizes" style="display: none">

          <br>

					<input type="checkbox" class="radio" name="cash" value="2"  onclick="this.form.submit()">

					Must have cash prizes</span>



					<br>

					<a href="a" onClick="showHide('text'); return false;">+ Text Search</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h10')" onMouseOver="popUp(event,'h10')"><span class="help">&nbsp;?&nbsp;</span></a>

					<span id="text" style="display: none;">

          <br>Description contains:

          <input type="text" name="textfield" size="10" maxlength="255" >

					</span>



					<br>

					<a href="a" onClick="showHide('festListDiv'); return false;">+ FestList</a>&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')" onMouseOut="popUp(event,'h12')" onMouseOver="popUp(event,'h12')"><span class="help">&nbsp;?&nbsp;</span></a>

					<span id="festListDiv" style="display: none;">

          <br><input type="checkbox" class="radio" name="festList" value="festList" onclick="this.form.submit()" >

						On my FestList

					</span>



          <br><br>

					<input type="submit" class="button" name="Submit" value="Search" onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">

					<br>

          <input type="reset" class="button" name="reset" value="Reset" onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">





</body>

</html>