<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 
$ijp = clean($_REQUEST['ijp']);
$EmployeeID = clean($_SESSION['__user_logid']);
if (isset($_REQUEST['ijp'])) {
    $myDB = new MysqliDb();
    $conn = $myDB->dbConnect();
    $select = " select i.EmployeeID,m.ijp_name, m.schedule_intro from ijp_emp i join ijp_master m on m.id=i.ijpID where flag='1' and EmployeeID=? and ijpID=?";
    $sel = $conn->prepare($select);
    $sel->bind_param("si", $EmployeeID, $ijp);
    $sel->execute();
    $results = $sel->get_result();
    $result = $results->fetch_row();
    // $result = $myDB->query($select);

    $ijpname = $result[1];
    $ijpdate = $result[2];
    $date = date_create($ijpdate);
    $date_int = date_format($date, "d-M Y H:i");
    $data['ijp_name'] = $ijpname;
    $data['schedule_intro'] = $date_int;
}
echo json_encode($data);
