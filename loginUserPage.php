<?
$cacheLimiter = "noCache";
require_once("dbFunctions.php");

?>

<html>
<head>
<title>Login to Filmdevil</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/info.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include("header.php"); ?>

<?php

if(isset($_GET["page2"])) {
	include("loginUser2.php");
} else {
	include("loginUser.php");
}
?>

<? require("footer.php"); ?>

</body>

</html>