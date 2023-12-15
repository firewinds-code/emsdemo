<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;
if ($_REQUEST) {
    $Name = cleanUserInput($_REQUEST['qh']);
    $isin = 0;
    $emplist = ['CE01080195', 'CE05070052', 'CE0421937241', 'CE032030295', 'CE10091236'];
    foreach ($emplist as $string) {
        if (strpos($Name, $string) !== false) {
            $isin = 1;
            break;
        }
    }
    if ($isin == 1) {
        $myDB = new MysqliDb();
        $Query = "select concat(t2.client_name,'|',t1.process,'|',t1.sub_process) Process,cm_id from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id not in (select cm_id from client_status_master) order by t2.client_name;";
        //where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
        // $res = $myDB->query($Query);
        ///
        $stmt = $conn->prepare($Query);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            foreach ($res as $key => $value) {
                $result[] = $value;
            }
            $result = json_encode($result);
            echo $result;
        } else {
            echo NULL;
        }
    } else {
        $myDB = new MysqliDb();
        // $Query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
        // where  qh='" . $_REQUEST['qh'] . "' order by `Process`;";
        //where EmployeeID='".$_REQUEST['EmployeeID']."' or account_head='".$_REQUEST['EmployeeID']."' order by `Process`;";
        ///
        $sql_qh = cleanUserInput($_REQUEST['qh']);
        $query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
		where  qh=? order by `Process`;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $sql_qh);
        $stmt->execute();
        $res = $stmt->get_result();

        // $res = $myDB->query($Query);
        if ($res->num_rows > 0) {
            foreach ($res as $key => $value) {
                $result[] = $value;
            }
            $result = json_encode($result);
            echo $result;
        } else {
            echo NULL;
        }
    }
} else {
    echo NULL;
}
