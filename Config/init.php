<?php
/*This Include File is used to inisiate all the Globeled use data veriable and enhance there value with hirerachy  for purpose of security in web page */
/*Strat The session for every file we include in our Folder*/
/* For View  Errors in php page */
//header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');
ini_set('error_log', '../Log/php-errors' . date('YmdH') . '.log');

// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set("session.cookie_httponly", True);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', True);
//Setting the X-XSS-Protection Header:
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Content-Type: text/html; charset=UTF-8");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("X-Permitted-Cross-Domain-Policies: none");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Access-Control-Allow-Origin", "*");
// Uses a secure connection (HTTPS) if possible
ini_set('session.cookie_secure', True);
ini_set('session.gc_maxlifetime', 30 * 60);
ini_set('max_execution_time', 300);
ini_set('session.cookie_lifetime', 30 * 60); // 5 sec
//ini_set('session.use_cookies', '0');
//session_id("decems2");

session_start();

//Define Rules For further Use;
//if($_SERVER['REQUEST_SCHEME'] == 'http')
//die('privay error not allowed to open');
if (isset($_SERVER['HTTPS'])) {
	define("REQUEST_SCHEME", "https");
} else {
	define("REQUEST_SCHEME", "http");
}

$Host_Directory = '/emsdemo/';
define("ROOT_PATH", __dir__ . '/../');
$location = REQUEST_SCHEME . '://' . $_SERVER['HTTP_HOST'] . $Host_Directory;
//$location= 'https://'.$_SERVER['HTTP_HOST'].$Host_Directory;
//$location= $_SERVER['HTTP_HOST'].$Host_Directory;
define("URL", $location);
define("INCL", URL . "Controller/");
define("CLS", ROOT_PATH . "AppData/Class/");
define("LIB", ROOT_PATH . "AppData/lib/");
define("STYLE", URL . "Style/");
define("SCRIPT", URL . "Script/");
define("IMAGE", URL . "Images/");
define("UPLOAD", URL . "Upload/");
define("UPLOADS", ROOT_PATH . "Upload/");
define("IMG", STYLE . 'images/');

define("CANDIDATE_INFO_URL", 'https://ems.cogentlab.com/candidate_info/');
define("INTERVIEW_URL", 'https://interview.cogentems.com/interview/');

/* Email Configuration   */
// define('EMAIL_HOST', 'mail.cogenteservices.in');
// define('EMAIL_AUTH', true);
// define('EMAIL_USER', 'ems@cogenteservices.in');
// define('EMAIL_PASS', '987654321');
// define('EMAIL_SMTPSecure', 'TLS');
// define('EMAIL_PORT', '25');
// define('EMAIL_FROM', 'ems@cogenteservices.in');
// define('EMAIL_FROMWhere', 'EMS:Cogent Employee Management System');
// define('EMS_CenterName', 'Noida');
/* Email Configuration END  */

define("ADMINISTRATORID", 'CE03070003'); //use save_personal.php for edit button

/* SMS Configuration Start */
// define('SMS_URL', 'http://site.ping4sms.com');
// define('SMS_TOKEN', '7d6a8fd620b4b19bad7adedd6b69dc6d');
// define('SMS_CREDIT', '2');
// define('SMS_SENDER', 'COGENT');
// define('appkey', 'ces');
/* SMS Configuration END */





require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

######################----------------function for validation-----------------######################
function cleanUserInput($userinput)
{
	$myDB = new MysqliDb();
	$connection = $myDB->dbConnect();
	// Open your database connection
	$dbConnection = $connection;
	// check if input is empty
	if (empty($userinput)) {
		return;
	} else {
		// Strip any html characters
		// $userinput = htmlspecialchars($userinput);
		// Clean input using the database  
		$userinput = mysqli_real_escape_string($dbConnection, $userinput);
	}

	// Return a cleaned string
	return $userinput;
}



function clean($data)
{
	// $data = htmlspecialchars($data);
	$data = stripslashes($data);
	$data = trim($data);
	return $data;
}
// function generateRandomString($length = 4)
// {
// 	$characters = 'abcdefghijklmnopqrstuvwxyz';
// 	$charactersLength = strlen($characters);
// 	$randomString = '';
// 	for ($i = 0; $i < $length; $i++) {
// 		$randomString .= $characters[random_int(0, $charactersLength - 1)];
// 	}
// 	return $randomString;
// }

function generateRandomString($length = 5, $string = "abcdefghijklmnopqrstuvwxyz")
{
	//$string can be:
	//0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
	//0123456789abcdefghijklmnopqrstuvwxyz
	return substr(str_shuffle($string), 0, $length);
}

function generateRandomNumber($length = 4)
{
	$characters = '1234567890';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[random_int(0, $charactersLength - 1)];
	}
	return $randomString;
}

function csrfToken()
{
	return bin2hex(rand(10, 100));
}
$authUserSite = array('CE011929747', 'CE10091236', 'CE06134661', 'CE0122942506', 'CE03146043');
// can add/edit/delete site master
