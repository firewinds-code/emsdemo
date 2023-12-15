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
	/*$Query="select cm_id,client_name,process,sub_process from new_client_master where concat( client_name,process)  in 
(select concat( client_name,process) from whole_details_peremp where EmployeeID ='".$_REQUEST['EmployeeID']."' or  qh ='".$_REQUEST['EmployeeID']."')";*/
$Query="select distinct w.cm_id,w.client_name,w.process,w.sub_process from new_client_master c 
join whole_details_peremp w  on concat( c.client_name,c.process)= concat( w.client_name,w.process)
 where w.EmployeeID ='".$_REQUEST['EmployeeID']."' or  w.qh ='".$_REQUEST['EmployeeID']."'";
				$res =$myDB->query($Query);
				if($res)
				{
					foreach($res as $key=>$value)
					{
						$result[] = $value;
		   			}
					$result = json_encode($result);
					echo $result;
				}
				else
				{
					echo 'CMID NOT EXIST';
				}
		}
	else
	{
		echo 'EmployeeID PLEASE !';
	}	
	?>