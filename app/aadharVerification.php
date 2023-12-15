<?php  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
include(__dir__.'/../Controller/endecript.php');
ini_set("display_errors",'1');
header("Content-Type: application/json; charset=UTF-8");
	$_POST = file_get_contents('php://input');
	$Data=json_decode($_POST,true);
	if(isset($Data['EmployeeId']) && $Data['AdhaarResp']!="")
	{
		
		//echo $Data['AdhaarResp'];
	
		
		
		$ofc_loc=$Data['location'];
		if($ofc_loc == "1" || $ofc_loc == "2")
		{	
			$dir_location = "Docs/AdharCard/";
			//$dir_location = "Docs/AdharCard2/";
		}
		else if($ofc_loc == "3")
		{
			$dir_location = "Meerut/Docs/AdharCard/";
		}
		else if($ofc_loc == "4")
		{
			$dir_location="Bareilly/Docs/AdharCard/";
		}
		else if($ofc_loc == "5")
		{
			$dir_location="Vadodara/Docs/AdharCard/";
		}
		else if($ofc_loc == "6")
		{
			$dir_location="Manglore/Docs/AdharCard/";
		}
		else if($ofc_loc == "7")
		{
			$dir_location="Bangalore/Docs/AdharCard/";
		}
		$userempid=$empid=$Data['EmployeeId'];
		//$data=str_replace("'",'"', $Data['AdhaarResp']);
		// $response_data= json_decode($data);
		 $response_data= json_decode($Data['AdhaarResp']);
		//print_r($response_data);
		//die;
		if($response_data->success=="true")
		{ 
			$empname = encrypt($response_data->data->full_name,"encrypt");
			$aadhaar_FatherName = encrypt($response_data->data->care_of,"encrypt");
			$aadhaarno = encrypt($response_data->data->aadhaar_number,"encrypt");				
			$aadhaardob = encrypt($response_data->data->dob,"encrypt");	
			//$FatherName=ltrim($response_data->data->care_of,"S/O C/O W/O D/O H/O :");			
			//$FatherName=trim($FatherName[0]);
			$FatherName=$response_data->data->care_of;
			$first_name=explode(" ",$response_data->data->full_name);
			$proimg=$response_data->data->profile_image;
			$FatherName1=explode(" ",$response_data->data->care_of);			
			$checkData="SELECT trim(FirstName) as FirstName,FatherName,cast(DOB as date) as DOB,EmployeeID FROM ems.personal_details where EmployeeID='".$userempid."' and trim(FirstName)='".trim($first_name[0])."' and cast(DOB as date)='".$response_data->data->dob."' limit 1;";
		
			$myDB=new MysqliDb();
			$verifyData=$myDB->rawQuery($checkData);
			$mysql_error = $myDB->getLastError();
			if(empty($mysql_error) && count($verifyData) > 0 &&  $verifyData[0]['EmployeeID']!=NULL)
			{
				$remarks=" ";					
				if(strpos(strtolower(trim($FatherName)),str_replace('  ',' ',strtolower(trim($verifyData[0]['FatherName'])))))					
				{
					$remarks =" DOB,first name,father name matched";
					
				}
				else{
					$remarks = " DOB,first name matched but Father Name not Match";
				}
				$remarks='App '.date('Y-m-d').' : '.$remarks;
				$dataurl=$response_data->data->aadhaar_pdf;
				if($dataurl)
				{
					
					$myDB =  new MysqliDb();
					$selectDoc="select doc_file from doc_details  where EmployeeID='".$userempid."' and doc_stype='Aadhar Card' and doc_type='Proof of Address'";
					$doc_data=$myDB->query($selectDoc);
					if(count($doc_data)>0)
					{
						foreach($doc_data as $dataval)
						{
							$file=$dataval['doc_file'];
							if(file_exists("../".$dir_location.$file))
							{
								@unlink("../".$dir_location.$file);
							}
						}
					}
				
					$datetime=date("Y-m-d h:i:s");
	 			 	$filename=$userempid.'_AadharCard.pdf';
				 	$saveto = ROOT_PATH.$dir_location.$filename;
					$content1 = file_get_contents($dataurl);
					if(file_put_contents($saveto, $content1))
					{
						if($response_data->data->aadhaar_number!=""){
					 		$fn=$response_data->data->aadhaar_number.'.jpg';
					 	}else{
					 		$fn="";
					 	}
					 	$aadhar_status= 'verified';
						$myDB=new MysqliDb();
						$veryfydate=$datetime;
						$result = $myDB->query("SELECT aadhar_status,remarks FROM aadhar_verifiaction where EmployeeID = '".$userempid."' ");
						if($result[0]['aadhar_status']!=NULL && $result[0]['aadhar_status']!='')
						{
							$remarks=$remarks.' | '.$result[0]['remarks'];
							
							$updateVerifiactionQuery="Update aadhar_verifiaction set  adhar_no='".$aadhaarno."',created_by='".$empid."',created_at='".$datetime."',aadhar_status='".$aadhar_status."',EmpName='".$empname."',FatherName='".$aadhaar_FatherName."',DOB='".$aadhaardob."',dist='".$response_data->data->address->dist."',loc='".$response_data->data->address->loc."',country='".$response_data->data->address->country."',subdist='".$response_data->data->address->subdist."',street='".$response_data->data->address->street."',vtc='".$response_data->data->address->vtc."',state='".$response_data->data->address->state."',house='".$response_data->data->address->house."',po='".$response_data->data->address->po."',zip='".$response_data->data->zip."',image='".$fn."',remarks='".$remarks."',aadhar_image_code='".addslashes($response_data->data->profile_image)."', verify_date='".$veryfydate."' where EmployeeID='".$empid."' ";
							$myDB=new MysqliDb();
							$myDB->rawQuery($updateVerifiactionQuery);
							
						}else
						{
							$insertVerifiactionQuery="INSERT INTO aadhar_verifiaction set EmployeeID='".$empid."', adhar_no='".$aadhaarno."',created_by='".$empid."',aadhar_status='".$aadhar_status."',EmpName='".$empname."',FatherName='".$aadhaar_FatherName."',DOB='".$aadhaardob."',dist='".$response_data->data->address->dist."',loc='".$response_data->data->address->loc."',country='".$response_data->data->address->country."',subdist='".$response_data->data->address->subdist."',street='".$response_data->data->address->street."',vtc='".$response_data->data->address->vtc."',state='".$response_data->data->address->state."',house='".$response_data->data->address->house."',po='".$response_data->data->address->po."',zip='".$response_data->data->zip."',image='".$fn."',remarks='".$remarks."',aadhar_image_code='".addslashes($response_data->data->profile_image)."', verify_date='".$veryfydate."' ";	
							$myDB=new MysqliDb();
							$myDB->rawQuery($insertVerifiactionQuery);
						}
							
						$myDB =  new MysqliDb();
						$deleteOldone="DELETE from doc_details where EmployeeID='".$userempid."' and doc_stype='Aadhar Card' and doc_type='Proof of Address'";
						$delete_adhar=$myDB->query($deleteOldone);
						$insertquery="Insert into doc_details set EmployeeID='".$userempid."',doc_stype='Aadhar Card',doc_type='Proof of Address', dov_value='".$response_data->data->aadhaar_number."', doc_file='".$filename."',createdon=now(),modifiedon=now(),aadhar_source='chooseFromAdharAPI'";
						$myDB =  new MysqliDb();
						$myDB->query($insertquery);
						$error = $myDB->getLastError();
						
						
						/*  Call API for Aadhar OCR*/
						
						  $url = URL.'View/aadhar_ocr.php?empid='.$userempid;
						// $url = URL.'View/aadhar_ocr_pythonapi.php?empid='.$userempid;
						
						$curll = curl_init();
						curl_setopt($curll, CURLOPT_URL,$url);
						curl_setopt($curll, CURLOPT_RETURNTRANSFER,1);
						curl_setopt($curll, CURLOPT_TIMEOUT, 30);
						curl_setopt($curll, CURLOPT_SSL_VERIFYPEER, FALSE);
						 curl_exec($curll); 
						curl_close($curll);
						//echo "<script>$(function(){ toastr.success('curl result ".$result."'); }); </script>";		
						//include("aadhar_ocr.php");
						$result['message']='Verified';
						$result['status']=1;
					
					}
					
				}
			
			}else
			{
				 $checkData1 = "SELECT trim(FirstName) as FirstName,FatherName,cast(DOB as date) as DOB FROM personal_details where EmployeeID='".$userempid."' limit 1;";

				$myDB=new MysqliDb();
				$verifyData1=$myDB->rawQuery($checkData1);
				$mysql_error = $myDB->getLastError();
				$remarks=" ";
				if(strtolower(trim($verifyData1[0]['FirstName'])) !=strtolower(trim($first_name[0])))
				{
					$remarks .="First name, ";  
				}
				if($verifyData1[0]['DOB'] !=$response_data->data->dob)
				{
					$remarks .="DOB, "; 
				}
				if(strpos(strtolower(trim($FatherName)),str_replace('  ',' ',strtolower(trim($verifyData1[0]['FatherName'])))))					
				{
					$remarks .= "Father Name ";
				}
				$remarks1='';
				$remarks .= " not matched";
				 $remarks1='App '.date('Y-m-d').' : '.$remarks;
			
				$result = $myDB->query("SELECT aadhar_status,remarks FROM aadhar_verifiaction where EmployeeID = '".$userempid."' ");
				if($result[0]['aadhar_status']!=NULL && $result[0]['aadhar_status']!='')
				{
					$remarks1=$result[0]['remarks'].' | '.$remarks;
					$sql="UPDATE aadhar_verifiaction  SET aadhar_status = 'pending' , remarks='".$remarks1."' WHERE EmployeeID = '".$userempid."' ";
				}else{
					$sql="INSERT into aadhar_verifiaction  SET aadhar_status = 'pending' , remarks='".$remarks1."',EmployeeID = '".$userempid."' ";
				}
				$myDB=new MysqliDb();
				$verifyData1=$myDB->rawQuery($sql);
				$mysql_error = $myDB->getLastError();
				$result=array();
				$result['message']='Not Verified, '.$remarks;
				$result['status']=0;
			}
		}
		else{
			$result['message']='Invalid Aadhar Response';
		}
}else{
	$result['message']='Invalid Data';
}
print_r(json_encode($result));
?>


				
				
				
