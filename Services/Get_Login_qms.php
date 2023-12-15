<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS . 'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
	$myDB = new MysqliDb();
	//$abcd=urldecode($_REQUEST['refrance']);
	$clientid = '';
	/*$loginid = strtoupper($_REQUEST['LoginId']);
	if($loginid == "BHARATPE")
	{
		$clientid='74'; 
	}*/

	if (strtoupper($_REQUEST['LoginId']) == "OFF8438" || strtoupper($_REQUEST['LoginId']) == "APOLLO1" || strtoupper($_REQUEST['LoginId']) == "7073695246" || strtoupper($_REQUEST['LoginId']) == "ANUJBHATT" || strtoupper($_REQUEST['LoginId']) == "MS@TATAAIG.COM") {
		$Query = "Call D2_check_login_qmsqh('" . $_REQUEST['client'] . "')";
	} else {
		$Query = "Call D2_check_login_qms('" . $_REQUEST['client'] . "')";
	}



	//$Query="Call D2_check_login_qmsqh('".$_REQUEST['client']."','".$_REQUEST['access_level']."')";
	//echo $Query;
	/*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
where  qh='".$_REQUEST['qh']."' order by `Process`;";*/
	//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
	$res = $myDB->query($Query);
	if ($res) {
		foreach ($res as $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		//echo '';
	}
} else {
	//echo '';
}
