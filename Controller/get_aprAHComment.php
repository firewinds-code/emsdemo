<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql="select id,EmployeeID,EvaluatorRemark1,EvaluatorRemark2,EvaluatorRemark3,EvaluatorRemark4,EvaluatorRemark5,EvaluatorRemark6,EvaluatorRemark7,EvaluatorRemark8,EvaluatorRemark9,EvaluatorRemark10 from apprisalremarks where MasterId='".$_REQUEST['ID']."' ";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
    foreach($result as $key=>$value)
    {
		foreach($value as $k => $Score)
		{
			echo $Score.'|$|';
		}
    }	
}
else
{
	echo "<script>$(function(){ toastr.error('Applicant Score does not exist.') }); </script>";
}
?>