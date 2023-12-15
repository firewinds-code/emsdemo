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
$result=array();
$myDB =  new MysqliDb();
if(isset($Data['appkey']) && $Data['appkey'] !=' ')
{
	     if( $Data['appkey']=='esicard' || $Data['appkey']=='all')
          {
          	    $EmployeeID=$Data['EmployeeID'];
	            $query = 'Select status,email_address from esicard where EmployeeID="'.$EmployeeID.'" ;';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msgE']="Data  Found.";
			        $result['statusE']=1;
			        $result['esicard']=$res;
				}
				else
				{
				     $result['msgE']="Data Not Found.";
			         $result['statusE']=0;	
				}	        
			}	
			
		   if($Data['appkey']=='appointmentl' || $Data['appkey']=='all')
          {
          	    $EmployeeID=$Data['EmployeeID'];
	            $query = 'Select status,fetcheEmail from appointmentlonline where EmployeeID="'.$EmployeeID.'"';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msgA']="Data  Found.";
			        $result['statusA']=1;
			        $result['appointmentl']=$res;
				}
				else
				{
				     $result['msgA']="Data Not Found.";
			         $result['statusA']=0;	
				}	        
			}	
		
			//validating complete request
		if(!isset($result['esicard']) && !isset($result['appointmentl']))	
		{
			$result['msg']="Not found.";
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

