<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$myDB=new MysqliDb();
$sql = "select ifnull(t2.ctc,'0') as ctc,ifnull(t2.pli_ammount,'0') as pli_ammount,ifnull(t2.AppraisalMonth,'NA') as AppraisalMonth,ifnull(t2.pli_percent,'0') as pli_percent,ifnull(t2.min_wages,'0') as min_wages,ifnull(t2.basic,'0') as basic,ifnull(t2.hra,'0') as hra,ifnull(t2.convence,'0') as convence,ifnull(t2.sp_allow,'0') as sp_allow,ifnull(t2.gross_sal,'0') as gross_sal,ifnull(t2.pf,'0') as pf,ifnull(t2.esis,'0') as esis,ifnull(t2.pf_employer,'0') as pf_employer,ifnull(t2.esi_employer,'0') as esi_employer,ifnull(t2.professional_tex,'0') as professional_tex,ifnull(t2.net_takehome,'0') as net_takehome,t2.pf_status,t2.payrolltype,t2.pli_status from apprisalmaster t1 join salary_details t2 on t1.EmployeeId=t2.EmployeeID where t1.id='".$_REQUEST['ID']."' order by t2.modifiedon desc limit 1";
$myDB=new MysqliDb();
$result=$myDB->query($sql);
$mysql_error=$myDB->getLastError();
if(count($result) > 0 && $result)
{
    foreach($result as $key=>$value)
    {
    	foreach($value as $k => $Details)
		{
			echo $Details.'|$|';
		}
    }	
}
else
	{
		echo 'No Comment ';
		
	}
?>