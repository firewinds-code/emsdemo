<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$_Description = $Request_Emp = $_Name = $alert_msg = '';
$btn_submit = isset($_POST['btn_submit']);
if ($btn_submit) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$createBy = clean($_SESSION['__user_logid']);
		$batch_id = cleanUserInput($_POST['text_trcheck_Batch']);
		$txt_body = cleanUserInput($_POST['txt_body']);
		$myDB = new MysqliDb();
		$str  = $myDB->query('call insert_qh_log("' . $createBy . '","' . $batch_id . '","' . $txt_body . '")');
		$mysql_error = $myDB->getLastError();
		if (empty($mysql_error)) {
			echo "<script>$(function(){ toastr.success('Record save successfully.'); }); </script>";
		} else {
			echo "<script>$(function(){ toastr.error('Record not Updated. $mysql_error'); }); </script>";
		}
	}
}

?>

<script>
	$(document).ready(function() {
		$('input').keyup(function() {

			$('#txt_chg_pwd1').closest('div').removeClass('has-error');
			$('#txt_chg_pwd1').closest('div').removeClass('has-success');
			// alert($('#txt_chg_pwd').val()+','+$('#txt_chg_pwd1').val())
			if ($('#txt_chg_pwd').val() === $('#txt_chg_pwd1').val()) {
				$('#alert_message').fadeOut();
				$('#txt_chg_pwd1').closest('div').addClass('has-success');
			} else {

				$('#txt_chg_pwd1').closest('div').addClass('has-error');
				$('#alert_message').show();
				$('#alert_msg').html("Password Not Matched");

			}
		});
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Employee Quality Head Daily LOG</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Quality Head Log</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class="input-field col s6 m6">
					<select id="text_trcheck_Batch" name="text_trcheck_Batch">
						<option value="NA">----Select----</option>
						<?php
						//$sqlBy ='SELECT personal_details.EmployeeID,personal_details.EmployeeName FROM ems.employee_map left outer join personal_details on personal_details.EmployeeID=employee_map.EmployeeID where dept_id=6 and client_id="'.$_SESSION["__user_client_ID"].'" and designation not in ("Senior Custumer Care Executive","Custumer Care Executive") '; 
						// $sqlBy = 'select distinct batch_master.BacthID,batch_master.BacthName from status_quality inner join status_table on status_table.EmployeeID = status_quality.EmployeeID inner join employee_map on employee_map.EmployeeID = status_quality.EmployeeID inner join new_client_master on employee_map.cm_id = new_client_master.cm_id left outer join batch_master on batch_master.BacthID = status_table.BatchID where status_quality.ojt_status < 2 and status_table.Status = 5  and new_client_master.qh ="' . $_SESSION['__user_logid'] . '" order by BacthName  ';
						$sqlBy = 'select distinct batch_master.BacthID,batch_master.BacthName from status_quality inner join status_table on status_table.EmployeeID = status_quality.EmployeeID inner join employee_map on employee_map.EmployeeID = status_quality.EmployeeID inner join new_client_master on employee_map.cm_id = new_client_master.cm_id left outer join batch_master on batch_master.BacthID = status_table.BatchID where status_quality.ojt_status < 2 and status_table.Status = 5  and new_client_master.qh = ? order by BacthName  ';
						$batch_id = 0;

						$stmt = $conn->prepare($sqlBy);
						$stmt->bind_param("s", $user_logid);
						$stmt->execute();
						$resultBy = $stmt->get_result();

						// $myDB = new MysqliDb();
						// $resultBy = $myDB->query($sqlBy);
						// $error = $myDB->getLastError();
						if ($resultBy->num_rows > 0) {
							$selec = '';
							foreach ($resultBy as $key => $value) {
								if (isset($_POST['text_trcheck_Batch'])) {
									$batch_id = $_POST['text_trcheck_Batch'];
								}
								if ($batch_id == $value['BacthID']) {
									echo '<option value="' . $value['BacthID'] . '" selected >' . $value['BacthName'] . '</option>';
								} else {
									echo '<option value="' . $value['BacthID'] . '" >' . $value['BacthName'] . '</option>';
								}
							}
						}

						?>
					</select>
					<label for="text_trcheck_Batch" class="active-drop-down active">Select Batch Number</label>
				</div>

				<div class="input-field col s6 m6 right-align">
					<input type="submit" value="Search" name="btnSave" id="btnSave" class="btn waves-effect waves-green hidden" />
				</div>

				<div class="input-field col s12 m12">
					<textarea name="txt_body" class="materialize-textarea" id="txt_body"></textarea>
					<label for="txt_body">Remark</label>
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" name="btn_submit" id="btn_submit" class="btn waves-effect waves-green">Submit</button>
				</div>


				<?php
				$text_trcheck_Batch = isset($_POST['text_trcheck_Batch']);
				if ($text_trcheck_Batch) {
					$batch_id = cleanUserInput($_POST['text_trcheck_Batch']);
				} else {
					$batch_id = 'NA';
				}
				if ($batch_id != 'NA') {
					$emp = clean($_SESSION['__user_logid']);
					$sqlConnect = 'call get_tr_dailyreport("' . $emp . '",' . $batch_id . ')';
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					$error = $myDB->getLastError();
					if (count($result) > 0 && $result) { ?>
						<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
							<div class="">
								<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Employee ID</th>
											<th>Employee Name</th>
											<th>Batch</th>
											<th>Remark</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 0;
										foreach ($result as $key => $value) {
											$count++;
											echo '<tr>';
											echo '<td>' . $value['EmployeeID'] . '</td>';
											echo '<td>' . $value['EmployeeName'] . '</td>';
											echo '<td>' . $value['batchid'] . '</td>';
											echo '<td>' . $value['body'] . '</td>';
											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
				<?php
					} else {
						echo "<script>$(function(){ toastr.info('Congratulations have been aligned to concern departments. " . $error . " '); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.info('No batch allinged'); }); </script>";
				}
				?>
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		$('#btn_submit').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			$('#txtFile').closest('div').removeClass('has-error');
			$('#txt_body').closest('div').removeClass('has-error');

			if ($('#txt_body').val() == '' || $('#txt_body').val().length < 250) {
				$('#txt_body').closest('div').addClass('has-error');
				validate = 1;
				toastr.info('Remark Can\'t be Empty or not less than 250 For any Checked Employee.');
			}
			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}

		});
		$('#text_trcheck_Batch').change(function() {
			$('#btnSave').click();
		});
	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>