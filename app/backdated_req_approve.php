<?php  
// Server Config file  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$Data=array();
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$alert_msg['msg']='';
$myDB= new MysqliDb();
ini_set('display_errors', '1');
if(isset($Data['Exception']) && $Data['ExpceptionID']!="" ){
					$cm_id=$Data['cm_id'];
					$ExpID =$Data['ExpceptionID'];
					$EmployeeID = $Data['EmployeeID'];
					$Exception = $Data['Exception'];
					$comment = $Data['comment'];
					$DateFrom = $Data['DateFrom'];
					$DateTo = $Data['DateTo'];
					$ShiftIn = $Data['ShiftIn'];
					$ShiftOut = $Data['ShiftOut'];
					$IssueType = $Data['IssueType'];
					$CurrAtt = $Data['Current_Att'];
					$UpdateAtt = $Data['Update_Att'];
					$LeaveType = $Data['LeaveType'];
					$MngrStatus = $Data['MgrStatus'];
					
					$level = $Data['level'];
					$level1 = $Data['level1'];
					$level2 = $Data['level2'];
					
					$ApproveStatus = 'Pending';
					if($level==1)
					{
						$ApproveStatus = $Data['MgrStatus'];						
					}
					else
					{
						if($level1=="YES")
						{
							$ApproveStatus = $Data['MgrStatus'];
							$MngrStatus = "Pending";
						}
						else
						{
							$MngrStatus = $Data['MgrStatus'];	
							$ApproveStatus = "";
						}
					}
										
					$approvedBy =$Data['approvedBy'];// which one want to approve that Id
					$DateModified = date('Y-m-d H:i:s');
					
					
					
	$sqlInsertException = 'call Aap_UpdateRequestDetailsManager("'.$ExpID.'","'.$DateFrom.'","'.$DateTo.'","'.$Exception.'","'.$comment.'","'.$MngrStatus.'","'.$ApproveStatus.'","'.$approvedBy.'","'.$DateModified.'","'.$IssueType.'","'.$CurrAtt.'","'.$UpdateAtt.'","'.$ShiftIn.'","'.$ShiftOut.'","'.$LeaveType.'","App")';
					$flag = $myDB->rawQuery($sqlInsertException);
					$error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if($rowCount>0)
					{
						if ($MngrStatus == "Approve")
						{
							 if ($Exception == "Back Dated Leave")
					            {
					                $EmployeeID = $EmployeeID;
					                $DateFrom1 = $DateFrom;
					                $DateTo1 = $DateTo;
					                
					                $ReasonofLeave = $Exception;
					                $myDB = new MysqliDb(); 
									$flag = $myDB->query('call totalday_excWO("'.$EmployeeID.'","'.$DateFrom.'","'.$DateTo.'")');
			            			
			            			if(count($flag) > 0)
						            {
						            	$TotalLeaves1 = $flag[0]['day'];
						            }
					                $CreatedBy = $EmployeeID;
									$query = 'INSERT INTO leavehistry(EmployeeID,DateFrom,DateTo,ReasonofLeave,LeaveOnDate,TotalLeaves,EmployeeComment,MngrStatusID,HRStatusID,ManagerComment,HRComents,CreatedBy,HOD,LeaveType) VALUES ("'.$EmployeeID.'","'.$DateFrom1.'","'.$DateTo1.'","'.$ReasonofLeave.'","'.date('Y-m-d').'","'.$TotalLeaves1.'","Approve","Approve","Approve","NA","","'.$CreatedBy.'","'.$approvedBy.'","'.$LeaveType.'")';
					                $myDB = new MysqliDb(); 
				                    $flag = $myDB->rawQuery($query);
				                    $error = $myDB->getLastError();
				                    $rowCount = $myDB->count;
					                if ($rowCount>0)
						            {
							            if((int)date('d',time()) > 5)
							            {
												if(date('m',strtotime($DateFrom)) == date('m',strtotime(date('Y-m-d',time()))) -1)
												{
													$myDB = new MysqliDb(); 
							            			$flag = $myDB->query('SELECT * FROM paid_leave_all where EmployeeID="'.$EmployeeID.'" and Month(date_paid)=Month("'.date('Y-m-d',time()).'") and Year(date_paid)=Year("'.date('Y-m-d',time()).'") order by id limit 1;');
							            			
							            			if(count($flag) > 0)
										            {
														$sum_release = $flag[0]['paidleave'];
														$rem_leave = $sum_release - $TotalLeaves1;
														if($rem_leave < 0)
														{
															$rem_leave = 0;
														}
														
														$myDB = new MysqliDb(); 
							            				$flag = $myDB->query('call save_paidleave("'.$rem_leave.'","'.date('Y-m-d',strtotime(date('Y-m-01',time()))).'","'.$EmployeeID.'")');
							            				 $error = $myDB->getLastError();
							            				 if($error!="")
							            				 {
							            				 	$myDB = new MysqliDb();   
															$logQuery="INSERT INTO exception_log_error set exp_id='".$ExpID."' ,exp_error='".$Exception." not calculate by App ";
															$myDB->rawQuery($logQuery);
															
							            				 }
							            					
													}
												}
							                }
										$result['status']=1;
										$result['msg']="Request Approve.";
										include('calc_range_api.php');
					                }
									else
									{
										$myDB = new MysqliDb();   
										$logQuery="INSERT INTO exception_log_error set exp_id='".$ExpID."' ,exp_error='back dated leave is not updated by App'";
										$myDB->rawQuery($logQuery);
										$result['status']=0;
										$result['msg']="Request not updated";
										
									}
									
					            }else{
					            	$result['status']=0;
									$result['msg']="Exception not valid";
					            }
					            
					}else{
						$result['status']=1;
						$result['msg']="Exception updated but Back Dated Leave not updated";	
						
					}
		}else{
			$result['status']=0;
			$result['msg']="Exception not updated";
			
		}  
}else{
	$result['status']=0;
	$result['msg']="Exception should not be blank";
}
echo json_encode($result);
?>