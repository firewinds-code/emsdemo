<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
ini_set('display_errors', '1');
ini_set('log_errors', 'On');
ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL);
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$date = date('Y-m-d');
//$date = "MU01200043";
//$date = '2022-05-05';

$sqlConnect = 'select EmployeeID, location,client_name, process, sub_process, reports_to from transfer_emp where transfer_date=?';
$selectQ = $conn->prepare($sqlConnect);
$selectQ->bind_param("s", $date);
$selectQ->execute();
$result = $selectQ->get_result();
// print_r($result);
// $myDB = new MysqliDb();
// $result = $myDB->query($sqlConnect);

// echo ($sqlConnect);
// die;
// $mysql_error = $myDB->getLastError();
if ($result->num_rows > 0) {
	foreach ($result as $key => $value) {
		$EmployeeID = $value['EmployeeID'];
		$location = $value['location'];
		$sub_process = $value['sub_process'];
		$reports_to = $value['reports_to'];
		//$location = '3';

		$selQr = "select img,location from personal_details where EmployeeID=?";
		// $myDB = new MysqliDb();
		// $res = $myDB->query($selQr);
		$selQ = $conn->prepare($selQr);
		$selQ->bind_param("s", $EmployeeID);
		$selQ->execute();
		$results = $selQ->get_result();
		$res = $results->fetch_row();

		$loc = $res[1];

		if ($loc == "1" || $loc == "2") {
			$sourceDirectory = "";
		} else if ($loc == "3") {
			$sourceDirectory = "Meerut/";
		} else if ($loc == "4") {
			$sourceDirectory = "Bareilly/";
		} else if ($loc == "5") {
			$sourceDirectory = "Vadodara/";
		} else if ($loc == "6") {
			$sourceDirectory = "Manglore/";
		} else if ($loc == "7") {
			$sourceDirectory = "Bangalore/";
		} else if ($loc == "8") {
			$sourceDirectory = "Nashik/";
		} else if ($loc == "9") {
			$sourceDirectory = "Anantapur/";
		} else if ($loc == "10") {
			$sourceDirectory = "Gurgaon/";
		} else if ($loc == "11") {
			$sourceDirectory = "Hyderabad/";
		}

		################################################################## transfer location ######################################

		if ($location == "1" || $location == "2") {
			$locationdir = "";
		} else if ($location == "3") {
			$locationdir = "Meerut/";
		} else if ($location == "4") {
			$locationdir = "Bareilly/";
		} else if ($location == "5") {
			$locationdir = "Vadodara/";
		} else if ($location == "6") {
			$locationdir = "Manglore/";
		} else if ($location == "7") {
			$locationdir = "Bangalore/";
		} else if ($location == "8") {
			$locationdir = "Nashik/";
		} else if ($location == "9") {
			$locationdir = "Anantapur/";
		} else if ($location == "10") {
			$locationdir = "Gurgaon/";
		} else if ($location == "11") {
			$locationdir = "Hyderabad/";
		}



		if ($res[0] != '') {
			if (file_exists("../" . $sourceDirectory . "Images/" . $res[0]) && $res[0] != '') {
				$imsrc = URL . $sourceDirectory . "Images/" . $res[0];
			} else
			if (file_exists("../Images/" . $res[0]) && $res[0] != '') {
				$imsrc = URL . "Images/" . $res[0];
			}
			$filename = $res[0];
			if ($filename != "") {
				$imsrc =  ROOT_PATH . $sourceDirectory . "Images/" . $filename;
				$filename = $filename;
				$target_dir = ROOT_PATH . $locationdir . 'Images/';
				$target_file = $target_dir . $filename;
				if (!rename($imsrc, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}
			}
		}


		$imsrc = '';
		$selQr = "select QrCode from employee_qrcode where EmployeeID=?";
		// $myDB = new MysqliDb();
		// $res = $myDB->query($selQr);
		$selQ = $conn->prepare($selQr);
		$selQ->bind_param("s", $EmployeeID);
		$selQ->execute();
		$result = $selQ->get_result();
		$res = $result->fetch_row();

		if ($res[0] != '') {
			if ($loc == '6') {
				if (file_exists("../QrSetup/Mangalore/" . $res[0]) && $res[0] != '') {
					$imsrc = ROOT_PATH . 'QrSetup/Mangalore/' . $res[0];
				}
			} else {
				if (file_exists("../QrSetup/" . $sourceDirectory . $res[0]) && $res[0] != '') {
					$imsrc = ROOT_PATH . 'QrSetup/' . $sourceDirectory . $res[0];
				}
			}


			$filename = $res[0];
			if ($filename != "" && $imsrc != "") {
				//$imsrc =  ROOT_PATH . $sourceDirectory . "Images/" . $filename;
				$filename = $filename;
				if ($location == '6') {
					$target_dir = ROOT_PATH . '/QrSetup/Mangalore/';
				} else {
					$target_dir = ROOT_PATH . 'QrSetup/' . $locationdir;
				}

				$target_file = $target_dir . $filename;
				// echo $imsrc . '<br/>';
				// echo $target_file . '<br/>';
				// die;
				if (!rename($imsrc, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}
			}
		}

		$selQry = "select edu_file from education_details where EmployeeID=?";
		// $myDB = new MysqliDb();
		// $resu = $myDB->query($selQry);
		// if (count($resu) > 0) {
		$selQ = $conn->prepare($selQry);
		$selQ->bind_param("s", $EmployeeID);
		$selQ->execute();
		$resu = $selQ->get_result();

		if ($resu->num_rows > 0) {
			foreach ($resu as $key => $value) {
				if (file_exists("../" . $sourceDirectory . "Edu/" . $value['edu_file']) && $value['edu_file'] != '') {
					$imsrc = URL . $sourceDirectory . "Edu/" . $value['edu_file'];
				} else if (file_exists("../Edu/" . $value['edu_file']) && $value['edu_file'] != '') {
					$imsrc = URL . "Edu/" . $value['edu_file'];
				} else if (file_exists("../" . $sourceDirectory . "Education/" . $value['edu_file']) && $value['edu_file'] != '') {
					$imsrc = URL . $sourceDirectory . "Education/" . $value['edu_file'];
				} else if (file_exists("../Education/" . $value['edu_file']) && $value['edu_file'] != '') {
					$imsrc = URL . "Education/" . $value['edu_file'];
				}
				$edufilename = $value['edu_file'];
				if ($edufilename != '') {
					$imsrcs = ROOT_PATH . $sourceDirectory . "Edu/" . $edufilename;
				} else {
					$imsrcs = ROOT_PATH . $sourceDirectory . "Education/" . $edufilename;
				}

				$target_dir = ROOT_PATH . $locationdir . 'Edu/';
				$target_file = $target_dir . $edufilename;
				if (!rename($imsrcs, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}
			}
		}

		$selectExp = "select releiving_experience_doc,appointment_offerletter_doc,salaryslip_bankstatement_doc from experince_details where EmployeeID=? and exp_type !='Fresher' ";
		// $myDB = new MysqliDb();
		// $results = $myDB->query($selectExp);
		// if (count($results) > 0) {
		$selectQ = $conn->prepare($selectExp);
		$selectQ->bind_param("s", $EmployeeID);
		$selectQ->execute();
		$results = $selectQ->get_result();

		if (count($results) > 0) {
			foreach ($results as $key => $val) {
				if (file_exists("../" . $sourceDirectory . "Docs/Experience/" . $val['releiving_experience_doc']) && $val['releiving_experience_doc'] != '') {
					$imsrc = URL . $sourceDirectory . "Docs/Experience/" . $val['releiving_experience_doc'];
				} else
				if (file_exists("../Docs/Experience/" . $val['releiving_experience_doc']) && $val['releiving_experience_doc'] != '') {
					$imsrc = URL . "Docs/Experience/" . $val['releiving_experience_doc'];
				}
				$filename = $val['releiving_experience_doc'];
				$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/Experience/" . $filename;
				$target_dir = ROOT_PATH . $locationdir . 'Docs/Experience/';
				$target_file = $target_dir . $filename;
				if (!rename($imsrc, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}


				if (file_exists("../" . $sourceDirectory . "Docs/offerletter/" . $val['appointment_offerletter_doc']) && $val['appointment_offerletter_doc'] != '') {
					$imsrc = URL . $sourceDirectory . "Docs/offerletter/" . $val['appointment_offerletter_doc'];
				} else
				if (file_exists("../Docs/offerletter/" . $val['appointment_offerletter_doc']) && $val['appointment_offerletter_doc'] != '') {
					$imsrc = URL . "Docs/offerletter/" . $val['appointment_offerletter_doc'];
				}
				$filename = $val['appointment_offerletter_doc'];
				$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/offerletter/" . $filename;
				$target_dir = ROOT_PATH . $locationdir . 'Docs/offerletter/';
				$target_file = $target_dir . $filename;
				if (!rename($imsrc, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}


				if (file_exists("../" . $sourceDirectory . "Docs/salaryslip/" . $val['salaryslip_bankstatement_doc']) && $val['salaryslip_bankstatement_doc'] != '') {
					$imsrc = URL . $sourceDirectory . "Docs/salaryslip/" . $val['salaryslip_bankstatement_doc'];
				} else
				if (file_exists("../Docs/salaryslip/" . $val['salaryslip_bankstatement_doc']) && $val['salaryslip_bankstatement_doc'] != '') {
					$imsrc = URL . "Docs/salaryslip/" . $val['salaryslip_bankstatement_doc'];
				}
				$filename = $val['salaryslip_bankstatement_doc'];
				$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/salaryslip/" . $filename;
				$target_dir = ROOT_PATH . $locationdir . 'Docs/salaryslip/';
				$target_file = $target_dir . $filename;
				if (!rename($imsrc, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}
			}
		}

		$selecttestscore = "select file from test_score where empid=?";
		// $myDB = new MysqliDb();
		// $resultscrore = $myDB->query($selecttestscore);
		// if (count($resultscrore) > 0) {
		$SelQ = $conn->prepare($selecttestscore);
		$SelQ->bind_param("s", $EmployeeID);
		$SelQ->execute();
		$resultscrore = $SelQ->get_result();

		if ($resultscrore->num_rows > 0) {
			foreach ($resultscrore as $key => $vals) {
				if (file_exists("../" . $sourceDirectory . "TestDocs/" . $vals['file']) && $vals['file'] != '') {
					$imsrc = URL . $sourceDirectory . "TestDocs/" . $vals['file'];
				} else
				if (file_exists("../TestDocs/" . $vals['file']) && $vals['file'] != '') {
					$imsrc = URL . "TestDocs/" . $vals['file'];
				}
				$filename = $vals['file'];
				$imsrc =  ROOT_PATH . $sourceDirectory . "TestDocs/" . $filename;
				$target_dir = ROOT_PATH . $locationdir . 'TestDocs/';
				$target_file = $target_dir . $filename;
				if (!rename($imsrc, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}
			}
		}

		$selectbank = "select cheque_book from bank_details where EmployeeID=?";
		// $myDB = new MysqliDb();
		// $resultbank = $myDB->query($selectbank);
		// if (count($resultbank) > 0) {
		$SelQ = $conn->prepare($selectbank);
		$SelQ->bind_param("s", $EmployeeID);
		$SelQ->execute();
		$resultbank = $SelQ->get_result();

		if ($resultbank->num_rows > 0) {
			foreach ($resultbank as $key => $values) {
				if (file_exists("../" . $sourceDirectory . "Docs/BankDocs/" . $values['cheque_book']) && $values['cheque_book'] != '') {
					$imsrc = URL . $sourceDirectory . "Docs/BankDocs/" . $values['cheque_book'];
				} else
				if (file_exists("../Docs/BankDocs/" . $values['cheque_book']) && $values['cheque_book'] != '') {
					$imsrc = URL . "Docs/BankDocs/" . $values['cheque_book'];
				}
				$filename = $values['cheque_book'];
				$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/BankDocs/" . $filename;
				$target_dir = ROOT_PATH . $locationdir . 'Docs/BankDocs/';
				$target_file = $target_dir . $filename;
				if (!rename($imsrc, $target_file)) {
					echo "File can't be moved!";
				} else {
					echo "File has been moved!";
				}
			}
		}

		$selectinfra = "select sys_img,inlanproof_doc from infra_details where EmployeeID=? and (sys_available='Yes' or internet_avail='Yes')";
		// $myDB = new MysqliDb();
		// $resultinfra = $myDB->query($selectinfra);
		// if (count($resultinfra) > 0) {
		$selQR = $conn->prepare($selectinfra);
		$selQR->bind_param("s", $EmployeeID);
		$selQR->execute();
		$resultinfra = $selQR->get_result();

		if ($resultinfra->num_rows > 0) {
			foreach ($resultinfra as $key => $val) {
				if (file_exists("../" . $sourceDirectory . "Docs/InfraDocs/" . $val['sys_img']) && $val['sys_img'] != '') {
					$imsrc = URL . $sourceDirectory . "Docs/InfraDocs/" . $val['sys_img'];
				} else
				if (file_exists("../Docs/InfraDocs/" . $val['sys_img']) && $val['sys_img'] != '') {
					$imsrc = URL . "Docs/InfraDocs/" . $val['sys_img'];
				}
				$filename = $val['sys_img'];
				if ($filename != '') {
					$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/InfraDocs/" . $filename;
					$target_dir = ROOT_PATH . $locationdir . 'Docs/InfraDocs/';
					$target_file = $target_dir . $filename;
					if (!rename($imsrc, $target_file)) {
						echo "File can't be moved!";
					} else {
						echo "File has been moved!";
					}
				}

				if (file_exists("../" . $sourceDirectory . "Docs/InternetDocs/" . $val['inlanproof_doc']) && $val['inlanproof_doc'] != '') {
					$imsrc = URL . $sourceDirectory . "Docs/InternetDocs/" . $val['inlanproof_doc'];
				} else
				if (file_exists("../Docs/InternetDocs/" . $val['inlanproof_doc']) && $val['inlanproof_doc'] != '') {
					$imsrc = URL . "Docs/InternetDocs/" . $val['inlanproof_doc'];
				}
				$filename = $val['inlanproof_doc'];
				if ($filename != '') {
					$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/InternetDocs/" . $filename;
					$target_dir = ROOT_PATH . $locationdir . 'Docs/InternetDocs/';
					$target_file = $target_dir . $filename;
					if (!rename($imsrc, $target_file)) {
						echo "File can't be moved!";
					} else {
						echo "File has been moved!";
					}
				}
			}
		}

		$selectdocs = "select doc_type,doc_stype,doc_file from doc_details where EmployeeID=?";
		// $myDB = new MysqliDb();
		// $resultdocs = $myDB->query($selectdocs);
		// if (count($resultdocs) > 0) {
		$selQery = $conn->prepare($selectdocs);
		$selQery->bind_param("s", $EmployeeID);
		$selQery->execute();
		$resultdocs = $selQery->get_result();

		if ($resultdocs->num_rows > 0) {
			foreach ($resultdocs as $key => $value) {
				$doc_type = $value['doc_type'];
				$doc_stype = $value['doc_stype'];
				$doc_file = $value['doc_file'];
				if ($doc_stype == 'Curriculum Vitae') {
					if (file_exists("../" . $sourceDirectory . "Resume/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Resume/" . $value['doc_file'];
					} else if (file_exists("../Resume/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Resume/" . $value['doc_file'];
					} else if (file_exists("../" . $sourceDirectory . "Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/Other/" . $value['doc_file'];
					} else if (file_exists("../Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/Other/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrcs = ROOT_PATH . $sourceDirectory . "Resume/" . $filename;
					} else {
						$imsrcs = ROOT_PATH . $sourceDirectory . "Docs/Other/" . $filename;
					}

					$target_dir = ROOT_PATH . $locationdir . 'Resume/';
					$target_file = $target_dir . $filename;
					if (!rename($imsrcs, $target_file)) {
						echo "File can't be moved!";
					} else {
						echo "File has been moved!";
					}
				} else if ($doc_stype == 'CheckList') {
					if (file_exists("../" . $sourceDirectory . "checklist_pdf/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "checklist_pdf/" . $value['doc_file'];
					} else if (file_exists("../checklist_pdf/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "checklist_pdf/" . $value['doc_file'];
					} else if (file_exists("../" . $sourceDirectory . "Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/Other/" . $value['doc_file'];
					} else if (file_exists("../Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/Other/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrcs = ROOT_PATH . $sourceDirectory . "checklist_pdf/" . $filename;
					} else {
						$imsrcs = ROOT_PATH . $sourceDirectory . "Docs/Other/" . $filename;
					}

					$target_dir = ROOT_PATH . $locationdir . 'checklist_pdf/';
					$target_file = $target_dir . $filename;
					if (!rename($imsrcs, $target_file)) {
						echo "File can't be moved!";
					} else {
						echo "File has been moved!";
					}
				} else if ($doc_stype == 'Salary Proof') {
					if (file_exists("../" . $sourceDirectory . "Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/Other/" . $value['doc_file'];
					} else if (file_exists("../Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/Other/" . $value['doc_file'];
					} else if (file_exists("../" . $sourceDirectory . "Docs/salaryslip/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/salaryslip/" . $value['doc_file'];
					} else if (file_exists("../Docs/salaryslip/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/salaryslip/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrcs = ROOT_PATH . $sourceDirectory . "Docs/Other/" . $filename;
					} else {
						$imsrcs = ROOT_PATH . $sourceDirectory . "Docs/salaryslip/" . $filename;
					}

					$target_dir = ROOT_PATH . $locationdir . 'Docs/salaryslip/';
					$target_file = $target_dir . $filename;
					if (!rename($imsrcs, $target_file)) {
						echo "File can't be moved!";
					} else {
						echo "File has been moved!";
					}
				} else if ($doc_stype == 'Signature' || $doc_stype == 'System Undertaking' || $doc_stype == 'Undertaking' || $doc_stype == 'Interview Form' || $doc_stype == 'WFH Policy' || $doc_stype == 'WFO-WFH Declaration') {
					if (file_exists("../" . $sourceDirectory . "Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/Other/" . $value['doc_file'];
					} else if (file_exists("../Docs/Other/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/Other/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/Other/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/Other/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'Proof of Address') {
					if (file_exists("../" . $sourceDirectory . "Docs/AdharCard/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/AdharCard/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/AdharCard/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/AdharCard/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/AdharCard/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/AdharCard/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'Proof of Identity') {
					if (file_exists("../" . $sourceDirectory . "Docs/IdentityProof/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/IdentityProof/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/IdentityProof/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/IdentityProof/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/IdentityProof/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/IdentityProof/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'BGV Report') {
					if (file_exists("../" . $sourceDirectory . "Docs/BGV/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/BGV/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/BGV/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/BGV/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/BGV/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/BGV/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'Covid 19') {
					if (file_exists("../" . $sourceDirectory . "Docs/Covid19/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/Covid19/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/Covid19/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/Covid19/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/Covid19/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/Covid19/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'Call Log') {
					if (file_exists("../" . $sourceDirectory . "Docs/CallLog/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/CallLog/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/CallLog/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/CallLog/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/CallLog/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/CallLog/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'FnF') {
					if (file_exists("../" . $sourceDirectory . "Docs/FnF/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/FnF/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/FnF/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/FnF/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/FnF/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/FnF/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'Warning Letter' || $doc_stype == 'Warning Letter') {
					if (file_exists("../" . $sourceDirectory . "Docs/WarningLetter/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/WarningLetter/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/WarningLetter/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/WarningLetter/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/WarningLetter/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/WarningLetter/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'Apology Letter') {
					if (file_exists("../" . $sourceDirectory . "Docs/ApologyLetter/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/ApologyLetter/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/ApologyLetter/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/ApologyLetter/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/ApologyLetter/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/ApologyLetter/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				} else if ($doc_type == 'Apprenticeship') {
					if (file_exists("../" . $sourceDirectory . "Docs/Apprenticeship/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . $sourceDirectory . "Docs/Apprenticeship/" . $value['doc_file'];
					} else
					if (file_exists("../Docs/Apprenticeship/" . $value['doc_file']) && $value['doc_file'] != '') {
						$imsrc = URL . "Docs/Apprenticeship/" . $value['doc_file'];
					}
					$filename = $value['doc_file'];
					if ($filename != '') {
						$imsrc =  ROOT_PATH . $sourceDirectory . "Docs/Apprenticeship/" . $filename;
						$target_dir = ROOT_PATH . $locationdir . 'Docs/Apprenticeship/';
						$target_file = $target_dir . $filename;
						if (!rename($imsrc, $target_file)) {
							echo "File can't be moved!";
						} else {
							echo "File has been moved!";
						}
					}
				}
			}
		}
		//die;
		$updatelocation = 'update personal_details,employee_map,status_table,EmpID_Name set personal_details.location=?, employee_map.cm_id=? ,status_table.ReportTo=?,EmpID_Name.loc=? where personal_details.EmployeeID=? and employee_map.EmployeeID=? and status_table.EmployeeID=? and EmpID_Name.EmpID=?;';
		// $myDB = new MysqliDb();
		// $resultBy = $myDB->query($updatelocation);
		$update = $conn->prepare($updatelocation);
		$update->bind_param("iisissss", $location, $sub_process, $reports_to, $location, $EmployeeID, $EmployeeID, $EmployeeID, $EmployeeID);
		$update->execute();
		$resultBy = $update->get_result();
	}
}
echo 'Done';
