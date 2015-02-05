<?
$cacheLimiter = "public";
require_once "dbFunctions.php";
if(!isLoggedIn())
	redirectToLogin();

fd_connect();

# update1 is created from all the fields
$update1 = "update fests set ";

while(list($key, $val) = each($HTTP_POST_VARS)) {
	if ($key != 'Submit2' && $key != 'id' && $key != 'stFriend') {
		$val = fd_filter($val);
		$val = nl2br($val);

		$update1.="$key='$val', ";
		$$key = $val;
	} elseif($key == "id") {
    	$$key = fd_filter($val, true);
	} else { $$key = $val; }
}

if (!empty($stFriend)){
	$update1 .="stFriend=1";
} else {$update1 .="stFriend=0";}

$update1 .= " WHERE ID=$id";

if(!festEditAuthorized($_SESSION["user_id"], $id))
	trigger_error("update fest unauthorized");

$query1 = "select * from fests where ID=$id";
$result = mysql_fetch_array(fd_query ("$query1"));

if ($result != '') {
	fd_query("$update1");
	#print $update1;
	$projection = fd_query ("select * from projections");
	$category = fd_query ("select * from categories");
	$projection2 = fd_query ("select PID from projtable where FID = $id");
	$category2 = fd_query ("select CID from cattable where FID = $id");
	$category3 = array();
	$k=0;

	while($value = mysql_fetch_array($category2)){
		$category3[$k]=$value["CID"];
		$k++;
	}

	$projection3 = array();
	$k=0;
	while($value2 = mysql_fetch_array($projection2)){
		$projection3[$k] = $value2["PID"];
		$k++;
	}

	mysql_close();
?>

<html>

<head>

<title>Festival Update - Page 4 of 6</title>

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
<span align="center"><img src="images/login4.gif"></span>
<form name="form2" method="post" action="login5.php" class="description">

 <input type="hidden" name="id" value="<? print $id; ?>">
  <table border="0" cellspacing="0" cellpadding="10" class="description" width="100%">

    <tr>

      <td colspan="2">

        <p><span class="popupheading">Submission Checklist</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page4','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>

          <textarea name="subCheck" wrap="VIRTUAL" rows="6" cols="80" class="select"><?
		$temp = $result["subCheck"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea>
        </p>

        <p><span class="popupheading">Eligibility Requirements</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page4','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>

          <textarea name="eligibility" wrap="VIRTUAL" rows="4" cols="80" class="select"><?
		  $temp = $result["eligibility"];
		$temp = str_replace("<br />", "", $temp);
		print $temp; ?></textarea>
        </p>

      </td>

    </tr>

    <tr>

      <td valign="top"><span class="popupheading">Categories</span>&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page4','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a>

        <table border="0" cellspacing="0" cellpadding="0" class="description">

          <? $j=2;

			while($row2 = mysql_fetch_array($category)) {

            $j++;

			if ($j%2==1){ ?>

          <tr>

            <td>

              <input type="checkbox" name="c<? print($row2["ID"]); ?>" value="<? print($row2["ID"]); ?>" <?
			  foreach ($category3 as $value){
			  	if ($value==$row2["ID"]){
				print "checked";
				}
				} ?>>

              <? print($row2["cat"]); ?>

            </td>

            <? } else { ?>

            <td>

              <input type="checkbox" name="c<? print($row2["ID"]); ?>" value="<? print($row2["ID"]); ?>"<?
			  foreach ($category3 as $value){
			  	if ($value==$row2["ID"]){
				print "checked";
				}
				} ?>>


              <? print($row2["cat"]); ?>

            </td>

          </tr>

          <? } ?>

          <? } ?>

        </table>

      </td>

      <td valign="top"><span class="popupheading">Projections</span> &nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page4','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a>

        <table border="0" cellspacing="0" cellpadding="0" class="description">

          <? $i=2;

			while($row = mysql_fetch_array($projection)) {

            $i++;

			if ($i%2==1){ ?>

          <tr>

            <td>

              <input type="checkbox" name="p<? print($row["ID"]); ?>" value="<? print($row["ID"]); ?>"<?
			  foreach ($projection3 as $value){
			  	if ($value==$row["ID"]){
				print "checked";
				}
				} ?>>


              <? print($row["proj"]); ?>

            </td>

            <? } else { ?>

            <td>

              <input type="checkbox" name="p<? print($row["ID"]); ?>" value="<? print($row["ID"]); ?>"<?
			  foreach ($projection3 as $value){
			  	if ($value==$row["ID"]){
				print "checked";
				}
				} ?>>


              <? print($row["proj"]); ?>

            </td>

          </tr>

          <? } ?>

          <? } ?>

        </table>

      </td>

    </tr>

    <tr>

      <td>&nbsp;</td>

      <td>

	    <input type="button" name="Submit2" value="<- Back" class="select" onclick="history.go(-1)">

        <input type="submit" name="Submit22" value="Next ->" class="select">

      </td>

    </tr>

  </table>

  </form>

</body>

</html>

<? } ?>