<?
	require "dbFunctions.php";
	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();

	fd_connect();
	if(isset($_REQUEST["flush"]))
		fd_query("update data set data = '' where id = 2");

	$log = mysql_fetch_assoc(fd_query("select data from data where id = 2"));
?>
<html>

<head>
  <title>Email Log</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<p class="title">Email Log</p>
<p>Currently logging emails? <? if(LOG_EMAIL) echo "yes"; else echo "no"; ?>
<br>Currently sending emails? <? if(SEND_EMAIL) echo "yes"; else echo "no"; ?>
<p><a href="emailLog.php?flush">Flush</a>
<pre>
<?= $log["data"] ?>
</pre>
</body>

</html>