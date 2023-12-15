<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

$query = 'select id,location from location_master';
$location_array = array();
$result = $myDB->query($query);
foreach ($result as $lval) {
	$location_array[$lval['id']] = $lval['location'];
}
$isPostBack = 1;

if ($isPostBack && isset($_POST['txt_dateTo'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$date_To = cleanUserInput($_POST['txt_dateTo']);
		$date_From = cleanUserInput($_POST['txt_dateFrom']);
	}
} else {
	$date_To = date('Y-m-d', time());
	$d = new DateTime($date_To);
	$d->modify('first day of this month');
	//$date_From= $d->format('Y-m-d');
	$date_From = date('Y-m-d', time());
}

$sql = "select p.EmployeeID,p.EmployeeName,p.location as location_id  ,des.designation,cm.client_name clientname,e.dateofjoin DOJ,c.Process,c.sub_process,s.datetime  from signup_policy_ack s left join   `employee_map` e on s.EmployeeID=e.EmployeeID
        left JOIN `personal_details` p ON ((p.`EmployeeID` = e.`EmployeeID`))
        LEFT JOIN `new_client_master` c ON ((c.`cm_id` = e.`cm_id`))
        LEFT JOIN `df_master` d ON ((d.`df_id` =e.`df_id`))
         LEFT JOIN `designation_master` des ON ((des.`ID` = d.`des_id`))
        LEFT JOIN `client_master` cm ON ((cm.`client_id` = c.`client_name`))
   where    cast(s.datetime as date) between  ? and ? ;";
$selectQury = $conn->prepare($sql);
$selectQury->bind_param("ss", $date_From, $date_To);
$selectQury->execute();
$result = $selectQury->get_result();
?>

<script>
	$(document).ready(function() {
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
			}, 'pageLength'],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"iDisplayLength": 10,
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
	<span id="PageTittle_span" class="hidden">Code Of Conduct Policy Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Code Of Conduct Policy Report</h4>
			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3"><br><b><u>Acknowledge Date:</u></b></div>
					<div class="input-field col s3 m3">
						<input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" autocomplete="off" />
					</div>
					<div class="input-field col s3 m3">
						<input type="text" name="txt_dateTo" id="txt_dateTo" value="<?php echo $date_To; ?>" autocomplete="off" />
					</div>
					<div class="input-field col s3 m3">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					</div>
				</div>
			</div>
			<div id="pnlTable">

				<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
					<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>SN.</th>
								<th>Location</th>
								<th>EmployeeID</th>
								<th>EmployeeName</th>
								<th>DOJ</th>
								<th>Designation</th>
								<th>Client</th>
								<th>Process</th>
								<th>Sub Process</th>
								<th>Acknowledge Date</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if ($result->num_rows > 0) {
								foreach ($result as $key => $value) {
									$lcation_name = '';
									if ($value['location_id'] != "") {
										$lcation_name = $location_array[$value['location_id']];
									}
									echo '<tr>';
									echo '<td>' . $count . '</td>';
									echo '<td>' . $lcation_name . '</td>';
									echo '<td>' . $value['EmployeeID'] . '</td>';
									echo '<td>' . $value['EmployeeName'] . '</td>';
									echo '<td>' . $value['DOJ'] . '</td>';
									echo '<td>' . $value['designation'] . '</td>';
									echo '<td>' . $value['clientname'] . '</td>';
									echo '<td>' . $value['Process'] . '</td>';
									echo '<td>' . $value['sub_process'] . '</td>';
									echo '<td>' . date("j M, Y, g:i a", strtotime($value['datetime'])) . '</td>';
									echo '</tr>';
									$count++;
								}
							} else {
								echo "<tr><td colspan='6'>Data not found</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>


			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>