<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$ID = clean($_REQUEST['id']);

$selectQry = 'select * from expense_travel where id=?';
$stmt = $conn->prepare($selectQry);
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
$empID = $row[1];
$empName = $row[2];
$date = $row[3];
$reqType = $row[17];
$remarks = $row[15];
$result->num_rows;

if ($result->num_rows === 1) {
    $delLog = "insert into expense_log(EmployeeID,empName,date,reqType,remarks)values(?,?,?,?,?)";
    $stmt1 = $conn->prepare($delLog);
    $stmt1->bind_param("sssss", $empID, $empName, $date, $reqType, $remarks);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    // die;

    $sql = 'delete from expense_travel where id=?';
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("s", $ID);
    $stmt2->execute();
    $Result2 = $stmt2->get_result();
    if ($stmt2->affected_rows === 1) {
        echo "Done|Row Deleted Successfully";
    } else {
        echo "No|Row Not Deleted Try Again";
    }
}
