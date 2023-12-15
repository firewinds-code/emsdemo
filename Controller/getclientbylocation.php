<?php

require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');

$sql = "select distinct t2.client_name from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id  where cm_id not in (select cm_id from client_status_master) and location='" . $_REQUEST['loc'] . "' order by t2.client_name";
$myDB = new MysqliDb();
$result = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if (count($result) > 0 && $result) {
    echo '<option value="NA" >---Select---</option>';
    echo '<option value="Other">Other</option>';
    foreach ($result as $key => $value) {
        echo '<option value="' . $value['client_name'] . '" >' . $value['client_name'] . '</option>';
    }
} else {
    echo '<option value="NA" >---Select---</option>';
    echo '<option value="Other">Other</option>';
}
