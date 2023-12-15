<?php
/* 
A domain Class to demonstrate RESTful web services
*/

require '../../Config/init.php';
require '../../Config/init.php';
require CLS.'MysqliDb.php';
Class emsLogin {
	
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
	public function checkLogin($EmployeeID = null,$Password = null){
		
		
		$myPInfo = new MysqliDb();
				
		$myPInfo->where("EmployeeID",$EmployeeID);
		
		$pData =$myPInfo->get("personal_details",1);
		
		$getPass = null;
		if(count($pData) > 0 && $pData)
		{
			
			$pFNAME  = $pData[0]['FirstName'];
			$pDOB = date('dmY',strtotime($pData[0]['DOB']));
			
			$getPass = $pFNAME.$pDOB;
		}
		
		
		if((strtoupper($getPass) == strtoupper($Password)) && count($pData) > 0 && $pData)
		{
			return array("EmployeeName"=>$pData[0]['EmployeeName'],"EmployeeID"=>$pData[0]['EmployeeID'],"INTEmpID"=>$pData[0]['INTEmpID']);	
		}
		return array(null);
	}
	
	/*public function getEducation($this->EmployeeID){
		
		return $this->Education;
	}	*/
}
?>