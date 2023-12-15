<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
include(__dir__ . '/../Controller/endecript.php');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
ini_set("display_errors", '1');
$locationdir = '';
$dir_location = '';
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
		$userempid = ''; //$_SESSION['__user_logid'];
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

if (isset($_POST['empid'])) {
	$userempid = $_POST['empid'];
	$myDB = new MysqliDb();
	$result = $myDB->query("SELECT aadhar_status FROM aadhar_verifiaction where EmployeeID = '" . $userempid . "' and aadhar_status ='verified' limit 1; ");
	if (count($result) <= 0) {
		$getadharData = "SELECT dov_value FROM ems.doc_details where EmployeeID='" . $userempid . "' and doc_stype='Aadhar Card' limit 1;";
		$myDB = new MysqliDb();
		$Adhar_DataEmployee = $myDB->rawQuery($getadharData);

		$getEmployeeData = "SELECT personal_details.EmployeeID, personal_details.location,img,EmployeeName,FatherName,mobile,cast(DOB as date) as DOB FROM ems.personal_details  INNER JOIN contact_details ON personal_details.EmployeeID=contact_details.EmployeeID where personal_details.EmployeeID='" . $userempid . "';";

		$myDB = new MysqliDb();
		$EmployeeData = $myDB->rawQuery($getEmployeeData);

		$ofc_loc = $EmployeeData[0]['location'];
		if ($ofc_loc == "1" || $ofc_loc == "2") {
			$locationdir = "Images/";
			$dir_location = "Docs/AdharCard/";
		} else if ($ofc_loc == "3") {
			$locationdir = "Meerut/Images/";
			$dir_location = "Meerut/Docs/AdharCard/";
		} else if ($ofc_loc == "4") {
			$locationdir = "Bareilly/Images/";
			$dir_location = "Bareilly/Docs/AdharCard/";
		} else if ($ofc_loc == "5") {
			$locationdir = "Vadodara/Images/";
			$dir_location = "Vadodara/Docs/AdharCard/";
		} else if ($ofc_loc == "6") {
			$locationdir = "Manglore/Images/";
			$dir_location = "Manglore/Docs/AdharCard/";
		} else if ($ofc_loc == "7") {
			$locationdir = "Bangalore/Images/";
			$dir_location = "Bangalore/Docs/AdharCard/";
		} else if ($ofc_loc == "8") {
			$locationdir = "Nashik/Images/";
			$dir_location = "Nashik/Docs/AdharCard/";
		} else if ($ofc_loc == "9") {
			$locationdir = "Anantapur/Images/";
			$dir_location = "Anantapur/Docs/AdharCard/";
		} else if ($ofc_loc == "10") {
			$locationdir = "Gurgaon/Images/";
			$dir_location = "Gurgaon/Docs/AdharCard/";
		} else if ($ofc_loc == "11") {
			$locationdir = "Hyderabad/Images/";
			$dir_location = "Hyderabad/Docs/AdharCard/";
		}
	} else {
		echo "<script>$(function(){ toastr.error('Aadhar Number Already Verified'); }); </script>";
	}

	//print_r($EmployeeData);
	//die;
}
$Request_Emp = '';
$image = '';
$_Description = $_Name = $alert_msg = '';
$adhar_no = '';
if (isset($_POST['btn_genrateOtpno'])) {
	$adhar_no = $_POST['aadhar_no'];
	$userempid = $_POST['empid'];
}

if (isset($_POST['btn_genrateOtpyes'])) {

	$adhar_no = $_POST['aadhar_yes'];
	$userempid = $_POST['empid'];
}
if ($adhar_no != "") {
	$url = URL . 'Services/avotppro.php?aadhar=' . $adhar_no;

	$curll = curl_init();
	curl_setopt($curll, CURLOPT_URL, $url);
	curl_setopt($curll, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curll, CURLOPT_TIMEOUT, 30);
	curl_setopt($curll, CURLOPT_SSL_VERIFYPEER, FALSE);
	$reply = curl_exec($curll);
	curl_close($curll);

	if (isset($reply) and $reply != "") {
		echo "<script>$(function(){ toastr.success('Otp Send Successfully'); }); </script>";
	} else {
		echo "<script>$(function(){ toastr.error('Aadhaar API not responding this movement, please try again after some time.'); }); </script>";

		$sqlResponse = "INSERT INTO aadhar_status_log (aadhar_no,type, api_status) VALUES ('" . $adhar_no . "','otpSend','Otp Not sent Aadhaar. API not responding this movement, please try again after some time.')";
		$myDB = new MysqliDb();
		$sqlResponseRun = $myDB->rawQuery($sqlResponse);
		$mysql_error = $myDB->getLastError();
	}
}


