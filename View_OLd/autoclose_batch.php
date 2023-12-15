<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH.'AppCode/nHead.php');
// Global variable used in Page Cycle


$myDB = new MysqliDb();
$Insert = "update batch_status t1 inner join batch_master t2 on t1.batch_no=t2.batch_no and t1.cm_id=t2.cm_id set status='Close',modifiedon=now(), modifiedby='Server' where t1.status ='Assign to TH' and t2.BacthID in(select BatchID from status_table where cast(InTraining as date)<date_add(cast(now() as date),interval -2 day) group by BatchID)";
$myDB = new MysqliDb();
$myDB->rawQuery($Insert);
$mysql_error = $myDB->getLastError();

if (empty($mysql_error)) {

	echo "Close";
} else {
	echo "Not Close";
}
