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
$value = $counEmployee = $countProcess = $countClient = 0;
$mymsg = '';
if (isset($_POST['btnSave'])) {
	$queryto = $_POST['queryto'];
	$querysub = $_POST['querysub'];
	$querybody = $_POST['querybody'];
	$createdby = $_SESSION['__user_logid'];
	$myDB = new MysqliDb();
	$result = $myDB->$query('call add_query("' . $createdby . '","' . $queryto . '","' . $querysub . '","' . $querybody . '")');
	$Error = $myDB->getLastError();
	if (empty($Error)) {
		echo "<script>$(function(){ toastr.success('Query Submited, We will try our best to resolve it as soon as possible. Thank You <b class='text-danger'>Mr. " . $_SESSION['__user_Name'] . "</b>') }); </script>";
	} else {
		echo "<script>$(function(){ toastr.error('Query Not Submited') }); </script>";
	}
}
?>



<script>
	$(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollX: '100%',
			scrollCollapse: true,
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
	<span id="PageTittle_span" class="hidden">Issue Request</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Issue Request</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<div id="pnlTable">
					<?php
					$result = '';
					$myDB = new MysqliDb();
					// if (clean($_SESSION['__user_logid']) == "CE041929970" || clean($_SESSION['__user_logid']) == "CE0321936546" || clean($_SESSION['__user_logid']) == "CE011929750") {
					// 	$result = $myDB->query('call get_issuetracker_migration_byclient("' . clean($_SESSION['__user_logid']) . '")');
					// } else if (clean($_SESSION['__user_logid']) == "CE01145570") {
					$result = $myDB->query('call get_issuetracker_migration_new("' . clean($_SESSION['__user_logid']) . '","' . clean($_SESSION["__location"]) . '")');
				//	echo 'call get_issuetracker_migration_new("' . clean($_SESSION['__user_logid']) . '","' . clean($_SESSION["__location"]) . '")';
					// } else {
					// 	$result = $myDB->query('call get_issuetracker_migration("' . clean($_SESSION['__user_logid']) . '","' . clean($_SESSION["__location"]) . '")');
					// }

					if ($result) {
						echo '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">';
						echo '<thead><tr><th>Case ID</th><th class="">EmployeeID</th><th>Request By</th><th>Query </th><th>Communicated With</th><th>Request Date</th><th>Status</th><th>Manage</th></thead>';
						foreach ($result as $key => $value) {
							echo '<tr>';
							echo '<td>' . $value['id'] . '</td>';
							echo '<td>' . $value['requestby'] . '</td>';
							echo '<td>' . $value['EmployeeName'] . '</td>';
							echo '<td>' . $value['queary'] . '</td>';
							//echo '<td>'.$value['bt'].'</td>';		
							//echo '<td>'.$value['requesttoName'].'</td>';							
							//echo '<td>'.$value['issue_master']['tat'].' Hour</td>';
							echo '<td>' . $value['committed_with'] . '</td>';
							//echo "<td>".date('d-m-Y',strtotime($value['issue_tracker']['concern_off']))."</td>";
							echo '<td>' . $value['request_date'] . '</td>';
							if ($value['status'] == 'Reopen') {
								echo '<td class="Reopen">' . $value['status'] . '</td>';
							} else if ($value['status'] == 'Resolve') {
								echo '<td class="Resolve">' . $value['status'] . '</td>';
							} else if ($value['status'] == 'Refer') {
								echo '<td class="Refer">InProgress</td>';
							}
							else if (($value['status'] == 'Payout Request' || $value['status'] == 'Payout Reviewed' )&& $value['requestby'] == clean($_SESSION['__user_logid'])) {
							echo '<td class="Refer">Inprogress</td>';
							}

							else {
								echo '<td>' . $value['status'] . '</td>';
							}

							$encID = encryptor('encrypt', $value['id']);

							if ($value['requestby'] == clean($_SESSION['__user_logid']) || ($value['requestby'] == 'CE03070003' && clean($_SESSION['__user_logid']) == 'CE01145570' && clean($_SESSION['__user_logid']) == 'CE021929762')) {
								echo '<td><a target="_self" class="btn waves-effect waves-green red darken-3 white-text" style="min-width: 5px; !important" href=" ' . URL . 'View/open_issue.php?ID*DSAad=refcode_' . $encID . '_dabhd" >Open</a></td>';
							} else {
								// if ($value['status'] == 'PayoutRequest') {
								// 	echo '<td><a target="_self" class="btn waves-effect modal-action modal-close waves-red close-btn" style="min-width: 5px; !important" href="' . URL . 'View/payout_issue.php?ID*DSAad=refcode_' . $encID . '_dabhd">Payout</a></td>';
								// }
								if ($value['status'] == 'Reopen') {
									echo '<td><a target="_self" class="btn waves-effect modal-action modal-close waves-red close-btn" style="min-width: 5px; !important" href="' . URL . 'View/check_issue.php?ID*DSAad=refcode_' . $encID . '_dabhd">View</a></td>';
								} else {
									echo '<td><a target="_self" class="btn waves-effect waves-green" style="min-width: 5px; !important" href="' . URL . 'View/check_issue.php?ID*DSAad=refcode_' . $encID . '_dabhd" >View</a></td>';
								}
							}
							echo '</tr>';
						}
						echo '<tbody>';
						echo '</tbody></table>';
					} else {
						echo "<script>$(function(){ toastr.info('No Issues Ticket Raised till now') }); </script>";
					}
					?>
				</div>




			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
	$(function() {

		$('#alertdiv').delay(5000).fadeOut("slow");

		$('.check').each(function() {
			if ($(this).text() == 'Done !') {
				$(this).closest('tr').css('background', 'rgba(147, 255, 169, 0.4)');

			} else if ($(this).text() == 'Decline') {
				$(this).closest('tr').css('background', 'rgba(255, 156, 147, 0.4)');
			} else if ($(this).text() == 'Pending') {
				$(this).closest('tr').css('background', 'white');
			}
		});

	});
</script>