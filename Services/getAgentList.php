<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID=$CLID=$Proc=$SubProc=$DOJ=$Ageing=$Query= null;
	if($_REQUEST)
	{
	$myDB=new MysqliDb();
	/*$Query="select EmployeeName,EmployeeID from whole_details_peremp  
	where sub_process='".$_REQUEST['sub_process']."' and clientname='".$_REQUEST['clientname']."' order by EmployeeName ";*/
	$isin=0;
	 $emplist=["CE061930045","CE121829689","CE10091236","CE08134859"];	
	foreach($emplist as $string)
	{
	  if(strpos($_REQUEST['qh'], $string) !== false) 
	  {
	    $isin=1;
	    break;
	  }
	}
	if($_REQUEST['User_type']=="Demo")
	{
		$Query="select EmployeeName,EmployeeID from whole_details_peremp where client_name='".$_REQUEST['client']."'  order by EmployeeName ";
	}
	else
	{
		
	
	if($isin==1)
	{
		
		$sqlstr="select  process  from  new_client_master where cm_id='".$_REQUEST['cmid']."' ";
		$res =$myDB->query($sqlstr);
				if($res)
				{	
				//$Query="select EmployeeName,EmployeeID from whole_details_peremp where cm_id='".$_REQUEST['cmid']."' order by EmployeeName ";
				$Query="select EmployeeName,EmployeeID from whole_details_peremp where process='".$res[0]['process']."' order by EmployeeName ";			
				}
				else
				{
					echo 'EmployeeID NOT EXIST for this Process';
				}
	}
	else
	{
	$Query="select EmployeeName,EmployeeID from whole_details_peremp  
	where (process='".$_REQUEST['process']."' or qh='".$_REQUEST['qh']."') and clientname='".$_REQUEST['clientname']."' and location='".$_REQUEST['location']."'  order by EmployeeName ";
	}
	}
	//echo $Query;
				$res =$myDB->query($Query);
				if($res)
				{
					foreach($res as $key=>$value)																											{
						$result[] = $value;
		   			}
					$result = json_encode($result);
					echo $result;
				}
				else
				{
					echo 'EmployeeID NOT EXIST';
				}
		}
	else
	{
		echo 'ID PLEASE !';
	}	
	?>