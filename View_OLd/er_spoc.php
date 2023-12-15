<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$emp_location = '';
if (isset($_SESSION)) {
	$user_logid = clean($_SESSION['__user_logid']);
	if (!isset($user_logid)) {
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

		if ($isPostBack && isset($_POST['txt_dept'])) {
			$dept = cleanUserInput($_POST['txt_dept']);
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
	exit();
}


?>

<script>
	$(function() {

		$('#myTable').DataTable({
			dom: 'Bfrtip',
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
				}

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"sScrollY": "192",
			"bScrollCollapse": true,
			"bLengthChange": false

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
	<span id="PageTittle_span" class="hidden">ER SPOC</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>ER SPOC</h4>
			<style>
				.area1 {
					width: 100%;
					padding-bottom: 30px;
				}

				.area2 {
					width: 50%;
					float: left;
					text-align: right;
					padding-right: 10px;
					font-weight: bold;
					font-size: 14px;
				}

				.area3 {
					width: 50%;
					float: left;
					padding-left: 5px;
					font-size: 14px
				}

				.card-body {
					padding: 15px;
				}

				.card-body p {
					text-align: center;
				}

				.card-content {

					border-bottom: 1px solid #f0f0f0;
				}
			</style>
			<!-- Form container if any -->
			<div class="schema-form-section row">


				<?php
				$name1 = $desig1 = $con1 = ' - ';
				$cmid = clean($_SESSION["__cm_id"]);
				$getDetails = 'select t1.er_scop,t2.EmployeeName,t2.designation,t3.mobile from new_client_master t1 join whole_details_peremp t2 on t1.er_scop=t2.employeeid  join contact_details t3 on t3.EmployeeID=t2.EmployeeID where t1.cm_id=?';
				$selectQ = $conn->prepare($getDetails);
				$selectQ->bind_param("i", $cmid);
				$selectQ->execute();
				$result = $selectQ->get_result();
				$result_all = $result->fetch_row();
				// $myDB = new MysqliDb();
				// $result_all = $myDB->rawQuery($getDetails);
				// $MysqliError = $myDB->getLastError();
				if ($result->num_rows > 0 && $result) {
					$name1 = clean($result_all[1]);
					$desig1 = clean($result_all[2]);
					$con1 = clean($result_all[3]);
				}

				$table = '<div class="col s8 m8" style="margin-left: 15%;">';


				$table .= '<div class="col s8 m8" style="align-content: center;width: 100%; border: 1px solid blueviolet;"><div class="card"><div class="card-content ">';
				$table .= '<span class="card-title">ER SPOC</span></div><div><br/></div>';


				$table .= '<div class="area1"><div class="area2">Name :</div><div class="area3">' . $name1 . '</div></div>';

				$table .= '<div class="area1"><div class="area2">Designation :</div><div class="area3">' . $desig1 . '</div></div>';

				$table .= '<div class="area1"><div class="area2">Contact #: </div><div class="area3">' . $con1 . '</div></div>';


				$table .= '</div></div>';

				$table .= '</div>';



				echo $table;

				?>
				<div class="col s8 m8"> <br /></div>




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
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>