<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
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
		$thisPage = REQUEST_SCHEME . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST)) {
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
	<span id="PageTittle_span" class="hidden">Down Time Report New</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Down Time Report New</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<div class="col s12 m12" id="rpt_container">
					<div class="input-field col s4 m4">

						<input type="text" class="form-control" name="txt_dateFrom" style="min-width: 225px;" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
					</div>
					<div class="input-field col s4 m4">

						<input type="text" class="form-control" name="txt_dateTo" style="min-width: 225px;" id="txt_dateTo" value="<?php echo $date_To; ?>" />
					</div>
					<div class="input-field col s4 m4">

						<Select class="form-control" name="emp_status" style="min-width: 200px;" id="status">
							<option value='Active' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'Active') {
														echo "selected";
													} ?>>Active</option>
							<option value='InActive' <?php if (isset($_POST['emp_status']) && $_POST['emp_status'] == 'InActive') {
															echo "selected";
														} ?>>InActive</option>
						</Select>
					</div>
					<div class="input-field col s6 m6 l6">
						<select id="clientName" name="clientName">
							<option Selected="True" Value="NA">-Select One-</option>
							<option Value="ALL">ALL</option>
							<?php
							$myDB = new MysqliDb();
							$result = $myDB->query('select distinct t1.client_name ID,t3.client_name from new_client_master t1 join report_map t2 on t1.cm_id=t2.processID join client_master t3 on t1.client_name=t3.client_id where t2.EmpID="' . $_SESSION['__user_logid'] . '" and reportID=6');
							$my_error = $myDB->getLastError();
							foreach ($result as $key => $value) {
								echo '<option value="' . $value['ID'] . '">' . $value['client_name'] . '</option>';
							} ?>
						</select>
						<label for="clientName" class="active-drop-down active">Client Name</label>
					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
					</div>

				</div>


				<?php
				if (isset($_POST['btn_view'])) {
					if (!empty($_POST['clientName'])) {
						$clientname = $_POST['clientName'];
					}

					$myDB = new MysqliDb();
					$empStatus = $_POST['emp_status'];
					if ($empStatus == 'Active') {
						$tablename = 'whole_details_peremp';
					} elseif ($empStatus == 'InActive') {
						$tablename = 'view_for_report_inactive';
					}
					// echo 'call sp_getDawnTime_Report_new("' . $_SESSION['__user_logid'] . '","' . $date_From . '","' . $date_To . '","' . $empStatus . '","' . $clientname . '")';
					$chk_task  =  $myDB->query('call sp_getDawnTime_Report_new("' . $_SESSION['__user_logid'] . '","' . $date_From . '","' . $date_To . '","' . $empStatus . '","' . $clientname . '")');
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						  <thead><tr>';
						$table .= '<th>EmployeeID</th>';
						$table .= '<th>EmployeeName</th>';
						$table .= '<th>Request_Type</th>';
						$table .= '<th>FA EmployeeID</th>';
						$table .= '<th>FA Status</th>';
						$table .= '<th>FA Comment</th>';
						$table .= '<th>RT EmployeeID</th>';
						$table .= '<th>RT Status</th>';
						$table .= '<th>RT Comment</th>';
						$table .= '<th>From</th>';
						$table .= '<th>To</th>';
						$table .= '<th>Total Hour</th>';
						$table .= '<th>BillableType</th>';
						$table .= '<th>IT Ticket ID</th>';
						$table .= '<th>Createdon</th>';
						$table .= '<th>Designation</th>';
						$table .= '<th>Dept Name</th>';
						$table .= '<th>DOJ</th>';
						$table .= '<th>Status</th>';

						$table .= '<th>Client</th>';
						$table .= '<th>Process</th>';
						$table .= '<th>Sub Process</th>';
						$table .= '<th>Location</th>';
						$table .= '<th>Supervisor</th>';
						$table .= '<th>Approved By</th>';
						$table .= '<th>Comments</th><thead><tbody>';

						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['EmpID'] . '</td>';
							$table .= '<td>' . $value['EmployeeName'] . '</td>';
							$table .= '<td>' . $value['Request_type'] . '</td>';
							$table .= '<td>' . $value['FAID'] . '</td>';
							$table .= '<td>' . $value['FAStatus'] . '</td>';
							$table .= '<td>' . $value['FAComment'] . '</td>';
							$table .= '<td>' . $value['RTID'] . '</td>';
							$table .= '<td>' . $value['RTStatus'] . '</td>';
							$table .= '<td>' . $value['RTComment'] . '</td>';
							$table .= '<td>' . $value['DTFrom'] . '</td>';
							$table .= '<td>' . $value['DTTo'] . '</td>';
							$table .= '<td>' . $value['TotalDT'] . '</td>';
							$table .= '<td>' . $value['BillableType'] . '</td>';
							$table .= '<td>' . $value['IT_ticketid'] . '</td>';
							$table .= '<td>' . $value['createdon'] . '</td>';
							$table .= '<td>' . $value['designation'] . '</td>';
							$table .= '<td>' . $value['dept_name'] . '</td>';
							$table .= '<td>' . $value['DOJ'] . '</td>';
							$table .= '<td>' . $value['emp_status'] . '</td>';
							$table .= '<td>' . $value['clientname'] . '</td>';
							$table .= '<td>' . $value['Process'] . '</td>';
							$table .= '<td>' . $value['sub_process'] . '</td>';
							$table .= '<td>' . $value['location'] . '</td>';
							$table .= '<td>' . $value['Supervisor'] . '</td>';
							$comment = explode('|', $value['Comments']);

							$string1 = 'Approved by Server at First Level';
							$string2 = 'Approved by Server at Final Level';
							$modify = (empty($value['modifiedon'])) ? '' : '(' . date('Y-m-d', strtotime($value['modifiedon'])) . ')';
							$attr_val = $modify . ' ' . $value['modifiedby'];
							$attr = '';
							foreach ($comment as $url) {

								if (preg_match("/\b$string1\b/i", $url)) {
									$attr  .=  $url . ' | ';
								}
								if (preg_match("/\b$string2\b/i", $url)) {
									$attr  .=  $url . ' | ';
								}
							}
							if (!empty($attr))
								$attr_val = $attr;
							$table .= '<td>' . $attr_val . '</td>';
							$table .= '<td>' . $value['Comments'] . '</td></tr>';
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
					}
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
	$('#btn_view').on('click', function() {
		var validate = 0;
		var alert_msg = '';
		// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
		$("input,select,textarea").each(function() {
			var spanID = "span" + $(this).attr('id');
			$(this).removeClass('has-error');
			if ($(this).is('select')) {
				$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
			}
			var attr_req = $(this).attr('required');
			if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
				validate = 1;
				$(this).addClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
				}
				if ($('#' + spanID).size() == 0) {
					$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
				}
				var attr_error = $(this).attr('data-error-msg');
				if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
					$('#' + spanID).html('Required *');
				} else {
					$('#' + spanID).html($(this).attr("data-error-msg"));
				}
			}
		})
		if ($('#clientName').val() == 'NA') {
			$('#clientName').addClass('has-error');
			if ($('#spanclientName').size() == 0) {
				$('<span id="spanclientName" class="help-block">Required*</span>').insertAfter('#clientName');
			}
			validate = 1;
		}
		if (validate == 1) {
			$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
			$('#alert_message').show().attr("class", "SlideInRight animated");
			$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		}
		var dept = $('#clientName').val();
		if (dept == 'ALL') {
			//call sp_get_roster_Report("CE01145570","Apr","2022","Compliance","Active","1")
			//$chk_task = 'call sp_get_roster_Report("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '","' . $dept . '","' . $EmpStatus . '","' . $_SESSION["__location"] . '")';
			var usrid = <?php echo "'" . $_SESSION["__user_logid"] . "'"; ?>;
			// alert(usrid);
			var loc = <?php echo "'" . $_SESSION["__location"] . "'"; ?>;
			var date_from = $('#txt_dateFrom').val();
			// alert(date_from);
			var date_to = $('#txt_dateTo').val();
			// alert(date_to);
			var status = $('#status').val();
			//var type = 'ADMINISTRATOR';
			//'call sp_get_atnd_Report_new("' . $_SESSION['__user_logid'] . '","' . $date_To . '","' . $date_From . '","' . $clientname . '") ';
			var sp = "call sp_getDawnTime_Report_new('" + usrid + "','" + date_from + "','" + date_to + "','" + status + "','" + dept + "')";
			var url = "textExport.php?sp=" + sp;
			//alert(url);
			window.location.href = url;
			return false;
		}
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>