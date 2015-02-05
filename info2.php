 <?
require_once "dbFunctions.php";
fd_connect();

if(!empty($HTTP_GET_VARS['ID'])){
$ID = fd_filter($HTTP_GET_VARS['ID'], true);

$reviews=mysql_query("select *, DATE_FORMAT(date, '%M %e, %Y') AS date from reviews where festID=$ID ORDER BY date ASC");
?>
<?
// Gets the reduced image size that stays within the supplied limits,
// keeping the original scale
// Returns an associative array with 'width' and 'height' keys
function getScaledImageSize($imageURL, $maxWidth, $maxHeight) {
	$origSize = getimagesize($imageURL);
    $widthScale = $maxWidth / $origSize[0];
    $heightScale = $maxHeight / $origSize[1];
    $newSize = array("width" => $maxWidth, "height" => $maxHeight);
    if($widthScale < $heightScale) {
    	// width bigger than height
        $newSize["height"] = $origSize[1] * $widthScale;
	} elseif($widthScale > $heightScale) {
        // height bigger than width
        $newSize["width"] = $origSize[0] * $heightScale;
    }
    return $newSize;
}



$festival=mysql_fetch_array(mysql_query("select * from fests where ID=$ID"));
$reviewdate = mysql_query ("SELECT DATE_FORMAT(date, '%M %e, %Y') AS date from reviews where festID=$ID");
$reviewdate = mysql_fetch_array($reviewdate);


$fest1 = mysql_query ("select * from fests where ID=$ID");

$projections1 = mysql_query ("select * from projtable p, projections r where p.PID = r.ID AND p.FID = $ID");

$categories1 = mysql_query ("select * from cattable c, categories r where c.CID = r.ID AND c.FID = $ID");

$fees1 = mysql_query ("select * from fees where festID = $ID AND dateType = 'Early'");

$fees2 = mysql_query ("select * from fees where festID = $ID AND dateType = 'Normal'");

$fees3 = mysql_query ("select * from fees where festID = $ID AND dateType = 'Late'");

$date = mysql_query ("SELECT DATE_FORMAT(startDate, '%M %e, %Y') AS date1, DATE_FORMAT(endDate, '%M %e, %Y') AS date2, DATE_FORMAT(eDead, '%c/%e/%y') AS date3, DATE_FORMAT(nDead, '%c/%e/%y') AS date4, DATE_FORMAT(lDead, '%c/%e/%y') AS date5 FROM fests WHERE ID=$ID");

mysql_close();



$fest = mysql_fetch_array($fest1);

$feese = mysql_fetch_array($fees1);

$feesn = mysql_fetch_array($fees2);

$feesl = mysql_fetch_array($fees3);

$date = mysql_fetch_array($date);

?>





<html>

<head>

<title>Info for <? print $fest["title"] ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="styles/info.css" type="text/css">

<script language="JavaScript">

<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}

//-->

</script>
		<script src="findDOMNested.js"></script>
		<script language="JavaScript">

			var oldDom = null;
			var i = 0;


			function swapForm(objectID){

				if ( i != 1){

				dom = findDOM('formStack',objectID,1);

				if (oldDom) oldDom.visibility = 'hidden';

				dom.visibility = 'visible';

				oldDom = dom;

				oldObjectID = objectID;

				i=1;
				}

			}
			function swapForm2(objectID){
				if ( i != 2){

				dom = findDOM('formStack',objectID,1);

				if (oldDom) oldDom.visibility = 'hidden';

				dom.visibility = 'visible';

				oldDom = dom;

				oldObjectID = objectID;
				i=2;
				}


			}





</script>

		<style media="screen" type="text/css"><!--

#formStack {



	visibility: visible;

	position: relative

	}

#canada     {

	padding: 0px;

	visibility: visible;

	position: absolute

	}




