<?
$cacheLimiter = "public";
require_once "dbFunctions.php";
if(!isLoggedIn())
	redirectToLogin();

fd_connect();

// ADDFEST: If this an add, overwrite the password and id fields
if($HTTP_POST_VARS["operation"] == "add") {
	$operation = "add";
	$title = fd_filter($_POST["title"]);
	fd_query("insert into fests(title, created) values('$title', now())");
	$id = mysql_insert_id();
	// grant adminstrator privledges to user for this fest
	fd_query("insert into userFest (userID, festID, relation) values ("
		. $_SESSION["user_id"] . ", $id, 'admin')");
	// set the primaryType as administrator
	fd_query("update user set primaryType = 'administrator' where id = "
		. $_SESSION["user_id"]);
} else {
	$operation = "update";

	// Update undo
	$id = fd_filter($_REQUEST["ID"], true);
	fd_query("update fests set undoSQL = '"
	  . mysql_escape_string(createUndo("fests", "ID", intval($id), array("undoSQL", "status")))
	  . "' where ID = $id");
}

$update1 = "update fests set ";

while(list($key, $val) = each($HTTP_POST_VARS)) {
	$val = fd_filter($val);
    if ($key == 'ID') {
    	if($operation == "update")
        	$id = fd_filter($val, true);
	}
    elseif($key == 'Submit2' || $key == 'context' || strpos($key, "Select") > 0
    	|| $key == "operation")
    	{ /* Don't put these in the update string */ }

    elseif ($val == "Not selected")
    	$update1 .= "$key='', ";

    elseif ($key == 'appURL') { $update1.="$key='$val' "; }

    elseif ($key == 'submission') {$submission=$val;}

    # venue info, which all start with a 'v'
    //elseif (substr($key, 0, 1) == "v") $update1 .= "$key= '$val', ";

	elseif ($key == 'oZip' && $val != '') {$update1.="$key='$val', ";}
	else {$update1.="$key='$val', ";}
}

if(!festEditAuthorized($_SESSION["user_id"], $id))
	trigger_error("update fest unauthorized");

fd_query("update fests set submission=".$submission." where id=".$id);
$update1.="WHERE ID=$id";
$today1 = getdate();
$month1 = $today1['mon'];
$mday1 = $today1['mday'];
$year1 = $today1['year'];
fd_query("update fests set lastDate='$year1-$month1-$mday1' where id=$id");

$datequery = mysql_fetch_array(fd_query ("select MONTH(startDate) AS startmonth, MONTHNAME(startDate) AS startmonthn, DAYOFMONTH(startDate) AS startday, YEAR(startDate) AS startyear, MONTH(endDate) AS endmonth, MONTHNAME(endDate) AS endmonthn, DAYOFMONTH(endDate) AS endday, YEAR(endDate) AS endyear, MONTH(eDead) AS emonth, MONTHNAME(eDead) AS emonthn, DAYOFMONTH(eDead) AS eday, YEAR(eDead) AS eyear, MONTH(nDead) AS nmonth, MONTHNAME(nDead) AS nmonthn, DAYOFMONTH(nDead) AS nday, YEAR(nDead) AS nyear, MONTH(lDead) AS lmonth, MONTHNAME(lDead) AS lmonthn, DAYOFMONTH(lDead) AS lday, YEAR(lDead) AS lyear FROM fests where ID=$id"));
fd_query("$update1");

// Set unverified
fd_query("update fests set status = 'unverified' where ID = $id");

$result = mysql_fetch_assoc(fd_query("select * from fests where id = $id"));
?>

<html>

<head>

<title>Festival Update - Page 2 of 6</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="styles/info.css" type="text/css">
<script language="JavaScript">
<!--
function validate(object,text) {
    if (object.value.length > 0 && object.value > 0)
        return true;
    else {
        alert('Please select a ' + text);
        if (navigator.appName.indexOf('Netscape') > -1) {
            object.focus();
        }
        return false;
    }
}

