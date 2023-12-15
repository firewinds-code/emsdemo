<?php
require_once(__dir__.'/../Config/init.php');
#require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

	if($_REQUEST['actionType']=='delete')
	{
		if(isset($_REQUEST['id']) && $_REQUEST['actionType']=='delete')
		{
			$DeleteQuery = "delete from vaccination_data where id='".$_REQUEST['id']."'";
					$myDB= new MysqliDb();
					$result =$myDB->query($DeleteQuery);
					
					$data['status']=true;
		}
		else{
			$data['status']=false;
		}
	}
	
	
	echo json_encode($data);	
?>