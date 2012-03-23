<?php

/***********************************/

/** set your account details here **/

/***********************************/

$GLOBALS['config'] = array(
			'apiUrl' => 'https://app.icontact.com/icp',
			'username' => '   ',
			'password' => '   ',
			'appId' => '   ',
			);

// get accountID
$headers = array(
		"Accept: text/xml",
		"Content-Type: text/xml",
		'Api-Version: 2.0',
		'Api-AppId: ' . $GLOBALS['config']['appId'],
		'Api-Username: ' . $GLOBALS['config']['username'],
		'Api-Password: ' . $GLOBALS['config']['password']);
		
$ch=curl_init("https://app.icontact.com/icp/a/");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$buf = curl_exec($ch);

curl_close($ch);

$account_id = "";

if (($pos=strpos($buf,"<accountId>"))!==false){
	$account_id = substr($buf, strlen("<accountId>")+$pos);
	if (($pos=strpos($account_id,"<"))!==false){
		$account_id = substr($account_id, 0, $pos);	
	}
}

$ch=curl_init("https://app.icontact.com/icp/a/$account_id/c/");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$buf = curl_exec($ch);
curl_close($ch);

// Extract client folder id from response

$client_folder_id = "";
if (($pos=strpos($buf,"<clientFolderId>"))!==false){
	$client_folder_id = substr($buf, strlen("<clientFolderId>")+$pos);
	if (($pos=strpos($client_folder_id,"<"))!==false){
		$client_folder_id = substr($client_folder_id, 0, $pos);
	}
}

if (($pos=strpos($buf,"<clientFolderId>"))!==false){
	$client_folder_id = substr($buf, strlen("<clientFolderId>")+$pos);
	if (($pos=strpos($client_folder_id,"<"))!==false){
		$client_folder_id = substr($client_folder_id, 0, $pos);
	}
}


define('STATUS_CODE_SUCCESS', 200);

function callResource($url, $method, $data = null)

{

	$url = $GLOBALS['config']['apiUrl'] . $url;
	
	//echo $url;
	
	$handle = curl_init();
	
	$headers = array(
	
	'Accept: application/json',
	
	'Content-Type: application/json',
	
	'Api-Version: 2.2',
	
	'Api-AppId: ' . $GLOBALS['config']['appId'],
	
	'Api-Username: ' . $GLOBALS['config']['username'],
	
	'Api-Password: ' . $GLOBALS['config']['password'],
	
	);

	curl_setopt($handle, CURLOPT_URL, $url);
	
	curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
	
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	
	switch ($method) {
	
		case 'POST':
		
		
			curl_setopt($handle, CURLOPT_POST, true);
			
			curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
			
			break;
		
		 case 'PUT':
	 		if (!is_string($data) || !file_exists($data)) {
				// Not a file, so we assume this is just data
				curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
			} else {
				$rFileContentHandle = fopen($data, 'r');
				if ($rFileContentHandle === false) {
					$this->addError('A non-existant file was specified for POST data, or the file could not be opened.');
				} else {
					// Found a file, so upload its contents
					curl_setopt($handle, CURLOPT_PUT, true);
					curl_setopt($handle, CURLOPT_INFILE, $rFileContentHandle);
				}
			}
		     // curl_setopt($handle, CURLOPT_PUT, true);
		      //$data2 = implode(",", $data);
		     // $file_handle = @fopen($data, 'r');
		     // curl_setopt($handle, CURLOPT_INFILE, $file_handle);
	
			break;
		
		case 'DELETE':
		
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
		
		break;
	
	}

$response = curl_exec($handle);

$response = json_decode($response, true);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

curl_close($handle);

return array(

	'code' => $code,
	
	'data' => $response,

);

}

function dump($array)

{

echo "<pre>" . print_r($array, true) . "</pre>";

}



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

//example to subscribe
//of course assign all the varibles first $foo = 'bar';
//$contactId = addContact($accountId, $clientFolderId, $firstname, $lastname, $email, $password);
//subscribeContactToList($contactId, $listId,$accountId,$clientFolderId,$welcomeMessageId);
?>