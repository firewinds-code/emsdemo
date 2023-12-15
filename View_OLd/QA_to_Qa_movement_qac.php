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
$userID = $_SESSION['__user_logid'];

$process = 'NA';
$alert_msg = $thisPage = "";
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		/*$isPostBack = false;
		$referer = "";
		$thisPage = REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];	
		if (isset($_SERVER['HTTP_REFERER'])){
		    $referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage){
		    $isPostBack = true;
		} 
		
		if($isPostBack && isset($_POST))
		{
			if(isset($_POST['txt_process'])){
				$process = $_POST['txt_process'];
			}
			
			
		}*/

		if (isset($_POST['txt_process'])) {
			$process = cleanUserInput($_POST['txt_process']);
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}

if (isset($_POST['btn_initiate'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$cb = $_POST['cb'];
		if (count($cb) > 0 && $cb) {
			$insert_flag = 0;
			$mySQLError_log = '';
			foreach ($cb as $EmployeeID) {
				$username = clean($_SESSION['__user_Name']);
				$user_log_id = clean($_SESSION['__user_logid']);
				$txt_remark_qa2qa = clean($_SESSION['txt_remark_qa2qa']);
				$insert_ar = array(
					"EmployeeID" => $EmployeeID,
					"MovementOn" => date('Y-m-01', strtotime("next month")),
					"Process" => $_POST['proc_handler'],
					"Status" => 1,
					"Remark" => $username . '>' . $user_log_id . '>' . date('Y-m-d H:i:s') . '>' . $txt_remark_qa2qa . '|',
					"CreatedBy" => $user_log_id
				);
				// $checkEmployee = $myDB->rawQuery("SELECT * FROM tbl_qa_to_qa_movement where EmployeeID ='" . $EmployeeID . "' and Status in (0,1,2,4) ;");
				$checkEmployeeQry = "SELECT * FROM tbl_qa_to_qa_movement where EmployeeID =? and Status in (0,1,2,4)";

				// $mysql_error = $myDB->getLastError();
				// $rowCount = $myDB->count;
				$stmt = $conn->prepare($checkEmployeeQry);
				$stmt->bind_param("s", $EmployeeID);
				$stmt->execute();
				$checkEmployee = $stmt->get_result();

				// print_r($checkEmployee);
				// exit;

				if ($checkEmployee->num_rows > 0 && $checkEmployee) {
					$insert_flag++;
					$mySQLError_log .= $EmployeeID . ' not initiated. Error : already in queue';
				} else {
					$myDB = new MysqliDb();
					$flag = $myDB->insert("tbl_qa_to_qa_movement", $insert_ar);
					$mySQLError = $myDB->getLastError();
					if ($flag) {
					} else {
						// echo "sas" . $EmployeeID;
						// exit;
						$insert_flag++;
						$mySQLError_log .= $EmployeeID . ' not initiated. Error ';
					}
				}
				if ($insert_flag == 0) {
					//$alert_msg = '<span class="text-success">ALL Employee has been initiated.</span>';
					echo "<script>$(function(){ toastr.success('ALL Employee has been initiated.'); }); </script>";
				} else {
					//$alert_msg = $mySQLError_log;
					echo "<script>$(function(){ toastr.error(' " . $mySQLError_log . "'); }); </script>";
				}
			}
			//tbl_qa_to_qa_movement
		} else {
			//$alert_msg = '<span class="text-warning">No Employee selected...</span>';
			echo "<script>$(function(){ toastr.error('No Employee selected.'); }); </script>";
		}
	}
}
?>
<script>
	$(function() {

		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Employee Movement QA to QA </span>

	<!-- Main Div for all Page -->
	<div class="pim-container ">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employee Movement QA to QA Current QA</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				// if (intval(date("d")) > 24 && intval(date("d")) <= 31) {
				if (intval(date("d")) > 1 && intval(date("d")) <= 31) {
				?>
					<div class="input-field col s10 m10">
						<?php

						// $sqlBy = 'select distinct clientname,Process from whole_details_peremp where Qa_ops = "' . $_SESSION['__user_logid'] . '" ';
						$sqlBy = 'select distinct clientname,Process from whole_details_peremp where Qa_ops = ? ';
						$stmt = $conn->prepare($sqlBy);
						$stmt->bind_param("s", $userID);
						$stmt->execute();
						$resultBy = $stmt->get_result();
						// $resultBy = $myDB->rawQuery($sqlBy);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						?>
						<select id="txt_process" name="txt_process">
							<option value="NA">---Select---</option>
							<?php
							if ($resultBy  && $resultBy->num_rows > 0) {
								foreach ($resultBy as $key => $value) {
									if ($process == ($value['clientname'] . '|' . $value['Process'])) {
										echo '<option selected>' . $value['clientname'] . '|' . $value['Process'] . '</option>';
									} else {
										echo '<option>' . $value['clientname'] . '|' . $value['Process'] . '</option>';
									}
								}
							}
							?>
						</select>
						<label for="txt_process" class="active-drop-down active">Process</label>
						<input type="hidden" name="proc_handler" value="<?php echo $process; ?>" />
					</div>
					<div class="input-field col s2 m2 right-align">
						<button type="submit" value="Check" name="btn_check" id="btn_check" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green ">Check</button>
					</div>

					<?php
					if (!empty($process) && $process != 'NA') {

						// $query = "SELECT EmployeeID, EmployeeName,designation as `Designation` , clientname as `Client Name`, Process, sub_process as `Sub Process` FROM whole_details_peremp where Qa_ops = '" . $_SESSION['__user_logid'] . "' and status  = 6 and concat(clientname,'|',Process) = '" . $process . "' and EmployeeID not in (select EmployeeID from tbl_qa_to_qa_movement where Status in (0,1,2,4,6));";
						$query = "SELECT EmployeeID, EmployeeName,designation as `Designation` , clientname as `Client Name`, Process, sub_process as `Sub Process` FROM whole_details_peremp where Qa_ops = ? and status  = 6 and concat(clientname,'|',Process) = ? and EmployeeID not in (select EmployeeID from tbl_qa_to_qa_movement where Status in (0,1,2,4,6));";
						$stq = $conn->prepare($query);
						$stq->bind_param("ss", $userID, $process);
						$stq->execute();
						$rst_qa2qa = $stq->get_result();
						$rst_qa2qaRow = $rst_qa2qa->fetch_row();
						// print_r($rst_qa2qaRow);
						// die;
						// $rst_qa2qa = $myDB->rawQuery($query);
						// $mysql_error = $myDB->getLastError();
						// $rowCount = $myDB->count;
						if ($rst_qa2qa->num_rows > 0) {
					?>
							<div class="hidden" id="div_btn">
								<div class="input-field col s12 m12 ">
									<textarea name="txt_remark_qa2qa" id="txt_remark_qa2qa" class="materialize-textarea"></textarea>
									<label for="txt_remark_qa2qa" class="active-drop-down active">Remark</label>
								</div>
								<div class="input-field col s12 m12 right-align ">
									<button type="submit" value="Initiate" name="btn_initiate" id="btn_initiate" onclick="return confirm('Are you want to proceed?');" class="btn waves-effect waves-green ">Initiate</button>
								</div>
							</div>
					<?php

							$table = '<div class="had-container pull-left row card dataTableInline"  ><div class=""  >	
							<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						<thead><tr>';

							foreach ($rst_qa2qaRow as $key_kl => $value_kl) {
								if ($key_kl == 'EmployeeID') {
									$table .= '<th >
								<input type="checkbox" id="cbAll" name="cbAll" value="ALL">
								<label for="cbAll" >EmployeeID</label></th>';
								} else {
									$table .= '<th>' . $key_kl . '</th>';
								}
							}
							$table .= '</tr>';
							$table .= '</thead><tbody>';
							$row_index = 0;
							foreach ($rst_qa2qa as $key => $value) {


								$table .= '<tr>';
								foreach ($value as $key_kl => $value_kl) {
									if ($key_kl == 'EmployeeID') {
										$table .= '<td><input type="checkbox" id="cb' . $value_kl . '" class="cb_child" name="cb[]" value="' . $value_kl . '"><label for="cb' . $value_kl . '" >' . $value_kl . '</td>';
									} else {
										$table .= '<td>' . $value_kl . '</td>';
									}
								}

								$table .= '</tr>';
							}
							$table .= '</tbody></table></div></div>';
							echo $table;

							echo "<script>$(function(){ toastr.success('Total" . $rst_qa2qa->num_rows . "Employee found for " . $process . "'); }); </script>";
						} else {

							echo "<script>$(function(){ toastr.error('No Employee found for this Process or you may be have wrong Process selection..'); }); </script>";
						}
					} elseif (isset($_POST['btn_check'])) {
						echo "<script>$(function(){ toastr.error('No Employee found for this Process or you may be have wrong Process selection..'); }); </script>";
					}
					?>
			</div>
		<?php

				} else {
					echo "<script>$(function(){ toastr.info('This module only allowed after 24th of every month for next month alteration.'); }); </script>";
				}
		?>

		<div class="container-fluid">

		</div>
		<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<script>
	$(function() {
		$("#cbAll").change(function() {
			$("input:checkbox").prop('checked', $(this).prop("checked"));
		});
		$("input:checkbox").change(function() {

			if ($('input.cb_child:checkbox:checked').length > 0) {
				if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {

					$("#cbAll").prop("checked", true);
				} else {
					$("#cbAll").prop("checked", false);
				}
				$("#div_btn").removeClass('hidden');
			} else {
				$("#cbAll").prop("checked", false);
				$("#div_btn").addClass('hidden');

			}
		});
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>