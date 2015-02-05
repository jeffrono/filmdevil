<?
if(!empty($HTTP_GET_VARS['count'])){
$count=$HTTP_GET_VARS['count'];
}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
<style>
#table2 {

position: absolute;

top: 0px;

left: 0px;

visibility: visible }
</style>
</head>

<body bgcolor="#999999" text="#FFFF00">
<? if(!empty($count)){ ?>
<table width="100%" class="bar" ID="table2" height="10" cellspacing="0" cellpadding="0">
  <tr height="10" valign="top"> 
    <td align="center" class="bar" valign="top"> 
      <div>&nbsp;&nbsp;Rating</div>
    </td>
    <td align="center" class="bar" valign="top">Reviews</td>
    <td width="64%" class="bar" valign="top"> 
      <div align="center">Title
         (displaying 
          <? print $count; ?>
          results)
      </div>
    </td>
    <td width="20%" class="bar" valign="top">Location</td>
  </tr>
</table><? } ?>
</body>
</html>
