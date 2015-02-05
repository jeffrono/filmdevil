<?
$cacheLimiter = "public";
require_once "dbFunctions.php";
fd_connect();

while(list($key, $val) = each($HTTP_POST_VARS)) {
	$val = fd_filter($val);
    $val = nl2br($val);

    if ($key == 'id') {$id = fd_filter($val, true); }
    elseif ($key == 'body') {$body = $val; }
    else { $$key=$val; }
}

	$result = mysql_fetch_array(fd_query ("select * from fests where ID=$id "));
	fd_close();
	$body.="\n".$result["title"]."\n".$result["oTel"];
if ($result != '') {

	mail("jeff@filmdevil.com", "comments from ".$result["title"], $body,
     "From: ".$result["email"]."\n"
	 ."Reply-To: ".$result["email"]."\n"
    ."X-Mailer: PHP/" . phpversion());
?>
<html>
<head>
<title>Thank You</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
</head>

<body bgcolor="#000000" text="#FFFFFF" class="description">
<form name="form3" method="post" action="login6.php" class="description">
  <table border="0" cellspacing="0" cellpadding="10" class="description" width="100%" align="center">
    <tr>
      <td> </td>
    </tr>
    <tr>
      <td>
	  Thank you for your comments.<br><br>
			<a href="info.php?ID=<? print $result["ID"]; ?>&preview">View Your Festival Profile</a> |
					<a href="<?= getWelcomePage() ?>">Go to welcome page</a></p>
      </td>
    </tr>
  </table>
  </form>
  <?
  $email = $result["email"];
 	mail($email, "Thank you for your comments", "Thank you very much for submitting your comments to FilmDevil.com.  Your feedback will help us make our website the most comprehensive film festival resource on the Internet.  As always, we urge you to spread the word about the FilmDevil.\n\n\nSincerely,\n\nThe FilmDevil.com staff",
     "From: support@filmdevil.com\n"
	 ."Reply-To: support@filmdevil.com\n"
    ."X-Mailer: PHP/" . phpversion());

	?>
</body>
</html>
<? } ?>