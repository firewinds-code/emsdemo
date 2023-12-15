<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$id = $_REQUEST['id'];


$sql = 'delete from site_master where id=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$Result2 = $stmt->get_result();
if ($stmt->affected_rows === 1) {
	/* Delete from Seat Master */
	$sql1 = 'delete from site_seat_master where site_id=?';
	$stmt1 = $conn->prepare($sql1);
	$stmt1->bind_param("s", $id);
	$stmt1->execute();
	/* Delete from Seat Master */
	
	/* Delete from Cost Master */
	$sql2 = 'delete from site_cost_master where site_id=?';
	$stmt2 = $conn->prepare($sql2);
	$stmt2->bind_param("s", $id);
	$stmt2->execute();
	/* Delete from Cost Master */
	echo "Deleted Successfully";
} else {
	echo "Not Deleted Try Again";
}

