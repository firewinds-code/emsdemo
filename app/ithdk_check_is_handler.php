<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
//table empid,Name, dateon status(0/1) //page add/remove/edit(update status as 0/1)
$empList=array('CE0820912502','CE10091236','CE06080411','CE061510258','CE121513568','CE021929775','CE03070014','CE051726977');
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='getHandlers' && isset($Data['EmployeeID']) && isset($Data['EmployeeID']) )
{
		
		if (in_array(strtoupper($Data['EmployeeID']), $empList))
		{
			$result['status']=1;
			$result['msg']='Employee is a handler.';
			$result['isHandler']=1;
		}
		else
		{
			$result['status']=0;
			$result['msg']='Employee is not a handler.';
			$result['isHandler']=0;
		}

}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
		
}
echo  json_encode($result);

?>

