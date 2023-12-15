<?php 
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID=$CLID=$Proc=$SubProc=$DOJ=$Ageing=$Query= null;
	if($_REQUEST)
	{
	$myDB=new MysqliDb();
	$Pwd=urldecode($_REQUEST['Pwd']);
	//$Pwd= $_REQUEST['Pwd'];
	$Query="Call D2_check_login_asset('".$_REQUEST['LoginId']."','".md5($Pwd)."')";
	//echo $Query;
/*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
where  qh='".$_REQUEST['qh']."' order by `Process`;";*/
//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
				$res =$myDB->query($Query);
				if($res)
				{
					/*foreach($res as $key=>$value)																										{
						$result[] = $value;
		   			}*/
		   			$result['EmployeeID'] = $res[0]['EmployeeID'];
		   			$result['EmployeeName'] = $res[0]['EmployeeName'];
		   			$result['location'] = $res[0]['location'];
		   			$result['emp_type'] = $res[0]['emp_type'];
		   			
		   			
		   			$result['Status']= "1";
					
					//echo $result;
				}
				else
				{
					$result['Status']= "0";
				}
		}
	else
	{
		//echo '';
	}
	echo json_encode($result);
	
	?>