<?
$cacheLimiter = "noCache";
require_once "dbFunctions.php";

logout();

?>

<html>

<head>
  <title>Logout</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<? include "header.php"; ?>
You have been logged out of FilmDevil.

<p>If you are concerned about someone else who uses this computer seeing what you
have written or read on this site, we advise closing this browser window. Thanks again
for using FilmDevil.


<? include("footer.php"); ?>
</body>

</html>