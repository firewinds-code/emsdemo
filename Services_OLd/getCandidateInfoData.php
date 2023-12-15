<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$iid = $tempid = $hrid = $loc = "";

if (isset($_REQUEST['intid']) && trim($_REQUEST['intid']) != "") {
	if ((substr($_REQUEST['intid'], 0, 3) == 'INT') && (is_numeric(substr($_REQUEST['intid'], 3, 15)))) {
		$intid = clean($_REQUEST['intid']);
		$iid = trim($intid);
	}
}
if (isset($_REQUEST['tempid']) && trim($_REQUEST['tempid']) != "") {
	if ((substr($_REQUEST['tempid'], 0, 2) == 'TE') && (is_numeric(substr($_REQUEST['tempid'], 2, 15)))) {
		$tempidd = clean($_REQUEST['tempid']);
		$tempid = trim($tempidd);
	}
}

if (isset($_REQUEST['hrid']) && trim($_REQUEST['hrid']) != "") {
	if (((substr($_REQUEST['hrid'], 0, 2) == 'CE') || (substr($_REQUEST['hrid'], 0, 2) == 'MU')) && (strlen($_REQUEST['hrid']) <= 15)) {
		$hrids = clean($_REQUEST['hrid']);
		$hrid = trim($hrids);
	}
}

