<? 	require "dbFunctions.php";
	fd_connect();

	fd_import_request_variables("gp", "form_");
	fd_filter_batch(array("form_id"), true);
  $reviewRow = mysql_fetch_array(fd_query("select * from reviews where id = $form_id"));
  fd_close();
?>

<html>
<head>
<title>Contact User - <?= getDisplayName($reviewRow) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
</head>

<body bgcolor="#000000" text="#FFFFFF" class="description">

<form name="form3" method="post" action="contactUser2.php" class="description">
	<input type="hidden" name="id" value="<?= $form_id ?>">

  <table cols="2" border="0" cellspacing="0" cellpadding="5" class="description" width="100%">
    <tr>
      <td colspan=2 align="center">Contact the user: <b><?= $reviewRow["name"] ?></b></td>
    </tr>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="name" class="select" size="30"></td>
    </tr>
	<tr>
      <td>Email:</td>
      <td><input type="text" name="email" class="select" size="30"></td>
    </tr>
	<tr>
      <td>Subject:</td>
      <td><input type="subject" name="subject" class="select" size="30"></td>
    </tr>
	<tr>
      <td>Body:</td>
      <td><textarea name="body" rows="6" cols="40" wrap="VIRTUAL" class="select"></textarea></td>
    </tr>
	<tr>
      <td colspan=2 align="center">
    	<input type="submit" name="Submit" value="Submit" class="select">
      </td>
    </tr>
  </table>
  </form>
</body>
</html>