<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$loc='';

	$loc=$_REQUEST['loc'];
	$type=$_REQUEST['type'];
	$client=$_REQUEST['client'];
	$process=$_REQUEST['process'];
	
		//$sql = 'select distinct sub_process,cm_id from whole_details_peremp where location="'.$loc.'" and client_name="'.$client.'" and process="'.$process.'" and cm_id not in (select cm_id from client_status_master) and id in (7,8,10)';
		
		$sql = 'select distinct sub_process,cm_id from new_client_master where location="'.$loc.'" and client_name="'.$client.'" and process="'.$process.'" and cm_id not in (select cm_id from client_status_master)';

		$myDB=new MysqliDb();
		$result=$myDB->query($sql);
		$mysql_error=$myDB->getLastError();
		if( count($result) > 0 && $result)
		{
			echo '<option value="NA" >---Select---</option>';
			foreach($result as $key=>$value)
			{
				echo '<option value="'.$value['cm_id'].'">'.$value['sub_process'].'</option>';					
			}
			
		}
		else
		{
			echo '<option value="NA" >---Select---</option>';
			
		}
