<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$myDB =  new MysqliDb();
//print_r($Data);
if(isset($Data['appkey']) && $Data['appkey']=='ces'  && isset($Data['lat']) && !empty($Data['lat']) && isset($Data['lng']) && !empty($Data['lng']) && isset($Data['ip']) && !empty($Data['ip']))
{
	
	//Insert data To forgt_password attemp Table.
$QInsert = "INSERT INTO `app_forget_pass_attempt` (`EmployeeID`, `lat`, `lng`, `ip`) VALUES ('".$Data['emp_id']."', '".$Data['lat']."', '".$Data['lng']."', '".$Data['ip']."')";
	
          $responseIn = $myDB->query($QInsert);
        	
        if(empty($myDB->getLastError())){
        		
        	
		 $Queryattendance = 'select secques,secans,DOB,e.EmployeeID,c.mobile from employee_map e inner join View_EmpinfoActive a on e.EmployeeID=a.EmployeeID inner join contact_details c on e.EmployeeID=c.EmployeeID  WHERE e.EmployeeID="'.$Data['emp_id'].'" and DOB="'.$Data['txt_dob'].'" LIMIT 1 ';
	
          $response = $myDB->query($Queryattendance);
          $result=array();
	         if(count ($response)> 0)
		        { 
		        
					 $result['secquestion'] =$response[0]['secques'];
		             $result['secanswer'] =$response[0]['secans'];
		             $result['emp_id'] =$response[0]['EmployeeID'];
		             $result['mobile'] =$response[0]['mobile'];
		             $result['status']=1;
				}
				else
				{
				     $result['msg']="Data Not Found";
			         $result['status']=0;	
				}
				
			}else{
				 $result['msg']="Unable to get data, Please try agian later.";
	             $result['status']=0;
			}
		        
   } else
   {
   	     $result['msg']="Bad Request";
         $result['status']=0;
   }

echo  json_encode($result);


?>

