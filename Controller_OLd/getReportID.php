<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$loc = '';
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$empid = clean($_REQUEST['empid']);
if (isset($_REQUEST['empid']) && $empid != "") {
	$empid = clean($_REQUEST['empid']);
	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	$sql = 'select distinct reportID from report_map where EmpID=?;';
	$mysql_error = $myDB->getLastError();
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $empid);

	$stmt->execute();
	$result = $stmt->get_result();
	$count = $result->num_rows;
	if ($result->num_rows > 0) {
		//echo '<option value="NA" >---Select---</option>';
		foreach ($result as $key => $value) {
			echo $value['reportID'] . '|$|';
			//echo '<option value="'.$value['cm_id'].'"  >'.$value['Process'].'</option>';

		}
	}
}
