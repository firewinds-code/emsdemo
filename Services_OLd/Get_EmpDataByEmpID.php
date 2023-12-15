<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {

	if (isset($_REQUEST['EmpID']) && (trim($_REQUEST['EmpID'])) && (strlen($_REQUEST['EmpID']) <= 15)) {
		if ((substr($_REQUEST['EmpID'], 0, 2) == 'ce') || (substr($_REQUEST['EmpID'], 0, 2) == 'mu')) {
			$EmpID = clean($_REQUEST['EmpID']);
		}
	}


	$myDB = new MysqliDb();
	$Query = "SELECT t2.EmployeeID, t2.EmployeeName,t2.Process,t2.DOJ from whole_details_peremp t2 where t2.EmployeeID=?;";
	//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
	// $res = $myDB->query($Query);
	$stmt = $conn->prepare($Query);
	$stmt->bind_param("s", $EmpID);
	$stmt->execute();
	$res = $stmt->get_result();
	// $res = mysqli_fetch_array($res);
	// $count = $res->num_rows;
	// if ($res->num_rows > 0) {
	// }
	// print_r($res);
	// exit;
	if ($res) {
		foreach ($res as $key => $value) {
			$EmployeeID = $value['EmployeeID'];
			$result[] = $value;
		}
		// print_r($result);
		// exit;

		$Query2 = "SELECT InTime, OutTime from roster_temp where EmployeeID = ? and dateon=curdate();";

		// $res1 = $myDB->query($Query);
		$stmt2 = $conn->prepare($Query2);
		$stmt2->bind_param("s", $EmployeeID);
		$stmt2->execute();
		$res1 = $stmt2->get_result();
		// print_r($res1);
		// exit;
		$count = $res1->num_rows;
		if ($res1->num_rows > 0) {
			// if ($res1) {
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

		echo $Query = "select (select OCCURRENCES(?,'" . date('m') . "','" . date('Y') . "',',L,') ) as `Current Month Leave`
, (select OCCURRENCES(?,'" . date('m') . "','" . date('Y') . "',',A,') ) as `Current Month Absent`
, (select OCCURRENCES(?,'" . date('m') . "','" . date('Y') . "','LWP') ) as `Current Month LWP`
, (select OCCURRENCES(?,'" . date('m') . "','" . date('Y') . "','WONA') ) as `Current Month WONA`
, (select OCCURRENCES(?,'" . date('m') . "','" . date('Y') . "',',H,') ) as `Current Month H`;";
		die;
		// $res1 = $myDB->query($Query);
		$stmt2 = $conn->prepare($Query);
		$stmt2->bind_param("sssss", $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID);
		$stmt2->execute();
		$res1 = $stmt2->get_result();
		if ($res1) {
			foreach ($res1 as $key => $value) {
				//$EmployeeID = $value['EmployeeID'];
				array_push($result, $value);
			}
		}

		$Query = "select (select OCCURRENCES(?,'" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "',',L,') ) as `Previous Month Leave`, (select OCCURRENCES(?,'" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "',',A,') ) as `Previous Month Absent`
, (select OCCURRENCES(?,'" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "','LWP') ) as `Previous Month LWP`
, (select OCCURRENCES(?,'" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "','WONA') ) as `Previous Month WONA`
, (select OCCURRENCES(?,'" . date('m', strtotime('last month')) . "','" . date('Y', strtotime('last month')) . "',',H,') ) as `Previous Month H`;";
		// $res1 = $myDB->query($Query);
		$stmt2 = $conn->prepare($Query);
		$stmt2->bind_param("sssss", $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID);
		$stmt2->execute();
		$res1 = $stmt2->get_result();
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
