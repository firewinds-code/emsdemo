<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 

$myDB =  new MysqliDb();
		
	$QueryUpdate = 'UPDATE employee_map SET password ="'.md5($_REQUEST['newpassword']).'" where EmployeeID="'.$_REQUEST['emp_id'].'"' ;
	
		 //$QueryUpdate = $myDB->query($QueryUpdate);
		 $res =$myDB->query($QueryUpdate);
	if (!$myDB->getLastError()) {
	$result['status']=1;
} else {
 $result['status']=0;
}

echo  json_encode($result);
//echo   $resultSends ; 
exit;
?>

