<?	require "dbFunctions.php";
    import_request_variables("p", "form_");

	fd_connect();
	$info = mysql_fetch_array(fd_query("SELECT email FROM fests WHERE ID = "
    	. $form_ID));
    fd_close();

	mail($info["email"], $form_subject, $form_body . EMAIL_FOOTER,
     "From: \"".$form_name."\" <".$form_email.">\n"
    ."X-Mailer: PHP/" . phpversion());

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