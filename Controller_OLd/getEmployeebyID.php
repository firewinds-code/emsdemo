<?php

// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['ID']) && (trim($_REQUEST['ID'])) && (strlen($_REQUEST['ID']) <= 15)) {
	if ((substr($_REQUEST['ID'], 0, 2) == 'CE') || (substr($_REQUEST['ID'], 0, 2) == 'MU')) {
		$ID = clean($_REQUEST['ID']);
	}
}
if (isset($_SESSION['__user_logid']) && (trim($_SESSION['__user_logid'])) && (strlen($_SESSION['__user_logid']) <= 15)) {
	if ((substr($_SESSION['__user_logid'], 0, 2) == 'CE') || (substr($_SESSION['__user_logid'], 0, 2) == 'MU')) {
		$user_logid = clean($_SESSION['__user_logid']);
	}
}
// $sql = 'select wh.EmployeeID,wh.EmployeeName,wh.DOJ,wh.Process,wh.clientname,wh.sub_process,wh.designation,pd.EmployeeName as `Supervisor`,pd1.img from whole_details_peremp wh left outer join personal_details pd1 on pd1.EmployeeID = wh.EmployeeID left outer join personal_details pd on pd.EmployeeID = wh.ReportTo where wh.EmployeeID = "' . $ID . '" and wh.account_head = "' . $user_logid . '"';
$sql = 'select wh.EmployeeID,wh.EmployeeName,wh.DOJ,wh.Process,wh.clientname,wh.sub_process,wh.designation,pd.EmployeeName as `Supervisor`,pd1.img from whole_details_peremp wh left outer join personal_details pd1 on pd1.EmployeeID = wh.EmployeeID left outer join personal_details pd on pd.EmployeeID = wh.ReportTo where wh.EmployeeID =? and wh.account_head = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $ID, $user_logid);
$stmt->execute();
$result_all = $stmt->get_result();
// print_r($result_all);
// die;
$img = $table = '';
// $result_all = $myDB->query($sql);
// $mysql_error = $myDB->getLastError();
if ($result_all && $result_all->num_rows > 0) {

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
