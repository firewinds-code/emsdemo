<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$target_dir = '';
$EmployeeID = clean($_REQUEST['EmpID']);
$dirLoc = clean($_REQUEST['dirloc']);
if (isset($dirLoc)) {
	$target_dir = ROOT_PATH . $dirLoc;
}
//$EmployeeID='AE022026095';
$flag = $flag1 = $flag2 = $flag3 = 0;
$myDB = new MysqliDb();
$result_ac_validate = $myDB->query("SELECT  distinct(edu_name),edu_file  FROM education_details where EmployeeID= '" . $EmployeeID . "'  and    edu_name in('10th','12th') ");
if (count($result_ac_validate) >= 2) {
	if (file_exists(($target_dir . 'Education/' . $result_ac_validate[0]['edu_file'])) && file_exists(($target_dir . 'Education/' . $result_ac_validate[1]['edu_file']))) {
		$flag = 1;
	} else {
		if (file_exists(($target_dir . 'Edu/' . $result_ac_validate[0]['edu_file'])) && file_exists(($target_dir . 'Edu/' . $result_ac_validate[1]['edu_file']))) {
			$flag = 1;
		} else {
			$flag = 0;
		}
	}
}


$sql = "select doc_type,doc_stype,doc_file from doc_details where EmployeeID= ?   and doc_stype in('Salary Proof','Interview Form','Curriculum Vitae','Undertaking' ,'Aadhar Card')";
$sel = $conn->prepare($sql);
$sel->bind_param("s", $EmployeeID);
$sel->execute();
$doc_validate = $sel->get_result();

$exp_array = "select distinct(exp_type) from experince_details where EmployeeID= ?  and exp_type='Experienced' ";
$select = $conn->prepare($sql);
$select->bind_param("s", $EmployeeID);
$select->execute();
$exp_array = $select->get_result();
/*echo "exprience=".count($exp_array);
	echo "<br>";
	//echo $exp_array[0]['exp_type'];
	//if(isset($exp_array[0]['exp_type']!=""))
	die;*/
if ($exp_array->num_rows > 0) {
	if ($doc_validate->num_rows >= 5) {
		foreach ($doc_validate as $value) {
			if ($value['doc_stype'] != 'Aadhar Card') {
				if (file_exists($target_dir . "Docs/Other/" . $value['doc_file'])) {
					$flag1 = 1;
				} else {
					if ($value['doc_stype'] == 'Curriculum Vitae') {
						if (file_exists($target_dir . "Resume/" . $value['doc_file'])) {
							$flag1 = 1;
						} else {
							$flag1 = 0;
							break;
						}
					} else {
						$flag1 = 0;
						break;
					}
				}
			} else {
				$doctypefilepath = '';
				if ($value['doc_stype'] == 'Aadhar Card') {
					$doctypefilepath = $value['doc_type'];
				}
				if ($doctypefilepath == 'Proof of Identity') {
					if (file_exists($target_dir . "Docs/IdentityProof/" . $value['doc_file'])) {
						$flag2 = 1;
					} else {
						$flag2 = 0;
						break;
					}
				} else
					if ($doctypefilepath == 'Proof of Address') {
					if (file_exists($target_dir . "Docs/AddressProof/" . $value['doc_file'])) {
						$flag2 = 1;
					} else {
						$flag2 = 0;
						break;
					}
				}
			}
		}
	}
} else {
	//echo "doc validate=".count($doc_validate );
	//die;
	if ($doc_validate->num_rows >= 4) {
		foreach ($doc_validate as $value) {
			if ($value['doc_stype'] != 'Aadhar Card') {
				if (file_exists($target_dir . "Docs/Other/" . $value['doc_file'])) {
					$flag1 = 1;
				} else {

					if ($value['doc_stype'] == 'Curriculum Vitae') {
						if (file_exists($target_dir . "Resume/" . $value['doc_file'])) {
							$flag1 = 1;
						} else {
							$flag1 = 0;
							break;
						}
					} else {
						$flag1 = 0;
						break;
					}
				}
			} else {
				$doctypefilepath = '';
				if ($value['doc_stype'] == 'Aadhar Card') {
					$doctypefilepath = $value['doc_type'];
				}
				if ($doctypefilepath == 'Proof of Identity') {
					if (file_exists($target_dir . "Docs/IdentityProof/" . $value['doc_file'])) {
						$flag2 = 1;
					} else {
						$flag2 = 0;
						break;
					}
				} else
					if ($doctypefilepath == 'Proof of Address') {
					if (file_exists($target_dir . "Docs/AddressProof/" . $value['doc_file'])) {
						$flag2 = 1;
					} else {
						$flag2 = 0;
						break;
					}
				}
			}
		}
	} else {
		echo $flag = '3';
		exit;
	}
}

if ($flag == 1 and $flag1 == 1 and $flag2 == 1) {
	echo 1;
} else {
	echo 0;
}
		
		
	//echo 1;