--></style>
  <STYLE>
    BODY {scrollbar-3dlight-color: #CC0000;
           scrollbar-arrow-color: #CC0000;
           scrollbar-base-color:black;
           scrollbar-darkshadow-color: #660000;
           scrollbar-face-color:#9900000;
           scrollbar-highlight-color:black;
           scrollbar-shadow-color:#660000}
  </STYLE>
</head>



<body text="#FFFFFF" bgcolor="#000000"" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="swapForm('canada')">
<div id="formStack">

  <div id="canada" name="canada">
    <table width="95%" border="0" cellspacing="0" cellpadding="0" class="description">
      <tr>

    <td colspan="4">

    <td>

  </tr>

  <tr>

    <td align="left" valign="middle">
<? if ($fest["logoURL"] != '') { ?>
      <table cellspacing="0" cellpadding="0" width="120">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

              <td align="center" valign="middle" width="100">
<? $imgSize = getScaledImageSize($fest["logoURL"], 100, 100); ?>

                <img src="<? print $fest["logoURL"] ?>" width=<?= $imgSize["width"] ?> height=<?= $imgSize["height"] ?> ></td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>
<? } else{ ?>
&nbsp;
<? } ?>

   </td>

        <td valign="middle" class="info" align="right" width="2">&nbsp;</td>

    <td colspan="2"  valign="top">

      <table width ="95%" cellspacing="0" cellpadding="0">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="0" cellpadding="5" valign="top" width="95%">

              <tr>

                    <td colspan="3" class="title" valign="top">
                      <? if ($login == '1'){ ?>
                      <a href="#" onClick="MM_openBrWindow('login.php?ID=$id','FestivalUpdate','resizable=yes,width=510,height=575')"><span class="popupheading">Click
                      here to log in and update this information</span></a><br><br> <? } ?>
                      <? print $fest["title"] ?>
                      <br>
                      <span class="description"><i>"<? print $fest["tagline"] ?>"</i></span>
                    </td>

              </tr>

              <tr valign="top">

                <td colspan="1" class="details">

                  <? print $date["date1"]." - ".$date["date2"] ?>

                </td>

                <td colspan="2" class="details">

                  <? print $fest["vCity"];

		  	if ($fest["vState"] != '' && $fest["vCity"] != '') { print (", ");  }

            print $fest["vState"];
		  	if (($fest["vState"] != '' || $fest["vCity"] != '') && $fest["country"] != '') { print (", ");  }

			print $fest["country"]; ?>

                </td>

              </tr>

              <tr valign="bottom">

                <td colspan="2"><a  href="#" onClick="MM_openBrWindow('contactfest.php?id=<? print $fest["ID"] ?>','ContactFest','resizable=yes,width=300,height=250')">Contact this Fest</a>
				<br>
				<a href="action.php?type=3&id=<? print $fest["ID"] ?>" target="_blank">Festival Website</a></td>

                <td><a href="action.php?type=4&id=<? print $fest["ID"] ?>" target="_blank">Application Link</a>
				<br>
                <a href="action.php?type=1&id=<? print $fest["ID"] ?>" target="_blank"><img src="images/goo.jpg" align="absmiddle" border="0"> this fest</a></td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>
           </td>

  </tr>

  <tr valign="top" align="center">

        <td width="240" colspan="2" align="left">
          <? if($fest["submission"]==0){ print "This festival does not accept submissions";} ?>
          <? if ($fest["numAccepted"] != 0 || $fest["numEntries"] != 0) { ?>
          <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="info">

              <tr>

                <td>

                  <? if ($fest["numAccepted"] != 0 && $fest["numEntries"] != 0) { ?>

                  <a class="heading">Acceptance Rate: </a>

                  <? print 100 * ($fest["numAccepted"] / $fest["numEntries"]); ?>

                  %<br>

                  <? } ?>

                  Official Selections:

                  <? print $fest["numAccepted"] ?>

                  <br>

                  Number of Entries:

                  <? print $fest["numEntries"] ?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>
	      <? } ?>
                      <table border="0" cellspacing="5" cellpadding="5" class="info">

              <tr>

                <td><a class="heading">Organizer</a><br>

                  <? print $fest["oName"]."<br>".$fest["oAdr"]."<br>".$fest["oCity"].", ".$fest["oState"]." ".$fest["oZip"]."<br>tel:".$fest["oTel"]."<br>fax:".$fest["oFax"]."<br>" ?>

                </td>

              </tr>

            </table>

            <table border="0" cellspacing="5" cellpadding="5" class="info">

              <tr>

                <td><a class="heading">Venue</a><br>

                  <? print $fest["vName"]."<br>".$fest["vAdr"]."<br>".$fest["vCity"].", ".$fest["vState"]." ".$fest["vZip"]."<br>tel:".$fest["vTel"]."<br>fax:".$fest["vFax"]."<br>" ?>

                </td>

              </tr>

            </table>
          <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="info">

              <tr>

                <td><a class="heading">Film Categories</a><br>

                  <? while($category = mysql_fetch_array($categories1)){print $category["cat"].", ";}?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="info">

              <tr>

                <td><a class="heading">Projection Capability</a><br>

                  <? while($projection = mysql_fetch_array($projections1)){print $projection["proj"].", "; }?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

    </td>

    <td>

      <? if ($fest["theme"] != '') { ?>

      <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details">

              <tr>

                <td><a class="heading">Theme</a><br>

                  <? print $fest["theme"] ?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } ?>

      <? if ($fest["distinguish"] != '') { ?>
	<? print $fest["distinguish"]; ?>

	  <? } ?>
      <? if ($fest["descriptGen"] != '') { ?>

      <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details">

              <tr>

                <td><a class="heading">Description</a><br>

                  <? print $fest["descriptGen"] ?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } ?>
      <? if ($fest["press"] != '') {print $fest["press"]; }?>

      <? if ($fest["descriptStu"] != '') { ?>

      <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details">

              <tr>

                <td><a class="heading">Student Description</a><br>

                  <? print $fest["descriptStu"] ?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } ?>

      <? if ($fest["descriptPro"] != '') { ?>

      <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details">

              <tr>

                <td><a class="heading">Professional Description</a><br>

                  <? print $fest["descriptPro"] ?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } ?>

    </td>

    <td width="210" align="center">

      <? if ($fest["eDead"] != '0000-00-00' || $fest["nDead"] != '0000-00-00' || $fest["lDead"] != '0000-00-00') { ?>

      <table width ="210" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td> <a class="heading">&nbsp;&nbsp;&nbsp;&nbsp;FEES</a>

            <table border="0" cellspacing="1" cellpadding="1" class="info" width="190">

              <tr align="center">

                <td>$US</td>

                <td>Feature</td>

                <td>Short</td>

                <td>Student</td>

                <td>Other</td>

              </tr>

              <? if ($fest["eDead"] != '0000-00-00') { ?>

              <tr align="center">

                <td>Early (

                  <? print $date["date3"] ?>

                  )</td>

                <td>

                  <? if($feese["Feature"] != -1) print $feese["Feature"] ?>

                </td>

                <td>

                  <? if($feese["Short"] != -1) print $feese["Short"] ?>

                </td>

                <td>

                  <? if($feese["Student"] != -1) print $feese["Student"] ?>

                </td>

                <td>

                  <? if($feese["Other"] != -1) print $feese["Other"] ?>

                </td>

              </tr>

              <? } ?>

              <tr align="center">

                <td>Normal (

                  <? print $date["date4"] ?>

                  )</td>

                <td>

                  <? if($feesn["Feature"] != -1)print $feesn["Feature"] ?>

                </td>

                <td>

                  <? if($feesn["Short"] != -1)print $feesn["Short"] ?>

                </td>

                <td>

                  <? if($feesn["Student"] != -1) print $feesn["Student"] ?>

                </td>

                <td>

                  <? if($feesn["Other"] != -1) print $feesn["Other"] ?>

                </td>

              </tr>

              <? if ($fest["lDead"] != '0000-00-00') { ?>

              <tr align="center">

                <td>Late (

                  <? print $date["date5"] ?>

                  )</td>

                <td>

                  <? if($feesl["Feature"] != -1) print $feesl["Feature"] ?>

                </td>

                <td>

                  <? if($feesl["Short"] != -1) print $feesl["Short"] ?>

                </td>

                <td>

                  <? if($feesl["Student"] != -1) print $feesl["Student"] ?>

                </td>

                <td>

                  <? if($feesl["Other"] != -1) print $feesl["Other"] ?>

                </td>

              </tr>

              <? } ?>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } ?>

      <? if ($fest["subCheck"] != '') { ?>

      <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details">

              <tr>

                <td><a class="heading">Submission Checklist</a><br>

                  <? print $fest["subCheck"] ?>

                </td>


              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } ?>

      <? if ($fest["eligibility"] != '') { ?>

      <table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details">

              <tr>

                <td><a class="heading">Eligibility</a><br>

                  <? print $fest["eligibility"] ?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } ?>
	  <? if($fest["prizes"]!=''){ ?>
<table width ="95%" cellspacing="0" cellpadding="0" background="images/bg.gif">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details">

              <tr>

                <td><a class="heading">Awards / Prizes</a><br>

                  <? print $fest["prizes"] ?>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

      <? } elseif($fest["award"]==1){ ?>
	  This festival gives out awards, but no prizes.
	  <? } elseif($fest["award"]==2){ ?>
	  This festival gives out awards and prizes.
	  <? } ?>
    </td>

  </tr>

</table>
</div>


</div>
</body>

</html>

<? } else { ?>

<html>

<head>

<title>Untitled Document</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="styles/info.css" type="text/css">

</head>



<body text="#FFFFFF" bgcolor="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

&nbsp;

</body>

</html>



<? } ?>