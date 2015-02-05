<?
$cacheLimiter = "public";
require_once "dbFunctions.php";

$id = fd_filter($_POST["id"], true);
if(!festEditAuthorized($_SESSION["user_id"], $id))
	trigger_error("update fest unauthorized");

fd_connect();

$update4 = "update fests set";
while(list($key, $val) = each($HTTP_POST_VARS)) {
		$val = fd_filter($val);
		$val = nl2br($val);
		if ($key == 'id') {
			$id = fd_filter($val, true);
			fd_query("delete from projtable where FID=$id");
			fd_query("delete from cattable where FID=$id");
			}
		elseif (preg_match("/^p/", $key)) {fd_query("INSERT INTO projtable (PID,FID) values ($val,$id)");}
		elseif (preg_match("/^c/", $key)) {fd_query("INSERT INTO cattable (CID,FID) values ($val,$id)");}
		else {$$key = $val;}
	}

if ($subCheck != ''){ $update4 .= " subCheck='$subCheck'";}
else { $update4 .= " subCheck=''";}
if ($eligibility != ''){$update4 .= ", eligibility='$eligibility'";}
else { $update4 .= ", eligibility=''";}
$update4 .= " WHERE ID=$id";
	$query1 = "select * from fests where ID=$id";
	$result = mysql_fetch_array(fd_query ("$query1"));
if ($result != '') {
fd_query("$update4");
			$feese = mysql_fetch_array(fd_query ("select * from fees where festID = $id AND dateType = 'Early'"));
			$feesn = mysql_fetch_array(fd_query ("select * from fees where festID = $id AND dateType = 'Normal'"));
			$feesl = mysql_fetch_array(fd_query ("select * from fees where festID = $id AND dateType = 'Late'"));

// The number of fees for the fest
$numFees = mysql_fetch_array(fd_query("SELECT COUNT(festID) AS numFees FROM fees WHERE festID = $id"));
$error = "error: " . mysql_error();

	$datequery = mysql_fetch_array(fd_query ("select MONTH(startDate) AS startmonth, MONTHNAME(startDate) AS startmonthn, DAYOFMONTH(startDate) AS startday, YEAR(startDate) AS startyear, MONTH(endDate) AS endmonth, MONTHNAME(endDate) AS endmonthn, DAYOFMONTH(endDate) AS endday, YEAR(endDate) AS endyear, MONTH(eDead) AS emonth, MONTHNAME(eDead) AS emonthn, DAYOFMONTH(eDead) AS eday, YEAR(eDead) AS eyear, MONTH(nDead) AS nmonth, MONTHNAME(nDead) AS nmonthn, DAYOFMONTH(nDead) AS nday, YEAR(nDead) AS nyear, MONTH(lDead) AS lmonth, MONTHNAME(lDead) AS lmonthn, DAYOFMONTH(lDead) AS lday, YEAR(lDead) AS lyear FROM fests where ID=$id"));
mysql_close();
?>
<html>
<head>
<title>Festival Update - Page 5 of 6</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
<script language="JavaScript">
<!--
function preloadFees() {
	<?
    if($result["submission"]==1) {
	    if($numFees["numFees"] == 0) {
	        print "document.form3.freeRadio.click();";
	    } else {
	        print "document.form3.costsRadio.click();";
	    }
    	//print "alert(" . $numFees["numFees"] . ")";
    }
    ?>
}



function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
/*function fillin() {
	document.form3.fee1.value=0;
	document.form3.fee2.value=0;
	document.form3.fee3.value=0;
	document.form3.fee4.value=0;
	document.form3.fee5.value=0;
	document.form3.fee6.value=0;
	document.form3.fee7.value=0;
	document.form3.fee8.value=0;
	document.form3.fee9.value=0;
	document.form3.fee10.value=0;
	document.form3.fee11.value=0;
	document.form3.fee12.value=0;
}*/

function formvalidate() {
    <? if($result["submission"]==1) { ?>
        if (document.form3.free.value==1) {
	        var fee5 = document.form3.fee5.value;
	        var fee6 = document.form3.fee6.value;
	        var fee7 = document.form3.fee7.value;
	        var fee8 = document.form3.fee8.value;

	        if (fee5 == 0 &&
	            fee6 ==0 &&
	            fee7 ==0 &&
	            fee8 ==0) {
	                alert('Please enter a NORMAL deadline entry fee.');
	            return false;
	        }
	        //for (i = 0; i < fee5.length; i++) {
	         //   var c = s.charAt(i);
	          //  if (!isDigit(c)) return false;
	        //alert('Please enter a fee.');
	        //}
    	}
    <? } ?>
	return true;
}
//-->
</script>
		<script src="findDOMNested.js"></script>

<script language="JavaScript">
<!--
var oldDom = null;

			function swapForm(objectID){
				dom = findDOM('formStack',objectID,1);
				if (oldDom) oldDom.visibility = 'hidden';
				dom.visibility = 'visible';
				oldDom = dom;
				oldObjectID = objectID;
			}
			function setfree(arg){
			document.form3.free.value=arg;
			}
--></script>
		<style media="screen" type="text/css"><!--
#formStack {
	visibility: visible;
	position: relative
	}
