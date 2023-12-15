<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH . 'AppCode/nHead.php');
$user_id = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($user_id)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
$remark = $empname = $msg = $searchBy = $empid = '';
$classvarr = "'.byID'";
$error = '';
?>

<script>
	$(document).ready(function() {
		$('.statuscheck').addClass('hidden');
		$('#txt_ED_joindate_to').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#txt_ED_joindate_from').datetimepicker({
			format: 'Y-m-d',
			timepicker: false
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
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
			}, 'pageLength']
		});


		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');
		$('#searchBy').change(function() {
			$('.byID').addClass('hidden');

		});
	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Manage Enrolled Employee</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Manage Enrolled Employee</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div id="pnlTable">
					<?php
					$api = CANDIDATE_INFO_URL . "getData/getVerifiedList.php?loc=" . rawurlencode($_SESSION["__location"]);
					// echo $api;
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $api);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HEADER, false);
					$data = curl_exec($curl);
					$data_array = json_decode($data);

					if (count($data_array) > 0) { ?>

						<table id="myTable" class="data dataTable no-footer" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>SN.</th>
									<th>EmployeeID</th>
									<th>EmployeeName</th>
									<th>DOB</th>
									<th>Primary Language</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								$fullname = '';
								foreach ($data_array as $key => $value) {
									$fullname = $value->fname;
									if ($value->mname != "") {
										$fullname .= ' ' . $value->mname;
									}
									$fullname .= ' ' . $value->lname;
									echo '<tr>';
									echo '<td id="countc' . $count . '">' . $count++ . '</td>';
									echo '<td  id="empid' . $count . '">' . $value->INTID . '</td>';
									echo '<td  id="empname' . $count . '" >' . $fullname . '</td>';
									echo '<td   id="bob' . $count . '" >' . $value->dob . '</td>';
									echo '<td  id="primary_language' . $count . '"  >' . $value->primary_language . '</td>';

								?>

									<td class="tbl__ID">
										<a href="add_personal.php?id=<?php echo $value->INTID; ?>"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a>

									</td>
								<?php echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php
					} else {
						echo "<script>$(function(){ toastr.error('Data Not Found (May be You Not Have Any Employee Assigned ):: <code ></code>') });</script>";
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