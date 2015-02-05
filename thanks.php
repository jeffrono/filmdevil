<? 

mysql_connect("128.121.4.19:3306","devil","films");

mysql_select_db("filmfests");

while(list($key, $val) = each($HTTP_POST_VARS)){$$key=$val;}
mysql_query("insert into notify(email) values('".$email."')");
?>

<html>
<head>
<style>
#logo {

position: relative;

top: 1px;

left: 1px;

visibility: visible }
</style>
<script language="JavaScript">

<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}

//-->

</script>
<link rel="stylesheet" href="styles/info.css" type="text/css">
</head>
<body text="#FFFFFF" bgcolor="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="description">

 <img src="leftlogo.gif" border="0" ID="logo"> <br>
    <br>
    <br>
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
  <table width="90%" border="0" cellspacing="2" cellpadding="2">
    <tr>
    <td>
        <table width="200" align="center" bordercolor="#CC0000" cellpadding="3" border="2" cellspacing="0">
          <tr> 
            <td class="popupheading">FESTIVALS: 
            </td>
          </tr>
          <tr> 
            <td class="description" bgcolor="#000000">If you received an email 
              with your password, your festival is already listed on our site. 
              Please <a  href="#" onClick="MM_openBrWindow('login.php','FestivalUpdate','resizable=yes,width=510,height=575')" class="description">Log 
              In</a> and update your festival information.</td>
          </tr>
        </table>

</td>
      <td align="center" valign="top"> 
        <table bordercolor="#CC0000" bgcolor="#990000" class="description" width="200" cellpadding="4">
          <tr>
      <td><div align="center">Thank You<br>We will notify you as soon as the site is live</div>
              
	  
</td></tr></table></td>
  </tr>
</table>

          
</blockquote>
    </body>
</html>