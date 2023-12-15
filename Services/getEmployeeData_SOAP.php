<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID=$CLID=$Proc=$SubProc=$DOJ=$Ageing=$Query= null;
	/*require_once('../init.php');
	$default59 = array('host' => '192.168.202.252','user' => 'root','pass' => 'india@123','db' => 'ems');                        
	$myDB->__destruct($default59);
	$myDB->__construct($default59);*/
	if($_REQUEST)
	{
	$myDB=new MysqliDb();
	//$Query="SELECT EmployeeID,clientname,Process,sub_process,DOJ,DATEDIFF(now(),DOJ)as Ageing FROM whole_details_peremp where EmployeeID='".$_REQUEST['id']."'";
	/*$Query="SELECT EmployeeID,clientname,Process,sub_process,DOJ,concat( TIMESTAMPDIFF(YEAR, DOJ, NOW()),' Y ',MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) ,' M ',TIMESTAMPDIFF(DAY, DATE_ADD(DATE_ADD(DOJ,INTERVAL TIMESTAMPDIFF(YEAR, DOJ, NOW()) YEAR),INTERVAL MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) MONTH), NOW()) ,' D') as Ageing FROM whole_details_peremp where EmployeeID='".$_REQUEST['id']."'";*/
	$Query="SELECT EmployeeID,clientname,Process,sub_process,DOJ,concat( TIMESTAMPDIFF(YEAR, DOJ, NOW()),' Y ',MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) ,' M ',TIMESTAMPDIFF(DAY, DATE_ADD(DATE_ADD(DOJ,INTERVAL TIMESTAMPDIFF(YEAR, DOJ, NOW()) YEAR),INTERVAL MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) MONTH), NOW()) ,' D') as Ageing,
oh,qh,th,ReportTo FROM whole_details_peremp where EmployeeID='".$_REQUEST['id']."'";
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