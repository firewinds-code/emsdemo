<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

if (isset($_REQUEST['ijp'])) {

    $select = " select i.EmployeeID,m.ijp_name, m.schedule_intro from ijp_emp i join ijp_master m on m.id=i.ijpID where flag='1' and EmployeeID='" . $_SESSION['__user_logid'] . "' and ijpID='" . $_REQUEST['ijp'] . "'";

    $myDB = new MysqliDb();
    $result = $myDB->query($select);

    $ijpname = $result[0]['ijp_name'];
    $ijpdate = $result[0]['schedule_intro'];
    $date = date_create($ijpdate);
    $date_int = date_format($date, "d-M Y H:i");
    $data['ijp_name'] = $ijpname;
    $data['schedule_intro'] = $date_int;
}
echo json_encode($data);
