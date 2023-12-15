<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time

// Main contain Header file which contains html , head , body , one default form 

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['location1']) && ($_REQUEST['client_name1']) && ($_REQUEST['process1']) && ($_REQUEST['sub_process1'])) {
    $Location = clean($_REQUEST['location1']);
    $client_name = clean($_REQUEST['client_name1']);
    $process = clean($_REQUEST['process1']);
    $cm_id = clean($_REQUEST['sub_process1']);

    $qury = "select (select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=excep_spoc) as excep_spocs, (select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=leave1_empid) as leave1_empids,(select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=leave2_empid) as leave2_empids,(select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=er_scop) as er_scops ,(select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=er_spoc2) as er_scops2,(select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=er_spoc3) as er_scops3 ,(select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=er_spoc4) as er_scops4 from new_client_master where cm_id=?";
    $sql = $conn->prepare($qury);
    $sql->bind_param("i", $cm_id);
    $sql->execute();
    $results = $sql->get_result();
    $ress = $results->fetch_row();
    $excp = $ress[0];
    $leave1 = $ress[1];
    $leave2 = $ress[2];
    $er_spoc = $ress[3];
    $er_spoc2 = $ress[4];
    $er_spoc3 = $ress[5];
    $er_spoc4 = $ress[6];

    $sql = "select (select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=QualityID) as QualityIDs,(select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=TrainingID) as TrainingIDs,(select concat(EmpName,'(',empid,')') as EmployeeName  from EmpID_Name where EmpID=OpsID) as OpsIDs,HRID,ITID,ReportsTo from downtimereqid1 where cm_id=?";
    $sel = $conn->prepare($sql);
    $sel->bind_param("i", $cm_id);
    $sel->execute();
    $resul = $sel->get_result();
    $ressu = $resul->fetch_row();
    $QualityID = $ressu[0];
    $TrainingID = $ressu[1];
    $OpsID = $ressu[2];
    $HRID = $ressu[3];
    $ITID = $ressu[4];
    $ReportsTo = $ressu[5];

    $toption = $excp;
    $toption1 = $QualityID;
    $toption2 = $TrainingID;
    $toption3 = $OpsID;
    $toption4 = $leave1;
    $toption5 =  $leave2;
    $toption6 = $er_spoc;
    $toption7 =  $er_spoc2;
    $toption8 =  $er_spoc3;
    $toption12 =  $er_spoc4;
    $toption9 =  $ITID;
    $toption10 =  $HRID;
    $toption11 =  $ReportsTo;

    $data['excep_app'] = $toption;
    $data['downtime_quality'] = $toption1;
    $data['downtime_training'] = $toption2;
    $data['downtime_ops'] = $toption3;
    $data['leave1'] = $toption4;
    $data['leave2'] = $toption5;
    $data['er_spoc'] = $toption6;
    $data['er_spoc2'] = $toption7;
    $data['er_spoc3'] = $toption8;
    $data['er_spoc4'] = $toption12;
    $data['ITID'] = $toption9;
    $data['HRID'] = $toption10;
    $data['ReportsTo'] = $toption11;
}
echo json_encode($data);
