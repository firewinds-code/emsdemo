<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID=$CLID=$Proc=$SubProc=$DOJ=$Ageing=$Query= null;
	if($_REQUEST)
	{
	$myDB=new MysqliDb();
	$Query="select t2.EmployeeID, t2.EmployeeName,t2.Process,t2.DOJ from whole_details_peremp t2 where t2.EmployeeID='".$_REQUEST['EmpID']."';";
//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
				$res =$myDB->query($Query);
				if($res)
				{
					foreach($res as $key=>$value){
						$EmployeeID = $value['EmployeeID'];
						$result[] = $value;
		   			}
					
					
					$Query="select InTime, OutTime from roster_temp where EmployeeID='".$EmployeeID."' and dateon=curdate();";
					
					$res1 =$myDB->query($Query);
					if($res1)
					{
						foreach($res1 as $key=>$value){
							//$EmployeeID = $value['EmployeeID'];
							array_push($result,$value);
							//$result[] = $value;
			   			}
			   		}
			   		else
			   		{
						$Query="select null as InTime, null as OutTime;";
						$res1 =$myDB->query($Query);
						if($res1)
						{
							foreach($res1 as $key=>$value){
							//$EmployeeID = $value['EmployeeID'];
							array_push($result,$value);
							//$result[] = $value;
			   			}
			   		}	
					}
			   		
			   		$Query="select (select OCCURRENCES('".$EmployeeID."','".date('m')."','".date('Y')."',',L,') ) as `Current Month Leave`
, (select OCCURRENCES('".$EmployeeID."','".date('m')."','".date('Y')."',',A,') ) as `Current Month Absent`
, (select OCCURRENCES('".$EmployeeID."','".date('m')."','".date('Y')."','LWP') ) as `Current Month LWP`
, (select OCCURRENCES('".$EmployeeID."','".date('m')."','".date('Y')."','WONA') ) as `Current Month WONA`
, (select OCCURRENCES('".$EmployeeID."','".date('m')."','".date('Y')."',',H,') ) as `Current Month H`;";
			   		$res1 =$myDB->query($Query);
					if($res1)
					{
						foreach($res1 as $key=>$value){
							//$EmployeeID = $value['EmployeeID'];
							array_push($result,$value);
							}
					}
					
					$Query="select (select OCCURRENCES('".$EmployeeID."','".date('m', strtotime('last month'))."','".date('Y', strtotime('last month'))."',',L,') ) as `Previous Month Leave`, (select OCCURRENCES('".$EmployeeID."','".date('m', strtotime('last month'))."','".date('Y', strtotime('last month'))."',',A,') ) as `Previous Month Absent`
, (select OCCURRENCES('".$EmployeeID."','".date('m', strtotime('last month'))."','".date('Y', strtotime('last month'))."','LWP') ) as `Previous Month LWP`
, (select OCCURRENCES('".$EmployeeID."','".date('m', strtotime('last month'))."','".date('Y', strtotime('last month'))."','WONA') ) as `Previous Month WONA`
, (select OCCURRENCES('".$EmployeeID."','".date('m', strtotime('last month'))."','".date('Y', strtotime('last month'))."',',H,') ) as `Previous Month H`;";
			   		$res1 =$myDB->query($Query);
					if($res1)
					{
						foreach($res1 as $key=>$value){
							//$EmployeeID = $value['EmployeeID'];
							array_push($result,$value);
							}
					}
					
			   		$result = json_encode($result);
					echo $result;
				}
				else
				{
					echo NULL;
				}
		}
	else
	{
		echo NULL;
	}	
	?>