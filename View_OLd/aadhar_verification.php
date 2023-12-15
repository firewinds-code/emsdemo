<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead1.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_SESSION)) {
	if (!isset($_REQUEST['tfs'])) {
		$location = URL . 'Login';
		echo "<script>location.href='" . $location . "'</script>";
		//header("Location: $location");

	} else {
		$_SESSION['tfs'] = $_REQUEST['tfs'];
		$userempid = $_SESSION['__user_logid'];
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}


$reqEMS = 0;
$Request_Emp = '';
$image = '';
$_Description = $_Name = $alert_msg = '';
if (isset($_POST['btn_genrateOtp'])) {
	// $getData = "SELECT dov_value FROM ems.doc_details where EmployeeID='" . $userempid . "' and doc_stype='Aadhar Card' limit 1;";
	$getData = "SELECT dov_value FROM ems.doc_details where EmployeeID=? and doc_stype='Aadhar Card' limit 1;";
	$stmt = $conn->prepare($getData);
	$stmt->bind_param("s", $userempid);
	$stmt->execute();
	$Adhar_Data = $stmt->get_result();
	$Adhar_DataRow = $Adhar_Data->fetch_row();
	// $Adhar_Data = $myDB->rawQuery($getData);
	if (empty($mysql_error)) {
		if ($Adhar_Data) {

			// if ($Adhar_Data[0]['dov_value'] != "") {
			if ($Adhar_DataRow[0] != "") {
				$curl = curl_init();

				curl_setopt_array($curl, array(
					///CURLOPT_URL => "https://sandbox.aadhaarapi.io/api/v1/aadhaar-v2/generate-otp",	
					CURLOPT_URL => "https://kyc-api.aadhaarkyc.io/api/v1/aadhaar-v2/generate-otp",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => "{\n\t\"id_number\": \"" . $Adhar_DataRow[0] . "\"\n}",
					CURLOPT_HTTPHEADER => array(
						"Content-Type: application/json",
						"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1Nzg0NzM3MTEsImp0aSI6IjJhODI2OGIwLTA4YTgtNGU2YS1iNGVhLTFiOThjNjJlNzQ3YiIsImV4cCI6MTg5MzgzMzcxMSwiZnJlc2giOmZhbHNlLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfSwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmNvZ2VudHNlcnZpY2VzQGFhZGhhYXJhcGkuaW8iLCJuYmYiOjE1Nzg0NzM3MTF9.cmTpsW3U9ro0vCv2TYYnEzw5PYh0iObj-IbPUcA0QXU"

					),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				$resposne_data = json_decode($response);
				//print_r($resposne_data);;
				if (isset($resposne_data) and $resposne_data->success == true) {
					echo "<script>$(function(){ toastr.success('Otp Send Successfully'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Somthing Went Wrong ! Try Again'); }); </script>";
				}
			}
		} else {
			echo "<script>$(function(){ toastr.error('Aadhar Not found for this employee '); }); </script>";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Somthing Went Wrong'); }); </script>";
	}
}
if (isset($_POST['btn_verify_otp'])) {
	if (isset($_POST["token1"]) && isset($_SESSION["token1"]) && $_POST["token1"] == $_SESSION["token1"]) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			//CURLOPT_URL => "https://sandbox.aadhaarapi.io/api/v1/aadhaar-v2/submit-otp",
			CURLOPT_URL => "https://kyc-api.aadhaarapi.io/api/v1/aadhaar-v2/submit-otp",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\n\t\"client_id\": \"" . $_POST['client_id'] . "\",\n\t\"otp\": \"" . $_POST['otp'] . "\"\n}",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1Nzg0NzM3MTEsImp0aSI6IjJhODI2OGIwLTA4YTgtNGU2YS1iNGVhLTFiOThjNjJlNzQ3YiIsImV4cCI6MTg5MzgzMzcxMSwiZnJlc2giOmZhbHNlLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfSwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmNvZ2VudHNlcnZpY2VzQGFhZGhhYXJhcGkuaW8iLCJuYmYiOjE1Nzg0NzM3MTF9.cmTpsW3U9ro0vCv2TYYnEzw5PYh0iObj-IbPUcA0QXU"
			),
		));

		$response2 = curl_exec($curl);
		$resposne_data2 = json_decode($response2);
		if ($resposne_data2->success == "true") {
			$empname = $resposne_data2->data->full_name;
			//$FatherName=explode("S/O",$resposne_data2->data->care_of);	
			$FatherName = substr($resposne_data2->data->care_of, 3);
			$FatherName = str_ireplace(":", "", $FatherName);
			$FatherName = trim($FatherName);

			$first_name = explode(" ", $resposne_data2->data->full_name);
			$proimg = $resposne_data2->data->profile_image;
			$FatherName1 = explode(" ", $resposne_data2->data->care_of);
			// $checkData = "SELECT * FROM ems.personal_details where EmployeeID='" . $userempid . "' and FirstName='" . $first_name[0] . "' and DOB='" . $resposne_data2->data->dob . "'   limit 1;";
			$checkData = "SELECT * FROM ems.personal_details where EmployeeID=? and FirstName=? and DOB='?   limit 1;";
			$stmt = $conn->prepare($checkData);
			$stmt->bind_param("sss", $userempid, $first_name[0], $resposne_data2->data->dob);
			$stmt->execute();
			$verifyData = $stmt->get_result();
			$verifyDataRow = $verifyData->fetch_row();
			// $verifyData = $myDB->rawQuery($checkData);

			if ($verifyData) {
				if ($verifyData->num_rows > 0) {
					$remarks = " ";
					if ($verifyDataRow[8] != $FatherName) {
						$remarks = " DOB,first name now matched but Father Name not Match";
					} else {
						$remarks = " DOB,first name,father name now matched";
					}

					$remarks = date('Y-m-d') . ' : ' . $remarks;
					// $checkEmpData = "SELECT * FROM ems.aadhar_verifiaction where EmployeeID='" . $userempid . "' limit 1;";
					$checkEmpData = "SELECT * FROM ems.aadhar_verifiaction where EmployeeID=? limit 1;";
					$stmt = $conn->prepare($checkEmpData);
					$stmt->bind_param("s", $userempidb);
					$stmt->execute();
					$verifyEmpData = $stmt->get_result();
					$verifyDataRow = $verifyData->fetch_row();
					// $verifyEmpData = $myDB->rawQuery($checkEmpData);
					// $mysql_error = $myDB->getLastError();
					if ($verifyEmpData->num_rows > 0) {
						// $oldremark = $verifyEmpData[0]['remarks'] . ' | ' . $remarks;
						$oldremark = $verifyDataRow[22] . ' | ' . $remarks;
						// $sql = "UPDATE aadhar_verifiaction  SET aadhar_status = 'verified' , remarks='" . $oldremark . "' ,aadhar_image_code='" . addslashes($resposne_data2->data->profile_image) . "' WHERE EmployeeID = '" . $userempid . "'";
						$adharICode = addslashes($resposne_data2->data->profile_image);
						$sql = "UPDATE aadhar_verifiaction  SET aadhar_status = 'verified' , remarks=? ,aadhar_image_code=? WHERE EmployeeID =?";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("sss", $oldremark, $adharICode, $userempid);
						$stmt->execute();
					} else {
						$fn = $resposne_data2->data->aadhaar_number . '.jpg';
						$image = base64_to_jpeg($proimg, ROOT_PATH . 'aadharverification/' . $resposne_data2->data->aadhaar_number . '.jpg');
						// $sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image,remarks,aadhar_image_code) VALUES ('" . $userempid . "', '" . $resposne_data2->data->aadhaar_number . "', '" . $userempid . "' , 'verified' ,'" . $empname . "','" . $FatherName . "','" . $resposne_data2->data->dob . "','" . $resposne_data2->data->address->dist . "','" . $resposne_data2->data->address->loc . "','" . $resposne_data2->data->address->country . "','" . $resposne_data2->data->address->subdist . "','" . $resposne_data2->data->address->street . "','" . $resposne_data2->data->address->vtc . "','" . $resposne_data2->data->address->state . "','" . $resposne_data2->data->address->house . "','" . $resposne_data2->data->address->po . "','" . $resposne_data2->data->zip . "','" . $fn . "','" . $remarks . "','" . $resposne_data2->data->profile_image . "');";
						$sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image,remarks,aadhar_image_code) VALUES (?, ?, ? , 'verified' ,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("sssssssssssssssisss", $userempid, $resposne_data2->data->aadhaar_number, $userempid, $empname, $FatherName, $resposne_data2->data->dob, $resposne_data2->data->address->dist, $resposne_data2->data->address->loc, $resposne_data2->data->address->country, $resposne_data2->data->address->subdist, $resposne_data2->data->address->street, $resposne_data2->data->address->vtc, $resposne_data2->data->address->state, $resposne_data2->data->address->house, $resposne_data2->data->address->po, $resposne_data2->data->zip, $fn, $remarks, $resposne_data2->data->profile_image);
						$stmt->execute();
					}
					echo "<script>$(function(){ toastr.success('Aadhar Verified Successfully'); }); </script>";
					$reqEMS = 1;
				} else {
					// $checkEmpData = "SELECT * FROM ems.aadhar_verifiaction where EmployeeID='" . $userempid . "' limit 1;";
					$checkEmpData = "SELECT * FROM ems.aadhar_verifiaction where EmployeeID=? limit 1;";
					$stmt = $conn->prepare($checkEmpData);
					$stmt->bind_param("s", $userempid);
					$stmt->execute();
					$verifyEmpData = $stmt->get_result();
					$verifyEmpDataRow = $verifyEmpData->fetch_row();
					// $verifyEmpData = $myDB->rawQuery($checkEmpData);
					// $mysql_error = $myDB->getLastError();

					// $checkData1 = "SELECT * FROM ems.personal_details where EmployeeID='" . $userempid . "' limit 1;";
					$checkData1 = "SELECT * FROM ems.personal_details where EmployeeID=? limit 1;";
					$stmt = $conn->prepare($checkData1);
					$stmt->bind_param("s", $userempid);
					$stmt->execute();
					$verifyData1 = $stmt->get_result();
					$verifyDataRow1 = $verifyData1->fetch_row();
					// $verifyData1 = $myDB->rawQuery($checkData1);

					//echo $verifyEmpData;
					$remarks = " ";
					if ($verifyDataRow1[4] != $first_name) {
						$remarks .= "First name, ";
					}
					if ($verifyDataRow1[7] != $resposne_data2->data->dob) {
						$remarks .= "DOB, ";
					}
					if ($verifyDataRow1[8] != $FatherName) {
						$remarks .= "Father Name  ";
					}
					$remarks .= " not matched";
					$remarks = date('Y-m-d') . ' : ' . $remarks;
					if ($verifyEmpData->num_rows > 0) {
						$oldremark = $verifyEmpDataRow[22] . ' | ' . $remarks;
						// $sql = "UPDATE aadhar_verifiaction  SET aadhar_status = 'pending' , remarks='" . $oldremark . "' WHERE EmployeeID = '" . $userempid . "' ;";
						$sql = "UPDATE aadhar_verifiaction  SET aadhar_status = 'pending' , remarks=? WHERE EmployeeID = ?";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("ss", $oldremark, $userempid);
						$stmt->execute();
					} else {
						$fn = $resposne_data2->data->aadhaar_number . '.jpg';
						$image = base64_to_jpeg($proimg, ROOT_PATH . 'aadharverification/' . $resposne_data2->data->aadhaar_number . '.jpg');

						// $sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image,remarks) VALUES ('" . $userempid . "', '" . $resposne_data2->data->aadhaar_number . "', '" . $userempid . "' , 'pending','" . $empname . "','" . $FatherName . "','" . $resposne_data2->data->dob . "','" . $resposne_data2->data->address->dist . "','" . $resposne_data2->data->address->loc . "','" . $resposne_data2->data->address->country . "','" . $resposne_data2->data->address->subdist . "','" . $resposne_data2->data->address->street . "','" . $resposne_data2->data->address->vtc . "','" . $resposne_data2->data->address->state . "','" . $resposne_data2->data->address->house . "','" . $resposne_data2->data->address->po . "','" . $resposne_data2->data->zip . "','" . $fn . "','" . $remarks . "');";
						$sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image,remarks) VALUES (?,?,? , 'pending',?,?,?',?,?,?,?,?,?,?,?,?,?,?,?);";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("sssssssisssssssiss", $userempid, $resposne_data2->data->aadhaar_number, $userempid, $empname, $FatherName, $resposne_data2->data->dob, $resposne_data2->data->address->dist, $resposne_data2->data->address->loc, $resposne_data2->data->address->country, $resposne_data2->data->address->subdist, $resposne_data2->data->address->street, $resposne_data2->data->address->vtc, $resposne_data2->data->address->state, $resposne_data2->data->address->house, $resposne_data2->data->address->po, $resposne_data2->data->zip, $fn, $remarks);
						$stmt->execute();
					}
					echo "<script>$(function(){ toastr.error('Aadhar# Not Verified '); }); </script>";
				}
				$submitdata = $stmt->get_result();
				// $submitdata = $myDB->rawQuery($sql);
				// $mysql_error = $myDB->getLastError();
			} else {
				echo "<script>$(function(){ toastr.error('Something went wrong.. , try again after some time'); }); </script>";
			}
		} else {
			echo "<script>$(function(){ toastr.error('Somthing Went Wrong '); }); </script>";
		}
	}
}
function base64_to_jpeg($base64_string, $output_file)
{
	$ifp = fopen($output_file, "wb");
	fwrite($ifp, base64_decode($base64_string));
	fclose($ifp);
	return ($output_file);
}

