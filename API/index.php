<?php
require_once 'RequestAPI.php';
require_once 'Authenticator.php';
require_once 'Service/RestExecutor.php';

// Json to request
$json = '{
	"To": "Street 1",
	"From": "Street 2",
	"Type": "Ride",
}';

// Optionals
// Could be set default at RequestAPI class
$url = 'http://api.com/activity' 

// Could be set default at Authenticator class
$urlAuth = 'http://api.com/token' 
$user = 'login'
$password = '12345'

// Usage
$restExecutor = new RestExecutor();
$api = new RequestAPI($json, new Authenticator($restExecutor, $urlAuth), $restExecutor, $url);

if ($api->auth($user, $password)){
	// Could use any HTTP methods. POST is default.
	$api->request('GET');
} else {
	print 'Login and/or password do not accepted.';
}


?>