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
//print_r($Data);
if(isset($Data['appkey']) && $Data['appkey']=='hoList')
{
		        $emp_location_id=$Data['locationId'];
	            $query = 'select DateOn, Reason, Associates, Support from ho_list_admin h where location="'.$emp_location_id.'"';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msg']="Data  Found";
			        $result['status']=1;
			        $result['Ho_List']=$res;
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

echo  json_encode($result);


?>

