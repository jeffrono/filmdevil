<? require_once "dbFunctions.php"; ?>



<script language="JavaScript">

<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}



var currentDate = <?= time() ?>;

<?

	$functionPrefix = "";

	$functionSuffix = "";

	if(!noFrames()) {

	  $functionPrefix = "if(window.top.frames.topFrame) window.top.frames.topFrame.";

		$functionSuffix = "";

	}

	if(isLoggedIn()) {

	  echo $functionPrefix . "showLogin(currentDate, '"

	    . $_SESSION["user_displayName"] . "', '" .  $_SESSION["user_primaryType"]

			. "')" . $functionSuffix . ";";

	} else

	  echo $functionPrefix . "hideLogin(currentDate)" . $functionSuffix . ";";



?>

//-->

</script>

<html>

<body style="background-color:#660000;">

<div class="footer" style="color:#FFFFFF;">

	<a href="#" style="color:#FFFFFF;" onClick="MM_openBrWindow('contact1.php', 'ContactUs', 'resizable=yes,width=500,height=300');">

		Questions, problems, errors, suggestions?</a>

	<br>

	Copyright 2001-2003 by FilmDevil.

	<br>

	<a href="termsOfUse.php" style="color:#FFFFFF;">Terms of use</a>

</div>

</body>

</html>

<?if(noFrames()) {

		ob_end_flush();

	}

?>