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


if(isset($Data['appkey']) && $Data['appkey']=='verifierFeedBAck'  && isset($Data['feedBack']) && !empty( $Data['feedBack']) && isset($Data['insertId']) && !empty( $Data['insertId']))
{
	
	
	
	$insertIdAssetCorrdTable=$Data['insertId'];
	$feedBack=$Data['feedBack'];
	
	
	
		
		 
	 	  $UpdateQ = "UPDATE `asset_coordinates` SET `verifier_feedback` = '".$feedBack."' WHERE (`id` = '".$insertIdAssetCorrdTable."')"  ;  
			
			 $myDB =  new MysqliDb();
			 $responseupdate =$myDB->rawQuery($UpdateQ);
		 	
			 //Proceed Only IF The Employee have ant Asset In Pending.
			if (empty($myDB->getLastError()) )
				{
					
					$result['status']=1;
					$result['msg']='All Asset Verified Successfully and Go Back to Verify Another.';
				
						
				}else{
					$result['msg']='failed to save data, Please retry!';
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

