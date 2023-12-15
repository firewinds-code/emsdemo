<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$date1 = clean($_REQUEST['Date1']);
$date2 = clean($_REQUEST['Date2']);
$EMP = clean($_REQUEST['EmpID']);
//$sql = "select concat(date_format(log_date,'%b'),' ',year(log_date)) as Apr from tbl_log_altaration where type='Designation' and EmployeeID= '".$_REQUEST['EmpID']."' order by id desc limit 1";
$sql = "select t1.id,t1.created_at from corrective_action_form t1 where (cast(t1.created_at as date) between ? and ?) and employee_id=? and statusHead='Approved' and statusHr='Approved' order by t1.created_at desc limit 1;";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("sss", $date1, $date2, $EMP);
$selectQ->execute();
$result = $selectQ->get_result();

if ($result->num_rows > 0 && $result) {
	echo $result[0]['id'] . '|$|' . $result[0]['created_at'];
	/*foreach($result as $key=>$value)
    {
    	foreach($value as $k => $Details)
		{
			echo $Details.'|$|';
		}
    }*/
}
/*else
	{
		echo 'No Comment ';
		
	}*/
