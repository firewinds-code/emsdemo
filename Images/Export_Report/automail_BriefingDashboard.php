<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
error_reporting(1);
ini_set('display_errors', 0);
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}

$myDB=new MysqliDb();
$loc=$myDB->query('call get_locationID()');
if(count($loc) > 0 && $loc)
{  
	foreach($loc as $key=>$val)
	{
		$automail = '';$location='';
		if($val['id']=="1")
		{
			$automail = 'AutoEmail_Briefingdashboard_Noida';
			$location="1,2";
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmail_Briefingdashboard_Meerut';
			$location = "3";
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmail_Briefingdashboard_Bareilly';
			$location = "4";
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmail_Briefingdashboard_Vadodara';
			$location = "5";
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmail_Briefingdashboard_Mangalore';
			$location = "6";
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmail_Briefingdashboard_Bangalore';
			$location = "7";
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmail_Briefingdashboard_Bangalore_Flipkart';
			$location = "8";
		}
		
		settimestamp($automail,'Strat');
			$myDB=new MysqliDb ();
			$today=date('Y-m-d');
			$todate=date('Y-m-d',strtotime($today.'- 1 day'));
			$PreDay = date('Y-m-d',strtotime($today.'- 5 day')); 
			//$PreDay = date('Y-m-d',strtotime('2017-08-01'.'- 1 day')); 
				 $sqlConnect="SELECT distinct  h.EmployeeID ,h.BriefingId,h.fromdate, h.EmployeeName,h.designation,h.status,h.ReportTo,h.Qa_ops,h.clientname,h.Process,h.sub_process,N.heading,N.cm_id,N.CreatedBy,N.CreatedOn,N.id,N.view_for,N.quiz  FROM brf_briefingfor h INNER JOIN brf_briefing N ON N.id=h.BriefingId inner join personal_details pd on h.EmployeeID=pd.EmployeeID left outer join bioinout a on a.EmpID=h.EmployeeID and cast(h.fromdate as date)=cast(DateOn as date) where cast(h.fromdate as date) between '".$PreDay."' and '".$todate."'  and   cast(DateOn as date)  between '".$PreDay."' and '".$todate."'  and h.status in(4,5,6) and pd.location in (".$location.")";
					$data=0;
					$myDB=new MysqliDb ();
					$result=$myDB->query($sqlConnect);
					$error=$myDB->getLastError();
					$fileName = '';
					if($val['id']=="1")
					{
						$fileName = 'automail_Briefing_Dashboard_Noida.csv';
						
					}
					else if($val['id']=="3")
					{
						$fileName = 'automail_Briefing_Dashboard_Meerut.csv';
					}
					else if($val['id']=="4")
					{
						$fileName = 'automail_Briefing_Dashboard_Bareilly.csv';
					}
					else if($val['id']=="5")
					{
						$fileName = 'automail_Briefing_Dashboard_Vadodara.csv';
					}
					else if($val['id']=="6")
					{
						$fileName = 'automail_Briefing_Dashboard_Mangalore.csv';
					}
					else if($val['id']=="7")
					{
						$fileName = 'automail_Briefing_Dashboard_Bangalore.csv';
					}
					else if($val['id']=="8")
					{
						$fileName = 'automail_Briefing_Dashboard_Bangalore_Flipkart.csv';
					}
					
					if(empty($error)){
						$columnNames = array();
						$i=1;
						
						$fp = fopen($fileName, 'w');
						$columnNames =array("EmployeeID","EmployeeName","Designation","Report To(OPS)","Report To(Q.A)","Client","Process","Sub Process","EMP Status","View For","Date","Attendance","Applicable","Attend Briefing","Briefing Name","Briefing Date","Briefing Response Date","Quiz Applicable","Quiz Status","Quiz Date","Quiz Response Date","Quiz Score","Correct Quiz","total Quiz",);
					fputcsv($fp, $columnNames);
					       $quizStatus="";
					        foreach($result as $key=>$value){
					        	$AttendB="";
					        	$applicable="NA";
					        	$quizStatus="No";
								$bf_id="";
								$QuizDate="";
								$AttemptedDate="";
								$correctAverage="";
								$AcknowledgeDate="";
								$attendence="NA";
								$quizApplicable='NA';
								$empStatus='NA';
								$empid="";
								$fromDate=$value['fromdate'];
								$fromdate=date('Y-m-d',strtotime($value['fromdate']));
								if($value['BriefingId']!=""){
									$bf_id=$value['BriefingId'];
									$empid=$value['EmployeeID'];	
								}
								$date=date('j',strtotime($fromDate));
								$month=date('m',strtotime($fromDate));
								$year=date('Y',strtotime($fromDate));
		 			
								$att_query="select  D".$date."  from calc_atnd_master  where  month='".$month."' and Year ='".$year."' and EmployeeID='".$value['EmployeeID']."'  and ( D".$date." like 'P%'  || D".$date."='H' || D".$date." ='HWP' )";
											
								$myDB=new MysqliDb ();
								//$att_array=mysql_query($att_query);
								$att_array=$myDB->query($att_query);
					            $error=$myDB->getLastError();
								if($myDB->count > 0)
								{
									$data=1;
									//$data_array=mysql_fetch_array($att_array);
									$attendence=$att_array[0]['D'.$date];
									
									
									$ackdate=' ';
									$ack_query="select AcknowledgeDate from brf_acknowledge where  EmployeeID='".$empid."'  and BriefingId='".$bf_id."'";
									$myDB=new MysqliDb ();
									$ackdata_array=$myDB->query($ack_query);
									if($myDB->count > 0)
									{
										//$ackdata_array=mysql_fetch_array($ack_array);
										$AcknowledgeDate=$ackdata_array[0]['AcknowledgeDate'];
										$ackdate=date('Y-m-d',strtotime($ackdata_array[0]['AcknowledgeDate']));
										if($fromdate==$ackdate){
											$AttendB='Yes';
										}else{
											$AttendB='No';
										}
										
									}else{
										$AttendB='No';
										$AcknowledgeDate="";
									}
									
									if(($value['status']=='6' || $value['status']=='5' || $value['status']=='4' ) && ($value['designation']=='CSA' || $value['designation']=='Senior CSA'))
									{
										if($value['N']['view_for']=='All' || $value['N']['view_for']=='onFloor' || $value['N']['view_for']=='CSA' )
										{
											$applicable="Yes";
											//$quizApplicable='Yes';	
										}else{
											$applicable="NA";
											$quizApplicable='NA';
										}		
									}
									elseif(($value['status']=='6' || $value['status']=='5' || $value['status']=='4' ) && ($value['designation']!='CSA' || $value['designation']!='Senior CSA'))
									{
										if($value['N']['view_for']=='All' || $value['N']['view_for']=='onFloor' ||  $value['N']['view_for']=='Support' )
										{			
											$applicable="Yes";	
										}else{
											$applicable="NA";
											$quizApplicable='NA';
										}	
									}
								  	if($value['status']=='6'){
										$empStatus='On Floor';
									}else
									if($value['status']=='4' || $value['status']=='5'){
										$empStatus='OJT';
									}
									else{
										$empStatus='NA';
										$applicable="NA";
										$quizApplicable='NA';
									}
								$AttemptedDate="";	
								$quizAtemptedDate="";	
								$quizStatus='No';	
								$bf_id=$value['N']['id'];
								$empid=$value['EmployeeID'];
								$select_question="select * from brf_question where BriefingID='".$bf_id."'";
								$myDB=new MysqliDb ();
								$Qresult=$myDB->query($select_question);
								$error=$myDB->getLastError();
								$correct="";
								$c="";
								$correctAverage="";
								if(count($Qresult) > 0 && $Qresult)
								{
									
									$tq = $Qresult->count;
									
									$quizApplicable='Yes';
									$QuizDate=$value['fromdate'];
									$myDB=new MysqliDb ();	
									if($bf_id!="" && $empid!="")
									{
										$AttemptedDate="";	
										$quizAtemptedDate="";
										
										$select_attempted=$myDB->query("SELECT b.Answer,a.BriefingID,a.QuestionID ,b.AttemptedDate,a.Answer AS CorrectAns,b.EmployeeID FROM brf_question a INNER JOIN brf_quiz_attempted b ON a.QuestionID=b.QuestionId where a.BriefingId='".$bf_id."' and b.EmployeeId='".$empid."'");
							        	
							        	if(count($select_attempted) > 0 && $select_attempted)
							        	{
							        		
							        		$AttemptedDate="";
											$c=0;	
						        			$user_ans="";
						        			$ans="";
						        			$correct=0;
						        			$InCorrect=0;
						        			
											for($l=1;$l<=$tq;$l++)
											{ 
												//$qarray=mysql_fetch_array($select_attempted);
												$user_ans=strtoupper($select_attempted[0]['Answer']);
												$ans=strtoupper($select_attempted[0]['CorrectAns']);
											
												
												if($user_ans==$ans){
													$correct++;
												}else{	
													$InCorrect++;
												}
												$c++;
												$AttemptedDate=$select_attempted[0]['AttemptedDate'];
								        		$quizAtemptedDate=date('Y-m-d',strtotime($AttemptedDate));
								        		if($quizAtemptedDate==$fromdate){
													
													$quizStatus="Yes";	
												}else{
													
													$quizStatus="No";	
												}	
											}
											
											if($tq>0){
												$correctAverage=$correct.' / '.$c;	
											}else{
												$correctAverage="";
											}
										}else
										{
											$quizStatus="No";
											$QuizDate="";
											$AttemptedDate="";	
										}				
													
									}else{
										$AttemptedDate="";		
									}
								}else{
									$quizStatus="No";	
									$quizApplicable='NA';
									$QuizDate="";
							 	 	$correctAverage="";
							 	 	$AttemptedDate="";
								}			
								/* When Employee Status has been Changed then Following Condition will be applicable */		
								if($applicable=='NA'){
									$quizApplicable='NA';
									$AttendB='No';
									$quizStatus='No';
									
								}
								
								$row=array($value['EmployeeID'],$value['EmployeeName'],$value['designation'],$value['ReportTo'],$value['Qa_ops'],$value['clientname'],$value['Process'],$value['sub_process'],$empStatus,$value['N']['view_for'],date('Y-m-d',strtotime($value['fromdate'])),$attendence,$applicable,$AttendB,$value['N']['heading'],$value['fromdate'],$AcknowledgeDate,$quizApplicable,$quizStatus,$QuizDate,$AttemptedDate,$correctAverage,$correct,$c,);					
							 fputcsv($fp, $row);
							}	

				}if($data=='0'){
					echo "Data not found";
				}
			}
			else{
					echo "Data not found";
				}
	
	settimestamp($automail,'END');
			fclose($fp);	    
		echo "filesize".$file_size = filesize($fileName);		
	if(file_exists($fileName) && !empty($fileName)){	 
	
		$myDB=new MysqliDb ();
		$pagename='automail_BriefingDashboard';
		//echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
		$select_email_array=$myDB->rawQuery("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'");	
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = EMAIL_HOST; 
		$mail->SMTPAuth = EMAIL_AUTH;
		$mail->Username = EMAIL_USER;   
		$mail->Password = EMAIL_PASS;                        
		$mail->SMTPSecure = EMAIL_SMTPSecure;
		$mail->Port = EMAIL_PORT; 
		$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
		if(count($select_email_array)> 0)
		{
			
			foreach($select_email_array as $email_array){
			 	$email_address=$email_array['email_address'];
			
				if($email_address!=""){
					$mail->AddAddress($email_address);
				}
				$cc_email=$email_array['ccemail'];
				if($cc_email!=""){
					$mail->addCC($cc_email);
				}
			}
			
		}	
		//$mail->AddAddress('rinku.kumari@cogenteservices.in');
		$mail->AddAttachment($fileName);
		$EMS_CenterName='';
		if($val['id']=="1")
		{
			$EMS_CenterName = 'Noida';
			
		}
		else if($val['id']=="3")
		{
			$EMS_CenterName = 'Meerut';
		}
		else if($val['id']=="4")
		{
			$EMS_CenterName = 'Bareilly';
		}
		else if($val['id']=="5")
		{
			$EMS_CenterName = 'Vadodara';
		}
		else if($val['id']=="6")
		{
			$EMS_CenterName = 'Mangalore';
		}
		else if($val['id']=="7")
		{
			$EMS_CenterName = 'Bangalore';
		}
		else if($val['id']=="8")
		{
			$EMS_CenterName = 'Bangalore Flipkart';
		}
		
		$mail->Subject = 'EMS '.$EMS_CenterName.', Briefing-Dashboard Report ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Briefing-Dashboard Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
				
				$mail->Body = $pwd_;
				
				$mymsg = '';
				if(!$mail->send())
			 	{
			 		settimestamp($automail,'Email Not Sent');
			 		echo '.Mailer Error:'. $mail->ErrorInfo;
			  	} 
				else
				 {
				    settimestamp($automail,'Email Sent');
				    echo  '.Mail Send successfully.';
				 }
		}
			
			
	}
}		
		

		 ?>
		 	
