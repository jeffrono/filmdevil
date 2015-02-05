<?php
	require_once "dbFunctions.php";

	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "administrator")
		redirectToLogin();

  fd_connect();
	$result = fd_query("select fests.* from userFest "
  	. "inner join fests on userFest.festID = fests.ID where userID = "
    . $_SESSION["user_id"] . " and relation = 'admin'");
	if(mysql_num_rows($result) == 1) {
		$onlyFest = mysql_fetch_assoc($result);
	}
	$numFests = mysql_num_rows($result);
?>
<html>

<head>
  <title>Welcome, <?= $_SESSION["user_displayName"] ?></title>
  <link href="styles/central.css" rel="stylesheet" type="text/css">
</head>

<body>
<? include("header.php"); ?>

<?include "festMenu.php";
	printFestMenu("home"); ?>

<span class="title">Welcome, <?= $_SESSION["user_displayName"] ?></span>
<a href="editUser.php?id=<?= $_SESSION["user_id"] ?>">(Update your user profile)</a>

<? if($_SESSION["user_numLogins"] < 5) { ?>
	<p>It looks like you've just started logging into FilmDevil. Thanks for showing interest in our site.
	  Don't hesitate to check out our <a href="help.php">help documentation</a>.</p>
<? } ?>

<div class="section">
	<div class="sectionHeading">Congratulations!</div>
	<div class="sectionBody">
<?if(mysql_num_rows($result) > 0) {
	  if(isset($onlyFest))
	    echo $onlyFest["title"] . " is";
	  else
	    echo "Your festivals are";
	} else {
		echo "You have the chance to have your festival";
	} ?>
	  officially listed on FilmDevil, the most comprehensive film festival database
	  on the Internet.  You are only a few steps away from gaining
	  maximum exposure for your festival.
	</div>
	<div class="sectionBody">
		FilmDevil connects filmmakers and festivals from around the world.
		Our unique search engine lets filmmakers find festivals that meet
		their specific criteria.  But with over 1600 festivals
		listed on FilmDevil, you don't want to get lost in the crowd.
	</div>
</div>

<div class="section">
	<div class="sectionHeading">Update your information</div>
	<div class="sectionBody">As a festival administrator, you can
<?if(mysql_num_rows($result) > 0) {
		if(isset($onlyFest))
			echo "<a href='prelogin.php?operation=update&festID=" . $onlyFest["ID"] . "'>
				update and add</a> information to your festival profile.
				Take a moment to <a href='info.php?noframes&ID=" . $onlyFest["ID"] . "'>
				see how your page looks</a>";
		else
			echo "update and add information to your festival profiles.
				Take a moment to see how your page looks";
	} else {
		echo "<a href='prelogin.php?operation=add'>create a profile</a> for your festival.";
	} ?>.
		You'll want your profile to be as complete, accurate, and compelling
		as possible, so it stands out when filmmakers are searching for festivals.
		The more information you provide, the more easily filmmakers can
		find your festival and discover the unique opportunities it offers.
		Also, registered users of FilmDevil receive e-mails about festivals
		that have updated their profile, submission deadlines, and
		a list of "festivals of interest."
	</div>
<?if(mysql_num_rows($result) > 1) { ?>
	<div class="sectionBody">To update one of your festivals, or preview how it will
		appear on our site, click the appropriate link after the festival name:
		<ul>
<?	while($row = mysql_fetch_assoc($result)) { ?>
			<li><? print($row["title"]); ?>
			<a href="info.php?noframes&ID=<? print($row["ID"]); ?>">(View)</a>
	  	<a href="prelogin.php?operation=update&festID=<? print($row["ID"]); ?>">(Update)</a>
<?	}  ?>
		</ul>
	</div>
<?} ?>
	<div class="sectionBody">We're always, of course, interested in hearing about new festivals.
		Help to keep our users current by <a href="prelogin.php?operation=add">adding festivals</a>
		to our growing database.
	</div>
</div>

<div class="section">
	<div class="sectionHeading">Advertise with FilmDevil</div>
	<div class="sectionBody">Advertise with FilmDevil to greatly increase your
	festival's visibility.  We provide the
	<a href="showPromotions.php">most effective online advertising</a>,
	and cost much less than placing a print ad.  Also, FilmDevil users are
	filmmakers who were eager to submit films, so rest assured that your
	advertising will hit your target audience more effectively than anywhere else.
	See how FilmDevil can <a href="showPromotions.php">help increase submissions</a>
	to your festivals with
	our unbeatable line of promotional packages.
	</div>
</div>

<div class="section">
	<div class="sectionHeading">Features that FilmDevil Provides for free:</div>
	<div class="sectionBody">We want you to be able to monitor how well FilmDevil
		is helping your festival.  To do this, we keep track of how
		our web site is used.  Each month, you will receive an e-mail
		that will include the following statistics:
		<ul>
			<li>how many times your FilmDevil profile was viewed, and how you ranked up
			<li>how many people went from your FilmDevil profile to your festival web site
	    <li>how many e-mails were sent to you through FilmDevil
	    <li>how similar festivals are doing
	    <li>how many registered users have added your festival to their favorites
	    <li>how many reviews were written about your festival and your average rating
	    <li>how many people clicked to be "google this fest" link on your profile
	    <li>how many people viewed your festival's submission application
		</ul>
	</div>
</div>

<? include("footer.php"); ?>
</body>

</html>