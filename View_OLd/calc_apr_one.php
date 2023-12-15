<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

function settimestamp($module, $type)
{
	$myDB = new MysqliDb();
	$conn = $myDB->dbConnect();
	$sq1 = "insert into scheduler(modulename,type)values(?,?) ;";
	$ins = $conn->prepare($sq1);
	$ins->bind_param("ss", $module, $type);
	$ins->execute();
	$result = $ins->get_result();
	// $myDB->query($sq1);
}
//settimestamp('calc_apr_one','Start');
if (isset($_REQUEST['date']) && isset($_REQUEST['type'])) {
	if (strtoupper($_REQUEST['type']) == 'ONE') {
		$cal = new calc_apr($_REQUEST['empid'], $_REQUEST['date']);
	} else if (strtoupper($_REQUEST['type']) == 'BKTBL') {
		$cal_total_user = 0;
		$strt = "select whole_dump_emp_data.EmployeeID from whole_dump_emp_data inner join backdated_calc_tab on whole_dump_emp_data.EmployeeID = backdated_calc_tab.EmployeeID";
		$myDB = new  MysqliDb();
		$rstl_employees = $myDB->query($strt);
		if ($rstl_employees && count($rstl_employees) > 0) {
			foreach ($rstl_employees as $glob_key => $glob_val) {

				if (isset($glob_val['EmployeeID'])) {
					$cal = new calc_apr($glob_val['EmployeeID'], $_REQUEST['date']);
					$cal_total_user++;
				}
			}
		}
	}
}

