<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
include(__dir__ . '/../Controller/endecript.php');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
error_reporting(0);
$empID = '';
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
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$show = ' hidden';
$link = $btn_view = $btn_view1 = $alert_msg = '';
if (isset($_POST['txt_dateTo'])) {
	$date_To = $_POST['txt_dateTo'];
	$date_From = $_POST['txt_dateFrom'];
} else {
	$date_To = date('Y-m-d', time());
	$d = new DateTime($date_To);
	$d->modify('first day of this month');
	$date_From = date('Y-m-d', time());
}
?>

<script>
	$(function() {
		$('#txt_dateFrom,#txt_dateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$("#myTable .text-danger").css('color', 'red');
		$("#myTable .text-success").css('color', 'green');
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
	<span id="PageTittle_span" class="hidden">Report Aadhar Verification</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Report Aadhar Verification</h4>

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
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					</div>
				</div>
			</div>
			<!-- <div id="pnlTable"> -->
			<?php
			// $getData = "select  p.EmpID as EmployeeID,t2.INTID,p.EmpName as EmployeeName,p.DOB,p.FatherName, c.client_name, n.Process, n.sub_process,e.dateofjoin as DOJ,e.emp_level, e.emp_status, t2.aadhar_status,t2.created_by , t2.created_at, t2.verify_date,t2.remarks,skipreason,l.location from (select EmployeeID,INTID,created_by,aadhar_status,created_at,verify_date,remarks,skipreason from aadhar_verifiaction where created_at between '" . $date_From . " 00:00:00' and '" . $date_To . " 23:59:59'  )t2 left join EmpID_Name p on  t2.EmployeeID=p.EmpID or t2.INTID=p.INTID or t2.INTID=p.EmpID left join employee_map e on t2.EmployeeID=e.EmployeeID left join new_client_master n on e.cm_id=n.cm_id left join client_master c on n.client_name=c.client_id left join location_master l on p.loc=l.id";
			// $myDB = new MysqliDb();
			// $allData = $myDB->query($getData);
			// $my_error = $myDB->getLastError();
			// if (count($allData) > 0) {
			// 	$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
			// 	 <div class=""><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
			// 	$table .= '<th>Location</th>';
			// 	$table .= '<th>Employee ID</th>';
			// 	$table .= '<th>INTID</th>';
			// 	$table .= '<th>Employee Name</th>';
			// 	$table .= '<th>Verification Status</th>';
			// 	$table .= '<th>Creation Date</th>';
			// 	$table .= '<th>Verification Date</th>';
			// 	$table .= '<th>Remaining Date</th>';
			// 	$table .= '<th>DOJ</th>';
			// 	$table .= '<th>DOB</th>';
			// 	$table .= '<th>Father Name</th>';
			// 	$table .= '<th>Client</th>';
			// 	$table .= '<th>Process</th>';
			// 	$table .= '<th>Sub Process</th>';
			// 	$table .= '<th>Emp Level</th>';
			// 	$table .= '<th>Employee Status</th>';
			// 	$table .= '<th>Verified By ID</th>';
			// 	$table .= '<th>Verification Remarks</th>';
			// 	$table .= '<th>Skip Reason</th>';
			// 	$table .= '<thead><tbody>';

			// 	foreach ($allData as $key => $value) {
			// 		$reamainingDate = '0 ';
			// 		if (strtolower($value['aadhar_status']) == 'verified') {
			// 			$reamainingDate = "verified";
			// 		} else if (strtolower($value['aadhar_status']) == 'pending') {

			// 			$start = strtotime($value['created_at']);
			// 			$end = strtotime(date('Y-m-d'));

			// 			$reamainingDate = ceil(abs($end - $start) / 86400);
			// 			$reamainingDate = (30 - $reamainingDate) . " Days";
			// 		} else {
			// 			$start = strtotime('2021-06-01');
			// 			$end = strtotime(date('Y-m-d'));
			// 			$reamainingDate = ceil(abs($end - $start) / 86400);
			// 			$reamainingDate = ($reamainingDate - 30) . " Days";
			// 		}
			// 		$table .= '<td>' . $value['location'] . '</td>';
			// 		$table .= '<td>' . $value['EmployeeID'] . '</td>';
			// 		$table .= '<td>' . $value['INTID'] . '</td>';
			// 		$table .= '<td>' . $value['EmployeeName'] . '</td>';
			// 		$table .= '<td>' . $value['aadhar_status'] . '</td> ';
			// 		$table .= '<td>' . $value['created_at'] . '</td>';
			// 		$table .= '<td>' . $value['verify_date'] . '</td>';
			// 		$table .= '<td>' . $reamainingDate . '</td>';
			// 		$table .= '<td>' . $value['DOJ'] . '</td> ';
			// 		$table .= '<td>' . $value['DOB'] . '</td> ';
			// 		$table .= '<td>' . $value['FatherName'] . '</td>';
			// 		$table .= '<td>' . $value['client_name'] . '</td> ';
			// 		$table .= '<td>' . $value['Process'] . '</td> ';
			// 		$table .= '<td>' . $value['sub_process'] . '</td> ';
			// 		$table .= '<td>' . $value['emp_level'] . '</td> ';
			// 		$table .= '<td>' . $value['emp_status'] . '</td> ';
			// 		$table .= '<td>' . $value['created_by'] . '</td> ';
			// 		$table .= '<td>' . $value['remarks'] . '</td>';
			// 		$table .= '<td>' . $value['skipreason'] . '</td></tr>';
			// 	}
			// 	$table .= '</tbody></table></div></div>';
			// 	echo $table;
			// } else {
			// 	echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
			// }
			?>
			<!-- </div> -->

			<!--Reprot / Data Table End -->
		</div>
		<!--Form container End -->
	</div>
	<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>
<script>
	$(function() {
		$('#alert_msg_close').click(function() {
			$('#alert_message').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		} else {
			$('#alert_message').delay(10000).fadeOut("slow");
		}
	});
	$('#btn_view').on('click', function() {
		var date_from = $('#txt_dateFrom').val();
		var date_to = $('#txt_dateTo').val();
		//var sp = "call getExcelReport('" + date_from + "','" + date_to + "')";
		//var sp = "select  p.EmpID as EmployeeID,t2.INTID,p.EmpName as EmployeeName,p.DOB,p.FatherName, c.client_name, n.Process, n.sub_process,e.dateofjoin as DOJ,e.emp_level, e.emp_status, t2.aadhar_status,t2.created_by , t2.created_at, t2.verify_date,t2.remarks,skipreason,l.location from (select EmployeeID,INTID,created_by,aadhar_status,created_at,verify_date,remarks,skipreason from aadhar_verifiaction where created_at between '" + date_from + " 00:00:00' and '" + date_to + " 23:59:59'  )t2 left join EmpID_Name p on  t2.EmployeeID=p.EmpID or t2.INTID=p.INTID or t2.INTID=p.EmpID left join employee_map e on t2.EmployeeID=e.EmployeeID left join new_client_master n on e.cm_id=n.cm_id left join client_master c on n.client_name=c.client_id left join location_master l on p.loc=l.id";

		var sp = "select a.EmployeeID,t2.INTID,a.EmployeeName,DOB,FatherName,client_name,process,sub_process,DOJ,emp_level,emp_status, t2.aadhar_status,t2.created_by , t2.created_at, t2.verify_date,t2.remarks,skipreason,t3.location from  Active_Emp_Base a join (select EmployeeID ,INTID,created_by,aadhar_status,created_at,verify_date,remarks,skipreason from aadhar_verifiaction where created_at between '" + date_from + " 00:00:00' and '" + date_to + " 23:59:59'  )t2   on t2.EmployeeID=a.EmployeeID join location_master t3 on a.location=t3.id";
		//alert(sp);
		//return false;
		var url = "textExport.php?sp=" + sp;
		// alert(url);
		window.location.href = url;
		return false;
	});
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>