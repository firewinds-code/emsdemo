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
$response = array();
$response['msg']='';
$myDB=new MysqliDb();
$emp_id=$Data['EmployeeID'];	
$belongsto=$Data['belongsto'];
$issue=$Data['query_detail'];
$handler=$Data['handler'];
$employeeName=$Data['employeeName'];
$designation=$Data['designation'];
$remark=trim($Data['remark']);
$location=$Data['location'];
$MobNo =$Data['MobNo']; 
$concern_off =$Data['concern_off'];
$committed_with =$Data['committed_with'];

	if(isset($Data) && count($Data) > 0   )
	{
		if(isset($Data['appkey']) && $Data['appkey']=="addreq" && $emp_id!="" && $remark!="")
		{
	 	  $checkActiveID="SELECT cm_id FROM ems.ActiveEmpID where EmployeeID='".$emp_id."' ";
	 	  $myDB =  new MysqliDb();
		  $response =$myDB->rawQuery($checkActiveID);
		  $error = $myDB->getLastError();
	 	  if(empty($error) && count($response)!=0)
	 	  {
			if(strlen($remark)>250 && strlen($remark)<1000 )
			{
				
				if(strstr($remark,'>') ){
					$remark=str_replace('>','greater than',$remark);
				}
				if(strstr($remark,'<')){
					$remark=str_replace('<','less than',$remark);
				}
			}	
		  $queryh='call add_issueticket("'.$emp_id.'","'.$belongsto.'","'.$issue.'","'.$handler.'","'.addslashes($remark).'","Manual","'.$MobNo.'","'.$committed_with.'","'.$concern_off.'");';
		   	$result = $myDB->query($queryh);
	      	$error = $myDB->getLastError();
	      
			if(empty($error))
	         {
	         	include('sendmail.php');//Sending mail successful.
		        $response['status']=1;
		        $response['msg']='Request added successfully and Mail send successfully.';
		       
	         }
	     	else
	         {
		        $response['status']=0;
		        $response['msg']='Request not added';

	         }

		}
		else
		  {
			 $result['status']=0;
			 $result['msg']="You are inactive.";		
		  }
			echo json_encode($response);
        }
    }
?>
