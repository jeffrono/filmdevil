<? 
mysql_connect("128.121.4.19:3306", "devil", "films");
mysql_select_db("filmfests");

for($i=1; $i<1722; $i++){
	$result = mysql_fetch_array(mysql_query ("select * from fests where id=".$i)); 
	$title = $result["title"];
	$title = ucwords(strtolower($title));
	$pass= $result["password"];
	$pass = substr($pass, 0, 5);

	print $i . $pass . $title . "<br>";
	if ($pass != '' && $title != '') {
		mysql_query ("update fests set title='".$title."', password='".$pass."' where id=".$i);
	}
}
	mysql_close();		
?>
