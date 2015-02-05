<?php
require_once "dbFunctions.php";

fd_connect();
$data = mysql_fetch_array(mysql_query("select data from data where id = 1"));

print $data["data"];

?>