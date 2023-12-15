<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
//print_r($Data);
$response=array();
$myDB =  new MysqliDb(); 
if(isset($Data['appkey']) && $Data['appkey']!='')
{
	if( $Data['appkey']=='insert_bio')
          {
			 $EmployeeID=$Data['EmployeeID'];
  	         $lat=$Data['lat'];
		     $lng=$Data['lng'];
		     $WorkingType=$Data['workingType'];
		    // $DateOn=$Data['DateOn'];
		     //$PunchTime=$Data['PunchTime'];
		     $DateOn= new datetime();
	         $DateOn=$DateOn->format('Y-m-d');
		     $PunchTime= new datetime();
	         $PunchTime=$PunchTime->format('H:i:s');
		     $SelectWFH='select work_from from roster_temp where EmployeeID="'.$EmployeeID.'" and DateOn="'.$DateOn.'"';
		     $myDB= new MysqliDb();
	         $results =$myDB->query($SelectWFH);
	         if(count($results)> 0)
	         {
	         	$work_from=$results[0]['work_from'];
	         	if($work_from =='WFH' || $work_from =='WFO')
	         	{
			 	     $QueryInsertl = 'insert into login_lat_lng (EmployeeID, lat, lng, source) values ("'.$EmployeeID.'","'.$lat.'","'.$lng.'","App'.$WorkingType.'");' ;
			        $myDB =  new MysqliDb();
			        $response =$myDB->rawQuery($QueryInsertl);
			        $result=array();
						if (empty($myDB->getLastError()))
							{
								 $QueryInsert = 'insert into biopunchcurrentdata (PunchTime,DateOn,EmpID,EmployeeID) values("'.$PunchTime.'","'.$DateOn.'","'.$EmployeeID.'","App");' ;
							    $myDB =  new MysqliDb();
								$response =$myDB->rawQuery($QueryInsert);
								$result=array();
								if (empty($myDB->getLastError()))
									{
										$result['status']=1;
										$result['msg']="Time punch successful";
									} 
									else
									{
										$result['status']=0;
										$result['msg']=getLastError();
									}
							} 
							else
							{
								$result['status']=0;
								$result['msg']=getLastError();
							}
					
				}
				else
				{
				    $result['status']=0;
				    $result['msg']='Your request is invalid.';	
			   }
			 }
			 else
			 {
			 	$result['status']=0;
				$result['msg']='Please set employeeid.';
			 }   
		}
		else
		{
			    $result['status']=0;
				$result['msg']="Please set valid appkey.";
		}
}
else
{
	    $result['status']=0;
		$result['msg']="Bad Request.";
}
echo  json_encode($result);
?>

