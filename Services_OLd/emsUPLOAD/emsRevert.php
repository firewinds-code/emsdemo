<?php

error_reporting(E_ALL); ini_set('display_errors', 1); 
require_once("emsUploadRestHandler.php");
		
$view = "";
if(isset($_REQUEST["view"]))
	$view = $_REQUEST["view"];

/*
controls the RESTful services
URL mapping
*/
if($_REQUEST["key"] != 'ahSHASNmashmK@kas$3,asmAAssk')
{
	$emsUploadRestHandler = new emsUploadRestHandler();
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
	
	case "Contact":
		// to handle REST Url /mobile/list/
		
		//$uploadfile = $uploaddir . basename($_REQUEST['filedata']);
		/*var_dump($_FILES);
		var_dump($_REQUEST);

		if(move_uploaded_file($_FILES['file']['tmp_name'],$uploaddir.$_FILES['file']['name']))
		{
			echo 'OK';
		}*/
		
		/*
    	copy($_REQUEST['filedata'],"".$_REQUEST['filename']);
    	*/
    	
    	
		$emsUploadRestHandler = new emsUploadRestHandler();
		$emsUploadRestHandler->getContact($_REQUEST['EmployeeID'],$_FILES);
		break;
		
		
		
	/*case "Education":
		// to handle REST Url /mobile/list/
		$emsUploadRestHandler = new emsUploadRestHandler();
		$emsUploadRestHandler->getEducation($_GET['EmployeeID']);
		break;*/

	case "" :
		//404 - not found;
		break;
}
?>