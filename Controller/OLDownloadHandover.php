<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
if(isset($_SESSION['__user_logid'])){
	$EmpID=$_REQUEST['EmpID'];
	$validateBy = $_SESSION['__user_logid'];
	$myDB = new MysqliDb();
	$myDB->rawQuery('update doc_al_status set handover=1,handoverby="'.$validateBy.'",handovertime = now() where EmployeeID="'.$EmpID.'"');
	//echo 'update doc_al_status set handover=1,handoverby="'.$validateBy.'",handovertime = now() where EmployeeID="'.$EmpID.'"';
	if($myDB->count>0){
		echo 1;
	}
	
}	
?>