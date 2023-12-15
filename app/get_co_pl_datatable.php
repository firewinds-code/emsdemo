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
if(isset($Data['appkey']) && $Data['appkey'] =='COPL_data')
{	
    if(isset ($Data['EmployeeID']) && $Data['EmployeeID']!="" && isset($Data['isPL']) && $Data['isPL']!="" )
       {     	
            	$EmpID = $Data['EmployeeID'];
            	$date_To=$Data['Month'];
            	$date_From=$Data['Year'];
            	$isPL=$Data['isPL'];
            	if($isPL==1)
            	{
					$myDB = new MysqliDb();
	    			$pl = $myDB->query('call get_paid_history_byEmployee("'.$EmpID.'","'.$date_To.'","'.$date_From.'")');
	    			$mysql_error = $myDB->getLastError();
	    			if (empty($myDB->getLastError()))
						{
							$result['status']=1;
							$result['msg']='Data found';
							$result['PLRecords']=$pl;
						}
	 
			       else
			           {
							$result['status']=0;
							$result['msg']='Data not found.';
					  }
				}
				else
				{
					$myDB = new MysqliDb();
	    			$co = $myDB->query('call get_co_history_byEmployee("'.$EmpID.'")');
	    			$mysql_error = $myDB->getLastError();
	    			if (empty($myDB->getLastError()))
						{
							$result['status']=1;
							$result['msg']='Data found';
							$result['CORecords']=$co;
						}
	 
			       else
			           {
							$result['status']=0;
							$result['msg']='Data not found.';
					  }
				}
            	
		        
      }
	else
	 {
	     $result['msg']="Set emplyeeID.";
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

