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
	$clean_user_log_in = clean($_SESSION['__user_logid']);
	if (!isset($clean_user_log_in)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
// Global variable used in Page Cycle
$clean_from_date = '';
if (isset($_POST['send'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_from_date = cleanUserInput($_POST['from_date']);
		$clean_to_date = cleanUserInput($_POST['to_date']);

		$myDB = new MysqliDb();
		$conn = $myDB->dbConnect();
		$sqlConnect = "select ssm.mnth_year,l.location,sm.site,cm.client_name as ClientName,ssm.process,ssm.seat,
FORMAT(ROUND((B.price/C.seat)*ssm.seat,0),0,'en_IN') as processCost
		from   site_master as sm
		left join site_seat_master as ssm  on sm.id = ssm.site_id and  sm.mnth_year =ssm.mnth_year
		left join location_master l on l.id = sm.loc_id  
		left join client_master cm on cm.client_id = ssm.client_id  
		left join 
        (SELECT site_id,txt_date, sum(price) price   FROM site_cost_master  where txt_date BETWEEN ? AND ? group by site_id,txt_date)B on sm.id =B.site_id and    sm.mnth_year =B.txt_date
		left join
        (SELECT site_id, mnth_year,sum(seat) seat FROM site_seat_master where mnth_year BETWEEN ? AND ? group by site_id,mnth_year) C on  sm.id =C.site_id and sm.mnth_year =C.mnth_year
		 where ssm.mnth_year BETWEEN ? AND ?";
		$selectQury = $conn->prepare($sqlConnect);
		$selectQury->bind_param("ssssss", $clean_from_date, $clean_to_date, $clean_from_date, $clean_to_date, $clean_from_date, $clean_to_date);
		$selectQury->execute();
		$result = $selectQury->get_result();
		$numRow = $result->num_rows;
	}
}

?>


<script>
	$(document).ready(function() {
		$('#from_date').datetimepicker({
			format: 'Y-m',
			timepicker: false
		});
		$('#to_date').datetimepicker({
			format: 'Y-m',
			timepicker: false
		});
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			buttons: [{
				extend: 'excel',
				text: 'EXCEL',
				extension: '.xlsx',
				exportOptions: {
					modifier: {
						page: 'all'
					}
				},
				title: 'seat_report'
			}, 'pageLength']
		});

		$('.buttons-copy').attr('id', 'buttons_copy');
		$('.buttons-csv').attr('id', 'buttons_csv');
		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-pdf').attr('id', 'buttons_pdf');
		$('.buttons-print').attr('id', 'buttons_print');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');
		$('.byDate').addClass('hidden');
		$('.byDept').addClass('hidden');
		$('.byProc').addClass('hidden');
		$('.byName').addClass('hidden');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Seat Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Seat Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<form method="POST" action="#">
					<?php $_SESSION["token"] = csrfToken(); ?>
					<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

					<div class="input-field col s5 m5 clsIDHome">
						<input type='text' name='from_date' id='from_date' <?php if (isset($clean_from_date)) { ?> value="<?php echo $clean_from_date; ?>" <?php } ?> autocomplete="off">
						<label for="from_date">From Date</label>
					</div>
					<div class="input-field col s5 m5 clsIDHome">
						<input type='text' name='to_date' id='to_date' <?php if (isset($clean_to_date)) { ?> value="<?php echo $clean_to_date; ?>" <?php } ?> autocomplete="off">
						<label for="to_date">To Date</label>
					</div>
					<div class="input-field col s2 m2 clsIDHome">
						<input type="submit" value="Search" name="send" id="send" class="btn waves-effect waves-green" />
					</div>
				</form>
				<!--Form element model popup End-->
				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					/* if ($numRow >0 && isset($clean_from_date)) {  */ ?>
					<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Sr. No.</th>
									<th>Month</th>
									<th>Location</th>
									<th>Site</th>
									<th>Client</th>
									<th>Process</th>
									<th>Seat</th>
									<th>Process Cost</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;

								foreach ($result as $key => $value) {
									echo '<tr style="vertical-align:top;">';
									echo '<td>' . $i . '</td>';
									echo '<td>' . $value['mnth_year'] . '</td>';
									echo '<td>' . $value['location'] . '</td>';
									echo '<td>' . $value['site'] . '</td>';
									echo '<td>' . $value['ClientName'] . '</td>';
									echo '<td>' . $value['process'] . '</td>';
									echo '<td>' . $value['seat'] . '</td>';
									echo '<td>' . $value['processCost'] . '</td>';
									echo '</tr>';
									$i++;
								}
								?>
							</tbody>
						</table>
					</div>
					<?php
					/* } else {
							echo "<script>$(function(){ toastr.error('No Data Found'); }); </script>";
						} */

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

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
<script>
	$(document).ready(function() {
		$('#div_error').click(function() {
			$('#div_error').hide();
		});
		if ($('#alert_msg').text() == '') {
			$('#alert_message').hide();
		}
		$('#div_error').removeClass('hidden');
		$('#send').on('click', function() {

			validate = 0;
			alert_msg = "";
			var from_date = $('#from_date').val().trim();
			var to_date = $('#to_date').val().trim();
			if (from_date == "" || to_date == "") {
				// alert("Please select both date  from and to");
				toastr.error('Please select both date  from and to');
				validate = 1;
				alert_msg += '<li> Please select both date  from and to </li>';
			}

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");

				return false;
			}
		});

	});
</script>