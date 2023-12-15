<?php
/* 
A domain Class to demonstrate RESTful web services
*/
require '../../Config/init.php';
require CLS.'MysqliDb.php'; 
Class emsUPLOAD {
	
	/*private $uploads = array(
		1 => 'Contact Document',  
		2 => 'Education Document',  
		3 => 'Others');
		*/
	
	private $Contact = array();
	private $Education = array();
	/*
		you should hookup the DAO here
	*/
	public function getContact($EmployeeID = null,$FILES = null){
		$myContact = new MysqliDb();
		$myContact->where("EmployeeID",$EmployeeID);
		$this->Contact = $myContact->get("contact_details");
		
		$this->Contact = array();
		$uploaddir = realpath('./') . '/Contact Docs/';
		try
		{
			
		
			if(move_uploaded_file($FILES['file']['tmp_name'],$uploaddir.$FILES['file']['name']))
			{
				$target_file = $uploaddir . basename($_FILES["file"]["name"]);
				$ext = pathinfo($target_file, PATHINFO_EXTENSION);
				$renameFile = time().'_'.$EmployeeID."_ContactDoc_temp.".$ext;
				$renameFileT = $uploaddir.$renameFile;
				if(file_exists($target_file))
				{
					rename($target_file,$renameFileT);
					$this->Contact[0]['file_staus'] = 'OK';
					$this->Contact[0]['file_msg'] = 'File uploaded succesfully'.'|'.$renameFile;
				}
				
				
			}
			else
			{
				$this->Contact[0]['file_staus'] = 'FAIL';
				$this->Contact[0]['file_msg'] = 'Failed to upload file.try again';
			}
	    
	    	//var_dump($this->Contact);
			return $this->Contact[0];
		}
		catch(Exception $e) {
			  return 'Message: ' .$e->getMessage();
		}

	}
	
	/*public function getEducation($this->EmployeeID){
		
		return $this->Education;
	}	*/
}
?>