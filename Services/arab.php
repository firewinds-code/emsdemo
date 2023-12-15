<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
header("Content-Type: application/json; charset=UTF-8");
	$_POST = file_get_contents('php://input');
	$Data=json_decode($_POST,true);
	
if(isset($Data['EmployeeID']) && $Data['EmployeeID']!="" && isset($Data['month']) && $Data['month']!="" && isset($Data['year']) && $Data['year']!="" )
{
	$myDB=new MysqliDb();

				
				$CurrontMonthDay=cal_days_in_month(CAL_GREGORIAN,$Data['month'],$Data['year']);
$attnd="SELECT D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 FROM calc_atnd_master  where Year = '".$Data['year']."' AND Month = '".$Data['month']."' AND EmployeeID = '".$Data['EmployeeID']."'";
						$resattnd =$myDB->query($attnd);
				
$hours_hlp="SELECT D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 FROM hours_hlp  where Year = '".$Data['year']."' AND Month = '".$Data['month']."' AND EmployeeID = '".$Data['EmployeeID']."'";
						$reshours_hlp =$myDB->query($hours_hlp);
						
$bio="SELECT EmpID,DateOn,CAST(MIN(`biopunchcurrentdata`.`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`biopunchcurrentdata`.`PunchTime`),MIN(`biopunchcurrentdata`.`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`biopunchcurrentdata`.`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` FROM biopunchcurrentdata where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND Empid = '".$Data['EmployeeID']."' group by Dateon ,EmpID;";
//$bio="SELECT Empid,cast(DateOn as date) as  DateOn, cast( min(PunchTime) as time) InTime, cast( max(PunchTime) as time) OutTime FROM biopunchcurrentdata  where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND Empid = '".$Data['EmployeeID']."' group by  Empid ,cast(DateOn as date) order by DateOn";
						$resbio =$myDB->query($bio);
//print_r($resbio);exit;
$rost=" SELECT InTime as roasterIn, OutTime as roasterOut,DateOn FROM roster_temp  where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND EmployeeID = '".$Data['EmployeeID']."' order by DateOn";
						$resrost =$myDB->query($rost);
						
					$attandanceList=array();
				$r=$b=0;
				 for($i=1;$i <= $CurrontMonthDay ;$i++)
				 {
						$fdate=$Data['year'].'-'.$Data['month'].'-'.$i;
						$data['id']=$i;
						$data['dayName']=date("l",strtotime($fdate));
						/*attnd start*/
						if( count($resattnd) > 0 )
						{
							$data['attandance'] =$resattnd[0]['D'.$i];
							
							}
							else{
								$data['attandance'] = 'NA';
							}
						
						/*attnd end*/
						/*net hours*/
					if( count($reshours_hlp) > 0 )
						{
						 $data['netHours'] = $reshours_hlp[0]['D'.$i];
						/*net hours end*/
						}
						else{
						$data['netHours']  = '00:00';
						}
						$data['date'] =  $fdate;
						/*bio matric*/
						
						if( count($resbio) > 0 && count($resbio) > $b )
						{
							
						$dateon =  date('d',strtotime($resbio[$b]['DateOn']) );
							if($dateon==$i)
							{
								$data['InTime'] =  $resbio[$b]['InTime'] ;
								$data['OutTime'] = $resbio[$b]['OutTime'] ;
								$b++;
							}
							else
							{
								$data['InTime'] = '00:00' ;
								$data['OutTime'] = '00:00' ;
							}
						
						}
						else{
							$data['InTime'] = '00:00' ;
							$data['OutTime'] = '00:00' ;
						}
							/*bio matric end*/
						
						if( count($resrost) > 0 && count($resrost) > $r )
						{
							$dateon =  date('d',strtotime($resrost[$r]['DateOn']) );
							if($dateon==$i)
							{
								$data['roasterIn'] =  $resrost[$r]['roasterIn'] ;
								$data['roasterOut'] = $resrost[$r]['roasterOut'] ;
								$r++;
							}
							else
							{
								$data['roasterIn'] = '00:00' ;
								$data['roasterOut'] = '00:00' ;
							}
						
						}
						else{
						$data['roasterIn'] =  '00:00' ;
						$data['roasterOut'] = '00:00' ;
						}

						$attandanceList[]=$data;
					}
				 
				 if(count($attandanceList) > 0 )
				 {
					  $rosterData['message']='success';
				 }
				 else{
					  $rosterData['message']='fail';
				 }
				
				 $rosterData['EmployeeID']=$Data['EmployeeID'];
				 $rosterData['year']=$Data['year'];
				 $rosterData['month']=$Data['month'];
				  $rosterData['attandanceList']=$attandanceList;
				
				
}else
{
	$rosterData['message']='Invalid Data';
}
		echo json_encode($rosterData);
	?>