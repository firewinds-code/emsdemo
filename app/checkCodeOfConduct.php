<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
 if(isset($Data['appkey']) && $Data['appkey'] !='' && $Data['appkey']=='checkemp')
 {              
                $EmployeeID = $Data['EmployeeID'];
          	    $myDB =  new MysqliDb();
	            $query = 'select EmployeeID  from signup_policy_ack where EmployeeID="'.$EmployeeID.'";';
                $res =$myDB->query($query);
                $error = $myDB->getLastError();
                 if( empty($error))
				 {
				 	if(count($res)>0)
				 	{
						$result['msg']="Data  Found.";
			            $result['status']=1;
			            $result['ack']=1;
					}
					else
					{
						 $result['msg']="Data not found.";
				         $result['status']=1;	
				         $result['ack']=0;
					}
				}
				else
				{
				     $result['msg']="Server Error.";
			         $result['status']=0;	
				}	        	
}
else if (isset($Data['appkey']) && $Data['appkey'] !='' && $Data['appkey']=='insert')
{
	        $EmployeeID = $Data['EmployeeID'];
	        $myDB =  new MysqliDb();
			$QueryInsert = "insert into  signup_policy_ack (EmployeeID) values('".$EmployeeID."');";
			$res =$myDB->query($QueryInsert);
			$MysqliError=$myDB->getLastError();
			if (empty($MysqliError)) 
			{
				$result['msg']="Successfully acknowledged.";
			    $result['status']=1;
			}
			else
			{
			 $result['msg']=getLastError();
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

