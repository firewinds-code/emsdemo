<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data = json_decode($_POST, true);
$myDB =  new MysqliDb();
$result = array();
$token = $EmployeeID = $passwod = $source = $sysip = '';
if (isset($Data['appkey']) && $Data['appkey'] == 'checkauth') {
	$EmployeeID = $Data['EmployeeID'];
	$url = $Data['url'];
	if (isset($Data['token']) && trim($Data['token']) != "" && trim($EmployeeID) != "") {
		$token = $Data['token'];

		// $myDB =  new MysqliDb();
		$Query = "SELECT EmployeeID,token from login_history where EmployeeID=?  order by ID desc limit 1";
		//and token='".$token."'
		// $response = $myDB->query($Query);
		$stmt = $conn->prepare($Query);
		$stmt->bind_param("s", $EmployeeID);
		$stmt->execute();
		$response = $stmt->get_result();
		$response = mysqli_fetch_array($response);
		$mysql_error = $myDB->getLastError();
		if ($response) {
			$rtoken = $response['token'];
			if ($token == $rtoken) {
				$result['status'] = 1;
				$result['msg'] = "Token valid";
			} else {
				$result['status'] = 0;
				$result['msg'] = "Token not valid";
			}
		}
	} else if (isset($Data['passwod']) && trim($Data['passwod']) != "" && trim($EmployeeID) != "") {
		$passwod = $Data['passwod'];
		//echo $passwod='c4ca4238a0b923820dcc509a6f75849b' for 1;
		$myDB =  new MysqliDb();
		$Query = "SELECT EmployeeID from employee_map where EmployeeID=? and password = ?";
		// $response = $myDB->query($Query);
		$stmt = $conn->prepare($Query);
		$stmt->bind_param("ss", $EmployeeID, $passwod);
		$stmt->execute();
		$response = $stmt->get_result();

		$mysql_error = $myDB->getLastError();
		if ($mysql_error == "" and $response->num_rows > 0) {
			$result['status'] = 1;
			$result['msg'] = "Valid";
		} else {
			$result['status'] = 0;
			$result['msg'] = "Invalid User";
		}
	} else {
		$result['status'] = 0;
		$result['msg'] = "Invalid User";
	}
	if (isset($Data['source'])) {
		$source = $Data['source'];
	}
	if (isset($Data['sysip'])) {
		$sysip = $Data['sysip'];
	}
	if ($EmployeeID != "") {
		$inserQuery = "INSERT into dashboard_check_auth set  EmployeeID=?, `password`=?, token=?, source=?, ip_address=?, durl=?";

		$stmt3 = $conn->prepare($inserQuery);
		$stmt3->bind_param("ssssss", $EmployeeID, $passwod, $token, $source, $sysip, $url);
		$inst = $stmt3->execute();
		// echo $insertId = $myDB->getInsertId(); //for last inserted id using mysqliDb
		// $myDB =  new MysqliDb();
		// $response = $myDB->rawQuery($inserQuery);
		$mysql_error = $myDB->getLastError();
	}

	/*if($mysql_error==""){
		$result['capture']=1;
	}*/
} else {
	$result['status'] = 0;
	$result['msg'] = "Bad Request";
}
echo  json_encode($result);
