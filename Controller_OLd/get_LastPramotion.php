<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = clean($_REQUEST['ID']);

$sql = "select concat(date_format(log_date,'%b'),' ',year(log_date)) as Apr from tbl_log_altaration where type='Designation' and EmployeeID= ? order by id desc limit 1";
$selectQ = $conn->prepare($sql);
$selectQ->bind_param("s", $id);
$selectQ->execute();
$result = $selectQ->get_result();
$resu = $result->fetch_row();
if ($result->num_rows > 0 && $result) {
	echo  clean($resu[0]);
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
