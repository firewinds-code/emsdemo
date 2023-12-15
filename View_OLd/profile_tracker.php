<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

if (isset($_SESSION)) {
	$usr_log = clean($_SESSION['__user_logid']);
	if (!isset($usr_log)) {
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

		if ($isPostBack && isset($_POST)) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$rpt_type = cleanUserInput($_POST['txt_type']);
			}
		} else {
			$rpt_type = 'NA';
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
			scrollX: '100%',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

			]
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
	<span id="PageTittle_span" class="hidden">Profile Tracker</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Profile Tracker</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				$myDB = new MysqliDb();
				$userLOgID = clean($_SESSION['__user_logid']);
				$chk_task = $myDB->query("call sp_profile_tracker('" . $userLOgID . "')");
				$my_error = $myDB->getLastError();
				if (count($chk_task) > 0 && $chk_task) {
					$table = ' <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						  <div class=""  >																											                                     <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';

					$table .= '<th>Employee ID</th>';
					$table .= '<th>Employee Name</th>';
					$table .= '<th>DOJ</th>';
					$table .= '<th>DOD</th>';

					$table .= '<th>DOB</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Gender</th>';

					$table .= '<th>Client</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Sub Process</th>';
					$table .= '<th>Contact#</th>';
					$table .= '<th>Experience</th>';
					$table .= '<th>Language</th>';
					$table .= '<th>Location</th>';
					$table .= '<th>Highest Qualification</th>';
					$table .= '<th>Address</th><thead><tbody>';


					foreach ($chk_task as $key => $value) {

						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['DOJ'] . '</td>';
						$table .= '<td>' . $value['DOD'] . '</td>';

						$table .= '<td>' . $value['DOB'] . '</td>';
						$table .= '<td>' . $value['designation'] . '</td>';
						$table .= '<td>' . $value['Gender'] . '</td>';
						$table .= '<td>' . $value['clientname'] . '</td>';
						$table .= '<td>' . $value['process'] . '</td>';


						$table .= '<td>' . $value['sub_process'] . '</td>';
						$table .= '<td>' . $value['ContactNo'] . '</td>';
						$table .= '<td>' . $value['Experience'] . '</td>';
						$table .= '<td>' . $value['primary_language'] . '</td>';
						$table .= '<td>' . $value['location'] . '</td>';
						$table .= '<td>' . $value['Qualification'] . '</td>';

						$date1 = date_create(str_replace(",", " ", $value['DOJ']));
						$date2 = date_create(date('Y-m-d', time()));
						$diff = date_diff($date1, $date2)->format("%R%a");

						/*if($diff <= 7)
						{
							$table .='<td>'.$value['password'].'</td>';	
						}
						else
						{
							$table .='<td>******</td>';	

						}*/
						//	$table .='<td>******</td>';	
						$table .= '<td>' . $value['Address'] . '</td></tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . " '); }); </script>";
				}


				/*if(isset($_POST['btn_export']))
			{
				  
				  $_SESSION['query'] = 'call sp_getLeaveStatus("'.$_SESSION['__user_logid'].'","'.$date_From.'","'.$date_To.'")';
				  require_once('export_rpt_leavestatus.php');
				  $_SESSION['query'] ='';
			}*/
				?>

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>