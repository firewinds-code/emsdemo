<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql="SELECT id, EmployeeId, ApplicantScore5_1, ApplicantScore5_2, ApplicantScore5_3, ApplicantScore5_4, ApplicantScore5_5, ApplicantScore5_6, ApplicantScore5_7, ApplicantScore5_8, ApplicantScore5_9, ApplicantScore5_10, EvaluatorsScore5_1, EvaluatorsScore5_2, EvaluatorsScore5_3, EvaluatorsScore5_4, EvaluatorsScore5_5, EvaluatorsScore5_6, EvaluatorsScore5_7, EvaluatorsScore5_8, EvaluatorsScore5_9, EvaluatorsScore5_10, HRScore5_1, HRScore5_2, HRScore5_3, HRScore5_4, HRScore5_5, HRScore5_6, HRScore5_7, HRScore5_8, HRScore5_9, HRScore5_10, AVGScore5_1, AVGScore5_2, AVGScore5_3, AVGScore5_4, AVGScore5_5, AVGScore5_6, AVGScore5_7, AVGScore5_8, AVGScore5_9, AVGScore5_10, CreatedOn FROM apprisalque where MasterId='".$_REQUEST['ID']."' ";
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