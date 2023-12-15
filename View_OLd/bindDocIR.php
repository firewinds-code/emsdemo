<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$empid = clean($_REQUEST['empid']);
if (isset($empid)) {
	//Echo 'Document <br/>';
	$sqlConnect = "SELECT i.id,ah_Datetime,ah_remark,ah_status, group_concat(Document)`File` FROM warning_rth i inner join warning_rth_documents d on i.EmployeeID=d.EmployeeID
 and i.id=d.DataId where i.EmployeeID=? group by d.DataId";
	$selectQ = $conn->prepare($sqlConnect);
	$selectQ->bind_param("s", $empid);
	$selectQ->execute();
	$result = $selectQ->get_result();
	// $myDB = new MysqliDb();
	// $result = $myDB->query($sqlConnect);
	if ($result->num_rows > 0) { ?>
		<table id="myTable1" class="data dataTable no-footer" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="hidden">Doc ID</th>
					<th>AH Remark</th>
					<th>AH Date</th>
					<th>AH Status</th>

					<th style="width:49px;">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($result as $key => $value) {
					$z = NULL;
					$str = $value['File'];
					$b = explode(',', $str);
					foreach ($b as $aa) {
						$z = $z . '<i class="material-icons download_item imgBtn imgBtnDownload tooltipped" onclick="javascript:return Download(this);"  data="' . $aa . '" data-position="left" data-tooltip="' . $aa . '" title="' . $aa . '">ohrm_file_download</i>';
					}
					echo '<tr>';
					echo '<td class="ExpID hidden">' . $value['id'] . '</td>';
					echo '<td>' . $value['ah_remark'] . '</td>';
					echo '<td>' . $value['ah_Datetime'] . '</td>';
					echo '<td>' . $value['ah_status'] . '</td>';

					echo '<td>' . $z . '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
<?php
	}
}

?>