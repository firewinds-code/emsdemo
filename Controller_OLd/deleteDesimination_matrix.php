<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

$ID = clean($_REQUEST['id']);
$sql = 'delete from desimination_matrix where id=?';

// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ID);
$delt = $stmt->execute();

if ($delt) {

	echo "Done|Row Deleted Successfully";
	/*if(mysql_affected_rows()>0)
		{
			echo "Done|Row Deleted Affected Row are :".mysql_affected_rows();
		}
		else
		{
			echo "No|Row Not Deleted:<code> May be action taken on it </code>";
		}*/
} else {
	echo "No|Row Not Deleted Try Again";
}
