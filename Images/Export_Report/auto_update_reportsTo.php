<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*For ReportsTo Update  */
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
function checkActive($empId)
{
	$myDB=new MysqliDb();
	$queryCheck=$myDB->rawQuery("select emp_status from employee_map where EmployeeID='".$empId."'");
	if(count($queryCheck)>0)
	{
		foreach($queryCheck as $queryCheck_val)
		{
			
			if($queryCheck_val['emp_status']=='Active')
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
	}
}
settimestamp('Auto Update ReportsTo','Start');
$myDB=new MysqliDb();
echo $Q="select distinct EmployeeID from exit_emp  e where cast(e.createdon as date)=curdate() - INTERVAL 1 DAY";
$fetch_reportsTo=$myDB->rawQuery($Q);
/*$fetch_reportsTo=mysql_query("select distinct EmployeeID from exit_emp  e where cast(e.createdon as date)=curdate() - INTERVAL 1 DAY");*/
if(count($fetch_reportsTo)>0)
{	
$reportTo="";
/*while($reportToArray=mysql_fetch_array($fetch_reportsTo))
	{*/	
	foreach($fetch_reportsTo as $fetch_reports_val)
	{
	$reportTo=$fetch_reports_val['EmployeeID'];
		$query=$myDB->rawQuery("select EmployeeID,ReportTo from status_table where ReportTo='".$reportTo."' ");
		/*$query=$myDB->rawQuery("select EmployeeID,ReportTo from status_table where ReportTo='CE101512484' ");*/
	    echo "total Employee ID of Reports to $reportTo =".count($query);
		echo "<br>";
		if(count($query)>0)
		{
			
			foreach($query as $query_val)
			{
				$myDB=new MysqliDb();
				$empid=$query_val['EmployeeID'];
				$EXIT_ReportTo=$query_val['ReportTo'];
				$qurydata=$myDB->rawQuery("select oh,account_head from whole_details_peremp where EmployeeID='".$empid."'");
			
				if(count($qurydata)>0)
				{
					foreach($qurydata as $qurydata_val)
					{
					//$myDB=new MysqliDb();
					//$ohArray=mysql_fetch_array($qurydata);
						$oh=$qurydata_val['oh'];
						$account_head=$qurydata_val['account_head'];
						if(checkActive($oh)==1)
						{
							$myDB=new MysqliDb();
					 		$myDB->rawQuery("update status_table set ReportTo='".$oh."' where EmployeeID ='".$empid."' and ReportTo ='".$EXIT_ReportTo."'");
					 		echo "update status_table set ReportTo='".$oh."' where EmployeeID ='".$empid."' and ReportTo ='".$EXIT_ReportTo."'";
						 }
						 else
						 {
						 	if(checkActive($account_head)==1){
						 		$myDB=new MysqliDb();
								$myDB->rawQuery("update status_table set ReportTo='".$account_head."' where EmployeeID ='".$empid."' and ReportTo ='".$EXIT_ReportTo."'");
								echo "update status_table set ReportTo='".$account_head."' where EmployeeID ='".$empid."' and ReportTo ='".$EXIT_ReportTo."'";
							}
						 }
					}
				}
				 
			}
		}
	}
}
/* 
 For Qa_ops  Update*/   
$myDB=new MysqliDb();
$QaopsID="";
$Qaops_query=$myDB->rawQuery("select  EmployeeID from exit_emp  e where cast(e.createdon as date)=curdate() - INTERVAL 1 DAY");
if(count($Qaops_query)>0)
{
	
	foreach($Qaops_query as $Qaops_query_val)
		{
		$QaopsID=$Qaops_query_val['EmployeeID'];
		//echo "select $Qaops_query,Qa_ops from status_table where Qa_ops ='".$QaopsID."';";
		$myDB=new MysqliDb();
		$query=$myDB->rawQuery("select EmployeeID,Qa_ops from status_table where Qa_ops ='".$QaopsID."'");
		if(count($query)>0)
		{
			$empid="";
			
			foreach($query as $query_val)
			{	
				$myDB=new MysqliDb();
				$empid=$query_val['EmployeeID'];
				$EXIT_Qa_ops=$query_val['Qa_ops'];
				$qurydataQA=$myDB->rawQuery("select qh,account_head from whole_details_peremp where EmployeeID='".$empid."'");
				if(count($qurydataQA)>0)
				{
					foreach($qurydataQA as $qurydataQA_val)
					{
						$qh=$qurydataQA_val['qh'];
						$QHaccount_head=$qurydataQA_val['account_head'];
						if(checkActive($qh)==1)
						{
							$myDB=new MysqliDb();
						 	$myDB->rawQuery("update status_table set Qa_ops='".$qh."' where EmployeeID ='".$empid."' and Qa_ops ='".$EXIT_Qa_ops."'");
						 		echo "update status_table set Qa_ops='".$qh."' where EmployeeID ='".$empid."' and Qa_ops ='".$EXIT_Qa_ops."'";
						 }
						 else
						 {
						 	if(checkActive($QHaccount_head)==1)
						 	{
						 		$myDB=new MysqliDb();
								$myDB->rawQuery("update status_table set Qa_ops='".$QHaccount_head."' where EmployeeID ='".$empid."' and Qa_ops ='".$EXIT_Qa_ops."'");
						echo "update status_table set Qa_ops='".$QHaccount_head."' where EmployeeID ='".$empid."' and Qa_ops ='".$EXIT_Qa_ops."'";
							}
						 }
					}
				}
			}
		}		
	}
settimestamp('Auto Update ReportsTo','END');
}
?>