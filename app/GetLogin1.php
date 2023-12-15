<?php 

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
 $mysqli=new MysqliDb();
 //$connection = $mysqli->mysqli();

header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
	$Data=json_decode($_POST,true);
date_default_timezone_set('Asia/Kolkata');
$result = array();
$response = array();
	if(isset($Data) && count($Data) > 0   )
	{
		if(isset($Data['appkey']) && $Data['appkey']==appkey)
		{
			$pwd=urldecode($Data['refrance']);
			$pwd=md5($pwd);
			$emp_id=urldecode($Data['LoginId']);
		
				$stmt = $mysqli->mysqli()->prepare("Call app_get_login(?,?)");
				$stmt->bind_param("ss", $emp_id,$pwd);
				$stmt->execute();
				$result = $stmt->get_result();
				$res =$result->fetch_all(MYSQLI_ASSOC);
				if($res)
				{
					$response['msg']="Sucessfully login";
					$response['status']="success";
					$response['employ_details']=$res[0];
				}
				else
				{
					$response['msg']="Invalid employee id and password";
					$response['status']="fail";

				}
			}
			else{
					$response['msg']="Bad Requesr";
						$response['status']="fail";
			}
	
		}
	else
	{
			$response['msg']="Invalid Request";
			$response['status']="fail";
	}	
	
	echo json_encode($response);
	?>