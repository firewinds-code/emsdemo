
<?php

require_once(__dir__ . '/../Config/init.php');
 require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$id = $_REQUEST['id'];


$sql = 'delete from site_seat_master where id=?';
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param("s", $id);
$stmt2->execute();
$Result2 = $stmt2->get_result();
if ($stmt2->affected_rows === 1) {
	echo "Deleted Successfully";
} else {
	echo "Not Deleted Try Again";
}

