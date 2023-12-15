<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 	
ini_set('display_errors', '1'); 
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$response['msg']='';

	
if(isset($Data['appkey']) && $Data['appkey']=="AckBriefingAndGetQuiz" && isset($Data['empStatus']) && isset($Data['isAcknowledge']) && isset($Data['EmployeeID']) && !empty($Data['EmployeeID']) && isset($Data['fromdate']) && !empty($Data['fromdate'])  && isset($Data['cmId']) && !empty($Data['cmId'])  && isset($Data['bId']) && !empty($Data['bId']) && isset($Data['ViewFor']) && !empty($Data['ViewFor']) && isset($Data['isQuiz']) && !empty($Data['isQuiz']))
{
			
			$isAcknowledgeNeeded = $Data['isAcknowledge'];
			$FromDateGot = $Data['fromdate'];
			$EmpStatus = $Data['empStatus'];
			$CmId = $Data['cmId'];
			$BriefingId = $Data['bId'];
			$ViewFor = $Data['ViewFor'];
			$employeeId = $Data['EmployeeID'];
			$isQuizAvail = $Data['isQuiz'];
			
			
			
			
			//////////////////////////////////////////////ACKNOWLEDGE PART /////////////////////////////////////////////////////////
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($isAcknowledgeNeeded == 1){
				$fromDate="";
	
				$fromDate=	date('Y-m-d',strtotime($FromDateGot));
//				if($EmpStatus=="" && $fromDate!="" ){
				if( $fromDate!="" ){
					$date=date('Y-m-d');
					if($fromDate<=$date){
						
						$query_Add_update="call brf_MiniWholeEmployee('".$BriefingId."','".$CmId."','".$FromDateGot."','".$ViewFor."')";
						$myDB=new MysqliDb();
						$resultBy=$myDB->rawQuery($query_Add_update);
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;

					}
				}
				
				

				if($employeeId !="" && $BriefingId!=""){
					$myDB=new MysqliDb();
					$select=$myDB->rawQuery("Select id from brf_acknowledge where BriefingId='".$BriefingId."' and EmployeeID='".$employeeId."' ");
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if($rowCount<1){
						$sql="insert into brf_acknowledge set BriefingId='".$BriefingId."',EmployeeID='".$employeeId."' ";
						$myDB=new MysqliDb();
						$result=$myDB->rawQuery($sql);
						$mysql_error = $myDB->getLastError();
						$rowCount = $myDB->count;
							if($rowCount>0){
								//echo 'yes';
							}
						
					
						}
					}
				}
			
			/////////////////////////////////////////////////////Get Quiz Data/////////////////////////////////////////////////////////////
		
				
				
				if($isQuizAvail == 'YES'){
					
					$select_question="select * from brf_question where BriefingID='".$BriefingId."'";
					$myDB=new MysqliDb();
					$Qresult=$myDB->rawQuery($select_question);
					$mysql_error = $myDB->getLastError();
					if(empty($mysql_error) && count($Qresult)){
						$response['status']=1;
			    		$response['msg']='SuccessFully Acknowledge.';
			    		$response['ackMsg']='SuccessFully Acknowledged.';
			    		$response['quizMsg']='Quiz Data Found.';
			    		$response['quizStatus']=1;
			    		$response['quizData']=$Qresult;
					}else{
						$response['status']=1;
			    		$response['msg']='SuccessFully Acknowledge.';
			    		$response['ackMsg']='SuccessFully Acknowledged.';
			    		$response['quizMsg']='No Quiz Data Found.';
			    		$response['quizStatus']=0;
					}	
					
				}else{
					$response['status']=1;
		    		$response['msg']='SuccessFully Acknowledge.';
		    		$response['ackMsg']='SuccessFully Acknowledged.';
				}
				
			
			
	
        }else{
        	$response['data']='Not Found';
        	$response['status']=0;
		    $response['msg']='Bad Request';
        }
  
 echo json_encode($response);       

?>