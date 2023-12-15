<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$myDB =  new MysqliDb();
//print_r($Data);
if(isset($Data['appkey']) && $Data['appkey']=='downtimeapprovalemplist')
{
	      $EmployeeID=$Data['EmployeeID'];      
	      if($EmployeeID != "")
	      {		  	
	      $downtimeemplist = 'select  Process from downtimereqid1  where QualityID="'.$EmployeeID.'" OR TrainingID = "'.$EmployeeID.'" OR OpsID = "'.$EmployeeID.'" OR HRID = "'.$EmployeeID.'" OR ITID = "'.$EmployeeID.'" limit 1 ;';
          $response = $myDB->query($downtimeemplist);
          $result=array();
	         if(count ($response)> 0)
		        { 
		             $result['msg']="Data Found";
		             $result['status']=1;	            
				}
				else
				{
				     $result['msg']="Data Not Found";
			         $result['status']=0;	
				}	        
         }
       else
         {	
	        $result['msg']="Bad Request";
		    $result['status']=0;
         }
}
else
{
        $result['msg']="Bad Request";
		$result['status']=0;
}
echo  json_encode($result);
?>

