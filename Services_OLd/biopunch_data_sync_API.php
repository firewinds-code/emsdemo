<?php
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

class rest_API
{
	function insert_data($data)
	{
		header('Content-type: application/json');
		$responseData = json_decode($data, TRUE);
		$psw = 'bachan@123';
		if ($responseData["uid"] == 'bachan' && $responseData["pwd"] == $psw) {
			if (!empty($responseData['Location'])) {
				$EmployeeID = $responseData['EmployeeID'] . "_FB";
				//$Query="Call InsertBiopunch_temp('".$responseData['EmployeeID']."','".$responseData['PunchTime']."','".$responseData['DateOn']."','".$responseData['EmpID']."','".$responseData['Location']."','".$responseData['datasentdt']."')"; 
				$Query = "Call InsertBiopunch_Face('" . $EmployeeID . "','" . $responseData['PunchTime'] . "','" . $responseData['DateOn'] . "','" . $responseData['EmpID'] . "','" . $responseData['Location'] . "','" . $responseData['datasentdt'] . "')";
				$myDB =  new MysqliDb();
				$result = $myDB->query($Query);
				$mysql_error = $myDB->getLastError();
				if (empty($mysql_error)) {

					echo '{"status":"Data Inserted Successfully"}';
					die;
				} else {
					echo '{"status":"Data not Inserted","Error.":"' . $mysql_error . '"}';
					die;
				}
			} else {
				echo '{"error":"Data Empty"}';
				die;
			}
		} else {
			echo '{"status":"fail","error":"Invalid UserID or Password"}';
			die;
		}
	}
}

//$_POST = '{"SRCType":"Site","userid":"7221100","PunchTime":"00:04:41","DateOn":"2021-03-26","createdon":"2021-03-26 00:10:15","EmpID":"CE121622565","CreatedBy":"offline","uid":"bachan","pwd":"bachan"}';

//if(isset($_POST) && !empty($_POST) )


$_POST = file_get_contents('php://input');
if (isset($_POST) && !empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$obj = new rest_API();
	$obj->insert_data($_POST);
} else {
	echo '{"status":"fail","error":"Unauthorized request"}';
	die;
}
//
