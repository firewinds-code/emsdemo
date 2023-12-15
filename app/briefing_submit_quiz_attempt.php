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

	
if(isset($Data['appkey']) && $Data['appkey']=="submitBriefingQuiz" && isset($Data['EmployeeID']) && !empty($Data['EmployeeID'])  && isset($Data['bId']) && !empty($Data['bId']) && isset($Data['answerList']) && !empty($Data['answerList'])&& isset($Data['questIdList']) && !empty($Data['questIdList']))
{
			
			
	 $BriefingId = $Data['bId'];
			
	 $employeeId = $Data['EmployeeID'];

	 $answerListString = str_replace("[","",$Data['answerList']);
	 $answerListString = str_replace("]","",$answerListString);
	 $answerListString = str_replace(" ","",$answerListString);
	 $QuestionIdListString =str_replace("[","",$Data['questIdList']) ;
	 $QuestionIdListString = str_replace("]","",$QuestionIdListString);
	 $QuestionIdListString = str_replace(" ","",$QuestionIdListString);
	 $answerList =explode(",",$answerListString);
	 $QuestionIdList = explode(",",$QuestionIdListString);
	 
	 	$myDB=new MysqliDb();
	 	
	 	if(count($answerList) > 0){
	 		
	 	
	
    	for($i = 0 ; $i < count($answerList) ; $i++)
	    {	
			
			$sql="Insert into brf_quiz_attempted set  AttemptedDate='".date('Y-m-d H:i:s')."' , EmployeeID='".$employeeId."', BriefingId='".$BriefingId."' , QuestionId='".$QuestionIdList[$i]."',Answer='".$answerList[$i]."'";
						$result=$myDB->rawQuery($sql);
		
		}
		
		////////////////////////
			$response['status']=1;
	   		$response['msg']='Quiz Submitted Successfully.';
		
		}else{
	 		$response['status']=0;
	   		$response['msg']='Invalid Data';
	 	}
		
		
		
	
	
    }else{
    	
    	$response['status']=0;
	    $response['msg']='Bad Request';
    }
  
 echo json_encode($response);       

?>