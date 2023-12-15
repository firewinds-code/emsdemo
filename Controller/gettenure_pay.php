<?php
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
$loc='';

if(isset($_REQUEST['locid']) && $_REQUEST['locid']!='' && isset($_REQUEST['conid']) && $_REQUEST['conid']!='' && isset($_REQUEST['cmid']) && $_REQUEST['cmid']!='')
{
    $loc=$_REQUEST['locid'];	
    $conid=$_REQUEST['conid'];	
    $cmid=$_REQUEST['cmid'];	
    
    $sql="select payout,tenure from manage_consultancy where consultancy_id='".$conid."' and locid='".$loc."' and cm_id='".$cmid."';";
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	
	if( count($result) > 0 && $result)
	{
		echo $result[0]['payout'].'|$|'.$result[0]['tenure'];
	}
	
}



	
?>