<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;


if (isset($_REQUEST['EmployeeID']) && (trim($_REQUEST['EmployeeID'])) && (strlen($_REQUEST['EmployeeID']) <= 15)) {
    if ((substr($_REQUEST['EmployeeID'], 0, 2) == 'CE') || (substr($_REQUEST['EmployeeID'], 0, 2) == 'MU')) {
        $empid = clean($_REQUEST['EmployeeID']);
    }
}


if ($_REQUEST) {
    $myDB = new MysqliDb();
    /*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
    where  qh='".$_REQUEST['qh']."' order by `Process`;";*/
    $isin = 0;
    $emplist = ["CE01080195"];
    //$emplist=["CE01080195","CE061930045","CE061930050","CE071728286","CE071728536","CE071930074","CE121622091","CE101930165","CE121829689","CE121829697"];	
    foreach ($emplist as $string) {
        if (strpos($empid, $string) !== false) {
            $isin = 1;
            break;
        }
    }
    $sql_usertype = cleanUserInput($_REQUEST['user_type']);
    $sql_client = cleanUserInput($_REQUEST['Client']);
    $sql_process = cleanUserInput($_REQUEST['Process']);
    if ($sql_usertype == 'Demo') {
        // $Query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp where client_name='" . $_REQUEST['Client'] . "' order by Process";
        ///
        $query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp where client_name=? order by Process";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $sql_client);
    } else {
        if ($empid == 'CE10091236' || $empid == 'CE03070003' || $isin == 1) {
            $Query = "select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp  order by Process";
            ///
            $stmt = $conn->prepare($Query);
        } else {
            /*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
		where  Process in (select distinct Process from whole_details_peremp where EmployeeID='".$empid."') order by `Process`";*/

            /*$Query="select distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp
		where ( EmployeeID='".$empid."' or account_head='".$empid."' or ReportTo='".$empid."' or qh='".$empid."' or oh='".$empid."' 
		or th='".$empid."' or Qa_ops='".$empid."')";*/

            /*$Query="select  distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp 
		where  Process in (select distinct Process from whole_details_peremp where ( EmployeeID='".$empid."' or
		 account_head='".$empid."' or ReportTo='".$empid."' or qh='".$empid."' or oh='".$empid."' or th='".$empid."') ) order by `Process`;";*/
            // 	$Query = "select distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp
            // where Process='" . $_REQUEST['Process'] . "' or qh='" . $empid . "' or oh='" . $empid . "'  or account_head='" . $empid . "' order by `Process`";
            ///

            $query = "select distinct concat(clientname,'|',Process,'|',sub_process) Process ,cm_id from whole_details_peremp
		where Process=? or qh=? or oh=? or account_head=? order by `Process`";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("siii", $sql_process, $empid, $empid, $empid);
        }
    }


    // $res = $myDB->query($Query);
    ///

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
    echo NULL;
}
