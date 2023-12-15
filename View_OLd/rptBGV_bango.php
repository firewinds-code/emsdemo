<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$query = 'select id,location from location_master';
$myDB = new MysqliDb();
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
$sql = "select p.EmployeeID,p.EmployeeName,p.location as location_id  ,des.designation,cm.client_name clientname,e.dateofjoin DOJ,c.Process,c.sub_process,dc.EmployeeID,dc.createdon,dc.doc_file  
 from doc_details dc left join   `employee_map` e on dc.EmployeeID=e.EmployeeID
        left JOIN `personal_details` p ON ((p.`EmployeeID` = e.`EmployeeID`))
        LEFT JOIN `new_client_master` c ON ((c.`cm_id` = e.`cm_id`))
        LEFT JOIN `df_master` d ON ((d.`df_id` =e.`df_id`))
         LEFT JOIN `designation_master` des ON ((des.`ID` = d.`des_id`))
        LEFT JOIN `client_master` cm ON ((cm.`client_id` = c.`client_name`))
   where   doc_type='BGV Report' and p.location=7 and cast(dc.createdon as date) and cast(e.dateofjoin as date) between  ? and ?;"

/*between '".$date_From."' and '".$date_To."' ";	*/
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
	<span id="PageTittle_span" class="hidden">BGV Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>BGV Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s3 m3"><br>

						Date of Join
					</div>

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
				<?php
				$myDB = new MysqliDb();
				$conn = $myDB->dbConnect();
				$selectQ = $conn->prepare($sql);
				$selectQ->bind_param("ss", $date_From, $date_To);
				$selectQ->execute();
				$result = $selectQ->get_result();
				// $result = $myDB->query($sql);
				?>
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
								<th>BGV Status</th>
								<th>Created Date</th>
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
									if ($value['doc_file'] != "") {
										$bgvStatus = 'Yes';
									} else {
										$bgvStatus = 'No';
									}
									echo '<tr>';
									echo '<td  id="countc' . $count . '">' . $count . '</td>';
									echo '<td  id="location_id' . $count . '"  >' . $lcation_name . '</td>';
									echo '<td  id="empid' . $value['EmployeeID'] . '" class="div_tempCard">' . $value['EmployeeID'] . '</td>';
									echo '<td  id="empid' . $value['EmployeeName'] . '" class="div_tempCard">' . $value['EmployeeName'] . '</td>';
									echo '<td  id="empid' . $value['DOJ'] . '" class="div_tempCard">' . $value['DOJ'] . '</td>';
									echo '<td  id="empid' . $value['designation'] . '" class="div_tempCard">' . $value['designation'] . '</td>';
									echo '<td  id="empid' . $value['clientname'] . '" class="div_tempCard">' . $value['clientname'] . '</td>';
									echo '<td  id="empid' . $value['Process'] . '" class="div_tempCard">' . $value['Process'] . '</td>';
									echo '<td  id="empid' . $value['sub_process'] . '" class="div_tempCard">' . $value['sub_process'] . '</td>';
									echo '<td  id="empid' . $count . '" class="div_tempCard">' . $bgvStatus . '</td>';
									echo '<td  id="createdon' . $count . '"  >' . date("j M, Y, g:i a", strtotime($value['createdon'])) . '</td>';
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

		<!--Content Div for all Page End -->
	</div>
	<script>
		$(document).ready(function() {});
	</script>
	<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>