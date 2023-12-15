<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID=$CLID=$Proc=$SubProc=$DOJ=$Ageing=$Query= null;	
	if($_REQUEST)
	{
	$myDB=new MysqliDb();
$Query="select Name,t.EmployeeID from ActiveEmpID a inner join 
(select distinct 'Manager' as Name, qh as EmployeeID  from whole_details_peremp where cm_id='".$_REQUEST['cmid']."' and qh is not null
union
select distinct 'AM',  Trainer as EmployeeID from whole_details_peremp where cm_id='".$_REQUEST['cmid']."' and Trainer is not null
union
select distinct 'TL', ReportTo as EmployeeID from whole_details_peremp where cm_id='".$_REQUEST['cmid']."' and ReportTo is not null
union
select distinct 'QA', Qa_ops as EmployeeID from whole_details_peremp where cm_id='".$_REQUEST['cmid']."' and Qa_ops is not null)t on a.EmployeeID=t.EmployeeID";
  $dataarray["Status"]="0";
				$res =$myDB->query($Query);
				if($res)
				{
					/*foreach($res as $key=>$value)
					{
						$result[] = $value;
		   			}*/
					//$result = json_encode($result);
					$dataarray["data"]=$res;
					$dataarray["Status"]="1";
				}
				else
				{
					$dataarray["data"]="CMID NOT EXIST";
					$dataarray["Status"]="0";
				}
		}
			else
			{
							$dataarray["data"]="invalid request";
							$dataarray["Status"]="2";
			}	
	echo  json_encode($dataarray);
	//print_r($dataarray);
	?>