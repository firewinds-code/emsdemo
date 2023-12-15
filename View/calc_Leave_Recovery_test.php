<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$rs_emp =$myDB->query("select des_id,DOJ,whole_dump_emp_data.EmployeeID,salary_details.rt_type from whole_dump_emp_data inner join salary_details on salary_details.EmployeeID = whole_dump_emp_data.EmployeeID join exit_emp on whole_dump_emp_data.EmployeeID=exit_emp.EmployeeID where whole_dump_emp_data.EmployeeID='CE012030230';");
//$date = '2016-09-01';//date('Y-m-d',time());
ini_set('display_errors',0);
$staff = 0;
$other = 0;
if(count($rs_emp) > 0)
{
	foreach($rs_emp as $rs_key=>$rs_val)
	{
		$var_desg_id = intval($rs_val['des_id']);
		$EmpID = $rs_val['EmployeeID'];
		$rt_type = $rs_val['rt_type'];		
		$now = time();
		if((int)date('d',$now) <= 6)
		{
			$now = strtotime('last day of previous month');	
		}
		
		//$date	= date('Y-m-d',$now);		
		$date = '2021-08-25';
		if(in_array($var_desg_id,array(1,2,3,4,6,9,11,12,17,20)))
		{
			 // or your date as well
			 $doj = $rs_val['DOJ'];

		     $your_date = strtotime($rs_val['DOJ']);
		     $day = date('d',$your_date);
		     if($day <= 15)
		     {
		     	$your_date  = date('Y-m-',$your_date).'01';
			 	
			 }
			 else
			 {
			 	$your_date = date('Y-m-01',strtotime(date('Y-m-01',$your_date).' +1 months'));
			 	//$your_date  = date('Y-',$your_date).$month.'-01';
			 }
		    
		     $iTime_in = new DateTime($your_date);
			 $iTime_out =new DateTime($date);
			 $interval = $iTime_in->diff($iTime_out);
			 $day_count = $interval->format('%r%m');
			 if($interval->format('%r%y') > 0)
			 {
			 	$day_count = $day_count  + (12 * $interval->format('%r%y'));
			 }
		     
		     if($day_count > 3 || ($day_count >= 3 && $interval->format('%r%d') > 4 ))
		     {
			 	$myDB = new MysqliDb();
			 	//echo 'select D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31 from calc_atnd_master where EmployeeID = "'.$EmpID.'" and Month='.date('n',strtotime($date)).' and Year='.date('Y',strtotime($date)).'';
				$result = $myDB->query('select D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31 from calc_atnd_master where EmployeeID = "'.$EmpID.'" and Month='.date('n',strtotime($date)).' and Year='.date('Y',strtotime($date)).'');
				if(count($result)>0)
				{
					$other ++;
					$calc = 0;
					$counter =1;
					$date_counter = intval(date('j',strtotime($date)));
					foreach($result as $key=>$value)
					{
						echo '1'.'<br/>';
						foreach($value as $k=>$val)
						{
							echo '2'.'<br/>';
							if($counter <= $date_counter)
							{
								if(strtoupper($val)  =='P' ||strtoupper($val) =='L' ||strtoupper($val) =='HO' ||strtoupper($val) =='CO' ||strtoupper($val) =='WO')
								{
									if(strtoupper($val) == 'P' && $rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
									
								}
								else if(strtoupper($val[0]) == 'P')
								{
									if($rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
								}
								else if(strtoupper($val) == 'H')
								{
									if($rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
								}
								else if(strtoupper($val[0]) == 'H' && strtoupper($val) != 'HWP')
								{
									if($rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
								}
								else if(strtoupper($val) == 'HWP')
								{
									
									if($rt_type == '3')
									{
										$calc = $calc + 1;
									}
									else
									{
										$calc = $calc + 0.5;
									}
								}
								else if($val == 'HWP(Biometric Issue)' || substr($val, 0, 3) == 'HWP')
								{
									if($rt_type == '3')
									{
										$calc = $calc + 1;
									}
									else
									{
										$calc = $calc + 0.5;
									}
								}
								else if($val == 'L(Biometric Issue)' || substr($val, 0, 3) == 'L(B' )
								{
									$calc = $calc + 1;
								}
								
							}
							$counter ++;
							
							
						}
					}
					echo $calc . '<br/>';
					$leave = 0;
					if($calc >= 25)
					{
						$leave=1;
					}
					else if($calc < 25 && $calc >= 15)
					{
						$leave=0.5;
					}
					else if($calc >= 10 && $calc < 15)
					{
						$leave=0.5;
					}
					else
					{
						$leave=0;
					}
					
					//// If current date is between from 1 to 6//////////////////////////
					/*if((int)date('d',time()) <= 6)
					{
						$myDB = new MysqliDb();
						$getMonth = date('n',strtotime($date)) -1;
						$getYear  = date('Y',strtotime($date));
						if($getMonth == 0)
						{
							$getMonth = 12;
							
						}
						if($getMonth == 12)
						{
							$getYear --;
						}
						
						$myDB = new MysqliDb();
						$last_drawn =0;
						$result0 = $myDB->query('SELECT ifnull(sum(Paid_Leave),0) as paidleave FROM paidleave where EmployeeID="'.$EmpID.'" and Month='.$getMonth.' and Year='.$getYear.';');
						
						if($result0)
						{
							$last_drawn = $result0[0][0]['paidleave'];
						}
						$myDB = new MysqliDb();
						$last_remains =0;
						$result0 = $myDB->query('SELECT * FROM paid_leave_all where EmployeeID="'.$EmpID.'" and Month(date_paid)='.$getMonth.' and Year(date_paid)='.$getYear.' order by id desc limit 1;');
						
						if($result0)
						{
							$last_remains = $result0[0]['paid_leave_all']['paidleave'];
						}
						$sum_release = $last_remains - $last_drawn;
						
						if($sum_release == 0)
						{
							$leave = $leave + 0;
						}
						else
						{
							if(intval($sum_release) >= 12 && $getMonth == 12)
							{
								$sum_release =12;
							}
							$leave = $leave + $sum_release;
						}
					}
					
					
					else
					{*/
						$myDB = new MysqliDb();
						$getMonth = date('n',strtotime($date));
						$getYear  = date('Y',strtotime($date));
						if($getMonth == 0)
						{
							$getMonth = 12;
							
						}
						if($getMonth == 12)
						{
							$getYear --;
						}
						
						
						$myDB = new MysqliDb();
						$last_remains =0;
						//echo 'SELECT * FROM paid_leave_all where EmployeeID="'.$EmpID.'" and Month(date_paid)='.$getMonth.' and Year(date_paid)='.$getYear.' order by id limit 1;';
						$result = $myDB->query('SELECT * FROM paid_leave_all where EmployeeID="'.$EmpID.'" and Month(date_paid)='.$getMonth.' and Year(date_paid)='.$getYear.' order by id limit 1;');
						
						if(count($result)>0)
						{
							$sum_release = $result[0]['paidleave'];
						}
												
						if($sum_release == 0)
						{
							$leave = $leave + 0;
						}
						else
						{
							if(intval($sum_release) >= 12 && $getMonth == 12)
							{
								$sum_release =12;
							}
							$leave = $leave + $sum_release;
						}
					//}
					
					
					/////////////////////////////////////////////////
					$myDB = new MysqliDb();
					$result1 = $myDB->query('call save_paidleave("'.$leave.'","'.$date.'","'.$EmpID.'")');
					echo $my_error= $myDB->getLastError();
					echo $EmpID.' : '.$leave.' and Days '.$calc.'<br/>';
					
				}

			 }
		     
		}
		else
		{
				$myDB = new MysqliDb();
				$result = $myDB->query('select D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31 from calc_atnd_master where EmployeeID = "'.$EmpID.'" and Month='.date('n',strtotime($date)).' and Year='.date('Y',strtotime($date)).'');
				if(count($result)>0)
				{
					$calc = 0;
					$staff ++;
					$counter =1;
					$date_counter = intval(date('j',strtotime($date)));
					foreach($result[0] as $key=>$value)
					{
						
						foreach($value as $k=>$val)
						{
							if($counter <= $date_counter)
							{
								if(strtoupper($val)  =='P' ||strtoupper($val) =='L' ||strtoupper($val) =='HO' ||strtoupper($val) =='CO' ||strtoupper($val) =='WO')
								{
									if(strtoupper($val) == 'P' && $rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
								}
								else if(strtoupper($val[0]) == 'P')
								{
									if($rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
								}
								else if(strtoupper($val) == 'H')
								{
									if($rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
								}
								else if(strtoupper($val[0]) == 'H' && strtoupper($val) != 'HWP')
								{
									if($rt_type == '3')
									{
										
									}
									else
									{
										$calc++;
									}
								}
								else if(strtoupper($val) == 'HWP')
								{
									if($rt_type == '3')
									{
										$calc = $calc + 1;
									}
									else
									{
										$calc = $calc + 0.5;
									}
								}
								else if($val == 'HWP(Biometric Issue)' || substr($val, 0, 3) == 'HWP')
								{
									if($rt_type == '3')
									{
										$calc = $calc + 1;
									}
									else
									{
										$calc = $calc + 0.5;
									}
								}
								else if($val == 'L(Biometric Issue)' || substr($val, 0, 3) == 'L(B' )
								{
									$calc = $calc + 1;
								}
								
							}
							$counter ++;
							
							
						}
					}
					$leave = 0;
					if($calc >= 25)
					{
						$leave=1.5;
					}
					else if($calc < 25 && $calc >= 15)
					{
						$leave=1.0;
					}
					else if($calc >= 10 && $calc < 15)
					{
						$leave=0.5;
					}
					else
					{
						$leave=0;
					}
				
					
					
					$getMonth = date('n',strtotime($date));
					$getYear  = date('Y',strtotime($date));
					if($getMonth == 0)
					{
						$getMonth = 12;
						
					}
					if($getMonth == 12)
					{
						$getYear --;
					}
					
					
					$myDB = new MysqliDb();
					$last_remains =0;
					$result = $myDB->query('SELECT * FROM paid_leave_all where EmployeeID="'.$EmpID.'" and Month(date_paid)='.$getMonth.' and Year(date_paid)='.$getYear.' order by id limit 1;');
					
					if(count($result)>0)
					{
						$sum_release = $result[0]['paidleave'];
					}
											
					if($sum_release == 0)
					{
						$leave = $leave + 0;
					}
					else
					{
						if(intval($sum_release) >= 12 && $getMonth == 12)
						{
							$sum_release =12;
						}
						$leave = $leave + $sum_release;
					}
						
						
					$myDB = new MysqliDb();
					$result1 = $myDB->query('call save_paidleave("'.$leave.'","'.$date.'","'.$EmpID.'")');
					echo $my_error= $myDB->getLastError();
					echo $EmpID.' : '.$leave.'<br/>';
				}
		}
		
	}
}

echo 'Staff  ::  => '.$staff;
echo '<br /> <br />Other  ::  => '.$other;



?>