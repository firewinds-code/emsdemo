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
	$abcd=urldecode($_REQUEST['refrance']);
	$abcd=urldecode($_REQUEST['LoginId']);
	$Query="Call D2_check_login('".$_REQUEST['LoginId']."','".md5($abcd)."')";
	//echo $Query;
/*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
where  qh='".$_REQUEST['qh']."' order by `Process`;";*/
//where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
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
					//echo '';
				}
		}
	else
	{
		//echo '';
	}	
	?>