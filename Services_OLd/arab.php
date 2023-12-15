<?php

require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data = json_decode($_POST, true);

if (isset($Data['EmployeeID']) && $Data['EmployeeID'] != "" && isset($Data['month']) && $Data['month'] != "" && isset($Data['year']) && $Data['year'] != "") {
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$year = $Data['year'];
	$month = $Data['month'];
	$EmployeeID = $Data['EmployeeID'];

	$CurrontMonthDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	echo $attnd = "SELECT D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 FROM calc_atnd_master  where Year = ? AND Month = ? AND EmployeeID = ?";
	// $resattnd = $myDB->query($attnd);
	$stmt = $conn->prepare($attnd);
	$stmt->bind_param("sss", $year, $month, $EmployeeID);
	$stmt->execute();
	$resattnd = $stmt->get_result();
	$resattnd = mysqli_fetch_array($resattnd);


	$hours_hlp = "SELECT D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 FROM hours_hlp  where Year = ? AND Month = ? AND EmployeeID = ?";
	// $reshours_hlp = $myDB->query($hours_hlp);

	$stmt1 = $conn->prepare($hours_hlp);
	$stmt1->bind_param("sss", $year, $month, $EmployeeID);
	$stmt1->execute();
	$reshours_hlp = $stmt1->get_result();



	$bio = "SELECT EmpID,DateOn,CAST(MIN(`biopunchcurrentdata`.`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`biopunchcurrentdata`.`PunchTime`),MIN(`biopunchcurrentdata`.`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`biopunchcurrentdata`.`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` FROM biopunchcurrentdata where YEAR(DateOn) = ? AND MONTH(DateOn) = ? AND Empid = ? group by Dateon ,EmpID;";
	//$bio="SELECT Empid,cast(DateOn as date) as  DateOn, cast( min(PunchTime) as time) InTime, cast( max(PunchTime) as time) OutTime FROM biopunchcurrentdata  where YEAR(DateOn) = '".$year."' AND MONTH(DateOn) = '".$month."' AND Empid = '".$EmployeeID."' group by  Empid ,cast(DateOn as date) order by DateOn";
	// $resbio = $myDB->query($bio);

	$stmt2 = $conn->prepare($bio);
	$stmt2->bind_param("sss", $year, $month, $EmployeeID);
	$stmt2->execute();
	$resbio = $stmt2->get_result();

	//print_r($resbio);exit;
	$rost = " SELECT InTime as roasterIn, OutTime as roasterOut,DateOn FROM roster_temp  where YEAR(DateOn) = ? AND MONTH(DateOn) = ? AND EmployeeID = ? order by DateOn";
	// $resrost = $myDB->query($rost);

	$stmt3 = $conn->prepare($rost);
	$stmt3->bind_param("sss", $year, $month, $EmployeeID);
	$stmt3->execute();
	$resrost = $stmt3->get_result();

	$attandanceList = array();
	$r = $b = 0;
	for ($i = 1; $i <= $CurrontMonthDay; $i++) {
		$fdate = $year . '-' . $month . '-' . $i;
		$data['id'] = $i;
		$data['dayName'] = date("l", strtotime($fdate));
		/*attnd start*/
		if ($resattnd) {
			$data['attandance'] = $resattnd['D' . $i];
		} else {
			$data['attandance'] = 'NA';
		}

		/*attnd end*/
		/*net hours*/
		if (($reshours_hlp->num_rows) > 0) {
			$data['netHours'] = $reshours_hlp[0]['D' . $i];
			/*net hours end*/
		} else {
			$data['netHours']  = '00:00';
		}
		$data['date'] =  $fdate;
		/*bio matric*/

		if (($resbio->num_rows) > 0 && ($resbio) > $b) {

			$dateon =  date('d', strtotime($resbio[$b]['DateOn']));
			if ($dateon == $i) {
				$data['InTime'] =  $resbio[$b]['InTime'];
				$data['OutTime'] = $resbio[$b]['OutTime'];
				$b++;
			} else {
				$data['InTime'] = '00:00';
				$data['OutTime'] = '00:00';
			}
		} else {
			$data['InTime'] = '00:00';
			$data['OutTime'] = '00:00';
		}
		/*bio matric end*/

		if (($resrost->num_rows) > 0 && ($resrost->numr_rows) > $r) {
			$dateon =  date('d', strtotime($resrost[$r]['DateOn']));
			if ($dateon == $i) {
				$data['roasterIn'] =  $resrost[$r]['roasterIn'];
				$data['roasterOut'] = $resrost[$r]['roasterOut'];
				$r++;
			} else {
				$data['roasterIn'] = '00:00';
				$data['roasterOut'] = '00:00';
			}
		} else {
			$data['roasterIn'] =  '00:00';
			$data['roasterOut'] = '00:00';
		}

		$attandanceList[] = $data;
	}

	if (count($attandanceList) > 0) {
		$rosterData['message'] = 'success';
	} else {
		$rosterData['message'] = 'fail';
	}

	$rosterData['EmployeeID'] = $EmployeeID;
	$rosterData['year'] = $year;
	$rosterData['month'] = $month;
	$rosterData['attandanceList'] = $attandanceList;
} else {
	$rosterData['message'] = 'Invalid Data';
}
echo json_encode($rosterData);
