<?php
require_once("emsRest.php");
require_once("emsUPLOAD.php");
error_reporting(E_ALL); ini_set('display_errors', 1); 
class emsUploadRestHandler extends emsRest {

	private $EmployeeID ='';
	private $Contact = array();
	private $Education = array();
	
	
	public function __construct($EmployeeID = null) {
        if($EmployeeID)
        {
			$this->EmployeeID =(string) strtoupper($EmployeeID);	
		}
        
    }
    
	
	function getContact($EmployeeID = null,$FILES) {	
		
		
			
		
			if($EmployeeID)
	        {
				$this->EmployeeID =(string) strtoupper($EmployeeID);	
			}
			$Contact = new emsUPLOAD();
			$rawData = $Contact->getContact($this->EmployeeID,$FILES);

			if(empty($rawData)) {
				$statusCode = 404;
				$rawData = array('error' => 'No contact found!');		
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