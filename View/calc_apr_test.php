<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();

function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."') ;";
			$myDB->query($sq1);
}
settimestamp('calc_apr','Start');
	 //$date = date('Y-m-d',strtotime("-1 days"));
	 $date ='2019-08-08';
	//////////////
	$sql_1="select * from CCSPAPR_final where cast(LoggedIn as date)='".$date."' and Agent_ID in ('13749');";// and Agent_ID='10168'
	$ds_apr = $myDB->rawQuery($sql_1);
	// var_dump($ds_apr);
	if(empty($mysql_error) && count($ds_apr)>0)
	    {
			for ($i = 0; $i < count($ds_apr); $i++)
			{
				$Process_Name=$ds_apr[$i]['Process_Name'];
				$FirstLogOut=$ds_apr[$i]['FirstLogOut'];
				$AgentGlobal_ID=$ds_apr[$i]['AgentGlobal_ID'];
				$Agent_ID=$ds_apr[$i]['Agent_ID'];
				$AgentFirstName=$ds_apr[$i]['AgentFirstName'];
				$AgentLastName=$ds_apr[$i]['AgentLastName'];
				$Available=$ds_apr[$i]['Available'];
				$Talk=$ds_apr[$i]['Talk'];
				$WrapUp=$ds_apr[$i]['WrapUp'];
				$Release=$ds_apr[$i]['Release'];
				$Hold=$ds_apr[$i]['Hold'];
				$Other=$ds_apr[$i]['Other'];
				$Calls=$ds_apr[$i]['Calls'];
				$ASA=$ds_apr[$i]['ASA'];
				$MaxSpeedAns=$ds_apr[$i]['MaxSpeedAns'];
					$AprIn=$ds_apr[$i]['LoggedIn']; // $LoggedIn1
					$AprOut=$ds_apr[$i]['TimeTo']; // $logout1
		/*$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://localhost/ems_noida/branches/dev/Services/get_apr_by_cosmoID.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,'coid='.$Agent_ID.'&date='.$date);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $dataee = json_decode($server_output, TRUE);
		$proc = $idd = array();
		if($dataee != null || $dataee !="")
		{
				foreach($dataee as $k=>$y)
				{
					$RosterIn=$y['InTime'];
					$RosterOut=$y['OutTime'];
				}
		}
		*/
		//Get Roster by cosmo ID and date
		$myDB=new MysqliDb();
	$Query="select  EmployeeID,InTime,OutTime,DateOn from roster_temp r  inner join cosmo_user_mapping c on r.EmployeeID=c.empid where c.cosmo_ID='".$Agent_ID."' and cast(r.DateOn as date)=cast('".$date."' as date);";
				$res =$myDB->query($Query);
				if($res)
				{
						foreach($res as $key=>$value)
						{
						$RosterIn=$value['InTime'];
						$RosterOut=$value['OutTime'];
						$EmployeeID=$value['EmployeeID'];
		   				}
				}
				else
				{
					$RosterIn='00:00';
					$RosterOut='00:00';
				}
		//
		
$flg=$exc_flg=$AssumptionCount=0;
if(strlen($AprIn)>0 && strlen($AprOut)>0)
{
	$Roster_In=date('Y-m-d',strtotime($AprIn)).' '.$RosterIn.':00'; // $LoggedIn2
	$Roster_Out=date('Y-m-d',strtotime($AprOut)).' '.$RosterOut.':00'; // $logout2
	
	if(strtotime($AprIn)<=strtotime($Roster_In))
	{
			$Calculation_In=$Roster_In;	
	}	
	else
	{
			$Calculation_In=$AprIn;
	}
	if(strtotime($AprOut)<strtotime($Roster_Out))
	{
			$Calculation_Out=$AprOut;
	}
	else
	{
		$Calculation_Out=$Roster_Out;	
	}
	if(strtotime($AprIn)>strtotime($Roster_Out))
	{
		$flg=1;
	}
	else if(strtotime($AprIn)< strtotime($Roster_In) && (strtotime($AprOut)<strtotime($Roster_In)))
	{
		$flg=1;
	}
	$Duration = strtotime($Calculation_Out) - strtotime($Calculation_In);
	if($flg==0)
	{
	$myDB = new MysqliDb();
	$sq12="insert into CCSPAPR(Process_Name,LoggedIn,TimeTo,Duration,FirstLogOut,AgentGlobal_ID,Agent_ID,AgentFirstName,AgentLastName,Available,Talk,WrapUp,`Release`,Hold,Other,Calls,ASA,MaxSpeedAns)values('".$Process_Name."','".$AprIn."',
	'".$AprOut."','".$Duration."','".$FirstLogOut."','".$AgentGlobal_ID."','".$Agent_ID."','".$AgentFirstName."','".$AgentLastName."','".$Available."','".$Talk."','".$WrapUp."','".$Release."','".$Hold."','".$Other."','".$Calls."','".$ASA."','".$MaxSpeedAns."');";
	//Echo $sq12.'<br/>';
			$myDB->query($sq12); 
	}
}
			}	
		}
else
{
	Echo 'less';
}
	////////////////////
	$sql = 'call insert_cosmo_apr("'.$date.'")';
	$myDB = new MysqliDb();
	$flag = $myDB->query($sql);
	$error = $myDB->getLastError();
	if(empty($error))
	{
		$sql = 'select agentid, employeeid,date,apr from cosmo_apr_temp where employeeid is not null or employeeid !="" order by id;';
		
		$ds_apr = $myDB->rawQuery($sql);
		$mysql_error = $myDB->getLastError();
	    if(empty($mysql_error) && count($ds_apr)>0)
	    {
			for ($i = 0; $i < count($ds_apr); $i++)
			{
				$empid =  $ds_apr[$i]['employeeid'];
				$date = strtotime($ds_apr[$i]['date']);
				$day = 'D'.date('j',$date);
				$month = date('n',$date);
				$year = date('Y',$date);
				$apr = date("h:i", strtotime($ds_apr[$i]['apr']));
				$sql = 'call update_final_apr("'.$empid.'", "'.$day.'", "'.$month.'", "'.$year.'", "'.$apr.'")';
				$myDB = new MysqliDb();
				$flag = $myDB->query($sql);
				$error = $myDB->getLastError();
				if(empty($error))
				{
					'APR updated for EmpID : '.$empid. ' on : '.$day.$month.$year . '<br />';
				}
				//echo $sql;
				//echo 'EmpID  ::  => '.$empid. '<br />' . $day.$month.$year.$apr;
			}
		}
	}	
	/*$sql = 'call insert_raw_apr()';
	$myDB = new MysqliDb();
	$flag = $myDB->query($sql);*/
settimestamp('calc_apr','END');
?>