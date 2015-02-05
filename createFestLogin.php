<?
	require "dbFunctions.php";
	if(!isLoggedIn() || $_SESSION["user_primaryType"] != "devil")
		redirectToLogin();

	function generatePassword() {
		$numCount = 2;
		$charCount = 4;
		$chars = range("a", "z");
		$nums = range(0, 9);

		$password = "";
		for($i = 0; $i < $numCount; $i++)
			$password .= $nums[rand(0, count($nums) - 1)];
		for($i = 0; $i < $charCount; $i++)
			$password .= $chars[rand(0, count($chars) - 1)];

		return str_shuffle($password);
	}

	fd_import_request_variables("gp", "form_");

	fd_connect();

	$noUserResult = fd_query("select fests.ID, fests.email from fests left join user on fests.email = user.email
		where user.id is null");
	$autoResult = fd_query("select id from user where
		source = 'auto' and numLogins = 0");

	$countAgain = false;
	if(isset($form_createAll)) {
	  echo "Creating users...";
	  $numCreated = 0;
	  while($row = mysql_fetch_assoc($noUserResult)) {
			if(!empty($row["email"])) {
				$password = generatePassword();
	      fd_query("insert into user (email, password, primaryType, created, source, status) values
	        ('" . $row["email"] . "', '$password', 'administrator', now(), 'auto', 'ok')");
	      $userID = mysql_insert_id();
	      fd_query("insert into userFest (userID, relation, festID) values
	        ($userID, 'admin', " . $row["ID"] . ")");
	      $numCreated++;
			}
	  }
		echo "Done. Created $numCreated users.";
		$countAgain = true;
	} elseif(isset($form_deleteAll)) {
		echo "Deleting users...";
	  $numDeleted = 0;
	  while($row = mysql_fetch_assoc($autoResult)) {
	  	fd_query("delete from user where id = " . $row["id"]);
	    $numDeleted++;
	  }
		echo "Done. Deleted $numDeleted users.";
		$countAgain = true;
	}

	// To get accurate numbers after additions/deletions i repeat the counts
	if($countAgain) {
	  $noUserResult = fd_query("select fests.ID, fests.email from fests left join user on fests.email = user.email
	    where user.id is null");
	  $autoResult = fd_query("select id from user where
	    source = 'auto' and numLogins = 0");
	} ?>

<p>There are <?= mysql_num_rows($noUserResult) ?> fests without users.
<br>There are <?= mysql_num_rows($autoResult) ?> automatically created users that have never logged in
<p><a href='createFestLogin.php?createAll'>Create users for all these fests</a>
<br><a href='createFestLogin.php?deleteAll'>Delete all auto users that have never logged in</a>
<br><a href='createFestLogin.php'>Refresh</A>

<p>Sample password: <?= generatePassword() ?>