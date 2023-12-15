<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql = "call apr_GetDataID('".$_REQUEST['EmpID']."')";
//$sql="select id, EmployeeName, EmployeeId, Doj, WarningLetter, WarningCount, LastDateFill, Q1, Q2, Q3, Q4, Q6, `Process`, SubProcess,Cm_id, AH, ReportTo,
// RatingPerPMS, RatingManagr, RatingAH, RatingHR,PromotionRecomend,PromotionPost,AHStatus,HRStatus,CreatedOn,CreatedBy,HoldForMonth,PostponeForMonth,DisputeFlag,NeedTraining,Relocate from apprisalmaster where id='".$_REQUEST['ID']."'";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
    echo $result[0]['id'];	
}
/*else
	{
		echo 'No Comment ';
		
	}*/
?>