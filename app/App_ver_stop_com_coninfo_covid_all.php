<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
date_default_timezone_set("Asia/Kolkata");
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$result=array();
$whereClause=$sender_ID=$dayofweek=$EmployeeID="";
$myDB =  new MysqliDb();
if(isset($Data['appkey']) && $Data['appkey'] !=' ')
{
	     if( $Data['appkey']=='appStopStatus' || $Data['appkey']=='all')
          {
	            $query = 'select * from stop_app where flag=1 order by id desc limit 1 ;';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msgS']="Data  Found.";
			        $result['statusS']=1;
			        $result['appStopStatus']=$res;
				}
				else
				{
				     $result['msgS']="Data Not Found.";
			         $result['statusS']=0;	
				}	        
			}	
			
		   if($Data['appkey']=='appVer' || $Data['appkey']=='all')
			{
	            $query = 'select version,description,DATE_FORMAT(updated_date,"%Y-%m-%d")as updated_date, version_number,mandatory  from app_maintenance order by id desc limit 1;';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msgV']="Data  Found.";
			        $result['statusV']=1;
			        $result['appVersion']=$res;
				}
				else
				{
				     $result['msgV']="Data Not Found.";
			         $result['statusV']=0;	
				}	        
			}	
			
		    if($Data['appkey']=='communication' || $Data['appkey']=='all')
            {
            	
            	if(isset ($Data['EmployeeID']) && $Data['EmployeeID']!="" )
            	
            	{
            		    $sender_ID=$Data['EmployeeID'];
						$whereClause ="where to_empid='".$sender_ID."' and t1.ackstatus=0";
			   		 $query="select t1.ID, t1.ackstatus, t1.msg_date, t1.text_msg, t1.to_empid, t1.sender_empid, t1.sender_name,t2.EmployeeName from tbl_chat_message t1 inner join personal_details t2 on t1.to_empid=t2.EmployeeID $whereClause  order by ackstatus,id desc";
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msgC']="Data  Found.";
			        $result['statusC']=1;
			        $result['communicationResponse']=$res;
				}
				else
				{
				     $result['msgC']="Data Not Found.";
			         $result['statusC']=0;	
				}	      
				}
				else
				{
					 $result['msgC']="Bad request.";
			         $result['statusC']=0;
				}
            	     
			}	
			   if($Data['appkey']=='con_upd_dt' || $Data['appkey']=='all')
          {
          	     $EmployeeID=$Data['EmployeeID'];
	            $query = 'SELECT contact_details.mobile,contact_details.altmobile,contact_details.em_contact,contact_details.emailid,contact_details.em_relation,DATE_FORMAT(tbl_contact_log.created_on,"%Y-%m-%d") as created_on  FROM contact_details inner join tbl_contact_log on contact_details.EmployeeID=tbl_contact_log.EmployeeID where contact_details.EmployeeID="'.$EmployeeID.'" order by created_on desc limit 1 ;';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msgCUD']="Data  Found.";
			        $result['statusCUD']=1;
			        $result['contact_updated_date']=$res;
				}
				else
				{
				     $result['msgCUD']="Data Not Found.";
			         $result['statusCUD']=0;	
				}	        
			}	
			
             if($Data['appkey']=='covid_dec' || $Data['appkey']=='all')
            {
            	
            	if(isset ($Data['EmployeeID']) && $Data['EmployeeID']!="" )
            	{
            		$EmployeeID=$Data['EmployeeID'];
            		$dayofweek = date("w",strtotime(date('Y-m-d')));
						if($dayofweek==0)
						{
							 $currentMondayDate= date('Y-m-d', strtotime('monday last week'));
						}
						else
						{
							$currentMondayDate= date('Y-m-d', strtotime('monday this week'));
						}
			   		 $query="select work_from , c.createdOn from ack_covid_weekly_form as c inner join roster_temp on roster_temp.EmployeeID=c.EmployeeID where roster_temp.EmployeeID ='".$EmployeeID."' and roster_temp.DateOn=DATE_FORMAT(now(),'%Y-%m-%d')  and cast(c.createdOn as date) between '".$currentMondayDate."' and cast(NOW() as date) ;";
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msgCD']="Data  Found.";
			        $result['statusCD']=1;
			        $result['covid_decResponse']=$res;
				}
				else
				{
				     $result['msgCD']="Data Not Found.";
			         $result['statusCD']=0;	
				}	      
				}
				else
				{
					 $result['msgC']="Bad request.";
			         $result['statusC']=0;
				}
            	     
			}	
			////////////////
			
			   if($Data['appkey']=='adharv' || $Data['appkey']=='all')
            {
            	
            	if(isset ($Data['EmployeeID']) && $Data['EmployeeID']!="" )
            	{
            		$EmployeeID=$Data['EmployeeID'];
			   		 $query="select EmployeeID,doc_type,doc_stype from doc_details  where EmployeeID ='".$EmployeeID."' and  doc_stype ='Aadhar Card' and doc_type='Proof of Address' and (aadhar_source='chooseFromdigilocker' or aadhar_source='FromdigilockerApp');";
                $res =$myDB->query($query);
                $error = $myDB->getLastError();
                if(empty($error))
				 {
					
			            $result['statusAV']=1;
				        if(count($res) > 0)
				        {
				        	$result['msgAV']="Verified.";
							$result['adhaarV']=1;
						}
						else
						{
							$result['msgAV']="You are not verified yet, please verify your adhaar first.";
							$result['adhaarV']=0;
						}
				}
				else
				{
				     $result['msgAV']="Data Not Found.";
			         $result['statusAV']=0;	
				}	      
				}
				else
				{
					 $result['msgAV']="Set employee id.";
			         $result['statusAV']=0;
				}
            	     
			}
   
			/////////////////////////////
			if($Data['appkey']=='adhaarNewV' || $Data['appkey']=='all')
            {
            	
            	if(isset ($Data['EmployeeID']) && $Data['EmployeeID']!="" )
            	{
            		$EmployeeID=$Data['EmployeeID'];
			   		 $query="select EmployeeID,aadhar_status,created_at from aadhar_verifiaction  where EmployeeID ='".$EmployeeID."' ;";
                $res =$myDB->query($query);
                $error = $myDB->getLastError();
                if(empty($error) )
				 {	
							
					if(count($res) == 0){
								$result['statusAVNew']=1;
								$orgDate = "2021-08-01";  
								$vCreated_at = date("Y-m-d", strtotime($orgDate)); 
								$now = time(); // or your date as well
								$your_date = strtotime($vCreated_at);
								$datediff = $now - $your_date;

								$daysDifference = 	round($datediff / (60 * 60 * 24));
						
								$result['adhaarVNew']=0;
								$result['adhaarVNewDialog']=2;

								$tt ='days';
								if((30-$daysDifference) <= 1){
									$tt ='day';
								}	

								 // $result['msgAVNew']="Please verify your Aadhar card before ".(30-$daysDifference)." ".$tt."";
								//$result['msgAVNew']="Your Aadhaar card is not verified, Please verify as soon as //possible.\n\n(Verification Window : 10:00 AM - 06:00 PM)";
								$result['msgAVNew']="Your Aadhaar card is not verified, Please verify as soon as possible.";
								$result['CurrentTimeAVNew']=date("Y-m-d H:i:s");		
								/* if($daysDifference <= 30){
									$result['adhaarVNewDialog']=2;

									$tt ='days';
									if((30-$daysDifference) <= 1){
										$tt ='day';
									}

									// $result['msgAVNew']="Please verify your Aadhar card before ".(30-$daysDifference)." ".$tt."";
									$result['msgAVNew']="No. of days left to verify Aadhaar Card : ".(30-$daysDifference)."\n\nPlease verify your Aadhaar card before ".date('l, d F Y',strtotime('+30 days',strtotime($vCreated_at)))."\n\n(Verification Window : 10:00 AM - 06:00 PM)";
									$result['CurrentTimeAVNew']=date("Y-m-d H:i:s");
								
								}else{
									$result['adhaarVNewDialog']=3;
									$result['msgAVNew']="Your Aadhaar card is not veryfied, please verify first in order to login! \n\n(Verification Window : 10:00 AM - 06:00 PM)";
									$result['CurrentTimeAVNew']=date("Y-m-d H:i:s");		
								}	 */
								
					}else if(count($res) > 0) {
								
						$result['statusAVNew']=1;

						$vStat = $res[0]['aadhar_status'];
						$vCreated_at = $res[0]['created_at'];

						$now = time(); // or your date as well
						$your_date = strtotime($vCreated_at);
						$datediff = $now - $your_date;	

						$daysDifference = 	round($datediff / (60 * 60 * 24));
				        if($vStat == 'verified')
				        {
				        	$result['msgAVNew']="Verified.";
							$result['adhaarVNew']=1;
							$result['adhaarVNewDialog']=1;
						}
						else
						{
							
							$result['adhaarVNew']=0;
							$result['adhaarVNewDialog']=2;

								$tt ='days';
								if((30-$daysDifference) <= 1){
									$tt ='day';
								}

							 	// $result['msgAVNew']="Please verify your Aadhar card before ".(30-$daysDifference)." ".$tt."";
							//$result['msgAVNew']="Your Aadhaar card is not verified, Please verify as soon as possible.\n\n(Verification //Window : 10:00 AM - 06:00 PM)";
								$result['msgAVNew']="Your Aadhaar card is not verified, Please verify as soon as possible.";
								$result['CurrentTimeAVNew']=date("Y-m-d H:i:s");
							/* if($daysDifference <= 30){
								$result['adhaarVNewDialog']=2;

								$tt ='days';
								if((30-$daysDifference) <= 1){
									$tt ='day';
								}

								// $result['msgAVNew']="Please verify your Aadhar card before ".(30-$daysDifference)." ".$tt."";
								$result['msgAVNew']="No. of days left to verify Aadhaar Card : ".(30-$daysDifference)."\n\nPlease verify your Aadhaar card before ".date('l, d F Y',strtotime('+30 days',strtotime($vCreated_at)))."\n\n(Verification Window : 10:00 AM - 06:00 PM)";
								$result['CurrentTimeAVNew']=date("Y-m-d H:i:s");
								
							}else{
								$result['adhaarVNewDialog']=3;
								$result['msgAVNew']="Your Aadhaar card is not veryfied, please verify first in order to login! \n\n(Verification Window : 10:00 AM - 06:00 PM)";
								$result['CurrentTimeAVNew']=date("Y-m-d H:i:s");
							} */
						}
					}
					
			           
				}
				else
				{
				     $result['msgAVNew']="Data Not Found.";
			         $result['statusAVNew']=0;	
				}	      
				}
				else
				{
					 $result['msgAVNew']="Set employee id.";
			         $result['statusAVNew']=0;
				}
            	     
			}
			
			
			
			////////New Update Section 
			  if($Data['appkey']=='getUrl' || $Data['appkey']=='all')
            {
				
				$userCmId = 'All';
				
				//GET CM ID IF ANY PASSED
				if(isset($Data['cmId']) && !empty($Data['cmId']) ){
					$userCmId = $Data['cmId'];
				}
				
            	$resultgetUrl = array();
            	$ImageList=array();
            	$ImageListDiscount=array();
				$ImageListLifeCogent=array();

            	$ImageListRecog=array();
            	//For Testing In Local
				/*  $dirPath = __DIR__.'/../Images/';
				 $imageBaseUrl ='http://192.168.24.76/ems/branches/Images/';*/
				
				//For Server
				$dirPath =  __DIR__.'/../IndexEditPage/appimg/';
			    $imageBaseUrl = 'https://demo.cogentlab.com/erpm/IndexEditPage/appimg/';
			    $imageBaseUrlDiscount = 'https://demo.cogentlab.com/erpm/IndexEditPage/emp_discount_app/';
				$imageBaseUrlLifeCogent = 'https://demo.cogentlab.com/erpm/IndexEditPage/emp_lifecogent_app/';

			    $imageBaseUrlRecognition = 'https://demo.cogentlab.com/erpm/IndexEditPage/emp_recognition_app/';
				
				////////BANNER MAIN RELATED IMAGES
				$myDB=new MysqliDb();
				$sqlSelect="select file_name from ipp_details where  banner_type = 'Banner' and platform='APP' and ( cmid = '".$userCmId."'  OR cmid = 'All' ) ;";
				$resultByb = $myDB->rawQuery($sqlSelect);
				$mysql_error = $myDB->getLastError();
				
				if(empty($mysql_error) && count($resultByb) > 0 ){
					 foreach ($resultByb as $fileRow)
					{		 
						 array_push($ImageList, $imageBaseUrl.$fileRow['file_name']);

					}  
				}
				
				////////Discount Related IMAGES
				$myDB=new MysqliDb();
				$sqlSelect="select file_name from ipp_details where banner_type = 'Discount' and platform='APP' and ( cmid = '".$userCmId."'  OR cmid = 'All' );";
				$resultByd = $myDB->rawQuery($sqlSelect);
				$mysql_error = $myDB->getLastError();
				
				if(empty($mysql_error) && count($resultByd) > 0 ){
					 foreach ($resultByd as $fileRow)
					{		 
						   array_push($ImageListDiscount, $imageBaseUrlDiscount.$fileRow['file_name']);

					}  
				}

				//Life at cogent images
				$myDB=new MysqliDb();
				$sqlSelect="select file_name from ipp_details where banner_type = 'LifeCogent' and platform='APP' and ( cmid = '".$userCmId."'  OR cmid = 'All' );";
				$resultByd = $myDB->rawQuery($sqlSelect);
				$mysql_error = $myDB->getLastError();
				
				if(empty($mysql_error) && count($resultByd) > 0 ){
					 foreach ($resultByd as $fileRow)
					{		 
						   array_push($ImageListLifeCogent, $imageBaseUrlLifeCogent.$fileRow['file_name']);

					}  
				}
				
				////////Recognition Related IMAGES
				$myDB=new MysqliDb();
				$sqlSelect="select file_name from ipp_details where banner_type = 'Recognition' and platform='APP' and ( cmid = '".$userCmId."'  OR cmid = 'All' );";
				$resultBy = $myDB->rawQuery($sqlSelect);
				$mysql_error = $myDB->getLastError();
				
				if(empty($mysql_error) && count($resultBy) > 0 ){
					 foreach ($resultBy as $fileRow)
					{		 
						   array_push($ImageListRecog, $imageBaseUrlRecognition.$fileRow['file_name']);

					}  
				}
				
				//exit;
				//reading the Folder To get The Images.
					
				/* foreach (array_filter(glob( $dirPath.'*'), 'is_file') as $file)
				{
					
					array_push($ImageList, $imageBaseUrl.basename($file));
					
			    // Do something with $file
				} */
				 
				 
				 //Validating The Images list
				 if(count($ImageList) > 0 || count($ImageListDiscount) > 0 || count($ImageListRecog) > 0 || count($ImageListLifeCogent) > 0 ){
				 	$resultgetUrl['statusURL']=1;
					$resultgetUrl['msgURL']='Found Images.';
					$resultgetUrl['imageListURL']=$ImageList;
					$resultgetUrl['imageListURLDiscount']=$ImageListDiscount;
					$resultgetUrl['imageListURLRecog']=$ImageListRecog;
					$resultgetUrl['imageListURLLifeCogent']=$ImageListLifeCogent;
					$result['ImageUrlObject'] = $resultgetUrl;
				 }else{
				 	$resultgetUrl['statusURL']=0;
					$resultgetUrl['msgURL']='Data not found.';
					$result['ImageUrlObject'] = $resultgetUrl;
								
				 }
	
            	     
			}
			
			//validating complete request
		if(!isset($result['communicationResponse']) && !isset($result['contact_updated_date']) && !isset($result['covid_decResponse']) && !isset($result['appVersion']) && !isset($result['appStopStatus'])&& !isset($result['adharVerification'])&& !isset($result['ImageUrlObject']))	
		{
			$result['msg']="Not found.";
            $result['status']=0;
		}
			
		        
 }
else
 {
     $result['msg']="Bad Request";
     $result['status']=0;
 }
echo  json_encode($result);
?>

