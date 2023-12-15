<?php
$d1 = $d2 = NULL;
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$empid = isset($_REQUEST['empid']);
if ($empid) {
	// $sqlConnect = "SELECT i.id,ah_Datetime,ah_remark,ah_status,hr_Datetime,hr_remark,hr_status, group_concat(Document)`File` FROM warning_rth i inner join warning_rth_documents d on i.EmployeeID=d.EmployeeID and i.id=d.DataId where i.EmployeeID='" . $_REQUEST['empid'] . "' group by d.DataId";
	$empID = clean($_REQUEST['empid']);
	$sqlConnect = "SELECT i.id,ah_Datetime,ah_remark,ah_status,hr_Datetime,hr_remark,hr_status, group_concat(Document)`File` FROM warning_rth i inner join warning_rth_documents d on i.EmployeeID=d.EmployeeID and i.id=d.DataId where i.EmployeeID=? group by d.DataId";
	$stmt = $conn->prepare($sqlConnect);
	$stmt->bind_param("s", $empID);
	$stmt->execute();
	$result = $stmt->get_result();
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sqlConnect);
	if ($result) {
?>
		<table id="myTable2" class="data dataTable no-footer" cellspacing="0" width="100%" style="border-bottom:none;">
			<thead>
				<tr>
					<th>ID</th>
					<th>Date</th>
					<th>AH Remark</th>
					<th>AH Status</th>
					<th>Date</th>
					<th>HR Remark</th>
					<th>HR Status</th>
					<th>File</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$Count = 1;
				foreach ($result as $key => $value) {
					if ($value['ah_remark'] != NULL && $value['hr_remark'] != NULL) {
						$z = NULL;
						$str = $value['File'];
						$b = explode(',', $str);
						foreach ($b as $aa) {
							$z = $z . '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="' . $aa . '" data-position="left" data-tooltip="' . $aa . '" title="' . $aa . '">ohrm_file_download</i>';
						}
						$d1 = date("d-m-Y", strtotime($value['ah_Datetime']));
						$d2 = date("d-m-Y", strtotime($value['hr_Datetime']));
						echo '<tr>';
						echo '<td class="ExpID">' . $Count . '</td>';
						echo '<td>' . $d1 . '</td>';
						echo '<td>' . $value['ah_remark'] . '</td>';
						echo '<td>' . $value['ah_status'] . '</td>';
						echo '<td>' . $d2 . '</td>';
						echo '<td>' . $value['hr_remark'] . '</td>';
						echo '<td>' . $value['hr_status'] . '</td>';
						echo '<td>' . $z . '</td>';
						echo '</tr>';
						$Count++;
					}
				}
				?>
			</tbody>
		</table>
<?php
	} else {
		echo '';
	}
}
?>