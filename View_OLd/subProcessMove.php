<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
$_SESSION['__user_logid'] = 'schedulerPage';

//require(ROOT_PATH.'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$updateforAh = $myDB->rawQuery("update tbl_tl2_tl_movement set  flag='Moved', status='transfered' , Updated_by='Server',updated_on=now() where  flag='NRTA' and status='NRT_Approve' and DAY(CURDATE())='01' and DAY(move_date)='01' and new_ReportsTo is not null ");

$mysql_error = $myDB->getLastError();
$rowCount = $myDB->count;
if ($rowCount > 0) {
	 $myDB = new MysqliDb();
	 echo "SELECT EmployeeID,new_ReportsTo,old_ReportsTo,cm_id,move_date FROM tbl_tl2_tl_movement  where flag='Moved' and status='transfered' and DAY(move_date) ='01'   and DAY(CURDATE())='01' and new_ReportsTo is not null";

	$select_data_from_move_table = $myDB->rawQuery("SELECT EmployeeID,new_ReportsTo,old_ReportsTo,cm_id,move_date FROM tbl_tl2_tl_movement  where flag='Moved' and status='transfered' and DAY(move_date) ='01'   and DAY(CURDATE())='01' and new_ReportsTo is not null");
	$mysql_error = $myDB->getLastError();
	$rowCount = $myDB->count;
	if ($rowCount > 0) {
		foreach ($select_data_from_move_table as $key => $data_array) {
			$NRT = $data_array['new_ReportsTo'];
			$EmployeeID = $data_array['EmployeeID'];
			$date = date("Y-m-d H:i:s");
			$moveDate = $data_array['move_date'];

			// $data_status_query = $myDB->rawQuery("update tbl_tl2_tl_movement set  flag='FM' where  flag='Moved' and EmployeeID='" . $data_array['EmployeeID'] . "'");
			$data_status_queryQry = "update tbl_tl2_tl_movement set  flag='FM' where  flag='Moved' and EmployeeID=?";
			$stmt = $conn->prepare($data_status_queryQry);
			$stmt->bind_param("s", $EmployeeID);
			$stmt->execute();
			$data_status_query = $stmt->get_result();
			// $rowCount = $myDB->count;
			// $data_status_query = $myDB->rawQuery("insert into status_table_log  select  * from status_table  where EmployeeID='" . $data_array['EmployeeID'] . "'");
			$data_status_queryQry = "insert into status_table_log  select  * from status_table  where EmployeeID=?";
			$stmt = $conn->prepare($data_status_queryQry);
			$stmt->bind_param("s", $EmployeeID);
			$stmt->execute();
			$data_status_query = $stmt->get_result();
			// $rowCount = $myDB->count;
			if ($data_status_query->num_rows > 0) {

				// echo "Update status_table set ReportTo='" . $NRT . "' where  EmployeeID='" . $EmployeeID . "'";
				echo "<br>";
				// $update_status_table = $myDB->rawQuery("Update status_table set ReportTo='" . $NRT . "' where  EmployeeID='" . $EmployeeID . "'");
				$update_status_tableQry = "Update status_table set ReportTo='" . $NRT . "' where  EmployeeID=?";
				$stmt1 = $conn->prepare($update_status_tableQry);
				$stmt1->bind_param("s", $EmployeeID);
				$stmt1->execute();
				$update_status_table = $stmt1->get_result();
			}
		}
	}
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Movement : Sub-Process Final Move</span>


</div>
<?php
//include(ROOT_PATH.'AppCode/footer.mpt'); 

?>