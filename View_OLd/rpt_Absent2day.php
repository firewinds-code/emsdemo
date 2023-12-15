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
$value = $counEmployee = $countProcess = $countClient = $countSubproc = $date_From = 0;
/*if(time() > strtotime('12:00:00') && time() < strtotime('12:30:00')) 
{
	$location= URL.'Login';
	echo "<script>location.href='".$location."'</script>";
}*/
if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;
		$referer = "";
		$thisPage = REQUEST_SCHEME . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}
		if ($referer == $thisPage) {
			$isPostBack = true;
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>
<script>
	$(function() {

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
			}, 'pageLength'],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollY": "192",
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
	<span id="PageTittle_span" class="hidden">Day 2 of Absentiseem Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->


			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->

				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<?php

				$dateA = date_create(date('Y-m-d'));
				$dateB = date_create(date('Y-m-d'));


				$date1 = date_sub($dateA, date_interval_create_from_date_string('1 days'));
				$date2 = date_sub($dateB, date_interval_create_from_date_string('2 days'));

				$d1 = 'D' . $date1->format('j');
				$d2 = 'D' . $date2->format('j');

				$m	= $date1->format('n');
				$y  = $date1->format('Y');
				$loc = '';
				if (isset($_SESSION["__location"])) {
					$loc = clean($_SESSION["__location"]);
				}
				$sqlstr = '';
				$myDB = new MysqliDb();
				$conn = $myDB->dbConnect();
				$user_type = clean($_SESSION['__user_type']);
				$status_ah = clean($_SESSION['__status_ah']);
				$user_logid = clean($_SESSION['__user_logid']);
				$status_er = clean($_SESSION['__status_er']);

				if ($user_type == 'HR' && (($status_ah != 'No' && $status_ah == $user_logid) && $status_ah != '')) {
					$sqlstr = "select w.EmployeeID,t1.EmployeeName,date_format(w.DOJ,'%D %M %Y') as DOJ,t2.mobile,t2.altmobile,t4.client_name,t3.process,t3.sub_process from ActiveEmpID w inner join (select EmployeeID  from calc_atnd_master where month=$m and year =$y and concat($d1,$d2)='AA' )cal on w.EmployeeID = cal.EmployeeID  left join excp_emp_ncns_msg ex on w.EmployeeID = ex.EmpID join personal_details t1 on t1.EmployeeID=cal.EmployeeID join contact_details t2 on cal.EmployeeID=t2.EmployeeID join new_client_master t3 on w.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id where ex. EmpID is null and t1.location=$loc";
					$selectQ = $conn->prepare($sqlstr);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
				} else if (($status_er != 'No' && $status_er == $user_logid) && $status_er != '') {
					$sqlstr = "select w.EmployeeID,t1.EmployeeName,date_format(w.DOJ,'%D %M %Y') as DOJ,t2.mobile,t2.altmobile,t4.client_name,t3.process,t3.sub_process from ActiveEmpID w inner join (select EmployeeID  from calc_atnd_master where month=$m and year =$y and concat($d1,$d2)='AA' )cal on w.EmployeeID = cal.EmployeeID  left join excp_emp_ncns_msg ex on w.EmployeeID = ex.EmpID join personal_details t1 on t1.EmployeeID=cal.EmployeeID join contact_details t2 on cal.EmployeeID=t2.EmployeeID join new_client_master t3 on w.cm_id=t3.cm_id join client_master t4 on t3.client_name=t4.client_id where ex. EmpID is null and t1.location=$loc and t3.er_scop=?";
					$selectQ = $conn->prepare($sqlstr);
					$selectQ->bind_param("s", $user_logid);
					$selectQ->execute();
					$chk_task = $selectQ->get_result();
				}

				$type = '';

				// $chk_task = $myDB->query($sqlstr);
				// $my_error = $myDB->getLastError();
				if ($chk_task->num_rows > 0 && $chk_task) {
					$table = '
				<table id="myTable" class="data dataTable no-footer row-border">
				<thead>
				<tr>';
					$table .= '<th>EmployeeID</th>';
					$table .= '<th>EmployeeName</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>Mobile</th>';
					$table .= '<th>Alt Mobile</th>';
					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th></tr></thead><tbody>';

					foreach ($chk_task as $key => $value) {
						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['mobile'] . '</td>';
						$table .= '<td>' . $value['altmobile'] . '</td>';

						$table .= '<td>' . $value['client_name'] . '</td><td>' . $value['process'] . '</td><td>' . $value['sub_process'] . '</td></tr>';
					}
					$table .= '</tbody></table>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Record found.'); }); </script>";
				}
				?>

				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
	$(document).ready(function() {

	});
</script>