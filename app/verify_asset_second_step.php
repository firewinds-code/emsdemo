<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
// Set timezone
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']='';


if(isset($Data['appkey']) && $Data['appkey']=='verifysecondStep' && isset($Data['EmployeeID']) && $Data['EmployeeID']!="" && isset($Data['insertIdOfCoordinateTable']) && !empty( $Data['insertIdOfCoordinateTable']) && isset($Data['assetId']) && !empty( $Data['assetId']) && isset($Data['assetAssignedDate']) && !empty( $Data['assetAssignedDate']) && isset($Data['assetName']) && !empty( $Data['assetName']) && isset($Data['model']) && !empty( $Data['model']) && isset($Data['company']) && !empty( $Data['company']))
{
	
	//For Local testing
	//$dir_locationToSavePhoto = __DIR__.'/../Images/';
	
	//For Sevrer Production.
	$dir_locationToSavePhoto = __DIR__.'/../assetMultiple/';
	$VerifiedDate= date("Y-m-d h:i:s");
	$EmployeeID=$Data['EmployeeID'];
	$insertIdAssetCorrdTable=$Data['insertIdOfCoordinateTable'];
	$assetId=$Data['assetId'];
	$assetAssignedDate=$Data['assetAssignedDate'];
	$assetName=$Data['assetName'];
	$assetModel=$Data['model'];
	$assetCompany=$Data['company'];
	$assetAdd1=$Data['add1'];
	$assetAdd2=$Data['add2'];
	$assetAdd3=$Data['add3'];
	$assetAdd4=$Data['add4'];
	$assetPhoto64Bit=$Data['assetPhoto'];
	$assetStatus=$Data['status'];
	$assetRemark=$Data['remark'];
	$isAssetDamaged=$Data['isDamaged'];
	$isAssetCollected=$Data['isCollected'];
	$isModel=$Data['isModel'];
	$isCompany=$Data['isCompany'];
	$isAdd1=$Data['isAdd1'];
	$isAdd2=$Data['isAdd2'];
	$isAdd3=$Data['isAdd3'];
	$isAdd4=$Data['isAdd4'];
	$extension = '.jpg'; 
	 $dateTime = date("Ymdhis");
	 
	$decoded_fileAssetPhoto = base64_decode($assetPhoto64Bit); // decode the file
	$assetNameWithoutSpace = str_replace(' ', '', $assetName);
	$fileNameAssetPhoto = $EmployeeID.'_asset_'.$assetNameWithoutSpace.$dateTime.$extension;// rename file as a unixque name
	 
	
	
	 //Var to Check  the Photo Uploaded.
	 $assetPhotoUploaded = 0;
	 
	 
	 //Save Emp Photo
	  if($decoded_fileAssetPhoto!=""  )
  	{
  		if(file_put_contents($dir_locationToSavePhoto.$fileNameAssetPhoto, $decoded_fileAssetPhoto))
	 	{
	 		$assetPhotoUploaded =1;
		}
  	
	}
	
	 
	 
	/*echo   $UpdateQ = "UPDATE `asset_info` SET `assetImg` = '".$fileNameAssetPhoto."', `isDamaged` = '".$isAssetDamaged."', `isCollected` = '".$isAssetCollected."',`isModel` = '".$isModel."', `isCompany` = '".$isCompany."', `isAdd_1` = '".$isAdd1."', `isAdd_2` = '".$isAdd2."', `isAdd_3` = '".$isAdd3."', `isAdd_4` = '".$isAdd4."', `assetRemark` = '".$assetRemark."', `status` = 'Verified', `asset_flag` = '1', `asset_coordiante_id` = '".$insertIdAssetCorrdTable."', `verifyDate` = '".$VerifiedDate."' WHERE (`id` = '".$assetId."')";  */
	 /* echo  $QueryInsert = "INSERT INTO `asset_info` ( `EmployeeID`, `assetName`, `model`, `company`, `add_1`, `add_2`, `add_3`, `add_4`, `isDamaged`, `isModel`, `isCompany`, `isAdd_1`, `isAdd_2`, `isAdd_3`, `isAdd_4`, `status`, `assignedDate`) VALUES ( '".$EmployeeID."', '".$assetName."', '".$assetModel."', '".$assetCompany."', '".$assetAdd1."', '".$assetAdd2."', '".$assetAdd3."', '".$assetAdd4."', '".$isAssetDamaged."', '".$isModel."', '".$isCompany."', '".$isAdd1."', '".$isAdd2."', '".$isAdd3."', '".$isAdd4."',  '".$assetStatus."', '".$assetAssignedDate."')";*/
	  
	//die;
	 
	
	
	//Check For All Files Updation 
	if($assetPhotoUploaded == 1 ){
		
		//Checking Whther we Require
			//Update the Data Of Previous asset Info.
					
	 	 $UpdateQ = "UPDATE `asset_info` SET `assetImg` = '".$fileNameAssetPhoto."', `isDamaged` = '".$isAssetDamaged."', `isCollected` = '".$isAssetCollected."',`isModel` = '".$isModel."', `isCompany` = '".$isCompany."', `isAdd_1` = '".$isAdd1."', `isAdd_2` = '".$isAdd2."', `isAdd_3` = '".$isAdd3."', `isAdd_4` = '".$isAdd4."', `assetRemark` = '".$assetRemark."', `status` = 'Verified', `asset_flag` = '1', `asset_coordiante_id` = '".$insertIdAssetCorrdTable."', `verifyDate` = '".$VerifiedDate."' WHERE (`id` = '".$assetId."')"  ;  
			
			 $myDB =  new MysqliDb();
			 $responseupdate =$myDB->rawQuery($UpdateQ);
		 	
			 //Proceed Only IF The Employee have ant Asset In Pending.
			if (empty($myDB->getLastError()) )
				{
					
					//If Checking if Asset is Not Taken then Again Insert The Asset Info for Re-Verifivation.
					if($isAssetCollected != 1 ){
						

						//Insert The Data To Asset Info Table.
						$QueryInsert = "INSERT INTO `asset_info` ( `EmployeeID`, `assetName`, `model`, `company`, `add_1`, `add_2`, `add_3`, `add_4`, `isDamaged`, `isModel`, `isCompany`, `isAdd_1`, `isAdd_2`, `isAdd_3`, `isAdd_4`, `status`, `assignedDate`) VALUES ( '".$EmployeeID."', '".$assetName."', '".$assetModel."', '".$assetCompany."', '".$assetAdd1."', '".$assetAdd2."', '".$assetAdd3."', '".$assetAdd4."', '".$isAssetDamaged."', '".$isModel."', '".$isCompany."', '".$isAdd1."', '".$isAdd2."', '".$isAdd3."', '".$isAdd4."',  '".$assetStatus."', '".$assetAssignedDate."')"  ;  
							
							 $myDB =  new MysqliDb();
							 $responseAsset =$myDB->rawQuery($QueryInsert);
							
							 
								 //Proceed Only IF The Employee have ant Asset In Pending.
								if (empty($myDB->getLastError()) )
									{
										$result['status']=1;
										$result['msg']='Asset - '.$assetName.' Verified Successfully.';
										//$result['msgForAllAvailVerification']='All Asset Verified Successfully and Go Back to Verify Another.';
								}else{
										$result['msg']='failed to save data, Please retry!';
									$result['status']=0;
								}
						
					}else{
						
						$result['status']=1;
						$result['msg']='Asset - '.$assetName.' Verified Successfully.';
					//	$result['msgForAllAvailVerification']='All Asset Verified Successfully and Go Back to Verify Another. ';
					}
				
						
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

