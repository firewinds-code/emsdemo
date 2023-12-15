<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
	// $myDB=new MysqliDb();
	/*$Query="select EmployeeName,EmployeeID from whole_details_peremp  
	where sub_process='".$_REQUEST['sub_process']."' and clientname='".$_REQUEST['clientname']."' order by EmployeeName ";*/

	$sql_qh	 = cleanUserInput($_REQUEST['qh']);
	$isin = 0;
	$emplist = ["CE061930045", "CE121829689", "CE10091236", "CE08134859"];
	foreach ($emplist as $string) {
		if (strpos($sql_qh, $string) !== false) {
			$isin = 1;
			break;
		}
	}
	$sql_usertype = cleanUserInput($_REQUEST['User_type']);
	$sql_client = cleanUserInput($_REQUEST['client']);
	if ($sql_usertype == "Demo") {
		// $Query="select EmployeeName,EmployeeID from whole_details_peremp where client_name='".$sql_client."'  order by EmployeeName ";
		///
		$query = "select EmployeeName,EmployeeID from whole_details_peremp where client_name=?  order by EmployeeName ";

		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $sql_client);
	} else {

		$sql_cmid = cleanUserInput($_REQUEST['cmid']);

		if ($isin == 1) {

			$sqlstr = "select  process  from  new_client_master where cm_id=? ";

			$stmt = $conn->prepare($sqlstr);
			$stmt->bind_param("s", $sql_cmid);

			$stmt->execute();
			$result1234 = $stmt->get_result();
			$count = $result1234->num_rows;
			$res = $result1234->fetch_row();

			// $res =$myDB->query($sqlstr);
			if ($result1234->num_rows > 0) {
				$sql_process = 	clean($res[0]);
				//$Query="select EmployeeName,EmployeeID from whole_details_peremp where cm_id='".$_REQUEST['cmid']."' order by EmployeeName ";
				$Query = "select EmployeeName,EmployeeID from whole_details_peremp where process='" . $sql_process . "' order by EmployeeName ";
				///

				$query = "select EmployeeName,EmployeeID from whole_details_peremp where process=? order by EmployeeName ";

				$stmt = $conn->prepare($query);
				$stmt->bind_param("s", $sql_process);
			} else {
				echo 'EmployeeID NOT EXIST for this Process';
			}
		} else {
			$sql_process = cleanUserInput($_REQUEST['process']);
			$sql_clientname = cleanUserInput($_REQUEST['clientname']);
			$sql_location = cleanUserInput($_REQUEST['location']);
			// $Query="select EmployeeName,EmployeeID from whole_details_peremp  
			// where (process='".$sql_process."' or qh='".$sql_qh."') and clientname='".$sql_clientname."' and location='".$sql_location."'  order by EmployeeName "; 
			///

			$query = "select EmployeeName,EmployeeID from whole_details_peremp  
		where (process=? or qh=?) and clientname=? and location=?  order by EmployeeName ";

			$stmt = $conn->prepare($query);
			$stmt->bind_param("ssss", $sql_process, $sql_process, $sql_clientname, $sql_location);
			$stmt->execute();
			$result1234 = $stmt->get_result();
			$count = $result1234->num_rows;
			if ($result1234->num_rows > 0) {
			}
		}
	}
	//echo $Query;
	// $res =$myDB->query($Query);
	///

	$stmt->execute();
	$res = $stmt->get_result();
	// $count = $res->num_rows;  

	if ($res->num_rows > 0) {
		foreach ($res as     $key => $value) {
			$result[] = $value;
		}
		$result = json_encode($result);
		echo $result;
	} else {
		echo 'EmployeeID NOT EXIST';
	}
} else {
	echo 'ID PLEASE !';
}
