<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
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
						
						$downtime="select TotalDT,DATE_FORMAT(LoginDate,'%e') as dt from downtime where EmpID='".$Data['EmployeeID']."' and FAStatus ='Approve' and RTStatus = 'Approve' and DATE_FORMAT(LoginDate,'%Y') = '".$Data['year']."'and DATE_FORMAT(LoginDate,'%m') = '".$Data['month']."'  order by id asc;";
						$resdowntime =$myDB->query($downtime);
						
/*$bio="SELECT EmpID,DateOn,CAST(MIN(`biopunchcurrentdata`.`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`biopunchcurrentdata`.`PunchTime`),MIN(`biopunchcurrentdata`.`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`biopunchcurrentdata`.`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` FROM biopunchcurrentdata where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND Empid = '".$Data['EmployeeID']."' group by Dateon ,EmpID;";
//$bio="SELECT Empid,cast(DateOn as date) as  DateOn, cast( min(PunchTime) as time) InTime, cast( max(PunchTime) as time) OutTime FROM biopunchcurrentdata  where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND Empid = '".$Data['EmployeeID']."' group by  Empid ,cast(DateOn as date) order by DateOn";
						$resbio =$myDB->query($bio);*/
						
						$resbio = array();
						
						
			 $dateFromMonth = "".$Data['year']."-".$Data['month']."-01";			
					$dateToMonth = "".$Data['year']."-".$Data['month']."-".$CurrontMonthDay."";	
$bioMonth="SELECT PunchTime,DateOn FROM ems.biopunchcurrentdata where empid='".$Data['EmployeeID']."' and cast(DateOn as date) between cast('".$dateFromMonth."'  as date) and cast( '".$dateToMonth."' as date) order by DateOn,PunchTime;";

						$resBioMonth =$myDB->query($bioMonth);						
						
						
