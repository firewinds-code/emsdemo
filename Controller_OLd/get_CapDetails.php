<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$EMP = clean($_REQUEST['EmpID']);
$date1 = clean($_REQUEST['Date1']);
$date2 = clean($_REQUEST['Date2']);
//$sql = "select concat(date_format(log_date,'%b'),' ',year(log_date)) as Apr from tbl_log_altaration where type='Designation' and EmployeeID= '".$_REQUEST['EmpID']."' order by id desc limit 1";
$sql = "select count(*) as count from corrective_action_form where employee_id=? and (cast(created_at as date) between ? and ?);";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("sss", $EMP, $date1, $date2);
$selectQ->execute();
$result = $selectQ->get_result();
$resu = $result->fetch_row();

if ($result->num_rows > 0 && $result) {
	//echo $result[0]['id'].'|$|'.$result[0]['created_at'];
	echo is_numeric($resu[0]) . '|$|';
	/*foreach($result as $key=>$value)
    {
    	foreach($value as $k => $Details)
		{
			echo $Details.'|$|';
		}
    }*/
}


//$sql = "select concat(date_format(log_date,'%b'),' ',year(log_date)) as Apr from tbl_log_altaration where type='Designation' and EmployeeID= '".$_REQUEST['EmpID']."' order by id desc limit 1";
$sql = "select id from corrective_action_form where employee_id=? and (cast(created_at as date) between ? and ?) order by id desc limit 3;";

$selectQ = $conn->prepare($sql);
$selectQ->bind_param("sss", $EMP, $date1, $date2);
$selectQ->execute();
$result = $selectQ->get_result();

if ($result->num_rows > 0 && $result) {
	//echo $result[0]['id'].'|$|'.$result[0]['created_at'];
	//echo $result[0]['id'].'|$|';
	foreach ($result as $key => $value) {
		foreach ($value as $k => $Details) {
			echo $Details . '|$|';
		}
	}
}