if (isset($_POST['btn_cont'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		if ($_SESSION['tfs'] == '1') {
			$location = "https://demo.cogentlab.com/erpm/View/index";
			echo "<script>location.href='" . $location . "'</script>";
		}
	}
}

?>



<style>
	.short,
	.weak {
		color: red;
	}

	.good {
		color: #e66b1a;
	}

	.strong {
		color: green;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Aadhar Verification</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">
			<!-- Header for Form If any -->
			<div style="margin-left: 15px; line-height:2; ">
				Dear <strong> <?php echo $_SESSION['__user_Name']; ?>,</strong>
				<br />
				Hope you are having a great start of the day!<br />
				In a constant endeavor to keep the EMS database updated, it is required to have your profile information updated in sync with your aadhaar card information.
				<br />
				Please click the Generate OTP button below to receive the OTP generated by Aadhaar system that you shall receive on your Aadhaar registered mobile number. As the next step, please input the OTP in the textbox below and click Submit OTP button.
				<br />
				That is all the help we need from you.
				<br />
				<br />
				<strong> Thanks,
					<br />
					Cogent EMS Team</strong>
				<hr />
			</div>
			<!-- Form container if any -->
			<div class="schema-form-section row">


				<form id="genrateotp">

					<?php
					$_SESSION["token"] = csrfToken();
					?>
					<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
					<div class="input-field col s12 m12 center-align">
						<?php if ($reqEMS == 0) { ?>
							<button name="btn_genrateOtp" id="btn_genrateOtp" class="btn waves-effect waves-green">Generate OTP</button>
						<?php } ?>
						<?php if ($reqEMS == 1) { ?>
							<button name="btn_cont" id="btn_cont" class="btn waves-effect waves-green">Continue To EMS</button>
						<?php } ?>
					</div>
				</form>
				<?php if (isset($resposne_data->data->client_id) && $resposne_data->data->client_id != "") { ?>
					<form id="verify_otp" method="post">

						<?php
						$_SESSION["token1"] = csrfToken();
						?>
						<input type="hidden" name="token1" value="<?= $_SESSION["token1"] ?>">
						<div class="input-field col s5 m5">
							<input type="hidden" id="client_id" name="client_id" value="<?php echo $resposne_data->data->client_id ?>" />
						</div>
						<div class="input-field col s5 m5">
							<input type="text" id="otp" name="otp" placeholder="Enter OTP" />
							<label for="otp">Enter OTP</label>
						</div>
						<div class="input-field col s12 m12 center-align">
							<button type="submit" name="btn_verify_otp" id="btn_verify_otp" class="btn waves-effect waves-green">Submit OTP</button>
						</div>
					</form>
				<?php } ?>
				<div>
					<?php if (isset($resposne_data2->data->aadhaar_number) && $resposne_data2->data->aadhaar_number != "") { ?>
						<img src="<?php echo '../aadharverification/' . $resposne_data2->data->aadhaar_number . '.jpg'; ?>" />
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#btn_genrateOtp').on('click', function() {
			$("#genrateOtp").submit();

		});
	});
	$(document).ready(function() {
		$('#btn_verify_otp').on('click', function() {
			$("#verify_otp").submit();

		});
	});
</script>
<script src="../Script/bootstrap2.min.js"></script>
<style>
	.disablediv {
		pointer-events: none;
		opacity: 70% !important;
	}
</style>