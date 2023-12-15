<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', '1');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response = array();
$response['msg']='';
if(isset($Data) && count($Data) > 0 )
	{
		if(isset($Data['appkey']) && $Data['appkey']=="getdowntimedata")
		{
			$EmployeeID=$Data['EmployeeID'];	
		    $dt_id=0;	
			$adt=$Data['totalDT'];	
			$LoginDate=$Data['LoginDate'];	
			$RequestType=$Data['RequestType'];	// OJT , Client Training ,Buddy Support ,Nestor
			$myDB = new MysqliDb();				
		$dt_time = $myDB->query("select sum(time_to_sec(TotalDT)) sec from downtime where EmpID ='".$EmployeeID."' and (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= '".date("Y-m-d",strtotime($LoginDate))."'  and id not in ('".$dt_id."')");
		$dt_totaltime =0;
		if(count($dt_time) > 0 && $dt_time)
		{
				$dt_time = $dt_time[0]['sec'];
		}
		if(empty($dt_time))
		{
			$dt_time = 0;
		}
		
		$adt = date('H:i:s',strtotime('+'.$dt_time.' seconds'.$adt));	
		$ct_max = date('H:i:s',strtotime('08:00:00'));
		if($adt > $ct_max)
		{
			$response['msg']='There must be a downtime Type.We found a invalid value in this field.';
		    $response['status']=0;
		}
		else
		{
				$response = check_validation($EmployeeID,$RequestType,$LoginDate,$adt,$dt_id);
		}	
       }
      else
         {
		   $response['msg']='Key not found.';
		   $response['status']=0;
	     }
	}
		else
          {
				$response['msg']='Invalid Request';
				$response['status']=0;
	      }
