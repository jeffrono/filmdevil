<?	require_once "dbFunctions.php";
	fd_connect();

if(!empty($HTTP_GET_VARS['ID']))
	$ID=$HTTP_GET_VARS['ID'];
else
	die("There is no id");

$fest1 = mysql_query ("select title, id from fests where ID=". fd_filter($ID, true));
$fest = mysql_fetch_array($fest1);

?>
<html>
<head>
<title>Rate & Review <? print $fest["title"] ?> !</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="styles/central.css" type="text/css">
</head>

<body bgcolor="#000000">
<?include "header.php" ?>
<script language="javascript">
	function validateForm() {
		var form = document.reviewForm;
		if(form.title.value == "") {
			alert("Please enter a title for the review.");
			return false;
		}
		if(form.body.value == "") {
			alert("Please enter body text for the review in the big box.");
			return false;
		}
		return true;
	}
</script>


	<form name="reviewForm" method="post" action="thankreview.php"
		onSubmit="return validateForm()">
	<input type="hidden" name="id" value="<? print $fest["id"] ?>">
  <table width="600" align="center" border="0" cellspacing="0" cellpadding="5" >
    <tr>
      <td colspan="3">Please write a review for:<br>
        <a class="title">
        <? print $fest["title"] ?>
        </a></td>
    </tr>
		<tr>
    	<td colspan="2"><hr color="#000000"></td>
  	</tr>
    <tr class="alt1">
      <td >Your Name:</td>
      <td>
        <input type="text" name="name" size="26" maxlength="50"
			<? if(isLoggedIn()) echo ("value='" . $_SESSION["user_displayName"]
				 	. "'");  ?>>
      </td>
    </tr>
    <tr class="alt2">
      <td>Email:
				<div class="note">(omit this if you don't want someone to be able to contact you)
				</div>
			</td>
      <td>
        <input type="text" name="email" size="26" maxlength="50"
			<? if(isLoggedIn()) echo ("value='" . $_SESSION["user_email"] . "'"); ?>>
      </td>
    </tr>
    <tr class="alt1">
      <td >Website:</td>
      <td>
        <input type="text" name="URL" size="26" maxlength="100"
			<? if(isLoggedIn()) echo ("value='" . $_SESSION["user_webSite"] . "'"); ?>>
      </td>
    </tr>
    <tr class="alt2">
      <td >You are a</td>
      <td>
        <select name="type">
          <option value="Professional Filmmaker">Professional Filmmaker</option>
          <option value="Student Filmmaker">Student Filmmaker</option>
          <option value="Amateur Filmmaker">Amateur Filmmaker</option>
          <option value="Industry Pro">Industry Pro</option>
          <option value="Film Enthusiast">Film Enthusiast</option>
        </select>
      </td>
    </tr>
    <tr class="alt1">
      <td >Favorite Film</td>
      <td>
        <input type="text" name="favMov" size="26" maxlength="255"
			<? if(isLoggedIn()) echo ("value='" . $_SESSION["user_favFilm"] . "'"); ?>>
      </td>
    </tr>
    <tr class="alt2">
      <td >Favorite Film-related Website:</td>
      <td>
        <input type="text" name="favWeb" size="26" maxlength="255"
			<? if(isLoggedIn()) echo ("value='" . $_SESSION["user_favWebSite"] . "'"); ?>>
      </td>
      <td  valign="bottom">&nbsp;</td>
    </tr>
		<tr>
    	<td colspan="2"><hr color="#000000"></td>
  	</tr>
    <tr>
			<td colspan="2">Please rate the festival by how satisfied you were on each
				of the 4 criteria below. For each of rows, click the circle under the
				devil face ranging from "blah" to "super happy":<br>
				<table border="0" cellspacing="" cellpadding="5">
        <tr>
          <td>&nbsp;</td>
          <td><img src="images/icon1white.gif" alt="1"></td>
          <td><img src="images/icon2white.gif" alt="2"></td>
          <td><img src="images/icon3white.gif" alt="3"></td>
          <td><img src="images/icon4white.gif" alt="4"></td>
          <td><img src="images/icon5white.gif" alt="5"></td>
        </tr>
        <tr class="alt1">
          <td>Films</td>
          <td align="center">
            <input type="radio" class="radio" name="filmRating" value="1">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="filmRating" value="2">
          </td align="center">
          <td align="center">
            <input type="radio" class="radio" name="filmRating" value="3" checked>
          </td align="center">
          <td align="center">
            <input type="radio" class="radio" name="filmRating" value="4">
          </td align="center">
          <td align="center">
            <input type="radio" class="radio" name="filmRating" value="5">
          </td align="center">
        </tr>
        <tr class="alt2">
          <td>Location</td align="center">
          <td align="center">
            <input type="radio" class="radio" name="locationRating" value="1">
          </td align="center">
          <td align="center">
            <input type="radio" class="radio" name="locationRating" value="2">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="locationRating" value="3" checked>
          </td>
          <td align="center">
            <input type="radio" class="radio" name="locationRating" value="4">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="locationRating" value="5">
          </td>
        </tr>
        <tr class="alt1">
          <td>Organization</td>
          <td align="center">
            <input type="radio" class="radio" name="orgRating" value="1">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="orgRating" value="2">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="orgRating" value="3" checked>
          </td>
          <td align="center">
            <input type="radio" class="radio" name="orgRating" value="4">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="orgRating" value="5">
          </td>
        </tr>
        <tr class="alt2">
          <td>People</td>
          <td align="center">
            <input type="radio" class="radio" name="peopleRating" value="1">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="peopleRating" value="2">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="peopleRating" value="3" checked>
          </td>
          <td align="center">
            <input type="radio" class="radio" name="peopleRating" value="4">
          </td>
          <td align="center">
            <input type="radio" class="radio" name="peopleRating" value="5">
          </td>
        </tr>
      </table></td>
		</tr>
		<tr>
	    <td colspan="2"><hr color="#000000"></td>
	  </tr>
    <tr class="alt1">
      <td >Title of this review:</td>
      <td colspan="2" align="left" valign="top">
        <input type="text" name="title" size="70" value="">
      </td>
    </tr>
    <tr class="alt2">
      <td align="left" valign="top">Review:</td>
		</tr>
		<tr class="alt2">
      <td colspan="2">
        <textarea name="body" rows="10" cols="50" wrap="VIRTUAL"></textarea>
      </td>
    </tr>
		<tr>
	    <td colspan="2"><hr color="#000000"></td>
	  </tr>
    <tr>
      <td colspan="2" align="center">
      	<input type="submit" name="Submit2" value="Submit" class="button"
					onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">
	    	<input type="reset" name="Submit2" value="Reset" class="button"
					onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">
      </td>
    </tr>
  </table>
</form>

<? include "footer.php"; ?>

</body>
</html>