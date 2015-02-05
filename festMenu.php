<?function printFestMenu($currentPage = "") {
		if(isset($GLOBALS["onlyFest"])) {
			$onlyFest = $GLOBALS["onlyFest"];
			$numFests = $GLOBALS["numFests"];
		} else {
			fd_connect();
	    $result = fd_query("select fests.* from userFest "
	      . "inner join fests on userFest.festID = fests.ID where userID = "
	      . $_SESSION["user_id"] . " and relation = 'admin'");
	    if(mysql_num_rows($result) == 1) {
	      $onlyFest = mysql_fetch_assoc($result);
	    }
			$numFests = mysql_num_rows($result);
		}
?>

<div class="linkSet">
<? 	if($currentPage != "home") { ?>
	<a href="welcomeFest.php">Welcome</a> |
<?	}
		if(isset($onlyFest)) { ?>
  <a href='prelogin.php?operation=update&festID=<?= $onlyFest["ID"] ?>'>
    Update Festival Profile</a> |
  <a href='info.php?preview&ID=<?= $onlyFest["ID"] ?>'>
    See Festival Profile</a> |
<?	} ?>
<?	if($numFests == 0) { ?>
  <a href="prelogin.php?operation=add">
    Add Festival</a> |
<?	} ?>
  <a href="promoteFest.php">
    Purchase Advertising</a> |
  <a href="showPromotions.php">
    Show Current Advertising</a>
</div>

<?} ?>