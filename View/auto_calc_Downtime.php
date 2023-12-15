<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH.'AppCode/nHead.php');
$myDB = new MysqliDb();
$data_exp = $myDB->query('call get_exceed_downtime_first_level()');
echo $myDB->getLastError();
$alert_msg = '';
//ini_set('log_errors','1'); 
if (count($data_exp) > 0) {
	foreach ($data_exp as $exp_key => $exp_val) {

		$myDB = new MysqliDb();
		$PDay_dt = $myDB->query('call get_calcAtnd_fromDate("' . str_replace(' ', '', $exp_val['FAID']) . '","' . $exp_val['LoginDate'] . '")');

		$val =  $PDay_dt[0]['PDay'];
		if (str_replace(' ', '', $exp_val['FAID']) != 'CE07147134') {
			if (trim($exp_val['FAID']) != 'CE01145570' && trim($exp_val['FAID']) != 'CE081511111' && trim($exp_val['FAID']) != 'CE091829551' && trim($exp_val['FAID']) != 'CE0321936339' && trim($exp_val['FAID']) != 'CE0322943713' && trim($exp_val['FAID']) != 'CE0622945490' && trim($exp_val['FAID']) != 'CE0921940248') {
				$myDB = new MysqliDb();
				$Update_Leave = 'call auto_downtime_approve_1level("' . trim($exp_val['ID']) . '","' . str_replace(' ', '', $exp_val['FAID']) . '")';
				$myDB->query($Update_Leave);
				//echo $exp_val['ID'].';,';
				//echo 'Leave Updated For '$exp_val['leavehistry']['LeaveID'].' of Account Head ='.$data_ah[0]['whole_details_peremp']['account_head'].'<br />';
				echo $myDB->getLastError();
			}
		}
	}
}
#call get_exceed_exp_data('CE07147134');

echo '<br /> Run for ' . count($data_exp) . ' Employee';
//var_dump($data_exp);
$myDB = new MysqliDb();
$data_exp = $myDB->query('call get_exceed_downtime_second_level()');
echo $myDB->getLastError();
$alert_msg = '';
//ini_set('log_errors','1'); 
if (count($data_exp) > 0) {
	foreach ($data_exp as $exp_key => $exp_val) {

		$myDB = new MysqliDb();
		$PDay_dt = $myDB->query('call get_calcAtnd_fromDate("' . trim($exp_val['RTID']) . '","' . $exp_val['ModifiedOn'] . '")');
		$val =  $PDay_dt[0]['PDay'];
		if (trim($exp_val['RTID']) != 'CE07147134' && trim($exp_val['RTID']) != 'CE01145570' && trim($exp_val['RTID']) != 'CE081511111' && trim($exp_val['RTID']) != 'CE091829551' && trim($exp_val['RTID']) != 'CE0321936339' && trim($exp_val['RTID']) != 'CE0322943713' && trim($exp_val['FAID']) != 'CE0622945490' && trim($exp_val['FAID']) != 'CE0921940248') {

			$myDB = new MysqliDb();
			$Update_Leave = 'call auto_downtime_approve_2level("' . trim($exp_val['ID']) . '","' . trim($exp_val['RTID']) . '")';
			$myDB->query($Update_Leave);
			//echo 'Leave Updated For '$exp_val['leavehistry']['LeaveID'].' of Account Head ='.$data_ah[0]['whole_details_peremp']['account_head'].'<br />';
			//echo $Update_Leave.';,';
			echo $myDB->getLastError();
		}
	}
}
#call get_exceed_exp_data('CE07147134');

echo '<br /> Run for ' . count($data_exp) . ' Employee';
