<?php  
// Server Config file

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb_replica.php');

date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$empID=$response1=$rsnofleaving='';

if(isset($_GET['adhar'])){
	$varadhar= $_GET['adhar'];
	$rsp="select distinct t2.EmployeeID,t2.EmployeeName,t2.createdby,t2.createdon,t3.location from doc_details t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join location_master t3 on t2.location=t3.id where dov_value='".$varadhar."' and doc_stype='Aadhar Card' and left(t1.EmployeeID,2) !='TE'";
	$myDB =  new MysqliDb();
 	$result =$myDB->query($rsp);
	$mysql_error = $myDB->getLastError();
	
	if(count($result)>0 and !empty($result))
	{
		foreach($result as $key=>$value)
		{
			$response1 .= "<div style='border: 1px solid;padding: 3px;border-radius: 9px;'> Created BY <strong> ".$value['createdby']." </strong> at <strong> ".$value['location']." </strong> with Employee Name/Employee Id <strong> ".$value['EmployeeName']."/".$value['EmployeeID']." </strong> on <strong> ".$value['createdon']." </strong> ";
			
			$myDB = new MysqliDb();
			$select_Query=$myDB->rawQuery("Select disposition,rsnofleaving,rejoin_status from exit_emp where EmployeeID='".$value['EmployeeID']."' order by id desc limit 1");
			if(count($select_Query)>0){
				if($select_Query[0]['disposition']=='TER'){
					$response1 .=' and Reason of Leaving is '.$select_Query[0]['rsnofleaving'].'|TER';
				}else{
					$response1 .=' and Reason of Leaving is '.$select_Query[0]['rsnofleaving'];
				}
				
			}
			$response1 .='<br/></div>';
		}
		echo $response1;
	}
	else
	{
		echo 'Employee does not exit !';
	}
	
	
	/*$response = file_get_contents("http://lb.cogentlab.com:8081/Investment/checkaadhar.php?AadharNo=".$varadhar."");
	$catchData=strip_tags($response);
	$catchData_array=explode('}',$catchData);
	 $response1= trim(end($catchData_array)); 
	if(trim($response1)=='Employee does not exit !'){
			echo $response;
	}
	else
	{
		$existing_user=@end(explode('/',$response1));
		$Emp_array=explode('on',$existing_user);
		if(isset($Emp_array[0]))
		{
			$empID=$Emp_array[0];
			$myDB = new MysqliDb();
			$select_Query=$myDB->rawQuery("Select disposition,rsnofleaving,rejoin_status from exit_emp where EmployeeID='".$empID."' order by id desc limit 1");
			if(count($select_Query)>0){
				if($select_Query[0]['disposition']=='TER'){
					$rsnofleaving=' and Reason of Leaving is '.$select_Query[0]['rsnofleaving'].'|TER';
				}else{
					$rsnofleaving=' and Reason of Leaving is '.$select_Query[0]['rsnofleaving'];
				}
				
			}
			
		}
		echo $response1.$rsnofleaving;
	}*/
}

?>

