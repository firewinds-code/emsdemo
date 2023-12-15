<label class="control-label">Batch List:</label>
<div class="controls">
	<select id="batch_id_tmp" name="batch_id_tmp">
		<option value="NA">---Select---</option>
		<?php
		require_once(__dir__ . '/../Config/init.php');
		// require_once(CLS . 'MysqliDb.php');
		date_default_timezone_set('Asia/Kolkata');
		$result = array();
		$Query = null;


		if (isset($_REQUEST['EmployeeID']) && (trim($_REQUEST['EmployeeID'])) && (strlen($_REQUEST['EmployeeID']) <= 15)) {
			if ((substr($_REQUEST['EmployeeID'], 0, 2) == 'CE') || (substr($_REQUEST['EmployeeID'], 0, 2) == 'MU')) {
				$empid = clean($_REQUEST['EmployeeID']);
			}
		}

		if ($_REQUEST['cm_id'] && (strlen($_REQUEST['cm_id']) <= 5)) {
			if (is_numeric($_REQUEST['cm_id'])) {
				$cmid = clean($_REQUEST['cm_id']);
			}
		}


		if ($_REQUEST) {
			// echo "flkhfglhfglh";
			// exit;
			$myDB = new MysqliDb();
			// $Query = "SELECT distinct whole_details_peremp.BatchID,batch_master.BacthName FROM whole_details_peremp inner join  batch_master on batch_master.BacthID = whole_details_peremp.BatchID where ('" . $empid . "' in (th,qh,oh,account_head)) and whole_details_peremp.cm_id = '" . $cmid . "'";
			// $res = $myDB->query($Query);
			///

			$query = "SELECT distinct whole_details_peremp.BatchID,batch_master.BacthName FROM whole_details_peremp inner join  batch_master on batch_master.BacthID = whole_details_peremp.BatchID where (? in (th,qh,oh,account_head)) and whole_details_peremp.cm_id = ?";

			$stmt = $conn->prepare($query);
			$stmt->bind_param("si", $empid, $cmid);
			$stmt->execute();
			$res = $stmt->get_result();
			$count = $res->num_rows;
			// echo "<script>console.log(' " .$res->num_rows."');</script>";
			if ($res->num_rows > 0) {
				foreach ($res as $key => $value) {
					echo '<option value="' . $value['BatchID'] . '">' . $value['BacthName'] . '</option>';
				}
			}
		}

		?>

	</select>
</div>