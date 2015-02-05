<?
	require_once "dbFunctions.php";
	function getLinkProps($href) {
		$linkProps = "href='$href";
		if(noFrames())
			return $linkProps . "?noFrames'"; // style='color: #FFFFFF;'";
		else
			return $linkProps . "' target='mainFrame'";
	}

?>

<?if(!noFrames()) { ?>
<html>
<head>
  <title>FilmDevil Menu</title>
	<link rel="stylesheet" href="styles/outside.css" type="text/css">
<?} ?>
	<style>
<? //if(!noFrames()) { ?>
		/* Redefining link styles */
	  .menuTable a {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #CCCCCC; text-decoration: none }
	  .menuTable a:link {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #CCCCCC; text-decoration: none }
	  .menuTable a:visited {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #CCCCCC; text-decoration: none }
	  .menuTable a:hover {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #FFFFFF; text-decoration: none }
	  .menuTable a:active {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #FFFFFF; text-decoration: underline }
<? //} ?>
		#message {
			display: none;
			border: 1px #CC0000 solid;
			background-color: #000000;
			color: #FFFFFF;
			font-size: 12px;
			padding: 3px;
			position: absolute;
			top: 2px;
			left: 250px;
			z-index: 1;
		}

		.userName {
			font-size: 12px;
		}

/*
		.menuTable {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
		}
*/
	</style>

<?if(!noFrames()) { ?>
</head>

<body onLoad="checkLogin();">
<?} ?>

<script src="mousetrack.js"></script>
<script src="common.js"></script>
<script language="javascript">
	var userName = false;

	function showLogin(currentDate, name, primaryType) {
		if(!withinDateRange(currentDate)) return false;
		if(userName != name) {
			userName = name;
			var link = document.all("loginLink")
			link.innerHTML = "Welcome <span class='userName'>" + userName + "</span>";
			if(primaryType == "administrator")
				link.href = "welcomeFest.php";
			else if(primaryType == "devil")
				link.href = "welcomeAdmin.php";
			else
				link.href = "welcomeUser.php";

			document.all("logoutCell").style.display = "inline";
			return true;
		}
		return false;
	}

	function hideLogin(currentDate) {
		if(!withinDateRange(currentDate)) return false;
		if(userName != false) {
			userName = false;
			var link = document.all("loginLink")
			link.innerText = "Login";
			link.href = "loginUserPage.php";
			document.all("logoutCell").style.display = "none";
			return true;
		}
		return false;
	}

	function checkLogin() {
<? 	if(isLoggedIn()) echo "showLogin(oldDate, '" . $_SESSION["user_displayName"] . "', '"
			.  $_SESSION["user_primaryType"] . "');";
?>
	}

	var oldDate = <?= time() ?>;
	function withinDateRange(newDate) {
		/*if(newDate >= oldDate)
			alert("new date set");
		else
			alert("staying with old date"); */
		if(newDate >= oldDate) {
			oldDate = newDate;
			return true;
		} else
			return false;
	}

	function showMessage(message) {
		if(isMac) return false;
		var div = document.all("message");
		div.innerText = message;
		/*var mouseCoords = getMouseCoords(window.event);
		div.style.left = mouseCoords[0];
		div.style.top = mouseCoords[1] - 20; */
		div.style.display = "inline";
	}

	function hideMessage() {
		var div = document.all("message");
		div.style.display = "none";
	}

	function highlightItem(item, message) {
		//item.className = "menuCellHighlight";
		showMessage(message);
	}

	function unHighlightItem(item) {
		//item.className = "menuCell";
		hideMessage();
	}
</script>

<div id="message"></div>

<table class="menuTable" cellspacing="0" cellpadding="0" width="100%" background="images/bg.gif">
	<tr valign="bottom">
		<td><a <?= getLinkProps("front.php"); ?>
			onMouseOver="highlightItem(this.parent, 'Go back home');"
			onMouseOut="unHighlightItem(this.parent);">
			<img src="images/leftlogo.gif" height="50" width="60" border="0" alt="Home">
			</a></td>
		<td class="menuCell"><a
<?if(noFrames())
		echo "href='indexTop.php?goto=searchFrameset'";
	else
		echo "href='searchFrameset.php' target='mainFrame'";
?>
			onMouseOver="highlightItem(this.parent, 'Search through festivals from around the world');"
			onMouseOut="unHighlightItem(this.parent);">Find a Fest</a></td>
		<td class="menuCell"><a <?= getLinkProps("showPromotions.php"); ?>
			onMouseOver="highlightItem(this.parentElement, 'Promote your festival through our website');"
			onMouseOut="unHighlightItem(this.parentElement);">Advertise</a></td>
		<td class="menuCell"><a <?= getLinkProps("festList.php"); ?>
			onMouseOver="highlightItem(this.parent, 'Keep a personal list of fests which interest you');"
			onMouseOut="unHighlightItem(this.parent);">FestList</a></td>
		<td class="menuCell"><a <?= getLinkProps("about.php"); ?>
			onMouseOver="highlightItem(this.parent, 'Learn what makes us tick');"
			onMouseOut="unHighlightItem(this.parent);">About</a></td>
		<td class="menuCell"><a <?= getLinkProps("help.php"); ?>
			onMouseOver="highlightItem(this.parent, 'How do I use this thing?');"
			onMouseOut="unHighlightItem(this.parent);">Help</a></td>
		<td class="menuCell"><a id="loginLink"
<?if(noFrames())
		echo "href='indexTop.php?goto=login'";
	else
		echo "href='loginUserPage.php' target='mainFrame'";
?>
			onMouseOver="highlightItem(this.parent, 'Use your account for updating fest info and accessing your FestList');"
			onMouseOut="unHighlightItem(this.parent);">Login</a></td>
		<td class="menuCell" id="logoutCell" style="display: none;"><a <?= getLinkProps("logout.php"); ?>
			onMouseOver="highlightItem(this.parent, 'Logout when done for greater security');"
			onMouseOut="unHighlightItem(this.parent);">Logout</a></td>
	</tr>
</table>

<?if(!noFrames()) { ?>
</body>
</html>
<?} ?>