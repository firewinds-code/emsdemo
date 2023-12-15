<?php
// Server Config file

require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;

		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST['txt_dateTo'])) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$date_To = cleanUserInput($_POST['txt_dateTo']);
				$date_From = cleanUserInput($_POST['txt_dateFrom']);
			}
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
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

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"iDisplayLength": 25,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {

				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});
		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Exit Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Exit Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateFrom" id="txt_dateFrom" readonly="true" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateTo" id="txt_dateTo" readonly="true" value="<?php echo $date_To; ?>" />
					</div>

					<div class="input-field col s2 m2">

						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

				</div>
				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();

					$sqlstr = '';
					$user_type = clean($_SESSION['__user_type']);
					$status_ah = clean($_SESSION['__status_ah']);
					$user_logid = clean($_SESSION['__user_logid']);
					$status_er = clean($_SESSION['__status_er']);
					$location = clean($_SESSION["__location"]);
					if ($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $user_logid) && $status_ah != '')) {
						$sqlstr = "select t1.EmployeeID,t3.EmployeeName,t2.dateofjoin,dol,disposition,rsnofleaving,t5.client_name,t4.process,t4.sub_process from exit_emp t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID join personal_details t3 on t1.EmployeeID=t3.EmployeeID join new_client_master t4 on t2.cm_id=t4.cm_id join client_master t5 on t4.client_name=t5.client_id where t3.location=?  and disposition in ('RES','NCNS Request (ABSC)','TER','DCR') and (cast(dol as date) between ? and ?) order by EmployeeName, dol";
						$selectQ = $conn->prepare($sqlstr);
						$selectQ->bind_param("iss", $location, $date_From, $date_To);
						$selectQ->execute();
						$chk_task = $selectQ->get_result();
					} else if (($status_er != 'No' && $status_er == $user_logid) && $status_er != '') {
						$sqlstr = "select t1.EmployeeID,t3.EmployeeName,t2.dateofjoin,dol,disposition,rsnofleaving,t5.client_name,t4.process,t4.sub_process from exit_emp t1 join employee_map t2 on t1.EmployeeID=t2.EmployeeID join personal_details t3 on t1.EmployeeID=t3.EmployeeID join new_client_master t4 on t2.cm_id=t4.cm_id join client_master t5 on t4.client_name=t5.client_id where t3.location= ? and t4.er_scop=? and disposition in ('RES','NCNS Request (ABSC)','TER','DCR') and (cast(dol as date) between ? and ?) order by EmployeeName, dol";
						$selectQ = $conn->prepare($sqlstr);
						$selectQ->bind_param("isss", $location, $user_logid, $date_From, $date_To);
						$selectQ->execute();
						$chk_task = $selectQ->get_result();
					}
					//echo $sqlstr;
					// $chk_task = $myDB->query($sqlstr);
					// $my_error = $myDB->getLastError();

					if ($chk_task->num_rows > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Exit Date</th>';
						$table .= '<th>Disposition</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Reason</th>';

						$table .= '<thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['dateofjoin'] . '</td>';
							$table .= '<td>' . $value['dol'] . '</td>';
							$table .= '<td>' . $value['disposition'] . '</td>';
							$table .= '<td>' . $value['client_name'] . '</td>';
							$table .= '<td>' . $value['process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['rsnofleaving'] . '</td>';

							$table .= '</tr>';
						}
						$table .= '</tbody></table></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
					}
				}

				function getdays($bt, $dt)
				{
					$days = '';
					$myDB = new MysqliDb();
					$conn = $myDB->dbConnect();
					//echo 'select count( distinct bio.EmpID) count  from status_table st left join biopunchcurrentdata bio on bio.EmpID=st.EmployeeID where BatchID ="'.$bt.'" and DateOn="'.$dt.'"';
					$sql = 'select count( distinct bio.EmpID) as count from status_table st left join biopunchcurrentdata bio on bio.EmpID=st.EmployeeID where BatchID =? and DateOn=?';
					$selectQr = $conn->prepare($sql);
					$selectQr->bind_param("is", $bt, $dt);
					$selectQr->execute();
					$chk_task = $selectQr->get_result();
					//var_dump($chk_task);	
					// $my_error = $myDB->getLastError();

					if ($chk_task->num_rows > 0 && $chk_task) {
						//echo $chk_task[0][0];
						foreach ($chk_task as $key => $value) {
							$days = $value['count'];
						}
					}
					return $days;
				}

				?>

			</div>
			<!--Reprot / Data Table End -->
		</div>
		<!--Form container End -->
	</div>
	<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>

<script>
	$("#btn_view").click(function() {
		//alert($("#txt_location").val());

	});
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>