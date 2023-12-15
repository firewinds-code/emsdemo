<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
if(isset($_SESSION['__user_logid'])){
	$EmpID=$_REQUEST['EmpID'];
	$Comment=$_REQUEST['Comment'];
	$validateBy = $_SESSION['__user_logid'];
	$myDB = $myDB=new MysqliDb();
	$Update='update doc_al_status set validate=2,validateby="'.$validateBy.'",validatetime = now(),comment="'.$Comment.'" where EmployeeID="'.$EmpID.'" ';
	$myDB->rawQuery($Update);
	if($myDB->count>0){
		echo 1;
	}
	
}	
?>