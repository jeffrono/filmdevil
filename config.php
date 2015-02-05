<?
// REMOTE FILMDEVIL SERVER CONFIG

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);


// DB Properties
define("DB_HOST", $server);
define("DB_NAME", $db);
define("DB_USER", $username);
define("DB_PASSWORD", $password);

// ERROR properties
define("ERROR_CONTACT", "jeffnovich@gmail.com");
define("EMAIL_ERROR", false);
define("LOG_ERROR", true);
define("ECHO_ERROR", true);

// NETWORK properties
define("URL_ROOT", "http://filmdevil.herokuapp.com"); // Ends with a /
define("ONLINE", true); // are you connected to the internet?
define("FESTS_URL_REWRITE", true); // is the /fests/fest_name.php url rewrite on?

// SESSION properties
define("SESSION_TIMEOUT", 20); // in minutes
define("CACHE_LIMITER", "public");
define("CACHE_TIMEOUT", 30); // in minutes

// MISC
define("SEARCH_ROW", true); // whether the default search view is in rows or columns
define("MAX_SEARCH_ROWS", 50); // number of rows returned in search
define("ON_APACHE", true); // if running on an apache web server
define("USE_WDDX", false); // whether WDDX is installed
define("UPDATE_SEARCH_ON_FEST_UPDATE", true);
define("UPDATE_SIMILAR_FESTS_ON_FEST_UPDATE", true);

// EMAIL PROPS
define("SEND_EMAIL", true);
define("LOG_EMAIL", true);
define("EMAIL_FOOTER", "\n\n---------------------\n"
	. "  Brought to you by your friends at Filmdevil.com,\n"
	. "  The most comprehensive film festival database ever.\n"
  . "  http://www.filmdevil.com");
define("SUPPORT_CONTACT", "FilmDevil Support");
define("SUPPORT_EMAIL", "jeffnovich@gmail.com");

// PAYMENT options
define("PAYPAL_EMAIL", "jeffnovich@gmail.com");
define("PROMOTION_CONTACT", "FilmDevil Promotion");
define("PROMOTION_EMAIL", "jeffnovich@gmail.com");

?>