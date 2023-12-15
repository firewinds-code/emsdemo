<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_REQUEST['ohid']) && $_REQUEST['ohid'] != "") {

	$sql = "call AssetAPI('" . $_REQUEST['ohid'] . "')";

	$myDB = new MysqliDb();
	$result = $myDB->query($sql);
	if (count($result) > 0) {
		$salArray = array();
		$i = 0;
		foreach ($result as $val) {

			$salArray[$i]['EmployeeID'] = $val['EmployeeID'];
			$salArray[$i]['EmpName'] = $val['EmpName'];
			$salArray[$i]['location'] = $val['location'];
			$salArray[$i]['oh'] = $val['oh'];
			$salArray[$i]['OH_Name'] = $val['OH_Name'];
			$salArray[$i]['cm_id'] = $val['cm_id'];
			$salArray[$i]['client_name'] = $val['client_name'];
			$salArray[$i]['process'] = $val['process'];
			$salArray[$i]['sub_process'] = $val['sub_process'];
			$salArray[$i]['AdminID'] = $val['AdminID'];
			$salArray[$i]['Admin_Name'] = $val['Admin_Name'];
			$salArray[$i]['ITID'] = $val['ITID'];
			$salArray[$i]['ITName'] = $val['ITName'];
			$salArray[$i]['mobile'] = $val['mobile'];
			$salArray[$i]['emailid'] = $val['emailid'];
			$salArray[$i]['doj'] = $val['doj'];
			$salArray[$i]['reportto'] = $val['reportto'];
			$salArray[$i]['Reportto_Name'] = $val['Reportto_Name'];
			$salArray[$i]['address'] = $val['address'];
			$salArray[$i]['house_no'] = $val['house_no'];
			$salArray[$i]['latitude'] = $val['latitude'];
			$salArray[$i]['longitude'] = $val['longitude'];
			$salArray[$i]['district'] = $val['district'];
			$salArray[$i]['tehsil'] = $val['tehsil'];
			$salArray[$i]['city'] = $val['city'];
			$salArray[$i]['state'] = $val['state'];
			$salArray[$i]['zip'] = $val['zip'];
			$salArray[$i]['AdminEmailID'] = $val['AdminEmailID'];
			$salArray[$i]['OHEmailID'] = $val['OHEmailID'];
			$salArray[$i]['ITEmailID'] = $val['ITEmailID'];
			$salArray[$i]['AHEmailID'] = $val['AHEmailID'];
			$i++;
		}

		if (count($salArray) > 0) {
			echo json_encode($salArray);
		}
	}
}
