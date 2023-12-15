<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$ID = clean($_REQUEST['id']);

$selectQry = 'select * from expense_food where id=?';
$stmt1 = $conn->prepare($selectQry);
$stmt1->bind_param("i", $ID);
$stmt1->execute();
// $resuLT = $stmt1->get_result();
// $row = $resuLT->fetch_row();
// echo "v" . $emailid = $row[0];
$Result = $stmt1->get_result();
$row = $Result->fetch_row();

$empID = $row[1];
$empName = $row[2];
$date = $row[3];
$reqType = $row[9];
$remarks = $row[7];
$Result->num_rows;

if ($Result->num_rows === 1) {
    // <!-- if (count($res) > 0) { -->
    $delLog = "insert into expense_log(EmployeeID,empName,date,reqType,remarks)values(?,?,?,?,?)";
    $stmt2 = $conn->prepare($delLog);
    $stmt2->bind_param("sssss", $empID, $empName, $date, $reqType, $remarks);
    $stmt2->execute();
    $Result2 = $stmt2->get_result();


    $sql = 'delete from expense_food where id=?';
    $stmt3 = $conn->prepare($sql);
    $stmt3->bind_param("s", $ID);
    $stmt3->execute();
    $Result3 = $stmt3->get_result();
    if ($stmt3->affected_rows === 1) {
        echo "Done|Row Deleted Successfully";
    } else {
        echo "No|Row Not Deleted Try Again";
    }
}
