<?php
error_reporting(E_ALL); ini_set('display_errors', 1); 

require_once("emsLoginRestHandler.php");
		
$view = "";
if(isset($_REQUEST["view"]))
	$view = $_REQUEST["view"];


/*
controls the RESTful services
URL mapping
*/

if($_REQUEST["key"] != 'coge1234sshlYtkksj100912')
{
	
	$emsUploadRestHandler = new emsLoginRestHandler();
	
	$statusCode = 401;
	$rawData = array('error' => 'Unauthorized ! ');


	$requestContentType = $_SERVER['HTTP_ACCEPT'];
	$emsUploadRestHandler ->setHttpHeaders($requestContentType, $statusCode);
			
	if(strpos($requestContentType,'application/json') !== false){
		$response = $emsUploadRestHandler->encodeJson($rawData);
		echo $response;
	} else if(strpos($requestContentType,'text/html') !== false){
		$response = $emsUploadRestHandler->encodeHtml($rawData);
		echo $response;
	} else if(strpos($requestContentType,'application/xml') !== false){
		$response = $emsUploadRestHandler->encodeXml($rawData);
		echo $response;
	}
	
	exit;
}


switch($view){
	
	case "Login":
		
		$emsLogin = new emsLoginRestHandler();
		
		$emsLogin->checkLogin($_REQUEST['EmployeeID'],$_REQUEST['Password']);
		


		break;
				
	case "" :
		//404 - not found;
		break;
}
?>