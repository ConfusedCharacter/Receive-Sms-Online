<?php

# Receive Sms web service
# localhost/ReceiveSms.php?number=16463386303
# By @ConfusedCharacter
# Url: https://anonymsms.com/

header("Content-type: Application/json");

function GetDataReceiveSms(){
	$ch = curl_init();
	$number = $_GET["number"];
	curl_setopt($ch, CURLOPT_URL, 'https://anonymsms.com/number/'.$number);
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
	$data = str_replace("</b>","",str_replace("<b>","",$data));
	if ($data == ""){
		$jsoned["status"] = false;
		$jsoned["message"] = "Getting anonymsms.com Failed.";
		$jsoned["exception"] = "Failed To Connect host";
		echo json_encode($jsoned, JSON_PRETTY_PRINT);
	}
	$re = '/<td>(\+\d.+)<\/td>\n.*<td class="table-panel__message">(.*(?:[\s\r]+[^<]*)?)<\/td>/';
	preg_match_all($re, $data, $regexed, PREG_SET_ORDER, 0);
	$co = 0;
	if ( $jsoned["status"] ){
		$jsoned["data"] = array();
		foreach ($regexed as $st){
			$n_js = array();
			$n_js["from"] = $st[1];
			$n_js["text"] = $st[2];
			$jsoned["data"][$co] = $n_js;
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
