<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
date_default_timezone_set('Asia/Kolkata');

$myDB=new MysqliDb();
$loc=$myDB->query('call get_locationID()');
if(count($loc) > 0 && $loc)
{  
	foreach($loc as $key=>$val)
	{
		$automail = '';$location='';
		if($val['id']=="1")
		{
			$automail = 'AutoEmailNCNS_Noida';
			$location = "1,2";
		}
		else if($val['id']=="3")
		{
			$automail = 'AutoEmailNCNS_Meerut';
			$location = "3";
		}
		else if($val['id']=="4")
		{
			$automail = 'AutoEmailNCNS_Bareilly';
			$location = "4";
		}
		else if($val['id']=="5")
		{
			$automail = 'AutoEmailNCNS_Vadodara';
			$location = "5";
		}
		else if($val['id']=="6")
		{
			$automail = 'AutoEmailNCNS_Mangalore';
			$location = "6";
		}
		else if($val['id']=="7")
		{
			$automail = 'AutoEmailNCNS_Bangalore';
			$location = "7";
		}
		else if($val['id']=="8")
		{
			$automail = 'AutoEmailNCNS_Bangalore_Flipkart';
			$location = "8";
		}
		
		settimestamp($automail,'Strat');
				$columnNames =array("EmployeeID","Employee Name","Total","DOJ","Designating","Client","Process","Sub Process","Supervisor");
				$myDB=new MysqliDb();
				$queryncns = "";
				$current_date_ncnc = date('Y-m-d',strtotime("yesterday"));
				$date_4day_prev =  date('Y-m-d',strtotime($current_date_ncnc." -3 days"));
				$test='3';
				if(date('Y-m',strtotime($current_date_ncnc)) == date('Y-m',strtotime($date_4day_prev)))
				{
					$dt_ncns_1  = "D".date('j',strtotime($date_4day_prev));
					$dt_ncns_2  = "D".date('j',strtotime($date_4day_prev." +1 days"));
					$dt_ncns_3  = "D".date('j',strtotime($date_4day_prev." +2 days"));
					$dt_ncns_4  = "D".date('j',strtotime($current_date_ncnc));
					echo $queryncns =  "select distinct w.EmployeeID,w.EmployeeName,w.DOJ,w.Designation,w.client_name as clientname ,w.process,w.sub_process,p.EmployeeName as AH   from calc_atnd_master t left join (SELECT EmployeeID FROM ncns_cases where status = 0 ) ncns on ncns.EmployeeID=t.EmployeeID inner join	vw_active_emp_detail w on w.EmployeeID=t.EmployeeID left  join personal_details p on w.account_head = p.EmployeeID where ('A' in ($dt_ncns_1,$dt_ncns_2,$dt_ncns_3,$dt_ncns_4) and year='".date('Y',strtotime($date_4day_prev))."' and Month='".date('n',strtotime($date_4day_prev))."') and p.location in (".$location.")";
					
					//echo $queryncns."abc";
					
				}
				else
				{
					$dt_ncns_endprev = date('Y-m-t',strtotime($date_4day_prev));
					$dt_ncns_firstcur = date('Y-m-01',strtotime($current_date_ncnc));
					
					$date_f_loop_ncns = $date_4day_prev;
					$prev_col_string = '';
					while (strtotime($date_f_loop_ncns) <= strtotime($dt_ncns_endprev)) {
		                $prev_col_string[] = "D".date('j',strtotime($date_f_loop_ncns));
		                $date_f_loop_ncns = date ("Y-m-d", strtotime("+1 day", strtotime($date_f_loop_ncns)));
					}
					
					$date_l_loop_ncns = $dt_ncns_firstcur;
					$cur_col_string = '';
					while (strtotime($date_l_loop_ncns) <= strtotime($current_date_ncnc)) {
		                $cur_col_string[] = "D".date('j',strtotime($date_l_loop_ncns));
		                $date_l_loop_ncns = date ("Y-m-d", strtotime("+1 day", strtotime($date_l_loop_ncns)));
					}
					
					$queryncns =  "select distinct w.EmployeeID,w.EmployeeName,w.DOJ,w.Designation,w.client_name as clientname ,w.process,w.sub_process,p.EmployeeName as AH   from calc_atnd_master t left join (SELECT EmployeeID FROM ncns_cases where status = 0 ) ncns on ncns.EmployeeID=t.EmployeeID inner join	vw_active_emp_detail w on w.EmployeeID=t.EmployeeID left  join personal_details p on w.account_head = p.EmployeeID where (('A' in (".implode(",",$prev_col_string).") and year='".date('Y',strtotime($date_4day_prev))."' and Month='".date('n',strtotime($date_4day_prev))."') or ('A' in (".implode(",",$cur_col_string).") and year='".date('Y',strtotime($current_date_ncnc))."' and Month='".date('n',strtotime($current_date_ncnc))."'))";
				}
			$chk_task=$myDB->query($queryncns);
		
			$my_error= $myDB->getLastError();	
			
			$rg_status='';
			if(count($chk_task) > 0)
			{  
			$fileName = 'automail_NCNS_rpt.csv';
			if($val['id']=="1")
			{
				$fileName = 'automail_NCNS_rpt_Noida.csv';
				
			}
			else if($val['id']=="3")
			{
				$fileName = 'automail_NCNS_rpt_Meerut.csv';
			}
			else if($val['id']=="4")
			{
				$fileName = 'automail_NCNS_rpt_Bareilly.csv';
			}
			else if($val['id']=="5")
			{
				$fileName = 'automail_NCNS_rpt_Vadodara.csv';
			}
			else if($val['id']=="6")
			{
				$fileName = 'automail_NCNS_rpt_Mangalore.csv';
			}
			else if($val['id']=="7")
			{
				$fileName = 'automail_NCNS_rpt_Bangalore.csv';
			}
			else if($val['id']=="8")
			{
				$fileName = 'automail_NCNS_rpt_Bangalore_Flipkart.csv';
			}
				
				$fp = fopen($fileName, 'w');
    			fputcsv($fp, $columnNames);
    			$i=1;
				foreach($chk_task as $key=>$value)
				{
					if(true)
					{
						$myDB =new MysqliDb();
						
						$result_all = $myDB->query('select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t1 where EmployeeID = "'.$value['EmployeeID'].'" and  month='.date('m',time()).' and Year ='.date('Y',time()).' union all select EmployeeID,month,year,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master t2 where EmployeeID = "'.$value['EmployeeID'].'" and  month='.date('m',strtotime("-1 month ".date('Y-m-01',time()))).' and Year ='.date('Y',strtotime("-1 month ".date('Y-m-01',time()))));
						
						if(count($result_all) == 2)
						{
							$result_prev[0] = $result_all[1];						
							$result_cur[0] = $result_all[0];
						}
						else
						{
							if((intval(date('Y',time())) ==  intval($result_all[0]['year'])) && (intval(date('m',time())) ==  intval($result_all[0]['month'])))
							{
								
								$result_prev[0] = array();						
								$result_cur[0] = $result_all[0];
							}
							else
							{
								$result_prev[0] = $result_all[0];						
								$result_cur[0] = array();
							}
						}
						
						$count_prev = $count_abc =  0;
						$a_counter = 0;
						$inactiveThat = 0;
						$counter_check = 0;
						if(count($result_prev) > 0)
						{
							$begin  =  new DateTime(date('Y-m-01',strtotime("-1 month ".date('Y-m-01',time()))));
							$end =  new DateTime(date('Y-m-t',strtotime("-1 month ".date('Y-m-01',time()))));
							
					 		for($i = $begin; $begin <= $end; $i->modify('+1 day'))
				            {
				            	
	                            $col = "D".intval($i->format('d'));														
	                            $val ='';
	                            if(isset($result_prev[0][$col])){
									$val=$result_prev[0][$col];
								}
	                            if($i->format('Y-m-d') < date('Y-m-d',time()))
								{
									$val_calc = $val;
									
									if( intval($i->format('d')) == 1)
									{
										$val_calc_prev = '-';
									}
									else
									if(isset($result_prev[0]['D'.(intval($i->format('d')) - 1)]))
									{
										$val_calc_prev = $result_prev[0]['D'.(intval($i->format('d')) - 1)];
									}
									
									if($i->format('Y-m-d') == $i->format('Y-m-t'))
									{
										$val_calc_next = '-';
									}
									else
									if(isset($result_prev[0]['D'.(intval($i->format('d')) + 1)]))
									{
										$val_calc_next = $result_prev[0]['D'.(intval($i->format('d')) + 1)];
									}
									
									
									if($val_calc == 'A' || (($val_calc == 'WO' || $val_calc == 'WONA') && ($val_calc_next == 'A' || $val_calc_prev == 'A')))
									{
										if($inactiveThat > 0 || $val_calc == 'A')
										{
											$count_prev ++;
										}
										$inactiveThat++;
										if($val_calc == 'A' )
										{
											$counter_check++;
										}
										
									}
									elseif($val_calc == '-' || empty($val_calc) || $val_calc == 'HO')
									{
										
									}
									else
									{
										$count_prev = 0;
										$inactiveThat = 0;
										$counter_check = 0;
									}
									
									if($val_calc == 'A')
									{
										$a_counter++;
									}
									else
									{
										$a_counter = 0;
									}
								}	                           
				            }
				            
							
						}
						if(count($result_cur) > 0)						
						{
							for($j = 1;$j <= 31;$j++)
							{
								if($j < intval(date('d',time())))
								{
									$val_calc = $result_cur[0]['D'.$j];
									
									if($j < 31 )
									{
										$val_calc_next = $result_cur[0]['D'.($j + 1)];	
									}
									else
									{
										$val_calc_next = '-';
									}
									
									if($j > 1 )
									{
										$val_calc_prev = $result_cur[0]['D'.($j - 1)];	
									}
									else
									{
										$val_calc_prev = '-';
									}							
									
									
									if($val_calc == 'A' || (($val_calc == 'WO' || $val_calc == 'WONA') && ($val_calc_next == 'A' || $val_calc_prev == 'A')))
									{
										
										if($inactiveThat > 0 || $val_calc == 'A')
										{
											$count_abc ++;
										}
										if($val_calc == 'A' )
										{
											$counter_check++;
										}
										$inactiveThat++;
										
									}
									elseif($val_calc == '-' || empty($val_calc) || $val_calc == 'HO')
									{
										
									}
									else
									{
										$count_abc = 0;
										$count_prev = 0;
										$inactiveThat = 0;
										$counter_check = 0;
									}
									
									if($val_calc == 'A')
									{
										$a_counter++;
									}
									else
									{
										$a_counter = 0;
									}
								}
								
							}
						}
						
						$final_counter  = $count_abc + $count_prev;
						
						
						if($counter_check >= 3 || $a_counter >= 3)
						{
							
							$row1=array($value['EmployeeID'],$value['EmployeeName'],$final_counter,$value['DOJ'],$value['Designation'],$value['clientname'],$value['process'],$value['sub_process'],$value['AH']);
							fputcsv($fp, $row1);
						}
						
						
					}
				}
					
			}
			else
			{
			echo	$table="No Data Found  ...";
				
			}
settimestamp($automail,'END');
		//echo "<br>";
		$myDB=new MysqliDb();
		$pagename='automail_NCNS_rpt';
		echo "select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
			$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'");
		echo "filesize=".$file_size = filesize($fileName);
		//echo "<br>";
		if(file_exists($fileName) && !empty($fileName)){
		$count= 0;
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = EMAIL_HOST; 
		$mail->SMTPAuth = EMAIL_AUTH;
		$mail->Username = EMAIL_USER;   
		$mail->Password = EMAIL_PASS;                        
		$mail->SMTPSecure = EMAIL_SMTPSecure;
		$mail->Port = EMAIL_PORT; 
		$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
	
		if(count($select_email_array) > 0)		
		{
			
			foreach($select_email_array as $Key=>$val)
			{				
			   $email_address=$val['email_address'];
				if($email_address!=""){
					$mail->AddAddress($email_address);
				}
				 $cc_email=$val['ccemail'];
				if($cc_email!=""){
					$mail->addCC($cc_email);
				}
			}
		}
		
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
		
		$mail->Subject = 'EMS '.$EMS_CenterName.', NCNS Report  ['.date('d M,Y',time()).']';
		$mail->isHTML(true);
	
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the NCNS Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
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