class calc_apr
{
	public function __construct($EmployeeID, $Date)
	{
		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$sql = "select cosmo_ID from cosmo_user_mapping where empid= ? ";
		$selectQy = $conn->prepare($sql);
		$selectQy->bind_param("s", $EmployeeID);
		$selectQy->execute();
		$res = $selectQy->get_result();
		$ds_cosmoid = $res->fetch_row();
		// $ds_cosmoid = $myDB->query($sql);
		if ($res && $res->num_rows > 0) {
			$date = date('Y-m-d', strtotime($Date));
			//$date ='2019-08-02';
			//////////////
			$agent_id = $ds_cosmoid[0];
			$sql_1 = "SELECT * FROM CCSPAPR_final where  cast(LoggedIn as date)=cast('" . $date . "' as date) and Agent_ID=? ;";
			$selectQy = $conn->prepare($sql_1);
			$selectQy->bind_param("i", $agent_id,);
			$selectQy->execute();
			$result = $selectQy->get_result();
			$ds_apr = $result->fetch_row();
			// $ds_apr = $myDB->rawQuery($sql_1);
			// var_dump($ds_apr);
			if ($result && $result->num_rows > 0) {
				// for ($i = 0; $i < count($ds_apr); $i++) {
				while ($ds_apr = mysqli_fetch_assoc($result)) {
					try {

						$Process_Name = $ds_apr['Process_Name'];
						$FirstLogOut = $ds_apr['FirstLogOut'];
						$AgentGlobal_ID = $ds_apr['AgentGlobal_ID'];
						$Agent_ID = $ds_apr['Agent_ID'];
						$AgentFirstName = $ds_apr['AgentFirstName'];
						$AgentLastName = $ds_apr['AgentLastName'];
						$Available = $ds_apr['Available'];
						$Talk = $ds_apr['Talk'];
						$WrapUp = $ds_apr['WrapUp'];
						$Release = $ds_apr['Release'];
						$Hold = $ds_apr['Hold'];
						$Other = $ds_apr['Other'];
						$Calls = $ds_apr['Calls'];
						$ASA = $ds_apr['ASA'];
						$MaxSpeedAns = $ds_apr['MaxSpeedAns'];
						$AprIn = $ds_apr['LoggedIn']; // $LoggedIn1
						$AprOut = $ds_apr['TimeTo']; // $logout1

						//Get Roster by cosmo ID and date
						// $myDB = new MysqliDb();
						$Query = "select  EmployeeID,InTime,OutTime,DateOn from roster_temp r  inner join cosmo_user_mapping c on r.EmployeeID=c.empid where c.cosmo_ID=? and cast(r.DateOn as date)=cast(? as date);";
						$selectQry = $conn->prepare($Query);
						$selectQry->bind_param("is", $Agent_ID, $date);
						$selectQry->execute();
						$res = $selectQry->get_result();
						// $res = $myDB->query($Query);
						if ($res->num_rows > 0) {
							foreach ($res as $key => $value) {
								$RosterIn = $value['InTime'];
								$RosterOut = $value['OutTime'];
								$EmployeeID = $value['EmployeeID'];
							}
						} else {
							$RosterIn = '00:00';
							$RosterOut = '00:00';
						}


						$flg = $exc_flg = $AssumptionCount = 0;

						if (strlen($AprIn) > 0 && strlen($AprOut) > 0) {
							if (is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut))) {

								$Roster_In = date('Y-m-d', strtotime($AprIn)) . ' ' . $RosterIn . ':00'; // $LoggedIn2
								$Roster_Out = date('Y-m-d', strtotime($AprOut)) . ' ' . $RosterOut . ':00'; // $logout2

								if (strtotime($AprIn) <= strtotime($Roster_In)) {
									$Calculation_In = $Roster_In;
								} else {
									$Calculation_In = $AprIn;
								}
								if (strtotime($AprOut) < strtotime($Roster_Out)) {
									$Calculation_Out = $AprOut;
								} else {
									$Calculation_Out = $Roster_Out;

									/*$dt1 = new DateTime ($AprOut);
										$dt2 = new DateTime ($Roster_Out);
										$diffInSeconds = $dt1->getTimestamp() - $dt2->getTimestamp();
										if($diffInSeconds <= 600)
										{
											$Calculation_Out=$AprOut;
										}
										else
										{
											$Calculation_Out=date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($Roster_Out)));
											
										}*/
								}

								if (strtotime($AprIn) > strtotime($Roster_Out)) {
									$flg = 1;
								} else if (strtotime($AprIn) < strtotime($Roster_In) && (strtotime($AprOut) < strtotime($Roster_In))) {
									$flg = 1;
								}

								$Duration = strtotime($Calculation_Out) - strtotime($Calculation_In);

								if ($flg == 0) {

									$sql = "SELECT starttime,endtime FROM ccspapr_break where agent_id = ? and  starttime >= ? and endtime <= ? order by starttime ;";
									$select = $conn->prepare($sql);
									$select->bind_param("iss", $Agent_ID, $AprIn, $AprOut);
									$select->execute();
									$ds_break = $select->get_result();
									$ds_breaks = $ds_break->fetch_row();
									// $ds_break = $myDB->rawQuery($sql);
									// var_dump($ds_apr);
									if ($ds_break && $ds_break->num_rows > 0) {
										$Roster_In = strtotime($Roster_In);
										$Roster_Out = strtotime($Roster_Out);
										$BreakDuration = 0;
										// for ($j = 0; $j < count($ds_break); $j++) {
										while ($ds_breaks = mysqli_fetch_assoc($ds_break)) {
											$BreakDuration = 0;
											$breakstart = strtotime($ds_breaks['starttime']);
											$breakend = strtotime($ds_breaks['endtime']);
											if ($breakstart < $Roster_In && $breakend < $Roster_In) {
												$BreakDuration = $breakend - $breakstart;
											} else if ($breakstart > $Roster_Out && $breakend > $Roster_Out) {
												$BreakDuration = $breakend - $breakstart;
											} else if ($breakstart < $Roster_In && $breakend > $Roster_In) {
												$BreakDuration = $Roster_In - $breakstart;
											} else if ($breakstart < $Roster_Out && $breakend > $Roster_Out) {
												$BreakDuration = $Roster_Out - $breakstart;
											}

											if ($Release >= $BreakDuration) {
												$Release = $Release - $BreakDuration;
											} else {
												$Release = $BreakDuration - $Release;
											}
										}
									}


									$sq12 = "insert into CCSPAPR(Process_Name,LoggedIn,TimeTo,Duration,FirstLogOut,AgentGlobal_ID,Agent_ID,AgentFirstName,AgentLastName,Available,Talk,WrapUp,`Release`,Hold,Other,Calls,ASA,MaxSpeedAns)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
									$insert = $conn->prepare($sq12);
									$insert->bind_param("sssississiiiiiiiii", $Process_Name, $AprIn, $AprOut, $Duration, $FirstLogOut, $AgentGlobal_ID, $Agent_ID, $AgentFirstName, $AgentLastName, $Available, $Talk, $WrapUp, $Release, $Hold, $Other, $Calls, $ASA, $MaxSpeedAns);
									$insert->execute();
									$results = $insert->get_result();
									// $myDB->query($sq12);
								}
							}
						}
					} catch (Exception $ex) {
						// echo 'Message: ' . $ex->getMessage() . '<br/>';
						echo 'Message: "There was some error please try after sometimes."<br/>';
					}
				}
			} else {
				echo 'less';
			}

