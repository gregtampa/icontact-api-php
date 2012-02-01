<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

// Define our data
define('ICONTACT_APIPASSWORD',   'password');
define('ICONTACT_APIUSERNAME',   'username');
define('ICONTACT_APPID',         'api key');

// Load the iContactApi library
require_once('lib/iContactApi.php');
// Store the singleton
$oiContact = iContactApi::getInstance();
// Try to make the call(s)
try {
	/**
	 * Below are examples on how to call the  iContact
	 * PHP API class
	**/
	// Grab all contacts
	var_dump($oiContact->getContacts());
	// Grab a contact
	var_dump($oiContact->getContact($iContactId));
	// Create a contact
	var_dump($oiContact->addContact('joe@shmoe.com', null, null, 'Joe', 'Shmoe', null, '123 Somewhere Ln', 'Apt 12', 'Somewhere', 'NW', '12345', '123-456-7890', '123-456-7890', null));
	// Create a list
	var_dump($oiContact->addList('somelist', 123456, true, false, false, 'Just an example list', 'Some List'));
	// Subscribe contact to list
	var_dump($oiContact->subscribeContactToList(123456, 123456, 'normal'));
	// Create message
	var_dump($oiContact->addMessage('An Example Message', 123456, '<h1>An Example Message</h1>', 'An Example Message', 'ExampleMessage', 123456, 'normal'));
	// Schedule send
	var_dump($oiContact->sendMessage(array(123456, 123457), 123456, array(234567, 234568), null, null, mktime(12, 0, 0, 1, 1, 2012)));
} catch (Exception $oException) { // Catch any exceptions
	// Dump errors
	var_dump($oiContact->getErrors());
	// Along with errors, if you wish to view the last
	// request and/or response call the following
	var_dump($oiContact->getLastRequest());
	var_dump($oiContact->getLastResponse());
}
