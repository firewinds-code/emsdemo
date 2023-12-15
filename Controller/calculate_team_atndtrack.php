<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$DateTo ='';
			if(isset($_REQUEST['date']))
			{
				$DateTo = 	$_REQUEST['date'];
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
			if(isset($_REQUEST['empid']) && !empty($DateTo) && !empty($_REQUEST['cm_id']))
			{
				if(empty($DateTo))
				{
					$DateTo = date('Y-m-d',strtotime("today"));	
				}
				$date_check = $DateTo;
				$query = "select status_table.EmployeeID ,wh.des_id,wh.ReportTo,
case 
when status_table.status = 1 and status_table.InTraining is not null  then concat( 'Refer to HR') 
when status_table.status = 2 then concat( 'Mapped and Align to TH' ) 
when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 0  then concat( 'In Training' ) 
when status_table.status = 3 and status_training.Status = 'NO' and status_training.retrain_flag = 1  then concat( 'In RE-Training' ) 
when status_table.status = 3 and status_training.Status = 'YES' then concat( 'Training Complete and Align To TH' ) 
when status_table.status = 4 then concat( 'Align To QH' ) 
when status_table.status = 5 and status_quality.ojt_status = 0 then 
concat( 'In OJT') when status_table.status = 5 and status_quality.ojt_status = 1 then concat( 'In RE- OJT' ) 
when status_table.status = 5 and status_quality.ojt_status = 2 then concat( 'Complete OJT Align to QH') 
when status_table.status = 6 then concat( 'On Floor') End as 'Employee Level', wh.process,
wh.clientname,wh.sub_process,wh.designation,wh.EmployeeName, pdt.EmployeeName Trainer,pdth.EmployeeName TH,
pdq.EmployeeName QA_OJT,pdqh.EmployeeName QH, pdah.EmployeeName AH,pdrt.EmployeeName RT,wh.DOJ,wh.DOB,pdqaops.EmployeeName QA_OPS 
from  status_table 
inner join whole_details_peremp wh on wh.EmployeeID = status_table.EmployeeID 
left outer join status_training on  status_training.EmployeeID = status_table.EmployeeID 
left outer join status_quality on  status_quality.EmployeeID = status_table.EmployeeID 
left outer join personal_details pdt on  wh.Trainer = pdt.EmployeeID 
left outer join personal_details pdth on  wh.TH = pdth.EmployeeID 
left outer join personal_details pdah on  wh.account_head = pdah.EmployeeID 
left outer join personal_details pdq on  wh.Quality = pdq.EmployeeID 
left outer join personal_details pdqh on  wh.QH = pdqh.EmployeeID 
left outer join personal_details pdrt on  wh.ReportTo = pdrt.EmployeeID 
left outer join personal_details pdqaops on  wh.Qa_ops = pdqaops.EmployeeID
 where ( wh.ReportTo = '".$_REQUEST['empid']."') and wh.EmployeeID != '".$_REQUEST['empid']."' and wh.cm_id ='".$_REQUEST['cm_id']."'";
				
				$myDB=new MysqliDb();
				$chk_task=$myDB->query($query);
				$counter = 0;
				$my_error= $myDB->getLastError();			
				if(count($chk_task) > 0 && $chk_task)
				{   
					
					$table='<div class="col-sm-12" style="overflow:auto;width: 100%;height: 350px;margin-top:10px;" id="tbl_div"><div class="" style=""><table id="myTable_ttnp" class="data"><thead><tr>';
					$table .='<th >EmployeeID</th>';
					$table .='<th>EmployeeName</th>';
					
					$table .='<th>Date</th>';	
					$table .='<th>Biometric Hours</th>';
                    $table .='<th>APR</th>';
                    $table .='<th>Attendance</th>';
		            
					$table .='<th >Employee Stage</th>';		
					$table .='<th >Designation</th>';
					$table .='<th >Process</th>';
					$table .='<th >Sub Process</th>';
					$table .='<th >Client</th>';
					$table .='<th >Supervisor</th>';
					$table .='</tr>';
				    $table .='</thead><tbody>';
					foreach($chk_task as $key => $value)
					{
						$EmployeeID = $value['EmployeeID'];
						$table .='<tr>';
						/*$table .='<td style="width: 150px;max-width: 150px;min-width: 150px;padding:0px;"><b>'.$value['status_table']['EmployeeID'].'</b></td>';		*/
										
						if($value['des_id'] != '9' && $value['des_id'] != '12' && $value['EmployeeID'] != $_SESSION['__user_logid'] && $value['EmployeeID'] != $_REQUEST['empid'])
						{
							$table .='<td style="white-space: nowrap;"><a onclick="javascript:return calc_Team(this);" data="'.$value['EmployeeID'].'" date="'.$DateTo.'" cm_id="'.$_REQUEST['cm_id'].'"><i class="fa fa-plus"></i> '.$value['EmployeeID'].'</a></td>';
						}
						else
						{
							$table .='<td>'.$value['EmployeeID'].'</td>';
						}	
						
						$table .='<td class="EmployeeDetail" empid="'.$value['EmployeeID'].'" style="font-weight: bold;cursor: pointer;color: #19aec4;    text-transform: uppercase;white-space: nowrap;">'.$value['EmployeeName'].'</td>';
						
						$table .='<td><b>'.$DateTo.'</b></td>';
						
						$myDB = new MysqliDb();
			            $ds_biometric = $myDB->query('select EmpID,CAST(MIN(`biopunchcurrentdata`.`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`biopunchcurrentdata`.`PunchTime`), MIN(`biopunchcurrentdata`.`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`biopunchcurrentdata`.`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` from biopunchcurrentdata where EmpID ="'.$EmployeeID.'" and DateOn = "'.$DateTo.'" group by EmployeeID,DateOn');
			            if(count($ds_biometric) > 0 && $ds_biometric )
			            {
			            	$i_bioATND1= '00:00:00';
			            	$i_bioIN1 = $ds_biometric[0]['InTime'];
			            	$i_bioOUT1  = $ds_biometric[0]['OutTime'];
							if(empty($i_bioIN1) || empty($i_bioOUT1) || !strtotime($i_bioIN1) || !strtotime($i_bioOUT1))
							{
								
							}
							else
							{
								$str1 = $DateTo.' '.$i_bioIN1;
								$str2 = $DateTo.' '.$i_bioOUT1;
								
								$iTime_in = new DateTime($str1);
								$iTime_out =new DateTime($str2);
								if($str1 <= $str2)
								{
									$interval = $iTime_in->diff($iTime_out);
								   	$i_bioATND1= date('H:i:s',strtotime($interval->format('%H').':'.$interval->format('%i').':'.$interval->format('%s'))); 
								}
								
							}
							$table .='<td><b>'.$i_bioATND1.'</b></td>';
						}
						else
						{
							$table .='<td><b>-</b></td>';	
						}
						
						if($value['des_id'] != '9' && $value['des_id'] != '12')
						{
							$table .='<td>-</td>';
						}
						else
						{
							$myDB = new MysqliDb();
				            $ds_downtime = $myDB->query('select sum(time_to_sec(TotalDT)) sec,LoginDate from downtime where EmpID ="'.$EmployeeID.'" and FAStatus ="Approve" and RTStatus ="Approve" and LoginDate = "'.$DateTo.'" group by LoginDate');
				            $DTHour = '00:00';
				            if (count($ds_downtime) > 0 && $ds_downtime)
				            {
				            	 
				                foreach($ds_downtime as $key => $val)
				                {
				                	$date_for = $val['LoginDate'];
				                	$minute = intval(($val['sec'] % 3600) / 60);
				                	if($minute <=9)
				                	{
										$minute = '0'.$minute;	
									}
				                	$DTHour = intval($val['sec']/3600).':'.$minute;
									unset($date_for);
				                }
				                unset($ds_downtime);
				            }
							
							$myDB = new MysqliDb();
							$dt_apr = $myDB->query("select D".intval(date('d',strtotime($date_check)))." from hours_hlp where EmployeeID ='".$EmployeeID."' and  Type = 'Hours' and month ='".intval(date('m',strtotime($date_check)))."' and year = '".intval(date('Y',strtotime($date_check)))."' order by id desc limit 1");
							$value1 = '';
							if(count($dt_apr) > 0 && $dt_apr)
							{
								$value1 = $dt_apr[0]['D'.intval(date('d',strtotime($date_check)))];	
							}
							
							if($DTHour != '00:00')
							{
								
								if($value1 == '-' || $value1 == '' || $value1 == null )
								{
									$APR = $DTHour ;
								}
								else
								{
									$v1 = explode(':',$value1);
									$t1 = explode(':',$DTHour);
									$dataTime1 = $v1[0] + $t1[0];
									$dataTime2 = $v1[1] + $t1[1];
									if($dataTime2 >= 60)
									{
										$dataTime1 = $dataTime1 + intval($dataTime2/60);
										$dataTime2 = ($dataTime2 % 60);
									}
									if(intval($dataTime2) <=9)
				                	{
										$dataTime2 = '0'.$dataTime2;	
									}
									$APR = $dataTime1.':'.$dataTime2;
								}
							}
							else
							{
								$APR = $value1;
							}						
							$table .='<td>'.$APR.'</td>';		
							
						}
						
						$myDB = new MysqliDb();
						$dt_atnd = $myDB->query("select D".intval(date('d',strtotime($date_check)))." from calc_atnd_master where EmployeeID ='".$EmployeeID."' and month ='".intval(date('m',strtotime($date_check)))."' and year = '".intval(date('Y',strtotime($date_check)))."' order by id desc limit 1");
						
						$ATND = '';
						if(count($dt_atnd) > 0 && $dt_atnd)
						{
							$ATND = $dt_atnd[0]["D".intval(date('d',strtotime($date_check)))];
						}
						$table .='<td>'.$ATND.'</td>';
						$table .='<td>'.$value['Employee Level'].'</td>';			
						$table .='<td>'.$value['designation'].'</td>';
						$table .='<td>'.$value['Process'].'</td>';
						$table .='<td>'.$value['sub_process'].'</td>';
						$table .='<td>'.$value['clientname'].'</td>';	
						$table .='<td>'.$value['RT'].'</td>';
						$table .='</tr>';
						
						
					}		
					$table .='</tbody></table></div></div>';
					echo $table;
				
				}
				else
				{
					$alert_msg = '<span class="text-danger">No data found</span>';
				}
			}
			
		 ?>