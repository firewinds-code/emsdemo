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
	if (!isset($_SESSION['__user_logid'])) {
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
			$date_To = $_POST['txt_dateTo'];
			$date_From = $_POST['txt_dateFrom'];
		} else {
			$date_To = date('Y-m-d', time());
			$date_From = date('Y-m-d', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

/*if(file_exists('../Vacination/NA'))
{
	echo 'Yes';
}
else
{
	echo 'No';
}*/
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
			"iDisplayLength": 50,
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
	<span id="PageTittle_span" class="hidden">Employement Status</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Employement Status</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">






				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3">

						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s3 m3">
						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s3 m3">

						<select id="txt_location" name="txt_location" required">
							<option value="NA">----Select----</option>
							<?php
							$sqlBy = 'select id,location from location_master;';
							$myDB = new MysqliDb();
							$resultBy = $myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if (empty($mysql_error)) {
								echo '<option value="ALL"  >ALL</option>';
								foreach ($resultBy as $key => $value) {
									echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
								}
							}
							?>
						</select>
						<label for="txt_location" class="active-drop-down active">Location</label>
					</div>
					<div class="input-field col s2 m2">

						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
						<!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
					</div>

				</div>
				<?php
				if (isset($_POST['btn_view'])) {
					$myDB = new MysqliDb();
					$_location = (isset($_POST['txt_location']) ? $_POST['txt_location'] : null);
					if ($_location == 'ALL') {
						$query = 'select t3.location, t1.EmployeeID,t2.EmpName,t1.dateofjoin,t5.Designation,t7.client_name,process,sub_process ,sal_type
						from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join location_master t3 on t2.loc=t3.id 
						join df_master t4 on t1.df_id  = t4.df_id join designation_master t5 on t4.des_id=t5.id join new_client_master t6 on t1.cm_id=t6.cm_id join client_master t7 on t6.client_name = t7.client_id left join salary_details t8 on t1.EmployeeID=t8.EmployeeID where (cast(t1.dateofjoin as date) between "' . $date_From . '" and "' . $date_To . '");';
					} else {
						$query = 'select t3.location, t1.EmployeeID,t2.EmpName,t1.dateofjoin,t5.Designation,t7.client_name,process,sub_process ,sal_type
						from employee_map t1 join EmpID_Name t2 on t1.EmployeeID=t2.EmpID join location_master t3 on t2.loc=t3.id 
						join df_master t4 on t1.df_id  = t4.df_id join designation_master t5 on t4.des_id=t5.id join new_client_master t6 on t1.cm_id=t6.cm_id join client_master t7 on t6.client_name = t7.client_id left join salary_details t8 on t1.EmployeeID=t8.EmployeeID where (cast(t1.dateofjoin as date) between "' . $date_From . '" and "' . $date_To . '") and t2.loc="' . $_location . '";';
					}

					$chk_task = $myDB->query($query);
					$my_error = $myDB->getLastError();

					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;"><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>Location</th>';
						$table .= '<th>Employee ID</th>';

						$table .= '<th>Employee Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';

						$table .= '<th>Sub Process</th>';
						$table .= '<th>Employment</th>';
						$table .= '<th>Sub Employment</th>';

						$table .= '<thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr class="empdata_' . $value['id'] . '">';
							$Employment = $sub_emp = '';
							if (substr($value['EmployeeID'], 0, 2) == 'AE' || substr($value['EmployeeID'], 0, 2) == 'RS' || substr($value['EmployeeID'], 0, 3) == 'OCM' || substr($value['EmployeeID'], 0, 3) == 'RSM') {
								$Employment = 'Redstone';
							} else {
								$Employment = 'Cogent';
							}

							if ($value['sal_type'] != '') {
								if ($value['sal_type'] == 'NATS' || $value['sal_type'] == 'NAPS') {
									$sub_emp = $value['sal_type'];
								} else {
									$sub_emp = $Employment;
								}
							} else {
								$sub_emp = $Employment;
							}

							$table .= '<td>' . $value['location'] . '</td>';
							$table .= '<td>' . $value['EmployeeID'] . '</td>';
							$table .= '<td>' . $value['EmpName'] . '</td>';
							$table .= '<td>' . $value['dateofjoin'] . '</td>';
							$table .= '<td>' . $value['Designation'] . '</td>';
							$table .= '<td>' . $value['client_name'] . '</td>';
							$table .= '<td>' . $value['process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $Employment . '</td>';
							$table .= '<td>' . $sub_emp . '</td>';

							$table .= '</td></tr>';
						}
						$table .= '</tbody></table></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
					}
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
		if ($('#txt_location').val() == "NA") {
			alert('Please select location');
			return false;
		}
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>