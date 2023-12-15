<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
try {


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
	settimestamp('calc_apr', 'Start');

	$date = date('Y-m-d', strtotime("-1 days"));

	//$date ='2021-03-17';
	//$date =date('Y-m-d',time());

	$count = 0;
	//////////////
	$sql_1 = "SELECT distinct EmpID from CCSPAPR_AgentManagement where cast(ActionTime as date)=cast(? as date)";
	$selQ = $conn->prepare($sql_1);
	$selQ->bind_param("s", $date);
	$selQ->execute();
	$ds_agentid = $selQ->get_result();
	// $ds_agentid = $myDB->rawQuery($sql_1);

	if ($ds_agentid->num_rows > 0 && !empty($ds_agentid)) {
		$EmployeeID = "";
		foreach ($ds_agentid as $key => $value) {
			/*$sql_1="select empid from cosmo_user_mapping where cosmo_ID='".$value["EmpID"]."'";
				$ds_empid = $myDB->rawQuery($sql_1);*/

			/*if(empty($mysql_error) && !empty($ds_empid))
				{*/
			$sql_1 = "select InTime,OutTime,type_ from roster_temp where EmployeeID=? and DateOn= ?";
			$selectQ = $conn->prepare($sql_1);
			$selectQ->bind_param("ss", $value["EmpID"], $date);
			$selectQ->execute();
			$result = $selectQ->get_result();
			$ds_roster = $result->fetch_row();
			echo "<br/>";
			// $ds_roster = $myDB->rawQuery($sql_1);

			if ($result->num_rows && !empty($result)) {
				$RosterFlag = 0;
				$RosterIn = $ds_roster[0];
				$RosterOut = $ds_roster[1];
				$RosterType = $ds_roster[2];

				$i_rin_tmp = date('H:i:s', strtotime($RosterIn));
				$i_rout_tmp = date('H:i:s', strtotime($RosterOut));

				if ($i_rin_tmp >= "15:00:00" && $RosterType == 1 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
					$RosterFlag = 1;
				} elseif ($i_rin_tmp >= "13:00:00" && $RosterType == 2 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
					$RosterFlag = 1;
				} elseif ($i_rin_tmp >= "19:00:00" && $RosterType == 3 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
					$RosterFlag = 1;
				}
				/*else
						{
							$RosterFlag = 0;
						}*/

				//echo $value["EmpID"].' - '.$RosterFlag. '----';

				if ($RosterFlag == 0) {
					if ($RosterIn != '' && $RosterOut != '' && is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut))) {
						$sql_1 = "SELECT ActionTime,SysHours,`Release` from CCSPAPR_AgentManagement where  cast(ActionTime as date)=cast(? as date) and EmpID=? order by ActionTime;";
						$selQr = $conn->prepare($sql_1);
						$selQr->bind_param("ss", $date, $value["EmpID"]);
						$selQr->execute();
						$ds_apr = $selQr->get_result();
						// $ds_apr = $myDB->rawQuery($sql_1);
						$tmpcalc1 = $tmpcalc2 = $tmpcalc3 = 0;
						if ($ds_apr->num_rows && !empty($ds_apr)) {
							foreach ($ds_apr as $key => $value1) {
								try {
									$ActionTime = $value1['ActionTime'];
									$SysHours = $value1['SysHours'];
									$Release = $value1['Release'];

									$flg = $exc_flg = $AssumptionCount = 0;

									if (strlen($ActionTime) > 0) {
										if (is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut))) {
											$Roster_In = date('Y-m-d', strtotime($date)) . ' ' . date('H', strtotime($RosterIn)) . ":00:00";
											$Roster_Out = date('Y-m-d', strtotime($date)) . ' ' . date('H', strtotime($RosterOut)) . ":00:00";

											if (date('i', strtotime($RosterOut)) == '00') {
												$Roster_Out = date("Y-m-d H:i:s", strtotime('-1 hours', strtotime($Roster_Out)));
											}

											//$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
											//$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2
											if ($ActionTime >= $Roster_In && $ActionTime <= $Roster_Out && $SysHours != '0') {
												//echo "<br/>". $value["EmpID"]. "----" .$ActionTime;
												$tmpcalc1 = ($SysHours * $Release) / 100;
												$tmpcalc2 = $SysHours - $tmpcalc1;
												$tmpcalc3 = $tmpcalc3 + $tmpcalc2;
											}
										}
									}
								} catch (Exception $ex) {
									echo 'Message: ' . $ex->getMessage() . '<br/>';
								}
							}

							$day = 'D' . date("j", strtotime($date));
							echo $sql_1 = 'call insert_cosmo_hour("' . $value["EmpID"] . '","' . $day . '","' . $tmpcalc3 . '","' . date("n", strtotime($date)) . '","' . date("Y", strtotime($date)) . '")';
							echo "<br/>";
							$flag = $myDB->rawQuery($sql_1);
							$error = $myDB->getLastError();

							if (empty($error)) {
								$count++;
							}
						}
					}
				} else {
					$date1 = date('Y-m-d', strtotime($date . ' -1 days'));
					$sql_1 = "select InTime,OutTime,type_ from roster_temp where EmployeeID=? and DateOn= ?";
					$selQy = $conn->prepare($sql_1);
					$selQy->bind_param("ss", $value["EmpID"], $date1);
					$selQy->execute();
					$results = $selQy->get_result();
					$ds_roster = $results->fetch_row();

					// $ds_roster = $myDB->rawQuery($sql_1);

					if ($results->num_rows > 0 && !empty($results)) {
						$RosterFlag = 0;
						$RosterIn = $ds_roster[0];
						$RosterOut = $ds_roster[1];
						$RosterType = $ds_roster[2];

						$i_rin_tmp = date('H:i:s', strtotime($RosterIn));
						$i_rout_tmp = date('H:i:s', strtotime($RosterOut));

						if ($i_rin_tmp >= "15:00:00" && $RosterType == 1 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
							$RosterFlag = 1;
						} elseif ($i_rin_tmp >= "13:00:00" && $RosterType == 2 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
							$RosterFlag = 1;
						} elseif ($i_rin_tmp >= "19:00:00" && $RosterType == 3 && $i_rout_tmp  >= '00:00:00' && $i_rin_tmp > $i_rout_tmp) {
							$RosterFlag = 1;
						}

						if ($RosterFlag == 0) {
							if ($RosterIn != '' && $RosterOut != '' && is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut))) {
								$sql_1 = "SELECT ActionTime,SysHours,`Release` from CCSPAPR_AgentManagement where  cast(ActionTime as date)=cast(? as date) and EmpID=? order by ActionTime;";
								$selQu = $conn->prepare($sql_1);
								$selQu->bind_param("ss", $date, $value["EmpID"]);
								$selQu->execute();
								$ds_apr = $selQu->get_result();
								// $ds_roster = $results->fetch_row();
								// $ds_apr = $myDB->rawQuery($sql_1);
								$tmpcalc1 = $tmpcalc2 = $tmpcalc3 = 0;
								if ($ds_apr->num_rows && !empty($ds_apr)) {
									foreach ($ds_apr as $key => $value1) {
										try {
											$ActionTime = $value1['ActionTime'];
											$SysHours = $value1['SysHours'];
											$Release = $value1['Release'];

											$flg = $exc_flg = $AssumptionCount = 0;

											if (strlen($ActionTime) > 0) {
												if (is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut))) {
													$Roster_In = date('Y-m-d', strtotime($date)) . ' ' . date('H', strtotime($RosterIn)) . ":00:00";
													$Roster_Out = date('Y-m-d', strtotime($date)) . ' ' . date('H', strtotime($RosterOut)) . ":00:00";

													if (date('i', strtotime($RosterOut)) == '00') {
														$Roster_Out = date("Y-m-d H:i:s", strtotime('-1 hours', strtotime($Roster_Out)));
													}

													//$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
													//$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2
													if ($ActionTime >= $Roster_In && $ActionTime <= $Roster_Out && $SysHours != '0') {
														//echo "<br/>". $value["EmpID"]. "----" .$ActionTime;
														$tmpcalc1 = ($SysHours * $Release) / 100;
														$tmpcalc2 = $SysHours - $tmpcalc1;
														$tmpcalc3 = $tmpcalc3 + $tmpcalc2;
													}
												}
											}
										} catch (Exception $ex) {
											echo 'Message: ' . $ex->getMessage() . '<br/>';
										}
									}

									$day = 'D' . date("j", strtotime($date));
									echo $sql_1 = 'call insert_cosmo_hour("' . $value["EmpID"] . '","' . $day . '","' . $tmpcalc3 . '","' . date("n", strtotime($date)) . '","' . date("Y", strtotime($date)) . '")';
									echo "<br/>";
									$flag = $myDB->rawQuery($sql_1);
									$error = $myDB->getLastError();

									if (empty($error)) {
										$count++;
									}
								}
							}
						} else {
							if ($RosterIn != '' && $RosterOut != '' && is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut))) {

								$sql_1 = "SELECT ActionTime,SysHours,`Release` from CCSPAPR_AgentManagement where  (cast(ActionTime as date) between cast(? as date) and cast(? as date)) and EmpID=? order by ActionTime;";
								$selectQ = $conn->prepare($sql_1);
								$selectQ->bind_param("sss", $date1, $date, $value["EmpID"]);
								$selectQ->execute();
								$ds_apr = $selectQ->get_result();
								// $ds_apr = $myDB->rawQuery($sql_1);
								$tmpcalc1 = $tmpcalc2 = $tmpcalc3 = 0;
								if ($ds_apr->num_rows && !empty($ds_apr)) {
									foreach ($ds_apr as $key => $value1) {
										try {
											$ActionTime = $value1['ActionTime'];
											$SysHours = $value1['SysHours'];
											$Release = $value1['Release'];

											$flg = $exc_flg = $AssumptionCount = 0;

											if (strlen($ActionTime) > 0) {
												if (is_numeric(strtotime($RosterIn)) && is_numeric(strtotime($RosterOut))) {
													$Roster_In = date('Y-m-d', strtotime($date1)) . ' ' . date('H', strtotime($RosterIn)) . ":00:00";
													$Roster_Out = date('Y-m-d', strtotime($date)) . ' ' . date('H', strtotime($RosterOut)) . ":00:00";

													if (date('i', strtotime($RosterOut)) == '00') {
														$Roster_Out = date("Y-m-d H:i:s", strtotime('-1 hours', strtotime($Roster_Out)));
													}

													//$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
													//$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2
													if ($ActionTime >= $Roster_In && $ActionTime <= $Roster_Out && $SysHours != '0') {
														//echo "<br/>". $value["EmpID"]. "----" .$ActionTime;
														$tmpcalc1 = ($SysHours * $Release) / 100;
														$tmpcalc2 = $SysHours - $tmpcalc1;
														$tmpcalc3 = $tmpcalc3 + $tmpcalc2;
													}
												}
											}
										} catch (Exception $ex) {
											echo 'Message: ' . $ex->getMessage() . '<br/>';
										}
									}

									$day = 'D' . date("j", strtotime($date1));
									echo $sql_1 = 'call insert_cosmo_hour("' . $value["EmpID"] . '","' . $day . '","' . $tmpcalc3 . '","' . date("n", strtotime($date1)) . '","' . date("Y", strtotime($date1)) . '")';
									echo "<br/>";
									$flag = $myDB->rawQuery($sql_1);
									$error = $myDB->getLastError();

									if (empty($error)) {
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
	} else {
		echo 'less';
	}

	settimestamp('calc_apr', 'END');
} catch (Exception $ex) {
	echo 'Message: ' . $ex->getMessage() . '<br/>';
}
