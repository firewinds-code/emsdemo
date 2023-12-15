<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	
ini_set('display_errors', '1'); 
 //header("Content-Type: application/json; charset=UTF-8");
//$_POST = file_get_contents('php://input');
//$Data=json_decode($_POST,true);
$Data=$_POST;

$response = array();
$response['msg']='';

	
if(isset($Data['appkey']) && $Data['appkey']=="submitStatus" && isset($Data['EmpID']) && !empty($Data['EmpID']) )
{
	//insert into  vaccination_data(  `EmpID`,  `EmpName`,  `Vac1` ,  `Vac2` ,  `VacFile`,   `CreatedBy`)values()		
	 $EmpID = $Data['EmpID'];
	 $EmpName = $Data['EmpName'];
	 $Vac1  = $Data['Vac1'];
	 $Vac2  = $Data['Vac2'];	 
	 $CreatedBy  = $Data['CreatedBy'];
	 $nsdt  = $Data['NextSchedule'];
	 $fname="";
	 $fileNameFinal="";
	 if(isset($_FILES['VacFile']) && $_FILES['VacFile']!=null)
	 {
		$fn= $_FILES['VacFile'];
		$fname=$fn['name'];
	  $tempPath  =  $_FILES['VacFile']['tmp_name'];
	  $fileName  =  $_FILES['VacFile']['name'];
	 
	   $dir_locationToSave= __DIR__.'/../Vacination/';
	  
	  $fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); // get  extension
		
	 $fileNameFinal = $EmpID.'_'.date('Y-m-d_His').'.'.$fileExt;					
	move_uploaded_file($tempPath, $dir_locationToSave.$fileNameFinal);
	 }
	 
	 
	$myDB=new MysqliDb();
	$sql="";
	

	//$sql="insert into  vaccination_data(  `EmpID`,  `EmpName`,  `Vac1` ,  `Vac2` ,  `VacFile`,   `CreatedBy`)values('".$EmpID."',  '".$EmpName."',  '".$Vac1."' ,  '".$Vac2."' ,  '".$fname."',   '".$CreatedBy."')";
	$sql="call vaccination_submit('".$EmpID."',  '".$EmpName."',  '".$Vac1."' ,  '".$Vac2."' ,  '".$fileNameFinal."',  '".$nsdt."')";
	$result=$myDB->rawQuery($sql);
		
					if (empty($myDB->getLastError()))
					{
						$response['status']=1;
						$response['msg']='  Data Posted Successfully.';
					}else{
						$response['status']=0;
						$response['msg']='Data not Posted.';
					}
}
else
{						
	$response['status']=0;
	$response['msg']='Bad Request';
}
echo json_encode($response);

?>