<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '1');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);

if(isset($Data['location'])){
	$location=$Data['location'];
}

if($location!="")
{
$sql="SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM issue_master left outer join personal_details on issue_master.handler=personal_details.EmployeeID where issue_master.location='".$location."' limit 1";
 $myDB=new MysqliDb();
	$result = $myDB->query($sql);
	if(count($result)>0){
	
	      	$error = $myDB->getLastError();
			if(empty($error))
	         {
	         	$response['data']=$result;
		        $response['status']=1;
		        $response['msg']='Got data';
		       
	         }
	     	else
	         {
	         	$response['data']='No data';
		        $response['status']=0;
		        $response['msg']='Data not found '.$error;

	         }
			
	}else{
		$response['status']=0;
	  	$response['msg']='Data not found';	
	}	
}else{
	$response['status']=0;
	  $response['msg']='Location is blank';	
} 
echo json_encode($response);		
?>

