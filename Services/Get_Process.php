<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
	$Name = $_REQUEST['qh'];
	$isin = 0;
	$emplist = ['CE01080195', 'CE05070052', 'CE0421937241', 'CE032030295', 'CE10091236'];
	foreach ($emplist as $string) {
		if (strpos($Name, $string) !== false) {
			$isin = 1;
			break;
		}
	}

	if ($Name == "OFF8438" || $Name == "7073695246") {
		$myDB = new MysqliDb();
		$Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master)and t1.client_name='74' order by t2.client_name;";
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
	} else if ($Name == "APOLLO1") {
		$myDB = new MysqliDb();
		$Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master)and t1.client_name='127' order by t2.client_name;";
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
	} else if ($Name == "DEEPAK" || $Name == "DEEPAKM") {
		$myDB = new MysqliDb();
		$Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master)and t1.client_name='14' order by t2.client_name;";
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
	} else if ($Name == "ANUJBHATT") {
		$myDB = new MysqliDb();
		$Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master)and t1.client_name='129' order by t2.client_name;";
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
	} else if ($Name == "MS@TATAAIG.COM") {
		$myDB = new MysqliDb();
		$Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master)and t1.client_name='147' order by t2.client_name;";
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
	} else if ($Name == "9654124157" || $Name == "9650055338" || $Name == "9555428884") {
		$myDB = new MysqliDb();
		$Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master)and t1.client_name='131' order by t2.client_name;";
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
	} else if ($isin == 1) {
		$myDB = new MysqliDb();
		$Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master) order by t2.client_name";
		//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
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
		$myDB = new MysqliDb();
		$Query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
where  qh='" . $_REQUEST['qh'] . "' order by `Process`;";
		//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
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
	}
} else {
	echo NULL;
}
