<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
date_default_timezone_set('Asia/Kolkata');
require_once(CLS.'MysqliDb.php');
$in=$out=$Punchin1=$Punchin2='';
$staff=0;
				$myDB=new MysqliDb();
				//$query_Select='call sp_rpt_refscheme("'.$date_From.'","'.$date_To.'")';
				//$query_Select='call sp_rpt_refscheme("'.$date_From.'","'.$date_To.'")';
				$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
				$prev_date = date('Y-m-d', strtotime($date .' -1 day'));
				$value_dc = 'D'.date('d',strtotime($prev_date));
				$value_m = date('n',strtotime($prev_date));
				$value_y = date('Y',strtotime($prev_date));
				$DateOn=$prev_date;
				//$query_Select = "select employeeid from calc_atnd_master where ".$value_dc." in('P','H','HWP','P(Biometric Issue)') and month='".$value_m."' and Year='".$value_y."'";
				$query_Select = "select distinct empid as employeeid from biopunchcurrentdata where DateOn='".$prev_date."' and left(EmpID,2) ='CE' ";//and empid in('CE10091236','CE121622565')";
				$result1=$myDB->rawQuery($query_Select);
				$my_error= $myDB->getLastError();
				if(count($result1) > 0 && $result1)
				{
					foreach($result1 as $key=>$value1)
					{
						$EmployeeID=$value1['employeeid'];	
						$staff++;
						$query_Select = "select EmpID, PunchTime from biopunchcurrentdata where EmpID= '".$value1['employeeid']."' and dateon = '".$prev_date."' order by PunchTime;";
				
				$result=$myDB->rawQuery($query_Select);
				$my_error= $myDB->getLastError();
				$count=$myDB->count;
				$i=0;
				$flag1='In';
				if(count($result) > 0 && $result){
		    	$flag='0'	;			        
		    	$Punchin1 = $Punchin2=$in=$out='';
		       foreach($result as $key=>$value)
		        {
		        	if($flag=='0' && $Punchin1=='')
				 		$Punchin1 = $value['PunchTime'];			
				 	
				 	else if($flag=='0' && $Punchin1!='' && $Punchin2 =='')
				 		$Punchin2 = $value['PunchTime'];
				 		
				 	if($Punchin1 !='' && $Punchin2!='')
				 	{
						if(strtotime($Punchin2) - strtotime($Punchin1)> intval(120))
						{
							if($in=='')
							{
								if($out !='')
								{
									$in=$Punchin1;
									/*if(strtotime($Punchin2) - strtotime($Punchin1)> intval(0))
									{
										
									}
									else
									{
										$in=$Punchin2;
									}*/
									
								}
								else
								{
									$in=$Punchin1;
								}
								$out='';
								$flag='0';
								$Punchin1=$Punchin2;
								$Punchin2='';
								
								$myDB=new MysqliDb();
																
								$sqlInsertDoc='call InsertBioInOut("'.$EmployeeID.'","'.$DateOn.'","'.$in.'","In")';
								$flag1 = 'Out';
								$result=$myDB->query($sqlInsertDoc);
							}
							else if($out=='')
							{
								if(strtotime($Punchin1) - strtotime($in)> intval(120))
								{
									$out=$Punchin1;
									$Punchin1=$Punchin2;
									$Punchin2='';
									
								}
								else
								{
									$out=$Punchin2;
									$Punchin1=$out;
								}
								
								$myDB=new MysqliDb();
																
								$sqlInsertDoc='call InsertBioInOut("'.$EmployeeID.'","'.$DateOn.'","'.$out.'","Out")';
								$result=$myDB->query($sqlInsertDoc);
								$flag1 = 'In';
								$in='';
								$Punchin2='';
								$flag=='0';
							}
						}
						else
						{
							$Punchin2='';
							$flag=='0';
						}
						 
					}	
					$i++;
					if($i == $count)
					{
						
						$myDB=new MysqliDb();
																
						$sqlInsertDoc='call InsertBioInOut("'.$EmployeeID.'","'.$DateOn.'","'.$value['PunchTime'].'","'.$flag1.'")';
						$result=$myDB->query($sqlInsertDoc);
					}
				}	
				
				
				
				}
					$EmployeeID="";
					}
				}
				
				
				
				
				
echo 'Staff  ::  => '.$staff;
//echo '<br /> <br />Other  ::  => '.$other;



?>