<?php
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');     
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$result = array();
$EMPID = $CLID = $Proc = $SubProc = $DOJ = $Ageing = $Query = null;

if ($_REQUEST['cmid'] && (strlen($_REQUEST['cmid']) <= 5)) {
	if (is_numeric($_REQUEST['cmid'])) {
		$cmid = clean($_REQUEST['cmid']);
	}
}

if ($_REQUEST) {
	//if other process QA etc required
	// 	$Query = "select t.EmployeeID, EmployeeName as Name,Designation from
	// (select Qa_ops as EmployeeID,a.cm_id,'QA' as Designation from status_table s inner join ActiveEmpID a on a.EmployeeID=s.EmployeeID where  cm_id='" . $cmid . "'
	// union
	// select Trainer as EmployeeID,cm_id,'Trainer' as Designation from status_training s inner join ActiveEmpID a on a.EmployeeID=s.EmployeeID where cm_id='" . $cmid . "'
	// union 
	// select ReportTo as  EmployeeID,cm_id,'TL' as Designation from status_table s inner join ActiveEmpID a on a.EmployeeID=s.EmployeeID where cm_id='" . $cmid . "'
	// )t inner join ActiveEmpID b on b.EmployeeID=t.EmployeeID left join personal_details p on t.EmployeeID=p.EmployeeID where b.emp_level='EXECUTIVE' ";
	$Query = "select t.EmployeeID, EmployeeName as Name,Designation from
(select Qa_ops as EmployeeID,a.cm_id,'QA' as Designation from status_table s inner join ActiveEmpID a on a.EmployeeID=s.EmployeeID where  cm_id=?
union
select Trainer as EmployeeID,cm_id,'Trainer' as Designation from status_training s inner join ActiveEmpID a on a.EmployeeID=s.EmployeeID where cm_id=?
union 
select ReportTo as  EmployeeID,cm_id,'TL' as Designation from status_table s inner join ActiveEmpID a on a.EmployeeID=s.EmployeeID where cm_id=?
)t inner join ActiveEmpID b on b.EmployeeID=t.EmployeeID left join personal_details p on t.EmployeeID=p.EmployeeID where b.emp_level='EXECUTIVE' ";

	//where b.emp_level='EXECUTIVE' "; 

	//if other process QA etc not required
	/*$Query="select t.EmployeeID,EmployeeName,Designation from (
select  Qa_ops as EmployeeID , 'QA' as Designation from status_table s
union
select Trainer as EmployeeID , 'Trainer' as Designation from status_training s
union 
select ReportTo as  EmployeeID ,'TL' as Designation from status_table s 
)t inner join ActiveEmpID b on b.EmployeeID=t.EmployeeID 
left join personal_details p on t.EmployeeID=p.EmployeeID where b.cm_id='".$_REQUEST['cmid']."' ";*/
	$dataarray["Status"] = "0";
	$stmt = $conn->prepare($Query);
	$stmt->bind_param("iii", $cmid, $cmid, $cmid);
	$stmt->execute();
	$res = $stmt->get_result();
	// print_r($res);
	// die;
	// $res = $myDB->query($Query);
	if ($res) {
		/*foreach($res as $key=>$value)
					{
						$result[] = $value;
		   			}*/
		//$result = json_encode($result);
		$dataarray["data"] = $res;
		$dataarray["Status"] = "1";
	} else {
		$dataarray["data"] = "CMID NOT EXIST";
		$dataarray["Status"] = "0";
	}
} else {
	$dataarray["data"] = "invalid request";
	$dataarray["Status"] = "2";
}
echo  json_encode($dataarray);
	//print_r($dataarray);
