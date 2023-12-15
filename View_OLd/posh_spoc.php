<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$emp_location = '';
if (isset($_SESSION)) {
	$user_log = clean($_SESSION['__user_logid']);
	if (!isset($user_log)) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		if (clean($_SESSION['__gender']) != 'FEMALE' && clean($_SESSION['__user_logid']) != "CE10091236") {
			$location = URL . 'Error';
			header("Location: $location");
			exit();
		}
		$Empl = substr($_SESSION['__user_logid'], 0, 2);
		if ($Empl == 'MU') {
			$emp_location = 'Mumbai';
		} else {
			$emp_location = 'Noida';
		}
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
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$dept = cleanUserInput($_POST['txt_dept']);
			}
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
	<span id="PageTittle_span" class="hidden">POSH SPOC LIST</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>POSH SPOC LIST</h4>
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
				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<?php
				$name1 = $name2 = $desig1 = $desig2 = $con1 = $con2 = $table = '';
				$loc = clean($_SESSION["__location"]);
				if ($loc == "1") {
					$name1 = "Sapna Verma";
					$desig1 = "Manager";
					$con1 = "9910749203";
				} else if ($loc == "2") {
					$name1 = "Priya Sunil Gupta";
					$desig1 = "Senior Executive";
					$con1 = "8855078777";

					$name2 = "Manisha Suryavanshi";
					$desig2 = "Assistant Manager";
					$con2 = "9320774008";
				} else if ($loc == "3") {
					$name1 = "Divya Dhingra";
					$desig1 = "Assistant Manager";
					$con1 = "9045935001";
				} else if ($loc == "4") {
					$name1 = "Pratibha Chauhan";
					$desig1 = "Senior Executive";
					$con1 = "8859162815";
				} else if ($loc == "5") {
					$name1 = "Arti Prabodh Bhai Shah";
					$desig1 = "Assistant Manager";
					$con1 = "9898373732";
				} else if ($loc == "6") {
					$name1 = "Sushmitha K";
					$desig1 = "Team Lead";
					$con1 = "7975665239";
				} else if ($loc == "7") {
					$name1 = "Leena CC";
					$desig1 = "Manager";
					$con1 = "8747803435";
				}

				if ($loc != "2") {
					$table = '<div class="col s8 m8" style="margin-left: 15%;">';


					$table .= '<div class="col s8 m8" style="align-content: center;width: 100%; border: 1px solid blueviolet;"><div class="card"><div class="card-content ">';
					$table .= '<span class="card-title">POSH SPOC - L1</span></div><div><br/></div>';


					$table .= '<div class="area1"><div class="area2">Name :</div><div class="area3">' . $name1 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Designation :</div><div class="area3">' . $desig1 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Contact #: </div><div class="area3">' . $con1 . '</div></div>';


					$table .= '</div></div>';

					$table .= '</div>';
				} else if ($loc == "2") {
					$table = '<div class="col s8 m8" style="margin-left: 15%;">';


					$table .= '<div class="col s8 m8" style="align-content: center;width: 100%; border: 1px solid blueviolet;"><div class="card"><div class="card-content ">';
					$table .= '<span class="card-title">POSH SPOC - L1</span></div>';

					$table .= '<div class="card-body"><div class="col s12 m12"><div style="font-size: 14px;font-weight: bold;background-color:blue;"><div class="col s3 m3">Name</div><div class="col s3 m3">Designation</div><div class="col s3 m3">Location</div><div class="col s3 m3">Contact</div></div>';
					$table .= '<div style="font-size: 14px;margin-top: 37px;margin-bottom: 95px;"><div class="col s3 m3">' . $name1 . '</div><div class="col s3 m3">' . $desig1 . '</div><div class="col s3 m3">Mumbai - Palava</div><div class="col s3 m3">' . $con1 . '</div></div>';
					$table .= '<div style="font-size: 14px;margin-bottom: 155px;"><div class="col s3 m3">' . $name2 . '</div><div class="col s3 m3">' . $desig2 . '</div><div class="col s3 m3">Mumbai - Thane</div><div class="col s3 m3">' . $con2 . '</div></div></div>';

					$table .= '</div></div></div>';

					$table .= '</div>';
				}

				echo $table;

				?>
				<div class="col s8 m8"> <br /></div>
				<?php
				$table = '<div class="col s8 m8" style="margin-left: 15%;">';


				$table .= '<div class="col s8 m8" style="align-content: center;width: 100%; border: 1px solid blueviolet;"><div class="card"><div class="card-content ">';
				$table .= '<span class="card-title">POSH SPOC - L2</span></div>';

				$table .= '<div class="card-body"><div class="col s12 m12"><div style="font-size: 14px;font-weight: bold;background-color:blue;"><div class="col s4 m4">Name</div><div class="col s4 m4">Designation</div><div class="col s4 m4">Contact</div></div>';
				$table .= '<div style="font-size: 14px;margin-top: 37px;margin-bottom: 72px;"><div class="col s4 m4">Banpreet Kaur</div><div class="col s4 m4">General Manager</div><div class="col s4 m4">9540045651</div></div>';
				//$table .= '<div style="font-size: 14px;margin-bottom: 110px;"><div class="col s4 m4">Gayathri Ravishankar</div><div class="col s4 m4">General Manager</div><div class="col s4 m4">9886135559</div></div></div>';
				/*$table .='<p>Designation :- Assistant Manager</p>';
		$table .='<p>Contact #:- 9910749203</p>';*/

				/*$table .='<p style="font-size: 10px;font-weight: bold;"><span>Associates :</span> '.$value['Associates'].' | <span>Support :</span> '.$value['Support'].'</p>';*/
				$table .= '</div></div></div>';

				$table .= '</div>';
				echo $table;

				?>



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