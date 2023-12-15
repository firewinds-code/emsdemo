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
/*	$Query="SELECT w.EmployeeID,clientname,Process,sub_process,DOJ,
concat( TIMESTAMPDIFF(YEAR, DOJ, NOW()),' Y ',MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) ,' M ',TIMESTAMPDIFF(DAY, DATE_ADD(DATE_ADD(DOJ,INTERVAL TIMESTAMPDIFF(YEAR, DOJ, NOW()) YEAR),INTERVAL MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) MONTH), NOW()) ,' D') as Ageing,oh `OH`,po.EmployeeName `OH Name`,qh `QH`,pq.EmployeeName `QH Name`,th `TH`,pt.EmployeeName `TH Name`,account_head `AH`,pa.EmployeeName `AH Name`,ReportTo ,pr.EmployeeName `rptTo Name` FROM whole_details_peremp w inner join personal_details pr on w.ReportTo=pr.EmployeeID inner join personal_details po on w.oh=po.EmployeeID
inner join personal_details pa on w.account_head=pa.EmployeeID
inner join personal_details pt on w.th=pt.EmployeeID
inner join personal_details pq on w.qh=pq.EmployeeID
where w.EmployeeID='".$_REQUEST['id']."'";
*/
$Query="SELECT  w.EmployeeID,clientname,w.Process,sub_process,DOJ,concat(TIMESTAMPDIFF(YEAR, DOJ,NOW()),' Y ',MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) ,' M ',TIMESTAMPDIFF(DAY, DATE_ADD(DATE_ADD(DOJ,INTERVAL TIMESTAMPDIFF(YEAR, DOJ, NOW()) YEAR),INTERVAL MOD(TIMESTAMPDIFF(MONTH, DOJ, NOW()), 12) MONTH), NOW()) ,' D') as Ageing, oh as `OH`,reporttoname(oh) `OH Name`, qh as `QH`,reporttoname(qh) `QH Name`,th as `TH`,reporttoname(th ) `TH Name`, account_head as `AH`,reporttoname(account_head) `AH Name`,  ReportTo ,reporttoname(ReportTo) `rptTo Name`,w.client_name as client_id, cm_id as sub_process_id ,process_id FROM whole_details_peremp w LEFT JOIN (select distinct process_id,process from process_map) `pm` on ((`w`.`process` = `pm`.`process`)) where w.EmployeeID='".$_REQUEST['id']."'";
				$res =$myDB->query($Query);
				if($res)
				{
					foreach($res as $key=>$value)																							{
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