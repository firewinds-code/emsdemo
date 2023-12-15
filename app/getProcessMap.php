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
$result1=array();
$rrTOID=$rTOID=$rrrTOID=$rcontact=$rname=$rdesignation=$rrcontact=$rrname=$rrdesignation=$rrrcontact=$rrrdesignation=$rrname='';
$myDB =  new MysqliDb();
//print_r($Data);
if(isset($Data['appkey']) && $Data['appkey']=='pmap')
{
  if(isset($Data['EmployeeID'])&& $Data['EmployeeID']!='')
  { 
        $EmployeeID = $Data['EmployeeID'];
        $rptToEmp="select ReportTo from status_table   where employeeid='".$EmployeeID."'";
		$myDB= new MysqliDb();
		$resultrptToEmp =$myDB->query($rptToEmp);
		$MysqliError = $myDB->getLastError();
			if(isset($resultrptToEmp[0]['ReportTo']))
			{
				$rTOID=$resultrptToEmp[0]['ReportTo'];
				if($rTOID!="")
				{
					$detailsR=$myDB->query("select emp_dt_map.designation,personal_details.EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID inner join emp_dt_map on personal_details.EmployeeID=emp_dt_map.EmployeeID where personal_details.EmployeeID = '".$rTOID."' limit 1");
					               if(count($detailsR) > 0 && $detailsR)
									{
										$rcontact = $detailsR[0]['mobile'];
										$rname = $detailsR[0]['EmployeeName'];
										$rdesignation = $detailsR[0]['designation'];
									}
					
				}
			}
			if($rTOID!="")
			{
				$rptTortid="select ReportTo from status_table   where employeeid='".$rTOID."'";
				$myDB= new MysqliDb();
				$resultrptTortid =$myDB->query($rptTortid);
				$MysqliError1 = $myDB->getLastError();
					if(isset($resultrptTortid[0]['ReportTo']))
					{
						$rrTOID=$resultrptTortid[0]['ReportTo'];
					}
					if($rrTOID!="")
					{
						$detailsRR=$myDB->query("select emp_dt_map.designation,personal_details.EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID inner join emp_dt_map on personal_details.EmployeeID=emp_dt_map.EmployeeID where personal_details.EmployeeID = '".$rrTOID."' limit 1");
						               if(count($detailsRR) > 0 && $detailsRR)
										{
											$rrcontact = $detailsRR[0]['mobile'];
											$rrname = $detailsRR[0]['EmployeeName'];
											$rrdesignation = $detailsRR[0]['designation'];
										}
						
					}
			}
			if($rrTOID!="")
			{
				$rrrptTortid="select ReportTo from status_table   where employeeid='".$rrTOID."'";
				$myDB= new MysqliDb();
				$resultrrrto =$myDB->query($rrrptTortid);
				$MysqliError2 = $myDB->getLastError();
					if(isset($resultrrrto[0]['ReportTo']))
					{
						$rrrTOID=$resultrrrto[0]['ReportTo'];
					}
					if($rrrTOID!="")
					{
						$detailsRRR=$myDB->query("select emp_dt_map.designation,personal_details.EmployeeName,contact_details.mobile from personal_details inner join contact_details on contact_details.EmployeeID = personal_details.EmployeeID inner join emp_dt_map on personal_details.EmployeeID=emp_dt_map.EmployeeID where personal_details.EmployeeID = '".$rrrTOID."' limit 1");
						               if(count($detailsRRR) > 0 && $detailsRRR)
										{
											$rrrcontact = $detailsRRR[0]['mobile'];
											$rrrname = $detailsRRR[0]['EmployeeName'];
											$rrrdesignation = $detailsRRR[0]['designation'];
										}
						
					}
			}
			if(empty($MysqliError) && !empty($rTOID))
			{
				$result1[0]=$rTOID.'|'.$rcontact.'|'.$rname.'|'.$rdesignation;
			}
			if(!empty($rrTOID) && $rTOID!= $rrTOID && empty($MysqliError1) )
			{
				$result1[1]=$rrTOID.'|'.$rrcontact.'|'.$rrname.'|'.$rrdesignation;	
			}
			if(!empty($rrrTOID) && $rrrTOID!= $rrTOID && $rrrTOID!= $rTOID  && empty($MysqliError2))
			{
				$result1[2]=$rrrTOID.'|'.$rrrcontact.'|'.$rrrname.'|'.$rrrdesignation;
		    }
		    if(empty($MysqliError) || empty($MysqliError1) ||  empty($MysqliError2))
		    {
				$result['msg']="Data found";
                $result['status']=1;
                $result['processMap']=$result1;
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

