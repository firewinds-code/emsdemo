<?php

require_once("emsRest.php");
require_once("emsLogin.php");
ini_set('display_errors',0); 
class emsLoginRestHandler extends emsRest {

	private $EmployeeID ='';
	private $Contact = array();
	private $Education = array();
	
	
	public function __construct($EmployeeID = null) {
        if($EmployeeID)
        {
			$this->EmployeeID =(string) strtoupper($EmployeeID);	
		}
        
    }
    
	
	function checkLogin($EmployeeID = null,$Password = null) {	
		
		
			if($EmployeeID)
	        {
				$this->EmployeeID =(string) strtoupper($EmployeeID);	
			}
			$Contact = new emsLogin();
			$rawData = $Contact->checkLogin($EmployeeID,$Password);

			if(empty($rawData)) {
				$statusCode = 404;
				$rawData = array('error' => 'No Employee found!');		
			} else {
				$statusCode = 200;
			}

			$requestContentType = $_SERVER['HTTP_ACCEPT'];
			
			$this ->setHttpHeaders($requestContentType, $statusCode);
					
			if(strpos($requestContentType,'application/json') !== false){
				$response = $this->encodeJson($rawData);
				echo $response;
			} else if(strpos($requestContentType,'text/html') !== false){
				$response = $this->encodeHtml($rawData);
				echo $response;
			} else if(strpos($requestContentType,'application/xml') !== false){
				$response = $this->encodeXml($rawData);
				echo $response;
			}
			else
			{
				$response = $this->encodeJson($rawData);
				echo $response;
			}
		
	}
	
	public function encodeHtml($responseData) {
	
		$htmlResponse = "<table border='1'>";
		foreach($responseData as $key=>$value) {
    			$htmlResponse .= "<tr><td>". $key. "</td><td>". $value. "</td></tr>";
		}
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><mobile></mobile>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	/*
	public function getMobile($id) {

		$mobile = new Mobile();
		$rawData = $mobile->getMobile($id);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No mobiles found!');		
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}*/
}

?>