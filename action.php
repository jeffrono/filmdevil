<?
require "dbFunctions.php";

function incrementStat($festID, $statTypeID) {
	$result = fd_query("update stat set data = data + 1
		where festID = $festID and statTypeID = '$statTypeID'
		and startDate >= now() and endDate <= now()");
	if(mysql_affected_rows() == 0) {
		fd_query("insert into stat (festID, statTypeID, startDate, endDate, data)
			values ($festID, '$statTypeID', now(), now(), 1)");
	}
}

fd_connect();
fd_import_request_variables("GP", "get_");

$festquery = "select * from fests where id=$get_id";
$fest = mysql_fetch_array(fd_query($festquery));

switch($get_type) {
	case 1:
		#type 1 is google
		incrementStat($get_id, "google");
		header("Location: http://www.google.com/search?sourceid=navclient&q=".$fest["title"]);
		break;
	case 2:
		#type 2 is profile view
		incrementStat($get_id, "profile");
		if(noFrames())
			header("Location: " . URL_ROOT . "info.php?noFrames&ID=".$fest["ID"]);
		else
			header("Location: " . URL_ROOT . "info.php?ID=".$fest["ID"]);
		break;
	case 3:
		#type 3 is web
		incrementStat($get_id, "website");
		header("Location: ".$fest["URL"]);
		break;
	case 4:
		#type 4 is application
		incrementStat($get_id, "application");
		header("Location: " . $fest['appURL']);
		break;
	case 5:
		#type 5 is contact
		incrementStat($get_id, "contact");
		header("Location: " . URL_ROOT . "contactFest1.php?ID=" . $get_id);
		break;
	case 6:
		#type 6 is festList
		incrementStat($get_id, "festList");
		if(noFrames())
			header("Location: " . URL_ROOT . "festList.php?noFrames&add=" . $get_id);
		else
			header("Location: " . URL_ROOT . "festList.php?add=" . $get_id);
		break;
}