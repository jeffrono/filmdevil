<?require_once("dbFunctions.php"); ?>



<html><head><title>filmdevil</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<meta name="keywords" content="FilmDevil, filmdevil, film, devil, filmfestivals, film festivals, movies, zakoncrack, jeff, novich, zak, volnyansky">

</head>



<script language="javascript" src="common.js"></script>

<script language="javascript">

<?$searchInRows = SEARCH_ROW;

	if(ON_APACHE)

		$headers = apache_request_headers();

	else

		$headers = $_SERVER;

	if(isset($headers["UA-pixels"])) {

		$matches = array();

		preg_match("/([0-9]*)x/", $headers["UA-pixels"], $matches);

		if(isset($matches[0]) && $matches[0] <= 800)

			$searchInRows = true;

	} else {

		if(preg_match("/mac/i", $_SERVER['HTTP_USER_AGENT']))

			$searchInRows = false;

	}

?>



var searchInRows = <?

	if($searchInRows) echo "true";

	else echo "false"; ?>;



function maximize() {

	document.all("searchFrameset").cols = "0, *";

	if(searchInRows)

		document.all("infoFrameset").rows = "0, *";

	else

		document.all("infoFrameset").cols = "0, *";

}



function restore() {

	document.all("searchFrameset").cols = "170, *";

	if(searchInRows)

		document.all("infoFrameset").rows = "140, *";

	else

		document.all("infoFrameset").cols = "140, *";

}



function switchView() {

	if(searchInRows) {

		document.all("infoFrameset").rows = "";

		document.all("infoFrameset").cols = "140, *";

		document.frames("listingFrame").switchExtraCols("none");

	} else {

		document.all("infoFrameset").cols = "";

		document.all("infoFrameset").rows = "140, *";

		document.frames("listingFrame").switchExtraCols("inline");

	}

	searchInRows = !searchInRows;

}

</script>



<frameset name="searchFrameset" cols="170,*" frameborder="NO" border="0" framespacing="0">

	<frame name="searchFrame" scrolling="AUTO" noresize src="search.php" >

  <frameset id="infoFrameset"

<?if($searchInRows)

		echo "rows='140,*'";

	else

		echo "cols='140, *'"; ?>

		frameborder="NO" border="0" framespacing="0">

    <frame name="listingFrame" scrolling="YES" src="listing.php<?= makeRequestString() ?>" frameborder="NO">

    <frame name="infoFrame" src="search_footer.php" frameborder="NO">

  </frameset>

</framest>



<noframes>

<body bgcolor="#FFFFFF" text="#000000">Sorry, you need frames in order to see this page</body></noframes>

</html>