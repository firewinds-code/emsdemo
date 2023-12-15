<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$month = 8;
$year=2020;
$myDB = new MysqliDb();

$rs_emp =$myDB->query("select EmployeeID, D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31, Month, Year from calc_atnd_master where month='".$month."' and year='".$year."'");


//$date = '2016-09-01';//date('Y-m-d',time());
$monday_counter = array();
$rosterType= array();
$val='';
if(count($rs_emp) > 0)
{
	foreach($rs_emp as $rs_key=>$rs_val)
	{
		$EmpID = $atnd = $dateon = $empname ='';
		
		$rst_contact = $myDB->rawQuery('select EmployeeName from personal_details where EmployeeID= "'.$rs_val['EmployeeID'].'" limit 1');
		$empname = $rst_contact[0]['EmployeeName'];
		for($i=1;$i<=31;$i++)
		{
			$present = $leave = $lwp = $ulwp = 0;
			$col = 'D'.$i;
			if($rs_val[$col] !='' && $rs_val[$col] !='-')
			{
				$atnd = trim($rs_val[$col]);
				//echo $atnd;	
				
				if(substr($atnd,0,1)=="P" || $atnd=="WO" || $atnd =="HO")
				{
					$present = 1;
				}
				else if($atnd=="L" || $atnd=="H" || $atnd=="CO")
				{
					$leave = 1;
				}
				else if($atnd=="LWP" || $atnd=="HWP")
				{
					$lwp = 1;
				}
				else if($atnd=="LANA")
				{
					$ulwp = 1;
				}
				$dateon=$year.'-'.$month.'-'.$i;
				$myDB=new MysqliDb();
				$sq1="insert into employee_data_atnd (report_date, EmpID, EmpName, Presenet, `Leave`,leave_without_pay,unapprove_leave_withoutpay)values('".date('Y-m-d', strtotime($dateon))."','".$rs_val['EmployeeID']."','".addslashes($empname)."','".$present."','".$leave."','".$lwp."','".$ulwp."');";
				$myDB->query($sq1);
			}
			
		
			
		}
		
		
		
	}
}

echo 'Complete';

?>