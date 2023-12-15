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

		if(isset($Data['appkey']) && $Data['appkey']=="getBriefingList" && isset($Data['status']) && !empty($Data['status'])&& isset($Data['desig']) && !empty($Data['desig'])&& isset($Data['EmployeeID']) && !empty($Data['EmployeeID']))
		{
			
			$status = $Data['status'];
			$designation = $Data['desig'];
			$employeeId = $Data['EmployeeID'];
			
			
			  $view_for_query="";
			  if($status ==6 || $status==5 || $status==4)
			  {
			  	 	$view_for_query=" and (a.view_for='All' or a.view_for='onFloor' or a.view_for='CSA' or a.view_for='Support' ) ";
			  	 	
				  	if($designation!='CSA' && $designation!='Senior CSA'){
				  		$view_for_query=" and (a.view_for='All' or a.view_for='onFloor' or a.view_for='Support' ) ";
					
					}
					if($designation=='CSA' || $designation=='Senior CSA'){
						$view_for_query=" and (a.view_for='All' or a.view_for='onFloor' or a.view_for='CSA' ) ";
						
					}
					
				}
				
			
					
				$myDB=new MysqliDb();
				$movedate="";
				$q1="select EmployeeID,updated_on,new_cm_id from tbl_client_toclient_move where EmployeeID='".$employeeId."' and  flag='FM' order by id desc";
				$clientmove_query=$myDB->rawQuery($q1);
				$client_update_date="";
				if(count($clientmove_query)>0)
				{
					foreach($clientmove_query as $clientmove_query_val)
					{
					$client_update_date=$clientmove_query_val['updated_on'];
					}
				}
				$myDB=new MysqliDb();
				$processmove_query=$myDB->rawQuery("select EmployeeID,updated_on,new_cm_id from tbl_oh_tooh_move where EmployeeID='".$employeeId."' and  flag='FM' order by id desc");
				$process_update_date="";
				if(count($processmove_query)>0){
					foreach($processmove_query as $processmove_query_val)
					{
					$process_update_date=$processmove_query_val['updated_on'];
					}
				}
			if($client_update_date!="" && $process_update_date!=""){
				if($client_update_date>=$process_update_date){
					$movedate=$client_update_date;
				}else{
					$movedate=$process_update_date;
				}
					
			}else if($client_update_date!=""){
				$movedate=$client_update_date;
			}elseif($process_update_date!=""){
				$movedate=$process_update_date;
			}
			$nmonth="";
			$addnew_query="";	
			$nyear="";	
			$brStatus='Pending';
			$dateLimit = date('Y-m-01',strtotime('-2 month')); 
			$addnew_query.=" and  cast(a.fromdate as date)>='".$dateLimit."' ";
			/*if(isset($_GET['nmonth'],$_GET['nyear']) && $_GET['nmonth']!="" &&  $_GET['nyear']!=""){
				$nmonth=($_GET['nmonth']-1);
				$nyear=$_GET['nyear'];
			 	$addnew_query.=" and MONTH(a.fromdate)= '".$_GET['nmonth']."'  and YEAR(a.fromdate)= '".$_GET['nyear']."' and  acc.id is not  null";
			 $brStatus='Acknowledged';
			}else{
				 $brStatus='Pending';
				 $addnew_query.=" and  cast(a.fromdate as date)>='".$dateLimit."' and acc.id is null";
			}*/
			$sqlConnect2 = "SELECT  a.fromdate,a.heading,a.id,a.remark1,a.remark2,a.remark3,a.quiz,a.uploaded_file,a.TotalQuestionNum,a.cm_id,a.view_for, a.emp_status,b.EmployeeID,b.EmployeeID ,b.clientname,b.Process,b.sub_process,	acc.id as ac_id,acc.EmployeeID as AGENTID , acc.AcknowledgeDate , attem.AttemptedDate FROM brf_briefing a INNER JOIN whole_details_peremp b ON a.cm_id=b.cm_id LEFT OUTER JOIN brf_acknowledge acc ON a.id=acc.BriefingId and b.EmployeeID=acc.EmployeeID LEFT OUTER JOIN brf_quiz_attempted attem ON a.id=attem.BriefingId and b.EmployeeID=attem.EmployeeID where b.EmployeeID='".$employeeId."' and a.EnableStatus=1 and a.fromdate<=now() $view_for_query ";
		
if($movedate!=""){
	$sqlConnect2 .= " and  a.fromdate>= '".$movedate."'";
}else{
  	$sqlConnect2 .= " and  cast(a.fromdate as date)>= cast(b.DOJ as date)";
}
//$sqlConnect2.=" $addnew_query  order by a.id desc ";
$sqlConnect2.=$addnew_query." and (acc.id is null OR acc.id is not null) and (attem.AttemptedDate is null OR acc.AcknowledgeDate is null) group by a.id  order by a.id desc ";

//echo $sqlConnect2;
					$myDB=new MysqliDb();
					$result2=$myDB->rawQuery($sqlConnect2);
					$error=$myDB->getLastError();
					$rowCount = $myDB->count;
					
	  $mysql_error= $myDB->getLastError();
	      if(empty($mysql_error) && count($result2) > 0 )
	         {
	         	
		        $response['status']=1;
		        $response['msg']='Data Got Successfully';
		        $response['data']= $result2;
	         }
	     else
	         {
	         	$response['data']='Not Found';
		        $response['status']=0;
		        $response['msg']='Data Not Found';

	         }
	
        }else{
        	$response['data']='Not Found';
        	$response['status']=0;
		    $response['msg']='Bad Request';
        }
  
 echo json_encode($response);       

?>