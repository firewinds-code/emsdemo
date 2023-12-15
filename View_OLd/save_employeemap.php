<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
require CLS . '../lib/PHPMailer-master/class.phpmailer.php';
require CLS . '../lib/PHPMailer-master/PHPMailerAutoload.php';
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$clean_temp_check = clean($_SESSION["__ut_temp_check"]);
$alert_msg = '';
$EmployeeID = $btnShow = $df_id = '';
$data_update_alert = '';
$experience_detail = '';
$source_recruitment_Desc = '';
$interview_id = '';
$createBy = clean($_SESSION['__user_logid']);
$usertype = clean($_SESSION['__user_type']);
$mailler_msg = '';
$cm_id = '';
$sub_process1 = '';

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_emp_map = cleanUserInput($_POST['txt_empmap_dept']);
		$dept = (isset($clean_emp_map) ? $clean_emp_map : null);
		$clean_empmap_client = cleanUserInput($_POST['txt_empmap_client']);
		$client = (isset($clean_empmap_client) ? $clean_empmap_client : null);
		$clean_empmap_process = cleanUserInput($_POST['txt_empmap_process']);
		$process = (isset($clean_empmap_process) ? $clean_empmap_process : null);
		$clean_subprocess = cleanUserInput($_POST['txt_empmap_subprocess']);
		$cm_id = $subprocess = (isset($clean_subprocess) ? $clean_subprocess : null);
		$clean_desg = cleanUserInput($_POST['txt_empmap_desg']);
		$desg = (isset($clean_desg) ? $clean_desg : null);
		$clean_hde = cleanUserInput($_POST['hdepartment']);
		$hdept = (isset($clean_hde) ? $clean_hde : null);
		$clean_hde_sign = cleanUserInput($_POST['hdesignation']);
		$hdesg = (isset($clean_hde_sign) ? $clean_hde_sign : null);
		$clean_doj = cleanUserInput($_POST['txt_empmap_doj']);
		$doj = (isset($clean_doj) ? $clean_doj : null);
		$clean_pass = cleanUserInput($_POST['txt_empmap_pass']);
		$pass = (isset($clean_pass) ? $clean_pass : null);

		/*$pass = $_obj_ed_ems->Encrypt($pass);*/
		$clean_level = cleanUserInput($_POST['txt_empmap_level']);
		$level = (isset($clean_level) ? $clean_level : null);
		$clean_function = cleanUserInput($_POST['txt_empmap_function']);
		$function_ = (isset($clean_function) ? $clean_function : null);
		$clean_refid = cleanUserInput($_POST['txt_Personal_Ref_id']);
		$source_recruitment_id = (isset($clean_refid) ? $clean_refid : null);
		$clean_ref_desc = cleanUserInput($_POST['txt_Personal_Ref_Desc']);
		$source_recruitment_Desc = (isset($clean_ref_desc) ? $clean_ref_desc : null);
		$clean_refrname = cleanUserInput($_POST['txt_Personal_Ref_rName']);
		$consutancy_id = (isset($clean_refrname) ? $clean_refrname : null);
		//$cm_id=(isset($_POST['cm_id'])? $_POST['cm_id'] : null);
	}
} else {
	$dept = $client = $process = $subprocess = $desg = $doj = $pass = $level = $function_ = $source_recruitment_id = $source_recruitment_Desc = $consutancy_id = $emptype = '';
}


/*--------elect rtype from salary by rinku-----------*/
$rt_type = "NA";
$emp_salary = 0;
$location = $loc = '';
$clean_empid = cleanUserInput($_REQUEST['empid']);
if (isset($clean_empid)) {
	$EmpID = $clean_empid;
	$getQuery = 'select rt_type,ctc from salary_details where EmployeeID = ? limit 1';
	$stmt = $conn->prepare($getQuery);
	$stmt->bind_param("s", $EmpID);
	$stmt->execute();
	$resultBy = $stmt->get_result();
	// $resultBy = $myDB->rawQuery($getQuery);
	foreach ($resultBy as $key => $value) {
		$rt_type = $value['rt_type'];
		$emp_salary = $value['ctc'];
	}


	$EmployeeID = strtoupper($clean_empid);
	$sql = 'select location from personal_details where EmployeeID = ?';
	$stmtLoc = $conn->prepare($sql);
	$stmtLoc->bind_param("s", $EmployeeID);
	$stmtLoc->execute();
	$result = $stmtLoc->get_result();
	$row = $result->fetch_row();
	// echo $row[0];
	// print_r($row);
	// die;
	$rowloc = clean($row[0]);
	if ($result->num_rows > 0) {
		if (isset($rowloc)) {
			$loc = $rowloc;
		}
	}

	if ($loc == "7" || $loc == "1" || $loc == "2" || $loc == "3" || $loc == "4") {
		if (substr($EmployeeID, 0, 2) != 'TE') {
			if ($loc == "7") {
				if (substr($EmployeeID, 0, 3) == 'CEK') {
					$emptype = "Cogent";
				} else if (substr($EmployeeID, 0, 3) == 'FBK') {
					$emptype = "Flipkart";
				} else if (substr($EmployeeID, 0, 3) == 'CCE') {
					$emptype = "CCE";
				}
			} else {
				if (substr($EmployeeID, 0, 2) == 'CE' || substr($EmployeeID, 0, 2) == 'AE' || substr($EmployeeID, 0, 2) == 'RS' || substr($EmployeeID, 0, 2) == 'MU' || substr($EmployeeID, 0, 2) == 'OC') {
					$emptype = "Cogent";
				} else if (substr($EmployeeID, 0, 3) == 'CCE') {
					$emptype = "CCE";
				}
			}
		}
	}
}
$EMS_CenterName = '';
$dir_location = '';
$clean_emptype = cleanUserInput($_POST['employment_type']);
if (isset($clean_emptype) && $clean_emptype != "") {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		if ($clean_emptype == "1") {
			$locationRaw = rawurlencode('Noida C121');
			$EMS_CenterName = 'Noida';
		} else if ($clean_emptype == "2") {
			$locationRaw = rawurlencode('Mumbai');
			$EMS_CenterName = 'Mumbai';
		} else if ($clean_emptype == "3") {
			$locationRaw = rawurlencode('Meerut');
			$EMS_CenterName = 'Meerut';
			$dir_location = 'Meerut/';
		} else if ($clean_emptype == "4") {
			$locationRaw = rawurlencode('Bareilly');
			$EMS_CenterName = 'Bareilly';
			$dir_location = "Bareilly/";
		} else if ($clean_emptype == "5") {
			$locationRaw = rawurlencode('Vadodara');
			$EMS_CenterName = 'Vadodara';
			$dir_location = "Vadodara/";
		} else if ($clean_emptype == "6") {
			$locationRaw = rawurlencode('Mangalore');
			$EMS_CenterName = 'Mangalore';
			$dir_location = "Manglore/";
		} else if ($clean_emptype == "7") {
			$locationRaw = rawurlencode('Bangalore');
			$EMS_CenterName = 'Bangalore';
			$dir_location = "Bangalore/";
		} else if ($clean_emptype == "8") {
			$locationRaw = rawurlencode('Nashik');
			$EMS_CenterName = 'Nashik';
			$dir_location = "Nashik/";
		} else if ($clean_emptype == "9") {
			$locationRaw = rawurlencode('Anantapur');
			$EMS_CenterName = 'Anantapur';
			$dir_location = "Anantapur/";
		}
	}
}
/*--------End seleection  rtype from salary by rinku-----------*/
//Check Employee is exist or not
$clean_emid = clean($_REQUEST['empid']);
$clean_employeeid = cleanUserInput($_POST['EmployeeID']);
if (isset($clean_emid) && !isset($clean_employeeid)) {
	$EmployeeID = $clean_emid;
	$getDetails = 'call get_empmap("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	$predate = $cm_id = "";
	if ($result_all) {
		$cm_id = $result_all[0]['cm_id'];
		$df_id = $result_all[0]['df_id'];
		$predate = $doj = $result_all[0]['dateofjoin'];
		$pass = $result_all[0]['password'];
		/*if(!empty($pass) && $_obj_ed_ems->Decrypt($pass))
		{
			$pass = $_obj_ed_ems->Decrypt($pass);
		}*/
		$level = $result_all[0]['emp_level'];

		$call_cmid = 'call get_clientdata_bycmid("' . $cm_id . '")';
		$myDB = new MysqliDb();
		$rst_client = $myDB->query($call_cmid);

		if ($rst_client) {
			$dept = $rst_client[0]['dept_id'];
			$client = $rst_client[0]['client_name'];
			$process = $rst_client[0]['process'];
			$sub_process1 = $rst_client[0]['sub_process'];
		}

		$call_dfid = 'select des_id,function_id from df_master where df_id=("' . $df_id . '")';
		$myDB = new MysqliDb();
		$rst_df = $myDB->query($call_dfid);

		if ($rst_df) {

			$desg = $rst_df[0]['des_id'];
			$function_ = $rst_df[0]['function_id'];
		}
	}



	$getDetails = 'call get_personal("' . $EmployeeID . '")';
	$myDB = new MysqliDb();
	$result_all = $myDB->query($getDetails);
	if ($result_all) {
		$source_recruitment_id = $result_all[0]['ref_id'];
		$source_recruitment_Desc = $result_all[0]['ref_txt'];
		$interview_id = $result_all[0]['INTID'];
	} else {
		echo "<script>$(function(){ toastr.error('Wrong Employee To Search') });window.location='" . URL . "'</script>";
	}
} elseif (isset($clean_employeeid) && $clean_employeeid != '') {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$EmployeeID = $clean_employeeid;
	}
}
// echo $cm_id;

