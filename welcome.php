<? require_once "dbFunctions.php"; ?>

<?if(!isset($subPage)) { ?>
<html>
<head>
	<title>Welcome to FilmDevil</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>
<?} ?>
<body>
<? include "header.php"; ?>
<blockquote class="description">
  <p class="popupheading">Welcome to FilmDevil.com, the most comprehensive film festival
    resource on the net!</p>
  <p>For the independent filmmaker, finding festivals to submit your film to can
    be a daunting task. Filmdevil.com recognizes the filmmaking community's need
    for a one-stop tool to find key information on any festival in the world;
    to give filmmakers the ability to find festivals that suit their specific
    needs, and put all of the relevant information at their fingertips; to offer
    an equal opportunity playing field for film festivals to post as much or as
    little information about their fest as they want; to provide festivalgoers,
    enthusiasts and filmmakers alike, a forum to post reviews about any film Festival.</p>
  <p>Filmdevil.com is bridging the gap between filmmakers and film festivals,
    making it easier than ever before to find the most appropriate festival, using
    the most comprehensive film Festival database resource in the world. We have
    over 1500 completely searchable fest listings, with all relevant information
    updated by the festivals themselves. We also feature reviews of the fests
    written by filmmakers and film buffs alike. If you are a filmmaker looking
    to submit your film to festivals, then you can trust FilmDevil.com to help
    you choose the right ones for you. </p>
<?if(isset($subPage)) {
		$linkName = "listAllFests.php";
		if(FESTS_URL_REWRITE)
			$linkName = "fests/" . $linkName;
		else
			$linkName = "festRedirect.php?url=" . $linkName; ?>
	<p>Please see <a href="listAllFests.php?noFrames">our list of festivals</a>
<?} ?>
</blockquote>
<? include("footer.php"); ?>
</body>
<?if(!isset($subPage)) { ?>
</html>
<?} ?>