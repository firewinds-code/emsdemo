<?php

require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$ID = clean($_REQUEST['ID']);
// $sqlConnect = 'select * from trainee_doc where EmployeeID="' . $ID . '"';
$sqlConnect = 'select * from trainee_doc where EmployeeID=?';
$stmt = $conn->prepare($sqlConnect);
$stmt->bind_param("s", $ID);
$stmt->execute();
$result = $stmt->get_result();
// print_r($result);
// die;
// $result = $myDB->query($sqlConnect);
if ($result->num_rows > 0 && $result) { ?>
	<table id="myTable1" class="data dataTable no-footer" cellspacing="0" style="width:100%;">
		<thead>
			<tr>
				<th>Doc ID</th>
				<th>Doc File</th>
				<th>Doc Desc</th>
				<th style="width:100px;">Manage Doc </th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($result as $key => $value) {
				echo '<tr>';
				echo '<td class="ExpID">' . $value['ID'] . '</td>';
				echo '<td>' . $value['docfile'] . '</td>';
				echo '<td>' . $value['docdesc'] . '</td>';
				echo '<td><img alt="Delete" class="imgBtn imgBtnUploadDelete" src="../Style/images/users_delete.png" title="Delete Data Item"  id="' . $value['ID'] . '" data-file="' . $value['docfile'] . '" /><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png" title="View Data Item" data="' . $value['docfile'] . '" /></td>';

				echo '</tr>';
			}
			?>
		</tbody>
	</table>
<?php
} else {
	echo '<code>No Doc Upladed For This Employee...</code>';
}
?>