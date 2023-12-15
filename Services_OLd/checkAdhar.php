<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
$empID = $rsnofleaving = '';
$flag = 2;


if (isset($_GET['adhar']) && (trim($_GET['adhar'])) && (strlen($_GET['adhar']) == 12) && (is_numeric($_GET['adhar']) == 12)) {

	$adhar = clean($_GET['adhar']);
}

if ($adhar != '') {
	$rsp = "SELECT distinct t2.EmployeeID,emp_status, dateofjoin,disposition,t1.dov_value from doc_details t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID left join exit_emp t3 on t2.EmployeeID=t3.EmployeeID where doc_stype='Aadhar Card' and  trim(dov_value)=trim(?) and left(t2.EmployeeID,2) !='TE' ";
	// $myDB =  new MysqliDb();
	// $result = $myDB->query($rsp);

	$stmt = $conn->prepare($rsp);
	$stmt->bind_param("i", $adhar);
	$stmt->execute();
	$result = $stmt->get_result();
	$result2 = mysqli_fetch_array($result);
	// print_r($result2['dov_value']);
	// exit;
	// if ($result->num_rows > 0) {
	// }



	$mysql_error = $myDB->getLastError();
	$idProofNo = $result2['dov_value'];
	if ($result->num_rows > 0) {
		foreach ($result as $key => $value) {
			if ($value['emp_status'] == 'Active') {
				$flag = 4;
			}
		}

		if ($flag != 4) {
			foreach ($result as $key => $value) {
				if ($value['disposition'] == 'TER') {
					$flag = 0;
				} else {
					$blockQry = 'SELECT * from emp_block where aadharNo = ?';
					// $myDB =  new MysqliDb();
					// $resultQry = $myDB->query($blockQry);
					$stmt2 = $conn->prepare($blockQry);
					$stmt2->bind_param("i", $adhar);
					$stmt2->execute();
					$resultQry = $stmt2->get_result();
					// $result2 = mysqli_fetch_array($result);
					$mysql_error = $myDB->getLastError();
					if (!empty($resultQry) && $idProofNo != '') {
						$flag = 3;
					}
				}
			}
		}

		echo $flag;
	} else {
		echo "EmployeeID not found";
	}
} else {
	echo $flag;
}
