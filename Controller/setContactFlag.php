<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
if(isset($_REQUEST['tablename']) && $_REQUEST['tablename']!="") {
		$id=$_REQUEST['id'];
		$tablename=$_REQUEST['tablename'];
		$data='';
		$data2='';
		$oh_ack='';
		$vh_ack='';
		$ah_ack='';
		if($_SESSION['__status_ah']!='' && $_SESSION['__status_ah']==$_SESSION['__user_logid'])
		{
			 $data='';
			$ah_ack='1';
			$data2=" ack_flg='".$ah_ack."',";
			 $data= '<button type="button"  class="btn waves-effect waves-green">Acknowledged</button>';
		}
		if($_SESSION['__status_oh']!='' && $_SESSION['__status_oh']==$_SESSION['__user_logid'])
		{  $data='';
			$oh_ack='1';
			$data2.=" oh_ack='".$oh_ack."', ";
			 $data= '<button type="button"  class="btn waves-effect waves-green">Acknowledged</button>';
		}
		if($_SESSION["__status_vh"]!='' && $_SESSION["__status_vh"]==$_SESSION['__user_logid'])
		{
			 $data='';
			$vh_ack='1';
			$data2.=" vh_ack='".$vh_ack."', ";
			 $data= '<button type="button"  class="btn waves-effect waves-green">Acknowledged</button>';
		}
		echo  $data;

		$myDB=new MysqliDb();
		$update=$myDB->query(" update ctrctdetails_master  set  $data2 ack_date='".date("y-m-d h:i:s")."' where table_name='".$tablename."'" );
		
	
				

}