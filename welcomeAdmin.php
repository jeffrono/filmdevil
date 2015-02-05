<?require_once "dbFunctions.php";

	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();
?>

<html>

<head>
  <title>Welcome Admins</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body>
<span class="title">Welcome Admins</span>
<a href="editUser.php?id=<?= $_SESSION["user_id"] ?>">(Update your user profile)</a>

<p><a href="verifyPurchase.php">Approve/Disapprove Purchases</a>
<p><a href="showError.php">See error log</a>
<p><a href="emailLog.php">See email log</a>
<p><a href="createFestLogin.php">Create Fest Logins</a>
<p><a href="verifyFestEdit.php">Verify fest edits</a>
<p><a href="adminSimilarFests.php">Similar Festivals / Generate Heuristics</a>
<p><a href="createLocationBox.php">Reload location box</a>
<p><a href="generateSimilarFestivals.php">Generate similar festivals list</a>
<p><a href="deleteFest.php">Delete Fests</a>

<p><b>Scheduled Tasks:</b>
<ul>
	<li>Goto http://www.filmdevil.com/generateSimilarFestivals.php once a day
		(in the early morning like 12:01 am) to regenerate the similar festivals
		list.
		The optional parameter 'generateHeuristic' will regenerate all the heuristics
		before the similar festivals list is made.
</ul>

<? include "footer.php"; ?>
</body>

</html>