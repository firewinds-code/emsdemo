<?php
// Server Config file

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');

date_default_timezone_set('Asia/Kolkata');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$varadhar = '';
$empID = $response1 = $rsnofleaving = '';
if (isset($_GET['adhar']) && (trim($_GET['adhar'])) && (strlen($_GET['adhar']) == 12) && (is_numeric($_GET['adhar']) == 12)) {
	$varadhar = clean($_GET['adhar']);
}
if ($varadhar != '') {
	// $varadhar = $_GET['adhar'];
	$rsp = "SELECT distinct t2.EmployeeID,t2.EmployeeName,t2.createdby,t2.createdon,t3.location from doc_details t1 join personal_details t2 on t1.EmployeeID=t2.EmployeeID join location_master t3 on t2.location=t3.id where dov_value = ? and doc_stype='Aadhar Card' and left(t1.EmployeeID,2) !='TE'";
	// $myDB =  new MysqliDb();
	// $result = $myDB->query($rsp);
	$stmt = $conn->prepare($rsp);
	$stmt->bind_param("i", $varadhar);
	$stmt->execute();
	$result = $stmt->get_result();
	// $result2 = mysqli_fetch_array($result);
	$mysql_error = $myDB->getLastError();

	if ($result->num_rows > 0 and !empty($result)) {
		foreach ($result as $key => $value) {
			$response1 .= "<div style='border: 1px solid;padding: 3px;border-radius: 9px;'> Created BY <strong> " . $value['createdby'] . " </strong> at <strong> " . $value['location'] . " </strong> with Employee Name/Employee Id <strong> " . $value['EmployeeName'] . "/" . $value['EmployeeID'] . " </strong> on <strong> " . $value['createdon'] . " </strong> ";

			$myDB = new MysqliDb();
			$select_Queryq = ("SELECT disposition,rsnofleaving,rejoin_status from exit_emp where EmployeeID = ? order by id desc limit 1");
			$stmt1 = $conn->prepare($select_Queryq);
			$stmt1->bind_param("s", $value['EmployeeID']);
			$stmt1->execute();
			$select_Query = $stmt1->get_result();


			if ($select_Query->num_rows > 0) {
				if ($select_Query[0]['disposition'] == 'TER') {
					$response1 .= ' and Reason of Leaving is ' . $select_Query[0]['rsnofleaving'] . '|TER';
				} else {
					$response1 .= ' and Reason of Leaving is ' . $select_Query[0]['rsnofleaving'];
				}
			}
			$response1 .= '<br/></div>';
		}
		echo $response1;
	} else {
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
