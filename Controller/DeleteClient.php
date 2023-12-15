<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
if(isset($_REQUEST['dttid']) and $_REQUEST['dttid']!=""){
	$myDB=new MysqliDb();
	$delete_dt_query="DELETE from downtimereqid1 where ID='".$_REQUEST['dttid']."' ";
	$result2=$myDB->query($delete_dt_query);
}
	$sql='call remove_client_new('.$_REQUEST['ID'].')';
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sql);
	$row_count = $myDB->count;
	$mysql_error=$myDB->getLastError();
	
	if(empty($mysql_error)){
		echo "Row Deleted Affected Row are :".$row_count;
		 
	}
	else
	{
		echo "Row Not Deleted Try Again :";
	}
?>

