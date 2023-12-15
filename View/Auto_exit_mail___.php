<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
#require(ROOT_PATH.'AppCode/nHead.php');
include_once(__dir__ . '/../Services/sendsms_API.php');

error_reporting(E_ALL);
//ini_set('display_errors', 0);
ini_set('display_errors', 1);
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';


$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
$ResultSMS = $emailStatus = $msg = $response = $emailAddress = '';
function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$sq1 = "insert into scheduler(modulename,type)values('" . $module . "','" . $type . "');";
	$myDB->query($sq1);
}
settimestamp('Auto_Exit_Mail', 'Start');
$myDB = new MysqliDb();
$chk_task = $myDB->query('select EmployeeID,location from whole_dump_emp_data where cast(dol as date)=cast(date_add(now(), interval -1 day) as date) and des_id in (5,7,8,10,13,14,15,16,22) and disposition in ("IR","RES")');

//$chk_task=$myDB->query('select EmployeeID,location from whole_details_peremp where EmployeeID="CE12102224";');
//$tablename='whole_details_peremp';
$my_error = $myDB->getLastError();


$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$myDB = new MysqliDb();
		$rst = $myDB->rawQuery('call manage_auto_mail_exit();');
		$Joinee_EmpID = $value['EmployeeID'];
		$Joinee_loc = $value['location'];
		//echo $Joinee_EmpID . '<br/>';

		$myDB = new MysqliDb();
		$chk_task1 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="' . $Joinee_loc . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";');


		$my_error = $myDB->getLastError();

		foreach ($chk_task1 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}

		$myDB = new MysqliDb();
		$chk_task2 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="' . $Joinee_loc . '" and t1.des_id in (1,5,7,8,10,13,14,15,16,22,23) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";');


		$my_error = $myDB->getLastError();

		foreach ($chk_task2 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}
	}
}


$myDB = new MysqliDb();
$chk_task = $myDB->query('select EmployeeID,location,client_name from whole_dump_emp_data where cast(dol as date)=cast(date_add(now(), interval -2 day) as date) and des_id in (1,2,3,4,6,11) and disposition in ("IR","RES");');

//$tablename='whole_details_peremp';
$my_error = $myDB->getLastError();


$table = '';
if (count($chk_task) > 0 && $chk_task) {
	foreach ($chk_task as $key => $value) {
		$myDB = new MysqliDb();
		$rst = $myDB->rawQuery('call manage_auto_mail_exit();');

		$Joinee_EmpID = $value['EmployeeID'];
		$Joinee_loc = $value['location'];
		$Joinee_client = $value['client_name'];

		$myDB = new MysqliDb();
		$chk_task1 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location="' . $Joinee_loc . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";');

		//$tablename='whole_details_peremp';
		$my_error = $myDB->getLastError();

		foreach ($chk_task1 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}


		$myDB = new MysqliDb();
		$chk_task2 = $myDB->query('select t1.EmployeeID,t2.ofc_emailid from whole_details_peremp t1 inner join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.location!="' . $Joinee_loc . '" and t1.client_name ="' . $Joinee_client . '" and t1.des_id not in (9,12) and ofc_emailid is not null and ofc_emailid !="" and t1.EmployeeID not like "EXT%";');

		//$tablename='whole_details_peremp';
		$my_error = $myDB->getLastError();

		foreach ($chk_task2 as $key => $value) {
			$rst = $myDB->rawQuery('insert into exit_mail_temp set rec_empid="' . $value['EmployeeID'] . '",rec_email_id="' . $value['ofc_emailid'] . '",empid="' . $Joinee_EmpID . '"');
		}
	}
}

settimestamp('Auto_Exit_Mail', 'End');
