<?
	require_once "dbFunctions.php";
?>

<html>

<head>
  <title>FilmDevil Menu</title>
	<link rel="stylesheet" href="styles/outside.css" type="text/css">
	<style>
		/* Redefining link styles */
	  a {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #CCCCCC; text-decoration: none }
	  a:link {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #CCCCCC; text-decoration: none }
	  a:visited {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #CCCCCC; text-decoration: none }
	  a:hover {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #FFFFFF; text-decoration: none }
	  a:active {  font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; color: #FFFFFF; text-decoration: underline }

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
		}

		.userName {
			font-size: 12px;
		}

	</style>
</head>
<body onLoad="checkLogin();">
<script src="mousetrack.js"></script>
<script language="javascript">
	var userName = false;

	function showLogin(name, primaryType) {
		if(userName != name) {
			userName = name;
			var link = document.all("loginLink")
			link.innerHTML = "Welcome <span class='userName'>" + userName + "</span>";
			if(primaryType == "administrator")
				link.href = "welcomeFest.php";
			else if(primaryType == "devil")
				link.href = "welcomeAdmin.php";
			else
				link.href = "welcome.php";

			document.all("logoutCell").style.display = "inline";
			return true;
		}
		return false;
	}

	function hideLogin() {
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
<? 	if(isLoggedIn()) echo "showLogin('" . $_SESSION["user_displayName"] . "', '"
			.  $_SESSION["user_primaryType"] . "');";
?>
	}

	function showMessage(message) {
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
		//	item.className = "menuCell";
		hideMessage();
	}
</script>

<div id="message">Default Message</div>
<!---<div style="position: absolute; left: 100; top: 0"><img src="images/newtop.jpg" border="0"></div>--->

<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#FF0000">
	<tr height="60">
		<td width="100"><a href="welcome.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'Go back home');"
			onMouseOut="unHighlightItem(this.parent);">
			<img src="images/newlogo.jpg" height="60" width="100" border="0" alt="Home">
			</a></td>

<td valign="top"><table cellspacing="0" cellpadding="0" width="100%" bgcolor="#FF0000" background="images/newtop.jpg">
		<tr height="60">
		<td class="menuCell" valign="bottom"><a href="searchFrameset.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'Search through festivals from around the world');"
			onMouseOut="unHighlightItem(this.parent);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Find a Fest</a></td>
		<td class="menuCell" valign="bottom"><a href="showPromotions.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'Promote your festival through our website');"
			onMouseOut="unHighlightItem(this.parent);">Promote Fest</a></td>
		<td class="menuCell" valign="bottom"><a href="festList.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'Keep a personal list of fests which interest you');"
			onMouseOut="unHighlightItem(this.parent);">FestList</a></td>
		<td class="menuCell" valign="bottom"><a href="about.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'Learn what makes us tick');"
			onMouseOut="unHighlightItem(this.parent);">About</a></td>
		<td class="menuCell" valign="bottom"><a href="help.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'How do I use this thing?');"
			onMouseOut="unHighlightItem(this.parent);">Help</a></td>
		<td class="menuCell" valign="bottom"><a id="loginLink" href="loginUserPage.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'Use your account for updating fest info and accessing your FestList');"
			onMouseOut="unHighlightItem(this.parent);">Login</a></td>
		<td class="menuCell" valign="bottom" id="logoutCell" style="display: none;"><a href="logout.php" target="mainFrame"
			onMouseOver="highlightItem(this.parent, 'Logout when done for greater security');"
			onMouseOut="unHighlightItem(this.parent);">Logout</a></td>
	</tr>
</table>
</td></tr></table>
</body>

</html>