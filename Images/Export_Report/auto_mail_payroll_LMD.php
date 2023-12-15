<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
//require_once(LIB.'PHPExcel/IOFactory.php');
error_reporting(E_ALL);
ini_set('display_errors',1);

require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
$table="";
function getMax( $array,$key )
{
    $max = 0;
    foreach( $array as $k => $v )
    {
        $max = max( array( $max, $v[$key] ) );
    }
    return $max;
}
function getDatesFromRange($start, $end, $format = 'd') 
{
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) { 
        $array[] = intval($date->format($format)); 
    }
	sort($array);
    return $array;
}
function _daysInMonth($month=null,$year=null)
{
         
    if(null==($year))
        $year =  date("Y",time()); 

    if(null==($month))
        $month = date("m",time());
         
    return date('t',strtotime($year.'-'.$month.'-01'));
}
date_default_timezone_set('Asia/Kolkata');

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
			$automail = 'automail_payroll_lm_Noida';
			$location = "1,2";
		}
		else if($val['id']=="3")
		{
			$automail = 'automail_payroll_lm_Meerut';
			$location = "3";
		}
		else if($val['id']=="4")
		{
			$automail = 'automail_payroll_lm_Bareilly';
			$location = "4";
		}
		else if($val['id']=="5")
		{
			$automail = 'automail_payroll_lm_Vadodara';
			$location = "5";
		}
		else if($val['id']=="6")
		{
			$automail = 'automail_payroll_lm_Mangalore';
			$location = "6";
		}
		else if($val['id']=="7")
		{
			$automail = 'automail_payroll_lm_Bangalore';
			$location = "7";
		}
		else if($val['id']=="8")
		{
			$automail = 'automail_payroll_lm_Bangalore_Flipkart';
			$location = "8";
		}
		
		settimestamp($automail,'Strat');
		
		$columnNames =array(" EmployeeID"," EmployeeName"," Salary Month"," Date Of Joining"," Date Of Deployment"," Calculated Deployment"," Designation"," Client"," Process"," Sub Process"," Emp Type"," Payroll Type"," Roster Type "," GENDER"," Father Name"," Mother Name"," Date Of Birth"," Bank Name"," Bank Account Number"," Name as per Bank"," IFSC Code"," Emp Status"," Total Floor Days"," CTC"," TK Home"," Leave Opening Balance"," P"," A"," L"," H"," LWP"," HWP"," WO"," LANA"," WONA"," Total Leaves"," Leave Adjusted"," Pay Days"," CO Paid"," CO Less"," Total Pay Days"," Attendnace Inct"," Split Inct"," OT Inct"," Night-Mor Inct"," Ref Inct"," Lylty-PLI Bonus"," Trainig Stipend"," Client Incentive"," Arrears"," Other Incentive"," Other Narration"," Total Add"," Asset Damage Deduction"," Card Damage Deduction"," Guest House Rent"," TDS"," Recovery Day"," Recovery Amount"," Other Less"," Other Narration Less"," Total Less"," Salary"," Payable Salary"," By Bank"," By Cash"," Basic"," Payable Basic"," EMP PF 12"," EMPR PF 3_67"," EMPR PF 8_33"," EMPR PF 1_36"," Gross"," Payable Gross"," ESIC 1_75"," ESIC 4_75"," Resignation Date"," Last Working Date"," Reason of Leaving"," Salary Status"," Remarks If Any");
			
			
			$DateTo = date('F Y',strtotime("last day of previous month"));
			$myDB=new MysqliDb();
			//echo "select w.*,s.*,b.BankName,b.AccountNo,b.Branch,b.Location,b.Active,cam.D1,cam.D2,cam.D3,cam.D4,cam.D5,cam.D6,cam.D7,cam.D8,cam.D9,cam.D10,cam.D11,cam.D12,cam.D13,cam.D14,cam.D15,cam.D16,cam.D17,cam.D18,cam.D19,cam.D20,cam.D21,cam.D22,cam.D23,cam.D24,cam.D25,cam.D26,cam.D27,cam.D28,cam.D29,cam.D30,cam.D31 from whole_dump_emp_data w inner join calc_atnd_master cam on cam.EmployeeID = w.EmployeeID and month='".date("m",strtotime($DateTo))."' and year ='".date("Y",strtotime($DateTo))."' inner join  salary_details s on w.EmployeeID = s.EmployeeID left outer join bank_details b on w.EmployeeID = b.EmployeeID and b.Active = 'Active' where w.location in (".$location.") and w.DOJ<='".date("Y-m-t",strtotime($DateTo))."' and case when w.emp_status = 'InActive' then  w.EmployeeID in (select distinct EmployeeID from exit_emp  inner join (select max(id) id from exit_emp group by EmployeeID) t1 on t1.id = exit_emp.id where exit_emp.dol >= '".date("Y-m-01",strtotime($DateTo))."')  else true end;";
			$chk_task=$myDB->query("select w.*,s.*,b.BankName,b.IFSC_code,b.AccountNo,b.Branch,b.Location,b.Active,cam.D1,cam.D2,cam.D3,cam.D4,cam.D5,cam.D6,cam.D7,cam.D8,cam.D9,cam.D10,cam.D11,cam.D12,cam.D13,cam.D14,cam.D15,cam.D16,cam.D17,cam.D18,cam.D19,cam.D20,cam.D21,cam.D22,cam.D23,cam.D24,cam.D25,cam.D26,cam.D27,cam.D28,cam.D29,cam.D30,cam.D31 from whole_dump_emp_data w inner join calc_atnd_master cam on cam.EmployeeID = w.EmployeeID and month='".date("m",strtotime($DateTo))."' and year ='".date("Y",strtotime($DateTo))."' inner join  salary_details s on w.EmployeeID = s.EmployeeID left outer join bank_details b on w.EmployeeID = b.EmployeeID and b.Active = 'Active' where w.location in (".$location.") and w.DOJ<='".date("Y-m-t",strtotime($DateTo))."' and case when w.emp_status = 'InActive' then  w.EmployeeID in (select distinct EmployeeID from exit_emp  inner join (select max(id) id from exit_emp group by EmployeeID) t1 on t1.id = exit_emp.id where exit_emp.dol >= '".date("Y-m-01",strtotime($DateTo))."')  else true end;");
							   
			$my_error= $myDB->getLastError();
			$rg_status='';
			$fileName = '';
			if($val['id']=="1")
			{
				$fileName = 'automail_payroll_lm_Noida.csv';
				
			}
			else if($val['id']=="3")
			{
				$fileName = 'automail_payroll_lm_Meerut.csv';
			}
			else if($val['id']=="4")
			{
				$fileName = 'automail_payroll_lm_Bareilly.csv';
			}
			else if($val['id']=="5")
			{
				$fileName = 'automail_payroll_lm_Vadodara.csv';
			}
			else if($val['id']=="6")
			{
				$fileName = 'automail_payroll_lm_Mangalore.csv';
			}
			else if($val['id']=="7")
			{
				$fileName = 'automail_payroll_lm_Bangalore.csv';
			}
			else if($val['id']=="8")
			{
				$fileName = 'automail_payroll_lm_Bangalore_Flipkart.csv';
			}
			
		if(count($chk_task) > 0 && $chk_task)
		{  
				
				$fp = fopen($fileName, 'w');
				
    			fputcsv($fp, $columnNames);
			    $counter = 0;
				$my_error= $myDB->getLastError()	;		
				if(count($chk_task) > 0 && $chk_task)
				{   
					$date_first = date("Y-m-01",strtotime($DateTo));
					$date_last = date("Y-m-t",strtotime($DateTo));
					
					
					foreach($chk_task as $key => $value)
					{
						$array_to_insert = array();
						
						$EmployeeID = $value['EmployeeID'];						
						$array_to_insert['EmployeeID'] = $EmployeeID;
                                                 	
				        $myDB = new MysqliDb();
				        $data_rt_type = $myDB->query("select sf_payroll_rt_type('".$EmployeeID."','".$date_last."') rt_type limit 1;");
				        if(count($data_rt_type) > 0 && $data_rt_type)
				        {
				         	$tmp_val_sal = $data_rt_type[0]['rt_type'];
						    $value['rt_type'] = $data_rt_type[0]['rt_type'];
					    }
					    
					    
						$array_to_insert['EmployeeName'] = $value['EmployeeName'];
						$array_to_insert['Salary Month'] = date('F Y',strtotime($date_first));
						
						$DOJ = $value['DOJ'];
						$myDB  = new MysqliDb();
						$data_dod = $myDB->query("select first_dod,day_stpn from personal_details where EmployeeID ='".$EmployeeID."'");
						$DOD = $value['DOD'];
						$date_fd  = date('Y-m-d',strtotime('-1 days '.$DOJ));
						$Stipend_pd_amt = 0;
						
						if(isset($data_dod[0]['first_dod']) && ($value['df_id'] == 74 || $value['df_id'] == 77))
						{
							if(!empty($data_dod[0]['first_dod']) && strtotime($data_dod[0]['first_dod']))
							{
								
								$date_fd = date('Y-m-d',strtotime('-1 days '.$data_dod[0]['first_dod']));
								$Stipend_pd_amt = $data_dod[0]['day_stpn'];
								
							}
							
							
						}
						$array_to_insert['Date Of Joining'] = date('Y-m-d',strtotime($value['DOJ']));
						if($DOD == 'NA' || empty($DOD))
						{
							$array_to_insert['Date Of Deployment'] = '-';
						}
						else
						{
							$array_to_insert['Date Of Deployment']	= date('Y-m-d',strtotime($value['DOD']));
						}
						
							
						$array_to_insert['Calculated Deployment'] = date('Y-m-d',strtotime('+1 days '.$date_fd));
						
						$array_to_insert['Designation'] = $value['designation'];
						$array_to_insert['Client'] = $value['clientname'];
						$array_to_insert['Process'] = $value['Process'];
						$array_to_insert['Sub Process'] = $value['sub_process'];

						if($value['emptype']=='FT')
						{
							$array_to_insert['Emp Type'] = 'Full Time';
						}
						elseif($value['emptype']==''){
							$array_to_insert['Emp Type'] = '';
						}
						else
						{
							$array_to_insert['Emp Type'] = 'Part Time';
						}
						if($value['payrolltype']=='RT')
						{
							$array_to_insert['Payroll Type'] = 'Retainership';
						}
						elseif($value['payrolltype']==''){
							$array_to_insert['Payroll Type'] = '';
						}
						elseif($value['payrolltype']=='INPE')
						{
							$array_to_insert['Payroll Type'] = 'On Roll Under PF & ESI Slab';
						}
						else
						{
							$array_to_insert['Payroll Type'] = 'On Roll Above PF & ESI Slab';
						}
						
						if($value['rt_type'] == 3)
						{
							$array_to_insert['Roster Type'] = 'Part Time';
						}
						else if($value['rt_type'] == 4)
						{
							$array_to_insert['Roster Type'] = 'Split Time';
						}
						else
						{
							$array_to_insert['Roster Type'] = 'Full Time';
						}
						
						$array_to_insert['GENDER'] = $value['Gender'];
						$array_to_insert['Father Name'] = $value['FatherName'];
						$array_to_insert['Mother Name'] = $value['MotherName'];
						$array_to_insert['Date Of Birth'] = date('Y-m-d',strtotime($value['DOB']));
						$array_to_insert['Bank Name'] = $value['BankName'];
						$array_to_insert['Bank Account Number'] = "'".$value['AccountNo'];
						$array_to_insert['Name as per Bank'] = $value['EmployeeName'];
						$array_to_insert['IFSC Code'] = $value['IFSC_code'];
						$array_to_insert['Emp Status'] = $value['emp_status'];
						$data_status_dsp = array();
						$till_date = $date_last;
						
						if(strtoupper($value['emp_status']) == 'INACTIVE')
						{
							
							$myDB = new MysqliDb();
							$data_status_dsp = $myDB->query('select rsnofleaving,disposition,dol from exit_emp where EmployeeID = "'.$EmployeeID.'" order by id desc limit 1;'); 		if(strtotime($data_status_dsp[0]['dol']))
							{
								if(date('Y-m',strtotime($data_status_dsp[0]['dol'])) ==  date('Y-m',strtotime($date_last)))
								{
									if(strtoupper($data_status_dsp[0]['disposition']) == 'RES' || strtoupper($data_status_dsp[0]['rsnofleaving']) == 'RES' )
									{
										$till_date = date('Y-m-d',strtotime($data_status_dsp[0]['dol']));
									}
									else
									{
										$till_date = date('Y-m-d',strtotime('-1 days '.$data_status_dsp[0]['dol']));
									}
								}
								
								
							}
						}
						
						$tfd = '0';
						if($date_first == date("Y-m-01", strtotime("previous day")))
						{
							if($till_date > date("Y-m-d", strtotime("previous day")))
							{
								$till_date = date("Y-m-d", strtotime("previous day"));
							}
							
						}
						
						if(strtotime($date_fd) >= strtotime($DOJ))
						{
							if(strtotime($date_fd) <= strtotime($till_date) && strtotime($date_fd) >= strtotime($date_first))
							{
								$check_date_ttl = '';
								$check_date_ttl = date('Y-m-d',strtotime('+1 days '.$date_fd));	
								$tmp_date1=date_create($check_date_ttl);					
								$tmp_date2=date_create($till_date);
								$diff=date_diff($tmp_date1,$tmp_date2);
								$tfd = $diff->format("%r%a") + 1;
							}
							else if(strtotime($date_fd) <= strtotime($till_date) && strtotime($date_fd) < strtotime($date_first))
							{
									
								$tmp_date1=date_create($date_first);					
								$tmp_date2=date_create($till_date);
								$diff=date_diff($tmp_date1,$tmp_date2);
								$tfd = $diff->format("%r%a") + 1;
							}
						}
						else
						{
							if(strtotime($DOJ) <= strtotime($till_date) && strtotime($DOJ) >= strtotime($date_first))
							{
									
								$tmp_date1=date_create($DOJ);					
								$tmp_date2=date_create($till_date);
								$diff=date_diff($tmp_date1,$tmp_date2);
								$tfd = $diff->format("%r%a") + 1;
							}
							else if(strtotime($DOJ) <= strtotime($till_date) && strtotime($DOJ) < strtotime($date_first))
							{
									
								$tmp_date1=date_create($date_first);					
								$tmp_date2=date_create($till_date);
								$diff=date_diff($tmp_date1,$tmp_date2);
								$tfd = $diff->format("%r%a") + 1;
							}
						}
						$array_to_insert['Total Floor Days'] = $tfd;
						$array_to_insert['CTC'] =round($value['ctc'],0);
						$array_to_insert['TK Home'] = round($value['net_takehome'],0);

						$salary_takehome = round($value['net_takehome'],2);
						
						$myDB = new MysqliDb();
						$data_pl = $myDB->query("SELECT paidleave FROM paid_leave_all where date_format(date_paid,'%Y-%M') = date_format('".$date_first."','%Y-%M') and date_paid <= curdate() and EmployeeID= '".$EmployeeID."' order by id desc,date_paid desc limit 1");
						
						//$data_pl = $myDB->query('select paidleave,date_paid from paid_leave_all where EmployeeID = "'.$EmployeeID.'" and cast(date_paid as date) = "'.$date_first.'" order by id'); 
						
						$total_leave_start = 0;
						
						if(count($data_pl) > 0)
						{
							$total_leave_start  = $data_pl[0]['paidleave'];
						}
						else
						{
							$total_leave_start  = '0';
						}
						
						
						/*$myDB = new MysqliDb();
						$data_atnd = $myDB->query('select D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 from calc_atnd_master where EmployeeID = "'.$EmployeeID.'" and Month = "'.intval(date('m',strtotime($date_first))).'" order by modifiedon limit 1'); 
						*/
						
						//$data_atnd = $value['calc_atnd_master'];
						$data_atnd = array();
                    for($i = 1 ;$i <= 31 ; $i++)
                    {
                        $data_atnd['D'.$i] = $value['D'.$i];
                    }
						$atnd_A = 0;
						$atnd_P = 0;
						$atnd_H = 0;
						$atnd_L = 0;
						$atnd_LWP = 0;
						$atnd_HWP = 0;
						$atnd_WO = 0;
						$atnd_HO = 0;
						$atnd_LANA = 0;
						$atnd_WONA = 0;
						$atnd_CO = 0;
						if(count($data_atnd) > 0)
						{
							
							foreach($data_atnd as $key=>$value_atnd)
							{
								$date_day = explode('D',$key);
								$date_day = ($date_day[1] < 10)?'0'.$date_day[1]:$date_day[1];
								$date_val = date("Y-m-".$date_day,strtotime($date_first));
								if(strtotime($date_val) > strtotime($date_fd) && strtotime($date_val) <= strtotime($till_date) && strtotime($date_val) >= strtotime($DOJ))
								{
									
									if($value_atnd[0] == 'P')
									{
										$atnd_P++;
									}
									else if($value_atnd == 'LWP' || strtoupper($value_atnd) == strtoupper('LWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('LWP(Attendance Change'))
									{
										$atnd_LWP++;
									}
									else if($value_atnd == 'HWP' || strtoupper($value_atnd) == strtoupper('HWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('HWP(Attendance Change'))
									{
										$atnd_HWP++;
									}
									else if($value_atnd == 'L' || strtoupper($value_atnd) == strtoupper('L(Biometric issue)') || strtoupper($value_atnd) == strtoupper('L(Attendance Change)'))
									{
										$atnd_L++;
									}
									else if($value_atnd == 'H' || strtoupper($value_atnd) == strtoupper('H(Biometric issue)') || strtoupper($value_atnd) == strtoupper('H(Attendance Change)'))
									{
										$atnd_H++;
									}
									else if(strtoupper(trim($value_atnd)) == 'HO')
									{
										$atnd_HO++;
									}
									else if(strtoupper(trim($value_atnd)) == 'WO')
									{
										$atnd_WO++;
									}
									else if(strtoupper(trim($value_atnd)) == 'LANA')
									{
										$atnd_LANA++;
									}
									else if(strtoupper(trim($value_atnd)) == 'WONA')
									{
										$atnd_WONA++;
									}
									else if(strtoupper(trim($value_atnd)) == 'CO')
									{
										$atnd_CO++;
									}
									else
									{
										$atnd_A++;
									}
								}
								
								
								
								
							}
							
						}
						$atnd_CO_Less = 0;
						$array_to_insert['Leave Opening Balance'] = $total_leave_start;

						if($value['rt_type']  == 3)
						{
							$array_to_insert['P'] = '0';
						}
						else
						{
							$array_to_insert['P'] = $atnd_P;
						}
						$array_to_insert['A'] = $atnd_A;

						if($value['rt_type']  == 3)
						{
							$array_to_insert['L'] = round($atnd_L / 2,1);
						}
						else
						{
							$array_to_insert['L'] = $atnd_L;
						}
						if($value['rt_type']  == 3)
						{
							$array_to_insert['H'] = 0;
						}
						else
						{
							$array_to_insert['H'] = $atnd_H;
						}
						$array_to_insert['LWP'] = $atnd_LWP;
						$array_to_insert['HWP'] = $atnd_HWP;
						$array_to_insert['WO'] = $atnd_WO;
						$array_to_insert['LANA'] = $atnd_LANA;
						$array_to_insert['WONA'] = $atnd_WONA;


						if($value['rt_type']  == 3)
						{
							$array_to_insert['Total Leaves'] = round(($atnd_L / 2),2);
						}
						else
						{
							$array_to_insert['Total Leaves'] = round(($atnd_L + $atnd_H / 2),2);
						}
						if($value['rt_type']  == 3)
						{
							$array_to_insert['Leave Adjusted'] = round(($atnd_L / 2),2);
						}
						else
						{
							$array_to_insert['Leave Adjusted'] = round(($atnd_L + $atnd_H / 2),2);
							
						}
						if($value['rt_type']  == 3)
						{
							$array_to_insert['Pay Days'] = round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO),2);

						}
						else
						{
							$array_to_insert['Pay Days'] = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO),2);
						}
						$array_to_insert['CO Paid'] = 'CO Paid';
						
						if($value['rt_type']  == 3)
						{
							$array_to_insert['CO Less'] = round($atnd_CO / 2);
						}
						else
						{
							$array_to_insert['CO Less'] = $atnd_CO;
						}
						
						if($value['rt_type']  == 3)
						{
							$array_to_insert['Total Pay Days'] = round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO / 2 + $atnd_CO_Less / 2),2);
						}
						else
						{
							$array_to_insert['Total Pay Days'] = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO + $atnd_CO_Less ),2);
						}
						
						$total_day_paid = 0;
						if($value['rt_type']  == 3)
						{
							$total_day_paid = round(($atnd_L / 2 + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO / 2 + $atnd_CO_Less / 2),2);
						}
						else
						{
							$total_day_paid = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO + $atnd_CO_Less),2);
						}
						
						
						
						$auth_calc_P = $atnd_P;
						$auth_calc_A = $atnd_A;
						
						$atnd_A = 0;
						$atnd_P = 0;
						$atnd_H = 0;
						$atnd_L = 0;
						$atnd_LWP = 0;
						$atnd_HWP = 0;
						$atnd_WO = 0;
						$atnd_HO = 0;
						$atnd_LANA = 0;
						$atnd_WONA = 0;
						$atnd_CO = 0;
						if(count($data_atnd) > 0)
						{
							
							foreach($data_atnd as $key=>$value_atnd)
							{
								$date_day = explode('D',$key);
								$date_day = ($date_day[1] < 10)?'0'.$date_day[1]:$date_day[1];
								$date_val = date("Y-m-".$date_day,strtotime($date_first));
								if(strtotime($date_val) >= strtotime($DOJ) && strtotime($date_val) <= strtotime($date_fd) && strtotime($date_fd) >= strtotime($DOJ) && strtotime($date_val) <= strtotime($till_date))
								{
									if($value_atnd[0] == 'P')
									{
										$atnd_P++;
									}
									else if($value_atnd == 'LWP' || strtoupper($value_atnd) == strtoupper('LWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('LWP(Attendance Change'))
									{
										$atnd_LWP++;
									}
									else if($value_atnd == 'HWP' || strtoupper($value_atnd) == strtoupper('HWP(Biometric issue)') || strtoupper($value_atnd) == strtoupper('HWP(Attendance Change'))
									{
										$atnd_HWP++;
									}
									else if($value_atnd == 'L' || strtoupper($value_atnd) == strtoupper('L(Biometric issue)') || strtoupper($value_atnd) == strtoupper('L(Attendance Change)'))
									{
										$atnd_L++;
									}
									else if($value_atnd == 'H' || strtoupper($value_atnd) == strtoupper('H(Biometric issue)') || strtoupper($value_atnd) == strtoupper('H(Attendance Change)'))
									{
										$atnd_H++;
									}
									else if(strtoupper(trim($value_atnd)) == 'HO')
									{
										$atnd_HO++;
									}
									else if(strtoupper(trim($value_atnd)) == 'WO')
									{
										$atnd_WO++;
									}
									else if(strtoupper(trim($value_atnd)) == 'LANA')
									{
										$atnd_LANA++;
									}
									else if(strtoupper(trim($value_atnd)) == 'WONA')
									{
										$atnd_WONA++;
									}
									else if(strtoupper(trim($value_atnd)) == 'CO')
									{
										$atnd_CO++;
									}
									else
									{
										$atnd_A++;
									}
								}
								
								
								
								
							}
							
						}
						$atnd_CO_Less = 0;
						$atnd_tr = round(($atnd_L + $atnd_H + $atnd_P + $atnd_HWP / 2 + $atnd_WO + $atnd_HO + $atnd_CO + $atnd_CO_Less),2);	
						$Stipend_pd = round($Stipend_pd_amt,2);
						
						// Calculation for Inct for selected month.
						
						$Attendnace_inc = 0;
						$Split_inc = 0;
						$OT_inc = 0;
						$Night_Mor_inc = 0;
						
						$Ref_inc = 0;
						$Lylty_inc = 0;
						
						
						// Calculation for Split
						
						$myDB = new MysqliDb();						
						$Split_data = $myDB->query('SELECT * FROM inc_incentive_criteria where ("'.$date_first.'" between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = "'.$EmployeeID.'") and Incentive_Type = "Split"  and Request_Status = "Approved" order by CreatedOn , id limit 1;');
						
						if(count($Split_data) > 0 && $Split_data)
						{
							$begin = new DateTime($date_first);
							$end = new DateTime($date_last);
							
							$range_date_1 =new DateTime($Split_data[0]['StartDate']);
							$range_date_2 =new DateTime($Split_data[0]['EndDate']);
							$Rate_split = floatval($Split_data[0]['Rate']);
							$BaseCriteria =  $Split_data[0]['BaseCriteria'];
							$criteria1 = $Split_data[0]['criteria1'];
							$criteria2 = $Split_data[0]['criteria2'];
							
							$myDB = new MysqliDb();
							
							$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID="'.$EmployeeID.'" and DateOn between cast("'.$range_date_1->format('Y-m-d').'" as date) and cast("'.date('Y-m-d',(strtotime($range_date_2->format('Y-m-d').' +1 days'))).'" as date) order by DateOn,PunchTime';
							
							$ds_punchtime = $myDB->query($str_capping);
							$bioinout = array();
				            // Fetch data for APR  in given range; 
				            if(count($ds_punchtime) > 0 && $ds_punchtime)
				            {
								foreach($ds_punchtime as $key_bio=>$value_bio)
								{
									$bioinout[$value_bio['DateOn']][] = $value_bio['DateOn'].' '.$value_bio['PunchTime'];
								}
							}
							for($i = $begin; $begin <= $end; $i->modify('+1 day'))
							{
								
								$dat_this =  $i->format('Y-m-d');
								if($i >= $range_date_1 && $i <= $range_date_2 && $range_date_1 <= $range_date_2 && $data_atnd['D'.$i->format('j')][0] == 'P')
								{
									$bio_data = $bioinout[$dat_this];
									$validate = 0;
									foreach($bio_data as $bio_key => $bio_value)
									{
										
										if(strtotime($bio_value) <=  strtotime($dat_this.' '.$criteria1) && $validate === 0)
										{
											$validate = 1;
										}
										elseif((strtotime($bio_value) >=  strtotime($dat_this.' '.$criteria2)) && $validate === 1)
										{
											$Split_inc = $Split_inc + $Rate_split; 
											$validate = 2;
										}
										else
										{
											$Split_inc = $Split_inc  + 0;
										}
									}
									
								}
								
								
							}
						}
						
						
						// Calculation for Night_Mor_inc
						$myDB = new MysqliDb();						
						$Night_data = $myDB->query('SELECT * FROM inc_incentive_criteria where ("'.$date_first.'" between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = "'.$EmployeeID.'") and Incentive_Type = "Night/Late Evening" and Request_Status = "Approved" order by CreatedOn , id limit 1;');
						
						if(count($Night_data) > 0 && $Night_data)
						{
							$begin = new DateTime($date_first);
							$end = new DateTime($date_last);
							
							$range_date_1 =new DateTime($Night_data[0]['StartDate']);
							$range_date_2 =new DateTime($Night_data[0]['EndDate']);
							$Rate_Night = floatval($Night_data[0]['Rate']);
							$BaseCriteria =  $Night_data[0]['BaseCriteria'];
							$criteria1 = $Night_data[0]['criteria1'];
							$criteria2 = $Night_data[0]['criteria2'];
							
							$myDB = new MysqliDb();
							
							$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID="'.$EmployeeID.'" and DateOn between cast("'.$range_date_1->format('Y-m-d').'" as date) and cast("'.date('Y-m-d',(strtotime($range_date_2->format('Y-m-d').' +1 days'))).'" as date) order by DateOn,PunchTime';
							
							$ds_punchtime = $myDB->query($str_capping);
							$bioinout = array();
				            // Fetch data for APR  in given range; 
				            if(count($ds_punchtime) > 0 && $ds_punchtime)
				            {
								foreach($ds_punchtime as $key_bio=>$value_bio)
								{
									$bioinout[$value_bio['DateOn']][] = $value_bio['DateOn'].' '.$value_bio['PunchTime'];
								}
							}
							for($i = $begin; $begin <= $end; $i->modify('+1 day'))
							{
								
								$dat_this =  $i->format('Y-m-d');
								if($i >= $range_date_1 && $i <= $range_date_2 && $range_date_1 <= $range_date_2 && $data_atnd['D'.$i->format('j')][0] == 'P')
								{
									$bio_data = array_merge($bioinout[$dat_this],$bioinout[$dat_this]);
									$validate = 0;
									foreach($bio_data as $bio_key => $bio_value)
									{
										$timeStamp = '';
										if(strtotime($criteria1) >= strtotime('00:00') && strtotime($criteria1) <= strtotime('09:00'))
										{
											$timeStamp = strtotime($dat_this.' '.$criteria1);
											$timeStamp = strtotime('+1 days '.date('Y-m-d H:i:s',$timeStamp));
											
										}
										else
										{
											$timeStamp = strtotime($dat_this.' '.$criteria1);
										}


										$timeStamp1 = '';
										if(strtotime($criteria2) >= strtotime('00:00') && strtotime($criteria2) <= strtotime('09:00'))
										{
											$timeStamp1 = strtotime($dat_this.' '.$criteria2);
											$timeStamp1 = strtotime('+1 days '.date('Y-m-d H:i:s',$timeStamp));
											
										}
										else
										{
											$timeStamp1 = strtotime($dat_this.' '.$criteria2);
										}
										
										
										if(strtotime($bio_value) <=  $timeStamp && $validate === 0)
										{
											$validate = 1;
										}
										elseif((strtotime($bio_value) >=  $timeStamp1) && $validate === 1)
										{
											$Night_Mor_inc = $Night_Mor_inc + $Rate_Night; 
											$validate = 2;
										}
										else
										{
											$Night_Mor_inc = $Night_Mor_inc  + 0;
										}
									}
									
								}
								
								
							}
						}
						
						
						// Calculation for Day
						
						$myDB = new MysqliDb();						
						$Day_data = $myDB->query('SELECT * FROM inc_incentive_criteria where ("'.$date_first.'" between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = "'.$EmployeeID.'") and Incentive_Type = "Morning" and Request_Status = "Approved" order by CreatedOn , id limit 1;');
						
						if(count($Day_data) > 0 && $Day_data)
						{
							$begin = new DateTime($date_first);
							$end = new DateTime($date_last);
							
							$range_date_1 =new DateTime($Day_data[0]['StartDate']);
							$range_date_2 =new DateTime($Day_data[0]['EndDate']);
							$Rate_Day = floatval($Day_data[0]['Rate']);
							$BaseCriteria =  $Day_data[0]['BaseCriteria'];
							$criteria1 = $Day_data[0]['criteria1'];
							$criteria2 = $Day_data[0]['criteria2'];
							
							$myDB = new MysqliDb();
							
							$str_capping = 'select EmpID,PunchTime, DateOn from biopunchcurrentdata where EmpID="'.$EmployeeID.'" and DateOn between cast("'.$range_date_1->format('Y-m-d').'" as date) and cast("'.date('Y-m-d',(strtotime($range_date_2->format('Y-m-d').' +1 days'))).'" as date) order by DateOn,PunchTime';
							
							$ds_punchtime = $myDB->query($str_capping);
							$bioinout = array();
				            // Fetch data for APR  in given range; 
				            if(count($ds_punchtime) > 0 && $ds_punchtime)
				            {
								foreach($ds_punchtime as $key_bio=>$value_bio)
								{
									$bioinout[$value_bio['DateOn']][] = $value_bio['DateOn'].' '.$value_bio['PunchTime'];
								}
							}
							for($i = $begin; $begin <= $end; $i->modify('+1 day'))
							{
								
								$dat_this =  $i->format('Y-m-d');
								if($i >= $range_date_1 && $i <= $range_date_2 && $range_date_1 <= $range_date_2 && $data_atnd['D'.$i->format('j')][0] == 'P')
								{
									$bio_data = $bioinout[$dat_this];
									$validate = 0;
									foreach($bio_data as $bio_key => $bio_value)
									{
										
										if(strtotime($bio_value) <=  strtotime($dat_this.' '.$criteria1) && $validate === 0)
										{
											$validate = 1;
										}
										elseif((strtotime($bio_value) >=  strtotime($dat_this.' '.$criteria2)) && $validate === 1)
										{
											$Split_inc = $Split_inc + $Rate_Day; 
											$validate = 2;
										}
										else
										{
											$Split_inc = $Split_inc  + 0;
										}
									}
									
								}
								
								
							}
						}
						
						
						
						// Calculation for Attendance
						$Attendnace_inc = 0;
						$Attendance_data = null;
						if($value['df_id'] == 74 || $value['df_id'] == 77)
						{
							$myDB = new MysqliDb();						
							$Attendance_data = $myDB->query('SELECT * FROM inc_incentive_criteria where ("'.$date_first.'" between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = "'.$EmployeeID.'" ) and Incentive_Type = "Attendance" and Request_Status = "Approved" and ApplicableFor="CSA" order by CreatedOn , id limit 1;');
						}
						else
						{
							$myDB = new MysqliDb();						
							$Attendance_data = $myDB->query('SELECT * FROM inc_incentive_criteria where ("'.$date_first.'" between StartDate and EndDate) and cm_id = (select cm_id from employee_map where EmployeeID = "'.$EmployeeID.'" ) and Incentive_Type = "Attendance" and Request_Status = "Approved" and ApplicableFor="Support" order by CreatedOn , id limit 1;');
							
						}
						
						if(count($Attendance_data) > 0 && $Attendance_data)
						{
							$begin = new DateTime($date_first);
							$end = new DateTime($date_last);
							
							$range_date_1 =new DateTime($Attendance_data[0]['StartDate']);
							$range_date_2 =new DateTime($Attendance_data[0]['EndDate']);
							
							$BaseCriteria =  $Attendance_data[0]['BaseCriteria'];
							$criteria11 = $Attendance_data[0]['criteria1'];
							$criteria12 = $Attendance_data[0]['criteria2'];
							$Rate_Attendance1 = floatval($Attendance_data[0]['Rate']);
							
							$criteria21 = $Attendance_data[0]['criteria12'];
							$criteria22 = $Attendance_data[0]['criteria22'];
							$Rate_Attendance2 = floatval($Attendance_data[0]['Rate2']);
							
							$criteria31 = $Attendance_data[0]['criteria13'];
							$criteria32 = $Attendance_data[0]['criteria23'];
							$Rate_Attendance3 = floatval($Attendance_data[0]['Rate3']);
							
							$tmp_Attendnace_inc =array();
							if(!empty($criteria11))
							{
								if($auth_calc_P > $criteria11 && $auth_calc_A<=$criteria12)
								{
									$validate = 1;
									$tmp_Attendnace_inc[] = array("days"=>$criteria11,"rate"=>$Rate_Attendance1);
								}
							}
							if(!empty($criteria21))
							{
								if($auth_calc_P > $criteria21 && $auth_calc_A<=$criteria22)
								{
									$validate = 1;
									$tmp_Attendnace_inc[] = array("days"=>$criteria21,"rate"=>$Rate_Attendance2);
								}
							}
							if(!empty($criteria31))
							{
								if($auth_calc_P > $criteria31 && $auth_calc_A<=$criteria32)
								{
									$validate = 1;
									$tmp_Attendnace_inc[] = array("days"=>$criteria31,"rate"=>$Rate_Attendance3);
								}
							}
							if(count($tmp_Attendnace_inc) > 0)
							{
								$tmp_Attendnace_inc_val = array();								
								$tmp_Attendnace_inc_val = getMax($tmp_Attendnace_inc,"days");
								foreach($tmp_Attendnace_inc as $val_inc_atnd_check)
								{
									if($val_inc_atnd_check["days"] == $tmp_Attendnace_inc_val)
									{
										$Attendnace_inc = $Attendnace_inc + ((is_numeric($val_inc_atnd_check["rate"]))?$val_inc_atnd_check["rate"]:0);
									}
								}
							}
							
							
						}
						
						
						// Calculation for Woman Attendance
						
						$Attendance_data = null;
						if($value['df_id'] == 74 || $value['df_id'] == 77)
						{
							$myDB = new MysqliDb();						
							$Attendance_data = $myDB->query('SELECT * FROM inc_incentive_criteria where ("'.$date_first.'" between StartDate and EndDate) and cm_id = (select cm_id from whole_dump_emp_data where EmployeeID = "'.$EmployeeID.'" and Gender = "Female") and Incentive_Type = "Woman" and Request_Status = "Approved" and ApplicableFor="CSA" order by CreatedOn , id limit 1;');
						}
						else
						{
							$myDB = new MysqliDb();						
							$Attendance_data = $myDB->query('SELECT * FROM inc_incentive_criteria where ("'.$date_first.'" between StartDate and EndDate) and cm_id = (select cm_id from whole_dump_emp_data where EmployeeID = "'.$EmployeeID.'" and Gender = "Female") and Incentive_Type = "Woman" and Request_Status = "Approved" and ApplicableFor="Support" order by CreatedOn , id limit 1;');
							
						}
						
						if(count($Attendance_data) > 0 && $Attendance_data)
						{
							$begin = new DateTime($date_first);
							$end = new DateTime($date_last);
							
							$range_date_1 =new DateTime($Attendance_data[0]['StartDate']);
							$range_date_2 =new DateTime($Attendance_data[0]['EndDate']);
							
							$BaseCriteria =  $Attendance_data[0]['BaseCriteria'];
							$criteria11 = $Attendance_data[0]['criteria1'];
							$criteria12 = $Attendance_data[0]['criteria2'];
							$Rate_Woman_Attendance1 = floatval($Attendance_data[0]['Rate']);
							
							$criteria21 = $Attendance_data[0]['criteria12'];
							$criteria22 = $Attendance_data[0]['criteria22'];
							$Rate_Woman_Attendance2 = floatval($Attendance_data[0]['Rate2']);
							
							$criteria31 = $Attendance_data[0]['criteria13'];
							$criteria32 = $Attendance_data[0]['criteria23'];
							$Rate_Woman_Attendance3 = floatval($Attendance_data[0]['Rate3']);
							
							$tmp_Attendnace_inc =array();
							if(!empty($criteria11))
							{
								if($auth_calc_P > $criteria11 && $auth_calc_A<=$criteria12)
								{
									$validate = 1;
									$tmp_Attendnace_inc[] = array("days"=>$criteria11,"rate"=>$Rate_Woman_Attendance1);
								}
							}
							if(!empty($criteria21))
							{
								if($auth_calc_P > $criteria21 && $auth_calc_A<=$criteria22)
								{
									$validate = 1;
									$tmp_Attendnace_inc[] = array("days"=>$criteria21,"rate"=>$Rate_Woman_Attendance2);
								}
							}
							if(!empty($criteria31))
							{
								if($auth_calc_P > $criteria31 && $auth_calc_A<=$criteria32)
								{
									$validate = 1;
									$tmp_Attendnace_inc[] = array("days"=>$criteria31,"rate"=>$Rate_Woman_Attendance3);
								}
							}
							if(count($tmp_Attendnace_inc) > 0)
							{
								$tmp_Attendnace_inc_val = array();								
								$tmp_Attendnace_inc_val = getMax($tmp_Attendnace_inc,"days");
								foreach($tmp_Attendnace_inc as $val_inc_atnd_check)
								{
									if($val_inc_atnd_check["days"] == $tmp_Attendnace_inc_val)
									{
										$Attendnace_inc = $Attendnace_inc + ((is_numeric($val_inc_atnd_check["rate"]))?$val_inc_atnd_check["rate"]:0);
									}
								}
							}
							
							
						}
						$array_to_insert['Attendnace Inct'] = $Attendnace_inc;
						$array_to_insert['Split Inct'] = $Split_inc;
						$array_to_insert['OT Inct'] = 'OT Inct';
						$array_to_insert['Night-Mor Inct'] = $Night_Mor_inc;
						$array_to_insert['Ref Inct'] = 'Ref Inct';
						$array_to_insert['Lylty-PLI Bonus'] = 'Lylty/PLI Bonus';


						
						$df_id = $value['df_id'];
						$df_id_NTE = array(67,68,69,71,74,76,77,81,82,83,88,103,104,105,110);
						
						if(in_array($df_id,$df_id_NTE))
						{
							$array_to_insert['Trainig Stipend'] = round( $Stipend_pd * $atnd_tr,0);
						}
						else
						{
							$array_to_insert['Trainig Stipend'] = 0;
						}
						
						
						//$table .='<td>'.round( $Stipend_pd * $atnd_tr,0).'</td>';
						
						if(!is_numeric($Split_inc))
						{
							$Split_inc = 0;
						}
						if(!is_numeric($Night_Mor_inc))
						{
							$Night_Mor_inc = 0;
						}
						if(!is_numeric($Attendnace_inc))
						{
							$Attendnace_inc = 0;
						}
						$myDB = new MysqliDb();
						$data_Incentive = $myDB->query('SELECT ClientIncentive, Arrears, OtherIncentive, OtherNarration FROM payroll_incentive_upld where  EmployeeID = "'.$EmployeeID.'" and month="'.date("m",strtotime($date_first)).'" and year="'.date("Y",strtotime($date_first)).'"  order by createdon desc limit 1;'); 
						if(count($data_Incentive) > 0 )
						{
							$ClientIncentive = $data_Incentive[0]['ClientIncentive'];
							$Arrears = $data_Incentive[0]['Arrears'];
							$OtherIncentive = $data_Incentive[0]['OtherIncentive'];
							$Other_Incentive_Narration = $data_Incentive[0]['OtherNarration'];
							$total_Incentive = trim($ClientIncentive) + trim($Arrears) + trim($OtherIncentive) + trim($Other_Incentive_Narration);
						
						}
						else
						{
							$ClientIncentive = 0;
							$Arrears = 0;
							$OtherIncentive = 0;
							$Other_Incentive_Narration = 0;
							$total_Incentive = 0;
							
						}
						$array_to_insert['Client Incentive'] = $ClientIncentive;
						$array_to_insert['Arrears'] = $Arrears;
						$array_to_insert['Other Incentive'] = $OtherIncentive;
						$array_to_insert['Other Narration'] = $Other_Incentive_Narration;

						
						$total_Incentive = $total_Incentive + $Split_inc + $Night_Mor_inc + $Attendnace_inc;						
						
						$array_to_insert['Total Add'] = $total_Incentive;
					
						$df_id = $value['df_id'];
						$df_id_NTE = array(67,68,69,71,74,76,77,81,82,83,88,103,104,105,110);
						
						if(in_array($df_id,$df_id_NTE))
						{
							
							$total_add = ( $Stipend_pd * $atnd_tr) +  ($total_Incentive);
							
						}
						else
						{
							$total_add = ( 0 ) +  ($total_Incentive);
						}
						$myDB = new MysqliDb();
						$data_Deduction = $myDB->query('SELECT AssetDamage, Id_Access_Card_Damage, Guest_House_Rent, TDS, NoticeRecovery, OtherLess, OtherNarration FROM payroll_deduction_upld where  EmployeeID = "'.$EmployeeID.'" and month="'.date("m",strtotime($date_first)).'" and year="'.date("Y",strtotime($date_first)).'"  order by createdon desc limit 1;'); 
						if(count($data_Deduction) > 0 )
						{
							$AssetDamage = $data_Deduction[0]['AssetDamage'];
							$CardDamage = $data_Deduction[0]['Id_Access_Card_Damage'];
							$GuestHouseRent = $data_Deduction[0]['Guest_House_Rent'];
							$TDS = $data_Deduction[0]['TDS'];
							//$NoticeRecovery = $data_Deduction[0]['NoticeRecovery'];
							$OtherLess = $data_Deduction[0]['OtherLess'];
							$Other_Deduction_Narration = $data_Deduction[0]['OtherNarration'];
							$TotalLess = trim($AssetDamage) + trim($CardDamage) + trim($GuestHouseRent) + trim($TDS) + trim($OtherLess) + trim($Other_Deduction_Narration) ;
							
						}
						else
						{
							$AssetDamage = 0;
							$CardDamage = 0;
							$GuestHouseRent = 0;
							$TDS = 0;
							//$NoticeRecovery = 0;
							$OtherLess = 0;
							$Other_Deduction_Narration = 0;
							$TotalLess = 0;
							
						}
						
						//////////////// Calculate Recovery Day and Recovery Amount //////////////////////////////////////////////////////////////////
						
						$myDB = new MysqliDb();
						$recday = $myDB->query('select RecoveryDay from tbl_recovery where EmployeeID="'.$EmployeeID.'" and payroll_month="'.date("m",strtotime($date_first)).'" and payroll_year="'.date("Y",strtotime($date_first)).'"');
						
						if(count($recday)>0)
						{
							$NoticeRecovery = $recday[0]['RecoveryDay'];
						}
						else
						{
							$NoticeRecovery = 0;
						}
						
						if($NoticeRecovery < 0)
						{
							$NoticeRecoverytemp = (-1) * $NoticeRecovery;
							$Recovery_Amount  = ( $NoticeRecoverytemp * ($salary_takehome / $days_in_month) + $total_add ) - $TotalLess;
							
							if($Recovery_Amount > 0)
							{
								$TotalLess = $TotalLess + $Recovery_Amount;
							}
						}
						else
						{
							$Recovery_Amount = 0;
						}
						
						
						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
						
						$array_to_insert['Asset Damage Deduction'] = $AssetDamage;
						$array_to_insert['Card Damage Deduction'] = $CardDamage;
						$array_to_insert['Guest House Rent'] = $GuestHouseRent;
						$array_to_insert['TDS'] = $TDS;
						$array_to_insert['Recovery Day'] = $NoticeRecovery;
						$array_to_insert['Recovery Amount'] = $Recovery_Amount;
						$array_to_insert['Other Less'] = $OtherLess;
						$array_to_insert['Other Narration Less'] = $Other_Deduction_Narration;
						$array_to_insert['Total Less'] = $TotalLess;

						
						$total_less = 0;
						
						$days_in_month = _daysInMonth(date("m",strtotime($date_first)),date("Y",strtotime($date_first))); 
						
						$salary_to_carry  = ( $total_day_paid * ($salary_takehome / $days_in_month) + $total_add ) - $TotalLess;
						
						$status_dsp = 0;
						$rsn_date = null;
						$last_working_date = null;
						$rsn_of_leaving = '';
						if(strtoupper($value['emp_status']) == 'INACTIVE')
						{
							
							
							if(count($data_status_dsp) > 0)
							{
								if(strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'NCNS REQUEST (ABSC)' || strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'ABSC' || strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'DCR' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'NCNS REQUEST (ABSC)' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'ABSC' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'DCR')
								{
									$status_dsp = 1;
									$last_working_date = date('Y-m-d',strtotime($data_status_dsp[0]['dol']));
								}
								
								if(strtoupper(trim($data_status_dsp[0]['rsnofleaving'])) == 'RES' || strtoupper(trim($data_status_dsp[0]['disposition'])) == 'RES')
								{
									$rsn_date = $data_status_dsp[0]['dol'];
									$last_working_date = date('Y-m-d',strtotime($data_status_dsp[0]['dol']));
								}
								else
								{
									$last_working_date = date('Y-m-d',strtotime($data_status_dsp[0]['dol']));
								}
								
								if(!empty($data_status_dsp[0]['disposition']))
								{
									$rsn_of_leaving = $data_status_dsp[0]['disposition'];
								}
								else
								{
									$rsn_of_leaving =$data_status_dsp[0]['rsnofleaving'];
								}
							}
							
						}
						$final_payable_sal = 0;
						$final_payable_sal = (($status_dsp === 1)?0:round($salary_to_carry,0));
						if($final_payable_sal < 0)
						{
							$final_payable_sal = 0;
						}
						
						$array_to_insert['Salary'] = round($salary_to_carry,0);
						$array_to_insert['Payable Salary'] = $final_payable_sal;

						// Bank Status Details ... 
						if(!empty($value['AccountNo']) && is_numeric($value['AccountNo']))
						{
							$array_to_insert['By Bank'] = $final_payable_sal;
							$array_to_insert['By Cash'] = 0;
						}
						else
						{
							$array_to_insert['By Bank'] = 0;
							$array_to_insert['By Cash'] = $final_payable_sal;
						}
						
						$gross_sal = round($value['gross_sal'],2);
						$gross_sal_asper = round((($gross_sal / $days_in_month) * $total_day_paid),2);
						$basic_sal = round($value['basic'],2);
						$basic_sal_asper = round((($basic_sal / $days_in_month) * $total_day_paid),2);
						$array_to_insert['Basic'] = $basic_sal;
						$array_to_insert['Payable Basic'] = $basic_sal_asper;
						
						if($value['pf'] > 0  && $final_payable_sal > 0)
						{
							$array_to_insert['EMP PF 12'] = round((($basic_sal_asper * 12 )/100),2);
							$array_to_insert['EMPR PF 3_67'] = round((($basic_sal_asper * 3.67 )/100),2);
							$array_to_insert['EMPR PF 8_33'] = round((($basic_sal_asper * 8.33 )/100),2);
							$array_to_insert['EMPR PF 1_0'] = round((($basic_sal_asper * 1.0 )/100),2);
						}
						else
						{
							
							$array_to_insert['EMP PF 12'] = 0;
							$array_to_insert['EMPR PF 3_67'] = 0;
							$array_to_insert['EMPR PF 8_33'] = 0;
							$array_to_insert['EMPR PF 1_0'] = 0;
						}
						
						$table .='<td>'.$gross_sal.'</td>';
						$table .='<td>'.$gross_sal_asper.'</td>';
						$array_to_insert['Gross'] = $gross_sal;
						$array_to_insert['Payable Gross'] = $gross_sal_asper;
						
						if($value['esis'] > 0  && $final_payable_sal > 0)
						{
							$array_to_insert['ESIC 0_75'] = round((($gross_sal_asper * 0.75 )/100),2);
							$array_to_insert['ESIC 3_25'] = round((($gross_sal_asper * 3.25 )/100),2);
						}
						else
						{
							$array_to_insert['ESIC 0_75'] = 0;
							$array_to_insert['ESIC 3_25'] = 0;
						}
					$array_to_insert['Resignation Date'] = ((strtotime($rsn_date))?date('Y-m-d',strtotime($rsn_date)):'NA');
$array_to_insert['Last Working Date'] = ((strtotime($last_working_date))?date('Y-m-d',strtotime($last_working_date)):'NA');
						$array_to_insert['Reason of Leaving'] = $rsn_of_leaving;
						$myDB = new MysqliDb();
						$data_final_status = $myDB->query('SELECT SalaryStatus, Remarks FROM payroll_final_sl_status where  EmployeeID = "'.$EmployeeID.'" and month="'.date("m",strtotime($date_first)).'" and year="'.date("Y",strtotime($date_first)).'"  order by createdon desc limit 1;'); 				
						
						if(count($data_final_status)>0)
						{
							$array_to_insert['Salary Status'] = $data_final_status[0]['SalaryStatus'];
							$array_to_insert['Remarks If Any'] = $data_final_status[0]['Remarks'];
						}
						
						$array_to_insert['createdby'] = $_SESSION['__user_logid'];
						$array_to_print = array();
						
						foreach($array_to_insert as $array_tp_val)
						{
							$array_to_print[] = $array_tp_val;
						}
						
						fputcsv($fp, $array_to_print);
					}	
				}
			}
	
	settimestamp($automail,'END');
	
	$myDB=new MysqliDb();
	$pagename='auto_mail_payroll_LMD';
	echo $qq1="select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location ='".$val['id']."'";
	$select_email_array=$myDB->rawQuery($qq1);
	echo "filesize=".$file_size = filesize($fileName);
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
		if(count($select_email_array)>0){
				foreach($select_email_array as $select_email_array_val)
						{
				$email_address=$select_email_array_val['email_address'];
				if($email_address!=""){
					$mail->AddAddress($email_address);
				}
				$cc_email=$select_email_array_val['ccemail'];
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
			$EMS_CenterName = 'Bangalore_Flipkart';
		}
		
		$mail->Subject = 'EMS '.$EMS_CenterName.', Payroll Report ['.date('d M,Y',strtotime("last day of previous month")).']';
		$mail->isHTML(true);
		$mysqlError = $myDB->getLastError();
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Payroll Report for '.$EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		$mail->Body = $pwd_;
		$mymsg = '';
		if(!$mail->send())
	 	{
	 		echo '.Mailer Error:'. $mail->ErrorInfo;
	  	} 
		else
		 {
		    echo  '.Mail Send successfully.';
		 }
}
        else
        {
			//var_dump($chk_task);
		}
		
	}
	
	}
			
?>