echo json_encode($response);

    function check_validation($EmployeeID,$RequestType,$LoginDate,$adt,$dt_id )
	{
		$response['msg'] = '';
		
		if($RequestType == 'Client Training')
		{
			$myDB = new MysqliDb();
			 $dt_validate  =$myDB->query('select distinct client_training,client_time_ttl,client_time_min,client_time_max from downtime_time_master where  client_training ="Yes" and cm_id in (select cm_id from employee_map where EmployeeID ="'.$EmployeeID.'")');
			if(count($dt_validate) > 0 && $dt_validate)
			{
				$myDB = new MysqliDb();				
				$dt_time = $myDB->query("select sum(time_to_sec(TotalDT)) sec from downtime where EmpID ='".$EmployeeID."' and Request_type = '".$RequestType."' and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m')= '".date("Y-m",strtotime($LoginDate))."' and id not in ('".$dt_id."')");
				$dt_totaltime =0;
				if(count($dt_time) > 0 && $dt_time)
				{
						$dt_time = $dt_time[0]['sec'];				
				}
				if(empty($dt_time))
				{
					$dt_time = 0;
				}
				$seconds=0;
				$time = date('H:i:s',strtotime($adt));
				$parsed = date_parse($time);
				$error = $parsed['errors'];
				
				
				//Converting The Subtract Time String into Time String.
		/*		$timeToSubtract = date('H:i',strtotime($dtToSubtrct));
				$parsedSubTime = date_parse($timeToSubtract);*/
				if(empty($error))
				{
					/*$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'] - ($parsedSubTime['hour'] * 3600 + $parsed['minute'] * 60);*/
					$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];		
				}
				else
				{
					$response['msg']='There we get a invalid Downtime value,Try agian.';
		            $response['status']=0;
		            return $response;
				}
				
				
				if(empty($seconds))
				{
					$seconds = 0;
				}
				// sum current downtime to total;
			
			 $dt_time = $dt_time + $seconds;
				
				//convert total limit to seconds
				$dt_lim = 0;
				if(!empty($dt_validate[0]['client_time_ttl']))
				{
					$parsed = explode(":",$dt_validate[0]['client_time_ttl']);
					
					 $dt_lim = $parsed[0] * 3600 + $parsed[1] * 60 + $parsed[2];
					
					if(empty($dt_lim))
					{
						$dt_lim = 0;
					}
				}
				
				if($dt_time <= $dt_lim)
				{
						
					$myDB = new MysqliDb();				
					$dt_time = $myDB->query("select sum(time_to_sec(TotalDT)) sec from downtime where EmpID ='".$EmployeeID."' and Request_type = '".$RequestType."' and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= '".date("Y-m-d",strtotime($LoginDate))."' and id not in ('".$dt_id."')");
					$dt_totaltime =0;
					if(count($dt_time) > 0 && $dt_time)
					{
							$dt_time = $dt_time[0]['sec'];
					}
					if(empty($dt_time))
					{
						$dt_time = 0;
					}
					
					$adt = date('H:i:s',strtotime('+'.$dt_time.' seconds '.$adt));					
					$ct_min = date('H:i:s',strtotime($dt_validate[0]['client_time_min']));
					$ct_max = date('H:i:s',strtotime($dt_validate[0]['client_time_max']));
					if($adt >= $ct_min  && $adt <= $ct_max)
					{
						$response['msg']='Request  Submitted .';
		                $response['status']=1;
		                return $response;
					}
					else
					{
						$response['msg']='Downtime should be between client permitted time [ MINIMUM - '.$ct_min.' HOURS, MAXIMUM - '.$ct_max.' HOURS ] for the day.';
		                $response['status']=0;
		                return $response;
					}
				}
				else
				{
					   $response['msg']='Current Downtime is more ('.($dt_time - $dt_lim).' Seconds) then permitted time by client.';
		                $response['status']=0;
		                return $response;
				}
				
				
			}
			else
			{
				$response['msg']='Downtime request type is not valid for your client.';
		        $response['status']=0;
		        return $response;
			}
		}
		elseif($RequestType == 'OJT')
		{
			$myDB=new MysqliDb();
			$data_count_WO = $myDB->query("select count(*) as `count` from roster_temp where EmployeeID = '".$EmployeeID."' and DateOn = '".$LoginDate."' and (InTime like '%WO%' or OutTime like '%WO%')");
			//$myError = mysql_error();
			if(count($data_count_WO) > 0 && $data_count_WO)
			{
				$data_count_WO = $data_count_WO[0]['count'];
				if($data_count_WO > 0 )
				{
					$response['msg']='Request Not Submitted : found rostered weekoff (WO) on Login Date.';
		            $response['status']=0;
		            return $response;
				}
			}
			$myDB = new MysqliDb();
			$dt_validate  = $myDB->query('select distinct ojt_days, ojt_day_1, ojt_day_2, ojt_day_3, ojt_day_4, ojt_day_5, ojt_day_6, ojt_day_7, ojt_day_8, ojt_day_9, ojt_day_10, ojt_day_11, ojt_day_12, ojt_day_13, ojt_day_14, ojt_day_15, ojt_day_16, ojt_day_17, ojt_day_18, ojt_day_19, ojt_day_20 from downtime_time_master where  cm_id in (select cm_id from employee_map where EmployeeID ="'.$EmployeeID.'")');
			if(count($dt_validate) > 0 && $dt_validate)
			{	
				$myDB = new MysqliDb();
				$OJT_date = $myDB->query('select cast(InOJT as date) InOJT from status_table where EmployeeID = "'.$EmployeeID.'"');	
				if(count($OJT_date) > 0 && date('Y-m-d',strtotime($OJT_date[0]['InOJT'])) <= date('Y-m-d',strtotime($LoginDate)))
				{
					
					$OJT_date = $OJT_date[0]['InOJT'];
					$datetime1 = new DateTime($OJT_date);
					$datetime2 = new DateTime($LoginDate);
					$interval = $datetime1->diff($datetime2);
					$diffdays = $interval->format('%R%a') + 1;
					
					$sql_roster = "select count(*) as `count`  from roster_temp where EmployeeID = '".$EmployeeID."' and (InTime like '%WO%' or OutTime like '%WO%') and  DateOn between '".$OJT_date."' and '".$LoginDate."'  limit 1";
			
					$myDB = new MysqliDb();
					$dt_days = $myDB->query($sql_roster);
					$dt_days = $dt_days[0]['count'];
					if(empty($dt_days) || !$dt_days)
					{
						$dt_days = 0;
					}
			
					if($diffdays > 0)
					{
						if($diffdays >= 20)
						{
							$diffdays = 20;							
						}
						$diffdays = intval($diffdays) - intval($dt_days);
					 	$timeValue = date("H:i:s",strtotime($dt_validate[0]['ojt_day_'.$diffdays]));
						 $actualTime  = date("H:i:s",strtotime($adt));
						if($actualTime > $timeValue)
						{
							$response['msg']='Current Downtime should not be more then '.$timeValue.'.';
		                    $response['status']=0;
		                    return $response;
						}
						else
						{
							
							// get total Day Downtime
							$myDB = new MysqliDb();				
							 $dt_time = $myDB->query("select sum(time_to_sec(TotalDT)) sec from downtime where EmpID ='".$EmployeeID."' and Request_type = '".$RequestType."' and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= '".date("Y-m-d",strtotime($LoginDate))."' and id not in ('".$dt_id."')");
							$dt_totaltime =0;
							if(count($dt_time) > 0 && $dt_time)
							{
									$dt_time = $dt_time[0]['sec'];
							}
							if(empty($dt_time))
							{
								$dt_time = 0;
							}
						//	echo $dt_time;
							// calculation current downtime in seconds
							$seconds=0;
							$time = date('H:i:s',strtotime($adt));
							$parsed = date_parse($time);
							$error = $parsed['errors'];
							if(empty($error))
							{
								$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];	
							}
							else
							{
								$response['msg']='There we get a invalid Downtime value,Try agian.';
		                        $response['status']=0;
		                        return $response;
							}
							if(empty($seconds))
							{
								$seconds = 0;
							}
							$dt_time = $dt_time + $seconds;
							$dt_lim = 0;
							if(strtotime($timeValue))
							{
								$time = date('H:i:s',strtotime($timeValue));
								$parsed = date_parse($time);
								$error = $parsed['errors'];
								if(empty($error))
								$dt_lim = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];	
							}
							if(empty($dt_lim))
							{
								$dt_lim = 0;
							}
							if($dt_time > $dt_lim)
							{
								$response['msg']='Current Downtime is more ('.gmdate('H:i:s', ($dt_time - $dt_lim)).' HOURS) than permitted time by client.';
		                        $response['status']=0;
		                        return $response;
							}else {
								
								$response['msg']='Request  Submitted .';
		                        $response['status']=1;
		                        return $response;
							}
						}
						
					}
					else
					{
						$response['msg']='Downtime request type is not valid for your client .';
		                $response['status']=0;	
		                return $response;
					}
				}
				else
				{
					$response['msg']='Downtime request type is not valid for your client .';
		            $response['status']=0;
		            return $response;
				}
				
			}
			else
			{
				    $response['msg']='Downtime request type is not valid for your client.';
		            $response['status']=0;
		            return $response;
			}
		}
		
		elseif($RequestType == 'Buddy Support')
		{
			
			$myDB = new MysqliDb();
			$dt_validate  = $myDB->query('select Min_time,Max_Time from buddy_dtmatrix where cm_id in (select cm_id from employee_map where employeeID="'.$EmployeeID.'")');
				
				if(count($dt_validate) > 0 && $dt_validate)
				{
					$myDB = new MysqliDb();				
					$dt_time = $myDB->query("select sum(time_to_sec(TotalDT)) sec from downtime where EmpID ='".$EmployeeID."' and Request_type = '".$RequestType."' and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= '".date("Y-m-d",strtotime($LoginDate))."'  and id not in ('".$dt_id."')");
					$dt_totaltime =0;
					if(count($dt_time) > 0 && $dt_time)
						{
							$dt_time = $dt_time[0]['sec'];
						}
							if(empty($dt_time))
							{
								$dt_time = 0;
							}
							$seconds=0;
							$time = date('H:i:s',strtotime($adt));
							$parsed = date_parse($time);
							$error = $parsed['errors'];
							if(empty($error))
							{
								$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];	
							}
							else
							{
								$response['msg']='There we get a invalid Downtime value,Try agian.';
		                        $response['status']=0;
		                        return $response;
							}
							if(empty($seconds))
							{
								$seconds = 0;
							}
							$dt_time = $dt_time + $seconds;
							
							$MinValue = date("H:i:s",strtotime($dt_validate[0]['Min_time']));
							$MaxValue = date("H:i:s",strtotime($dt_validate[0]['Max_Time']));
							$dt_lim_min = 0;
							if(strtotime($MinValue))
							{
								$time = date('H:i:s',strtotime($MinValue));
								$parsed = date_parse($time);
								$error = $parsed['errors'];
								if(empty($error))
								$dt_lim_min = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];	
							}
							if(empty($dt_lim_min))
							{
								$dt_lim_min = 0;
							}
							if($dt_time < $dt_lim_min)
							{
								$response['msg']='Current Downtime for Buddy Support should not be less then'.$MinValue.'.';
		                        $response['status']=0;
		                        return $response;
							}
							else
							{
								$dt_lim_max = 0;
								if(strtotime($MaxValue))
								{
									$time = date('H:i:s',strtotime($MaxValue));
									$parsed = date_parse($time);
									$error = $parsed['errors'];
									if(empty($error))
									$dt_lim_max = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];	
								}
								if(empty($dt_lim_max))
								{
									$dt_lim_max = 0;
								}
								
								if($dt_time > $dt_lim_max)
								{
									$response['msg']='Current Downtime for Buddy Support should not be more then '.$MaxValue.'.';
		                            $response['status']=0;
		                            return $response;
								}
								else{
									$response['msg']='Request Submitted';
		                            $response['status']=1;
		                            return $response;
								}
							
							}
							
							
					}		
			}
				
		elseif($RequestType == 'Nestor')
		{
			$myDB = new MysqliDb();				
			$dt_time = $myDB->query("select sum(time_to_sec(TotalDT)) sec from downtime where EmpID ='".$EmployeeID."' and Request_type = '".$RequestType."' and  (FAStatus != 'Decline' or RTStatus !='Decline') and date_format(LoginDate,'%Y-%m-%d')= '".date("Y-m-d",strtotime($LoginDate))."'  and id not in ('".$dt_id."')");
			$dt_totaltime =0;
			if(count($dt_time) > 0 && $dt_time)
			{
					$dt_time = $dt_time[0]['sec'];
			}
			if(empty($dt_time))
			{
				$dt_time = 0;
			}
			// calculation current downtime in seconds
			$seconds=0;
			$time = date('H:i:s',strtotime($adt));
			$parsed = date_parse($time);
			$error = $parsed['errors'];
			if(empty($error))
			{
				$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];	
			}
			else
			{
				    $response['msg']='There we get a invalid Downtime value,Try agian.';
		            $response['status']=0;
		            return $response;
			}
			if(empty($seconds))
			{
				$seconds = 0;
			}
			$dt_lim = 25200;
			$dt_time = $dt_time + $seconds;
			if($dt_time > $dt_lim)
			{
				    $response['msg']='Current Downtime is more ('.($dt_time - $dt_lim).' Seconds)then permitted time (7 Hours ) by client.';
		            $response['status']=0;
		            return $response;
			}
			else
			{
				$response['msg']='Request  Submitted .';
		        $response['status']=1;
		        return $response;
			}
			
		}
   elseif($RequestType == 'IT')
		{
			$response['msg']='Request  Submitted.';
		    $response['status']=1;
		    return $response;
		}
		
		else
		{
			$response['msg']='Request Type should not be blank.';
		    $response['status']=0;
		    return $response;
		}
		return $response;
	}
?>
