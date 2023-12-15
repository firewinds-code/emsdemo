<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
 $AssetimageBaseUrl = 'https://demo.cogentlab.com/erpm/assetMultiple/';
 $ImageBaseUrl = 'https://demo.cogentlab.com/erpm/assetSingle/';
$response=array();
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='getEmpDetails' && isset($Data['EmployeeID']) && $Data['EmployeeID']!="")
{
	
	$EmployeeID=$Data['EmployeeID'];
	 
	 $QueryCheck = "SELECT id, EmployeeID, assetName, model, company, add_1, add_2, add_3, add_4, assetImg, isDamaged, isCollected, isModel, isCompany, isAdd_1, isAdd_2, isAdd_3, isAdd_4, assetRemark, status, asset_flag, asset_coordiante_id, assignedDate, verifyDate, createdDate from asset_info where EmployeeID = '".$EmployeeID."' and asset_flag =0 and cast(createdDate as date) < cast(now() as date) "  ;  
					 $myDB =  new MysqliDb();
					 $responseAsset =$myDB->rawQuery($QueryCheck);
					
					 
					 //Proceed Only IF The Employee have ant Asset In Pending.
					if (empty($myDB->getLastError()) && count($responseAsset)>0 )
						{
							//Set In The Result that Employee Has Asset Provided.
							$result['IsAssetProvided']=1;
							
							//Also Set The BAse URL Data for Asset And Other Images .
							$result['AssetBaseUrl']=$AssetimageBaseUrl;
							$result['ImageBaseUrl']=$ImageBaseUrl;
							
							//Now Get The Employee Details.
							 $QuerySelect = "call emp_info('".$EmployeeID."')"  ;  
							 $myDB =  new MysqliDb();
							 $response =$myDB->rawQuery($QuerySelect);
							
							 
							if (empty($myDB->getLastError()) && count($response)>0 )
								{
									$result['status']=1;
									$result['msg']='Data found';
									$result['empDetails']=$response;
									$result['empAssetList']=$responseAsset;
								}	 
					       else
					           {
									$result['status']=0;
									$result['msg']='Employee is not provided any asset.';
									
							  }
							
						} 
						else 
						{
							$result['status']=1;
							$result['IsAssetProvided']=0;
							$result['msg']='Employee asset is already verified today or  assigned today.';
						}
	
	
	
	
		
	
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
		
}
echo  json_encode($result);

?>

