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
	if (!isset($_SESSION['__user_logid'])) {
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

			$dept = $_POST['txt_dept'];
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
				// $getDetails = 'select t1.er_scop,t1.er_spoc2,t1.er_spoc3,t2.EmployeeName,t2.designation,t3.mobile from new_client_master t1 join whole_details_peremp t2 on t1.er_scop=t2.employeeid  join contact_details t3 on t3.EmployeeID=t2.EmployeeID where t1.cm_id="' . $_SESSION["__cm_id"] . '"';
				// $getDetails = 'select t1.er_scop,t1.er_spoc2,t1.er_spoc3,t3.EmpName,t5.Designation,t6.mobile from new_client_master t1 
				// join employee_map t2 on t1.er_scop=t2.EmployeeID  
				// join EmpID_Name t3 on t2.EmployeeID=t3.EmpID
				// join df_master t4 on t2.df_id=t4.df_id
				// join designation_master t5 on t4.des_id=t5.ID
				// join contact_details t6 on t2.EmployeeID=t6.EmployeeID where t1.cm_id="' . $_SESSION["__cm_id"] . '" ';
				$getDetails = 'SELECT er_scop, er_spoc2,er_spoc3 FROM ems.new_client_master where cm_id="' . $_SESSION["__cm_id"] . '" ';
				$myDB = new MysqliDb();
				$result_all = $myDB->rawQuery($getDetails);
				$MysqliError = $myDB->getLastError();
				if (empty($MysqliError) && count($result_all) > 0 && $result_all) {
					$er_scop = $result_all[0]['er_scop'];
					$er_spoc2 = $result_all[0]['er_spoc2'];
					$er_spoc3 = $result_all[0]['er_spoc3'];
				}

				if ($er_scop && $er_scop != "") {
					$geterspoc1 = 'SELECT er_scop,t2.EmpName as er_scop_name,t5.Designation,t6.mobile FROM ems.new_client_master t1
					join EmpID_Name t2 on t1.er_scop=t2.EmpID
					join employee_map t3 on t2.EmpID=t3.EmployeeID
					join df_master t4 on t3.df_id=t4.df_id
					join designation_master t5 on t4.des_id=t5.ID
					join contact_details t6 on t2.EmpID=t6.EmployeeID where t1.cm_id="' . $_SESSION["__cm_id"] . '" ';
					$myDB = new MysqliDb();
					$result_all1 = $myDB->rawQuery($geterspoc1);
					$MysqliError = $myDB->getLastError();
					if (empty($MysqliError) && count($result_all1) > 0 && $result_all1) {
						$name1 = $result_all1[0]['er_scop_name'];
						$desig1 = $result_all1[0]['Designation'];
						$con1 = $result_all1[0]['mobile'];
					}

					$table = '<div class="col s8 m8" style="margin-left: 15%;">';

					$table .= '<div class="col s8 m8" style="align-content: center;width: 100%; border: 1px solid blueviolet;"><div class="card"><div class="card-content ">';
					$table .= '<span class="card-title">ER SPOC 1</span></div><div><br/></div>';

					$table .= '<div class="area1"><div class="area2">Name :</div><div class="area3">' . $name1 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Designation :</div><div class="area3">' . $desig1 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Contact #: </div><div class="area3">' . $con1 . '</div></div>';

					$table .= '</div></div>';

					$table .= '</div>';

					echo $table;

				?>
					<div class="col s8 m8"> <br /></div>
				<?php }  ?>

				<?php
				if ($er_spoc2 && $er_spoc2 != "") {
					$geterspoc2 = 'SELECT er_spoc2,t2.EmpName as er_spoc_name2,t5.Designation,t6.mobile FROM ems.new_client_master t1
					join EmpID_Name t2 on t1.er_spoc2=t2.EmpID
					join employee_map t3 on t2.EmpID=t3.EmployeeID
					join df_master t4 on t3.df_id=t4.df_id
					join designation_master t5 on t4.des_id=t5.ID
					join contact_details t6 on t2.EmpID=t6.EmployeeID where t1.cm_id="' . $_SESSION["__cm_id"] . '" ';
					$myDB = new MysqliDb();
					$result_all2 = $myDB->rawQuery($geterspoc2);
					$MysqliError = $myDB->getLastError();
					if (empty($MysqliError) && count($result_all2) > 0 && $result_all2) {
						$name2 = $result_all2[0]['er_spoc_name2'];
						$desig2 = $result_all2[0]['Designation'];
						$con2 = $result_all2[0]['mobile'];
					}

					$table = '<div class="col s8 m8" style="margin-left: 15%;">';

					$table .= '<div class="col s8 m8" style="align-content: center;width: 100%; border: 1px solid blueviolet;"><div class="card"><div class="card-content ">';
					$table .= '<span class="card-title">ER SPOC 2</span></div><div><br/></div>';

					$table .= '<div class="area1"><div class="area2">Name :</div><div class="area3">' . $name2 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Designation :</div><div class="area3">' . $desig2 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Contact #: </div><div class="area3">' . $con2 . '</div></div>';

					$table .= '</div></div>';

					$table .= '</div>';

					echo $table; ?>

					<div class="col s8 m8"> <br /></div>
				<?php }  ?>

				<?php
				if ($er_spoc3 && $er_spoc3 != "") {
					$geterspoc3 = 'SELECT er_spoc3,t2.EmpName as er_spoc_name3,t5.Designation,t6.mobile FROM ems.new_client_master t1
					join EmpID_Name t2 on t1.er_spoc3=t2.EmpID
					join employee_map t3 on t2.EmpID=t3.EmployeeID
					join df_master t4 on t3.df_id=t4.df_id
					join designation_master t5 on t4.des_id=t5.ID
					join contact_details t6 on t2.EmpID=t6.EmployeeID where t1.cm_id="' . $_SESSION["__cm_id"] . '" ';
					$myDB = new MysqliDb();
					$result_all3 = $myDB->rawQuery($geterspoc3);
					$MysqliError = $myDB->getLastError();
					if (empty($MysqliError) && count($result_all3) > 0 && $result_all3) {
						$name3 = $result_all3[0]['er_spoc_name3'];
						$desig3 = $result_all3[0]['Designation'];
						$con3 = $result_all3[0]['mobile'];
					}

					$table = '<div class="col s8 m8" style="margin-left: 15%;">';

					$table .= '<div class="col s8 m8" style="align-content: center;width: 100%; border: 1px solid blueviolet;"><div class="card"><div class="card-content ">';
					$table .= '<span class="card-title">ER SPOC 3</span></div><div><br/></div>';

					$table .= '<div class="area1"><div class="area2">Name :</div><div class="area3">' . $name3 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Designation :</div><div class="area3">' . $desig3 . '</div></div>';

					$table .= '<div class="area1"><div class="area2">Contact #: </div><div class="area3">' . $con3 . '</div></div>';

					$table .= '</div></div>';

					$table .= '</div>';

					echo $table; ?>

					<div class="col s8 m8"> <br /></div>
				<?php } ?>

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