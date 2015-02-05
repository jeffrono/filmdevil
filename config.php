<?
// REMOTE FILMDEVIL SERVER CONFIG

// DB Properties
define("DB_HOST", "128.121.4.19:3306");
define("DB_NAME", "filmfests");
define("DB_USER", "devil");
define("DB_PASSWORD", "films");

// ERROR properties
define("ERROR_CONTACT", "drpepper@fastmail.fm");
define("EMAIL_ERROR", false);
define("LOG_ERROR", true);
define("ECHO_ERROR", true);

// NETWORK properties
define("URL_ROOT", "http://www.filmdevil.com/"); // Ends with a /
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
define("SUPPORT_EMAIL", "jeffnovich@hotmail.com");

// PAYMENT options
define("PAYPAL_EMAIL", "support@filmdevil.com");
define("PROMOTION_CONTACT", "FilmDevil Promotion");
define("PROMOTION_EMAIL", "promotion@filmdevil.com");

?>