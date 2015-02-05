<?require "dbFunctions.php";

	fd_import_request_variables("gp", "form_");
	if(!isset($form_url)) {
		header("Location: " . URL_ROOT . "index.php");
		die();
	}

	if($form_url == "" || $form_url == "listAllFests.php") {
		$_REQUEST["noFrames"] = "";
		include "listAllFests.php";
		die();
	}

  /* For links to festRedirect.php, all spaces are turned into dashes, and
    all dashes into three underscores */
	$title = str_replace("-", " ", urldecode(html_entity_decode($form_url)));
	$title = str_replace("___", "-", $title);
	$title = fd_filter(str_replace(".php", "", $title));

	fd_connect();
	$result = mysql_fetch_assoc(fd_query("select ID from fests where title = '$title'"));

	if(empty($result)) {
		trigger_error("Can't find fest for name '" . $title . "'");
		header("Location: " . URL_ROOT . "index.php");
	} else {
		if(FESTS_URL_REWRITE)
			$useAbsoluteLinks = true;
		$_REQUEST["noFrames"] = "";
		$_REQUEST["ID"] = $result["ID"];
		include "info.php";
	}
?>
