<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB =  new MysqliDb();
$conn = $myDB->dbConnect();

$sqlConnect = "select distinct t1.ID,t1.email_address,t3.EmployeeID,t3.emp_status from add_email_address t1 left join contact_details t2 on t1.email_address=t2.ofc_emailid left join employee_map t3 on t2.EmployeeID=t3.EmployeeID where t1.email_address not in ('ithelpdesk_bangalore@cogenteservices.com','hr.mangalore@cogenteservices.com','mis.mangalore@cogenteservices.com','ithelpdesk.mangalore@cogenteservices.com','ithelpdesk_vadodara@cogenteservices.com','ithelpdesk_meerut@cogenteservices.com','ithelpdesk_bareilly@cogenteservices.com','ithelpdesk.noida@cogenteservices.com','ithelpdesk@cogenteservices.com','hr.bangalore@cogenteservices.com','hr.vadodara@cogenteservices.com','hr.meerut@cogenteservices.com','hr.bareilly@cogenteservices.com','hr.mumbai@cogenteservices.com','hr.hebbal@cogenteservices.com','itsup.atp@cogenteservices.com','ithelpdesk.nasik@cogenteservices.com','ithelpdesk.thane@cogenteservices.com') and t3.emp_status='InActive'";
$sql = $conn->prepare($sqlConnect);
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows > 0) {
    foreach ($result as $key => $value) {
        $ID = $value['ID'];
        $sql = 'delete from add_email_address where ID=?';
        $del = $conn->prepare($sql);
        $del->bind_param("i", $ID);
        $del->execute();
        $result = $del->get_result();

        $sql = 'delete from manage_module_email where emailID=?';
        $del = $conn->prepare($sql);
        $del->bind_param("i", $ID);
        $del->execute();
        $result = $del->get_result();
        echo "Done|Row Deleted Successfully";
    }
}