#fees     {
	padding: 0px;
	visibility: hidden;
	position: relative
	}
#nofees     {
	padding: 0px;
	visibility: hidden;
	position: absolute
	}
--></style>
</head>
<body bgcolor="#000000" text="#FFFFFF" class="description" link="#000000" vlink="#000000" alink="#000000" onLoad="preloadFees()">
<span align="center"><img src="images/login5.gif"></span>
<form name="form3" method="post" action="login6.php" class="description" onSubmit="return formvalidate()">
     <input type="hidden" name="id" value="<? print $id; ?>">
<table border="0" cellspacing="0" cellpadding="0" class="description" width="100%">
    <tr>
      <td>
	  <?
if ($result["submission"]==1) { ?>
Does this festival charge submission fees?
<input type="hidden" name="free" value="1">
<input type="radio" id="costsRadio" onclick="swapForm('fees'),setfree(1)" name="radio">Yes&nbsp;&nbsp;
<input type="radio" id="freeRadio" onclick="swapForm('nofees'),setfree(2)" name="radio">No
<p>
        <div id="formStack">
<div id="fees" name="fees">
            <table border="0" cellspacing="10" cellpadding="0" class="description">
              <tr align="center">
                <td class="heading" colspan="4">
                  <div align="left"><span class="popupheading">Fees</span>
                  <a href="#" onClick="MM_openBrWindow('help.php#page5','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a></div>
								</td>
              </tr>
              <tr align="center">
                <td colspan="5">
                  <div align="left">Please fill out the submission fees table
                    for as many combinations of deadlines and categories as possible.
                    Whole US Dollar amounts only. Fill in as many fees as possible,
                    EVEN if your website has them. - <b>ONLY ENTER NUMBERS, NO
                    LETTERS OR PUNCTUATION!</b></div>
                </td>
              </tr>
              <tr align="center">
                <td width="235">$US</td>
                <td width="20">FEATURE</td>
                <td width="20">SHORT</td>
                <td width="20">STUDENT</td>
                <td width="20">OTHER</td>
              </tr>
              <? if ($result["eDead"] != '0000-00-00') {
			?>
              <tr align="center">
                <td width="235">EARLY
                  (
                  <? print $datequery["emonthn"]." ".$datequery["eday"].", ".$datequery["eyear"]; ?>
                  )</td>
                <td width="20">
                  <input type="text" name="fee1" size="6" maxlength="6" class="select" value=<? if($feese["Feature"] != -1) print $feese["Feature"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee2" size="6" maxlength="6" class="select" value=<? if($feese["Short"] != -1) print $feese["Short"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee3" size="6" maxlength="6" class="select" value=<? if($feese["Student"] != -1) print $feese["Student"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee4" size="6" maxlength="6" class="select" value=<? if($feese["Other"] != -1) print $feese["Other"] ?>>
                </td>
              </tr>
              <? } ?>
              <? if ($result["nDead"] != '0000-00-00') { ?>
              <tr align="center">
                <td width="235">NORMAL
                  (
                  <? print $datequery["nmonthn"]." ".$datequery["nday"].", ".$datequery["nyear"]; ?>
                  )</td>
                <td width="20">
                  <input type="text" name="fee5" size="6" maxlength="6" class="select" value=<? if($feesn["Feature"] != -1)print $feesn["Feature"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee6" size="6" maxlength="6" class="select" value=<? if($feesn["Short"] != -1)print $feesn["Short"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee7" size="6" maxlength="6" class="select" value=<? if($feesn["Student"] != -1) print $feesn["Student"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee8" size="6" maxlength="6" class="select" value=<? if($feesn["Other"] != -1) print $feesn["Other"] ?>>
                </td>
              </tr>
              <? } ?>
              <? if ($result["lDead"] != '0000-00-00') { ?>
              <tr align="center">
                <td width="235">LATE
                  (
                  <? print $datequery["lmonthn"]." ".$datequery["lday"].", ".$datequery["lyear"]; ?>
                  )</td>
                <td width="20">
                  <input type="text" name="fee9" size="6" maxlength="6" class="select" value=<? if($feesl["Feature"] != -1) print $feesl["Feature"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee10" size="6" maxlength="6" class="select" value=<? if($feesl["Short"] != -1) print $feesl["Short"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee11" size="6" maxlength="6" class="select" value=<? if($feesl["Student"] != -1) print $feesl["Student"] ?>>
                </td>
                <td width="20">
                  <input type="text" name="fee12" size="6" maxlength="6" class="select" value=<? if($feesl["Other"] != -1) print $feesl["Other"] ?>>
                </td>
              </tr>
              <? } ?>
            </table>
          </div>
		<div id="nofees" name="nofees">
		&nbsp;
		</div>
		</div>
      </td>
    </tr>
    <tr>
      <td>
        <table border="0" cellspacing="0" cellpadding="0" class="description">
          <tr>
            <td valign="bottom" align="left" width="90"><span class="popupheading">Fee Note</span></td>
            <td>If you have special fees based on eligibilty, please describe
              them below.&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page5','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a></td>
          </tr>
          <tr>
            <td colspan="2" valign="top">
              <textarea name="feeNote" cols="80" rows="2" wrap="VIRTUAL" class="select"><?
			  $temp = $result["feeNote"];
			$temp = str_replace("<br />", "", $temp);
			print $temp; ?></textarea>
               </td>
          </tr>
        </table>
		<? } ?>
  <br>
        <p><span class="popupheading">What Sets you apart</span><span class="description"> What
          distinguishes your festival from the rest?</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page5','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="distinguish" wrap="VIRTUAL" rows="2" cols="80" class="select"><?
		  $temp = $result["distinguish"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea>
         </p>
          <p><span class="popupheading">Party Scene</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page5','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="party" wrap="VIRTUAL" rows="2" cols="80" class="select"><?
		 	  $temp = $result["party"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea>
        </p>

        <p><span class="popupheading">Press Coverage</span><span class="description">
          What kind of press coverage does this fest get?</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page5','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="press" wrap="VIRTUAL" rows="2" cols="80" class="select"><?
		  	  $temp = $result["press"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea>
        </p>
        <p><input type="button" name="Submit2" value="<- Back" class="select" onclick="history.go(-1)"><input type="submit" name="Submit22" value="Next ->" class="select"></p>
      </td>
    </tr>
  </table>
  </form>
</body>
</html>
<? } ?>