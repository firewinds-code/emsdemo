<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$loc='';

if(isset($_REQUEST['empid']) && $_REQUEST['empid']!="")
{
	
	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	$sql='select distinct reportID from report_map where EmpID="'.$_REQUEST['empid'].'";';
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if($result)
	{
		//echo '<option value="NA" >---Select---</option>';
		foreach($result as $key=>$value)
		{
			echo $value['reportID'].'|$|';
			//echo '<option value="'.$value['cm_id'].'"  >'.$value['Process'].'</option>';
							
		}
		
	}
	

}	
?>