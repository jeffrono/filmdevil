 <?

mysql_connect("128.121.4.19:3306", "devil", "films");

mysql_select_db("filmfests");

if(!empty($HTTP_GET_VARS['ID'])){

$ID=$HTTP_GET_VARS['ID'];



$reviews=mysql_query("select *, DATE_FORMAT(date, '%M %e, %Y') AS date from reviews where festID=$ID ORDER BY date ASC");



$fest=mysql_fetch_array(mysql_query("select * from fests where ID=$ID"));

$date = mysql_query ("SELECT DATE_FORMAT(date, '%M %e, %Y') AS date from reviews where festID=$ID");

$date = mysql_fetch_array($date);

mysql_close();



?>





<html>

<head>

<title>Info for <? print $fest["title"] ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="styles/reviews.css" type="text/css">

<script language="JavaScript">

<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}

//-->

</script>

</head>



<body text="#FFFFFF" background="images/bgbig.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



<table width="95%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td colspan="3">

    <td>

  </tr>

  <tr>

    <td align="left" valign="middle">

      <table cellspacing="0" cellpadding="0" width="120">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td align="center" valign="middle" width="100">

            <? if ($fest["logoURL"] != '') { ?>

            <img src="http://<? print $fest["logoURL"] ?>" width="100" height="80">

            <? } else{ ?>

            <img src="images/logosmall.jpg">

            <? } ?>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>



    </td>

    <td colspan="2"  valign="top">

      <table width ="95%" cellspacing="0" cellpadding="0">

        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="0" cellpadding="5" valign="top" align="right" width="95%">

              <tr>

                <td class="title" valign="top">

                  <? print $fest["title"] ?>

                </td>

                <td valign="top" align="right" width="100">

                  <div align="right"><a href="info.php?ID=<? print($fest["ID"]); ?>" class="border">Back to Info</a></div>

                </td>

              </tr>

              <tr valign="top">

                <td class="details" colspan="2" align="center"><img src="images/icon<? print round($fest["rating"]) ?>big.gif"

	  alt="<? if (round($fest["rating"]) == 0) { ?>Not Yet Rated<? } else { print round($fest["rating"]); ?> out of 5<? } ?>" align="absmiddle">

                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Displaying

                  <? print $fest["numReviews"] ?>

                  reviews &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To

                  write one <a href="#" onClick="MM_openBrWindow('writereview.php?ID=<? print($fest["ID"]); ?>','WriteReview','scrollbars=yes,width=600,height=400')"  class="read">click

                  here &nbsp;&nbsp;<img src="images/write.gif" border="0" alt="Write a Review" align="absmiddle"></a>

                </td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>

    </td>

  </tr>
<? $a=2; ?>
      <? while ($review=mysql_fetch_array($reviews)) { ?>
  <tr>

    <td colspan="3" align="<?
	if ($a%2) {
 print "right";
} else {
 print "left";
}
?>">


      <table width ="60%" cellspacing="0" cellpadding="0" background="images/bg.gif">
<? $a++; ?>
        <tr height="10">

          <td background="images/lefttop.jpg" width="10"></td>

          <td background="images/top.jpg"></td>

          <td background="images/righttop.jpg" width="10"></td>

        </tr>

        <tr>

          <td background="images/left.jpg" width="10"></td>

          <td>

            <table border="0" cellspacing="5" cellpadding="5" class="details" width="98%" align="center">

              <tr>

                <td>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr bgcolor="#000000">
                      <td width="160" height="50">
                        <div align="center"><img src="images/icon<? print $review["genRating"] ?>.gif" align="absmiddle"></div></a>
                      </td>
                      <td colspan="2" align="left"><a class="description">
                        <? print $review["title"] ?>
                        </a></td>
                    </tr>
                    <tr>
                      <td colspan="2" valign="top" height="128" class="description">
                        <? if($review["name"] != ''){ ?>
                        <? print $review["name"] ?>
                        <? } ?>
                        <? if($review["email"] != ''){ ?>
                        (<a href="mailto:<? print $review["email"] ?>">
                        <? print $review["email"] ?>
                        </a>)
                        <? } ?>
                        <? if($review["URL"] != ''){ ?>
                        <a href="<? print $review["URL"] ?>" target="_blank">
                        <? print $review["URL"] ?>
                        </a>
                        <? } ?>
                        <br>
                        <? if($review["type"] != ''){ ?>
                        <a class="info">
                        <? print $review["type"] ?>
                        </a> <br>
                        <? } ?>
                        <? if($review["favMov"] != ''){ ?>
                        <a class="info">Favorite film: </a><a href="http://us.imdb.com/Find?for='<? print $review["favMov"] ?>'" target="_blank">
                        <? print $review["favMov"] ?>
                        </a> <br>
                        <? } ?>
                        <? if($review["favWeb"] != ''){ ?>
                        <a class="info">Favorite film website: </a> <a href="<? print $review["favWeb"] ?>" target="_blank">
                        <? print $review["favWeb"] ?>
                        </a><br>
                        <? } ?>
                        <a class="info">Posted:
                        <?  print $date["date"]?>
                        </a>
						<!-- a=<? print $a; ?> --></td>
                      <td width="323" valign="top" align="right">
                        <table width="100" border="0" cellspacing="0" cellpadding="0" class="info" bgcolor="#000000" align="right">
                          <tr>
                            <td>
                              <div align="left">Films:</div>
                            </td>
                            <td>
                              <div align="center"><a class="info"><img src="images/icon<? print $review["filmRating"] ?>.gif" align="absmiddle"></a></div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div align="left">Location:</div>
                            </td>
                            <td>
                              <div align="center"><a class="info"><img src="images/icon<? print $review["locationRating"] ?>.gif" align="absmiddle"></a></div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div align="left">Organization:</div>
                            </td>
                            <td>
                              <div align="center"><a class="info"><img src="images/icon<? print $review["orgRating"] ?>.gif" align="absmiddle"></a></div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div align="left">People:</div>
                            </td>
                            <td>
                              <div align="center"><a class="info"><img src="images/icon<? print $review["peopleRating"] ?>.gif" align="absmiddle"></a></div>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <!---<tr bgcolor="#000000">

                      <td><a class="info">Film: <img src="images/icon<? print $review["filmRating"] ?>.gif" align="absmiddle"></a>

                      </td>

                      <td><a class="info">&nbsp;&nbsp;&nbsp;&nbsp;Location: <img src="images/icon<? print $review["locationRating"] ?>.gif" align="absmiddle"></a></td>

                      <td><a class="info">&nbsp;&nbsp;&nbsp;&nbsp;Organization:

                        <img src="images/icon<? print $review["orgRating"] ?>.gif" align="absmiddle"></a></td>

                      <td><a class="info">&nbsp;&nbsp;&nbsp;&nbsp;People: <img src="images/icon<? print $review["orgRating"] ?>.gif" align="absmiddle"></a></td>

                    </tr>--->
                    <tr>
                      <td colspan="3" bgcolor="#000000"><a class="info">
                        <? print $review["body"] ?>
                        </a></td>
                    </tr>
                    <tr>
                      <td height="1"></td>
                      <td width="218"></td>
                      <td></td>
                    </tr>
                  </table>

				</td>

              </tr>

            </table>

          </td>

          <td background="images/right.jpg" width="10"></td>

        </tr>

        <tr height="10">

          <td background="images/leftbottom.jpg" width="10"></td>

          <td background="images/bottom.jpg"></td>

          <td background="images/rightbottom.jpg" width="10"></td>

        </tr>

      </table>



    </td>

  </tr>
      <? } ?>
</table>

</body>

</html>

<? }  ?>