if (isset($_POST['btn_verify_otp'])) {
	$userempid = $_POST['empidhnd'];
	$curl = curl_init();
	/*curl_setopt_array($curl, array(
		CURLOPT_URL => "https://kyc-api.aadhaarapi.io/api/v1/aadhaar-v2/submit-otp",						
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS =>"{\n\t\"client_id\": \"".$_POST['client_id']."\",\n\t\"otp\": \"".$_POST['otp']."\"\n}",
		CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json",
		"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1Nzg0NzM3MTEsImp0aSI6IjJhODI2OGIwLTA4YTgtNGU2YS1iNGVhLTFiOThjNjJlNzQ3YiIsImV4cCI6MTg5MzgzMzcxMSwiZnJlc2giOmZhbHNlLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfSwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmNvZ2VudHNlcnZpY2VzQGFhZGhhYXJhcGkuaW8iLCJuYmYiOjE1Nzg0NzM3MTF9.cmTpsW3U9ro0vCv2TYYnEzw5PYh0iObj-IbPUcA0QXU"
		),
		));*/
	$aadhaar_pdf_generate = true;
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://kyc-api.aadhaarapi.io/api/v1/aadhaar-v2/submit-otp",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "{\n\t\"client_id\": \"" . $_POST['client_id'] . "\",\n\t\"otp\": \"" . $_POST['otp'] . "\",\n\t\"aadhaar_pdf_generate\": \"" . $aadhaar_pdf_generate . "\"\n}",
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1Nzg0NzM3MTEsImp0aSI6IjJhODI2OGIwLTA4YTgtNGU2YS1iNGVhLTFiOThjNjJlNzQ3YiIsImV4cCI6MTg5MzgzMzcxMSwiZnJlc2giOmZhbHNlLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfSwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmNvZ2VudHNlcnZpY2VzQGFhZGhhYXJhcGkuaW8iLCJuYmYiOjE1Nzg0NzM3MTF9.cmTpsW3U9ro0vCv2TYYnEzw5PYh0iObj-IbPUcA0QXU"

		),
	));
	$response2 = curl_exec($curl);
	$resposne_data2 = json_decode($response2);
	if ($resposne_data2->success == "true") {
		$empname = encrypt($resposne_data2->data->full_name, "encrypt");
		$aadhaar_FatherName = encrypt($resposne_data2->data->care_of, "encrypt");
		$aadhaarno = encrypt($resposne_data2->data->aadhaar_number, "encrypt");
		$aadhaardob = encrypt($resposne_data2->data->dob, "encrypt");
		//$FatherName=ltrim($resposne_data2->data->care_of,"S/O C/O W/O D/O H/O :");			
		//$FatherName=trim($FatherName[0]);
		$FatherName = $resposne_data2->data->care_of;
		$first_name = explode(" ", $resposne_data2->data->full_name);
		$proimg = $resposne_data2->data->profile_image;
		$FatherName1 = explode(" ", $resposne_data2->data->care_of);
		$checkData = "SELECT location,trim(FirstName) as FirstName,FatherName,cast(DOB as date) as DOB,EmployeeID FROM ems.personal_details where EmployeeID='" . $userempid . "' and trim(FirstName)='" . trim($first_name[0]) . "' and cast(DOB as date)='" . $resposne_data2->data->dob . "' limit 1;";
		//echo	$checkData="SELECT * FROM ems.personal_details where EmployeeID='".$userempid."' and FirstName='".$first_name[0]."' and  FatherName='".$FatherName1[1].' '.$FatherName1[2]."' and DOB='".$resposne_data2->data->dob."'   limit 1;";
		$myDB = new MysqliDb();
		$verifyData = $myDB->rawQuery($checkData);
		$mysql_error = $myDB->getLastError();

		if (empty($mysql_error)) {
			if (count($verifyData) > 0) {
				$remarks = " ";
				if (strpos(strtolower(trim($FatherName)), str_replace('  ', ' ', strtolower(trim($verifyData[0]['FatherName']))))) {
					$remarks = " DOB,first name,father name matched";
				} else {
					$remarks = " DOB,first name matched but Father Name not Match";
				}
				$remarks = date('Y-m-d') . ' : ' . $remarks;
				$checkEmpData = "SELECT remarks FROM ems.aadhar_verifiaction where EmployeeID='" . $userempid . "' limit 1;";
				$myDB = new MysqliDb();
				$verifyEmpData = $myDB->rawQuery($checkEmpData);
				$mysql_error = $myDB->getLastError();
				if (count($verifyEmpData) > 0) {
					$oldremark = $verifyEmpData[0]['remarks'] . ' | ' . $remarks;


					$dataurl = $resposne_data2->data->aadhaar_pdf;
					$filename = $userempid . '_AadharCard.pdf';
					$saveto = ROOT_PATH . $dir_location . $filename;
					$content1 = file_get_contents($dataurl);
					if (file_put_contents($saveto, $content1)) {
						$myDB =  new MysqliDb();
						$selectDoc = "select doc_file from doc_details  where EmployeeID='" . $userempid . "' and doc_stype='Aadhar Card' and doc_type='Proof of Address'";
						$doc_data = $myDB->query($selectDoc);
						if (count($doc_data) > 0) {
							foreach ($doc_data as $dataval) {
								$file = $dataval['doc_file'];
								if (file_exists("../" . $dir_location . $file)) {
									//@unlink("../".$dir_location.$file);
								}
							}
						}
						$sql = "UPDATE aadhar_verifiaction  SET aadhar_status = 'verified' , remarks='" . $oldremark . "',aadhar_image_code='" . addslashes($resposne_data2->data->profile_image) . "',verify_date=now()  WHERE EmployeeID = '" . $userempid . "' ;";
						$myDB =  new MysqliDb();
						$deleteOldone = "DELETE from doc_details where EmployeeID='" . $userempid . "' and doc_stype='Aadhar Card' and doc_type='Proof of Address'";
						$delete_adhar = $myDB->query($deleteOldone);
						$insertquery = "Insert into doc_details set EmployeeID='" . $userempid . "',doc_stype='Aadhar Card',doc_type='Proof of Address', dov_value='" . $resposne_data2->data->aadhaar_number . "', doc_file='" . $filename . "',createdon=now(),modifiedon=now(),aadhar_source='chooseFromAdharAPI'";
						$myDB =  new MysqliDb();
						$myDB->query($insertquery);
						$error = $myDB->getLastError();

						$myDB = new MysqliDb();
						$submitdata = $myDB->rawQuery($sql);
						/*  Call API for Aadhar OCR*/

						$url = URL . 'View/aadhar_ocr.php?empid=' . $userempid;
						//$url = URL.'View/aadhar_ocr_pythonapi.php?empid='.$userempid;
						//echo "<br>";
						$curll = curl_init();
						curl_setopt($curll, CURLOPT_URL, $url);
						curl_setopt($curll, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curll, CURLOPT_TIMEOUT, 30);
						curl_setopt($curll, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_exec($curll);
						curl_close($curll);
						//echo "<script>$(function(){ toastr.success('curl result ".$result."'); }); </script>";		
						//include("aadhar_ocr.php");

					}
				}
				/*else
					{						
						$fn=$resposne_data2->data->aadhaar_number.'.jpg';
						$image = base64_to_jpeg($proimg, ROOT_PATH.'aadharverification/'.$resposne_data2->data->aadhaar_number.'.jpg');
						$sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image,remarks,aadhar_image_code,verify_date) VALUES ('".$userempid."', '".$aadhaarno."', '".$_SESSION['__user_logid']."' , 'verified' ,'".$empname."','".$aadhaar_FatherName."','".$aadhaardob."','".$resposne_data2->data->address->dist."','".$resposne_data2->data->address->loc."','".$resposne_data2->data->address->country."','".$resposne_data2->data->address->subdist."','".$resposne_data2->data->address->street."','".$resposne_data2->data->address->vtc."','".$resposne_data2->data->address->state."','".$resposne_data2->data->address->house."','".$resposne_data2->data->address->po."','".$resposne_data2->data->zip."','".$fn."','".$remarks."','".addslashes($resposne_data2->data->profile_image)."',now() );";
						
					}*/
				echo "<script>$(function(){ toastr.success('Aadhar Verified Successfully'); }); </script>";
			} else {

				$checkEmpData = "SELECT remarks FROM aadhar_verifiaction where EmployeeID='" . $userempid . "' limit 1;";
				$myDB = new MysqliDb();
				$verifyEmpData = $myDB->rawQuery($checkEmpData);
				$mysql_error = $myDB->getLastError();

				$checkData1 = "SELECT trim(FirstName) as FirstName,FatherName,cast(DOB as date) as DOB FROM ems.personal_details where EmployeeID='" . $userempid . "' limit 1;";
				//echo	$checkData="SELECT * FROM ems.personal_details where EmployeeID='".$userempid."' and FirstName='".$first_name[0]."' and  FatherName='".$FatherName1[1].' '.$FatherName1[2]."' and DOB='".$resposne_data2->data->dob."'   limit 1;";
				$myDB = new MysqliDb();
				$verifyData1 = $myDB->rawQuery($checkData1);
				$mysql_error = $myDB->getLastError();
				$remarks = " ";
				if (strtolower(trim($verifyData1[0]['FirstName'])) != strtolower(trim($first_name[0]))) {
					$remarks .= "First name, ";
				}
				if ($verifyData1[0]['DOB'] != $resposne_data2->data->dob) {
					$remarks .= "DOB, ";
				}
				if (strpos(strtolower(trim($FatherName)), str_replace('  ', ' ', strtolower(trim($verifyData1[0]['FatherName']))))) {
					$remarks .= "Father Name ";
				}
				$remarks .= " not matched";
				$remarks = date('Y-m-d') . ' : ' . $remarks;
				if (count($verifyEmpData) > 0) {
					$oldremark = $verifyEmpData[0]['remarks'] . ' | ' . $remarks;
					$sql = "UPDATE aadhar_verifiaction  SET aadhar_status = 'pending' , remarks='" . $oldremark . "' WHERE EmployeeID = '" . $userempid . "' ;";
				}
				/*else
					{
						$fn=$resposne_data2->data->aadhaar_number.'.jpg';
						$image = base64_to_jpeg($proimg, ROOT_PATH.'aadharverification/'.$resposne_data2->data->aadhaar_number.'.jpg');

						$sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image,remarks) VALUES ('".$userempid."', '".$aadhaarno."', '".$_SESSION['__user_logid']."' , 'pending','".$empname."','".$aadhaar_FatherName."','".$aadhaardob."','".$resposne_data2->data->address->dist."','".$resposne_data2->data->address->loc."','".$resposne_data2->data->address->country."','".$resposne_data2->data->address->subdist."','".$resposne_data2->data->address->street."','".$resposne_data2->data->address->vtc."','".$resposne_data2->data->address->state."','".$resposne_data2->data->address->house."','".$resposne_data2->data->address->po."','".$resposne_data2->data->zip."','".$fn."','".$remarks."')";							
					}*/
				echo "<script>$(function(){ toastr.error('Aadhar# Not Verified '); }); </script>";
			}

			$myDB = new MysqliDb();
			$submitdata = $myDB->rawQuery($sql);
			$mysql_error = $myDB->getLastError();
		} else {
			echo "<script>$(function(){ toastr.error('Something went wrong.. , try again after some time'); }); </script>";
		}
	} else {
		if (isset($Adhar_DataEmployee[0]['dov_value'])) {
			$sqlResponse = "INSERT INTO aadhar_status_log (aadhar_no,type, api_status) VALUES ('" . $Adhar_DataEmployee[0]['dov_value'] . "','verifyOTP','" . $response2 . "')";
			$myDB = new MysqliDb();
			$sqlResponseRun = $myDB->rawQuery($sqlResponse);
			$mysql_error = $myDB->getLastError();
			echo "<script>$(function(){ toastr.error('Invalid OTP, try again '); }); </script>";
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
				<!-- Dear <strong> <?php echo $_SESSION['__user_Name']; ?>,</strong>
	 <br />
	 
Hope you are having a great start of the day!<br />
	In a constant endeavor to keep the EMS database updated, it is required to have your profile information updated in sync with your Aadhaar card information.
	 <br />
Please click the Generate OTP button below to receive the OTP generated by Aadhaar system that you shall receive on your Aadhaar registered mobile number. As the next step, please input the OTP in the textbox below and click Submit OTP button.
	 <br />
	 That is all the help we need from you.
	<br />
	<br />
	<strong> Thanks,
	 <br />
	 Cogent EMS Team</strong>			
	 <hr/>-->
			</div>
			<!-- Form container if any -->
			<div class="schema-form-section row">


				<form id="genrateotp">
					<div class="input-field col s5 m5 center-align">
						<input type="text" name="empid" id="empid" />
						<label for="empid">Employee ID</label>


					</div>
					<div class="input-field col s5 m5 center-align">
						<button name="btn_Submit" id="btn_Submit" class="btn waves-effect waves-green">Submit</button>

					</div>
					<!--<div class="input-field col s12 m12 center-align">
				   <button name="btn_genrateOtp" id="btn_genrateOtp" class="btn waves-effect waves-green">Generate OTP</button>
				</div>-->
				</form>
			</div>
			<div class="schema-form-section row">
				<?php if (isset($EmployeeData) && $EmployeeData != "") {
					if (isset($Adhar_DataEmployee[0]['dov_value'])) {



				?>
						<div class="input-field col s5 m5 center-align">
							<table>

								<tr>
									<td><img src="<?php echo URL . $locationdir . $EmployeeData[0]['img'] ?>" height="200px" width="200px"></td>
								</tr>
								<tr>
									<td>Name :<?php echo $EmployeeData[0]['EmployeeName'] . ' (' . $EmployeeData[0]['EmployeeID'] . ')' ?> </td>
								</tr>
								<tr>
									<td>Father Name : <?php echo $EmployeeData[0]['FatherName'] ?></td>
								</tr>
								<tr>
									<td>DOB : <?php echo $EmployeeData[0]['DOB'] ?></td>
								</tr>
								<tr>
									<td>Aadhar No. : <?php echo $Adhar_DataEmployee[0]['dov_value'] ?></td>
								</tr>
								<tr>
									<td>Contact no. : <?php echo $EmployeeData[0]['mobile'] ?></td>
								</tr>
								<tr>
									<td>Do you want to proceed with existing aadhar number ? </td>
								</tr>
								<?php if (!isset($resposne_data2->data->aadhaar_number)) { ?>
									<tr>
										<td>
											<Select class="input-field col s4 m4 confirmation" name="confirmation" id="txt_dateMonth">
												<option value="">Select Option</option>
												<option value="1">Yes</option>
												<option value="0">No</option>
											</Select>
										</td>
									</tr>
								<?php } ?>


							</table>
						</div>



				<?php }
				} ?>


				<div class="input-field col s5 m5 center-align">
					<?php if (isset($resposne_data2->data->aadhaar_number) && $resposne_data2->data->aadhaar_number != "") { ?>


						<table>

							<tr>
								<td><img src="<?php echo '../aadharverification/' . $resposne_data2->data->aadhaar_number . '.jpg'; ?>" height="200px" width="200px">

								</td>
							</tr>
							<tr>
								<td>Name :<?php echo $resposne_data2->data->full_name ?>
									<?php if (strtolower(trim($resposne_data2->data->full_name)) == str_replace('  ', ' ', strtolower(trim($EmployeeData[0]['EmployeeName'])))) { ?>
										<i class="fa fa-check " style="color:green;font-size: 20px;"></i>
									<?php } else { ?>
										<i class="fa fa-times " style="color:red;font-size: 20px;"></i>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>Father Name : <?php echo $resposne_data2->data->care_of ?>

									<?php if (strpos(strtolower(trim($resposne_data2->data->care_of)), str_replace('  ', ' ', strtolower(trim($EmployeeData[0]['FatherName']))))) { ?>
										<i class="fa fa-check " style="color:green;font-size: 20px;"></i>
									<?php } else { ?>
										<i class="fa fa-times " style="color:red;font-size: 20px;"></i>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>DOB : <?php echo $resposne_data2->data->dob ?>
									<?php if ($resposne_data2->data->dob == $EmployeeData[0]['DOB']) { ?>
										<i class="fa fa-check " style="color:green;font-size: 20px;"></i>
									<?php } else { ?>
										<i class="fa fa-times " style="color:red;font-size: 20px;"></i>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>Aadhar no. : <?php echo $resposne_data2->data->aadhaar_number ?></td>
							</tr>

						</table>
					<?php } ?>



				</div>

			</div>
			<div class="schema-form-section row" s5 m5>
				<div style="display:none" id="aadhar_idno">
					<form id="genrateotpno" method="post">
						<div class="input-field s5 m5  center-align">
							<input type="hidden" name="empid" value="<?php echo $EmployeeData[0]['EmployeeID'] ?>" />
							<input type="text" class="input-field s5 m5  center-align" name="aadhar_no" id="aadhar_no" />
							<label for="empid">Aadhar No.</label>

						</div>
						<div class="input-field col s5 m5 center-align">
							<button name="btn_genrateOtpno" id="btn_genrateOtpno" class="btn waves-effect waves-green">Generate OTP</button>
						</div>
					</form>
				</div>

				<div style="display:none" id="aadhar_idyes">
					<form id="genrateotpyes" method="post">
						<div class="input-field col s5 m5 center-align">
							<input type="hidden" name="aadhar_yes" id="aadhar_yes" value="<?php echo $Adhar_DataEmployee[0]['dov_value'] ?>" />
						</div>
						<input type="hidden" name="empid" value="<?php echo $EmployeeData[0]['EmployeeID'] ?>" />
						<div class="input-field s5 m5 center-align">
							<button name="btn_genrateOtpyes" id="btn_genrateOtpyes" class="btn waves-effect waves-green">Generate OTP</button>
						</div>
					</form>
				</div>
			</div>
			<div class="schema-form-section row">
				<?php if (isset($reply) && $reply != "") { ?>
					<form id="verify_otp" method="post">
						<div class="input-field col s5 m5 center-align">
							<input type="hidden" id="client_id" name="client_id" value="<?php echo $reply ?>" />
							<input type="hidden" id="empid1hnd" name="empidhnd" value="<?php echo $userempid ?>" />
							<input type="hidden" id="empid" name="empid" value="<?php echo $userempid ?>" />
						</div>
						<div class="input-field col s5 m5 center-align">
							<input type="text" id="otp" name="otp" placeholder="Enter OTP" />
							<label for="otp">Enter OTP</label>
						</div>
						<div class="input-field col s5 m5  center-align">
							<button type="submit" name="btn_verify_otp" id="btn_verify_otp" class="btn waves-effect waves-green">Submit OTP</button>
						</div>

					</form>
				<?php } ?>
			</div>

		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#btn_genrateOtpyes').on('click', function() {
			$("#genrateotpyes").submit();

		});
	});

	$(document).ready(function() {
		$('#btn_genrateOtpno').on('click', function() {
			$("#genrateotpno").submit();

		});
	});
	$(document).ready(function() {
		$('#btn_verify_otp').on('click', function() {
			$("#verify_otp").submit();

		});
	});

	$(document).ready(function() {
		$('#btn_Submit').on('click', function() {
			$("#genrateOtp").submit();

		});
	});
	$(document).ready(function() {
		$('.confirmation').on('change', function() {
			var radioValue = $(this).val();
			if (radioValue == 0) {
				document.getElementById("aadhar_idno").style.display = "block";
				document.getElementById("aadhar_idyes").style.display = "none";

			}
			if (radioValue == 1) {
				document.getElementById("aadhar_idno").style.display = "none";
				document.getElementById("aadhar_idyes").style.display = "block";
			}
		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>