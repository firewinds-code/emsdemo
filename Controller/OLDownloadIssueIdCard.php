<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
if(isset($_SESSION['__user_logid'])){
	$EmpID=$_REQUEST['EmpID'];
	
	$myDB = new MysqliDb();
	$download_hostory = $myDB->query('select download_history from doc_al_status where EmployeeID="'.$EmpID.'"');
	$download_text = $download_hostory[0]['download_history'].' , '.$_SESSION['__user_Name'].'['.$_SESSION['__user_logid'].']'.'('.date('Y-m-d H:i:s',time()).') ID_Card Downloaded';
	
	$myDB = new MysqliDb();
	$myDB->rawQuery('update doc_al_status set ID_Card=1,download_history = "'.$download_text.'" where EmployeeID="'.$EmpID.'"');
	if($myDB->count>0){
		echo 1;
	}
	
}	
?>