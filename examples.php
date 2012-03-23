<?php

require_once ('config.php');

require_once ('util.php');


/***********************************/

/** set your account details here **/

/***********************************/

$accountId = $account_id;

$clientFolderId = $client_folder_id;

//$welcomeMessageId="10397";

// add new contact

function addContact($accountId,$clientFolderId,$firstname,$lastname,$email,$password)

{

	$contactId = null;
	
	$response = callResource("/a/{$accountId}/c/{$clientFolderId}/contacts",
	
		'POST', array(
		
		array(
		
		'firstName' => $firstname,
		
		'lastName' => $lastname,
		
		'email' => $email,
		
		'cg_username' => $email,
		
		'cg_password' => $password
		
		)
	
	));
	
	
	if ($response['code'] == STATUS_CODE_SUCCESS) {
	
		$contactId = $response['data']['contacts'][0]['contactId'];
		
		$warningCount = 0;
		
		if (!empty($response['data']['warnings'])) {
		
		$warningCount = count($response['data']['warnings']);
		
		}
		
		} else {
	
		}
	
	return $contactId;

}

// add contact to spicifice list

function subscribeContactToList($contactId, $listId,$accountId,$clientFolderId,$welcomeMessageId)

{

	$response = callResource("/a/{$accountId}/c/{$clientFolderId}/subscriptions",
	
	'POST', array(
	
	array(
	
	'contactId' => $contactId,
	
	'listId' => $listId,
	
	'status' => 'normal',
	
	),
	
	));
	
	if ($response['code'] == STATUS_CODE_SUCCESS) {
	
		$warningCount = 0;
		
		if (!empty($response['data']['warnings'])) {
		
		$warningCount = count($response['data']['warnings']);
		
		}
	
//echo "<script> alert('Congratulation You are Successfully Add to Online Gun Permit Subscription');</script>";

//echo "<p>Subscribed contact {$contactId} to list {$listId}, with {$warningCount} warnings.</p>\n";

//dump($response['data']);


} else {

echo "<script> alert('Sorry Some Problem occur Try again Later');</script>";

}

}

function moveContactToList($contactId, $listId, $newlistId, $accountId, $clientFolderId)

{
	$subscriptionId = $listId."_".$contactId;
	
	$array = callResource("/a/{$accountId}/c/{$clientFolderId}/subscriptions/{subscriptionId}",
	
	'PUT', array(		
		'listId' => $listId,
		'status' => 'normal',		
		));
	$response = implode(",", $array);
	//var_dump($array);die;

	if ($response['code'] == STATUS_CODE_SUCCESS) {
	
		$warningCount = 0;
		
		if (!empty($response['data']['warnings'])) {
		
		$warningCount = count($response['data']['warnings']);
		
		}
	

} else {

echo "<script> alert('Sorry Some Problem occur Try again Later');</script>";

}

}
?>