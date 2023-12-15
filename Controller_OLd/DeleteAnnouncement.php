<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'php_mysql_class.php');
// require_once(CLS.'MysqliDb.php');

// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();

if (isset($_REQUEST['ID']) && $_REQUEST['ID'] != "") {
	if (is_numeric($_REQUEST['ID'])) {
		$id = clean($_REQUEST['ID']);
	}
}

$sql = 'DELETE FROM announcement WHERE id=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
// $result = $myDB->rawQuery($sql);
// $row_count = $myDB->count;
// $mysql_error = $myDB->getLastError();

if ($stmt->affected_rows === 1) {
	echo "Row Deleted Successfully :";
} else {
	echo "Row Not Deleted Try Again :";
}
