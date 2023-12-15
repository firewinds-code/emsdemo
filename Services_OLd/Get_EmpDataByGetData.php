<?php

require_once(__dir__ . '/../Config/init.php');
//require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;

$myDB = new MysqliDb();
$Query = "select t1.id,t1.EmployeeID, t1.MobileNo,t3.EmployeeName,t3.Process,t3.doj from rosterednotbio t1 join contact_details t2 on t2.mobile = t1.MobileNo join whole_details_peremp t3 on t1.EmployeeID = t3.EmployeeID where t1.dateon=curdate() and t1.flag=0 order by t1.id limit 1;";
//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";

$res = $myDB->query($Query);

if ($res) {
	// $sqlInsertException = "Update rosterednotbio set Flag=1 where ID = '" . $res[0]['id'] . "';";
	// $flag = $myDB->rawQuery($sqlInsertException);

	//$error = $myDB->getLastError();
	//$rowCount = $myDB->count;

	$ID = $res[0]['id'];
	$sqlInsertException = "Update rosterednotbio set Flag=1 where ID = ? ";

	$stmt = $conn->prepare($sqlInsertException);
	$stmt->bind_param("i", $ID);
	if (!$stmt) {
		echo "failed to run";
		die;
	}
	$res = $stmt->execute();
	$count = $res->num_rows;

	//if ($rowCount > 0) {
	if ($count > 0) {
		foreach ($res as $key => $value) {
			$EmployeeID = $value['EmployeeID'];
			$result[] = $value;
		}
		// $Query = "select InTime, OutTime from roster_temp where EmployeeID='" . $EmployeeID . "' and dateon=curdate();";
		// $res1 = $myDB->query($Query);

		$EmployeeID = cleanUserInput($EmployeeID);
		$Query = "select InTime, OutTime from roster_temp where EmployeeID=? and dateon=curdate()";
		$stmt = $conn->prepare($Query);
		$stmt->bind_param("s", $EmployeeID);
		if (!$stmt) {
			echo "failed to run";
			die;
		}
		$stmt->execute();
		$res1 = $stmt->get_result();



		if ($res1) {
			foreach ($res1 as $key => $value) {
				//$EmployeeID = $value['EmployeeID'];
				array_push($result, $value);
				//$result[] = $value;
			}
		} else {
			$Query = "select null as InTime, null as OutTime;";
			$res1 = $myDB->query($Query);
			if ($res1) {
				foreach ($res1 as $key => $value) {
					//$EmployeeID = $value['EmployeeID'];
					array_push($result, $value);
					//$result[] = $value;
				}
			}
		}

		$Query = "select (select OCCURRENCES('" . $EmployeeID . "','" . date('m') . "','" . date('Y') . "',',L,') ) as `Current Month Leave`
, (select OCCURRENCES('" . $EmployeeID . "','" . date('m') . "','" . date('Y') . "',',A,') ) as `Current Month Absent`
, (select OCCURRENCES('" . $EmployeeID . "','" . date('m') . "','" . date('Y') . "','LWP') ) as `Current Month LWP`
, (select OCCURRENCES('" . $EmployeeID . "','" . date('m') . "','" . date('Y') . "','WONA') ) as `Current Month WONA`
, (select OCCURRENCES('" . $EmployeeID . "','" . date('m') . "','" . date('Y') . "',',H,') ) as `Current Month H`;";
		$res1 = $myDB->query($Query);
		if ($res1) {
			foreach ($res1 as $key => $value) {
				//$EmployeeID = $value['EmployeeID'];
				array_push($result, $value);
			}
		}

		$Query = "select (select OCCURRENCES('" . $EmployeeID . "','" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "',',L,') ) as `Previous Month Leave`, (select OCCURRENCES('" . $EmployeeID . "','" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "',',A,') ) as `Previous Month Absent`
, (select OCCURRENCES('" . $EmployeeID . "','" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "','LWP') ) as `Previous Month LWP`
, (select OCCURRENCES('" . $EmployeeID . "','" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "','WONA') ) as `Previous Month WONA`
, (select OCCURRENCES('" . $EmployeeID . "','" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "',',H,') ) as `Previous Month H`;";
		$res1 = $myDB->query($Query);
		if ($res1) {
			foreach ($res1 as $key => $value) {
				//$EmployeeID = $value['EmployeeID'];
				array_push($result, $value);
			}
		}

		$result = json_encode($result);
		echo $result;
	} else {
		echo NULL;
	}
} else {
	echo NULL;
}
