<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$loc = '';
$batchid = clean($_REQUEST['batchid']);
$empid = clean($_REQUEST['empid']);
if (isset($batchid) && $batchid != "" && isset($empid) && $empid != "") {


	//$sql='call get_process_byclient("'.$_REQUEST['id'].'","'.$loc.'")';
	//$sql='select nc.*,cm.*,t1.location from new_client_master nc inner join client_master cm  on nc.client_name = cm.client_id inner join location_master t1 on t1.id = nc.location where t1.id="'.$loc.'" order by cm.client_name';

	$sqlConnect = 'call get_thchecklist_new("' . $empid . '","' . $batchid . '")';
	$myDB = new MysqliDb();
	$result = $myDB->query($sqlConnect);

	$error = $myDB->getLastError();
	$html = '';
	if (count($result) > 0 && $result) {
		$html = '<div class="flow-x-scroll" id="divgrid">
						  															
						  <table id="myTable" class="data dataTable no-footer" cellspacing="0">
						    <thead>
						        <tr>
						            <th>
							            
							            <label for="cbAll">Employee ID</label>
						            </th>
						            <th class="hidden">Employee ID</th>
						            <th>Employee Name</th>
						            <th>Client</th>
						            <th>Process</th>
						            <th>Sub Process</th>
						            <th>DOJ</th>
						            
						        </tr>
						    </thead>
					    <tbody>	';

		$count = 0;
		foreach ($result as $key => $value) {
			$count++;
			$html .= '<tr>';
			$html .= '<td class="EmpId"><label style="color: #059977;font-size: 14px;font-weight: bold;}">' . $value['EmployeeID'] . '</label></td>';
			$html .= '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  style="cursor:pointer;" class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
			$html .= '<td class="FullName">' . $value['EmployeeName'] . '</td>';
			$html .= '<td class="client_name">' . $value['client_name'] . '</td>';
			$html .= '<td class="process">' . $value['process'] . '</td>';
			$html .= '<td class="sub_process">' . $value['sub_process'] . '</td>';
			$html .= '<td class="doj">' . $value['dateofjoin'] . '</td>';

			$html .= '</tr>';
		}

		$html .= '</tbody></table>';
	}
}
echo $html;
