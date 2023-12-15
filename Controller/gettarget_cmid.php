<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$loc='';

if(isset($_REQUEST['empid']) && $_REQUEST['empid']!="")
{
	
	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	$sql='select t1.cm_id,concat(t2.client_name,"|",t1.process,"|",t1.sub_process," (",t3.location,")") as Process from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id join location_master t3 on t1.location=t3.id where t1.cm_id not in (select cm_id from client_status_master) and t1.cm_id in (select distinct processID from report_map where EmpID="'.$_REQUEST['empid'].'") order by t2.client_name;';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if($result)
	{
		//echo '<option value="NA" >---Select---</option>';
		foreach($result as $key=>$value)
		{
			echo $value['cm_id'].'|$|'.$value['Process'].'|$$|';
			//echo '<option value="'.$value['cm_id'].'"  >'.$value['Process'].'</option>';
							
		}
		
	}
	

}	
?>