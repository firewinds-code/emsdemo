<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

	function settimestamp($module,$type)
	{
		$myDB=new MysqliDb();
		$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."') ;";
		$myDB->query($sq1);
	}
//settimestamp('calc_apr_one','Start');
	 if(isset($_REQUEST['date']) && isset($_REQUEST['type']))
	 {
	 	if(strtoupper($_REQUEST['type']) == 'ONE')
	 	{
			$cal = new calc_apr($_REQUEST['empid'], $_REQUEST['date']);
		}
		else if(strtoupper($_REQUEST['type']) == 'BKTBL')
	 	{
	 		$cal_total_user = 0;
			$strt = "select whole_dump_emp_data.EmployeeID from whole_dump_emp_data inner join backdated_calc_tab on whole_dump_emp_data.EmployeeID = backdated_calc_tab.EmployeeID";
			$myDB = new  MysqliDb();
			$rstl_employees = $myDB->query($strt);
			if($rstl_employees && count($rstl_employees) > 0)
			{
				foreach($rstl_employees as $glob_key=>$glob_val)
				{
				
					if(isset($glob_val['EmployeeID']))
					{
						$cal = new calc_apr($glob_val['EmployeeID'], $_REQUEST['date']);
						$cal_total_user ++;
					}
	
				}
			}
			
		}
		
	 } 
	 
	 class calc_apr
	 {
	 	public function __construct($EmployeeID,$Date)
	 	{
	 		/*$sql = "select cosmo_ID from cosmo_user_mapping where empid= '".$EmployeeID."' ";	
	 		$myDB = new MysqliDb();
	 		$ds_cosmoid = $myDB->query($sql);*/
	 		/*if(empty($mysql_error) && count($ds_cosmoid)>0)
	 		{*/
				$date = date('Y-m-d',strtotime($Date));
				
				//Get Roster by cosmo ID and date
				$myDB=new MysqliDb();
				$Query="select  EmployeeID,InTime,OutTime,DateOn from roster_temp where EmployeeID='".$EmployeeID."' and dateon=cast('".$date."' as date) limit 1;";
				$res =$myDB->query($Query);
				if($res)
				{
					foreach($res as $key=>$value)																															{
						$RosterIn=$value['InTime'];
						$RosterOut=$value['OutTime'];
						$EmployeeID=$value['EmployeeID'];
		   			}
				
					$tmpcalc1 = $tmpcalc2 = $tmpcalc3 = 0;
					$sql_1="SELECT ActionTime,SysHours,`Release` from CCSPAPR_AgentManagement where  cast(ActionTime as date)=cast('".$date."' as date) and EmpID='".$EmployeeID."' order by ActionTime;";// 
					$ds_apr = $myDB->rawQuery($sql_1);
				
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
									
												/*$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
												$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2*/
												if($ActionTime >=$Roster_In && $ActionTime <= $Roster_Out && $SysHours!='0')
												{
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
							$sql_1='call insert_cosmo_hour("'.$ds_empid[0]["empid"].'","'.$day.'","'.$tmpcalc3.'","'.date("n",strtotime($date)).'","'.date("Y",strtotime($date)).'")';
							$flag = $myDB->rawQuery($sql_1);
							$error = $myDB->getLastError();
							
							if(empty($error))
							{
								$count++;
							}
							
							}
					else
					{
						Echo 'less';
					}
					echo '<br/>'.$tmpcalc3.'<br/>';
								
				
				
				}
				
			//}
	 	}
	 }
	

?>