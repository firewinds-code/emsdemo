<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
	else{
		$userempid=$_SESSION['__user_logid'];
	}
	
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
$Request_Emp='';
	$image='';
$_Description=$_Name=$alert_msg='';
if(isset($_POST['btn_genrateOtp']))
{
	$getData="SELECT dov_value FROM ems.doc_details where EmployeeID='".$userempid."' and doc_stype='Aadhar Card' limit 1;";
	$myDB=new MysqliDb();
   $Adhar_Data=$myDB->rawQuery($getData);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		//print_r($Adhar_Data);
		
			if($Adhar_Data[0]['dov_value']!="")
			{
				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://sandbox.aadhaarapi.io/api/v1/aadhaar-v2/generate-otp",				  
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS =>"{\n\t\"id_number\": \"".$Adhar_Data[0]['dov_value']."\"\n}",
				CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1Nzk1OTc2MDMsImZyZXNoIjpmYWxzZSwiaWRlbnRpdHkiOiJkZXYuY29nZW50c2VydmljZXNAYWFkaGFhcmFwaS5pbyIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJyZWFkIl19LCJpYXQiOjE1NzgzMDE2MDMsIm5iZiI6MTU3ODMwMTYwMywidHlwZSI6ImFjY2VzcyIsImp0aSI6IjhlMDhiZWYyLTcxM2ItNDcxNS1hNjk2LTEyYjFjNDdjYTljMiJ9.1D0PiWugIbmeaDoNU68D9M10C4qb0iD_xfxF0ZIS5JY"
				),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				$resposne_data= json_decode($response);
				//print_r($resposne_data);;
				if(isset($resposne_data) and $resposne_data->success=="true")
				{
					echo "<script>$(function(){ toastr.success('Otp Send Successfully'); }); </script>";
					
				}
				else{
					echo "<script>$(function(){ toastr.error('Aadhar Number Not Found'); }); </script>";
				}
			}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Somthing Went Wrong'); }); </script>";
	}
}
if(isset($_POST['btn_verify_otp']))
{
	
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://sandbox.aadhaarapi.io/api/v1/aadhaar-v2/submit-otp",
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
		"Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1Nzk1OTc2MDMsImZyZXNoIjpmYWxzZSwiaWRlbnRpdHkiOiJkZXYuY29nZW50c2VydmljZXNAYWFkaGFhcmFwaS5pbyIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJyZWFkIl19LCJpYXQiOjE1NzgzMDE2MDMsIm5iZiI6MTU3ODMwMTYwMywidHlwZSI6ImFjY2VzcyIsImp0aSI6IjhlMDhiZWYyLTcxM2ItNDcxNS1hNjk2LTEyYjFjNDdjYTljMiJ9.1D0PiWugIbmeaDoNU68D9M10C4qb0iD_xfxF0ZIS5JY"
		),
		));
		
		$response2 = curl_exec($curl);
		$resposne_data2= json_decode($response2);
		if($resposne_data2->success=="true")
		{ 
			$empname=$resposne_data2->data->full_name;
			//$FatherName=$resposne_data2->data->care_of;
			$FatherName=ltrim($resposne_data2->data->care_of,"S/O:");
			//$FatherName=trim($FatherName[0]);
			$first_name=explode(" ",$resposne_data2->data->full_name);
			$proimg=$resposne_data2->data->profile_image;
			$FatherName1=explode(" ",$resposne_data2->data->care_of);			
			$checkData="SELECT * FROM ems.personal_details where EmployeeID='".$userempid."' and FirstName='".$first_name[0]."' and DOB='".$resposne_data2->data->dob."'   limit 1;";
			//echo	$checkData="SELECT * FROM ems.personal_details where EmployeeID='".$userempid."' and FirstName='".$first_name[0]."' and  FatherName='".$FatherName1[1].' '.$FatherName1[2]."' and DOB='".$resposne_data2->data->dob."'   limit 1;";
			$myDB=new MysqliDb();
			$verifyData=$myDB->rawQuery($checkData);
			$mysql_error = $myDB->getLastError();
			  
			if(empty($mysql_error))
			{
				if(count($verifyData) > 0 )
				{
					$checkEmpData="SELECT * FROM ems.aadhar_verifiaction where EmployeeID='".$userempid."' limit 1;";
					$myDB=new MysqliDb();
					$verifyEmpData=$myDB->rawQuery($checkEmpData);
					$mysql_error = $myDB->getLastError();				
					if(count($verifyEmpData) > 0 )
					{
						$sql="UPDATE aadhar_verifiaction  SET aadhar_status = 'verified' WHERE EmployeeID = '".$userempid."' ;";
					}
					else
					{						
					$fn=$resposne_data2->data->aadhaar_number.'.jpg';
						$image = base64_to_jpeg($proimg, ROOT_PATH.'aadharverification/'.$resposne_data2->data->aadhaar_number.'.jpg');
						$sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image) VALUES ('".$userempid."', '".$resposne_data2->data->aadhaar_number."', '".$userempid."' , 'verified' ,'".$empname."','".$FatherName."','".$resposne_data2->data->dob."','".$resposne_data2->data->address->dist."','".$resposne_data2->data->address->loc."','".$resposne_data2->data->address->country."','".$resposne_data2->data->address->subdist."','".$resposne_data2->data->address->street."','".$resposne_data2->data->address->vtc."','".$resposne_data2->data->address->state."','".$resposne_data2->data->address->house."','".$resposne_data2->data->address->po."','".$resposne_data2->data->zip."','".$fn."');";
						
					}
					echo "<script>$(function(){ toastr.success('Aadhar Verified Successfully'); }); </script>";					
				}
				else
				{
					$checkEmpData="SELECT * FROM ems.aadhar_verifiaction where EmployeeID='".$userempid."' limit 1;";
					$myDB=new MysqliDb();
					$verifyEmpData=$myDB->rawQuery($checkEmpData);
					$mysql_error = $myDB->getLastError();
					echo $verifyEmpData;
					if(count($verifyEmpData) > 0 )
					{
						$sql="UPDATE aadhar_verifiaction  SET aadhar_status = 'pending' WHERE EmployeeID = '".$userempid."'; ";
					}
					else
					{
						$fn="";//$resposne_data2->data->aadhaar_number.'.jpg';
						$image = base64_to_jpeg($proimg, ROOT_PATH.'aadharverification/'.$resposne_data2->data->aadhaar_number.'.jpg');

						$sql = "INSERT INTO aadhar_verifiaction (EmployeeID, adhar_no,created_by,aadhar_status,EmpName,FatherName,DOB,dist,loc,country,subdist,street,vtc,state,house,po,zip,image) VALUES ('".$userempid."', '".$resposne_data2->data->aadhaar_number."', '".$userempid."' , 'pending','".$empname."','".$FatherName."','".$resposne_data2->data->dob."','".$resposne_data2->data->address->dist."','".$resposne_data2->data->address->loc."','".$resposne_data2->data->address->country."','".$resposne_data2->data->address->subdist."','".$resposne_data2->data->address->street."','".$resposne_data2->data->address->vtc."','".$resposne_data2->data->address->state."','".$resposne_data2->data->address->house."','".$resposne_data2->data->address->po."','".$resposne_data2->data->zip."','".$fn."';)";							
					}
					echo "<script>$(function(){ toastr.error('Aadhar No Not Verified '); }); </script>";
				}
					
				$myDB=new MysqliDb();
				$submitdata=$myDB->rawQuery($sql);
				$mysql_error = $myDB->getLastError();
			}
			
			else{
				echo "<script>$(function(){ toastr.error('Aadhar No Not Find'); }); </script>";
			}
		}
		else{
			echo "<script>$(function(){ toastr.error('Aadhar No Not verified'); }); </script>";
		}
}
function base64_to_jpeg( $base64_string, $output_file ) {	
    $ifp = fopen($output_file, "wb" ); 
    fwrite( $ifp, base64_decode( $base64_string) ); 
    fclose( $ifp ); 
    return( $output_file ); 
}

