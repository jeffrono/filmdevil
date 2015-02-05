<?require_once "dbFunctions.php";
	fd_import_request_variables("gpc", "form_");
	fd_filter_batch(array("form_goto"), false, true, true);
?>
<html>
<head>
	<title>FilmDevil - The most comprehensive film database ever</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="keywords" content="FilmDevil, filmdevil, film, devil, filmfestivals, film festivals, movies, zakoncrack, jeff, novich, zak, volnyansky">
</head>

<?if(!noFrames()) { ?>
<script language="javascript">
function maximizeMidFrame() {
	document.all("topFrameset").rows = "0, *";
	document.frames("mainFrame").maximize();
}

function restoreMidFrame() {
	document.all("topFrameset").rows = "50, *";
	document.frames("mainFrame").restore();
}

</script>

</head>
<frameset id="topFrameset" rows="50,*" frameborder="NO" border="0" framespacing="0" cols="*">
	<frame name="topFrame" noresize src="top.php" scrolling="NO">
  <frame name="mainFrame" src="<?
	if($form_goto == "searchFrameset")
		echo "searchFrameset.php" . makeRequestString();
	elseif($form_goto == "login")
		echo "loginUserPage.php";
	else
		echo "front.php"; ?>" frameborder="NO">
</frameset>

<noframes>
<? }
	$subPage = true;
	require "front.php";

	if(!noFrames()) {?>
</noframes>
<?} ?>
</html>