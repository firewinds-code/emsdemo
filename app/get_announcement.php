<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$result=array();
$result['msg']='';

if(isset($Data['appkey']) && $Data['appkey']=='announcement')
{
			 $Queryannouncement ='SELECT Heading, createdon,Body FROM announcement order by createdon,id desc';
			 $myDB =  new MysqliDb();
			 $res =$myDB->query($Queryannouncement);
			if ($res>0)
				{
					$result['status']=1;
					$result['msg']="Data found.";
					$result['getAnnouncement']=$res;
				}
	 
	       else
	           {
					$result['status']=0;
					$result['msg']='Data not found.';
			  }
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request";
}
echo  json_encode($result);

?>