if (isset($_REQUEST['loc']) && trim($_REQUEST['loc']) != "") {
	if ((strlen($_REQUEST['loc']) == 1) && is_numeric($_REQUEST['loc'])) {
		$loca = clean($_REQUEST['loc']);
		$loc = trim($loca);
	}
}
//die;
if ($iid != "" && $tempid != "" && $hrid != "" && $loc != "") {
	// echo 'pass';
	// die;
	require_once(__dir__ . '/../Config/init.php');
	// require_once(CLS . 'php_mysql_class.php');
	require_once(CLS . 'MysqliDb.php');
	// error_reporting(E_ALL);
	// ini_set('display_errors', "1");
	date_default_timezone_set('Asia/Kolkata');
	$myDB = new MysqliDb();

	$imsrc = CANDIDATE_INFO_URL . "checklist_pdf/" . $iid . ".pdf";
	$filename = $iid . ".pdf";
	echo $loc . ' - ';

	if ($loc == "1" || $loc == "2") {
		$dir_location = '';
	} else if ($loc == "3") {
		$dir_location = 'Meerut/';
	} else if ($loc == "4") {
		$dir_location = "Bareilly/";
	} else if ($loc == "5") {
		$dir_location = "Vadodara/";
	} else if ($loc == "6") {
		$dir_location = "Manglore/";
	} else if ($loc == "7") {
		$dir_location = "Bangalore/";
	} else if ($loc == "8") {
		$dir_location = "Banglore_Fk/";
	}

	$target_dir = ROOT_PATH . $dir_location . 'checklist_pdf/';
	//echo $target_dir;
	$target_file = $target_dir . $filename;

	if (copy($imsrc, $target_file)) {
		echo "Checklist file copied";
	} else {
		echo "Checklist file not copied";
	}
	echo "<br>";


	$fullname = '';
	$url2 = CANDIDATE_INFO_URL . "getData/getProfileList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url2);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data2 = curl_exec($curl);
	$data_array2 = json_decode($data2);
	if (count($data_array2) > 0) {
		foreach ($data_array2 as $key => $value) {
			$fullname = $value->fname;
			if ($value->mname != "") {
				$fullname .= ' ' . $value->mname;
			}
			$fullname .= ' ' . $value->lname;
			echo "<br>";
			$sourceAadhar = trim($value->adharSource_from);
			echo  $insertData = "call manageProfileData('" . addslashes($value->fname) . "','" . addslashes($value->mname) . "','" . addslashes($value->lname) . "','" . addslashes($fullname) . "','" . $value->dob . "','" . $value->gender . "','" . addslashes($value->father_name) . "','" . addslashes($value->mother_name) . "','" . $value->blood_group . "','" . $value->marital_status . "','" . $value->AdharcardNumber . "','" . $value->Adharcardfront . "','" . $value->total_experience . "','" . $value->ProfileImage . "','" . $value->primary_language . "','" . $value->secondary_language . "','" . $tempid . "','" . $iid . "','" . $hrid . "','" . $value->location . "','" . $value->marraige_date . "','" . $value->spouse_name . "','" . $value->child_status . "','" . $value->spouse_dob . "','" . $value->nominee_name . "','" . $value->nominee_relation . "','" . $value->father_dob . "','" . $value->mother_dob . "','" . $value->dstatus . "')";

			$myDB->query($insertData);
			echo "<br>";

			if ($value->Adharcardfront != "") {
				echo $insertDocData = "call manageDocData('Proof of Address','Aadhar Card','" . $value->AdharcardNumber . "','" . $value->Adharcardfront . "','" . $tempid . "','" . $hrid . "','" . $iid . "','" . $sourceAadhar . "')";
				$myDB = new MysqliDb();
				$myDB->query($insertDocData);
				echo 'MySql Error1 -: ' . $myDB->getLastError();
				echo "<br>";
				$imsrc = CANDIDATE_INFO_URL . "aadharCard/" . $value->Adharcardfront;
				$filename = $value->Adharcardfront;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/AdharCard/';
				$target_file = $target_dir . $filename;
				if (copy($imsrc, $target_file)) {
					echo "Aadharcardfront file copied";
				} else {
					echo "Aadharcardfront not copied copied";
				}
				echo "<br>";
			}

			if ($sourceAadhar != 'chooseFromAdharAPI') {
				if ($value->Adharcardback != "") {
					$imsrc = CANDIDATE_INFO_URL . "aadharCard/" . $value->Adharcardback;
					$filename = $value->Adharcardback;

					$target_dir = ROOT_PATH . $dir_location . 'Docs/AdharCard/';

					$target_file = $target_dir . $filename;
					if (copy($imsrc, $target_file)) {
						echo "Aadhar Aadharcardback copied";
					} else {
						echo "Aadharcardback  file not copied copied";
					}
					echo "<br>";

					echo $insertDocData = "call manageDocData('Proof of Address','Aadhar Card','" . $value->AdharcardNumber . "','" . $value->Adharcardback . "','" . $tempid . "','" . $hrid . "','" . $iid . "','" . $sourceAadhar . "')";
					echo "<br>";
					$myDB = new MysqliDb();
					$myDB->query($insertDocData);
					if ($myDB->getLastError() != "") {
						echo 'MySql Error1 -: ' . $myDB->getLastError();
						echo "<br>";
					}
				}
			}

			if ($value->resume != "") {
				$imsrc = CANDIDATE_INFO_URL . "resume/" . $value->resume;
				$filename = $value->resume;
				$target_dir = ROOT_PATH . $dir_location . 'Resume/';

				$target_file = $target_dir . $filename;
				if (copy($imsrc, $target_file)) {
					echo "Resume copied";
				} else {
					echo "Resume not copied";
				}
				echo "<br>";

				echo $insertResumeData = "call manageDocData('Other','Curriculum Vitae','Resume','" . $value->resume . "','" . $tempid . "','" . $hrid . "','" . $iid . "','')";
				echo "<br>";
				$myDB = new MysqliDb();
				$myDB->query($insertResumeData);
				if ($myDB->getLastError() != "") {
					echo 'MySql Error1 -: ' . $myDB->getLastError();
					echo "<br>";
				}
			}


			if ($value->chk_list != '') {
				echo $insertResumeData = "call manageDocData('Other','CheckList','CheckList','" . $value->chk_list . "','" . $tempid . "','" . $hrid . "','" . $iid . "','')";
				echo "<br>";
				$myDB = new MysqliDb();
				$myDB->query($insertResumeData);
				if ($myDB->getLastError() != "") {
					echo 'MySql Error1 -: ' . $myDB->getLastError();
					echo "<br>";
				}
			}
			if ($value->pancardno != '') {
				$imsrc = CANDIDATE_INFO_URL . "panCard/" . $value->pancardfile;
				$filename = $value->pancardfile;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/IdentityProof/';
				$target_file = $target_dir . $filename;
				if (copy($imsrc, $target_file)) {
					echo "PAN Card copied";
				} else {
					echo "PAN Card not copied";
				}
				echo "<br>";
				echo $insertResumeData = "call manageDocData('Proof of Identity','PAN Card','" . $value->pancardno . "','" . $value->pancardfile . "','" . $tempid . "','" . $hrid . "','" . $iid . "','')";
				echo "<br>";
				$myDB = new MysqliDb();
				$myDB->query($insertResumeData);
				if ($myDB->getLastError() != "") {
					echo 'MySql Error1 -: ' . $myDB->getLastError();
					echo "<br>";
				}
			}


			if ($value->ProfileImage != "") {
				$imsrc = CANDIDATE_INFO_URL . "aadharCard/" . $value->ProfileImage;
				$filename = $value->ProfileImage;
				$target_dir = ROOT_PATH . $dir_location . 'Images/';
				$target_file = $target_dir . $filename;
				if (copy($imsrc, $target_file)) {
					echo "profile image copied";
				} else {
					echo "profile image not copied copied";
				}
				echo "<br>";
			}
		}
	}


	$url3 = CANDIDATE_INFO_URL . "getData/getChildList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url3);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data3 = curl_exec($curl);
	$data_array3 = array();
	if ($data3 != "") {
		$data_array3 = json_decode($data3);
	}

	if (count($data_array3) > 0) {
		//print_r($data_array3); 
		foreach ($data_array3 as $key => $value) {
			echo $insertData = "call manageChildData('" . addslashes($value->ChildName) . "','" . addslashes($value->ChildDob) . "','" . addslashes($value->BloodGroup) . "','" . addslashes($value->ChildGender) . "','" . addslashes($value->Docs) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
			if ($value->Docs != "") {
				$imsrc = CANDIDATE_INFO_URL . "childDoc/" . $value->Docs;
				echo "<br>";
				$filename = $value->Docs;
				$target_dir = ROOT_PATH . $dir_location . 'childDoc/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo " ChildDocs copied ";
				} else {
					echo "ChildDocs not  copied";
				}
				echo "<br>";
			}
		}
	}


	$url3 = CANDIDATE_INFO_URL . "getData/getDependList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url3);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data3 = curl_exec($curl);
	$data_array3 = array();
	if ($data3 != "") {
		$data_array3 = json_decode($data3);
	}

	if (count($data_array3) > 0) {
		//print_r($data_array3); 
		foreach ($data_array3 as $key => $value) {
			echo $insertData = "call manageDependData('" . addslashes($value->DependentName) . "','" . addslashes($value->DependentDob) . "','" . addslashes($value->Relation) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
		}
	}

	echo "<br>";

	$url3 = CANDIDATE_INFO_URL . "getData/getBankList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url3);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data3 = curl_exec($curl);
	$data_array3 = json_decode($data3);
	if (count($data_array3) > 0) {
		//print_r($data_array3); 
		foreach ($data_array3 as $key => $value) {
			echo $insertData = "call manageBankData('" . addslashes($value->BankName) . "','" . addslashes($value->AccountNo) . "','" . addslashes($value->Branch) . "','" . addslashes($value->Location) . "','" . addslashes($value->IFSC_code) . "','" . addslashes($value->name_asper_bank) . "','" . addslashes($value->check_image) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
			if ($value->check_image != "") {
				$imsrc = CANDIDATE_INFO_URL . "BankDocs/" . $value->check_image;
				echo "<br>";
				$filename = $value->check_image;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/BankDocs/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo " BankDocs copied ";
				} else {
					echo "BankDocs not  copied";
				}
				echo "<br>";
			}
		}
	}
	echo "<br>";



	$url4 = CANDIDATE_INFO_URL . "getData/getAddressList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url4);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data4 = curl_exec($curl);
	$data_array4 = json_decode($data4);
	if (count($data_array4) > 0) {
		foreach ($data_array4 as $key => $value) {
			echo $insertData = "call manageAddressData('" . addslashes($value->address) . "','" . addslashes($value->state) . "','" . addslashes($value->district) . "','" . addslashes($value->tehsil) . "','" . $value->pincode . "','" . addslashes($value->landmark) . "','" . addslashes($value->address_p) . "','" . addslashes($value->state_p) . "','" . addslashes($value->district_p) . "','" . addslashes($value->tehsil_p) . "','" . addslashes($value->pincode_p) . "','" . addslashes($value->landmark_p) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
		}
	}
	//echo "<br>";echo "<br>";
	$url5 = CANDIDATE_INFO_URL . "getData/getEducationList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url5);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data5 = curl_exec($curl);
	$data_array5 = json_decode($data5);
	if (count($data_array5) > 0) {
		foreach ($data_array5 as $key => $value) {
			echo $insertData = "call manageEducationData('" . addslashes($value->edu_level) . "','" . addslashes($value->edu_name) . "','" . addslashes($value->specialization) . "','" . addslashes($value->board) . "','" . addslashes($value->college) . "','" . addslashes($value->edu_type) . "','" . addslashes($value->edu_file) . "','" . addslashes($value->percentage) . "','" . addslashes($value->passing_year) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();

			$myDB->query($insertData);
			if ($value->edu_file != "") {

				$imsrc = CANDIDATE_INFO_URL . "Education/" . $value->edu_file;
				$filename = $value->edu_file;
				$target_dir = ROOT_PATH . $dir_location . 'Edu/';

				echo $target_file = $target_dir . $filename;
				if (copy($imsrc, $target_file)) {
					echo "Education image copied";
				} else {
					echo "Education  not copied";
				}
				echo "<br>";
			}
		}
	}
	echo "<br>";
	echo "<br>";
	$url6 = CANDIDATE_INFO_URL . "getData/getExperienceList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url6);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data6 = curl_exec($curl);
	$data_array6 = json_decode($data6);
	if (count($data_array6) > 0) {
		foreach ($data_array6 as $key => $value) {
			$insertData = "call manageExperienceData('" . $value->exp_type . "','" . addslashes($value->employer) . "','" . $value->releiving_experience_doc . "','" . $value->appointment_offerletter_doc . "','" . $value->salaryslip_bankstatement_doc . "','" . addslashes($value->contact_person) . "','" . $value->contact_person_no . "','" . $value->location . "','" . $value->from_date . "','" . $value->to_date . "','" . addslashes($value->ClientIndustry) . "','" . addslashes($value->designation) . "','" . addslashes($value->ctc) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
			echo "<br>";
			if ($value->releiving_experience_doc != "") {
				$imsrc = CANDIDATE_INFO_URL . "experiencedoc/" . $value->releiving_experience_doc;
				echo "<br>";
				$filename = $value->releiving_experience_doc;


				$target_dir = ROOT_PATH . $dir_location . 'Docs/Experience/';


				$target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "Releiving experience doc copied";
				} else {
					echo "Releiving experience doc not  copied";
				}
				echo "<br>";
			}
			if ($value->appointment_offerletter_doc != "") {
				$imsrc = CANDIDATE_INFO_URL . "experiencedoc/" . $value->appointment_offerletter_doc;
				echo "<br>";
				$filename = $value->appointment_offerletter_doc;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/offerletter/';
				$target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "Appointment_offerletter_doc copied";
				} else {
					echo "Appointment_offerletter_doc not copied copied";
				}
				echo "<br>";
			}
			if ($value->salaryslip_bankstatement_doc != "") {
				$imsrc = CANDIDATE_INFO_URL . "experiencedoc/" . $value->salaryslip_bankstatement_doc;
				echo "<br>";
				$filename = $value->salaryslip_bankstatement_doc;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/salaryslip/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "Salaryslip bankstatement doc copied";
				} else {
					echo "Salaryslip bankstatement doc not copied copied";
				}
				echo "<br>";
			}
			echo "<br>";
			echo "<br>";
		}
	}

	/* info details */
	echo "<br>";
	echo "<br>";
	$url7 = CANDIDATE_INFO_URL . "getData/getInfraList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url7);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data7 = curl_exec($curl);
	$data_array7 = json_decode($data7);
	if (count($data_array7) > 0) {
		foreach ($data_array7 as $key => $value) {
			echo  $insertData = "call `manageInfra`('" . $value->sys_name . "','" . $value->sys_available . "','" . addslashes($value->sys_processor) . "','" . $value->sys_img . "','" . $value->internet_avail . "','" . $value->internet_type . "','" . $value->service_provider . "','" . $value->internet_plan . "','" . $value->inlanproof_doc . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
			echo "<br>";
			if ($value->inlanproof_doc != "") {
				$imsrc = CANDIDATE_INFO_URL . "InternetDocs/" . $value->inlanproof_doc;
				echo "<br>";
				$filename = $value->inlanproof_doc;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/InternetDocs/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "Internet Document proof  copied";
				} else {
					echo "Internet Document proof not  copied";
				}
				echo "<br>";
			}
			if ($value->sys_img != "") {
				$imsrc = CANDIDATE_INFO_URL . "InfraDocs/" . $value->sys_img;
				echo "<br>";
				$filename = $value->sys_img;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/InfraDocs/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "system availability proof copied";
				} else {
					echo "system availability proof not  copied";
				}
				echo "<br>";
			}

			echo "<br>";
			echo "<br>";
		}
	}
	/* End info details */

	$url = CANDIDATE_INFO_URL . "getData/getContactList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data = curl_exec($curl);
	$data_array = json_decode($data);
	if (count($data_array) > 0) {
		foreach ($data_array as $key => $value) {
			$insertData = "call manageContactData('" . $value->mobile . "','" . $value->alt_mobile . "','" . $value->em_mobile . "','" . $value->email_id . "','" . addslashes($value->relation) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
			@$response = file_get_contents("http://192.168.220.35/outcallhiring/admin/ChangeStatus.php?ani=" . $value->mobile . "&flg=FlgTurned");
		}
	} else {
		echo "Data not found";
	}

	$url7 = CANDIDATE_INFO_URL . "getData/getTestScore.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url7);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data7 = curl_exec($curl);
	$data_array7 = array();
	if ($data7 != "") {
		$data_array7 = json_decode($data7);
	}

	if (count($data_array7) > 0) {
		foreach ($data_array7 as $key => $value) {
			$insertData = "call manageTestScore('" . addslashes($value->test_name) . "','" . addslashes($value->testid) . "','" . $value->test_score . "','" . addslashes($value->file) . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
			if ($value->file != "") {
				echo "<br>";
				$imsrc = CANDIDATE_INFO_URL . "TestDocs/" . $value->file;
				$filename = $value->file;
				$target_dir = ROOT_PATH . $dir_location . 'TestDocs/';


				echo $target_file = $target_dir . $filename;
				if (copy($imsrc, $target_file)) {
					echo "Test Score image copied";
				} else {
					echo "Test Score not copied";
				}
				echo "<br>";
			}
		}
	}

	/* Vehicel details */
	echo "<br>";
	echo "<br>";
	$url7 = CANDIDATE_INFO_URL . "getData/getVehicleList.php?iid=" . $iid;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url7);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data7 = curl_exec($curl);
	$data_array7 = json_decode($data7);
	if (count($data_array7) > 0) {
		foreach ($data_array7 as $key => $value) {
			echo  $insertData = "call `manageVehicle`('" . $value->dl_no . "','" . $value->dl_name . "','" . addslashes($value->dl_docs) . "','" . $value->rc_no . "','" . $value->rc_name . "','" . $value->rc_docs . "','" . $value->vehicle_docs . "','" . $tempid . "','" . $hrid . "','" . $iid . "')";
			$myDB = new MysqliDb();
			$myDB->query($insertData);
			echo "<br>";
			if ($value->dl_docs != "") {
				$imsrc = CANDIDATE_INFO_URL . "VehicleDocs/" . $value->dl_docs;
				echo "<br>";
				$filename = $value->dl_docs;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/VehicleDocs/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "DL Document proof  copied";
				} else {
					echo "DL Document proof not  copied";
				}
				echo "<br>";
			}
			if ($value->rc_docs != "") {
				$imsrc = CANDIDATE_INFO_URL . "VehicleDocs/" . $value->rc_docs;
				echo "<br>";
				$filename = $value->rc_docs;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/VehicleDocs/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "RC proof copied";
				} else {
					echo "RC proof not  copied";
				}
				echo "<br>";
			}

			if ($value->vehicle_docs != "") {
				$imsrc = CANDIDATE_INFO_URL . "VehicleDocs/" . $value->vehicle_docs;
				echo "<br>";
				$filename = $value->vehicle_docs;
				$target_dir = ROOT_PATH . $dir_location . 'Docs/VehicleDocs/';
				echo $target_file = $target_dir . $filename;
				if (@copy($imsrc, $target_file)) {
					echo "vehicle proof copied";
				} else {
					echo "vehicle proof not  copied";
				}
				echo "<br>";
			}


			echo "<br>";
			echo "<br>";
		}
	}
	/* End Vehicel details */
} else {
	echo 'parameter is missing';
}
