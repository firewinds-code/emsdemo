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
if(isset($Data['Exception']) && $Data['ExpceptionID']!="" &&  ($Data['Exception']== "Roster Change" || $Data['Exception'] == "Shift Change" ) && trim($Data['ShiftIn'])!="" && trim($Data['ShiftOut'])!="" )
{
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
							 if ($Exception == "Roster Change" || $Exception == "Shift Change")
					        {
					        	$count=0;
					                $shift = $ShiftIn."-".$ShiftOut;
									$dt1 = date($DateFrom);
									$dt2 = date($DateTo);
					                if ($dt1 == $dt2)
					                {
					                	$month = intval(date('m',strtotime($dt1)));
					                	$year =intval(date('Y',strtotime($dt1)));
					                	$day  =intval(date('d',strtotime($dt1)));
					                    $query = "call sp_UpdateRoaster('".$EmployeeID."','".$month."','".$year."','".$day."','".$shift."')";
					                    
					                    $myDB = new MysqliDb(); 
					                    $flag = $myDB->query($query);
					                    $error = $myDB->getLastError();
					                    $rowCount = $myDB->count;
					                    if ($rowCount>0)
					                    {
					                        $count++;
					                    }else{
											$myDB = new MysqliDb();   
											$logQuery="INSERT INTO exception_log_error set exp_id='".$ExpID."' ,exp_error='".$Exception." one day is not updated by app'";
											$myDB->rawQuery($logQuery);
										}
					                }
					                else
					                {
										$begin = new DateTime($DateFrom);
										$end   = new DateTime($DateTo);
										
								 		for($i = $begin; $begin <= $end; $i->modify('+1 day'))
							            {
				                            $month = intval($i->format('m'));
					                		$year = intval($i->format('Y'));
					                		$day  =  intval($i->format('d'));													
				                            $val =$shift;					                           
				                           	$Time_inout = explode('-',$val);
				                           	$query = "call sp_UpdateRoaster('".$EmployeeID."','".$month."','".$year."','".$day."','".$shift."')";
											$myDB = new MysqliDb(); 
						                    $flag = $myDB->query($query);
						                    $error = $myDB->getLastError();
						                    $rowCount = $myDB->count;
						                    if ($rowCount>0)
						                    {
						                        $count++;
						                    }else{
												$myDB = new MysqliDb();   
												$logQuery="INSERT INTO exception_log_error set exp_id='".$ExpID."' ,exp_error='".$Exception." is not updated by app'";
												$myDB->rawQuery($logQuery);
											}
					                		
							            }	
							            unset($begin);
							            unset($end);
					                }

					                if ($count == 0)
					                    $count++;
					                	$result['status']=1;
										$result['msg']="Request Approved";
										include('calc_range_api.php');

					    }else{
					    	$result['status']=0;
							$result['msg']="Not valid Exception";
					    }
					            
				}else{
					$result['status']=1;
					$result['msg']="Exception updated but Roster/shift not updated";
					
				}
		}else{
			$result['status']=0;
			$result['msg']="Exception not updated";
		}  
}else{
	$result['status']=0;
	$result['msg']="Exception should not be blank";
}
print_r(json_encode($result));
?>