function formvalidate() {
    var validated = true;
<? if($submission) { ?>
 	if (!validate(document.form2.nmonth,'Regular Deadline Month'))
        validated = false;
	if (!validate(document.form2.nday,'Regular Deadline Day'))
        validated = false;
	if (!validate(document.form2.nyear,'Regular Deadline Year'))
        validated = false;
	if (!validate(document.form2.startmonth,'Start Date'))
		validated = false;
<? } ?>
	if (!validate(document.form2.startday,'Start Date'))
		validated = false;
	if (!validate(document.form2.startyear,'Start Date'))
		validated = false;
	if (!validate(document.form2.endmonth,'End Date'))
		validated = false;
	if (!validate(document.form2.endday,'End Date'))
		validated = false;
	if (!validate(document.form2.endyear,'End Date'))
		validated = false;
    return validated;
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>



<body bgcolor="#000000" text="#FFFFFF" class="description">

<span align="center"><img src="images/login2.gif"></span>
<form name="form2" method="post" action="login3.php" class="description" onSubmit="formvalidate();">

  <table border="0" cellspacing="0" cellpadding="10" class="description" width="100%">

    <tr>

      <td>
        <table border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td class="description" colspan="2"><span class="popupheading">DATES:</span></td>
            <td class="description">
              <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page2','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a></div>
            </td>
          </tr>
          <tr>
            <td class="description" >Start Date</td>
            <td >
              <select name="startmonth" class="select" onChange="this.form.endmonth.selectedIndex = this.selectedIndex; validate(this.form.startmonth,'Start Month')">
                <option value="<? print $datequery["startmonth"] ?>" selected>
                <? print $datequery["startmonthn"] ?>
                </option>
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
              <select name="startday" class="select" onChange="this.form.endday.selectedIndex = this.selectedIndex; validate(this.form.startday,'Start Day')">
                <option value="<? print $datequery["startday"] ?>" selected>
                <? print $datequery["startday"] ?>
                </option>
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
              <select name="startyear" class="select" onChange="this.form.endyear.selectedIndex = this.selectedIndex; validate(this.form.startyear,'Start Year')">
                <option value="<? print $datequery["startyear"] ?>" selected>
                <? print $datequery["startyear"] ?>
                </option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
              </select>
            </td>
            <td width="195">&nbsp;</td>
          </tr>
          <tr>
            <td class="description" >End Date</td>
            <td >
              <select name="endmonth" class="select" onChange="validate(this.form.endmonth,'End Month')">
                <option value="<? print $datequery["endmonth"] ?>" selected>
                <? print $datequery["endmonthn"] ?>
                </option>
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
              <select name="endday" class="select" onChange="validate(this.form.endday,'End Day')">
                <option value="<? print $datequery["endday"] ?>" selected>
                <? print $datequery["endday"] ?>
                </option>
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
              <select name="endyear" class="select" onChange="validate(this.form.endyear,'End Year')">
                <option value="<? print $datequery["endyear"] ?>" selected>
                <? print $datequery["endyear"] ?>
                </option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
              </select>
            </td>
            <td width="195">&nbsp;</td>
          </tr>

<?
if ($submission == 1) { ?>
          <tr>
            <td class="description" colspan="2"><br>
              <span class="popupheading"> SUBMISSION DEADLINES:</span></td>
            <td class="description">
              <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page2','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a></div>
            </td>
          </tr>
          <tr>
            <td class="description" >Early</td>
            <td >
              <select name="emonth" class="select">
                <option value="0"></option>
                <option value="<? print $datequery["emonth"] ?>" selected>
                <? print $datequery["emonthn"] ?>
                </option>
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
              <select name="eday" class="select">
                <option value="0"></option>
                <option value="<? print $datequery["eday"] ?>" selected>
                <? if($datequery["eday"] != 0){print $datequery["eday"];} ?>
                </option>
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
              <select name="eyear" class="select">
                <option value="0"></option>
                <option value="<? print $datequery["eyear"] ?>" selected>
                <? if($datequery["eyear"] != 0){print $datequery["eyear"];} ?>
                </option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
              </select>
            </td>
            <td rowspan="3" class="description" width="200">If you only have a
              late and an early deadline, please enter the early deadline in the
              EARLY field, and the late deadline in the REGULAR field.</td>
          </tr>
          <tr>
            <td class="description" >Regular</td>
            <td >
              <select name="nmonth" class="select" onChange="validate(this.form.nmonth,'Regular Deadline Month')">
                <option value="0"></option>
                <option value="<? print $datequery["nmonth"] ?>" selected>
                <? print $datequery["nmonthn"] ?>
                </option>
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
              <select name="nday" class="select" onChange="validate(this.form.nday,'Regular Deadline Day')">
                <option value=></option>
                <option value="<? print $datequery["nday"] ?>" selected>
                <? if($datequery["nday"] != 0){print $datequery["nday"];} ?>
                </option>
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
              <select name="nyear" class="select" onChange="validate(this.form.nyear,'Regular Deadline Year')">
                <option value="0"></option>
                <option value="<? print $datequery["nyear"] ?>" selected>
                <? if($datequery["nyear"] != 0){print $datequery["nyear"];} ?>
                </option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="description" >Late</td>
            <td >
              <select name="lmonth" class="select">
                <option value="0"></option>
                <option value="<? print $datequery["lmonth"] ?>" selected>
                <? print $datequery["lmonthn"] ?>
                </option>
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
              <select name="lday" class="select">
                <option value="0"></option>
                <option value="<? print $datequery["lday"] ?>" selected>
                <? if($datequery["lday"] != 0){print $datequery["lday"];} ?>
                </option>
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
              <select name="lyear" class="select">
                <option value="0"></option>
                <option value="<? print $datequery["lyear"] ?>" selected>
                <? if($datequery["lyear"] != 0){print $datequery["lyear"];} ?>
                </option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
              </select>
            </td>
          </tr>
		  <? } ?>
        </table>
<br>

        <br>
        <span class="popupheading">Statistics:</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page2','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a>
        <br>
        Last year's total number of <b>FESTIVAL ENTRIES</b>:
        <input type="text" name="numEntries" value="<? print $result["numEntries"] ?>" class="select" size="5" maxlength="5">

        <br>
        Last year's total number of <b>OFFICIAL SELECTIONS</b>:
        <input type="text" name="numAccepted" value="<? print $result["numAccepted"] ?>" class="select" size="5" maxlength="5">

        <br>

		<br>
        <span class="popupheading">Prizes / Competition:</span> &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page2','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>

        This festival:<br>
		<input type="radio" name="award" value="0" <? if($result["award"] == 0){print "checked";} ?>>
        Has no competition<br>
		<input type="radio" name="award" value="1" <? if($result["award"] == 1){print "checked";} ?>>Has awards, but no cash prizes<br>
		<input type="radio" name="award" value="2" <? if($result["award"] == 2){print "checked";} ?>>Offers cash or other prizes<br><br>
        Please describe the competition, prizes, and awards: &nbsp;&nbsp;<br>

        <textarea name="prizes" class="select" cols="60" rows="8" wrap="VIRTUAL"><?
		$temp = $result["prizes"];
		$temp = str_replace("<br />", "", $temp);
		print $temp;

		 ?></textarea>

        <br>

		 <input type="hidden" name="id" value="<? print $id; ?>">

        <input type="button" name="Submit2" value="<- Back" class="select" onclick="history.go(-1)">

        <input type="submit" name="Submit2" value="Next ->" class="select">

      </td>

      <td>&nbsp;</td>

    </tr>

    <tr>

      <td>&nbsp;</td>

      <td>&nbsp;</td>

    </tr>

  </table>

</form>

</body>

</html>