			////////////////////
			$sql = 'call insert_cosmo_apr("' . $date . '")';
			$myDB = new MysqliDb();
			$flag = $myDB->query($sql);
			$error = $myDB->getLastError();

			if (empty($error)) { {
					$sql = 'select agentid, employeeid,date,apr,logged_in,logged_out,on_call,break,process from cosmo_apr_temp where employeeid is not null or employeeid !="" order by id;';

					$ds_apr = $myDB->rawQuery($sql);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error) && count($ds_apr) > 0) {
						for ($i = 0; $i < count($ds_apr); $i++) {
							try {

								$empid =  $ds_apr[$i]['employeeid'];
								$date = strtotime($ds_apr[$i]['date']);
								$agentid = $ds_apr[$i]['agentid'];
								$logged_in = $ds_apr[$i]['logged_in'];
								$logged_out = $ds_apr[$i]['logged_out'];
								$on_call = $ds_apr[$i]['on_call'];
								$break = $ds_apr[$i]['break'];
								$process = $ds_apr[$i]['process'];
								$day = 'D' . date('j', $date);
								$month = date('n', $date);
								$year = date('Y', $date);
								$apr = date("h:i", strtotime($ds_apr[$i]['apr']));
								/*$sql = 'call update_final_apr("'.$empid.'", "'.$day.'", "'.$month.'", "'.$year.'", "'.$apr.'")';
								$myDB = new MysqliDb();
								$flag = $myDB->query($sql);
								$error = $myDB->getLastError();
								if(empty($error))
								{*/
								echo ('APR updated for EmpID : ' . $empid . ' on : ' . $day . $month . $year . '<br />');
								$sql = 'call insert_raw_apr1("' . $agentid . '", "' . $empid . '", "' . $ds_apr[$i]['date'] . '", "' . $logged_in . '", "' . $logged_out . '", "' . $on_call . '", "' . $break . '", "' . $ds_apr[$i]['apr'] . '", "' . $process . '")';
								$myDB = new MysqliDb();
								$flag = $myDB->query($sql);
								//}
								//echo $sql;
								//echo 'EmpID  ::  => '.$empid. '<br />' . $day.$month.$year.$apr;
							} catch (Exception $ex) {
								// echo 'Message: ' . $ex->getMessage() . '<br/>';
								echo 'Message: "There was some error please try after sometimes."<br/>';
							}
						}
					}
				}

				/*$sql = 'call insert_raw_apr()';
				$myDB = new MysqliDb();
				$flag = $myDB->query($sql);*/
				//settimestamp('calc_apr_one','END');
			}
		}
	}
}
