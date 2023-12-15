<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
ini_set('display_errors', '1'); 
ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
 header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$result=array();
$result1=array();
$myDB =  new MysqliDb();
//print_r($Data);
if(isset($Data['appkey']) && $Data['appkey']=='srchlatlng')
{
  if(isset($Data['EmployeeID'])&& $Data['EmployeeID']!='')
  { 
        $employeeID=$Data['EmployeeID'];
        $dateFrom=$Data['DateFrom'];
        $dateTo=$Data['DateTo'];
        $Select="select A.* ,B.MaxTime,B.Maxlat,B.Maxlng from (SELECT  mt.EmployeeID,t.CreatedDate,t.MinDate as'MinTime',mt.lat as 'Minlat',mt.lng  as 'Minlng'FROM ems.login_lat_lng mt INNER JOIN(SELECT EmployeeID,cast(created_on as date) as CreatedDate, min(cast(created_on as datetime)) AS MinDate FROM ems.login_lat_lng GROUP BY EmployeeID,cast(created_on as date) ) t ON mt.EmployeeID = t.EmployeeID AND cast(mt.created_on as datetime)= cast(t.MinDate as datetime))A INNER JOIN (SELECT  mt.EmployeeID,t.CreatedDate,t.MaxDate as'MaxTime',mt.lat as 'Maxlat',mt.lng  as 'Maxlng' FROM ems.login_lat_lng mt INNER JOIN (  SELECT EmployeeID,cast(created_on as date) as CreatedDate, max(cast(created_on as datetime)) AS MaxDate FROM ems.login_lat_lng GROUP BY EmployeeID,cast(created_on as date) ) t ON mt.EmployeeID = t.EmployeeID AND cast(mt.created_on as datetime)= cast(t.MaxDate as datetime))B ON A.EmployeeID=B.EmployeeID and A.CreatedDate=B.CreatedDate  where A.EmployeeID='".$employeeID."' and cast(A.CreatedDate as date) between cast('".$dateFrom."'  as date) and cast( '".$dateTo."' as date) order by A.CreatedDate asc;";
		$myDB= new MysqliDb();
		$results =$myDB->query($Select);
		$MysqliError = $myDB->getLastError();
		    if(empty($MysqliError))
		    {
				$result['msg']="Data found";
                $result['status']=1;
                $result['LatLngRecords']=$results;
			}
			else
			{
				$result['msg']="Data not found";
                $result['status']=0;
			}
  } 
  else
  {
  	 $result['msg']="Set EmployeeID.";
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

