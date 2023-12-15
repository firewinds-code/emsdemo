<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$result=0;
if(isset($_REQUEST['bid'],$_REQUEST['empid'])){
	$myDB=new MysqliDb();
	$string="";
	if(isset($_REQUEST['ans']) && $_REQUEST['ans']!=""){
			if($_REQUEST['empid']!="" && $_REQUEST['bid']!="" ){
				$ans_array=explode(',',$_REQUEST['ans']);
				$qnum_array=explode(',',$_REQUEST['qnum']);
				
				$select=$myDB->rawQuery("Select id from brf_quiz_attempted where BriefingId='".$_REQUEST['bid']."' and EmployeeID='".$_REQUEST['empid']."' ");
				$mysql_error = $myDB->getLastError();
				$rowCount = $myDB->count;
				if($rowCount<1){
					for($i=1;$i<count($ans_array);$i++){
						 $sql="Insert into brf_quiz_attempted set  AttemptedDate='".date('Y-m-d H:i:s')."' , EmployeeID='".$_REQUEST['empid']."', BriefingId='".$_REQUEST['bid']."' , QuestionId='".$qnum_array[$i]."',Answer='".$ans_array[$i]."'";
						$result=$myDB->rawQuery($sql);
					}
				}
			}if($result){
				echo 'yes';
			}
	}
	
}

?>

