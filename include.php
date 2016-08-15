<?php

require __DIR__ . '/vendor/autoload.php';

session_start();

$username = 'jfenech@ea.com';
$password = 'gbcjrj3p';

$random1 = 'c24@#%';
$random2 = 'asE56YGy';

$loggedin = false;

$hash = md5($random1.$password.$random2); 

/* FORMS */
 
//logged out
if(isset($_GET['logout']))
{
	unset($_SESSION['login']);
}

//log in
if (isset($_SESSION['login']) && $_SESSION['login'] == $hash) {
	$loggedin = true;
} 
else if (isset($_POST['submit'])) {
 
	if ($_POST['username'] == $username && $_POST['password'] == $password){

		$_SESSION["login"] = $hash;
		header("Location: $_SERVER[PHP_SELF]");
 
	} else {
 
		echo '<p>Username or password is invalid</p>';
 
	}
} 

/* ZENDESK */

if ($loggedin) {
	define("ZDAPIKEY", "sqKQjy6eAUrV43IWUseZr5j8ncYM5FxL43rIV9mX");
	define("ZDUSER", $username);
	define("ZDURL", "https://firemonkeys.zendesk.com/api/v2");
}

/* Note: do not put a trailing slash at the end of v2 */
function curlWrap($url, $json, $action)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
	switch($action){
		case "POST":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "GET":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "DELETE":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			break;
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($output, true);
	return $decoded;
}


/* MUSTACHE */

$options =  array('extension' => '.ms');

$m = new Mustache_Engine(array(
     'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views', $options),
));