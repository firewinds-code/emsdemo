<?php

// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$myDB = new MysqliDb();

$sql = 'select * from asset_employee where EmpID="' . $_REQUEST['ID'] . '" and status="Assigned" and return_flag=0';
$result_all = $myDB->query($sql);
$mysql_error = $myDB->getLastError();
if ($result_all && count($result_all) > 0) {

	echo "Asset";
} else {


	$sql = 'select wh.EmployeeID,wh.EmployeeName,wh.DOJ,wh.Process,wh.clientname,wh.sub_process,wh.designation,pd.EmployeeName as `Supervisor`,pd1.img from whole_details_peremp wh left outer join personal_details pd1 on pd1.EmployeeID = wh.EmployeeID left outer join personal_details pd on pd.EmployeeID = wh.ReportTo where wh.EmployeeID = "' . $_REQUEST['ID'] . '" and wh.account_head = "' . $_SESSION['__user_logid'] . '"';

	//$sql = 'select t1.EmployeeID,t2.EmpName as EmployeeName,t1.dateofjoin as DOJ,t3.process as Process,t4.client_name as clientname,t3.sub_process,t6.Designation as designation,t8.EmpName as `Supervisor`,t2.img from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join new_client_master t3 on t1.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id join df_master t5 on t1.df_id=t5.df_id join designation_master t6 on t5.des_id=t6.id join status_table t7 on t1.EmployeeID=t7.EmployeeID join EmpID_Name t8 on t7.ReportTo=t8.EmpID where t1.employeeid="' . $_REQUEST['ID'] . '" and t3.account_head="' . $_SESSION['__user_logid'] . '" and t1.emp_status="Active" and t1.EmployeeID not in (select EmpID from asset_employee where status="Assigned")';

	$img = $table = '';

	$result_all = $myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if ($result_all && count($result_all) > 0) {

		foreach ($result_all as $key => $value) {
			$img = '<img alt="user" style="height: 200px;border-radius: 4px;margin: 0px;width: 200px;border: 1px solid #a9a8a8;" src="';

			if (file_exists("../Images/" . $value['img']) && $value['img'] != '') {
				$img .= "../Images/" . $value['img'];
			} else {
				$img .= "../Style/images/agent-icon.png";
			}
			$img .= '"/>';
			$table .= '<div style="font-weight: bold;color: #57734e;padding: 8px;width: 216px;" class="col s2 m2">' . $img . '</div>';

			$table .= '<div style="font-weight: bold;color: #57734e;padding-left:20px;padding-top:20px;" class="col s9 m9"><span style="color:black;width:150px;float:left;">Name  </span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['EmployeeName'] . '<span class="text-danger">&nbsp;(&nbsp;' . $value['EmployeeID'] . '&nbsp;)&nbsp;</span><br />';

			$table .= '<span style="color:black;width:150px;float:left;">DOJ  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['DOJ'] . '</br>';
			$table .= '<span style="color:black;width:150px;float:left;">Designation  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['designation'] . '</br>';
			$table .= '<span style="color:black;width:150px;float:left;">Process  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['Process'] . '</br>';
			$table .= '<span style="color:black;width:150px;float:left;">Sub Process  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['sub_process'] . '</br>';
			$table .= '<span style="color:black;width:150px;float:left;">Client  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['clientname'] . '</br>';
			$table .= '<span style="color:black;width:150px;float:left;">Supervisor  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;' . $value['Supervisor'] . '</br>';

			$table .= '</div>';
		}
		echo $table;
	} else {
		echo "nodata";
	}
}
