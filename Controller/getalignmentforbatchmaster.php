<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$loc='';

if(isset($_REQUEST['loc']) && $_REQUEST['loc']!="" && isset($_REQUEST['type']) && $_REQUEST['type']!="")
{
	$loc=$_REQUEST['loc'];
	$type=$_REQUEST['type'];
	$client=$_REQUEST['client'];
	$process=$_REQUEST['process'];
	$subprocess=$_REQUEST['subprocess'];
	
	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	//$sql='select nc.*,cm.*,t1.location from new_client_master nc inner join client_master cm  on nc.client_name = cm.client_id inner join location_master t1 on t1.id = nc.location where t1.id="'.$loc.'" order by cm.client_name';
	
	if($type=="client")
	{
		$sql = 'select client_id, client_name from client_master where client_id in (select distinct client_name from new_client_master where location="'.$loc.'") order by client_name';
		
		$myDB=new MysqliDb();
		$result=$myDB->query($sql);
		$mysql_error=$myDB->getLastError();
		if( count($result) > 0 && $result)
		{
			echo '<option value="NA" >---Select---</option>';
			foreach($result as $key=>$value)
			{
				echo '<option value="'.$value['client_id'].'">'.$value['client_name'].'</option>';					
			}
			
		}
		else
		{
			echo '<option value="NA" >---Select---</option>';
			
		}
	
	}
	else if($type=="Process")
	{
		$sql = 'select distinct process from new_client_master where location="'.$loc.'" and client_name="'.$client.'"';
		
		$myDB=new MysqliDb();
		$result=$myDB->query($sql);
		$mysql_error=$myDB->getLastError();
		if( count($result) > 0 && $result)
		{
			echo '<option value="NA" >---Select---</option>';
			foreach($result as $key=>$value)
			{
				echo '<option value="'.$value['process'].'">'.$value['process'].'</option>';					
			}
			
		}
		else
		{
			echo '<option value="NA" >---Select---</option>';
			
		}
	}
	else if($type=="SubProcess")
	{
		$sql = 'select sub_process,cm_id from new_client_master where location="'.$loc.'" and client_name="'.$client.'" and process="'.$process.'" and cm_id not in (select cm_id from client_status_master)';
		
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
	}
	
	else
	{
		echo '<option value="NA" >---Select---</option>';
		
	}
	
	

}
else if(isset($_REQUEST['mode']) && $_REQUEST['mode']!="")
{
	
	$type=$_REQUEST['type'];
	$client=$_REQUEST['client'];
	$process=$_REQUEST['process'];
	$mode = $_REQUEST['mode'];
	
	if($mode=="Online")
	{
		if($type=="client")
		{
			$sql = 'select client_id, client_name from client_master where client_id in (select distinct client_name from new_client_master) order by client_name';
			
			$myDB=new MysqliDb();
			$result=$myDB->query($sql);
			$mysql_error=$myDB->getLastError();
			if( count($result) > 0 && $result)
			{
				echo '<option value="NA" >---Select---</option>';
				foreach($result as $key=>$value)
				{
					echo '<option value="'.$value['client_id'].'">'.$value['client_name'].'</option>';					
				}
				
			}
			else
			{
				echo '<option value="NA" >---Select---</option>';
				
			}
		
		}
		else if($type=="Process")
		{
			$sql = 'select distinct process from new_client_master where client_name="'.$client.'"';
			
			$myDB=new MysqliDb();
			$result=$myDB->query($sql);
			$mysql_error=$myDB->getLastError();
			if( count($result) > 0 && $result)
			{
				echo '<option value="NA" >---Select---</option>';
				foreach($result as $key=>$value)
				{
					echo '<option value="'.$value['process'].'">'.$value['process'].'</option>';					
				}
				
			}
			else
			{
				echo '<option value="NA" >---Select---</option>';
				
			}
		}
			
		else
		{
			echo '<option value="NA" >---Select---</option>';
			
		}
	}
	
}
	
?>