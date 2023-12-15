<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
if(isset($_REQUEST['bid'],$_REQUEST['empid'])){
	$myDB=new MysqliDb();
	$string="";
	if(isset($_REQUEST['ans']) && $_REQUEST['ans']!=""){
		$ans_array=explode(',',$_REQUEST['ans']);
		for($i=1;$i < count($ans_array);$i++){
			$string.=", answer".$i."='".$ans_array[$i]."' ";
		}
	}
	$ackid=$_REQUEST['ackid'];
	if($ackid==""){
		
	
		$id_array=$myDB->query("select max(id) as id from tbl_brifing_accknowledge");		
		foreach($id_array as $key=>$value)
		{
			$ackid = $value['id'];
		}
		
	}
	
	if($_REQUEST['empid']!="" && $_REQUEST['bid']!="" && $ackid!="")
	{
		$sql="update tbl_brifing_accknowledge set  quiz_attempted_date='".date('Y-m-d H:i:s')."' $string ,quiz_attempt='Yes' where EmployeeID='".$_REQUEST['empid']."'  and brifing_id='".$_REQUEST['bid']."' and id='".$ackid."'";
		$result=$myDB->query($sql);
		$check_error  = $myDB->getLastError();
		if(empty($check_error))
		{
			echo 'yes';
		}
	}echo 'yes';
		

}

?>

