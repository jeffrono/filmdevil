<?
require_once("dbFunctions.php");

if(!isLoggedIn()) { ?>
<html>
<head>
  <title>My Fest List</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>
<body>
<? include("header.php"); ?>
<p class="title" align="center">My FestList</p>
<p>Never miss a deadline again!! Your FestList contains the fests that you
	want to keep track of. Every time
	you log in, these fests will be available to you. You can even be reminded
	by email when these deadlines are approaching. Please
	<a href="loginUserPage.php">log in</a> and then return to this page.
</p>
<? include "footer.php"; ?>
</body>
</html>
<?die();
}

fd_connect();
fd_import_request_variables("gp", "form_");
fd_filter_batch(array("form_add", "form_remove"), true, true, false);

if(isset($form_add)) {
	$dupQuery = fd_query("select * from userFest where userID = "
    . $_SESSION["user_id"] . " and festID = $form_add and relation = 'festList'");
  if(mysql_num_rows($dupQuery) == 0) {
		fd_query("insert into userFest (userID, festID, relation) values ("
    	. $_SESSION["user_id"] . ", $form_add, 'festList')");
	} else
		$msg = "You already have that fest in your FestList.";
}
if(isset($form_remove)) {
  fd_query("delete from userFest where festID = $form_remove and userID = "
    . $_SESSION["user_id"] . " and relation = 'festList'");
	if(mysql_affected_rows() == 0)
		$msg = "You don't have that fest in your FestList.";
}
if(isset($form_changeOptions)) {
	if(isset($form_remindByEmail)) {
	  fd_filter_batch("form_remindFreq", "form_remindPeriod");
	  fd_query("update user set festListRemindFreq = '$form_remindFreq',
	    festListRemindPeriod = '$form_remindPeriod' where id = "
	    . $_SESSION["user_id"]);
	} else {
		fd_query("update user set festListRemindFreq = '',
	    festListRemindPeriod = '' where id = "
	    . $_SESSION["user_id"]);
	}
	$msg = "Preferences Updated";
}

$resultFest = fd_query("select fests.* from userFest inner join fests on fests.id = "
	. "userFest.festID where userID = "
  . $_SESSION["user_id"] . " and relation = 'festList'");

$resultUser = mysql_fetch_assoc(fd_query("select festListRemindFreq, festListRemindPeriod
		from user where id = " . $_SESSION["user_id"]));
$wantsReminder = $resultUser["festListRemindFreq"] != ""
	&& $resultUser["festListRemindPeriod"] != "";
?>

<html>

<head>
  <title>My Fest List</title>
	<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<script src="common.js"></script>
<script language="javascript">
  function preloadSelects() {
    setOption(document.prefs.remindFreq,
      '<?= $resultUser["festListRemindFreq"] ?>');
    setOption(document.prefs.remindPeriod,
      '<?= $resultUser["festListRemindPeriod"] ?>');
  }
</script>

<body onLoad="preloadSelects();">
<? include("header.php"); ?>

<p class="title" align="center">My FestList</p>
<? if(!empty($msg)) { ?>
<p class="error"><?= $msg ?>
</p>
<? } ?>
<p>Your FestList contains the fests that you want to keep track of. Every time
	you log in, these fests will be advailable to you. You can even be reminded
	by email when these deadlines are approaching.
</p>

<p>You can
	<a href="listing.php?festList=yes" target="listingFrame">search on these fests</a>
	by clicking <i>"On my FestList"</i> under the quick searches tab on the search frame.
</p>

<form name="prefs" action="festList.php" method="post">
<input type="hidden" name="changeOptions" value="">
	<table class="box">
		<tr>
			<th colspan="2" alt="center">Preferences</th>
		</tr>
	  <tr>
	    <td width="10"><input type="checkbox" name="remindByEmail" value="1"
				class="radio" onClick="showHide('reminderDiv');"
				<? if($wantsReminder) echo "checked"; ?> ></td>
			<td>Remind me by email when these festival deadlines are approaching</td>
		</tr>
		<tr id="reminderDiv" <? if(!$wantsReminder) echo "style='display = none;'"; ?>>
			<td>&nbsp;</td>
			<td>Send reminders every
				<select name="remindFreq">
					<option value="month">month
					<option value="week">week
				</select> starting
				<select name="remindPeriod">
					<option value="months_6">six months
					<option value="months_3">three months
					<option value="months_1">one month
				</select> before the deadline</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input class="button" type="submit" value="Update"
					onmouseover="this.className = 'buttonSelect';"
					onMouseOut="this.className = 'button';">
			</td>
		</tr>
</form>

<? if(empty($resultFest)) { ?>
<p>Your FestList is empty. To add a fest to the list, click "Add to My FestList"
	when viewing the profile of that festival.</p>
<? } else { ?>
<table style="margin-top: 10px">
	<tr>
		<th></th>
		<th>Festival</th>
		<th>Location</th>
		<th>Next Deadline</th>
		<th>Dates</th>
	</tr>
<? 	while($row = mysql_fetch_assoc($resultFest)) { ?>
	<tr>
		<td><a href="festList.php?remove=<?= $row["ID"] ?>">X</a></td>
		<td><a href="info.php?ID=<?= $row["ID"] ?><? if(noFrames()) echo "&noFrames"; ?>"><?= $row["title"] ?></a></td>
		<td><?= getDisplayLocation($row) ?></td>
		<td><span class=
<?	  if(!empty($row["eDead"]) && strtotime($row["eDead"]) > time())
	      echo "'deadlineSoon'>Early: " . date("F j, Y", strtotime($row["eDead"]));
	    elseif(!empty($row["nDead"]) && strtotime($row["nDead"]) > time())
	      echo "'deadlineSoon'>Normal: " . date("F j, Y", strtotime($row["nDead"]));
	    elseif(!empty($row["lDead"]) && strtotime($row["lDead"]) > time())
	      echo "'deadlineSoon'>Late: " . date("F j, Y", strtotime($row["lDead"]));
	    else
	      echo "'deadlinePassed'>Deadline Passed";
?>
			</span>
		<td><?= date("F j, Y", strtotime($row["startDate"])) ?> -
			<?= date("F j, Y", strtotime($row["endDate"])) ?></td>
	</tr>
<? 	}
	}
?>
</table

<? include "footer.php"; ?>

</body>
</html>