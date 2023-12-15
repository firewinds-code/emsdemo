<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$response=array();
$result['msg']=$note=$created_by='';
if(isset($Data['appkey']) && $Data['appkey']=='insert_notes')
{
			 $created_by=$Data['created_by'];	
		     $note=$Data['note'];
			 $QueryInsert = 'Insert into notes set note="'.$note.'", created_by="'.$created_by.'", source="App";' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryInsert);
			 $mysql_error=$myDB->getLastError();
			 $result=array();
			if (empty($mysql_error) && strlen($note)<250)
				{
					$result['status']=1;
					$result['msg']="Note added successfully";
				}
	 
	       else
	           {
					$result['status']=0;
					$result['msg']='Notes should  lesser than 250 character.';
			  }
}
elseif(isset($Data['appkey']) && $Data['appkey']=='delete_notes')
{
			if(isset($Data['id']) &&  $Data['id']!="")
			{
			 $id=$Data['id'];	
			}
			 $QueryDelete = 'delete from notes where id="'.$id.'";' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->rawQuery($QueryDelete);
			 $result=array();
			if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']="Note deleted successfully";
				} 
				else
				{
					$result['status']=0;
					$result['msg']=getLastError();
				}
}
elseif(isset($Data['appkey']) && $Data['appkey']=='select_notes')
{
			if(isset($Data['created_by']) &&  $Data['created_by']!="")
			{
			 $created_by=$Data['created_by'];	
			}
			 $QuerySelect = 'select * from notes where created_by="'.$created_by.'" order by id desc ;' ;
			 $myDB =  new MysqliDb();
			 $response =$myDB->query($QuerySelect);
			 $result=array();
			if (empty($myDB->getLastError()))
				{
					$result['status']=1;
					$result['msg']='Data found';
					$result['records']=$response;
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

