<?require_once("dbFunctions.php");

	function rewriteNoFrameLinks($buffer) {
		$buffer = preg_replace("/(href=[\"\'])([^\"\'\?]*)([\"\'])/i",
			"\$1\$2?noFrames\$3", $buffer);
		return preg_replace("/(href=[\"\'])([^\"\']*)([\"\'])/i",
			"\$1\$2&noFrames\$3" . "\$3", $buffer);
	}

	function addNoFramesLink($link) {
		return "linkToNothing";
		if(!preg_match("/\?/", $link))
			return $link . "?noFrames";
		else
			return $link . "&noFrames";
	}

	if(noFrames()) {
		ob_start("rewriteNoFrameLinks");
		include "top.php";
	}
?>