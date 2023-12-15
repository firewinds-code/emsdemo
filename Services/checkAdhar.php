<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb_replica.php');
$empID = $rsnofleaving = '';
$flag = 2;
if (isset($_GET['adhar']) && trim($_GET['adhar']) != '') {
	$rsp = "select distinct t2.EmployeeID,emp_status, dateofjoin,disposition,t1.dov_value from doc_details t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID left join exit_emp t3 on t2.EmployeeID=t3.EmployeeID where doc_stype='Aadhar Card' and  trim(dov_value)=trim('" . $_GET['adhar'] . "') and left(t2.EmployeeID,2) !='TE' ";
	$myDB =  new MysqliDb();
	$result = $myDB->query($rsp);
	$mysql_error = $myDB->getLastError();
	//$idProofNo = $result[0]['dov_value'];
	//print_r($rsp);

	if (!empty($result) && $result[0]['EmployeeID'] != "") {
		$idProofNo = $result[0]['dov_value'];
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
					$blockQry = 'Select * from emp_block where aadharNo="' . $_GET['adhar'] . '"';
					$myDB =  new MysqliDb();
					$resultQry = $myDB->query($blockQry);
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
