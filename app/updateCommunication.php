<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$result=array();
$dataid="";
$myDB =  new MysqliDb();
if(isset($Data['appkey']) && $Data['appkey']=='updCom')
{
	            if(isset($Data['dataid']) &&  $Data['dataid']!="")
			    {
			    $dataid=$Data['dataid'];
			    }
	            $comment=$Data['comment'];
	            $date = date('Y-m-d H:i:s', time());
	            $query = "UPDATE tbl_chat_message SET acknowledge='".$comment."', acknowledgedate='".$date."', ackstatus=1 WHERE ID='".$dataid."';";
                $res =$myDB->rawQuery($query);
                if (empty($myDB->getLastError()))
				 {
					$result['msg']="Updated successfully.";
			        $result['status']=1;
				}
				else
				{
				     $result['msg']=getLastError();
			         $result['status']=0;	
				}	        
 }
else
 {
     $result['msg']="Bad Request";
     $result['status']=0;
 }
echo  json_encode($result);


?>