?>



<style>
	.short,.weak
	{
		color:red;
	}
	.good
	{
		color:#e66b1a;
	}
	.strong
	{
		color:green;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Aadhar Verification</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">
<!-- Header for Form If any -->
<div style="margin-left: 15px; line-height:2; ">
	 Dear <strong> <?php echo $_SESSION['__user_Name'];?>,</strong>
	 <br />
	 Hope you are having a great start of the day!<br />
	In a constant endeavor to keep the EMS database updated, it is required to have your profile information updated in sync with your aadhaar card information.
	 <br />
Please click the Generate OTP button below to receive the OTP generated by Aadhaar system that you shall receive on your Aadhaar registered mobile number. As a next step, please input the OTP in the textbox below and click Submit OTP button.
	 <br />
	 That all the help we need from you.
	<br />
	<br />
	<strong> Thanks,
	 <br />
	 Cogent EMS Team</strong>			
</div>
<!-- Form container if any -->
	<div class="schema-form-section row" >
	
			
			<form id="genrateotp">
				<div class="input-field col s12 m12 center-align">
				   <button name="btn_genrateOtp" id="btn_genrateOtp" class="btn waves-effect waves-green">Genrate OTP</button>
				</div>
			</form>
			<?php if(isset($resposne_data->data->client_id) && $resposne_data->data->client_id!=""){?>
				<form id="verify_otp" method="post">
					<div class="input-field col s5 m5">
					  <input type="hidden" id="client_id"   name="client_id"  value="<?php echo $resposne_data->data->client_id?>"/>					  
					</div>
					<div class="input-field col s5 m5">
					  <input type="text" id="otp"   name="otp" placeholder="Enter OTP"/>	
					   <label for="otp">Enter OTP</label>
					</div>
					<div class="input-field col s12 m12 center-align">
					   <button type="submit" name="btn_verify_otp" id="btn_verify_otp" class="btn waves-effect waves-green">Submit OTP</button>
					</div>					
				</form>
				<?php }?>
					<div>
					<img src="<?php echo '../aadharverification/'.$resposne_data2->data->aadhaar_number.'.jpg'; ?>" />	
					</div>
	</div>
	</div>    
  </div>
</div>
<script>
	$(document).ready(function(){
		    $('#btn_genrateOtp').on('click', function(){
		        $("#genrateOtp").submit();
		       
		    });
	});
	$(document).ready(function(){
		    $('#btn_verify_otp').on('click', function(){
		        $("#verify_otp").submit();
		       
		    });
	});
	
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
