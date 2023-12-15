<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
settimestamp('CalcRange11AM','Start');
// Main contain Header file which contains html , head , body , one default form 
$DateTo = date('Y-m-d',strtotime('-2 days'));
$myDB=new MysqliDb();

$data_exp = $myDB->rawQuery("select EmployeeID from whole_details_peremp where cm_id in (307,310,311,312,313,314,196,192,194,257,258,259,260,261,262,263,264,252,253)"); 

/*$data_exp = $myDB->rawQuery("select distinct t1.EmployeeID from calc_atnd_master t1 left outer join bioinout t2 on t1.EmployeeID=t2.EmpID left outer join roster_temp t3 on t1.EmployeeID=t3.EmployeeID left outer join hours_hlp t4 on t1.EmployeeID=t4.EmployeeID left outer join employee_map t5 on t1.EmployeeID=t5.EmployeeID where t1.Month=".intval(date('m',strtotime("yesterday")))." and t1.year=".intval(date('Y',strtotime("yesterday")))." and t5.cm_id != 88 and (t1.D".intval(date('d',strtotime("yesterday")))." is null || t1.D".intval(date('d',strtotime("yesterday")))."='A' || t1.D".intval(date('d',strtotime("yesterday")))."='LANA') and t2.DateOn='".$DateTo."' and (t2.InTime is not null and t2.OutTime is not null) and t3.DateOn='".$DateTo."' and t3.InTime !='WO' and t2.OutTime-t2.InTime!=0 and t4.Month=".intval(date('m',strtotime("yesterday")))." and t4.year=".intval(date('Y',strtotime("yesterday")))." and t4.D".intval(date('d',strtotime("yesterday")))." != '00:00' and t4.D".intval(date('d',strtotime("yesterday")))." != '-'"); */

echo $mysql_error = $myDB->getLastError();
$rowCount = $myDB->count;

if($rowCount > 0)
{
	foreach($data_exp as $exp_key=>$exp_val)
	{
		if(!empty($exp_val['EmployeeID']) && strtotime($DateTo))
		{
			$url = URL.'View/calcRange.php?empid='.$exp_val['EmployeeID'].'&from='.date('Y-m-d',strtotime($DateTo)).'&type=one';
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			$data = curl_exec($curl);
			curl_close($curl);
			echo $data.'<br />';
			//die();
				
		}
		
	}
}
#call get_exceed_exp_data('CE07147134');
settimestamp('CalcRange11AM','End');
echo '<br /> Run for '.count($data_exp).' Employee';

?>