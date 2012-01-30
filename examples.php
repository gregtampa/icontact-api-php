<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

// Define our data
define('ICONTACT_APIPASSWORD',   'password');
define('ICONTACT_APIUSERNAME',   'username');
define('ICONTACT_APPID',         'api key');

// Load the iContactApi library
require_once('lib/iContactApi.php');

// Try to make a call
$oiContact = iContactApi::getInstance();
try {
	// Dump a contact
	var_dump($oiContact->getContact(41281380));
} catch (Exception $oException) { // Catch any exceptions
	// Dump errors
	var_dump($oiContact->getErrors());
}
