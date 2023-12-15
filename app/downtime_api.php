<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	
ini_set('display_errors', '1'); 
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$response['msg']='';
$myDB=new MysqliDb();
$EmpID=$Data['EmpID'];	
$Process=$Data['Process'];
$DateFrom=$Data['DTFrom'];
$DateTo=$Data['DTTo'];
$TotalDT=$Data['TotalDT'];
$RequestTo=$Data['ReqTO'];
$LoginDate=$Data['LoginDate'];
$EmployeeComment=$Data['EmpComment'];
$FAID =$Data['FAID'];
$FAStatus ="Pending";
$RTID =$Data['RTID'];
$RTStatus ="Pending";
$RequestType =$Data['Request_type'];
$ITticketID =$Data['IT_ticketid'];	
	if(isset($Data) && count($Data)>0)
	{
		if(isset($Data['appkey']) && $Data['appkey']=="dwt")
		{
		    $Inser_downtime='call sp_InsertDTReq("'.$EmpID.'","'.$Process.'","'.$DateFrom.'","'.$DateTo.'","'.$TotalDT.'","'.$RequestTo.'","'.$LoginDate.'","'.$EmployeeComment.'","'.$FAID.'","'.$FAStatus.'","'.$RTID.'","'.$RTStatus.'","'.$RequestType.'","'.$ITticketID.'")'; 
		   $result = $myDB->query($Inser_downtime);
	       $mysql_error= $myDB->getLastError();
	      if(empty($mysql_error))
	         {
	         	$response['data']= $result;
		        $response['status']=1;
		        $response['msg']='Successfully add Down Time request';
	         }
	     else
	         {
	         	$response['data']='Not Found';
		        $response['status']=0;
		        $response['msg']='Please try again for this request down time '.$mysql_error;

	         }
	
        }else{
        	$response['data']='Not Found';
        	$response['status']=0;
		    $response['msg']='Key does not match';
        }
    }
    else{
    		$response['data']='Not Found';
        	$response['status']=0;
		    $response['msg']='data not set';
        }
 echo json_encode($response);       

?>