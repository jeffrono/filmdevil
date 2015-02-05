<?require "dbFunctions.php";
	fd_connect();

	fd_import_request_variables("gp", "form_");
  $festIDQuery = fd_query("select ID from fests");

	if(isset($form_generateHeuristic)) {
		echo "<br>Generating Heuristics...";
	  while($row = mysql_fetch_assoc($festIDQuery))
 	  	generateHeuristic($row["ID"]);
		mysql_data_seek($festIDQuery, 0);
		echo "<br>Done.";
	}

	echo "<br>Creating similar festivals...";
	fd_query("delete from similarFest");
	//mysql_data_seek($festIDQuery, mysql_num_rows($festIDQuery) - 2);
  while($festRow = mysql_fetch_assoc($festIDQuery)) {
		$listedQuery = fd_query("select shortName from purchase inner join purchasePackage
			on purchase.id = purchasePackage.purchaseID
			inner join purchasePackagePromotion on
			purchasePackagePromotion.purchasePackageID = purchasePackage.id
			inner join promotion on
			promotion.id = purchasePackagePromotion.promotionID
			where status = 'activated' and startDate <= now() and endDate >= now()
			and shortName like 'listed%'
			and festID = " . $festRow["ID"]);
		$listed = 0;
		while($listedRow = mysql_fetch_assoc($listedQuery)) {
			$match = array();
			if(preg_match("/listed([0-9]*)/", $listedRow["shortName"], $match)
				&& $match[1] > $listed)
				$listed = $match[1];
	}
    if($listed > 0)
			setSimilarFests($festRow["ID"], $listed);
  }
	echo "<br>Done.";
?>
