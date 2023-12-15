<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
// Set timezone
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='verifyFirstStep' && isset($Data['EmployeeID']) && $Data['EmployeeID']!="" && isset($Data['VerifiedByEmpId']) && !empty( $Data['VerifiedByEmpId']) && isset($Data['locationLat']) && !empty( $Data['locationLat']) && isset($Data['locationLng']) && !empty( $Data['locationLng']))
{
	//For Local testing
	//$dir_locationToSavePhotos = __DIR__.'/../Images/';
	
	
	//For Sevrer Production.
	$dir_locationToSavePhotos = __DIR__.'/../assetSingle/';
	
	$EmployeeID=$Data['EmployeeID'];
	$VerifiedByID=$Data['VerifiedByEmpId'];
	$lat=$Data['locationLat'];
	$lng=$Data['locationLng'];
	$empPhoto64Bit=$Data['empPhoto'];
	$empHomePhoto64Bit=$Data['empHomePhoto'];
	$empAssetPhoto64Bit=$Data['empAssetPhoto'];
	$empSignPhoto64Bit=$Data['empSignPhoto'];
	 $extension = '.jpg'; 
	 $dateTime = date("Ymdhis");
	 
	$decoded_fileEmpPhoto = base64_decode($empPhoto64Bit); // decode the file
	
	 $fileNameEmpPhoto = $EmployeeID.'_EmpPhoto_'.$dateTime.$extension;// rename file as a unixque name
	 
	 $decoded_fileHomeEmpPhoto = base64_decode($empHomePhoto64Bit); // decode the file
	
	 $fileNameEmpHomePhoto = $EmployeeID.'_EmpHome_'.$dateTime.$extension;// rename file as a unixque name
	 
	 $decoded_fileEmpAssetPhoto = base64_decode($empAssetPhoto64Bit); // decode the file
	
	 $fileNameEmpAssetPhoto = $EmployeeID.'_EmpAsset_'.$dateTime.$extension;// rename file as a unixque name
	 
	 $decoded_fileEmpSignPhoto = base64_decode($empSignPhoto64Bit); // decode the file
	
	 $fileNameEmpSignPhoto = $EmployeeID.'_EmpSign_'.$dateTime.$extension;// rename file as a unixque name
	 
	 //Var to Check All the Photos Uploaded.
	 $emphotoUploaded = 0;
	 $emAssetPhotoUploaded = 0;
	 $eHomePphotoUploaded = 0;
	 $emSignPhotoUploaded = 0;
	 
	 
	 //Save Emp Photo
	  if($decoded_fileEmpPhoto!=""  )
  	{
  		if(file_put_contents($dir_locationToSavePhotos.$fileNameEmpPhoto, $decoded_fileEmpPhoto))
	 	{
	 		$emphotoUploaded =1;
		}
  	
	}
	
	
	 //Save Emp Asset Photo
	  if($decoded_fileEmpAssetPhoto!=""  )
  	{
  		if(file_put_contents($dir_locationToSavePhotos.$fileNameEmpAssetPhoto, $decoded_fileEmpAssetPhoto))
	 	{
	 		$emAssetPhotoUploaded =1;
		}
  	
	}
	
	
	 //Save Emp Home Photo
	  if($decoded_fileHomeEmpPhoto!=""  )
  	{
  		if(file_put_contents($dir_locationToSavePhotos.$fileNameEmpHomePhoto, $decoded_fileHomeEmpPhoto))
	 	{
	 		$eHomePphotoUploaded =1;
		}
  	
	}
	
	 //Save Emp Sign Photo
	  if($decoded_fileEmpSignPhoto!=""  )
  	{
  		if(file_put_contents($dir_locationToSavePhotos.$fileNameEmpSignPhoto, $decoded_fileEmpSignPhoto))
	 	{
	 		$emSignPhotoUploaded =1;
		}
  	
	}
	
	
	//Check For All Files Updation 
	if($emSignPhotoUploaded == 1 && $eHomePphotoUploaded ==1 &&  $emAssetPhotoUploaded ==1 && $emphotoUploaded ==1 ){
		
		$dateVerify = date('Y-m-d h:i:s');
		//Insert The Data To Asset Coordinates Table.
		$QueryInsert = "INSERT INTO `ems`.`asset_coordinates` (`EmployeeID`,`empImage`, `assetImgComplete`, `home_img`,`emp_signature_img`, `asset_lat`, `asset_lng`,`verified_By`,`verifiedDate`) VALUES ('".$EmployeeID."', '".$fileNameEmpPhoto."', '".$fileNameEmpAssetPhoto."', '".$fileNameEmpHomePhoto."', '".$fileNameEmpSignPhoto."', '".$lat."', '".$lng."', '".$VerifiedByID."', '".$dateVerify."')"  ;  
			 $myDB =  new MysqliDb();
			 $responseAsset =$myDB->rawQuery($QueryInsert);
			
			 
			 //Proceed Only IF The Employee have ant Asset In Pending.
			if (empty($myDB->getLastError()) )
				{
					$result['msg']='Step 1 - Completed Successfully !';
				$result['status']=1;
				$result['insertId']=$myDB->getInsertId();
			}else{
				$result['msg']='failed to save data, Please retry!';
			$result['status']=0;
			}
		
		
	}else{
		$result['msg']='failed to save photos, Please retry!';
		$result['status']=0;
	}

	
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
		
}
echo  json_encode($result);

?>

