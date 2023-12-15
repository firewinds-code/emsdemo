<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form
require(ROOT_PATH . 'AppCode/nHead.php');
$createBy = $_SESSION['__user_logid'];
$imsrc = URL . 'Style/images/agent-icon.png';
$EmployeeID = $btnShow = '';
//$val_stype='FnF';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$loc = '';

function getPath($val_stype)
{
	//$val_stype=$GLOBALS['val_stype'];
	$rootloc = '';
	if ($_POST['loc'] == "1" || $_POST['loc'] == "2") {
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Docs/IdentityProof/";
		}
	} else if ($_POST['loc'] == "3") {
		$rootloc = "Meerut";
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Meerut/Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Meerut/Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Meerut/Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Meerut/Docs/IdentityProof/";
		}
	} else if ($_POST['loc'] == "4") {
		$rootloc = "Bareilly";
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Bareilly/Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Bareilly/Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Bareilly/Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Bareilly/Docs/IdentityProof/";
		}
	} else if ($_POST['loc'] == "5") {
		$rootloc = "Vadodara";
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Vadodara/Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Vadodara/Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Vadodara/Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Vadodara/Docs/IdentityProof/";
		}
	} else if ($_POST['loc'] == "6") {
		$rootloc = "Manglore";
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Manglore/Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Manglore/Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Manglore/Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Manglore/Docs/IdentityProof/";
		}
	} else if ($_POST['loc'] == "7") {
		$rootloc = "Bangalore";
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Bangalore/Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Bangalore/Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Bangalore/Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Bangalore/Docs/IdentityProof/";
		}
	} else if ($_POST['loc'] == "8") {
		$rootloc = "Nashik";
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Nashik/Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Nashik/Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Nashik/Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Nashik/Docs/IdentityProof/";
		}
	} else if ($_POST['loc'] == "9") {
		$rootloc = "Anantapur";
		if ($val_stype == 'Proof of Identity') {
			return $filePath = ROOT_PATH . "Anantapur/Docs/IdentityProof/";
		} else if ($val_stype == 'Proof of Address') {
			return $filePath = ROOT_PATH . "Anantapur/Docs/AddressProof/";
		} else if ($val_stype == 'FnF') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/FnF/";
		} else if ($val_stype == 'Apology Letter') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/ApologyLetter/";
		} else if ($val_stype == 'Warning Letter') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/WarningLetter/";
		} else if ($val_stype == 'Other') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/Other/";
		} else if ($val_stype == 'BGV Report') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/BGV/";
		} else if ($val_stype == 'Call Log') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/CallLog/";
		} else if ($val_stype == 'Covid 19') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/Covid19/";
		} else if ($val_stype == 'Apprenticeship') {
			return $filePath  = ROOT_PATH . "Anantapur/Docs/Apprenticeship/";
		} else {
			return $filePath = ROOT_PATH . "Anantapur/Docs/IdentityProof/";
		}
	}
}
if (isset($_REQUEST['empid'])) {
	$EmployeeID = $_REQUEST['empid'];
}
$loc = $clientid = '';
$getDetails = 'call get_personal("' . $EmployeeID . '")';
$myDB = new MysqliDb();
$result_all = $myDB->query($getDetails);
if ($result_all) {
	$loc = $result_all[0]['location'];
	$getDetails = 'select t3.client_id from employee_map t1 join new_client_master t2 on t1.cm_id=t2.cm_id join client_master t3 on t2.client_name=t3.client_id where t1.EmployeeID="' . $EmployeeID . '"';
	$result_all = $myDB->query($getDetails);
	if ($result_all) {
		$clientid = $result_all[0]['client_id'];
	}
} else {
	echo "<script>$(function(){ toastr.error('Wrong Employee To Search') }); window.location='" . URL . "'</script>";
}
if (isset($_POST['btn_document_add']) && $EmployeeID != '' && $_POST['documentid'] == "") {

	$myDB = new MysqliDb();
	$file_counter = 0;
	if (is_array($_FILES)) {
		$count = 0;
		foreach ($_FILES['txt_doc_name_']['name'] as $name => $value) {
			$count++;
			$filepath = '';
			if (is_uploaded_file($_FILES['txt_doc_name_']['tmp_name'][$name])) {
				$sourcePath = $_FILES['txt_doc_name_']['tmp_name'][$name];
				$val_type = trim($_POST['txt_doc_type_' . $count]);
				$val_stype = trim($_POST['txt_doc_stype_' . $count]);
				$docVal = trim($_POST['txt_doc_value_' . $count]);
				$filePath = getPath($val_type);
				$targetPath = $filePath . basename($_FILES['txt_doc_name_']['name'][$name]);
				$FileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

				$uploadOk = 1;
				$validation_check = 0;


				if ($val_type == 'BGV Report' || $val_type == 'Covid 19' || $val_stype == 'Curriculum Vitae' || $val_stype == 'WFH Policy' || $val_stype == 'System Undertaking' || $val_stype == 'Apprenticeship' || $val_stype == 'Signature') {
					if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf" && $FileType != "msg") {
						echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,png,msg and pdf files are allowed.'); }); </script>";
						$uploadOk = 0;
					}
					if ($val_type == 'BGV Report' && ($_FILES['txt_doc_name_']['size'][$name] > 6000000)) {
						echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 6MB File only.'); }); </script>";
						$uploadOk = 0;
					}
				} else if ($val_type == 'Call Log') {
					if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf" && $FileType != "msg" && $FileType != "wav" && $FileType != "mp3") {
						echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,png,wav,mp3,msg and pdf files are allowed.'); }); </script>";
						$uploadOk = 0;
					}
					if ($_FILES['txt_doc_name_']['size'][$name] > 1000000) {
						echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 1MB File only.'); }); </script>";
						$uploadOk = 0;
					}
				} else {


					// Check file size
					if ($_FILES['txt_doc_name_']['size'][$name] > 1000000) {
						echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 1MB File only.'); }); </script>";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf" && $FileType != "msg") {
						echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,pdf,msg and png files are allowed.'); }); </script>";
						$uploadOk = 0;
					}
				}
				if ($val_stype == 'Aadhar Card' || $val_stype == 'PAN Card') {

					if (empty($_POST['txt_doc_value_' . $count]) || ($val_stype == 'Aadhar Card' && (strlen($_POST['txt_doc_value_' . $count]) != 12 || !is_numeric($_POST['txt_doc_value_' . $count])))) {
						$validation_check = 1;
					} else {
						$myDB = new MysqliDb();
						$data_calid = $myDB->query('SELECT  whole_details_peremp.EmployeeID FROM whole_details_peremp INNER JOIN doc_details ON doc_details.EmployeeID = whole_details_peremp.EmployeeID where trim(doc_details.dov_value) = trim("' . $_POST['txt_doc_value_' . $count] . '") and doc_details.doc_stype = "' . $val_stype . '" and doc_details.doc_type = "' . $val_type . '"');
						if (count($data_calid) > 0) {
							$validation_check = 1;
						}
					}
				}

				$myDB = new MysqliDb();
				//echo "SELECT EmployeeID  from doc_details where doc_type='".$val_type."' and  doc_stype='".$val_stype."' and  dov_value='".$docVal."'and  EmployeeID='".$EmployeeID."'";
				$data_valid = $myDB->query("SELECT EmployeeID  from doc_details where doc_type='" . $val_type . "' and  doc_stype='" . $val_stype . "' and  dov_value='" . $docVal . "'and  EmployeeID='" . $EmployeeID . "'");
				if (count($data_valid) > 0) {
					$validation_check = 1;
				}
				if ($validation_check === 0 && $uploadOk == 1) {
					if (move_uploaded_file($sourcePath, $targetPath)) {
						$ext = pathinfo(basename($_FILES['txt_doc_name_']['name'][$name]), PATHINFO_EXTENSION);
						$filename = $EmployeeID . preg_replace('/\s+/', '', $_POST['txt_doc_stype_' . $count]) . '_' . date("mdY_s") . '.' . $ext;
						$file = rename($targetPath, $filePath . $filename);
						if (file_exists($filePath . $filename)) {
							$myDB = new MysqliDb();
							$sqlInsertDoc = 'call insert_empdoc("' . $_POST['txt_doc_type_' . $count] . '","' . $_POST['txt_doc_stype_' . $count] . '","' . $_POST['txt_doc_value_' . $count] . '","' . $filename . '","' . $EmployeeID . '")';
							$result = $myDB->rawQuery($sqlInsertDoc);
							$row_count = $myDB->count;
							if ($row_count > 0) {
								echo "<script>$(function(){ toastr.success('The file " . $filename . " has been uploaded.') });</script>";
								$file_counter++;
							}
						}
					} else {
						echo "<script>$(function(){ toastr.error('The file " . $filename . " not uploaded.') });</script>";
					}
				} else {
					if ($uploadOk) {
						echo "<script>$(function(){ toastr.error('Document type " . $_POST['txt_doc_stype_' . $count] . " not updated. May be document is alredy exist or the value you entered is not valid') });</script>";
					}
				}
			}
		}
	}
	if ($file_counter > 0) {
		echo "<script>$(function(){ toastr.success('Contact is Saved Successfully Document Uploaded Count is " . $file_counter . "') });</script>";
	}
}
if (isset($_POST['btn_document_save']) && $EmployeeID != '' && $_POST['documentid'] != "") {
	$txt_doc_type = '';
	$txt_doc_value = '';
	$txt_doc_stype = '';
	$val_stype = '';
	if (isset($_POST['txt_doc_type_1'])) {
		$txt_doc_type = $val_type = $_POST['txt_doc_type_1'];
	} else {
		$val_type = 'Proof of Identity';
	}
	if (isset($_POST['txt_doc_stype_1'])) {
		$txt_doc_stype = $_POST['txt_doc_stype_1'];
	}
	if (isset($_POST['txt_doc_value_1'])) {
		$txt_doc_value = $_POST['txt_doc_value_1'];
	}
	if (isset($_FILES['txt_doc_name_']['name'][0]) && $_FILES['txt_doc_name_']['name'][0] != "") {
		$filePath = getPath($val_type);
		$sourcePath = $_FILES['txt_doc_name_']['tmp_name'][0];
		$targetPath = $filePath . basename($_FILES['txt_doc_name_']['name'][0]);
		$FileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
		$uploadOk = 1;
		$validation_check = 0;
		if ($val_type == 'BGV Report' || $val_type == 'Covid 19' || $txt_doc_stype == 'Curriculum Vitae' || $txt_doc_stype === 'WFH Policy' || $txt_doc_stype == 'System Undertaking' || $txt_doc_stype == 'Apprenticeship' || $txt_doc_stype == 'Signature') {

			if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf" && $FileType != "msg") {
				echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,png,msg and pdf files are allowed.'); }); </script>";
				$uploadOk = 0;
			}
			if ($val_type == 'BGV Report' && ($_FILES['txt_doc_name_']['size'][0] > 6000000)) {
				echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 6MB File only.'); }); </script>";
				$uploadOk = 0;
			}
		} else if ($val_type == 'Call Log') {
			if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf" && $FileType != "wav" && $FileType != "mp3" && $FileType != "msg") {
				echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,png,wav,mp3,msg and pdf files are allowed.'); }); </script>";
				$uploadOk = 0;
			}
			if ($_FILES['txt_doc_name_']['size'][0] > 1000000) {
				echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 1MB File only.'); }); </script>";
				$uploadOk = 0;
			}
		} else {

			if ($_FILES['txt_doc_name_']['size'][0] > 1000000) {
				echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 1MB File only.'); }); </script>";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf" && $FileType != "msg") {
				echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg,pdf,msg and png files are allowed.'); }); </script>";
				$uploadOk = 0;
			}
		}
		if ($validation_check === 0 && $uploadOk == 1) {
			if (move_uploaded_file($sourcePath, $targetPath)) {
				//echo "targetPath".$targetPath;
				//echo "<br>";
				$ext = pathinfo(basename($_FILES['txt_doc_name_']['name'][0]), PATHINFO_EXTENSION);
				if (isset($_POST['txt_doc_stype_1'])) {
					$filename = $EmployeeID . preg_replace('/\s+/', '', $_POST['txt_doc_stype_1']) . '_' . date("mdY_s") . '.' . $ext;
				} else {
					$filename = $EmployeeID . 'AdharCard_' . date("mdY_s") . '.' . $ext;
				}

				$file = rename($targetPath, $filePath . $filename);
				if (file_exists($filePath . $filename)) {

					$myDB = new MysqliDb();
					$selectDoc = $myDB->rawQuery("select doc_type,doc_file from doc_details where doc_id='" . $_POST['documentid'] . "'"); //echo "select doc_type,doc_file from doc_details where doc_id='".$_POST['documentid']."'";echo "<br>";
					$val_type = '';
					$efilename = '';
					if (count($selectDoc) > 0) {
						$val_type = $selectDoc[0]['doc_type'];
						$efilename = $selectDoc[0]['doc_file'];
					}
					$efilePath = getPath($val_type);

					if (file_exists($efilePath . $efilename)) {
						@unlink($efilePath . $efilename);
					}
					$myDB = new MysqliDb();
					$updateDocument = "update doc_details set doc_file='" . $filename . "' where doc_id='" . $_POST['documentid'] . "'";
					$result = $myDB->rawQuery($updateDocument);
					$row_count = $myDB->count;
					echo "<script>$(function(){ toastr.success('The file " . $filename . " has been updated.') });</script>";
				}
			}
		}
	}
	$updateDocument = "update doc_details set modifiedon=NOW()";
	if ($txt_doc_value != '') {
		$updateDocument .= ", dov_value='" . $txt_doc_value . "'";
	}
	if ($txt_doc_stype != '') {
		$updateDocument .= ", doc_stype='" . $txt_doc_stype . "' ";
	}
	if ($txt_doc_type != '') {
		$updateDocument .= ", doc_type='" . $txt_doc_type . "' ";
	}
	$updateDocument .= " where doc_id='" . $_POST['documentid'] . "'";
	$myDB = new MysqliDb();

	$result = $myDB->rawQuery($updateDocument);
	echo "<script>$(function(){ toastr.success('Doc info updated.') });</script>";
}
?>

