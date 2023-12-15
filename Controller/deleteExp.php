<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
//echo " select releiving_experience_doc,appointment_offerletter_doc, salaryslip_bankstatement_doc from experince_details where exp_id='".$_REQUEST['ID']."' ";

$selectaql=$myDB->query("select releiving_experience_doc,appointment_offerletter_doc, salaryslip_bankstatement_doc from experince_details  where exp_id='".$_REQUEST['ID']."' ");

	$mysql_error=$myDB->getLastError();
	if(isset($selectaql[0]['releiving_experience_doc'])){
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/erpm/Docs/Experience/".$selectaql[0]['releiving_experience_doc']))
		{
			@unlink($_SERVER['DOCUMENT_ROOT']."/erpm/Docs/Experience/".$selectaql[0]['releiving_experience_doc']);
		}
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/erpm/Docs/offerletter/".$selectaql[0]['appointment_offerletter_doc']))
		{
			@unlink($_SERVER['DOCUMENT_ROOT']."/erpm/Docs/offerletter/".$selectaql[0]['appointment_offerletter_doc']);
		}	
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/erpm/Docs/salaryslip/".$selectaql[0]['salaryslip_bankstatement_doc']))
		{
			@unlink($_SERVER['DOCUMENT_ROOT']."/erpm/Docs/salaryslip/".$selectaql[0]['salaryslip_bankstatement_doc']);
		}
		
		}
	
	$sql='delete from experince_details where exp_id='.$_REQUEST['ID'];
		$myDB=new MysqliDb();
		$result=$myDB->rawQuery($sql);	
		echo 'done|<b>file Deleted Successfully</b>';
			
	
?>

