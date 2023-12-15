<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
	$myDB = new MysqliDb();
	/*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
where  qh='".$_REQUEST['qh']."' order by `Process`;";*/
	$isin = 0;
	$emplist = ["CE01080195"];
	//$emplist=["CE01080195","CE061930045","CE061930050","CE071728286","CE071728536","CE071930074","CE121622091","CE101930165","CE121829689","CE121829697"];	
	foreach ($emplist as $string) {
		if (strpos($_REQUEST['EmployeeID'], $string) !== false) {
			$isin = 1;
			break;
		}
	}

	if ($_REQUEST['user_type'] == 'Demo') {
		$Query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp where client_name='" . $_REQUEST['Client'] . "' order by Process";
	} else {
		if ($_REQUEST['EmployeeID'] == 'CE10091236' || $_REQUEST['EmployeeID'] == 'CE03070003' || $isin == 1) {
			$Query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp  order by Process";
		} else if ($_REQUEST['EmployeeID'] == 'OFF8438' || $_REQUEST['EmployeeID'] == 'OFF8418' || $_REQUEST['EmployeeID'] == 'OFF8408' || $_REQUEST['EmployeeID'] == 'OFF8409' || $_REQUEST['EmployeeID'] == 'OFF8408' || $_REQUEST['EmployeeID'] == '9266882209' || $_REQUEST['EmployeeID'] == '9627329523' || $_REQUEST['EmployeeID'] == '8800838362' || $_REQUEST['EmployeeID'] == '9625762391' || $_REQUEST['EmployeeID'] == '8178433132' || $_REQUEST['EmployeeID'] == '9891988449' || $_REQUEST['EmployeeID'] == '9205541837' || $_REQUEST['EmployeeID'] == '9958602820' || $_REQUEST['EmployeeID'] == '7073695246') {
			$Query = "select concat(t2.client_name,'|',Process,'|',sub_process) Process ,cm_id from new_client_master t1 join client_master t2 on t1.client_name= t2.client_id where t2.client_id=74";
		} else if ($_REQUEST['EmployeeID'] == 'APOLLO1') {
			$Query = "select concat(t2.client_name,'|',Process,'|',sub_process) Process ,cm_id from new_client_master t1 join client_master t2 on t1.client_name= t2.client_id where t2.client_id=127";
		} else if ($_REQUEST['EmployeeID'] == 'DEEPAK' || $_REQUEST['EmployeeID'] == 'DEEPAKM') {
			$Query = "select concat(t2.client_name,'|',Process,'|',sub_process) Process ,cm_id from new_client_master t1 join client_master t2 on t1.client_name= t2.client_id where t2.client_id=14";
		} else if ($_REQUEST['EmployeeID'] == 'MS@TATAAIG.COM') {
			$Query = "select concat(t2.client_name,'|',Process,'|',sub_process) Process ,cm_id from new_client_master t1 join client_master t2 on t1.client_name= t2.client_id where t2.client_id=147";
		} else if ($_REQUEST['EmployeeID'] == 'ANUJBHATT') {
			$Query = "select concat(t2.client_name,'|',Process,'|',sub_process) Process ,cm_id from new_client_master t1 join client_master t2 on t1.client_name= t2.client_id where t2.client_id=129";
		} else if ($_REQUEST['EmployeeID'] == '9654124157' || $_REQUEST['EmployeeID'] == '9650055338' || $_REQUEST['EmployeeID'] == '9555428884') {
			$Query = "select concat(t2.client_name,'|',Process,'|',sub_process) Process ,cm_id from new_client_master t1 join client_master t2 on t1.client_name= t2.client_id where t2.client_id=131";
		} else {
			/*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
		where  Process in (select distinct Process from whole_details_peremp where EmployeeID='".$_REQUEST['EmployeeID']."') order by `Process`";*/

			/*$Query="select distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp
		where ( EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' or ReportTo='".$_REQUEST['EmployeeID']."' or qh='".$_REQUEST['EmployeeID']."' or oh='".$_REQUEST['EmployeeID']."' 
		or th='".$_REQUEST['EmployeeID']."' or Qa_ops='".$_REQUEST['EmployeeID']."')";*/

			/*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
		where  Process in (select distinct Process from whole_details_peremp where ( EmployeeID='".$_REQUEST['EmployeeID']."' or
		 account_head='".$_REQUEST['EmployeeID']."' or ReportTo='".$_REQUEST['EmployeeID']."' or qh='".$_REQUEST['EmployeeID']."' or oh='".$_REQUEST['EmployeeID']."' or th='".$_REQUEST['EmployeeID']."') ) order by `Process`;";*/
			$Query = "select distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp
		where Process='" . $_REQUEST['Process'] . "' or qh='" . $_REQUEST['EmployeeID'] . "' or oh='" . $_REQUEST['EmployeeID'] . "'  or account_head='" . $_REQUEST['EmployeeID'] . "' order by `Process`";
		}
	}


	$res = $myDB->query($Query);
	if ($res) {
		foreach ($res as $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		echo NULL;
	}
} else {
	echo NULL;
}
