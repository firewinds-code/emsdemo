<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
//table empid,Name, dateon status(0/1) //page add/remove/edit(update status as 0/1)
$empList=array();
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='getAssetList' )
{
	
	
	$myDB=new MysqliDb();
	$qq ="select EmployeeID from asset_verifier where status =1;";
	$response_q = $myDB->rawQuery($qq);
						 	
	if(empty($myDB->getLastError()) && count($response_q) > 0){
						
						
		//Creating Eployee List from associative arrayy.
		foreach($response_q as $key => $empData ){
			array_push($empList ,$empData['EmployeeID'] );
		}
							 		
							 	
		//Validating The Images list
		 if(count($empList) > 0){
		 	$result['status']=1;
			$result['msg']='Found Emp List.';
			$result['empList']=$empList;
		 }else{
		 	$result['status']=0;
			$result['msg']='Data not found.';
		 }
	 
	 
	 }else{
	 	$result['status']=0;
		$result['msg']='Data not found.';
					
	 }
	
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
		
}
echo  json_encode($result);

?>