//print_r($resbio);exit;
$rost=" SELECT InTime as roasterIn, OutTime as roasterOut,DateOn,work_from FROM roster_temp  where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND EmployeeID = '".$Data['EmployeeID']."' order by DateOn";
						$resrost =$myDB->query($rost);
						
					/*$dhgdh= 	createArray($resBioMonth,"".$Data['year']."-".$Data['month']."-01","".$Data['year']."-".$Data['month']."-02");
						print_r($dhgdh);
						die; */
						/*var_dump($resdowntime);
						echo array_search('1',$resdowntime);
						echo 'i4theirohi';
						exit ;*/
					$attandanceList=array();
					$r=$b=$d=0;
				 for($i=1;$i <= $CurrontMonthDay ;$i++)
				 {
						$fdate=$Data['year'].'-'.$Data['month'].'-'.$i;
						$data['id']=$i;
						$data['dayName']=date("l",strtotime($fdate));
						/*attnd start*/
						if( count($resattnd) > 0 )
						{
							$data['attandance'] =$resattnd[0]['D'.$i];
						}else{
						
						$data['attandance'] = 'NA';
						
						}
						
						/////////////////////////////////////////////////////Mathching Downtime for the day.
						
						if($d < count($resdowntime) && $resdowntime[$d]['dt'] == $i){
							
							$data['downtime'] =$resdowntime[$d]['TotalDT'];
							$d++;
						}else{
							
							$data['downtime'] ='00:00';
						}
						
						/*attnd end*/
						/*net hours*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if( count($reshours_hlp) > 0 )
						{
						 $data['netHours'] = $reshours_hlp[0]['D'.$i];
						/*net hours end*/
						}
						else{
						$data['netHours']  = '00:00';
						}
						
						$data['date'] =  $fdate;
						
						
	///////////////////////////////*bio matric*////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						
						
			//////////////Roster Capping of Four Hours.//////////////For pEOPLE wORKING rOSTER iN IS 3 pm OR mORE. and Having roster in split 
						$fixedTm ="15:00";
						$rostIn ='09:00';
						if(count($resrost)> 0 && $r < count($resrost) ){
							$rostIn = $resrost[$r]['roasterIn'] ;
						}else if(count($resrost)> 0){
							$rostIn = $resrost[0]['roasterIn'] ;
						}
						
						
						if(strpos($rostIn, '|')){
							 $dateFrom = date('Y-m-d',strtotime($fdate));
							 $dateTo= $dateFrom;
							 
							 	$resBioDay =	createArray($resBioMonth,$dateFrom,$dateTo);
							 	
				if(count($resBioDay) > 0){
					$rostfirstInOut = $resrost[$r]['roasterIn'] ;
					$rosterFirstarray = explode('|',$rostfirstInOut);
					$rostFirstIn = $rosterFirstarray[0];
					$rostFirstOut = $rosterFirstarray[1];
					$rostSecondInOut = $resrost[$r]['roasterOut'] ;
					$rosterSecondarray = explode('|',$rostSecondInOut);
					$rostSecondIn = $rosterSecondarray[0];
					$rostSecondOut = $rosterSecondarray[1];
					
					$bioMinCappingInFirst = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($dateFrom.' '.$rostFirstIn)));
					$bioMaxCappingInFirst = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($dateFrom.' '.$rostFirstIn))) ;
					$bioMinCappingOutFirst = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($dateFrom.' '.$rostFirstOut)));
					$bioMaxCappingOutFirst = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($dateFrom.' '.$rostFirstOut))) ;	
					$bioMinCappingInSecond = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($dateFrom.' '.$rostSecondIn)));
					$bioMaxCappingInSecond = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($dateFrom.' '.$rostSecondIn))) ;
					$bioMinCappingOutSecond = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($dateFrom.' '.$rostSecondOut)));
					$bioMaxCappingOutSecond = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($dateFrom.' '.$rostSecondOut))) ;
					
					//var for below calculation
					$bioFirstInMinSet= 0 ;
					$bioOutCapping ;				
					
					$boiInOrOutGotFromQrry = 0;	
					$bioFirstInSet  = 0;
					$bioSecondInSet  = 0;
					$bioFirstOutSet  = 0;
					$bioSecondOutSet  = 0;
					$bioFirstIn='00:00:00' ;	
					$bioFirstOut='00:00:00' ;
					$bioSecondIn='00:00:00' ;
					$bioSecondOut='00:00:00' ;
					
					
					///var for only one punch inside the roater.
					$bioR1Set=0;
					$bioR2Set=0;
					$bioR1;
					$bioR2;
							
					$l = 0;
					
					//Loop to get the punch in out in split form.
					for($l ; $l < count($resBioDay) ; $l++ ){
						 $punchDateTime = date('Y-m-d H:i:s',  strtotime($resBioDay[$l]['DateOn'].' '.$resBioDay[$l]['PunchTime']));
						 
						 
						//For Bio / Punch In Time of First Roster  
						if($bioMinCappingInFirst <= $punchDateTime && $punchDateTime <= $bioMaxCappingInFirst && $bioFirstInSet == 0){
							$boiInOrOutGotFromQrry = 1;
							$bioFirstIn =  $resBioDay[$l]['PunchTime'] ;
							$bioFirstInSet = 1;
						}
						
						//For Bio / Punch Out Time of First Roster  
						if($bioMinCappingOutFirst <= $punchDateTime && $punchDateTime <= $bioMaxCappingOutFirst ){
							$boiInOrOutGotFromQrry = 1;
							$bioFirstOut =  $resBioDay[$l]['PunchTime'] ;
							$bioFirstOutSet = 1;
							
						}
						
						//For Bio / Punch In Time of Second Roster  
						if($bioMinCappingInSecond <= $punchDateTime && $punchDateTime <= $bioMaxCappingInSecond && $bioSecondInSet == 0  ){
							$boiInOrOutGotFromQrry = 1;
							$bioSecondIn =  $resBioDay[$l]['PunchTime'] ;
							$bioSecondInSet = 1;
						}
						
						//For Bio / Punch Out Time of Second Roster  
						if($bioMinCappingOutSecond <= $punchDateTime && $punchDateTime <= $bioMaxCappingOutSecond ){
							$boiInOrOutGotFromQrry = 1;
							$bioSecondOut =  $resBioDay[$l]['PunchTime'] ;
							$bioSecondOutSet = 1;
							
						}
						
						
						///
						
						
						////////////////Special Condition for Ho and Wo ////////////////////////
						
						if($rostIn == 'WO|WO' || $rostIn == 'HO|HO'){
							
							if($l == 0 && $bioFirstInSet == 0 ){
								$bioFirstIn =  $resBioDay[$l]['PunchTime'] ;
								$boiInOrOutGotFromQrry = 1;
								 $bioOutCapping = date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($punchDateTime)));
								
							}
							
							if($l == (count($resBioDay)-1) && $l!= 0 && $punchDateTime > $bioOutCapping ){
								$bioFirstOut = $resBioDay[$l]['PunchTime'] ;
								
							}
							
						}
						
						
						
						//////////////////////If No Puch Found Inside capping//////////////////
						
						///Roster1
						if($bioMinCappingInFirst <= $punchDateTime && $punchDateTime <= $bioMaxCappingOutFirst && $bioR1Set ==0 ){
							$bioR1 = $resBioDay[$l]['PunchTime'] ;
							$bioR1Set = 1;
							
						}
						
						//Roster 2
						if($bioMinCappingInSecond <= $punchDateTime && $punchDateTime <= $bioMaxCappingOutSecond && $bioR2Set == 0  ){
							$bioR2 = $resBioDay[$l]['PunchTime'] ;
							$bioR2Set = 1;
							
						}
						
						
						
					}
					
					
					////////////////////////////Special condition for when there is no pucnch detected under the required capping/////////////
					
					
					if($bioFirstInSet == 0 &&$bioFirstOutSet == 0 && $bioR1Set == 1 ){
						$bioFirstIn = $bioR1 ;
					}
					
					if($bioSecondInSet == 0 &&$bioSecondOutSet == 0 && $bioR2Set == 1 ){
						$bioSecondIn = $bioR2 ;
					}
					
					
					
					
					///Now Creating the in and out resp[onse for punch/bio;
					$data['InTime'] = $bioFirstIn.'|'.$bioFirstOut ;
					$data['OutTime'] = $bioSecondIn.'|'.$bioSecondOut ;
				
				
					
					////////Increement b var for that in or out time got from qeurry.
					
					if($boiInOrOutGotFromQrry == 1){
						
						$b++;
					}
							 		
				}else{
				
				$data['InTime'] = '00:00:00|00:00:00' ;
				$data['OutTime'] = '00:00:00|00:00:00' ;
				}
							 
							/*echo 'INNNNN';*/
	/*}else if (strtotime($fixedTm) <= strtotime($rostIn) ) {*/
	}else if (true)  {
		
		///////////For People Who Roster Not In Spit Format along With Whose Roster In Is More Than # PNM Or Equal to 3 PM . //////
		
							 $dateFrom = date('Y-m-d',strtotime($fdate));
							$dateTo= $dateFrom;
							
							///
							if (strtotime($fixedTm) <= strtotime($rostIn) ) {
									$dateTo = date('Y-m-d', strtotime('+1 day', strtotime($dateFrom)));
								}


						$resBioDay =	createArray($resBioMonth,$dateFrom,$dateTo);
									
				if(count($resBioDay) > 0){
					
					
										
										
					$rostIn = $resrost[$r]['roasterIn'] ;
					$rostOut = $resrost[$r]['roasterOut'] ;
					$bioInSet = 0 ;
					$bioOutSet = 0;
					$boiInOrOutGotFromQrry = 0;
					$bioMinCappingIn = date('Y-m-d H:i:s', strtotime('-4 hours', strtotime($dateFrom.' '.$rostIn)));
					$bioMaxCappingIn = date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateFrom.' '.$rostIn)));
					$bioMinCappingOut = date('Y-m-d H:i:s', strtotime('-4 hours', strtotime($dateTo.' '.$rostOut))) ;
					$bioMaxCappingOut = date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($dateTo.' '.$rostOut))) ;
					$bioOutCapping ;	
					
					//var for specl condition
					$bioDaySet=0 ; 			
					$bioDay ; 			
									
					$l = 0;
					for($l ; $l < count($resBioDay) ; $l++ ){
						 $punchDateTime = date('Y-m-d H:i:s',  strtotime($resBioDay[$l]['DateOn'].' '.$resBioDay[$l]['PunchTime']));	
						
						//For In Time Punch 
						if($bioMinCappingIn <= $punchDateTime && $punchDateTime <= $bioMaxCappingIn && $bioInSet == 0  ){
							$boiInOrOutGotFromQrry = 1;
							$data['InTime'] =  $resBioDay[$l]['PunchTime'] ;
							$bioInSet = 1;
						}
										
						//For Out Time Punch 
						if($bioMinCappingOut <= $punchDateTime && $punchDateTime <= $bioMaxCappingOut ){
							$data['OutTime'] = $resBioDay[$l]['PunchTime'] ;
							$boiInOrOutGotFromQrry = 1;
							$bioOutSet = 1;
						}
						
						////////////////Special Condition for Ho and Wo ////////////////////////
						
						if($rostIn == 'WO' || $rostIn == 'HO'){
							
							if($l == 0 && $bioInSet == 0 ){
								$data['InTime'] =  $resBioDay[$l]['PunchTime'] ;
								$boiInOrOutGotFromQrry = 1;
								 $bioOutCapping = date('Y-m-d H:i:s', strtotime('+4 hours', strtotime($punchDateTime)));
								$bioInSet = 1;
							}
							
							if($l == (count($resBioDay)-1) && $l!= 0 && $punchDateTime > $bioOutCapping ){
								$data['OutTime'] = $resBioDay[$l]['PunchTime'] ;
								$bioOutSet = 1;
							}
							
						}
						
						//////////////////////If No Punch Found Inside capping//////////////////
						
						///Roster1
						if($bioMinCappingIn <= $punchDateTime && $punchDateTime <= $bioMaxCappingOut && $bioDaySet ==0 ){
							$bioDay = $resBioDay[$l]['PunchTime'] ;
							$bioDaySet = 1;
							
						}
						
					}
					
					//////Special Condition for when ther is not punch inside the required capping///////////
					if($bioInSet == 0 && $bioOutSet == 0 && $bioDaySet == 1 ){
						$data['InTime'] = $bioDay ;
						
				}
					
				
				//////////////Checking For Any Case Where In And Out Bio is Not Set////////////////////////////////////////////
					if($bioInSet == 0){
						$data['InTime'] = '00:00:00' ;
						
					}
					
					if($bioOutSet == 0){
						$data['OutTime'] = '00:00:00' ;
						
					}
					
					
					////////Increement b var for that in or out time got from qeurry.
					
					if($boiInOrOutGotFromQrry == 1){
						
						$b++;
					}
										
			}else{
				
				$data['InTime'] = '00:00:00' ;
				$data['OutTime'] = '00:00:00' ;
			}
									
									
									
		}else if( count($resbio) > 0 && count($resbio) > $b ) 
						{ 
						
						///////////////////For People Who In Time is Less Than OR Equal 3 PM . ////////////////////////////////////
						
							
						$dateon =  date('d',strtotime($resbio[$b]['DateOn']) );
						
						
					//This While Loop is To Tackle Any Extra Punch Which Not under Any Previous Roster Capping and Leaved So Removing 										//that Punch usability For Neext Day Bio Punch
					//var for Tackling the While Loop Execution from infinite Loop;
					$check =0;
								while($dateon < $i && $check < 6){
									
									$b++;
									$check++;
									if($b < count($resbio)){
										$dateon =  date('d',strtotime($resbio[$b]['DateOn']) );
									}
									
								}
									
							if($dateon==$i)
							{
								$data['InTime'] =  $resbio[$b]['InTime'] ;
								$data['OutTime'] = $resbio[$b]['OutTime'] ;
								$b++;
							}
							else
							{
								$data['InTime'] = '00:00:00' ;
								$data['OutTime'] = '00:00:00' ;
							}
						
						}
						else{
							
							
							$data['InTime'] = '00:00:00' ;
							$data['OutTime'] = '00:00:00' ;
						}
							/*bio matric end*/
							
							
							
/////////////////////////////////////////////Roaster Desciding/////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
						if( count($resrost) > 0 && count($resrost) > $r )
						{
							$dateon =  date('d',strtotime($resrost[$r]['DateOn']) );
							if($dateon==$i)
							{
								$data['roasterIn'] =  $resrost[$r]['roasterIn'] ;
								$data['roasterOut'] = $resrost[$r]['roasterOut'] ;
								$data['work_from'] = $resrost[$r]['work_from'] ;
								$r++;
							}
							else
							{
								$data['roasterIn'] = '00:00' ;
								$data['roasterOut'] = '00:00' ;
								$data['work_from'] = null ;
							}
						
						}
						else{
						$data['roasterIn'] =  '00:00' ;
						$data['roasterOut'] = '00:00' ;
						$data['work_from'] = null ;
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
		
	function createArray($bioMonth , $date1 , $date2 )	{
		
	
		$requiredBio=array();
		 foreach($bioMonth as $bioSingleDay){
    		if($bioSingleDay['DateOn'] == $date1 || $bioSingleDay['DateOn'] == $date2 ){
				$requiredBio[]=$bioSingleDay;
				
			}
   
 		 }
		
		return $requiredBio;
	}
		
	?>