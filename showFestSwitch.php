<?
require_once("dbFunctions.php");

function printEnd() {
	include "footer.php" ?>
</body>
</html>
<?
}

// Either return the festID or die
function showFestSwitch($page) {
  fd_import_request_variables("gp", "form_");
  if(isset($GLOBALS["form_festID"])) {
		$form_festID = $GLOBALS["form_festID"];
    fd_filter_batch(array("form_festID"), true);
    if(festEditAuthorized($_SESSION["user_id"], $form_festID)) {
      return $form_festID;
    } else {
      fd_trigger_error("Access Error");
      die("You are not authorized to purchase promotion options for this festival.");
    }
  } else {
    $festivalResult = fd_query("select festID, title from userFest inner join
      fests on userFest.festID = fests.ID where userFest.userID = "
      . $_SESSION["user_id"] . " and relation = 'admin'");
		if(mysql_num_rows($festivalResult) == 0) { ?>
	<p>Although you are a festival administrator, we do not have you registered as
		being in charge of any festivals. If you have not done so, please add your festival.
		If there is an error please email us by clicking below.
<?		printEnd();
			die();
    } elseif(mysql_num_rows($festivalResult) == 1) {
      $festRow = mysql_fetch_assoc($festivalResult);
      return $festRow["festID"];
    } else {
?>
	<p>Since you administer more than one festival, please select the festival
		that you would like to promote from the list below and hit "submit"</p>
	<form action="<?= $page ?>" method="post">
		<select name="festID">
<?		while($row = mysql_fetch_assoc($festivalResult)) { ?>
			<option value="<?= $row["festID"] ?>"><?= $row["title"] ?>
<?		} ?>
		</select>
		<input type="submit" class="button" value="Submit">
	</form>
<?		printEnd();
			die();
		}
	}
}
?>