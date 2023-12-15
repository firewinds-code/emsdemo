<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

if(isset($_REQUEST['bid'],$_REQUEST['empid'])){
	$myDB=new MysqliDb();
	
	if($_REQUEST['empid']!="" && $_REQUEST['bid']!=""){
		$select=$myDB->query("Select id from tbl_brifing_accknowledge where brifing_id='".$_REQUEST['bid']."' and EmployeeID='".$_REQUEST['empid']."' ");
		if(count($select)<1){
			$sql="insert into tbl_brifing_accknowledge set brifing_id='".$_REQUEST['bid']."', total_question='".$_REQUEST['tqnum']."',EmployeeID='".$_REQUEST['empid']."',quiz_attempt='No' ";
			$result=$myDB->query($sql);
			$error = $myDB->getLastError();
			if(empty($error)){
				echo 'yes';
			}
		}else{
			echo 'yes';
		}
		
	}
}

?>
