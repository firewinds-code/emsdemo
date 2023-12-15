<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

if(isset($_SESSION['__user_logid'])){
	$show = '';
	$empName = $_REQUEST['txtEmployeeName'];
	$name=explode('(',$empName);
	$EmpID=$EmployeeID=$_REQUEST['EmpID'];
	$Comment=$_REQUEST['Comment'];
	$validateBy = $_SESSION['__user_logid'];
	$myDB = $myDB=new MysqliDb();
	$Update='update doc_al_status set validate=1,validateby="'.$validateBy.'",validatetime = now(),comment="'.$Comment.'" where EmployeeID="'.$EmpID.'" ';
	$body="";
	$myDB->rawQuery($Update);
	$mysql_error = $myDB->getLastError();
	if($myDB->count>0){
		$myDB=new MysqliDb();
		$selectCount=$myDB->rawQuery("Select EmployeeID from appointmentlonline Where EmployeeID='".$EmployeeID."' ");
		if($myDB->count<1){
			$myDB=new MysqliDb();
			$select_email_array=$myDB->rawQuery("select mobile,emailid,b.cm_id from contact_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID='".$EmployeeID."'");
			
			$myDB=new MysqliDb();
			$insert_Query=$myDB->rawQuery("Insert Into appointmentlonline set EmployeeID='".$EmployeeID."',EmpName='".$name[0]."',cm_id='".$select_email_array[0]['cm_id']."',date='".date('Y-m-d')."',status='0'");
			include('../View/appointmentLetter_download1.php');
			
		}
	}
	echo 1;
}	
?>