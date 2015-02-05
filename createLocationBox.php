<?
	require_once "dbFunctions.php";
	echo "Creating location box...";

	fd_connect();
	$continent = fd_query ("SELECT DISTINCT continent FROM fests ORDER BY continent ASC");
	$countall = fd_query ("SELECT COUNT(*) FROM fests");
	$countall2 = mysql_fetch_array($countall);
	$any="Any  (".$countall2[0].")";

	$numLocations=0;
	while($row1 = mysql_fetch_array($continent)) {
	  $count = fd_query ("SELECT COUNT(*) FROM fests WHERE continent='".$row1["continent"]."'");
	  $count2 = mysql_fetch_array($count);
	  $location1[$numLocations]=$row1["continent"];
	  $location2[$numLocations]=$row1["continent"]."  (".$count2[0].")";
	  $numLocations++;
	  $country = fd_query ("SELECT DISTINCT country FROM fests WHERE continent='".$row1["continent"]."' ORDER BY country ASC");
	  while($row2 = mysql_fetch_array($country)) {
	    $count = fd_query ("SELECT COUNT(*) FROM fests WHERE country='".$row2["country"]."'");
	    $count2 = mysql_fetch_array($count);
	    $location1[$numLocations]=$row2["country"];
	    $location2[$numLocations]="&nbsp;&nbsp;&nbsp;&nbsp;".$row2["country"]."  (".$count2[0].")";
	    $numLocations++;
	    $region = fd_query ("SELECT DISTINCT region FROM fests WHERE country='".$row2["country"]."' ORDER BY region ASC");
	    while($row3 = mysql_fetch_array($region)) {
	      $count = fd_query ("SELECT COUNT(*) FROM fests WHERE region='".$row3["region"]."'");
	      $count2 = mysql_fetch_array($count);
	      $location1[$numLocations]=$row3["region"];
	      $location2[$numLocations]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row3["region"]."  (".$count2[0].")";
	      $numLocations++;
	      $state = fd_query ("SELECT DISTINCT vState FROM fests WHERE region='".$row3["region"]."' ORDER BY vState ASC");
	      while($row4 = mysql_fetch_array($state)) {
	        $count = fd_query ("SELECT COUNT(*) FROM fests WHERE vState='".$row4["vState"]."'");
	        $count2 = mysql_fetch_array($count);
	        $location1[$numLocations]=$row4["vState"];
	        $location2[$numLocations]="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row4["vState"]."  (".$count2[0].")";
	        $numLocations++;
	      }
	    }
	  }
	}
	ob_start();
?>
    <option value="Any" selected>
    <? print ($any); ?>
    </option>
<?for ($i=0; $i < $numLocations; $i++) {
  	if ($location1[$i] != "") { ?>
      <option value="<? print ($location1[$i]); ?>">
      	<? print ($location2[$i]); ?>
      </option>
<?  } # if
	} # for ?>
<?$output = ob_get_clean();
	fd_query("update data set data = '$output' where id = 'locationBox'");
?>

	Done.