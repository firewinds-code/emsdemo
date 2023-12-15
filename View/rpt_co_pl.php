<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb_replica.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;
if (isset($_SESSION)) {
	if (!isset($_SESSION['__user_logid'])) {
		$location = URL . 'Login';
		header("Location: $location");
		exit();
	} else {
		$isPostBack = false;
		$referer_base = '';
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer_base = $_SERVER['HTTP_REFERER'];
		}
		$current_Page = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
		if ($referer_base == $current_Page) {
			$isPostBack = true;
		}

		if ($isPostBack && isset($_POST['txt_dateMonth'])) {
			$date_To = $_POST['txt_dateMonth'];
			$date_From = $_POST['txt_dateYear'];
			$dept = $_POST['txt_dept'];
		} else {
			$date_To = intval(date('m', time()));
			$date_From = date('Y', time());
			$dept = 'CO';
		}
	}
	if (isset($_POST['empid']) && !empty($_POST['empid'])) {
		$EmployeeID = $_POST['empid'];
	} elseif (isset($_REQUEST['empid']) && !empty($_REQUEST['empid'])) {
		$EmployeeID = $_REQUEST['empid'];
	} else {
		$EmployeeID = $_SESSION['__user_logid'];
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Report CO and Leave</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Report CO and Leave<?php
									include(__dir__ . '/../Controller/coleave_cal.php');

									?></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<div class="input-field col s12 m12" id="rpt_container">
					<div class="input-field col s12 m12 hidden">
						<input type="hidden" id="empid" name="empid" value="<?php echo $EmployeeID; ?>" />
						<Select class="input-field col s4 m4" name="txt_dateMonth" id="txt_dateMonth">
							<option value="1" <?php if ($date_To == '1') echo ' selected '; ?>>Jan</option>
							<option value="2" <?php if ($date_To == '2') echo ' selected '; ?>>Feb</option>
							<option value="3" <?php if ($date_To == '3') echo ' selected '; ?>>Mar</option>
							<option value="4" <?php if ($date_To == '4') echo ' selected '; ?>>Apr</option>
							<option value="5" <?php if ($date_To == '5') echo ' selected '; ?>>May</option>
							<option value="6" <?php if ($date_To == '6') echo ' selected '; ?>>Jun</option>
							<option value="7" <?php if ($date_To == '7') echo ' selected '; ?>>Jul</option>
							<option value="8" <?php if ($date_To == '8') echo ' selected '; ?>>Aug</option>
							<option value="9" <?php if ($date_To == '9') echo ' selected '; ?>>Sep</option>
							<option value="10" <?php if ($date_To == '10') echo ' selected '; ?>>Oct</option>
							<option value="11" <?php if ($date_To == '11') echo ' selected '; ?>>Nov</option>
							<option value="12" <?php if ($date_To == '12') echo ' selected '; ?>>Dec</option>

						</Select>
						<Select class="input-field col s4 m4 hidden" name="txt_dateYear" id="txt_dateYear">
							<option <?php if ($date_From == '2016') echo ' selected '; ?>>2016</option>
							<option <?php if ($date_From == '2017') echo ' selected '; ?>>2017</option>
							<option <?php if ($date_From == '2018') echo ' selected '; ?>>2018</option>
							<option <?php if ($date_From == '2019') echo ' selected '; ?>>2019</option>
							<option <?php if ($date_From == '2020') echo ' selected '; ?>>2020</option>
							<option <?php if ($date_From == '2021') echo ' selected '; ?>>2021</option>
						</Select>
					</div>
					<div class="input-field col s10 m10">
						<Select name="txt_dept" id="txt_dept">
							<option value="PL" <?php if ($dept == 'PL') echo ' selected '; ?>>Paid Leave</option>
							<option value="CO" <?php if ($dept == 'CO') echo ' selected '; ?>>Compensatory Off</option>
						</Select>
					</div>
					<div class="input-field col s2 m2">
						<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view"> Search</button>
					</div>

				</div>
				<?php

				if ($dept == 'PL') {
					$myDB = new MysqliDb();
					$chk_task = $myDB->query('call get_paid_history_byEmployee("' . $EmployeeID . '","' . date('n') . '","' . date('Y') . '")');
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;border: none !important;">
						<div class=""  >
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>Date </th>';
						$table .= '<th>Paid Leave(On Date)</th>';

						$table .= '<th>Paid Leave(Used)</th>';
						$table .= '<th>Paid Leave(Available as on Date)</th></tr></thead><tbody>';

						foreach ($chk_task as $key => $value) {
							if ($value['date_field'] < date('Y-m-d', time())) {
								$paidused = $value['Paid_Leave'];
								if (empty($paidused))
									$paidused = 0;

								$table .= '<tr><td>' . $value['date_field'] . '</td>';
								$table .= '<td>' . ($value['remain'] + $paidused) . '</td>';
								$table .= '<td>' . $paidused . '</td>';
								$table .= '<td>' . $value['remain'] . '</td></tr>';
							}
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found " . addslashes($my_error) . "'); }); </script>";
					}
				} else if ($dept == 'CO') {
					$myDB = new MysqliDb();
					//$chk_task=$myDB->query('call get_co_history_byEmployee("'.$EmployeeID.'","'.$date_To.'","'.$date_From.'")');
					$chk_task = $myDB->query('call get_co_history_byEmployee("' . $EmployeeID . '")');
					$my_error = $myDB->getLastError();
					if (count($chk_task) > 0 && $chk_task) {
						$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
							    <div class=""  >																											            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
						$table .= '<th>Generated On</th>';
						$table .= '<th>Used On</th>';
						$table .= '<th>Expired On</th></tr></thead><tbody>';
						$count_co = 0;
						foreach ($chk_task as $key => $value) {

							$table .= '<tr><td>' . $value['generatedOn'] . '</td>';
							if ($value['usedOn']  == '') {
								$count_co++;
								$table .= '<td>Not Used Yet</td>';
							} else {
								$table .= '<td>' . $value['usedOn'] . '</td>';
							}

							if ($value['usedOn']  == '') {
								$table .= '<td>' . $value['ExpiredOn'] . '</td></tr>';
							} else {
								$table .= '<td></td></tr>';
							}
						}
						$table .= '</tbody></table></div></div>';
						echo $table;
						echo "<script>$(function(){ toastr.success('You have " . $count_co . " Compensatory Off in this list') }); </script>";
					} else {
						echo "<script>$(function(){ toastr.error('No Data Found " . addslashes($my_error) . "'); }); </script>";
					}
				} else {
					echo "<script>$(function(){ toastr.error('No Data Found " . addslashes($my_error) . "'); }); </script>";
				}


				?>

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>