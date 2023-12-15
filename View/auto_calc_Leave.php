<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH.'AppCode/nHead.php');
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Auto Calculation :Leave </span>

    
<!--Content Div for all Page End -->  
</div>
<?php

$myDB=new MysqliDb();
$data_exp =$myDB->rawQuery('call get_exceed_leave_oh_data()');
echo $mysql_error = $myDB->getLastError();
$rowCount = $myDB->count;
$alert_msg='';
ini_set('log_errors','1'); 
if($rowCount > 0)
{
	foreach($data_exp as $exp_key=>$exp_val)
	{
		
		$myDB=new MysqliDb();
		//echo 'call get_ops_head("'.$exp_val['EmployeeID'].'")';
		$data_ah = $myDB->query('call get_ops_head("'.$exp_val['EmployeeID'].'")');
		//$test='call get_ops_head("'.$exp_val['EmployeeID'].'")';
		$myDB=new MysqliDb();
		if(isset($data_ah[0]['oh']) && $data_ah[0]['oh']!=""){
			//$test1='call get_calcAtnd_fromDate("'.$data_ah[0]['oh'].'","'.$exp_val['DateCreated'].'")';
			#echo $test1;
			$PDay_dt= $myDB->query('call get_calcAtnd_fromDate("'.$data_ah[0]['oh'].'","'.$exp_val['DateCreated'].'")');
			//$val =  $PDay_dt[0][0]['PDay'];
			if($data_ah[0]['oh'] !='CE07147134')
			{
					
					$myDB=new MysqliDb();
					//$test2='call auto_update_oh_Leave("'.$exp_val['LeaveID'].'","'.$data_ah[0]['oh'].'")';
					$Update_Leave='call auto_update_oh_Leave("'.$exp_val['LeaveID'].'","'.$data_ah[0]['oh'].'")';
					$myDB->rawQuery($Update_Leave);
					//echo 'Leave Updated For '$exp_val['LeaveID'].' of Account Head ='.$data_ah[0]['account_head'].'<br />';
					echo $mysql_error = $myDB->getLastError();
					
			}
		}
		
	}
}
#call get_exceed_exp_data('CE07147134');

echo '<br /> Run for '.count($data_exp).' Employee';

$myDB=new MysqliDb();
$data_exp = $myDB->query('call get_exceed_leave_data()');
echo $mysql_error = $myDB->getLastError();
$rowCount = $myDB->count;
$alert_msg='';
ini_set('log_errors','1'); 
if($rowCount > 0)
{
	foreach($data_exp as $exp_key=>$exp_val)
	{
		
		$myDB=new MysqliDb();
		#$test='call get_account_head("'.$exp_val['leavehistry']['EmployeeID'].'")';
		#echo $test;
		$data_ah = $myDB->rawQuery('call get_account_head("'.$exp_val['EmployeeID'].'")');
		$myDB=new MysqliDb();
		#$test1='call get_calcAtnd_fromDate("'.$data_ah[0][0]['account_head'].'","'.$exp_val['leavehistry']['DateModified'].'")';
		#echo $test1;
		$PDay_dt=$myDB->rawQuery('call get_calcAtnd_fromDate("'.$data_ah[0]['account_head'].'","'.$exp_val['DateModified'].'")');
		//$val =  $PDay_dt[0][0]['PDay'];
		if($data_ah[0]['account_head'] !='CE07147134')
		{
				
				$myDB=new MysqliDb();
				$Update_Leave='call auto_update_Leave("'.$exp_val['LeaveID'].'","'.$data_ah[0]['account_head'].'")';
				$myDB->rawQuery($Update_Leave);
				//echo 'Leave Updated For '$exp_val['leavehistry']['LeaveID'].' of Account Head ='.$data_ah[0]['whole_details_peremp']['account_head'].'<br />';
				echo $mysql_error = $myDB->getLastError();
				
		}
	}
}
#call get_exceed_exp_data('CE07147134');

echo '<br /> Run for '.count($data_exp).' Employee';



?>
