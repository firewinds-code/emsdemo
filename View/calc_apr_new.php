<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();

	try
	{
		
	
		function settimestamp($module,$type)
		{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."') ;";
			$myDB->query($sq1);
		}
		settimestamp('calc_apr','Start');
		
		$date = date('Y-m-d',strtotime("-1 days"));
		
		//$date ='2021-03-17';
		//$date =date('Y-m-d',time());
		
		$count = 0;
		//////////////
		$sql_1="SELECT distinct EmpID from CCSPAPR_AgentManagement where cast(ActionTime as date)=cast('".$date."' as date)";
		$ds_agentid = $myDB->rawQuery($sql_1);
		
		if(empty($mysql_error) && !empty($ds_agentid))
		{
			$EmployeeID="";
			foreach($ds_agentid as $key=>$value)
			{
				/*$sql_1="select empid from cosmo_user_mapping where cosmo_ID='".$value["EmpID"]."'";
				$ds_empid = $myDB->rawQuery($sql_1);*/
				
				/*if(empty($mysql_error) && !empty($ds_empid))
				{*/
					echo $sql_1="select InTime,OutTime,type_ from roster_temp where EmployeeID='".$value["EmpID"]."' and DateOn= '".$date."'"; echo "<br/>";	
					$ds_roster = $myDB->rawQuery($sql_1);
				
					if(empty($mysql_error) && !empty($ds_roster))
					{
						$RosterFlag = 0;
						$RosterIn=$ds_roster[0]['InTime'];
						$RosterOut=$ds_roster[0]['OutTime'];
						$RosterType=$ds_roster[0]['type_'];
						
						$i_rin_tmp = date('H:i:s',strtotime($RosterIn));
						$i_rout_tmp = date('H:i:s',strtotime($RosterOut));
						
						if($i_rin_tmp >= "15:00:00" && $RosterType == 1 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp)
						{
							$RosterFlag = 1;
						}
						elseif($i_rin_tmp >= "13:00:00" && $RosterType == 2 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp)
						{
							$RosterFlag = 1;
						}
						elseif($i_rin_tmp >= "19:00:00" && $RosterType == 3 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp)
						{
							$RosterFlag = 1;
						}
						/*else
						{
							$RosterFlag = 0;
						}*/
						
						//echo $value["EmpID"].' - '.$RosterFlag. '----';
						
						if($RosterFlag == 0)
						{
							if($RosterIn!='' && $RosterOut!='' && is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut)))
							{
								$sql_1="SELECT ActionTime,SysHours,`Release` from CCSPAPR_AgentManagement where  cast(ActionTime as date)=cast('".$date."' as date) and EmpID='".$value["EmpID"]."' order by ActionTime;";// 
								$ds_apr = $myDB->rawQuery($sql_1);
								$tmpcalc1 = $tmpcalc2 = $tmpcalc3 = 0;
								if(empty($mysql_error) && !empty($ds_apr))
								{
									foreach($ds_apr as $key=>$value1)
									{
										try
										{
											$ActionTime = $value1['ActionTime'];
											$SysHours = $value1['SysHours'];
											$Release = $value1['Release'];
											
											$flg=$exc_flg=$AssumptionCount=0;
						
											if(strlen($ActionTime)>0)
											{
												if(is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut)))
												{
													$Roster_In=date('Y-m-d',strtotime($date)).' '.date('H',strtotime($RosterIn)).":00:00";
													$Roster_Out=date('Y-m-d',strtotime($date)).' '.date('H',strtotime($RosterOut)).":00:00";
													
													if(date('i',strtotime($RosterOut))=='00')
												 	{
												 		$Roster_Out = date("Y-m-d H:i:s", strtotime('-1 hours', strtotime($Roster_Out)));
												 	}
									
													//$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
													//$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2
													if($ActionTime >=$Roster_In && $ActionTime <= $Roster_Out && $SysHours!='0')
													{
														//echo "<br/>". $value["EmpID"]. "----" .$ActionTime;
														$tmpcalc1 = ($SysHours*$Release)/100;
														$tmpcalc2 = $SysHours - $tmpcalc1;
														$tmpcalc3 = $tmpcalc3 + $tmpcalc2;
													}
																					
																 
							 					}
											}
					
										}
										catch(Exception $ex)
										{
											echo 'Message: ' .$ex->getMessage(). '<br/>';
										}
									}
							
									$day = 'D'. date("j",strtotime($date));
									echo $sql_1='call insert_cosmo_hour("'.$value["EmpID"].'","'.$day.'","'.$tmpcalc3.'","'.date("n",strtotime($date)).'","'.date("Y",strtotime($date)).'")'; echo "<br/>";
									$flag = $myDB->rawQuery($sql_1);
									$error = $myDB->getLastError();
									
									if(empty($error))
									{
										$count++;
									}
							
								}
							}
						}
						
						else
						{
							$date1 = date('Y-m-d',strtotime($date.' -1 days'));
							$sql_1="select InTime,OutTime,type_ from roster_temp where EmployeeID='".$value["EmpID"]."' and DateOn= '".$date1."'";	
							$ds_roster = $myDB->rawQuery($sql_1);
							
							if(empty($mysql_error) && !empty($ds_roster))
							{
								$RosterFlag = 0;
								$RosterIn=$ds_roster[0]['InTime'];
								$RosterOut=$ds_roster[0]['OutTime'];
								$RosterType=$ds_roster[0]['type_'];
								
								$i_rin_tmp = date('H:i:s',strtotime($RosterIn));
								$i_rout_tmp = date('H:i:s',strtotime($RosterOut));
								
								if($i_rin_tmp >= "15:00:00" && $RosterType == 1 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp)
								{
									$RosterFlag = 1;
								}
								elseif($i_rin_tmp >= "13:00:00" && $RosterType == 2 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp)
								{
									$RosterFlag = 1;
								}
								elseif($i_rin_tmp >= "19:00:00" && $RosterType == 3 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp)
								{
									$RosterFlag = 1;
								}
								
								if($RosterFlag == 0)
								{
									if($RosterIn!='' && $RosterOut!='' && is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut)))
									{
										$sql_1="SELECT ActionTime,SysHours,`Release` from CCSPAPR_AgentManagement where  cast(ActionTime as date)=cast('".$date."' as date) and EmpID='".$value["EmpID"]."' order by ActionTime;";// 
										$ds_apr = $myDB->rawQuery($sql_1);
										$tmpcalc1 = $tmpcalc2 = $tmpcalc3 = 0;
										if(empty($mysql_error) && !empty($ds_apr))
										{
											foreach($ds_apr as $key=>$value1)
											{
												try
												{
													$ActionTime = $value1['ActionTime'];
													$SysHours = $value1['SysHours'];
													$Release = $value1['Release'];
													
													$flg=$exc_flg=$AssumptionCount=0;
								
													if(strlen($ActionTime)>0)
													{
														if(is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut)))
														{
															$Roster_In=date('Y-m-d',strtotime($date)).' '.date('H',strtotime($RosterIn)).":00:00";
															$Roster_Out=date('Y-m-d',strtotime($date)).' '.date('H',strtotime($RosterOut)).":00:00";
															
															if(date('i',strtotime($RosterOut))=='00')
														 	{
														 		$Roster_Out = date("Y-m-d H:i:s", strtotime('-1 hours', strtotime($Roster_Out)));
														 	}
											
															//$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
															//$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2
															if($ActionTime >=$Roster_In && $ActionTime <= $Roster_Out && $SysHours!='0')
															{
																//echo "<br/>". $value["EmpID"]. "----" .$ActionTime;
																$tmpcalc1 = ($SysHours*$Release)/100;
																$tmpcalc2 = $SysHours - $tmpcalc1;
																$tmpcalc3 = $tmpcalc3 + $tmpcalc2;
															}
																							
																		 
									 					}
													}
					
												}
												catch(Exception $ex)
												{
													echo 'Message: ' .$ex->getMessage(). '<br/>';
												}
											}
							
									$day = 'D'. date("j",strtotime($date));
									echo $sql_1='call insert_cosmo_hour("'.$value["EmpID"].'","'.$day.'","'.$tmpcalc3.'","'.date("n",strtotime($date)).'","'.date("Y",strtotime($date)).'")'; echo "<br/>";
									$flag = $myDB->rawQuery($sql_1);
									$error = $myDB->getLastError();
									
									if(empty($error))
									{
										$count++;
									}
							
								}
									}
								}
								
								else
								{
									if($RosterIn!='' && $RosterOut!='' && is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut)))
									{
									
										echo $sql_1="SELECT ActionTime,SysHours,`Release` from CCSPAPR_AgentManagement where  (cast(ActionTime as date) between cast('".$date1."' as date) and cast('".$date."' as date)) and EmpID='".$value["EmpID"]."' order by ActionTime;";
										$ds_apr = $myDB->rawQuery($sql_1);
										$tmpcalc1 = $tmpcalc2 = $tmpcalc3 = 0;
										if(empty($mysql_error) && !empty($ds_apr))
										{
											foreach($ds_apr as $key=>$value1)
											{
												try
												{
													$ActionTime = $value1['ActionTime'];
													$SysHours = $value1['SysHours'];
													$Release = $value1['Release'];
													
													$flg=$exc_flg=$AssumptionCount=0;
								
													if(strlen($ActionTime)>0)
													{
														if(is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut)))
														{
															$Roster_In=date('Y-m-d',strtotime($date1)).' '.date('H',strtotime($RosterIn)).":00:00";
															$Roster_Out=date('Y-m-d',strtotime($date)).' '.date('H',strtotime($RosterOut)).":00:00";
															
															if(date('i',strtotime($RosterOut))=='00')
														 	{
														 		$Roster_Out = date("Y-m-d H:i:s", strtotime('-1 hours', strtotime($Roster_Out)));
														 	}
									
															//$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
															//$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2
															if($ActionTime >=$Roster_In && $ActionTime <= $Roster_Out && $SysHours!='0')
															{
																//echo "<br/>". $value["EmpID"]. "----" .$ActionTime;
																$tmpcalc1 = ($SysHours*$Release)/100;
																$tmpcalc2 = $SysHours - $tmpcalc1;
																$tmpcalc3 = $tmpcalc3 + $tmpcalc2;
															}
																					
																 
									 					}
													}
					
												}
												catch(Exception $ex)
												{
													echo 'Message: ' .$ex->getMessage(). '<br/>';
												}
											}
							
											$day = 'D'. date("j",strtotime($date1));
											echo $sql_1='call insert_cosmo_hour("'.$value["EmpID"].'","'.$day.'","'.$tmpcalc3.'","'.date("n",strtotime($date1)).'","'.date("Y",strtotime($date1)).'")'; echo "<br/>";
											$flag = $myDB->rawQuery($sql_1);
											$error = $myDB->getLastError();
											
											if(empty($error))
											{
												$count++;
											}
							
										}
								
									}
								}
							}
							
							
						}
					
					}
				//}
			}
			
			echo $count . ' Record Updated.';
			
		}
		else
		{
			Echo 'less';
		}
	
		settimestamp('calc_apr','END');
	}
	catch(Exception $ex)
	{
		echo 'Message: ' .$ex->getMessage(). '<br/>';
	}

?>