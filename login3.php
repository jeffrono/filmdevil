<?
$cacheLimiter = "public";
require_once "dbFunctions.php";
if(!isLoggedIn())
	redirectToLogin();

fd_connect();

$update2 = "update fests set";

import_request_variables("p", "form_");
fd_filter_batch(array("form_id", "form_startyear", "form_startday", "form_startmonth",
	"form_endyear", "form_endmonth", "form_endday", "form_numEntries",
    "form_numAccepted", "form_award", "form_eyear", "form_emonth", "form_eday",
    "form_nyear", "form_nmonth", "form_nday",
    "form_lyear", "form_lmonth", "form_lday", "form_award"), true, true);
fd_filter_batch(array("form_prizes"));
$form_prizes = nl2br($form_prizes);

if(!festEditAuthorized($_SESSION["user_id"], $form_id))
	trigger_error("update fest unauthorized");

if ($form_startyear != 'any' && $form_startday != 0 && $form_startmonth != 0
	&& $form_endyear != 'any' && $form_endday != 0 && $form_endmonth != 0){
	$update2 .= " startDate='$form_startyear-$form_startmonth-$form_startday', endDate='$form_endyear-$form_endmonth-$form_endday'";
}

if ($form_numEntries != ''){$update2.=", numEntries=$form_numEntries";}
else {$update2.=", numEntries=''";}
if ($form_numAccepted != ''){$update2.=", numAccepted=$form_numAccepted";}
else {$update2.=", numAccepted=''";}

if ($form_eyear != 0 && $form_eday != 0 && $form_emonth != 0){
	$update2 .= ", eDead='$form_eyear-$form_emonth-$form_eday'";
} else { $update2 .= ", eDead=''";}
if ($form_nyear != 0 && $form_nday != 0 && $form_nmonth != 0){
	$update2 .= ", nDead='$form_nyear-$form_nmonth-$form_nday'";
} else{$update2 .= ", nDead=''";}
if ($form_lyear != 0 && $form_lday != 0 && $form_lmonth != 0){
	$update2 .= ", lDead='$form_lyear-$form_lmonth-$form_lday'";
}else{$update2 .= ", lDead=''";}

$update2 .= " WHERE ID=$form_id";

$query1 = "select * from fests where ID=$form_id";
$result = mysql_fetch_array(fd_query ("$query1"));

if ($result != '') {
	fd_query("$update2");
	fd_query("update fests set award=$form_award where ID=$form_id");
	fd_query("update fests set prizes='$form_prizes' where ID=$form_id");
	mysql_close();
} else die("No fest found");
?>
<html>
<head>
<title>Festival Update - Page 3 of 6</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
<script language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body bgcolor="#000000" text="#FFFFFF" class="description">
<span align="center"><img src="images/login3.gif"></span>
<form name="form3" method="post" action="login4.php" class="description">
  <table border="0" cellspacing="10" cellpadding="0" class="description" width="100%">
    <tr>
      <td>
        <p><span class="popupheading">Theme</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="theme" wrap="VIRTUAL" rows="2" cols="80" class="select"><?
#		  if ($result["theme"] != "") {
#			  $temp = $result["theme"];
#			  $temp = str_replace("<br />", "", $temp);
#			  print $temp;
#		} ?>
</textarea>
        </p>
        <p><span class="popupheading">Tagline</span><span class="description"> (Your festival's slogan in 25 words or less)</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="tagline" wrap="VIRTUAL" rows="1" cols="80" class="select"><?
		$temp = $result["tagline"];
		$temp = str_replace("<br />", "", $temp);
		print $temp;
		?></textarea>
        </p>
		<p><span class="popupheading">General Description</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="descriptGen" wrap="VIRTUAL" rows="5" cols="80" class="select"><?

  		$temp = $result["descriptGen"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea>
        </p>
        <p><span class="popupheading">Professional Description</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="descriptPro" wrap="VIRTUAL" rows="3" cols="80" class="select"><?
		$temp = $result["descriptPro"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea>
        </p>
        <p><span class="popupheading">Student Description</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          <textarea name="descriptStu" wrap="VIRTUAL" rows="3" cols="80" class="select"><?
		  $temp = $result["descriptStu"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea></p>
        <span class="popupheading">Student-oriented?</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page3','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
				<p>
				<input type="checkbox" name="stFriend" class="select" <? if($result["stFriend"] ==1) {print "checked";} ?>>        The festival has <i>student recognition </i>(IE a student category,
        special student fees, located on a college campus, etc.
        <br><input type="hidden" name="id" value="<? print $form_id; ?>">
				<p>
				<input type="button" name="Submit2" value="<- Back" class="select" onclick="history.go(-1)">
        <input type="submit" name="Submit2" value="Next ->" class="select">
      </td>
    </tr>
  </table>
  </form>
</body>
</html>