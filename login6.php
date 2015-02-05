<?
$cacheLimiter = "public";
require_once "dbFunctions.php";

if(!isLoggedIn())
	redirectToLogin();

fd_connect();

while(list($key, $val) = each($HTTP_POST_VARS)) {
		$val = fd_filter($val);
		$val = nl2br($val);

        if(substr($key, 0, 3) == "fee" && $key != "feeNote")
        	$$key = fd_filter($val, true);
		elseif ($key == 'id') {$id = fd_filter($val, true); }
		elseif ($key == 'feeNote') { }
		else { $$key=$val; }
}


if(!festEditAuthorized($_SESSION["user_id"], $id))
	trigger_error("update fest unauthorized");

$query1 = "select * from fests where ID=$id";
$result = mysql_fetch_array(fd_query ("$query1"));

if ($result != '') {
	if(!empty($feeNote))
    	fd_query("update fests set feeNote='$feeNote' where id=$id");

	if(!empty($free)){
		fd_query("delete from fees where festID=$id");
		if ($free == 1){
			if(!empty($fee1) || !empty($fee2) || !empty($fee3) || !empty($fee4) ){
					$feequery1 = "insert into fees (festID, dateType, Feature, Short, Student, Other) values ($id, 'Early'";
					if ($fee1 != '') { $feequery1.=", $fee1"; }
					else {$feequery1.=", -1"; }
					if ($fee2 != '') { $feequery1.=", $fee2"; }
					else {$feequery1.=", -1"; }
					if ($fee3 != '') { $feequery1.=", $fee3"; }
					else {$feequery1.=", -1"; }
					if ($fee4 != '') { $feequery1.=", $fee4"; }
					else {$feequery1.=", -1"; }
				$feequery1.=")";
				fd_query($feequery1);
			}

			if(!empty($fee5) || !empty($fee6) || !empty($fee7) || !empty($fee8) ){
					$feequery1 = "insert into fees (festID, dateType, Feature, Short, Student, Other) values ($id, 'Normal'";
					if ($fee5 != '') { $feequery1.=", $fee5"; }
					else {$feequery1.=", -1"; }
					if ($fee6 != '') { $feequery1.=", $fee6"; }
					else {$feequery1.=", -1"; }
					if ($fee7 != '') { $feequery1.=", $fee7"; }
					else {$feequery1.=", -1"; }
					if ($fee8 != '') { $feequery1.=", $fee8"; }
					else {$feequery1.=", -1"; }
				$feequery1.=")";
				fd_query($feequery1);
			}

			if(!empty($fee9) || !empty($fee10) || !empty($fee11) || !empty($fee12) ){
					$feequery1 = "insert into fees (festID, dateType, Feature, Short, Student, Other) values ($id, 'Late'";
					if ($fee9 != '') { $feequery1.=", $fee9"; }
					else {$feequery1.=", -1"; }
					if ($fee10 != '') { $feequery1.=", $fee10"; }
					else {$feequery1.=", -1"; }
					if ($fee11 != '') { $feequery1.=", $fee11"; }
					else {$feequery1.=", -1"; }
					if ($fee12 != '') { $feequery1.=", $fee12"; }
					else {$feequery1.=", -1"; }
				$feequery1.=")";
				fd_query($feequery1);
			}
		} #end if($free==1)
		elseif($free==2){
			if($result["eDead"] != '0000-00-00'){
			fd_query("insert into fees (festID, dateType, Feature, Short, Student, Other) values ($id, 'Early', 0, 0, 0, 0)");
			}
			if($result["nDead"] != '0000-00-00') {
			fd_query("insert into fees (festID, dateType, Feature, Short, Student, Other) values ($id, 'Normal', 0, 0, 0, 0)");
			}
			if($result["lDead"] != '0000-00-00'){
			fd_query("insert into fees (festID, dateType, Feature, Short, Student, Other) values ($id, 'Late', 0, 0, 0, 0)");
			}
		}

}
		fd_query("update fests set distinguish='$distinguish', party='$party', press='$press' where ID=$id");
	mysql_close();
	$email = $result["email"];


	$body1 = "Thank you for updating your Festival Information on FilmDevil.com. This brings us another step closer to providing filmmakers with the most comprehensive database of film festivals on the Web, and making your festival more accessible to the filmmaking community.\n\nYou can always update your festival information by going to www.filmdevil.com and clicking on Log In.  Remember, your festival is named \"". $result["title"]. "\" in our database. If you have any questions, comments, or suggestions about how we can better suit your needs as a festival please reply to this email (support@filmdevil.com).  To view the information you've just submitted go to http://www.filmdevil.com/info2.php?ID=".$id."&login=1 .\n\n\nSincerely,\nThe FilmDevil.com staff";

   // *** Thank you email disabled to help jeff
   //mail("jeff@filmdevil.com", "Thank you for updating your Festival Information", $body1, "From: support@filmdevil.com\n" . "Reply-To: support@filmdevil.com\n" . "X-Mailer: PHP/" . phpversion());

$title = $result["title"] . " updated";
$body2 = "The fest '$title' has updated their shit. Check out http://www.filmdevil.com/info.php?ID=".$id."&login=1";
if(!empty($result["undoSQL"]))
	$body2 .= "\n\nYou can undo their changes with the following SQL statement:\n\n"
		. $result["undoSQL"] . "\n\n then verify the edit or use the verify edits tool";
fd_mail("jeff@filmdevil.com", $title, $body2);

?>
<html>
<head>
<title>Festival Update - Page 6 of 6</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
</head>

<body bgcolor="#000000" text="#FFFFFF" class="description">
<span align="center"><img src="images/login6.gif"></span>
	  <form name="form3" method="post" action="login7.php" class="description">
  <table border="0" cellspacing="0" cellpadding="10" class="description" width="100%">
    <tr>
      <td> </td>
    </tr>
    <tr>
      <td>

<input type="hidden" name="id" value="<? print $id; ?>">
	    <p>Thank you for updating your Festival Information.<br>
          If you have any complaints, comments, or suggestions<br>
          regarding the updating process or FilmDevil.com in general,<br>
          please express them below. We value your input greatly.<br>
			<p>
          <textarea name="body" rows="6" cols="40" wrap="VIRTUAL" class="select"></textarea>
          <br>
          <input type="submit" name="Submit" value="Send Comments" class="select">
        </p>
        <p><a href="info.php?ID=<? print $result["ID"]; ?>&preview">View Your Festival Profile</a> |
					<a href="<?= getWelcomePage() ?>">Go to welcome page</a></p>
      </td>
    </tr>
  </table>
  </form>
</body>
</html>
<?
		if(UPDATE_SEARCH_ON_FEST_UPDATE)
			file_get_contents(URL_ROOT . "createLocationBox.php");

		generateHeuristic($result["ID"]);
		if(UPDATE_SIMILAR_FESTS_ON_FEST_UPDATE)
	  	file_get_contents(URL_ROOT . "generateSimilarFestivals.php");
	}
?>