<?	require "dbFunctions.php";

	fd_import_request_variables("gp", "form_");
	fd_filter_batch(array("form_id"), true);

	fd_connect();
  $reviewRow = mysql_fetch_array(fd_query("select * from reviews where id = $form_id"));
  fd_close();

	$bodyPrefix = "This email was sent to you via FilmDevil, when a user clicked
		'Contact this User' at a review that you wrote: \n\n";
	fd_mail($reviewRow["email"], $form_subject, $bodyPrefix . $form_body,
     $form_name, $form_email);

?>

<html>
<head>
<title>Thank You</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
</head>

<body bgcolor="#000000" text="#FFFFFF" class="description">

  <table border="0" cellspacing="0" cellpadding="10" class="description" width="100%">
    <tr>
      <td> </td>
    </tr>
    <tr>

    <td> Your email has been sent. Thank you for using Filmdevil.<br>

        <p>
          <input type="button" name="Submit22" value="Close Window" class="select" onclick="window.close()">
        </p>
      </td>
    </tr>
  </table>


</body>
</html>