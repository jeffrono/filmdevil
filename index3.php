<html>

	
<head>
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
<title>DHTML for the WWW | Using iLayers and iFrames</title>
<style type="text/css">
<!--
.everything {  position: absolute; left: 0px; top: 0px; clip:  rect(   )}
    BODY {scrollbar-3dlight-color: #CC0000;
           scrollbar-arrow-color: #CC0000;
           scrollbar-base-color:black;
           scrollbar-darkshadow-color: #660000;
           scrollbar-face-color:#9900000;
           scrollbar-highlight-color:black;
           scrollbar-shadow-color:#660000}
-->
</style>
</head>

	<body bgcolor="#000000">
<table width="950" class="everything" cellspacing="0" cellpadding="0">
<tr height="100"><td width="170" rowspan="3">
<layer id="leftFrame1" src="newtop.php" top="0" left="0"></layer>
		<nolayer>
			<iframe id="leftFrame" name="leftFrame" src="blank.htm" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="NO">
				<a href="blank.htm">External Content</a>
			</iframe>
		</nolayer>
</td>
<td width="780">	<layer id="topFrame1" src="newtop.php" top="0" left="0"></layer>
		<nolayer>
			<iframe id="topFrame" src="newtop.php" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="NO">
				<a href="newtop.php">External Content</a>
			</iframe>
		</nolayer>
</td></tr>
<tr height="140"><td><layer id="midFrame1" src="frame1.php" top="0" left="0"></layer>
		<nolayer>
			<iframe id="midFrame" src="frame1.php" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="NO">
				<a href="frame1.php">External Content</a>
			</iframe>
		</nolayer></td></tr>
<tr height="460"><td><layer id="htNAV" src="info.php" top="0" left="0"></layer>
		<nolayer>
			<iframe id="htIE" src="info.php" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="NO">
				<a href="info.php">External Content</a>
			</iframe>
		</nolayer></td></tr>
<!---<tr height="560"><td></td></tr>--->
</table>
	</body>

</html>