<script>
	$(document).ready(function() {
		var usrtype = <?php echo "'" . $_SESSION["__user_type"] . "'"; ?>;
		var usrid = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;
		if (usrtype === 'ADMINISTRATOR' || usrtype === 'HR' || usrid == 'CE12102224' || usrid == 'CE01145570' || usrid == 'CE121622565') {} else if (usrtype === 'AUDIT') {
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

		function validateEmail(sEmail) {
			var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
			if (filter.test(sEmail)) {
				return true;
			} else {
				return false;
			}
		}
		$('#txt_contatc_email').keyup(function() {
			if (validateEmail($(this).val())) {
				$('#txt_contatc_email').removeClass('has-error').addClass('has-success');
				$('#mailcheck').html('Valid Mail').css('color', 'green');
			} else {
				$('#txt_contatc_email').addClass('has-error');
				$('#mailcheck').html('Invalid Mail').css('color', 'red');
			}
		});
		$('#txt_contatc_ofcemail').keyup(function() {
			if ($(this).val() != "") {
				if (validateEmail($(this).val())) {
					var str = $(this).val();
					var words = str.split('@');
					if (words[1].toLowerCase() == 'cogenteservices.com' || words[1].toLowerCase() == 'cogenteservices.in') {
						$('#txt_contatc_ofcemail').removeClass('has-error').addClass('has-success');
						$('#mailcheck1').html('Valid Mail').css('color', 'green');
					} else {
						$('#txt_contatc_ofcemail').addClass('has-error');
						$('#mailcheck1').html('Invalid Mail').css('color', 'red');
					}
				} else {
					$('#txt_contatc_ofcemail').addClass('has-error');
					$('#mailcheck1').html('Invalid Mail').css('color', 'red');
				}
			}
		});
		$('#txt_contatc_mob,#txt_contatc_altmob,#txt_contatc_em_contact').keydown(function(event) {
			if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||

				// Allow: Ctrl+A
				(event.keyCode == 65 && event.ctrlKey === true) ||

				// Allow: Ctrl+V
				(event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

				// Allow: Ctrl+c
				(event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

				// Allow: Ctrl+x
				(event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

				// Allow: home, end, left, right
				(event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
			} else {
				// Ensure that it is a number and stop the keypress
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
					event.preventDefault();
				}
			}
		});

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Document Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">
		<?php include('shortcutLinkEmpProfile.php'); ?>
		<!-- Sub Main Div for all Page -->
		<div class="form-div">
			<!-- Header for Form If any -->
			<h4>Document Details</h4>
			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				if ($EmployeeID == '' && empty($EmployeeID)) {
					echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
					exit();
				}
				?>
				<input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID; ?>" />
				<input type="hidden" name="loc" id="loc" value="<?php echo $loc; ?>" />

				<div class="input-field col s12 m12" id="childtables">
					<input type="hidden" id="Document Details" name="doc_child" />
					<div class="form-inline addChildbutton " style="margin-bottom: 10px;">
						<div class="form-group">
							<button type="button" name="btn_docAdd" id="btn_docAdd" title="Add Doc Row in Table Down" class="btn waves-effect waves-green">
								<i class="fa fa-plus"></i> Add Document</button>
							<button type="button" name="btnDoccan" id="btnDoccan" title="Remove Doc Row in Table Down" class="btn waves-effect modal-action modal-close waves-red close-btn">
								<i class="fa fa-minus"></i> Remove Document</button>
						</div>
					</div>
					<table class="table table-hovered table-bordered" id="childtable">
						<thead class="bg-danger">
							<tr>
								<th class="hidden">Doc ID</th>
								<th>Document File</th>
								<th>Document Name</th>
								<th>Document Type</th>
								<th>Document ID</th>
							</tr>
						</thead>
						<tbody>
							<tr class="trdoc" id="trdoc_1">
								<td class="doccount hidden">1</td>
								<td><input name="txt_doc_name_[]" type="file" id="txt_doc_name_1" class="form-control clsInput file_input" /></td>
								<td>
									<select name="txt_doc_type_1" id="txt_doc_type_1">
										<option value="Proof of Identity">Proof of Identity</option>
										<option value="Proof of Address">Proof of Address</option>
										<option value="FnF">FnF</option>
										<option value="Apology Letter">Apology Letter</option>
										<option value="Warning Letter">Warning Letter</option>
										<option value="BGV Report">BGV Report</option>
										<option value="Call Log">Call Log</option>
										<option value="Covid 19">Covid 19</option>
										<option value="Apprenticeship">Apprenticeship</option>
										<option value="Other">Other</option>
									</select>
								</td>
								<td>
									<select name="txt_doc_stype_1" id="txt_doc_stype_1">
										<option value="PAN Card">PAN Card</option>
										<option value="Passport">Passport</option>
										<option value="Voter ID Card">Voter ID Card</option>
										<option value="Bank Passbook">Bank Passbook</option>
										<!--<option value="Aadhar Card">Adhar Card</option>-->
									</select>
								</td>
								<td><input type="text" value="" name="txt_doc_value_1" id="txt_doc_value_1" maxlength="20" /></td>
							</tr>
							<tr class="trdoc htrdoc" id="trdoc_2">
								<td class="doccount hidden">2</td>
								<td><input name="txt_doc_name_[]" type="file" id="txt_doc_name_2" class="form-control clsInput file_input" /></td>
								<td>
									<select name="txt_doc_type_2" id="txt_doc_type_2">
										<option value="Proof of Identity">Proof of Identity</option>
										<option value="Proof of Address">Proof of Address</option>
										<option value="FnF">FnF</option>
										<option value="Apology Letter">Apology Letter</option>
										<option value="Warning Letter">Warning Letter</option>
										<option value="BGV Report">BGV Report</option>
										<option value="Call Log">Call Log</option>
										<option value="Covid 19">Covid 19</option>
										<option value="Apprenticeship">Apprenticeship</option>
										<option value="Other">Other</option>

									</select>
								</td>
								<td>
									<select name="txt_doc_stype_2" id="txt_doc_stype_2">
										<option>PAN Card</option>
										<option>Passport</option>
										<option>Voter ID Card</option>
										<option>Bank Passbook</option>
										<!--<option value="Aadhar Card">Adhar Card</option>-->
									</select>
								</td>
								<td><input type="text" value="" name="txt_doc_value_2" id="txt_doc_value_2" maxlength="20" /></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="input-field col s12 m12 right-align">
					<input type='hidden' name='documentid' id='documentid'>
					<button type="submit" title="Update Details" name="btn_document_add" id="btn_document_add" class="btn waves-effect waves-green">Add Document</button>

					<button type="submit" title="Update Details" name="btn_document_save" id="btn_document_save" class="btn waves-effect waves-green hidden">Save Document</button>
					<button type="button" title="Cancel Details" name="btn_document_cancel" id="btn_document_cancel" class="btn waves-effect waves-red close-btn">Cancel</button>
				</div>
				<div class="col s12 m12">
					<?php
					$sqlConnect = "select * from doc_details where EmployeeID='" . $EmployeeID . "' ";
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) {
					?>
						<table id="myTable1" class="data dataTable no-footer" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="hidden">Doc ID</th>
									<th>Document File</th>
									<th>Document Name</th>
									<th>Document Type</th>
									<th>Document ID</th>
									<th style="width:100px;">Manage</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$rootloc = '';
								if ($loc == "1" || $loc == "2") {
									$dir_loc = "Docs";
									$rootloc = '';
								} else if ($loc == "3") {
									$dir_loc = "Meerut/Docs";
									$rootloc = 'Meerut';
								} else if ($loc == "4") {
									$dir_loc = "Bareilly/Docs";
									$rootloc = 'Bareilly';
								} else if ($loc == "5") {
									$dir_loc = "Vadodara/Docs";
									$rootloc = 'Vadodara';
								} else if ($loc == "6") {
									$dir_loc = "Manglore/Docs";
									$rootloc = 'Manglore';
								} else if ($loc == "7") {
									$dir_loc = "Bangalore/Docs";
									$rootloc = 'Bangalore';
								} else if ($loc == "8") {
									$dir_loc = "Nashik/Docs";
									$rootloc = 'Nashik';
								} else if ($loc == "9") {
									$dir_loc = "Anantapur/Docs";
									$rootloc = 'Anantapur';
								}
								foreach ($result as $key => $value) {
									//$FileType = strtolower(pathinfo("../Docs/BGV/".$value['doc_file']."",PATHINFO_EXTENSION));	
									$FileType = strtolower(pathinfo("../" . $dir_loc . "/BGV/" . $value['doc_file'] . "", PATHINFO_EXTENSION));
									$FileType1 = strtolower(pathinfo("../" . $dir_loc . "/Covid19/" . $value['doc_file'] . "", PATHINFO_EXTENSION));
									$FileType2 = strtolower(pathinfo("../" . $dir_loc . "/CallLog/" . $value['doc_file'] . "", PATHINFO_EXTENSION));
									$FileType3 = strtolower(pathinfo("../" . $rootloc . "/checklist_pdf/" . $value['doc_file'] . "", PATHINFO_EXTENSION));
									$FileType4 = strtolower(pathinfo("../" . $dir_loc . "/Resume/" . $value['doc_file'] . "", PATHINFO_EXTENSION));
									$FileType5 = strtolower(pathinfo("../" . $dir_loc . "/AddressProof/" . $value['doc_file'] . "", PATHINFO_EXTENSION));
									$FileType6 = strtolower(pathinfo("../" . $dir_loc . "/IdentityProof/" . $value['doc_file'] . "", PATHINFO_EXTENSION));
									$FileType7 = strtolower(pathinfo("../" . $dir_loc . "/Apprenticeship/" . $value['doc_file'] . "", PATHINFO_EXTENSION));

									echo '<tr>';
									echo '<td class="DocID hidden">' . $value['doc_id'] . '</td>';
									echo '<td class="doc_file">' . $value['doc_file'] . '</td>';
									echo '<td class="doc_type">' . $value['doc_type'] . '</td>';
									/*if ($value['doc_stype'] == 'Aadhar Card') {
					echo '<td  class="doc_stypeoption" >Adhar Card</td>';
				} else {
					echo '<td  class="doc_stypeoption" >'.$value['doc_stype'].'</td>';
				}*/
									echo '<td  class="doc_stypeoption" >' . $value['doc_stype'] . '</td>';
									echo '<td class="doc_value">' . $value['dov_value'] . '</td>';
									echo '<td class="manage_item" >
			<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditDoc(this);"  data-file="' . $value['doc_file'] . '" id="' . $value['doc_id'] . '_' . $value['doc_type'] . '" data-position="left" data-tooltip="Edit">ohrm_edit</i>';
									if ($value['doc_type'] == 'BGV Report' && $FileType == 'pdf') {
										if (file_exists('../' . $dir_loc . '/BGV/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a href="../' . $dir_loc . '/BGV/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No file";
										}
									} else if ($value['doc_type'] == 'Covid 19' && $FileType1 == 'pdf') {
										if (file_exists('../' . $dir_loc . '/Covid19/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a href="../' . $dir_loc . '/Covid19/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No File";
										}
									} else if ($value['doc_type'] == 'Apprenticeship' && $FileType7 == 'pdf') {
										if (file_exists('../' . $dir_loc . '/Apprenticeship/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a href="../' . $dir_loc . '/Apprenticeship/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No File";
										}
									} else   if ($value['doc_type'] == 'Call Log' && ($FileType2 == 'wav' || $FileType2 == 'mp3')) {
										if (file_exists('../' . $dir_loc . '/CallLog/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a download  href="../' . $dir_loc . '/CallLog/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No File";
										}
									} else    if (($value['doc_type'] == 'Other') && $value['doc_stype'] == 'CheckList' && $FileType3 == 'pdf') {

										if (file_exists('../' . $rootloc . '/checklist_pdf/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a download  href="../' . $rootloc . '/checklist_pdf/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No File";
										}
									} else    if (($value['doc_type'] == 'Other') && $value['doc_stype'] == 'Curriculum Vitae' && $FileType4 == 'pdf') {

										if (file_exists('../' . $rootloc . '/Resume/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a download  href="../' . $rootloc . '/Resume/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No File";
										}
									} else   if ($value['doc_type'] == 'AddressProof' && $value['doc_stype'] == 'Adhar Card' && $FileType5 == 'pdf') {

										///file_exists('../'.$dir_loc.'/AddressProof/'.$value['doc_file']
										if (file_exists('../' . $dir_loc . '/AdharCard/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a download  href="../' . $dir_loc . '/AdharCard/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No File";
										}
									} else   if ($value['doc_type'] == 'IdentityProof' && $value['doc_stype'] == 'Adhar Card' && $FileType5 == 'pdf') {
										if (file_exists('../' . $dir_loc . '/IdentityProof/' . $value['doc_file'])) {
											echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" data-position="left" data-tooltip="Download File"><a download  href="../' . $dir_loc . '/IdentityProof/' . $value['doc_file'] . '" target="_blank">ohrm_file_download</a></i>';
										} else {
											echo "No File";
										}
									} else {

										echo '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="' . $value['doc_file'] . '" id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
									}
									echo '</td>';
									echo '</tr>';
								}
								//echo $clientid = clean($_SESSION["__user_client_ID"]);
								if ($clientid == 147) {
									$sqlConnect = "select * from tataaig_decl where EmployeeID='" . $EmployeeID . "' ";
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlConnect);
									if ($result) {
										echo '<tr>';
										echo '<td class="DocID hidden">' . $EmployeeID . '</td>';
										echo '<td class="doc_file">TATA AIG</td>';
										echo '<td class="doc_type">Declaration Form</td>';
										echo '<td  class="doc_stypeoption" >Declaration Form</td>';
										echo '<td class="doc_value">Declaration Form</td>';
										echo '<td class="manage_item" >
								<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return tatadecl(this);"  data="' . $value['DocID'] . '" id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
										echo '</td>';
										echo '</tr>';
									}
								}

								if ($clientid == 147) {
									$sqlConnect = "select * from tataaig_decl_self where EmployeeID='" . $EmployeeID . "' and status='accept'";
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlConnect);
									if ($result) {
										echo '<tr>';
										echo '<td class="DocID_self hidden">' . $EmployeeID . '</td>';
										echo '<td class="doc_file">TATA AIG</td>';
										echo '<td class="doc_type">Self Declaration Form</td>';
										echo '<td  class="doc_stypeoption" >Self Declaration Form</td>';
										echo '<td class="doc_value">Self Declaration Form</td>';
										echo '<td class="manage_item" >
								<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return tatadecl_self(this);"  data="' . $value['DocID'] . '" id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
										echo '</td>';
										echo '</tr>';
									}
								}

								if ($clientid == 152) {
									$sqlConnect = "select * from airtel_pay_bank_decl where EmployeeID='" . $EmployeeID . "'";
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlConnect);
									if ($result) {
										echo '<tr>';
										echo '<td class="DocID_airtel hidden">' . $EmployeeID . '</td>';
										echo '<td class="doc_file">Airtel Payment Bank</td>';
										echo '<td class="doc_type"> Declaration Form</td>';
										echo '<td  class="doc_stypeoption" > Declaration Form</td>';
										echo '<td class="doc_value"> Declaration Form</td>';
										echo '<td class="manage_item" >
								<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return airtel_paymentBank(this);"  data="' . $value['DocID'] . '" id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
										echo '</td>';
										echo '</tr>';
									}
								}

								if ($clientid == 151) {
									$sqlConnect = "select * from dicv_decl where EmployeeID='" . $EmployeeID . "' ";
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlConnect);
									if ($result) {
										echo '<tr>';
										echo '<td class="DocID hidden">' . $EmployeeID . '</td>';
										echo '<td class="doc_file">DICV</td>';
										echo '<td class="doc_type">Declaration Form</td>';
										echo '<td  class="doc_stypeoption" >Declaration Form</td>';
										echo '<td class="doc_value">Declaration Form</td>';
										echo '<td class="manage_item" >
								<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return dicv(this);"  data="' . $value['DocID'] . '" id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
										echo '</td>';
										echo '</tr>';
									}
								}

								if ($clientid == 103) {
									$sqlConnect = "select * from bajaj_finance_decl where EmployeeID='" . $EmployeeID . "'";
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlConnect);
									if ($result) {
										echo '<tr>';
										echo '<td class="DocID_bajaj_finance hidden">' . $EmployeeID . '</td>';
										echo '<td class="doc_file">Bajaj Finance Limited </td>';
										echo '<td class="doc_type"> Declaration Form</td>';
										echo '<td  class="doc_stypeoption" > Declaration Form</td>';
										echo '<td class="doc_value"> Declaration Form</td>';
										echo '<td class="manage_item" >
										<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return bajaj_finance(this);"  data="' . $value['DocID'] . '" 			id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
										echo '</td>';
										echo '</tr>';
									}
								}

								if ($clientid == 156) {
									$sqlConnect = "select * from hathway_decl where EmployeeID='" . $EmployeeID . "'";
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlConnect);
									if ($result) {
										echo '<tr>';
										echo '<td class="DocID_hathway hidden">' . $EmployeeID . '</td>';
										echo '<td class="doc_file">Hathway Digital Limited </td>';
										echo '<td class="doc_type"> Declaration Form</td>';
										echo '<td  class="doc_stypeoption" > Declaration Form</td>';
										echo '<td class="doc_value"> Declaration Form</td>';
										echo '<td class="manage_item" >
										<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return hathway(this);"  data="' . $value['DocID'] . '" 			id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
										echo '</td>';
										echo '</tr>';
									}
								}

								if ($clientid == 6) {
									$sqlConnect = "select * from den_decl where EmployeeID='" . $EmployeeID . "'";
									$myDB = new MysqliDb();
									$result = $myDB->query($sqlConnect);
									if ($result) {

										echo '<tr>';
										echo '<td class="DocID_den_decl hidden">' . $EmployeeID . '</td>';
										echo '<td class="doc_file">Den </td>';
										echo '<td class="doc_type"> Declaration Form</td>';
										echo '<td  class="doc_stypeoption" > Declaration Form</td>';
										echo '<td class="doc_value"> Declaration Form</td>';
										echo '<td class="manage_item" >
										<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return den_decl(this);"  data="' . $value['DocID'] . '" 			id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
										echo '</td>';
										echo '</tr>';
									}
								}

								$sqlConnectisms = "select * from isms_policies_decl where EmployeeID=?";
								$stmt = $conn->prepare($sqlConnectisms);
								$stmt->bind_param("s", $EmployeeID);
								$stmt->execute();
								$results = $stmt->get_result();
								//print_r($result);

								// $myDB = new MysqliDb();
								// $result = $myDB->query($sqlConnectden);
								if ($results->num_rows > 0) {
									echo '<tr>';
									echo '<td class="DocID_isms_policies_decl hidden">' . $EmployeeID . '</td>';
									echo '<td class="doc_file">ISMS Policies Ack </td>';
									echo '<td class="doc_type"> Declaration Form</td>';
									echo '<td  class="doc_stypeoption" > Declaration Form</td>';
									echo '<td class="doc_value"> Declaration Form</td>';
									echo '<td class="manage_item" >
	<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return isms_policies_decl(this);"  data="' . $value['DocID'] . '" 	id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
									echo '</td>';
									echo '</tr>';
								}

								$sqlConnect = "select * from nda_policies_decl where EmployeeID=?";
								$stmt = $conn->prepare($sqlConnect);
								$stmt->bind_param("s", $EmployeeID);
								$stmt->execute();
								$results = $stmt->get_result();
								if ($results->num_rows > 0) {
									echo '<tr>';
									echo '<td class="DocNdaID hidden">' . $EmployeeID . '</td>';
									echo '<td class="doc_file">NDA Policy</td>';
									echo '<td class="doc_type">Declaration Form</td>';
									echo '<td  class="doc_stypeoption" >Declaration Form</td>';
									echo '<td class="doc_value">Declaration Form</td>';
									echo '<td class="manage_item" >
							<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return nda(this);"  data="' . $value['DocID'] . '" id="' . $value['doc_type'] . '" id1="' . $value['doc_stype'] . '" data-position="left" data-tooltip="Download File">ohrm_file_download</i>';
									echo '</td>';
									echo '</tr>';
								}


								?>
							</tbody>
						</table>
					<?php
					}
					?>

				</div>
				<!--<div class="hidden modelbackground" id="myDiv"></div>
-->
				<script>
					$(document).ready(function() {
						$('input[type="text"]').click(function() {
							$(this).removeClass('has-error');
						});
						$('select').click(function() {
							$(this).removeClass('has-error');
						});
						$('#doc_child').val($(".trdoc").length);
						$('#btn_document_add').click(function() {
							var aa = confirm('Are you verified all document?');
							if (aa) {

								return true;
							} else {
								return false;
							}
						});
						$('#btn_document_cancel').addClass('hidden');
						$('#btn_document_cancel').click(function() {
							$('#btn_document_save').addClass('hidden');
							$('#btn_document_add').removeClass('hidden');
							$('#documentid').val('');
							$('.addChildbutton').removeClass('hidden');
							$('#txt_doc_value_1').removeAttr('readonly');
							$("#txt_doc_stype_1").removeAttr('disabled');
							$('#txt_doc_value_1').val('');
							$('select').formSelect();
						});

						$('#btn_document_save').click(function() {
							var aa = confirm('Do you want to update document?');
							if (aa) {
								return true;
							} else {
								return false;
							}
						});

						function changeevent() {
							$('*[id^=txt_doc_type_]').change(function() {
								//alert($(this).val());
								if ($(this).val() == 'Proof of Identity') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>PAN Card</option><option>Passport</option><option>Voter ID Card</option><option>Bank Passbook</option>');
									/*<option value="Aadhar Card">Adhar Card</option>*/
								} else if ($(this).val() == 'Proof of Address') {
									/*<option value="Aadhar Card">Adhar Card</option>*/
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>Passport</option><option>Voter ID Card</option><option>Driving License</option><option>Rashan Card</option><option>Telephone Bill</option><option>Electrycity Bill</option><option>Proof of Gas Connection</option><option>Registered Rent Agreement</option><option>Registered Lease Deed or Sale Agreement</option>');
								} else if ($(this).val() == 'FnF') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>No Dues Form</option><option>FnF Application</option>');
								} else if ($(this).val() == 'Apology Letter') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>Letter 1</option><option>Letter 2</option><option>Letter 3</option>');
								} else if ($(this).val() == 'Warning Letter') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>First Warning</option><option>Second Warning</option><option>Final Warning</option>');
								} else if ($(this).val() == 'Other') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>Curriculum Vitae</option><option>Interview Form</option><option>Salary Proof</option><option>Undertaking</option><option>WFO-WFH Declaration</option><option>System Undertaking</option><option>WFH Policy</option><option>Signature</option>');
								} else if ($(this).val() == 'BGV Report') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>BG verification</option>');
									// } else if ($(this).val() == 'Signature') {
									// 	$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>Signature</option>');
								} else if ($(this).val() == 'Call Log') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>WFH NCNS</option>');
								} else if ($(this).val() == 'Covid 19') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>Covid Positive</option><option>Covid Negative</option>');
								} else if ($(this).val() == 'Apprenticeship') {
									$(this).closest('tr').children("td:nth-child(4)").find('*[id^=txt_doc_stype_]').empty().append('<option>NATS</option><option>NAPS</option>');
								}
							});
						}
						changeevent();

						$('#btn_docAdd').click(function() {
							$count = $(".trdoc").length;
							$id = "trdoc_" + parseInt($count + 1);
							$('#doc_child').val(parseInt($count + 1));
							$tr = $("#trdoc_1").clone().attr("id", $id);
							$('#childtable tbody').append($tr);
							$tr.children("td:first-child").html(parseInt($count + 1));
							$tr.children("td:nth-child(2)").children("input").attr({
								"id": "txt_doc_name_" + parseInt($count + 1),
								"name": "txt_doc_name_[]"
							}).val('');

							$trSelect_n3 = $tr.children("td:nth-child(3)").find("select").clone().attr({
								"id": "txt_doc_type_" + parseInt($count + 1),
								"name": "txt_doc_type_" + parseInt($count + 1)
							}).datetimepicker({
								format: 'Y-m-d',
								timepicker: false
							}).val('Proof of Identity');

							$tr.children("td:nth-child(3)").html('').append($trSelect_n3);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();
							$trSelect_n4 = $tr.children("td:nth-child(4)").find("select").clone().attr({
								"id": "txt_doc_stype_" + parseInt($count + 1),
								"name": "txt_doc_stype_" + parseInt($count + 1)
							}).empty().append('<option>PAN Card</option><option>Passport</option><option>Voter ID Card</option><option>Employee ID card (any)</option><option>Bank Passbook</option>');
							/*<option>Aadhar Card</option>*/
							$tr.children("td:nth-child(4)").html('').append($trSelect_n4);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
								if ($(element).val().length > 0) {
									$(this).siblings('label, i').addClass('active');
								} else {
									$(this).siblings('label, i').removeClass('active');
								}
							});
							$('select').formSelect();

							$tr.children("td:nth-child(5)").children("input").attr({
								"id": "txt_doc_value_" + parseInt($count + 1),
								"name": "txt_doc_value_" + parseInt($count + 1)
							}).empty();
							changeevent();
						});
						$('#btnDoccan').click(function() {
							$count = $(".trdoc").length;
							if ($count > 1) {
								$('#childtable tbody').children("tr:last-child").remove();
								$('#doc_child').val(parseInt($count - 1));
							}

						});
						$('#btn_document_add').click(function() {
							var rowlen = ($('.trdoc').length);
							for (i = 1; i <= rowlen; i++) {
								if ($('#txt_doc_value_' + i).val().trim() == '') {
									$(function() {
										toastr.error('Please enter document id in' + i + ' row')
									});
									return false;
									break;
								}
								if ($('#txt_doc_name_' + i).val() == '') {
									$(function() {
										toastr.error('Please select document file for ' + i + ' row')
									});
									return false;
									break;
								}
							}


						});
					});

					function EditDoc(obj) {

						var len2 = $count = $(".trdoc").length;
						for (i = 3; i <= len2; i++) {
							$('#trdoc_' + i).addClass('hidden');
						}
						//alert(len2);
						$('#btn_document_cancel').removeClass('hidden');
						$('.addChildbutton').addClass('hidden');
						$('#trdoc_2').addClass('hidden');
						$('#btn_document_add').addClass('hidden');
						$('#btn_document_save').removeClass('hidden');
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID').text();
						var doc_val = $(obj).closest('tr').find('.doc_value').text();
						var doc_stype = $(obj).closest('tr').find('.doc_stypeoption').text();
						var doc_type = $(obj).closest('tr').find('.doc_type').text();

						var doc_file = $(obj).closest('tr').find('.doc_file').text();
						$('#documentid').val(doc_id);



						if (doc_stype.trim() != "Aadhar Card") {

							$('#txt_doc_type_1').val(doc_type);
							$('#txt_doc_stype_1').val(doc_stype);
							$('#txt_doc_value_1').val(doc_val);
							$("#txt_doc_stype_1").append("<option value='" + doc_stype + "' selected >" + doc_stype + "</option>");
							$("#btn_document_save").show();
							$('select').formSelect();
						} else {
							$("#txt_doc_stype_1").append("");
							$("#btn_document_save").hide();
							$('select').formSelect();
						}

						/*   
		        if($("#txt_doc_stype_1").val()=="Adhar Card"){
					$('#txt_doc_value_1').attr('readonly', true);
					$("#txt_doc_stype_1").prop('disabled', true);
					$("#txt_doc_name_1").prop('disabled', true);
					$("#btn_document_save").hide();
					
				}else{
					$('#txt_doc_value_1').removeAttr('readonly');
					$("#txt_doc_stype_1").removeAttr('disabled');
					$("#txt_doc_name_1").removeAttr('disabled');
					$("#btn_document_save").show();
					
					
				}*/

						$('select').formSelect();

					}

					function tatadecl(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID').text();
						//alert(doc_id);
						location.href = "../Controller/getTataAIGPdf.php?id=" + doc_id;
					}

					function tatadecl_self(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID_self').text();
						// alert(doc_id);
						location.href = "../Controller/getTataAIGPdf_self.php?id=" + doc_id;
					}

					function airtel_paymentBank(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID_airtel').text();
						// alert(doc_id);
						location.href = "../Controller/getAirtelPay_bank.php?id=" + doc_id;
					}

					function dicv(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID').text();
						//alert(doc_id);
						location.href = "../Controller/getDICVPdf.php?id=" + doc_id;
					}

					function bajaj_finance(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID_bajaj_finance').text();
						//alert(doc_id);
						location.href = "../Controller/getbajaj_finance_decl.php?id=" + doc_id;
					}

					function hathway(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID_hathway').text();

						location.href = "../Controller/getHathwayPdf.php?id=" + doc_id;
					}

					function den_decl(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID_den_decl').text();
						// alert(doc_id);
						location.href = "../Controller/getDenPdf.php?id=" + doc_id;
					}

					function isms_policies_decl(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocID_isms_policies_decl').text();
						// alert(doc_id);
						location.href = "../Controller/getISMS_policiesPDF.php?id=" + doc_id;
					}

					function nda(obj) {
						var tr = $(obj).closest('tr');
						var doc_id = tr.find('.DocNdaID').text();
						//alert(doc_id);
						location.href = "../Controller/getNDAPdf.php?id=" + doc_id;
					}

					function Download(el) {
						var docfile = '';
						var dirpath = $('#loc').val();
						var Docs = Docs_resume = Docs_chk = Docs_sign = '';
						if (dirpath == "1" || dirpath == "2") {
							Docs = '/Docs/';
							Docs_resume = 'Resume/';
							Docs_chk = 'checklist_pdf/';
						} else if (dirpath == "3") {
							Docs = '/Meerut/Docs/';
							Docs_resume = 'Meerut/Resume/';
							Docs_chk = 'Meerut/checklist_pdf/';
						} else if (dirpath == "4") {
							Docs = '/Bareilly/Docs/';
							Docs_resume = 'Bareilly/Resume/';
							Docs_chk = 'Bareilly/checklist_pdf/';
						} else if (dirpath == "5") {
							Docs = '/Vadodara/Docs/';
							Docs_resume = 'Vadodara/Resume/';
							Docs_chk = 'Vadodara/checklist_pdf/';
						} else if (dirpath == "6") {
							Docs = '/Manglore/Docs/';
							Docs_resume = 'Manglore/Resume/';
							Docs_chk = 'Manglore/checklist_pdf/';
						} else if (dirpath == "7") {
							Docs = '/Bangalore/Docs/';
							Docs_resume = 'Bangalore/Resume/';
							Docs_chk = 'Bangalore/checklist_pdf/';
						} else if (dirpath == "8") {
							Docs = '/Nashik/Docs/';
							Docs_resume = 'Nashik/Resume/';
							Docs_chk = 'Nashik/checklist_pdf/';
						} else if (dirpath == "9") {
							Docs = '/Anantapur/Docs/';
							Docs_resume = 'Anantapur/Resume/';
							Docs_chk = 'Anantapur/checklist_pdf/';
						}


						if ($(el).attr("id") == "Proof of Identity") {
							docfile = Docs + "IdentityProof/";
						} else
						if ($(el).attr("id") == "Proof of Address") {
							//docfile=Docs+"AddressProof/";
							docfile = Docs + "AdharCard/";
						} else
						if ($(el).attr("id") == "FnF") {
							docfile = Docs + "FnF/";
						} else
						if ($(el).attr("id") == "Apology Letter") {
							docfile = Docs + "ApologyLetter/";
						} else
						if ($(el).attr("id") == "Warning Letter") {
							docfile = Docs + "WarningLetter/";
						} else
						if ($(el).attr("id") == "Other") {
							docfile = Docs + "Other/";
						} else
						if ($(el).attr("id") == "BGV Report") {
							docfile = Docs + "BGV/";
						} else
						if ($(el).attr("id") == "Covid 19") {
							docfile = Docs + "Covid19/";
						} else
						if ($(el).attr("id") == "Apprenticeship") {
							docfile = Docs + "Apprenticeship/";
						} else
						if ($(el).attr("id") == "Call Log") {
							docfile = Docs + "CallLog/";
						}
						/*alert(docfile);
						alert($(el).attr("data"));*/
						if ($(el).attr("data") != '') {
							function getImageDimensions(path, callback) {
								var img = new Image();
								img.onload = function() {
									callback({
										width: img.width,
										height: img.height,
										srcsrc: img.src
									});
								}
								img.src = path;
							}

							$.ajax({
								url: ".." + docfile + $(el).attr("data"),
								type: 'HEAD',
								error: function() {

									// alert('No File Exist');
									/*
							 start  check inner file
							   */
									if ($(el).attr("id") == "Other" && $(el).attr("id1") == "Curriculum Vitae") {
										//alert("../"+Docs_resume+$(el).attr("data"));
										$.ajax({

											url: "../" + Docs_resume + $(el).attr("data"),
											type: 'HEAD',
											error: function() {
												alert('No File Exist');
												//alert('1');							        //flag=1;
											},
											success: function() {

												imgcheck = function(filename) {
													return (filename).split('.').pop();
												}
												imgchecker = imgcheck("../" + Docs_resume + $(el).attr("data"));

												if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
													getImageDimensions("../" + Docs_resume + $(el).attr("data"), function(data) {
														var img = data;

														$('<img>', {
															src: "../" + Docs_resume + $(el).attr("data")
														}).watermark({
															//text: 'ⓒ For Cogent E Services Ltd.',
															text: 'Cogent E Services Ltd.',
															//path:'../Style/images/cogent-logobkp.png',
															textWidth: 370,
															opacity: 1,
															textSize: (img.height / 15),
															nH: img.height,
															nW: img.width,
															textColor: "rgb(0,0,0,0.4)",
															outputType: 'jpeg',
															gravity: 'sw',
															done: function(imgURL) {
																var link = document.createElement('a');
																link.href = imgURL;
																link.download = $(el).attr("data");
																document.body.appendChild(link);
																link.click();

															}
														});
													});
												} else if (imgchecker.match(/(pdf)$/i)) {
													//window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src="+"../../"+Docs_resume+$(el).attr("data"));
													window.open("../" + Docs_resume + $(el).attr("data"));
												} else {
													window.open("../" + Docs_resume + $(el).attr("data"));
												}

											}
										});
									} else if ($(el).attr("id") == "Other" && $(el).attr("id1") == "CheckList") {

										$.ajax({

											url: "../" + Docs_chk + $(el).attr("data"),
											type: 'HEAD',
											error: function() {
												alert('No File Exist');
												//alert('1');							        //flag=1;
											},
											success: function() {

												imgcheck = function(filename) {
													return (filename).split('.').pop();
												}
												imgchecker = imgcheck("../" + Docs_chk + $(el).attr("data"));

												if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
													getImageDimensions("../" + Docs_chk + $(el).attr("data"), function(data) {
														var img = data;

														$('<img>', {
															src: "../" + Docs_chk + $(el).attr("data")
														}).watermark({
															//text: 'ⓒ For Cogent E Services Ltd.',
															text: 'Cogent E Services Ltd.',
															//path:'../Style/images/cogent-logobkp.png',
															textWidth: 370,
															opacity: 1,
															textSize: (img.height / 15),
															nH: img.height,
															nW: img.width,
															textColor: "rgb(0,0,0,0.4)",
															outputType: 'jpeg',
															gravity: 'sw',
															done: function(imgURL) {
																var link = document.createElement('a');
																link.href = imgURL;
																link.download = $(el).attr("data");
																document.body.appendChild(link);
																link.click();

															}
														});
													});
												} else if (imgchecker.match(/(pdf)$/i)) {
													window.open("../" + Docs_chk + $(el).attr("data"));
													//window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src="+"../.."+Docs_chk+$(el).attr("data"));
												} else {
													window.open("../" + Docs_chk + $(el).attr("data"));
												}

											}
										});
									}
									/*
								End  check inner file
							*/
									else {
										//alert('there');
										alert('No File Exist');
									}

								},

								success: function() {

									imgcheck = function(filename) {
										return (filename).split('.').pop();
									}
									imgchecker = imgcheck(".." + docfile + $(el).attr("data"));

									if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
										getImageDimensions(".." + docfile + $(el).attr("data"), function(data) {
											var img = data;

											$('<img>', {
												src: ".." + docfile + $(el).attr("data")
											}).watermark({
												//text: 'ⓒ For Cogent E Services Ltd.',
												text: 'Cogent E Services Ltd.',
												//path:'../Style/images/cogent-logobkp.png',
												textWidth: 370,
												opacity: 1,
												textSize: (img.height / 15),
												nH: img.height,
												nW: img.width,
												textColor: "rgb(0,0,0,0.4)",
												outputType: 'jpeg',
												gravity: 'sw',
												done: function(imgURL) {
													var link = document.createElement('a');
													link.href = imgURL;
													link.download = $(el).attr("data");
													document.body.appendChild(link);
													link.click();

												}
											});

										});
									} else if (imgchecker.match(/(pdf)$/i)) {
										window.open(".." + docfile + $(el).attr("data"));
										//window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src="+"../../"+docfile+$(el).attr("data"));
									} else {
										window.open(".." + docfile + $(el).attr("data"));
									}

								}

							});


						} else {
							alert('No File Exist');
						}
					}
				</script>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>