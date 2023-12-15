<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

if(isset($_GET['action']) and $_GET['action']=='sendmsg')
{
	$message_text=$_GET['message'];
	$logid=$_GET['logid'];
	$logname=$_GET['logname'];
	$recipient_name = $_GET['empid'];
	
	
	$value=explode(",",$recipient_name);
	
	if($recipient_name!="" && $message_text!="")
	{
		$myDB=new MysqliDb();
		foreach($value as $key=>$val)
		{
		 echo $sql="call Add_Chat_message('".$message_text."','".trim($val)."','".$logid."','".$logname."')";
		 $resultBy=$myDB->query($sql);
		 $error=$myDB->getLastError();
		}
		//echo $tableValue ="<p>Message Send Successfully</p>";
		
	} 
	
      
	}

?>

