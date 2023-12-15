<?php
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb_replica.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$Data=array();
$flag=array();
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);		
ini_set('display_errors', '1');
//var_dump($Data);
$EmployeeID=$key='';
$date='';
if(isset($Data['EmpID']) && $Data['date']!="" ){
	$EmployeeID=$Data['EmpID'];
	$date=$Data['date'];
	$key=$Data['key'];
}
if($key=='roster'){

	if($EmployeeID!="" && $date!=""){
		 $query="SELECT InTime as roasterIn, OutTime as roasterOut,DateOn FROM roster_temp  where DateOn='".$date."' AND EmployeeID = '".$EmployeeID."' ";	

		$myDB = new MysqliDb();
		$flag = $myDB->query($query);
		if(count($flag)<1)
		{
			$flag['status']=0;
			$flag['msg']='Data not found ';
			
		}
						
	}
}else{
	$flag['status']=0;
	$flag['msg']='Please add correct roster key';
	
}
if(count($flag)>0){
	$dataarray=json_encode($flag);
	print_r($dataarray);
}
?>