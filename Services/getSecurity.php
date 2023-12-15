<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb_replica.php');
 

$myDB =  new MysqliDb();
	$Queryattendance = 'select secques,secans,DOB,e.EmployeeID from employee_map e inner join View_EmpinfoActive a on e.EmployeeID=a.EmployeeID WHERE e.EmployeeID="'.$_REQUEST['emp_id'].'" and DOB="'.$_REQUEST['txt_dob'].'" LIMIT 1 ';
	
		 $questionData = $myDB->query($Queryattendance);
	//print_r($questionData);
//	die;
$result=array();
	if(count ($questionData ) > 0)
		{
							//print_r( $result); 
		$result['secquestion'] =$questionData[0]['secques'];
		$result['secanswer'] =$questionData[0]['secans'];
		$result['emp_id'] =$questionData[0]['EmployeeID'];
		$result['status']=1;
		
		//print_r($resultSends); 
							
		}
	else{
			$result['secquestion'] ="";
		$result['secanswer'] ="";
		$result['emp_id'] ="";
		$result['status']=0;
	}

echo  json_encode($result);
//echo   $resultSends ; 
exit;
?>

