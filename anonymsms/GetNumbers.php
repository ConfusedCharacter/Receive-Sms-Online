<?php

# Get Number web service
# localhost/GetNumbers.php
# By @ConfusedCharacter
# Url: https://anonymsms.com/

header("Content-type: Application/json");

function GetDataNumbers(){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://anonymsms.com/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko','Pragma: no-cache','Accept: */*'));
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

$jsoned = array( "status" => true );

try{
	$data = GetDataReceiveSms();
	if ($data == ""){
		$jsoned["status"] = false;
		$jsoned["message"] = "Getting anonymsms.com Failed.";
		$jsoned["exception"] = "Failed To Connect host";
		echo json_encode($jsoned, JSON_PRETTY_PRINT);
	}
	$re = '/data-clipboard-text="(\+\d.+)"/';
	preg_match_all($re, $data, $regexed, PREG_SET_ORDER, 0);
	$co = 0;
	if ( $jsoned["status"] ){
		$jsoned["data"] = array();
		foreach ($regexed as $st){
			$jsoned["data"][$co] = $st[1];
			$co++;
		}
		echo json_encode($jsoned, JSON_PRETTY_PRINT);
	}

}catch(Exception $e){
	$jsoned["status"] = false;
	$jsoned["message"] = "UnExpected Error";
	$jsoned["exception"] = $e->getMessage();
	echo json_encode($jsoned, JSON_PRETTY_PRINT);
}