if ($cm_id > 0) {
	$myDB = new MysqliDb();
	//echo 'call get_clientdata_bycmid('.$cm_id.')';
	$call_subprocess_array = $myDB->query('call get_clientdata_bycmid(' . $cm_id . ')');
	$sub_process1 = $call_subprocess_array[0]['sub_process'];
}
$clean_emp_save = cleanUserInput($_POST['btn_empmap_Save']);
$clean_rttype = cleanUserInput($_POST['rt_type']);
// if (isset($clean_emp_save) && $EmployeeID != '') {
if (isset($_POST['btn_empmap_Save']) && $EmployeeID != '') {

	// echo "dddddddddd";
	// print_r($_POST);
	// die;
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		if (isset($clean_rttype) and $clean_rttype != 'NA') {
			$clean_emp_salary = cleanUserInput($_POST['emp_salary']);
			$rt_type = $clean_rttype;
			$emp_IDorg = $EmployeeID;
			$validate = 0;

			if ($clean_temp_check != 'ADMINISTRATOR') {

				// $result_ac_validate = $myDB->query("SELECT  distinct(edu_name)  FROM education_details where EmployeeID= '" . $EmployeeID . "'  and    edu_name in('10th','12th') ");
				$EduQry = "SELECT distinct(edu_name)  FROM education_details where EmployeeID=? and edu_name in('10th','12th') ";
				$stmtEdu = $conn->prepare($EduQry);
				$stmtEdu->bind_param("s", $EmployeeID);
				$stmtEdu->execute();
				$result_ac_validate = $stmtEdu->get_result();
				// $rowData = $result_ac_validate->fetch_row();
				// print_r($result_ac_validate);
				// die;
				if ($result_ac_validate->num_rows >= 2) {
					// $experience_validate = $myDB->query("select  exp_type from experince_details where EmployeeID='" . $EmployeeID . "' and exp_type!=''  ");
					$ExpQry = "select exp_type from experince_details where EmployeeID=? and exp_type!=''  ";
					$stmtExp = $conn->prepare($ExpQry);
					$stmtExp->bind_param("s", $EmployeeID);
					$stmtExp->execute();
					$experience_validate = $stmtExp->get_result();
					// print_r($experience_validate);
					// die;
					if ($experience_validate->num_rows > 0) {
						// if (count($experience_validate) > 0) {
						// $result_ac_validate = $myDB->query('select dov_value,doc_file from doc_details where EmployeeID = "' . $EmployeeID . '" and doc_stype = "Aadhar Card";');
						$DocDQry = 'select dov_value,doc_file from doc_details where EmployeeID =? and doc_stype = "Aadhar Card" ';
						$stmtDocD = $conn->prepare($DocDQry);
						$stmtDocD->bind_param("s", $EmployeeID);
						$stmtDocD->execute();
						$result_ac_validate = $stmtDocD->get_result();
						$RowStmtDocD = $result_ac_validate->fetch_row();
						// print_r($result_ac_validate);
						// die;
						// echo $RowStmtDocD[1];
						// echo $RowStmtDocD[0];
						// die;
						if ($result_ac_validate->num_rows > 0 && $result_ac_validate && !empty(clean($RowStmtDocD[1])) && !empty(clean($RowStmtDocD[0])) && strlen(clean($RowStmtDocD[0])) == 12 && is_numeric(clean($RowStmtDocD[0]))) {

							//echo 'call get_mapvalidation_check("'.$EmployeeID.'")';
							$myDB = new MysqliDb();
							$result_validate = $myDB->query('call get_mapvalidation_check("' . $EmployeeID . '")'); //for check address detail and contact detail
							if ($result_validate) {
								if (count($result_validate) > 0) {
									$validate = 1;
								}
							}
							$validate_date = 0;
							if (substr($EmployeeID, 0, 2) == 'TE') {

								$tempdate = date('Y-m-d', strtotime('-2 days today'));
								$today_date = date('Y-m-d', strtotime('today'));
								if ($doj < $tempdate || $doj > $today_date) {
									$validate_date = 1;
								}
							}
							$mailler_msg = '';

							if ($validate == 1 && $validate_date == 0) {

								if (substr($EmployeeID, 0, 2) == 'TE') {
									$myDB = new MysqliDb();

									$orderID = substr($EmployeeID, 6, strlen($EmployeeID) - 1);
									$emp_alias = '';

									if ($clean_employment_type == "1") {
										if ($clean_employment_type_fk == "CCE") {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
											$orderID = $orderID[0]['EMPID'];
											$emp_alias = 'CCE';
										} else {
											if ($desg == 30 && $cm_id == 531) {
												$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RS%"');
												$orderID = $orderID[0]['EMPID'];
												$emp_alias = 'RS';
											} else {

												if ($desg == 9 || $desg == 12 || $desg == 30) {
													if ($clean_emp_salary >= '15800') {
														$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
														$orderID = $orderID[0]['EMPID'];
														$emp_alias = 'CE';
													} else {
														$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RS%"');
														$orderID = $orderID[0]['EMPID'];
														$emp_alias = 'RS';
													}
												} else {
													$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
													$orderID = $orderID[0]['EMPID'];
													$emp_alias = 'CE';
												}
											}
										}
									} else if ($clean_employment_type == "2") {
										if ($clean_employment_type_fk == "CCE") {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
											$orderID = $orderID[0]['EMPID'];
											$emp_alias = 'CCE';
										} else {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "MU%"');
											$orderID = $orderID[0]['EMPID'];
											$emp_alias = 'MU';
										}
									} else if ($clean_employment_type == "3") {
										if ($clean_employment_type_fk == "CCE") {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
											$orderID = $orderID[0]['EMPID'];
											$emp_alias = 'CCE';
										} else {
											if ($desg == 9 || $desg == 12 || $desg == 30) {
												if ($clean_emp_salary >= '15800') {
													$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
													$orderID = $orderID[0]['EMPID'];
													$emp_alias = 'CEM';
												} else {
													$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RSM%"');
													$orderID = $orderID[0]['EMPID'];
													$emp_alias = 'RSM';
												}
											} else {
												$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
												$orderID = $orderID[0]['EMPID'];
												$emp_alias = 'CEM';
											}
										}
									} else if ($clean_employment_type == "4") {
										if ($clean_employment_type_fk == "CCE") {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
											$orderID = $orderID[0]['EMPID'];
											$emp_alias = 'CCE';
										} else {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEB%"');
											$orderID = $orderID[0]['EMPID'];
											$emp_alias = 'CEB';
										}
									} else if ($clean_employment_type == "5") {
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEV%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias = 'CEV';
									} else if ($clean_employment_type == "6") {
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CMK%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias = 'CMK';
									} else if ($clean_employment_type == "7") {
										if ($clean_employment_type_fk == "Cogent") {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEK%" ');
											$emp_alias = 'CEK';
										} else if ($clean_employment_type_fk == "Flipkart") {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "FBK%" ');
											$emp_alias = 'FBK';
										} else if ($clean_employment_type_fk == "CCE") {
											$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
											$emp_alias = 'CCE';
										}

										$orderID = $orderID[0]['EMPID'];
									} else if ($_POST['employment_type'] == "8") {
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEN%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias = 'CEN';
									} else if ($_POST['employment_type'] == "9") {
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEA%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias = 'CEA';
									}


									if ($emp_alias != '') {
										if ($orderID) {
											$getEmpID = $orderID;

											if ($getEmpID < 10) {
												$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '000' . $getEmpID;
											} else if ($getEmpID >= 10 && $getEmpID < 100) {
												$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '00' . $getEmpID;
											} else if ($getEmpID >= 100 && $getEmpID < 1000) {
												$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '0' . $getEmpID;
											} else {
												$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '' . $getEmpID;
											}

											if ($EmployeeID != '') {
												if ($cm_id == "570") {
													$myDB = new MysqliDb();
													$sql = "select max(Ext) as Ext from ngucc_manage";
													$result = $myDB->query($sql);
													$Ext = 0;
													if ($result[0]['Ext'] == '') {
														$Ext = 2550;
														//$Ext = 2550;
													} else {
														$Ext = $result[0]['Ext'] + 1;
													}

													$sql = "select EmployeeName from personal_details where EmployeeID=?";
													$pstmt = $conn->prepare($sql);
													$pstmt->bind_param("s", $emp_IDorg);
													$pstmt->execute();
													$result = $pstmt->get_result();

													//$result = $myDB->query($sql);

													//echo $cm_id . '----' . $EmployeeID . '------' . $result[0]['EmployeeName'] . '--------' . $Ext;
													$requestMethod = $_SERVER['REQUEST_METHOD'];
													header("Access-Control-Allow-Origin: *");
													header("Content-Type: application/json; charset=UTF-8");
													header("Access-Control-Allow-Methods: POST");
													header("Access-Control-Max-Age: 3600");
													header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
													$data = json_decode(file_get_contents("php://input"));
													// if(isset($_REQUEST['submit'])){

													$name = $EmployeeID;
													$password = '12345678';
													$fullname = $result[0]['EmployeeName'];
													$extension = $Ext;
													$webadminrole = "0";
													$usergroupid = "1668060822493";
													$parentuser = "1667925795079";
													$domainid = "1667905947379";
													$authtoken = "fvjCQL7hS5Cels82gdOwAY2R";
													$arr = array(
														"name" => $name,
														"password" => $password,
														"fullname" => $fullname,
														"extension" => $extension,
														"webadminrole" => $webadminrole,
														"usergroupid" => $usergroupid,
														"parentuser" => $parentuser,
														"domainid" => $domainid,
														"authtoken" => $authtoken,
													);
													$jsonErr =  '[' . json_encode($arr) . ']';


													$curl = curl_init();

													curl_setopt_array($curl, array(
														CURLOPT_URL => 'https://verve.cogentlab.com/auth/InsertUser',
														CURLOPT_RETURNTRANSFER => true,
														CURLOPT_ENCODING => '',
														CURLOPT_MAXREDIRS => 10,
														CURLOPT_TIMEOUT => 0,
														CURLOPT_FOLLOWLOCATION => true,
														CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
														CURLOPT_CUSTOMREQUEST => 'POST',
														CURLOPT_POSTFIELDS => $jsonErr,
														CURLOPT_HTTPHEADER => array(
															'Content-Type: application/json'
														),
													));

													$response = curl_exec($curl);
													if (curl_errno($curl)) {
														echo 'Request Error:' . curl_error($curl);
													}

													curl_close($curl);
													// echo $response;
													// die;
												}

												//echo 'call update_mapedemp("'.$EmployeeID.'","'.$emp_IDorg.'","'.$rt_type.'")'

												$int_url = INTERVIEW_URL . "getSalary.php?intid=" . $_POST['hiddenIntID'];
												$curl = curl_init();
												curl_setopt($curl, CURLOPT_URL, $int_url);
												curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($curl, CURLOPT_HEADER, false);
												$data = curl_exec($curl);
												$salary_array = json_decode($data);

												// $sql_updateper = $myDB->query('delete from salary_details where EmployeeID="' . $emp_IDorg . '" ');

												$delQry = 'delete from salary_details where EmployeeID=? ';
												$stmtDel = $conn->prepare($delQry);
												$stmtDel->bind_param("s", $emp_IDorg);
												$stmtDel->execute();
												$sql_updateper = $stmtDel->get_result();
												// print_r($sql_updateper);
												// die;
												if (count($salary_array) > 0) {
													$clean_hiddenIntID = cleanUserInput($_POST['hiddenIntID']);
													$clean_user_logid = clean($_SESSION['__user_logid']);
													$insertData = "call manageOfferSalary('" . $salary_array->salary . "','" . $clean_hiddenIntID . "','" . $emp_IDorg . "','" . $clean_user_logid . "')";
													$myDB = new MysqliDb();
													$myDB->query($insertData);
													$error = $myDB->getLastError();
												}

												//die;
												$sql_updateper = $myDB->query('call update_mapedemp("' . $EmployeeID . '","' . $emp_IDorg . '","' . $rt_type . '")');
												$myDB = new MysqliDb();
												$mysql_error = $myDB->getLastError();

												$sql_updateper = $myDB->query('call update_module_emp("' . $EmployeeID . '","' . $subprocess . '","' . $createBy . '")');
												$myDB = new MysqliDb();
												$mysql_error = $myDB->getLastError();

												//$affacted_row = mysql_affected_rows();
												echo "<script>$(function(){ toastr.error('Employee ID is generated, Employee ID -" . $EmployeeID . " " . $mysql_error . "'); }); </script>";
												// $select_empinfo = $myDB->query("select distinct t.EmployeeID,p.EmployeeName  ,doc_stype as Adharcard, dov_value ,ct.mobile , ad.address from doc_details t left join address_details ad on t.EmployeeID=ad.EmployeeID left join contact_details ct on ct.EmployeeID=t.EmployeeID Inner Join personal_details p on t.EmployeeID=p.EmployeeID  where t.EmployeeID='" . $EmployeeID . "' and doc_stype  in('Aadhar Card') order by doc_id desc");
												$stmtSelQry = "select distinct t.EmployeeID,p.EmployeeName,doc_stype as Adharcard, dov_value ,ct.mobile , ad.address from doc_details t left join address_details ad on t.EmployeeID=ad.EmployeeID left join contact_details ct on ct.EmployeeID=t.EmployeeID Inner Join personal_details p on t.EmployeeID=p.EmployeeID  where t.EmployeeID=? and doc_stype  in('Aadhar Card') order by doc_id desc";
												$stmtSelct = $conn->prepare($stmtSelQry);
												$stmtSelct->bind_param("s", $EmployeeID);
												$stmtSelct->execute();
												$select_empinfo = $stmtSelct->get_result();
												$selRows = $select_empinfo->fetch_row();
												// print_r($select_empinfo);
												// print_r($selRows);
												// die;
												if ($select_empinfo->num_rows > 0) {
													// $empdata_array = $select_empinfo[0];
													// $Adharcard = $empdata_array['dov_value'];
													$empdata_array = clean($selRows[0]);
													$Adharcard = clean($selRows[3]);
													$address = 'NA';
													if ($empdata_array[5] != "") {
														$address = rawurlencode($empdata_array[5]);
													}

													$mobile = $empdata_array[4];
													if (strstr($empdata_array[1], ' ')) {
														$EmployeeName = rawurlencode($empdata_array[1]);
													} else {
														$EmployeeName = $empdata_array[1];
													}



													//$response = file_get_contents("http://lb.cogentlab.com:8081/Investment/putadhar.php?EmployeeID=".$EmployeeID."&EmployeeName=".$EmployeeName."&AdharCardNo=".$Adharcard."&PanCardNo=NA&ContactNo=".$mobile."&Address=".$address."&CreatedBy=".$createBy."&Location=".$locationRaw."");
													// $docAllStatus = $myDB->query("Insert into doc_al_status set EmployeeID='" . $EmployeeID . "',  DOJ='" . $doj . "',validate='0' ");
													$docQry = "Insert into doc_al_status set EmployeeID=?, DOJ=?,validate='0' ";
													$stmtDocQry = $conn->prepare($docQry);
													$stmtDocQry->bind_param("ss", $EmployeeID, $doj);
													$stmtDocQry->execute();
													$docAllStatus = $stmtDocQry->get_result();
												}

												if ($clean_employment_type == "1") {
													$location = rawurlencode('Noida');
												} else if ($clean_employment_type == "2") {
													$location = rawurlencode('Mumbai');
												} else if ($clean_employment_type == "3") {
													$location = rawurlencode('Meerut');
												} else if ($clean_employment_type == "4") {
													$location = rawurlencode('Bareilly');
												} else if ($clean_employment_type == "5") {
													$location = rawurlencode('Vadodara');
												} else if ($clean_employment_type == "6") {
													$location = rawurlencode('Mangalore');
												} else if ($clean_employment_type == "7") {
													$location = rawurlencode('Bangalore');
												} else if ($_POST['employment_type'] == "8") {
													$location = rawurlencode('Nashik');
												} else if ($_POST['employment_type'] == "9") {
													$location = rawurlencode('Anantapur');
												}
												$int_url = URL . "QrSetup/GenerateQRCodeAPI.php?empId=" . $EmployeeID . "&location=" . $location . "&qrtype=1";
												$curl = curl_init();
												curl_setopt($curl, CURLOPT_URL, $int_url);
												curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
												curl_setopt($curl, CURLOPT_HEADER, false);
												$data = curl_exec($curl);
												$resp = json_decode($data);
												// $flag = $myDB->query('insert into bgv (EmployeeID) values ("' . $EmployeeID . '")');

												$bgvQry = 'insert into bgv (EmployeeID) values (?)';
												$stmtBgvQry = $conn->prepare($bgvQry);
												$stmtBgvQry->bind_param("s", $EmployeeID);
												$stmtBgvQry->execute();
												$flag = $stmtBgvQry->get_result();
												if (strtolower(date('l', strtotime($doj))) == 'sunday') {
													$myDB = new MysqliDb();
													$data_roster_insert = $myDB->query('call insert_roster_tmp("' . strtoupper($EmployeeID) . '","WO","WO","' . date('Y-m-d', strtotime($doj)) . '",1,"WFOB")');
												} else {
													$begin_roster = new DateTime($doj);
													$end_roster   = new DateTime(date('Y-m-d', strtotime('next sunday')));
													$weekOFF = 0;
													$j = 1;
													$jj = 1;
													$daycount = 1;

													for ($i = $begin_roster; $begin_roster <= $end_roster; $i->modify('+1 day')) {
														$dateT_ins = $i->format('Y-m-d');
														//$myDB =new MysqliDb();
														//$sql_insert_roster = $myDB->query('call insert_roster_tmp("'.strtoupper($EmployeeID).'","09:00","18:00","'.$dateT_ins.'",1)');
														$sql_roster_insert = '';
														if (strtolower(date('l', strtotime($i->format('Y-m-d')))) == 'sunday') {
															$sql_roster_insert = 'call insert_roster_tmp("' . strtoupper($EmployeeID) . '","WO","WO","' . $dateT_ins . '",1,"WFOB")';
														} else {
															$sql_roster_insert = 'call insert_roster_tmp("' . strtoupper($EmployeeID) . '","09:00","18:00","' . $dateT_ins . '",1,"WFOB")';
														}
														$myDB = new MysqliDb();
														$data_roster_insert = $myDB->query($sql_roster_insert);
													}
												}
											}
											//	------------------------------------------------------------------------------


											if (($clean_employment_type == "1" || $clean_employment_type == "3" || $clean_employment_type == "4" || $clean_employment_type == "5" || $clean_employment_type == "6" || $clean_employment_type == "7" || $clean_employment_type == "8") && $desg != 9 && $desg != 12 && $desg != 30 && $desg != '') {

												$pagename = 'employee_map';
												// $select_email_array = $myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "' and b.location='" . $clean_employment_type . "'");
												$select_email_array = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=? and b.location=?";
												$stmtSelEmail = $conn->prepare($bgvQry);
												$stmtSelEmail->bind_param("ss", $pagename, $clean_employment_type);
												$stmtSelEmail->execute();
												$select_email_array = $stmtSelEmail->get_result();

												$mail = new PHPMailer;
												$mail->isSMTP();
												$mail->Host = EMAIL_HOST;
												$mail->SMTPAuth = EMAIL_AUTH;
												$mail->Username = EMAIL_USER;
												$mail->Password = EMAIL_PASS;
												$mail->SMTPSecure = EMAIL_SMTPSecure;
												$mail->Port = EMAIL_PORT;
												$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
												if ($select_email_array->num_rows > 0 && $select_email_array) {
													foreach ($select_email_array as $Key => $val) {
														$email_address = $val['email_address'];

														if ($email_address != "") {
															$mail->AddAddress($email_address);
														}
														$cc_email = $val['ccemail'];

														if ($cc_email != "") {
															$mail->addCC($cc_email);
														}
													}
												}
												$mail->Subject = 'Email ID Creation Request ' . $EMS_CenterName . ' [' . date('d M,Y', time()) . ']';
												$mail->isHTML(true);
												// $getDetails = $myDB->query("SELECT EmployeeName  FROM personal_details where EmployeeID='" . $EmployeeID . "'");
												$EmpnameQry = "SELECT EmployeeName  FROM personal_details where EmployeeID=?";
												$stmtEmpName = $conn->prepare($EmpnameQry);
												$stmtEmpName->bind_param("s", $EmployeeID);
												$stmtEmpName->execute();
												$getDetails = $stmtEmpName->get_result();
												$EmpNameRow = $getDetails->fetch_row();

												$EmployeeName = '';
												// if (isset($getDetails[0]['EmployeeName'])) {
												$rowemp = clean($EmpNameRow[0]);
												if (isset($rowemp)) {
													$EmployeeName = $rowemp;
												}
												// $getCLientName = $myDB->query("select client_name  from client_master  where client_id='" . $client . "' ");
												$clientQry = "select client_name  from client_master  where client_id=? ";
												$stmtClient = $conn->prepare($clientQry);
												$stmtClient->bind_param("i", $client);
												$stmtClient->execute();
												$getCLientName = $stmtClient->get_result();
												$clientRow = $getCLientName->fetch_row();
												$client_name = '';
												// if (isset($getCLientName[0]['client_name'])) {
												// $client_name = $getCLientName[0]['client_name'];
												$rowclient = clean($clientRow[0]);
												if (isset($rowclient)) {
													$client_name = $rowclient;
												}
												$Body = "Hello sir,<br>Please create the Email ID of the Employee<br><br>
							        <table border='1'>";
												$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Client</b></td><td><b>Process</b></td><td><b>Sub-process</b></td><td><b>Designation</b></td><td><b>Department</b></td></tr>";
												$Body .= "<tr><td>" . $EmployeeID . "</td><td>" . $EmployeeName . "</td><td>" . $client_name . "</td><td>" . $process . "</td><td>" . $sub_process1 . "</td><td>" . $hdesg . "</td><td>" . $hdept . "</td></tr>";

												$Body .= "</table><br><br>Thanks EMS Team";
												$mail->Body = $Body;

												if (!$mail->send()) {
													$mailler_msg = 'Mailer Error:' . $mail->ErrorInfo;
												} else {

													$mailler_msg =   'and Email Id creation request raised.';
												}
											}
										} else {
											$EmployeeID = '';
										}
									} else {
										$EmployeeID = '';
									}
								}


								if ($EmployeeID != '') {

									$createBy = clean($_SESSION['__user_logid']);

									$sqlInsertMap = 'call manage_map_employee("' . $EmployeeID . '","' . $dept . '","' . $client . '","' . $process . '","' . $subprocess . '","' . $desg . '","' . $doj . '","' . $level . '","' . $function_ . '","' . $createBy . '")';
									// echo $sqlInsertMap;
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlInsertMap);

									if ($desg != 9 && $desg != 12 && $desg != 30) {
										$sqlInsertMap = 'call update_apr_month("' . $EmployeeID . '","' . $doj . '")';
										$myDB = new MysqliDb();
										$result = $myDB->query($sqlInsertMap);
									}
									// echo "ffff";
									// echo $consutancy_id;
									$mysql_error = $myDB->getLastError();
									if (empty($mysql_error)) {
										if ($source_recruitment_id == '5') {

											// echo "select payout,tenure from manage_consultancy where cm_id='" . $cm_id . "' and consultancy_id='" . $consutancy_id . "' ";
											// $consult_array = $myDB->rawQuery("select payout,tenure from manage_consultancy where cm_id='" . $cm_id . "' and consultancy_id='" . $consutancy_id . "' ");

											$consulQry = "select payout,tenure from manage_consultancy where cm_id=? and consultancy_id=? ";
											$stmtConsul = $conn->prepare($consulQry);
											$stmtConsul->bind_param("ii", $cm_id, $consutancy_id);
											$stmtConsul->execute();
											$consult_array = $stmtConsul->get_result();
											$consulRow = $consult_array->fetch_row();

											$tenure = '';
											if ($consult_array->num_rows > 0) {
												// $tenure = $consult_array[0]["tenure"];
												// $payout = $consult_array[0]["payout"];
												$payout = clean($consulRow[0]);
												$tenure = clean($consulRow[1]);
												$myDB = new MysqliDb();
												$manage_query = $myDB->rawQuery('call consultancy_emp_manage("' . $EmployeeID . '","' . $consutancy_id . '" ,"' . $cm_id . '","' . $doj . '", "' . $tenure . '","' . $payout . '", "' . $createBy . '")');
											}
										}

										// echo "rrrrr";
										// die;
										// $myDB->rawQuery("update personal_details set ref_id='" . $source_recruitment_id . "', ref_txt='" . $source_recruitment_Desc . "' where EmployeeID='" . $EmployeeID . "'");
										$updtQry = "update personal_details set ref_id=?, ref_txt=? where EmployeeID=?";
										$stmtUpd = $conn->prepare($updtQry);
										$stmtUpd->bind_param("iss", $source_recruitment_id, $source_recruitment_Desc, $EmployeeID);
										$stmtUpd->execute();
										echo "<script>$(function(){ toastr.success('Saved Successfully " . $mailler_msg . "'); }); </script>";
									} else {
										echo "<script>$(function(){ toastr.error('Employee Not Assigned Try again " . $mysql_error . "'); }); </script>";
									}
								} else {
									echo "<script>$(function(){ toastr.error('Employee Not Assigned'); }); </script>";
								}
							} else {
								if ($validate_date == 1) {
									echo "<script>$(function(){ toastr.error('Please fill two day older date for joining for Employee.'); }); </script>";
								} else {

									echo "<script>$(function(){ toastr.error('Please fill following page details to map the Employee.1.Personal Details, 2.Education Details, 3.Contact Details, 4.Address Details'); }); </script>";
								}
							}
						} else {
							echo "<script>$(function(){ toastr.error('Please provide a valid Aadhar Card 12 digit number and attachment file on Contact Page.'); }); </script>";
						}
					} else {
						echo "<script>$(function(){ toastr.error('Please add Experience Details on Experince Page.'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Please add educatinal details (10th & 12th) on Education Page.'); }); </script>";
				}
			} else {
				if (substr($EmployeeID, 0, 2) == 'TE') {

					$myDB = new MysqliDb();

					$orderID = substr($EmployeeID, 6, strlen($EmployeeID) - 1);

					$emp_alias = '';
					$clean_employment_type_fk = cleanUserInput($_POST['employment_type_fk']);
					if ($clean_employment_type == "1") {
						if ($clean_employment_type_fk == "CCE") {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias = 'CCE';
						} else {
							if ($desg == 30 && $cm_id == 531) {
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RS%"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias = 'RS';
							} else {

								if ($desg == 9 || $desg == 12 || $desg == 30) {
									if ($clean_emp_salary >= '15800') {
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias = 'CE';
									} else {
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RS%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias = 'RS';
									}
								} else {
									$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
									$orderID = $orderID[0]['EMPID'];
									$emp_alias = 'CE';
								}
							}
						}
					} else if ($clean_employment_type == "2") {
						if ($clean_employment_type_fk == "CCE") {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias = 'CCE';
						} else {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "MU%"');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias = 'MU';
						}
					} else if ($clean_employment_type == "3") {
						if ($clean_employment_type_fk == "CCE") {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias = 'CCE';
						} else {
							if ($desg == 9 || $desg == 12 || $desg == 30) {
								if ($clean_emp_salary >= '15800') {
									$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
									$orderID = $orderID[0]['EMPID'];
									$emp_alias = 'CEM';
								} else {
									$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RSM%"');
									$orderID = $orderID[0]['EMPID'];
									$emp_alias = 'RSM';
								}
							} else {
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias = 'CEM';
							}
						}
					} else if ($clean_employment_type == "4") {
						if ($clean_employment_type_fk == "CCE") {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias = 'CCE';
						} else {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEB%"');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias = 'CEB';
						}
					} else if ($clean_employment_type == "5") {
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEV%"');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias = 'CEV';
					} else if ($clean_employment_type == "6") {
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CMK%"');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias = 'CMK';
					} else if ($clean_employment_type == "7") {
						if ($clean_employment_type_fk == "Cogent") {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEK%" ');
							$emp_alias = 'CEK';
						} else if ($clean_employment_type_fk == "Flipkart") {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "FBK%" ');
							$emp_alias = 'FBK';
						} else if ($clean_employment_type_fk == "CCE") {
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
							$emp_alias = 'CCE';
						}

						$orderID = $orderID[0]['EMPID'];
					} else if ($_POST['employment_type'] == "8") {
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEN%"');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias = 'CEN';
					} else if ($_POST['employment_type'] == "9") {
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEA%"');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias = 'CEA';
					}

					if ($emp_alias != '') {
						if ($orderID) {
							$getEmpID = $orderID;

							if ($getEmpID < 10) {
								$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '000' . $getEmpID;
							} else if ($getEmpID >= 10 && $getEmpID < 100) {
								$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '00' . $getEmpID;
							} else if ($getEmpID >= 100 && $getEmpID < 1000) {
								$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '0' . $getEmpID;
							} else {
								$EmployeeID = $emp_alias . date('my', strtotime($doj)) . '' . $getEmpID;
							}

							$clean_hiddenid = cleanUserInput($_POST['hiddenIntID']);
							if ($EmployeeID != '') {
								if ($cm_id == "570") {
									$myDB = new MysqliDb();
									$sql = "select max(Ext) as Ext from ngucc_manage";
									$result = $myDB->query($sql);
									$Ext = 0;
									if ($result[0]['Ext'] == '') {
										$Ext = 2550;
										//$Ext = 2550;
									} else {
										$Ext = $result[0]['Ext'] + 1;
									}

									$sql = "select EmployeeName from personal_details where EmployeeID=?";
									$stmtp = $conn->prepare($sql);
									$stmtp->bind_param("ii", $cm_id, $consutancy_id);
									$stmtp->execute();
									$result = $stmtp->get_result();

									//$result = $myDB->query($sql);

									//echo $cm_id . '----' . $EmployeeID . '------' . $result[0]['EmployeeName'] . '--------' . $Ext;
									$requestMethod = $_SERVER['REQUEST_METHOD'];
									header("Access-Control-Allow-Origin: *");
									header("Content-Type: application/json; charset=UTF-8");
									header("Access-Control-Allow-Methods: POST");
									header("Access-Control-Max-Age: 3600");
									header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
									$data = json_decode(file_get_contents("php://input"));
									// if(isset($_REQUEST['submit'])){

									$name = $EmployeeID;
									$password = '12345678';
									$fullname = $result[0]['EmployeeName'];
									$extension = $Ext;
									$webadminrole = "0";
									$usergroupid = "1668060822493";
									$parentuser = "1667925795079";
									$domainid = "1667905947379";
									$authtoken = "fvjCQL7hS5Cels82gdOwAY2R";
									$arr = array(
										"name" => $name,
										"password" => $password,
										"fullname" => $fullname,
										"extension" => $extension,
										"webadminrole" => $webadminrole,
										"usergroupid" => $usergroupid,
										"parentuser" => $parentuser,
										"domainid" => $domainid,
										"authtoken" => $authtoken,
									);
									$jsonErr =  '[' . json_encode($arr) . ']';


									$curl = curl_init();

									curl_setopt_array($curl, array(
										CURLOPT_URL => 'https://verve.cogentlab.com/auth/InsertUser',
										CURLOPT_RETURNTRANSFER => true,
										CURLOPT_ENCODING => '',
										CURLOPT_MAXREDIRS => 10,
										CURLOPT_TIMEOUT => 0,
										CURLOPT_FOLLOWLOCATION => true,
										CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
										CURLOPT_CUSTOMREQUEST => 'POST',
										CURLOPT_POSTFIELDS => $jsonErr,
										CURLOPT_HTTPHEADER => array(
											'Content-Type: application/json'
										),
									));

									$response = curl_exec($curl);
									if (curl_errno($curl)) {
										echo 'Request Error:' . curl_error($curl);
									}

									curl_close($curl);
									// echo $response;
									// die;
								}

								$int_url = INTERVIEW_URL . "getSalary.php?intid=" . $clean_hiddenid;
								$curl = curl_init();
								curl_setopt($curl, CURLOPT_URL, $int_url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_HEADER, false);
								$data = curl_exec($curl);
								$salary_array = json_decode($data);

								// $sql_updateper = $myDB->query('delete from salary_details where EmployeeID="' . $emp_IDorg . '" ');
								$delQry = 'delete from salary_details where EmployeeID=? ';
								$stmtDel = $conn->prepare($delQry);
								$stmtDel->bind_param("s", $emp_IDorg);
								$stmtDel->execute();
								$sql_updateper = $stmtDel->get_result();
								// print_r($sql_updateper);
								// die;
								if (count($salary_array) > 0) {
									$clean_hiddenintid = cleanUserInput($_POST['hiddenIntID']);
									$clean_user_logid = clean($_SESSION['__user_logid']);
									$insertData = "call manageOfferSalary('" . $salary_array->salary . "','" . $clean_hiddenintid . "','" . $emp_IDorg . "','" . $clean_user_logid . "')";
									$myDB = new MysqliDb();
									$myDB->query($insertData);
									$error = $myDB->getLastError();
								}
								//die;
								$myDB = new MysqliDb();
								$sql_updateper = $myDB->query('call update_mapedemp("' . $EmployeeID . '","' . $emp_IDorg . '","' . $rt_type . '")');
								$mysql_error = $myDB->getLastError();

								$myDB = new MysqliDb();
								$sql_updateper = $myDB->query('call update_module_emp("' . $EmployeeID . '","' . $subprocess . '","' . $createBy . '")');
								$mysql_error = $myDB->getLastError();

								//$affacted_row = mysql_affected_rows();
								echo "<script>$(function(){ toastr.error('Employee ID is generated, Employee ID -" . $EmployeeID . " " . $mysql_error . "'); }); </script>";

								// $select_empinfo = $myDB->query("select distinct t.EmployeeID,p.EmployeeName,doc_stype as Adharcard, dov_value ,ct.mobile , ad.address from doc_details t left join address_details ad on t.EmployeeID=ad.EmployeeID left join contact_details ct on ct.EmployeeID=t.EmployeeID Inner Join personal_details p on t.EmployeeID=p.EmployeeID  where t.EmployeeID='" . $EmployeeID . "' and doc_stype  in('Aadhar Card') order by doc_id desc");

								// if (count($select_empinfo) > 0) {
								// 	$empdata_array = $select_empinfo[0];
								// 	$Adharcard = $empdata_array['dov_value'];
								// 	$address = 'NA';
								// 	if ($empdata_array['address'] != "") {
								// 		$address = rawurlencode($empdata_array['address']);
								// 	}

								// 	$mobile = $empdata_array['mobile'];
								// 	if (strstr($empdata_array['EmployeeName'], ' ')) {
								// 		$EmployeeName = rawurlencode($empdata_array['EmployeeName']);
								// 	} else {
								// 		$EmployeeName = $empdata_array['EmployeeName'];
								// 	}

								$stmtSelQry = "select distinct t.EmployeeID,p.EmployeeName,doc_stype as Adharcard, dov_value ,ct.mobile , ad.address from doc_details t left join address_details ad on t.EmployeeID=ad.EmployeeID left join contact_details ct on ct.EmployeeID=t.EmployeeID Inner Join personal_details p on t.EmployeeID=p.EmployeeID  where t.EmployeeID=? and doc_stype  in('Aadhar Card') order by doc_id desc";
								$stmtSelct = $conn->prepare($stmtSelQry);
								$stmtSelct->bind_param("s", $EmployeeID);
								$stmtSelct->execute();
								$select_empinfo = $stmtSelct->get_result();
								$selRows = $select_empinfo->fetch_row();
								// print_r($select_empinfo);
								// print_r($selRows);
								// die;
								if ($select_empinfo->num_rows > 0) {
									// $empdata_array = $select_empinfo[0];
									// $Adharcard = $empdata_array['dov_value'];
									$empdata_array = clean($selRows[0]);
									$Adharcard = clean($selRows[3]);
									$address = 'NA';
									if ($empdata_array[5] != "") {
										$address = rawurlencode($empdata_array[5]);
									}

									$mobile = $empdata_array[4];
									if (strstr($empdata_array[1], ' ')) {
										$EmployeeName = rawurlencode($empdata_array[1]);
									} else {
										$EmployeeName = $empdata_array[1];
									}



									//$response = file_get_contents("http://lb.cogentlab.com:8081/Investment/putadhar.php?EmployeeID=".$EmployeeID."&EmployeeName=".$EmployeeName."&AdharCardNo=".$Adharcard."&PanCardNo=NA&ContactNo=".$mobile."&Address=".$address."&CreatedBy=".$createBy."&Location=".$locationRaw."");
									// $docAllStatus = $myDB->query("Insert into doc_al_status set EmployeeID='" . $EmployeeID . "',  DOJ='" . $doj . "',validate='0' ");
									$docQry = "Insert into doc_al_status set EmployeeID=?, DOJ=?,validate='0' ";
									$stmtDocQry = $conn->prepare($docQry);
									$stmtDocQry->bind_param("ss", $EmployeeID, $doj);
									$stmtDocQry->execute();
									$docAllStatus = $stmtDocQry->get_result();
								}

								if ($clean_employment_type == "1") {
									$location = rawurlencode('Noida');
								} else if ($clean_employment_type == "2") {
									$location = rawurlencode('Mumbai');
								} else if ($clean_employment_type == "3") {
									$location = rawurlencode('Meerut');
								} else if ($clean_employment_type == "4") {
									$location = rawurlencode('Bareilly');
								} else if ($clean_employment_type == "5") {
									$location = rawurlencode('Vadodara');
								} else if ($clean_employment_type == "6") {
									$location = rawurlencode('Mangalore');
								} else if ($clean_employment_type == "7") {
									$location = rawurlencode('Bangalore');
								} else if ($clean_employment_type == "8") {
									$location = rawurlencode('Nashik');
								} else if ($clean_employment_type == "9") {
									$location = rawurlencode('Anantapur');
								}
								$int_url = URL . "QrSetup/GenerateQRCodeAPI.php?empId=" . $EmployeeID . "&location=" . $location . "&qrtype=1";
								$curl = curl_init();
								curl_setopt($curl, CURLOPT_URL, $int_url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_HEADER, false);
								$data = curl_exec($curl);
								$resp = json_decode($data);
								// $flag = $myDB->query('insert into bgv (EmployeeID) values ("' . $EmployeeID . '")');
								$bgvQry = 'insert into bgv (EmployeeID) values (?)';
								$stmtBgvQry = $conn->prepare($bgvQry);
								$stmtBgvQry->bind_param("s", $EmployeeID);
								$stmtBgvQry->execute();
								$flag = $stmtBgvQry->get_result();

								// $flag = $myDB->query('insert into induction_master (EmpID) values ("' . $EmployeeID . '")');
								$inducQry = 'insert into induction_master (EmpID) values (?)';
								$stmtinducQry = $conn->prepare($inducQry);
								$stmtinducQry->bind_param("s", $EmployeeID);
								$stmtinducQry->execute();
								$flag = $stmtinducQry->get_result();

								if (strtolower(date('l', strtotime($doj))) == 'sunday') {

									$myDB = new MysqliDb();
									$data_roster_insert = $myDB->query('call insert_roster_tmp("' . strtoupper($EmployeeID) . '","WO","WO","' . date('Y-m-d', strtotime($doj)) . '",1,"WFOB")');
								} else {
									$begin_roster = new DateTime($doj);
									$end_roster   = new DateTime(date('Y-m-d', strtotime('next sunday')));
									$weekOFF = 0;
									$j = 1;
									$jj = 1;
									$daycount = 1;

									for ($i = $begin_roster; $begin_roster <= $end_roster; $i->modify('+1 day')) {
										$dateT_ins = $i->format('Y-m-d');
										//$myDB =new MysqliDb();
										//$sql_insert_roster = $myDB->query('call insert_roster_tmp("'.strtoupper($EmployeeID).'","09:00","18:00","'.$dateT_ins.'",1)');
										$sql_roster_insert = '';
										if (strtolower(date('l', strtotime($i->format('Y-m-d')))) == 'sunday') {
											$sql_roster_insert = 'call insert_roster_tmp("' . strtoupper($EmployeeID) . '","WO","WO","' . $dateT_ins . '",1,"WFOB")';
										} else {
											$sql_roster_insert = 'call insert_roster_tmp("' . strtoupper($EmployeeID) . '","09:00","18:00","' . $dateT_ins . '",1,"WFOB")';
										}
										$myDB = new MysqliDb();
										$data_roster_insert = $myDB->query($sql_roster_insert);
									}
								}
							}
							//	------------------------------------------------------------------------------


							if (($clean_employment_type == "1" || $clean_employment_type == "3" || $clean_employment_type == "4" || $clean_employment_type == "5" || $clean_employment_type == "6" || $clean_employment_type == "7" || $clean_employment_type == "8") && $desg != 9 && $desg != 12 && $desg != 30 && $desg != '') {

								$myDB = new MysqliDb();
								$pagename = 'employee_map';
								// $select_email_array = $myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='" . $pagename . "' and b.location='" . $clean_employment_type . "'");

								$select_email_array = "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename=? and b.location=?";
								$stmtSelEmail = $conn->prepare($bgvQry);
								$stmtSelEmail->bind_param("ss", $pagename, $clean_employment_type);
								$stmtSelEmail->execute();
								$select_email_array = $stmtSelEmail->get_result();

								$mail = new PHPMailer;
								$mail->isSMTP();
								$mail->Host = EMAIL_HOST;
								$mail->SMTPAuth = EMAIL_AUTH;
								$mail->Username = EMAIL_USER;
								$mail->Password = EMAIL_PASS;
								$mail->SMTPSecure = EMAIL_SMTPSecure;
								$mail->Port = EMAIL_PORT;
								$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
								if ($select_email_array->num_rows > 0 && $select_email_array) {
									foreach ($select_email_array as $Key => $val) {
										$email_address = $val['email_address'];

										if ($email_address != "") {
											$mail->AddAddress($email_address);
										}
										$cc_email = $val['ccemail'];

										if ($cc_email != "") {
											$mail->addCC($cc_email);
										}
									}
								}
								$mail->Subject = 'Email ID Creation Request ' . $EMS_CenterName . ' [' . date('d M,Y', time()) . ']';
								$mail->isHTML(true);
								// $getDetails = $myDB->query("SELECT EmployeeName  FROM personal_details where EmployeeID='" . $EmployeeID . "'");
								// $EmployeeName = '';
								// if (isset($getDetails[0]['EmployeeName'])) {
								// 	$EmployeeName = $getDetails[0]['EmployeeName'];
								// }
								// $myDB = new MysqliDb();
								// $getCLientName = $myDB->query("select client_name  from client_master  where client_id='" . $client . "' ");
								// $client_name = '';
								// if (isset($getCLientName[0]['client_name'])) {
								// 	$client_name = $getCLientName[0]['client_name'];
								// }

								$EmpnameQry = "SELECT EmployeeName  FROM personal_details where EmployeeID=?";
								$stmtEmpName = $conn->prepare($EmpnameQry);
								$stmtEmpName->bind_param("s", $EmployeeID);
								$stmtEmpName->execute();
								$getDetails = $stmtEmpName->get_result();
								$EmpNameRow = $getDetails->fetch_row();
								// print_r($EmpNameRow);
								// die;
								$EmployeeName = '';
								// if (isset($getDetails[0]['EmployeeName'])) {
								$rowempname = clean($EmpNameRow[0]);
								if (isset($rowempname)) {
									$EmployeeName = $rowempname;
								}
								// $getCLientName = $myDB->query("select client_name  from client_master  where client_id='" . $client . "' ");
								$clientQry = "select client_name  from client_master  where client_id=?";
								$stmtClient = $conn->prepare($clientQry);
								$stmtClient->bind_param("i", $client);
								$stmtClient->execute();
								$getCLientName = $stmtClient->get_result();
								$clientRow = $getCLientName->fetch_row();
								$client_name = '';
								// if (isset($getCLientName[0]['client_name'])) {
								// $client_name = $getCLientName[0]['client_name'];
								$rowsclient = clean($clientRow[0]);
								if (isset($rowsclient)) {
									$client_name = $rowsclient;
								}

								$Body = "Hello sir,<br>Please create the Email ID of the Employee<br><br>
							        <table border='1'>";
								$Body .= "<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Client</b></td><td><b>Process</b></td><td><b>Sub-process</b></td><td><b>Designation</b></td><td><b>Department</b></td></tr>";
								$Body .= "<tr><td>" . $EmployeeID . "</td><td>" . $EmployeeName . "</td><td>" . $client_name . "</td><td>" . $process . "</td><td>" . $sub_process1 . "</td><td>" . $hdesg . "</td><td>" . $hdept . "</td></tr>";

								$Body .= "</table><br><br>Thanks EMS Team";
								$mail->Body = $Body;

								if (!$mail->send()) {
									$mailler_msg = 'Mailer Error:' . $mail->ErrorInfo;
								} else {

									$mailler_msg =   'and Email Id creation request raised.';
								}
							}
						} else {
							$EmployeeID = '';
						}
					} else {
						$EmployeeID = '';
					}
				}

				if ($EmployeeID != '') {

					$myDB = new MysqliDb();
					$createBy = clean($_SESSION['__user_logid']);

					$sqlInsertMap = 'call manage_map_employee("' . $EmployeeID . '","' . $dept . '","' . $client . '","' . $process . '","' . $subprocess . '","' . $desg . '","' . $doj . '","' . $level . '","' . $function_ . '","' . $createBy . '")';
					// echo $sqlInsertMap;
					// die;
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlInsertMap);
					if ($desg != 9 && $desg != 12 && $desg != 30) {
						$sqlInsertMap = 'call update_apr_month("' . $EmployeeID . '","' . $doj . '")';

						$result = $myDB->query($sqlInsertMap);
					}


					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) {
						if ($source_recruitment_id == '5') {

							$myDB = new MysqliDb();
							//echo 	"select payout,tenure from manage_consultancy where cm_id='".$cm_id."' and consultancy_id='".$consutancy_id."' ";
							// $consult_array = $myDB->rawQuery("select payout,tenure from manage_consultancy where cm_id='" . $cm_id . "' and consultancy_id='" . $consutancy_id . "' ");
							$consulQry = "select payout,tenure from manage_consultancy where cm_id=? and consultancy_id=? ";
							$stmtConsul = $conn->prepare($consulQry);
							$stmtConsul->bind_param("ii", $cm_id, $consutancy_id);
							$stmtConsul->execute();
							$consult_array = $stmtConsul->get_result();
							$consulRow = $consult_array->fetch_row();

							$tenure = '';
							if ($consult_array->num_rows > 0) {
								// $payout = $consult_array[0]["payout"];
								// $tenure = $consult_array[0]["tenure"];	
								$payout = clean($consulRow[0]);
								$tenure = clean($consulRow[0]);
								$myDB = new MysqliDb();
								$manage_query = $myDB->rawQuery('call consultancy_emp_manage("' . $EmployeeID . '","' . $consutancy_id . '" ,"' . $cm_id . '","' . $doj . '", "' . $tenure . '","' . $payout . '", "' . $createBy . '")');
							}
						}

						$myDB = new MysqliDb();
						// $myDB->rawQuery("update personal_details set ref_id='" . $source_recruitment_id . "', ref_txt='" . $source_recruitment_Desc . "' where EmployeeID='" . $EmployeeID . "'");
						$updtQry = "update personal_details set ref_id=?, ref_txt=? where EmployeeID=?";
						$stmtUpd = $conn->prepare($updtQry);
						$stmtUpd->bind_param("iss", $source_recruitment_id, $source_recruitment_Desc, $EmployeeID);
						$stmtUpd->execute();
						echo "<script>$(function(){ toastr.success('Saved Successfully '); }); </script>";
					} else {
						echo "<script>$(function(){ toastr.error('Employee Not Assigned Try again'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('Employee Not Assigned'); }); </script>";
				}
			}
		} else {
			$data_update_alert = "<div id='div_mapping_aler_danger' class='alert alert-danger'>Please select Roster Type.</div>";
		}
	}
}
function randomPassword()
{
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = random_int(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}
// echo $pass;
// die;
if ($pass == '') {
	$pass = randomPassword();
}
?>

<script>
	$(document).ready(function() {
		var usrtype = <?php echo "'" . clean($_SESSION["__user_type"]) . "'"; ?>;
		var usrID = <?php echo "'" . clean($_SESSION["__user_logid"]) . "'"; ?>;
		if (usrtype === 'ADMINISTRATOR' || usrtype === 'HR' || usrID === 'CE12102224') {} else if (usrtype === 'AUDIT') {
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('button:not(.drawer-toggle)').remove();

			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();

		} else if (usrtype === 'CENTRAL MIS') {

			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled', 'true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		} else {
			$('input,button:not(.drawer-toggle),select,textarea').remove();
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
			window.location = <?php echo '"' . URL . '/undefined"'; ?>;
		}
		$('#txt_empmap_doj').datetimepicker({
			timepicker: false,
			format: 'Y-m-d',
			minDate: '-1970/01/03',
			maxDate: '+1970/01/01',
			scrollInput: false
		});
		$('#div_tempCard').click(function() {

			var popup = window.open("../Controller/get_tempCard.php?EmpID=" + $(this).children('a').attr('data_EmpID'), "popupWindow", "width=600px,height=600px,scrollbars=yes");


		});
	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Map Employee</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Map Employee</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<input type="hidden" id="emp_type" name="emp_type" />
				<input type="hidden" name="employment_type" id="employment_type" value="<?php echo $loc; ?>" />
				<input type="hidden" name="emp_salary" id="emp_salary" value="<?php echo $emp_salary; ?>" />
				<input type="hidden" name="hiddenIntID" id="hiddenIntID" value="<?php echo $interview_id; ?>" />
				<?php echo $data_update_alert; ?>

				<div class="input-field col s6 m6">
					<select id="txt_empmap_dept" name="txt_empmap_dept" required>
						<option value="NA">----Select----</option>
						<?php
						$sqlBy = "select dept_id,dept_name from dept_master";
						$myDB = new MysqliDb();
						$resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								if ($value['dept_id'] == $dept) {
									$selec = ' selected ';
								} else {
									$selec = '';
								}
								echo '<option value="' . $value['dept_id'] . '" ' . $selec . ' >' . $value['dept_name'] . '</option>';
							}
						}
						?>
					</select>
					<label for="txt_empmap_dept" class="active-drop-down active">Department *</label>
				</div>

				<div class="input-field col s6 m6">
					<select id="txt_empmap_client" name="txt_empmap_client" required>
						<option value="NA">----Select----</option>
						<?php
						if ($clean_temp_check == 'ADMINISTRATOR') {
							$sqlBy = "select distinct client_id,t1.client_name FROM client_master t1 join new_client_master t2 on t1.client_id=t2.client_name left join client_status_master t3 on t2.cm_id=t3.cm_id where t3.cm_id is null order by client_name";
							$stmtCln = $conn->prepare($sqlBy);
							$stmtCln->execute();
						} else {
							$clean_loc = clean($_SESSION["__location"]);
							$sqlBy = "select distinct client_id,t1.client_name FROM client_master t1 join new_client_master t2 on t1.client_id=t2.client_name left join client_status_master t3 on t2.cm_id=t3.cm_id where location=? and t3.cm_id is null order by client_name";
							$stmtCln = $conn->prepare($sqlBy);
							$stmtCln->bind_param("s", $clean_loc);
							$stmtCln->execute();
						}
						$resultBy = $stmtCln->get_result();

						// $myDB = new MysqliDb();
						// $resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								if ($value['client_id'] == $client) {
									$selec = ' selected ';
								} else {
									$selec = '';
								}
								echo '<option value="' . $value['client_id'] . '"  ' . $selec . '>' . $value['client_name'] . '</option>';
							}
						}
						?>
					</select>
					<label for="txt_empmap_client" class="active-drop-down active">Client *</label>
				</div>

				<?php if (clean($_SESSION["__user_type"]) == 'ADMINISTRATOR') { ?>
					<div class="input-field col s6 m6">
						<select id="txt_empmap_center" name="txt_empmap_center" required>
							<option value="NA">----Select----</option>
							<?php
							$sqlBy = "select id,location from location_master";
							$myDB = new MysqliDb();
							$resultBy = $myDB->query($sqlBy);
							if ($resultBy) {
								$selec = '';
								foreach ($resultBy as $key => $value) {
									if ($value['id'] == $loc) {
										$selec = ' selected ';
									} else {
										$selec = '';
									}
									echo '<option value="' . $value['id'] . '" ' . $selec . ' >' . $value['location'] . '</option>';
								}
							}
							?>
						</select>
						<label for="txt_empmap_center" class="active-drop-down active">Center *</label>
					</div>
				<?php } ?>
				<div class="input-field col s6 m6">
					<select id="txt_empmap_process" name="txt_empmap_process" required>
						<option value="NA">----Select----</option>
						<option selected="true"><?php echo ($process); ?></option>
					</select>
					<label for="txt_empmap_process" class="active-drop-down active">Process *</label>
				</div>

				<div class="input-field col s6 m6">
					<select id="txt_empmap_subprocess" name="txt_empmap_subprocess" required>
						<option value="NA">----Select----</option>
						<option selected="true" value="<?php echo $subprocess; ?>"><?php echo ($sub_process1); ?></option>
					</select>
					<label for="txt_empmap_subprocess" class="active-drop-down active">Sub Process *</label>
				</div>
				<input type="hidden" value="<?php echo $cm_id; ?>" name='cm_id' id='cm_id'>
				<div class="input-field col s6 m6">
					<select id="txt_empmap_level" name="txt_empmap_level" required>
						<?php
						$clean_ut = clean($_SESSION["__user_type"]);
						if ($clean_ut === 'ADMINISTRATOR' && $clean_temp_check == 'ADMINISTRATOR') {
						?>
							<option value="NA" <?php if ($level == 'NA' || $level == '' || empty($level)) {
													echo ('selected');
												} ?>>----Select----</option>
							<option value="EXECUTIVE" <?php if ($level == 'EXECUTIVE') {
															echo ('selected');
														} ?>>User</option>
							<option value="ADMINISTRATOR" <?php if ($level == 'ADMINISTRATOR') {
																echo ('selected');
															} ?>>Administrator</option>
							<option value="SITEADMIN" <?php if ($level == 'SITEADMIN') {
															echo ('selected');
														} ?>>Site Administrator</option>
							<option value="AUDIT" <?php if ($level == 'AUDIT') {
														echo ('selected');
													} ?>>Auditor</option>
							<option value="CENTRAL MIS" <?php if ($level == 'CENTRAL MIS') {
															echo ('selected');
														} ?>>Central MIS</option>
							<option value="COMPLIANCE" <?php if ($level == 'COMPLIANCE') {
															echo ('selected');
														} ?>>Compliance</option>
						<?php
						} else {
						?>
							<option value="NA" <?php if ($level == 'NA' || $level == '' || empty($level) || $level == 'ADMINISTRATOR' || $level == 'SITEADMIN' || $level == 'AUDIT' || $level == 'CENTRAL MIS' || $level == 'COMPLIANCE') {
													echo ('selected');
												} ?>>----Select----</option>

							<option value="EXECUTIVE" <?php if ($level == 'EXECUTIVE') {
															echo ('selected');
														} ?>>User</option>

							<!--<option value="ADMINISTRATOR" <?php if ($level == 'ADMINISTRATOR') {
																	echo ('selected');
																} ?>>Administrator</option>											<option value="SITEADMIN" <?php if ($level == 'SITEADMIN') {
																																										echo ('selected');
																																									} ?>>Site Administrator</option>
	           		<option value="AUDIT" <?php if ($level == 'AUDIT') {
													echo ('selected');
												} ?>>Auditor</option>
	           		<option value="CENTRAL MIS" <?php if ($level == 'CENTRAL MIS') {
														echo ('selected');
													} ?>>Central MIS</option>
	           		<option value="COMPLIANCE" <?php if ($level == 'CENTRAL MIS') {
														echo ('selected');
													} ?>>Compliance</option>
	           		-->
						<?php
						}
						?>
						<option value="HR" <?php if ($level == 'HR') {
												echo ('selected');
											} ?>>Human Resources</option>
						<!--<option value="ER" <?php if ($level == 'ER') {
													echo ('selected');
												} ?>>ER</option>				           		-->
						<option value="MIS" <?php if ($level == 'MIS') {
												echo ('selected');
											} ?>>MIS </option>
						<option value="HOD" <?php if ($level == 'HOD') {
												echo ('selected');
											} ?>>Head Of Department</option>
					</select>
					<label for="txt_empmap_level" class="active-drop-down active">Access Level *</label>
				</div>

				<div class="input-field col s6 m6">
					<select id="txt_empmap_function" name="txt_empmap_function" required>
						<option value="NA" <?php if ($function_ == 'NA' || $function_ == '' || empty($function_)) {
												echo ('selected');
											} ?>>----Select----</option>
						<?php
						$sqlBy = "select id,`function` from function_master";
						$myDB = new MysqliDb();
						$resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								if ($value['id'] == $function_) {
									$selec = ' selected ';
								} else {
									$selec = '';
								}
								echo '<option value="' . $value['id'] . '" ' . $selec . ' >' . $value['function'] . '</option>';
							}
						}

						?>
					</select>
					<label for="txt_empmap_function" class="active-drop-down active">Function *</label>
				</div>

				<div class="input-field col s6 m6">
					<select id="rt_type" name="rt_type" required>
						<option value="NA" <?php if ($rt_type == 'NA') echo 'selected'; ?>>---Select---</option>
						<option value="1" <?php if ($rt_type == '1') echo 'selected'; ?>>Full Time</option>
						<option value="4" <?php if ($rt_type == '4') echo 'selected'; ?>>Split Time</option>
						<option value="3" <?php if ($rt_type == '3') echo 'selected'; ?>>Part Time</option>

					</select>
					<label for="rt_type" class="active-drop-down active">Roster Type *</label>
				</div>

				<div class="input-field col s6 m6">
					<select id="txt_empmap_desg" name="txt_empmap_desg" required>
						<option value="NA">----Select----</option>
						<?php
						$sqlBy = "select ID,Designation from designation_master";
						$myDB = new MysqliDb();
						$resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								if ($value['ID'] == $desg) {
									$selec = ' selected ';
								} else {
									$selec = '';
								}
								echo '<option value="' . $value['ID'] . '" ' . $selec . ' >' . $value['Designation'] . '</option>';
							}
						}
						?>
					</select>
					<label for="txt_empmap_desg" class="active-drop-down active">Designation *</label>
				</div>

				<div class="input-field col s6 m6">
					<input type="text" value="<?php echo ($doj); ?>" id="txt_empmap_doj" name="txt_empmap_doj" readonly="true" required />
					<label for="txt_empmap_doj">DOJ *</label>
				</div>
				<?php
				if ($loc == "7" || $loc == "1" || $loc == "2" || $loc == "3" || $loc == "4") { ?>
					<div class="input-field col s6 m6">
						<select id="employment_type_fk" name="employment_type_fk" required>
							<option value="NA">---Select---</option>

							<option value="Cogent" <?php if ($emptype == 'Cogent') echo 'selected'; ?>>Cogent</option>
							<?php if ($loc == "7") { ?>

								<option value="Flipkart" <?php if ($emptype == 'Flipkart') echo 'selected'; ?>>Flipkart</option>
							<?php } ?>
							<option value="CCE" <?php if ($emptype == 'CCE') echo 'selected'; ?>>CCE</option>
						</select>
						<label for="employment_type_fk" class="active-drop-down active">Employment Type *</label>
					</div>
				<?php  } ?>

				<div class="input-field col s6 m6">
					<select id="txt_Personal_Ref_id" name="txt_Personal_Ref_id" required>
						<option value="NA">----Select----</option>
						<?php
						$sqlBy = 'SELECT ref_id,Type FROM ref_master';
						$myDB = new MysqliDb();
						$resultBy = $myDB->query($sqlBy);
						if ($resultBy) {
							foreach ($resultBy as $key => $value) {
								if ($source_recruitment_id == $value['ref_id']) {
									$selected = 'Selected';
								} else {
									$selected = '';
								}
								echo '<option value="' . $value['ref_id'] . '" ' . $selected . ' >' . $value['Type'] . '</option>';
							}
						}
						?>
					</select>
					<label for="txt_Personal_Ref_id" class="active-drop-down active">Source Of Recruitment *</label>
				</div>
				<?php
				$hiddenDesc = "";
				$hiddenCons = "";
				$ConsultancyName = '';
				$consultancy_id = '';
				$requireDesc = '';
				$requireCons = '';
				if ($source_recruitment_id == '5') {
					// $myDB = new MysqliDb();
					// $query = $myDB->rawQuery("select a.consultancy_id,b.ConsultancyName from consultancy_empref a inner join consultancy_master b on a.consultancy_id=b.id where a.EmployeeID='" . $EmployeeID . "' ");
					// echo "select a.consultancy_id,b.ConsultancyName from consultancy_empref a inner join consultancy_master b on a.consultancy_id=b.id where a.EmployeeID='" . $EmployeeID . "' ";
					// die;
					$conQry = "select a.consultancy_id,b.ConsultancyName from consultancy_empref a inner join consultancy_master b on a.consultancy_id=b.id where a.EmployeeID=? ";
					$stmtCon = $conn->prepare($conQry);
					$stmtCon->bind_param("s", $EmployeeID);
					$stmtCon->execute();
					$query = $stmtCon->get_result();
					$queryRow = $query->fetch_row();
					// print_r($queryRow);

					if ($query->num_rows > 0) {
						$consultancy_id = clean($queryRow[0]);
						$ConsultancyName = clean($queryRow[1]);
						$hiddenDesc = "hidden";
						$hiddenCons = "";
						$requireDesc = '';
						$requireCons = 'required';
					}
				} else {
					$hiddenDesc = "";
					$hiddenCons = "hidden";
					$requireDesc = 'required';
					$requireCons = '';
				}

				?>

				<div class="input-field col s6 m6 ref_txt_to <?php echo $hiddenDesc; ?>">
					<input type="text" id="txt_Personal_Ref_Desc" value="<?php echo $source_recruitment_Desc; ?>" name="txt_Personal_Ref_Desc" <?php echo $requireDesc; ?> />
					<label for="txt_Personal_Ref_Desc">Referred By *</label>
				</div>

				<div class="input-field col s6 m6 ref_option_to <?php echo $hiddenCons; ?>">
					<select id="txt_Personal_Ref_rName" name="txt_Personal_Ref_rName" <?php echo $requireCons; ?>>
						<option value="<?php echo $consultancy_id; ?>"><?php echo $ConsultancyName; ?> </option>
					</select>
					<label for="txt_Personal_Ref_rName" class="active-drop-down active">Referred By *</label>
				</div>

				<div class="input-field col s6 m6 hidden">
					<input type="password" class="hidden" value="NAN" id="txt_empmap_pass" name="txt_empmap_pass" readonly="true" />
					<label for="txt_empmap_pass">Password *</label>
				</div>
				<input type='hidden' name='hdesignation' id='hdesignation'>
				<input type='hidden' name='hdepartment' id='hdepartment'>
				<div class="input-field col s12 m12 right-align">
					<button type="submit" title="Update Details" name="btn_empmap_Save" id="btn_empmap_Save" class="btn waves-effect waves-green">Save</button>
				</div>

				<?php
				if ($loc == "1" || $loc == "2") {
					if ((substr($EmployeeID, 0, 2) == 'CE' || substr($EmployeeID, 0, 2) == 'AE' || substr($EmployeeID, 0, 2) == 'RS' || substr($EmployeeID, 0, 2) == 'MU') && $dept != "" &&  $sub_process1 != "" && $desg != "") {
						echo '<div id="div_tempCard"><a href="#" data_empID="' . $EmployeeID . '" id="a_print_card">
					      <i class="fa fa-link"></i> Print ID Card</a>
					      </div>';
					}
				} else if ($loc == "3") {
					if ((substr($EmployeeID, 0, 2) == 'CE' || substr($EmployeeID, 0, 2) == 'OC' || substr($EmployeeID, 0, 2) == 'RS') && $dept != "" &&  $sub_process1 != "" && $desg != "") {
						echo '<div id="div_tempCard"><a href="#" data_empID="' . $EmployeeID . '" id="a_print_card">
					      <i class="fa fa-link"></i> Print ID Card</a>
					      </div>';
					}
				} else if ($loc == "4" || $loc == "5" || $loc == "6" || $loc == "7" || $loc == "8" || $loc == "9") {
					if (substr($EmployeeID, 0, 2) != 'TE' && $dept != "" &&  $sub_process1 != "" && $desg != "") {
						echo '<div id="div_tempCard"><a href="#" data_empID="' . $EmployeeID . '" id="a_print_card">
					      <i class="fa fa-link"></i> Print ID Card</a>
					      </div>';
					}
				}

				?>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(document).ready(function() {


		$('input[type="text"]').click(function() {
			$(this).closest('div').removeClass('has-error');
		});
		$('select').click(function() {
			$(this).closest('div').removeClass('has-error');
		});

		<?php
		if (substr($EmployeeID, 0, 2) != 'TE' && $df_id != '') {
			if (substr($EmployeeID, 0, 2) != 'TE' && !($clean_temp_check == 'ADMINISTRATOR' || $clean_temp_check == 'COMPLIANCE')) {


		?>

				$('#btn_empmap_Save').addClass('hidden').attr('disabled', true);
				$('#btn_empmap_Save').remove();
		<?php
			}
		}
		?>
		var value_click_cl = 1;
		var value_click_pro = 1;
		var value_click_spro = 1;

		$('#txt_empmap_client').change(function() {
			var clientId = $(this).val();
			var dept = $("#txt_empmap_dept").val();
			var location = <?php echo clean($_SESSION["__location"]) ?>;
			var loc = "<?php echo $loc ?>";

			var lvl = '1';

			<?php
			if ($clean_temp_check == 'ADMINISTRATOR') {
			?>
				lvl = '2';
				location = loc;
			<?php
			}
			?>

			value_click_pro = 2;
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getprocess.php?id=" + clientId + "&loc=" + location + "&lvl=" + lvl
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_empmap_process').html(data);
				$('#txt_empmap_process').val('NA');
				$('#txt_empmap_subprocess').val('NA');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}
				});
				$('select').formSelect();
			});
		});
		$('#txt_empmap_center').change(function() {
			var center = $(this).val();
			var clientId = $('#txt_empmap_client').val();
			lvl = '2';
			// location = loc;
			value_click_pro = 2;
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getprocess.php?id=" + clientId + "&loc=" + center + "&lvl=" + lvl
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_empmap_process').html(data);
				$('#txt_empmap_process').val('NA');
				$('#txt_empmap_subprocess').val('NA');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}
				});
				$('select').formSelect();
			});
		});
		$('#txt_empmap_process').change(function() {
			var tval = $(this).val();
			var dept = $("#txt_empmap_dept").val();
			value_click_spro = 2;
			var id = $('#txt_empmap_client').val();
			var location = <?php echo clean($_SESSION["__location"]) ?>;
			var lvl = '1';
			var center = $('#txt_empmap_center').val();

			<?php
			if ($clean_temp_check == 'ADMINISTRATOR') {
			?>
				lvl = '2';
				location = center;

			<?php
			}
			?>
			//alert(location);
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/getsubprocess_map.php?id=" + id + "&proc=" + tval + "&dept=" + dept + "&loc=" + location + "&lvl=" + lvl
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_empmap_subprocess').html(data);
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}
				});
				$('select').formSelect();
			});
		});

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.

		$('#btn_empmap_Save').on('click', function() {
			/*var emptype = $("#employment_type option:selected").text();
			$("#emp_type").val(emptype);*/
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
					validate = 1;
					$(this).addClass('has-error');
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#' + spanID).size() == 0) {
						$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
					}
					var attr_error = $(this).attr('data-error-msg');
					if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
						$('#' + spanID).html('Required *');
					} else {
						$('#' + spanID).html($(this).attr("data-error-msg"));
					}
				}
			})

			// $('#txt_empmap_dept').selected()	
			var departText = $("#txt_empmap_dept option:selected").text();
			$("#hdepartment").val(departText);
			var desigText = $("#txt_empmap_desg option:selected").text();
			$("#hdesignation").val(desigText);

			if (validate == 1) {
				return false;
			}
		});


		$('#txt_empmap_dept').change(function() {

			var tval = $(this).val();
			var lvl = '1';

			<?php
			if ($clean_temp_check == 'ADMINISTRATOR') {
			?>
				lvl = '2';

			<?php
			}
			?>
			value_click_cl = 2;
			//alert(lvl);
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/get_client_bydept.php?id=" + tval + "&lvl=" + lvl
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_empmap_client').html(data);
				if (tval == '1') {
					$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				} else if (tval == 'NA') {
					$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				} else if (tval == '2') {

					<?php
					if ($clean_temp_check == 'ADMINISTRATOR') {
					?>
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option><option value="11">Emerging Businesses</option>');

					<?php
					} else {
					?>
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option><option value="11">Emerging Businesses</option>');

					<?php  }  ?>



					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				} else if (tval == '3') {
					$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="1">Administration</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				} else {
					$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				}
			});
		});

		$('#txt_empmap_function').change(function() {

			var tval = $(this).val();
			$.ajax({
				url: <?php echo '"' . URL . '"'; ?> + "Controller/get_desby_function.php?id=" + tval
			}).done(function(data) { // data what is sent back by the php page
				//alert(data);
				$('#txt_empmap_desg').html(data);
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}
				});
				$('select').formSelect();
			});
		});

		if ($('#txt_empmap_dept').val() == '1') {
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
			$('select').formSelect();
		} else if ($('#txt_empmap_dept').val() == 'NA') {
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
			$('select').formSelect();
		} else if ($('#txt_empmap_dept').val() == '2') {
			<?php
			if ($clean_temp_check == 'ADMINISTRATOR') {
			?>
				$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option>');

			<?php
			} else {
			?>
				$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option>');

			<?php  }  ?>

			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
			$('select').formSelect();
		} else if ($('#txt_empmap_dept').val() == '3') {
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
			$('select').formSelect();
		} else {
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
			$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}
			});
			$('select').formSelect();
		}


		<?php
		if ($function_ != '') {
		?>
			var val_funct = <?php echo '"' . $function_ . '"'; ?>;
			$('#txt_empmap_function').val(val_funct);
			$('#txt_empmap_function').trigger('change');


			var val_desg = <?php echo '"' . $desg . '"'; ?>;
			$(document).ajaxComplete(function() {

				$('#txt_empmap_desg').val(val_desg);
				$(document).unbind('ajaxComplete');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
					if ($(element).val().length > 0) {
						$(this).siblings('label, i').addClass('active');
					} else {
						$(this).siblings('label, i').removeClass('active');
					}
				});
				$('select').formSelect();

			});



		<?php
		}

		?>
		$('#txt_empmap_client').click(function() {
			if (value_click_cl == 1) {
				var lvl = '1';

				<?php
				if ($clean_temp_check == 'ADMINISTRATOR') {
				?>
					lvl = '2';

				<?php
				}
				?>
				var tval = $('#txt_empmap_dept').val();
				$.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/get_client_bydept.php?id=" + tval + "&lvl=" + lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_client').html(data);
					if (tval == '1') {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else if (tval == 'NA') {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else if (tval == '2') {
						<?php
						if ($clean_temp_check == 'ADMINISTRATOR') {
						?>
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option>');

						<?php
						} else {
						?>
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option>');

						<?php  }  ?>


						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else if (tval == '3') {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					}
				});
				value_click_cl = 2;
			}

		});
		$('#txt_empmap_process').click(function() {
			if (value_click_pro == 1) {
				var tval = $('#txt_empmap_client').val();
				var lvl = '1';

				<?php
				if ($clean_temp_check == 'ADMINISTRATOR') {
				?>
					lvl = '2';

				<?php
				}
				?>
				var location = <?php echo clean($_SESSION["__location"]) ?>;
				$.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/getprocess.php?id=" + tval + "&loc=" + location + "&lvl=" + lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_process').html(data);
					$('#txt_empmap_process').val('NA');
					$('#txt_empmap_subprocess').val('NA');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				});
				value_click_pro = 2;
			}

		});
		$('#txt_empmap_subprocess').click(function() {
			if (value_click_spro == 1) {
				var tval = $('#txt_empmap_process').val();
				var id = $('#txt_empmap_client').val();
				var location = <?php echo clean($_SESSION["__location"]) ?>;
				var center = $('#txt_empmap_center').val();

				var lvl = '1';

				<?php
				if ($clean_temp_check == 'ADMINISTRATOR') {
				?>
					lvl = '2';
					location = center;
				<?php
				}
				?>

				$.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/getsubprocess_map.php?id=" + id + "&proc=" + tval + "&loc=" + location + "&lvl=" + lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_subprocess').html(data);
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				});
				value_click_spro = 2;
			}

		});

		$('#txt_empmap_client').focusin(function() {
			if (value_click_cl == 1) {
				var lvl = '1';

				<?php
				if ($clean_temp_check == 'ADMINISTRATOR') {
				?>
					lvl = '2';

				<?php
				}
				?>
				var tval = $('#txt_empmap_dept').val();
				$.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/get_client_bydept.php?id=" + tval + "&lvl=" + lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_client').html(data);
					if (tval == '1') {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else if (tval == 'NA') {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else if (tval == '2') {
						<?php
						if ($clean_temp_check == 'ADMINISTRATOR') {
						?>
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option>');

						<?php
						} else {
						?>
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option>');

						<?php  }  ?>


						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else if (tval == '3') {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					} else {
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							if ($(element).val().length > 0) {
								$(this).siblings('label, i').addClass('active');
							} else {
								$(this).siblings('label, i').removeClass('active');
							}
						});
						$('select').formSelect();
					}
				});
				value_click_cl = 2;
			}
		});
		$('#txt_empmap_process').focusin(function() {
			if (value_click_pro == 1) {
				var tval = $('#txt_empmap_client').val();
				var lvl = '1';

				<?php
				if ($clean_temp_check == 'ADMINISTRATOR') {
				?>
					lvl = '2';

				<?php
				}
				?>
				var location = <?php echo clean($_SESSION["__location"]) ?>;
				$.ajax({

					url: <?php echo '"' . URL . '"'; ?> + "Controller/getprocess.php?id=" + tval + "&loc=" + location + "&lvl=" + lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_process').html(data);
					//alert(data);
					$('#txt_empmap_process').val('NA');
					$('#txt_empmap_subprocess').val('NA');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				});
				value_click_pro = 2;
			}

		});
		$('#txt_empmap_subprocess').focusin(function() {
			if (value_click_spro == 1) {
				var tval = $('#txt_empmap_process').val();
				var id = $('#txt_empmap_client').val();
				var location = <?php echo clean($_SESSION["__location"]) ?>;
				var lvl = '1';
				var center = $('#txt_empmap_center').val();

				<?php
				if ($clean_temp_check == 'ADMINISTRATOR') {
				?>
					lvl = '2';
					location = center;
				<?php
				}
				?>
				$.ajax({
					url: <?php echo '"' . URL . '"'; ?> + "Controller/getsubprocess_map.php?id=" + id + "&proc=" + tval + "&loc=" + location + "&lvl=" + lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_subprocess').html(data);
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						if ($(element).val().length > 0) {
							$(this).siblings('label, i').addClass('active');
						} else {
							$(this).siblings('label, i').removeClass('active');
						}
					});
					$('select').formSelect();
				});
				value_click_spro = 2;
			}
		});

		$('#txt_Personal_Ref_id').change(function() {

			$('#txt_Personal_Ref_Desc').autocomplete({
				disabled: true
			});
			//if($(this).val()=='Employee'||$(this).val()=='NewsPaper'||$(this).val()=='Other'||$(this).val()=='WebSite'||$('#txt_Personal_Ref_id').val()=='WalkIn')
			if ($(this).val() == '1' || $(this).val() == '1' || $(this).val() == '3' || $(this).val() == '4' || $('#txt_Personal_Ref_id').val() == '6') {
				$('.ref_txt_to').removeClass('hidden');
				$('.ref_option_to').addClass('hidden');
				$('#txt_Personal_Ref_Desc').prop('required', true);
				$('#txt_Personal_Ref_rName').prop('required', false);
				if ($(this).val() == 'Employee') {
					$('#txt_Personal_Ref_Desc').autocomplete({
						source: '../Controller/autocomplete_employee.php',
						minLength: 2,
						disabled: false
					});
					$('select').formSelect();
				}
				$('select').formSelect();
			} else if ($(this).val() == 'NA' || $(this).val() == '') {
				$('.ref_txt_to').addClass('hidden');
				$('.ref_option_to').addClass('hidden');
				//$('#txt_Personal_Ref_rName').val('NA');
				$('#txt_Personal_Ref_Desc').val('');
				$('select').formSelect();
			} else if ($(this).val() == '5' && $(this).val() != '') {
				$('.ref_txt_to').addClass('hidden');
				$('.ref_option_to').removeClass('hidden');
				$('#txt_Personal_Ref_Desc').val('');
				$('#txt_Personal_Ref_Desc').prop('required', false);
				$('#txt_Personal_Ref_rName').prop('required', true);
				if ($('#txt_empmap_subprocess').val() != "") {
					cm_id = $('#txt_empmap_subprocess').val();
				} else {
					cm_id = $('#cm_id').val();
				}
				//alert('cm_id'+cm_id);
				if (cm_id != "") {
					$.ajax({
						url: "../Controller/getRefrence.php?cm_id=" + cm_id,
						success: function(result) {
							//alert(result);
							$('#txt_Personal_Ref_rName').empty().append(result);
							$('select').formSelect();

						}
					});
				}

				$('select').formSelect();
			}
		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>