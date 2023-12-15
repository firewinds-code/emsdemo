<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// $myDB = new MysqliDb();
// $conn = $myDB->dbConnect();
$EmployeeID = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {
	if (!isset($EmployeeID)) {
		$location = URL . 'Login';
		header("Location: $location");
		die();
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	die();
}
$alert_msg = '';
?>

<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			scrollY: 195,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [

				{
					extend: 'csv',
					text: 'CSV',
					extension: '.csv',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				},
				'print',
				{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'copy', 'pageLength'

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
	<span id="PageTittle_span" class="hidden">Announcement</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Announcement</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<?php
				$sqlConnect = 'select * from whole_details_peremp inner join announcement_inproc on whole_details_peremp.cm_id = announcement_inproc.cm_id left outer join acknowledge_details on whole_details_peremp.EmployeeID = acknowledge_details.EmployeeID and announcement_inproc.id = acknowledge_details.action_id  where whole_details_peremp.EmployeeID = ?';
				$selectQ = $conn->prepare($sqlConnect);
				$selectQ->bind_param("s", $EmployeeID);
				$selectQ->execute();
				$result = $selectQ->get_result();
				// print_r($result);
				// $result = $myDB->query($sqlConnect);
				if ($result->num_rows > 0) { ?>
					<div class="panel panel-default pull-left" style="margin-top: 10px;width: 100%;">
						<div class="panel-body">
							<?php
							foreach ($result as $key => $value) {
							?>
								<div>
									<fieldset class="scheduler-border">
										<legend><?php echo $value['announcement_heading']; ?> </legend>
										<div>
											<pre><?php echo $value['announcement_body']; ?></pre>
											<?php
											if (empty($value['EmployeeID'])) { ?>
												<button type="button" class="btn btn-primary btn-rounded" data_id="<?php echo $value['id']; ?>" name="btn_save" id="btn_save" onclick="javascript:return ApplicationData(this);"><i class="fa fa-hand-o-up"></i> Acknowledge</button>
											<?php } ?>
										</div>
									</fieldset>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				<?php
				} else {
					echo "<script>$(function(){ toastr.error('No announcement found'); }); </script>";
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
	function ApplicationData(el) {


		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var Resp = xmlhttp.responseText;
				alert(Resp);

			}
		}
		xmlhttp.open("GET", "../Controller/accept_announcement_proc.php?ID=" + $('#' + el.id).attr('data_id'), true);
		xmlhttp.send();
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>