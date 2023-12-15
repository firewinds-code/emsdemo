<?php 
// Server Config file
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors','1');
ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
$doc_file='';
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}


$EmployeeID='';
$mrgstat='hidden';

if(isset($_GET['id']) && trim($_GET['id'])!="")
{
	
	$_SESSION['__interview_id']=$_GET['id'];
	$myDB=new MysqliDb();
 	$SelectQuery="select EmployeeID from personal_details where INTID='". $_SESSION['__interview_id']."' ";
	$result2=$myDB->rawQuery($SelectQuery);
	if (count($result2)>0) {
		$oldEmp=$result2[0]['EmployeeID'];
		echo "<script>location.href='".URL."View/empsave?empid=".$oldEmp."'</script>";
	} 
	else
	{
		$myDB=new MysqliDb();
		$sql="call getEmpID()";
		$orderID=$myDB->rawQuery($sql);
		$error = $myDB->getLastError();
		if (empty($error)) {
			$getEmpID = $orderID[0]['empid'];
			if ($getEmpID < 10) {
				$EmployeeID = "TE".date('my',time()).'000'.$getEmpID;
			} else if ($getEmpID >= 10 && $getEmpID < 100) {
				$EmployeeID = "TE".date('my',time()).'00'.$getEmpID;
			} else if ($getEmpID >= 100 && $getEmpID < 1000) {
				$EmployeeID = "TE".date('my',time()).'0'.$getEmpID;
			} else {
				$EmployeeID = "TE".date('my',time()).''.$getEmpID;
			}
		}
		
		//echo "tempid=".$EmployeeID="TE121977473";
		$int_url='';
		$intid=$_SESSION['__interview_id'];
		$int_url=INTERVIEW_URL."getSalary.php?intid=".$intid;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $int_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);	
		$salary_array=json_decode($data);	
		
		//print_r($data_array);
		if(count($salary_array)>0)
		{
			 $insertData="call manageOfferSalary('".$salary_array->salary."','".$intid."','".$EmployeeID."','".$_SESSION['__user_logid']."')";
			$myDB=new MysqliDb();
			$myDB->query($insertData);
			$error = $myDB->getLastError();	
			//echo $error;
			if (empty($error))
			{
				//echo 'Hiiewewdsdsd';
				//echo "https://ems.cogentlab.com/erpm/Services/getCandidateInfoData.php?intid=".$intid."&tempid=".$EmployeeID."&hrid=".$_SESSION['__user_logid']."&loc=".$_SESSION['__location'];
				$url = "https://ems.cogentlab.com/erpm/Services/getCandidateInfoData.php?intid=".$intid."&tempid=".$EmployeeID."&hrid=".$_SESSION['__user_logid']."&loc=".$_SESSION['__location'];
		//echo $url;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
				
				echo "<script>location.href='".URL."View/empsave?empid=".$EmployeeID."&intid=".$intid."'</script>";
				
			}
				
			//echo $data;
			
		}
		
	}
}

?>