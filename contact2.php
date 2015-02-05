<? 

while(list($key, $val) = each($HTTP_POST_VARS)){$$key=$val;}

	mail("jeff@filmdevil.com", $subject, $body,
     "From: \"".$name."\" <".$email.">\n"
    ."X-Mailer: PHP/" . phpversion());

 	mail($email, "Thank you for your comments", "Thank you very much for submitting your comments to FilmDevil.com.  Your feedback will help us make our website the most comprehensive film festival resource on the Internet.  As always, we urge you to spread the word about the FilmDevil.\n\n\nSincerely,\n\nThe FilmDevil.com staff",
     "From: \"FilmDevil.com support\" <support@filmdevil.com>\n"
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
      
    <td> Thank you for your comments, we will respond to them shortly.<br>
   
        <p> 
          <input type="button" name="Submit22" value="Close Window" class="select" onclick="window.close()">
        </p>
      </td>
    </tr>
  </table>


</body>
</html>
