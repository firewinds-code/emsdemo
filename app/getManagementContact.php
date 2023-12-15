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
if(isset($Data) && count($Data)>0)
{
	if( $Data['appkey']=='getmngcontact' && $Data['appkey']!='')
          {
          	     $gender=$Data['gender'];
          	     if(strtoupper($gender)=="FEMALE")
          	     {
				 	$query = "select EmployeeName,designation,EmployeeID,mobile from emp_dt_map where EmployeeID in ('CE07147134','CE03070003','CE05101779')order by designation,EmployeeName asc";
				 }
	             else
	             {
				 	$query = "select EmployeeName,designation,EmployeeID,mobile from emp_dt_map where EmployeeID in ('CE07147134','CE03070003') order by designation asc";
				 }
                $res =$myDB->rawQuery($query);
	                if($res)
					 {
						$result['msg']="Data  Found.";
				        $result['status']=1;
				        $result['managementContact']=$res;
					}
					else
					{
					     $result['msg']="Data Not Found.";
				         $result['status']=0;	
					}	        
			}
		else
		{
			 $result['status']=0;
		     $result['msg']='Key does not match.';
		}
}
else
{	
    $result['status']=0;
    $result['msg']='Data not set.';
 }
 echo json_encode($result);  
?>