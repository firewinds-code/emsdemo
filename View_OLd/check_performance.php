<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

require_once(LIB . 'PHPExcel/IOFactory.php');
$EmployeeID = clean($_SESSION['__user_logid']);
$date_To = $date_From = '';
if (isset($_SESSION)) {
	if (!isset($EmployeeID)) {
		$location = URL . 'Login';
		header("Location: $location");
	} else {
		$isPostBack = false;
		$referer = "";
		$alert_msg = "";
		$thisPage = REQUEST_SCHEME . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		}
		if ($referer == $thisPage) {
			$isPostBack = true;
		}
		if ($isPostBack) {
			if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
				$dateMonth = cleanUserInput($_POST['txt_dateMonth']);
				$dateYear = cleanUserInput($_POST['txt_dateYear']);
			}
			if (isset($dateMonth)) {
				$date_To = $dateMonth;
			} else {
				$date_To = date('M', time());
			}
			if (isset($dateYear)) {
				$date_From = $dateYear;
			} else {
				$date_From = date('Y', time());
			}
		} else {
			$date_To = date('M', time());
			$date_From = date('Y', time());
		}
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">My Performance Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>My Performance Report</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
				<div class="input-field col s6 m6">
					<select name="txt_dateMonth" id="txt_dateMonth">
						<option <?php if ($date_To == 'Jan') echo 'selected'; ?>>Jan</option>
						<option <?php if ($date_To == 'Feb') echo 'selected'; ?>>Feb</option>
						<option <?php if ($date_To == 'Mar') echo 'selected'; ?>>Mar</option>
						<option <?php if ($date_To == 'Apr') echo 'selected'; ?>>Apr</option>
						<option <?php if ($date_To == 'May') echo 'selected'; ?>>May</option>
						<option <?php if ($date_To == 'Jun') echo 'selected'; ?>>Jun</option>
						<option <?php if ($date_To == 'Jul') echo 'selected'; ?>>Jul</option>
						<option <?php if ($date_To == 'Aug') echo 'selected'; ?>>Aug</option>
						<option <?php if ($date_To == 'Sep') echo 'selected'; ?>>Sep</option>
						<option <?php if ($date_To == 'Oct') echo 'selected'; ?>>Oct</option>
						<option <?php if ($date_To == 'Nov') echo 'selected'; ?>>Nov</option>
						<option <?php if ($date_To == 'Dec') echo 'selected'; ?>>Dec</option>
					</select>
					<label for="txt_dateMonth" class="active-drop-down active">Month</label>
				</div>

				<div class="input-field col s6 m6">
					<select name="txt_dateYear" id="txt_dateYear">
						<option <?php if ($date_From == '2016') echo ' selected '; ?>>2016</option>
						<option <?php if ($date_From == '2017') echo ' selected '; ?>>2017</option>
						<option <?php if ($date_From == '2018') echo ' selected '; ?>>2018</option>
						<option <?php if ($date_From == '2019') echo ' selected '; ?>>2019</option>
						<option <?php if ($date_From == '2020') echo ' selected '; ?>>2020</option>
						<option <?php if ($date_From == '2021') echo ' selected '; ?>>2021</option>
						<option <?php if ($date_From == '2022') echo ' selected '; ?>>2022</option>
					</select>
					<label for="txt_dateYear" class="active-drop-down active">Year</label>
				</div>

				<div class="input-field col s12 m12 right-align">
					<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
					<!--<button type="submit" class="button button-3d-action" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
				</div>


				<h4 class="no-padding">MTD Performance Data</h4>

				<div class="input-field col s12 m12">
					<?php
					$myDB = new MysqliDb();
					//echo 'call get_performance_MIS("' . $EmployeeID . '","' . $date_From . '","' . $date_To . '")';
					$rst_mis = $myDB->query('call get_performance_MIS("' . $EmployeeID . '","' . $date_From . '","' . $date_To . '")');
					// print_r($rst_mis);
					if ($rst_mis) {
						echo '<div class="row" >';
						foreach ($rst_mis as $key => $value) {
							$rst_arr = array_values($value);
							for ($i = 0; $i < count($rst_arr); $i++) {
								if (($i % 2) == 0) {
									if (!empty($rst_arr[$i]) && !empty($rst_arr[$i + 1]))
										echo '<div class="col s3 m3">
											   <div class="card">
											       <div class="card-content blue lighten-1 ">
											          <span class="card-title  white-text text-darken-2" style="font-size: 12px;font-weight:bold;">' . $rst_arr[$i] . '</span>
											       </div>
											       <div  class="card-action" style="z-index: 0 !important;">' . $rst_arr[$i + 1] . '</div>
										       </div>
									     </div>';
								}
							}
						}
						echo '</div>';
					}
					?>
				</div>

				<h4 class="no-padding">Daily Performance Data</h4>

				<div class="input-field col s12 m12">
					<?php
					$myDB = new MysqliDb();
					$rst_daily = $myDB->query('call get_performance_Daily("' . $EmployeeID . '","' . $date_From . '","' . $date_To . '")');
					// print_r($rst_daily);
					if ($rst_daily) {
						echo '<div class="row card">';
						$header_table = array();
						$rst_arr = array_values($rst_daily[0]);
						for ($i = 0; $i < count($rst_arr); $i++) {
							if ($i % 2 == 0) {
								$flag = 0;
								foreach ($rst_daily as $key => $value) {
									$temp_arr = array_values($value);
									if (!empty($temp_arr[$i]) && !empty($temp_arr[$i + 1])) {
										if ($flag == 0) {
											$header_table[$i] = $temp_arr[$i];
											//array_push($header_table,$temp_arr[$i]);
										}
										$flag++;
									}
								}
							}



							//if(!empty($rst_arr[$i])&&!empty($rst_arr[$i+1]))
							//echo '<div class="col-sm-3"><div class="head_per"><b>'.$rst_arr[$i].'</b></div><div  class="body_per">'.$rst_arr[$i+1].'</div></div>';
						}

						echo '<div  style="overflow:auto;"> <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
								<thead><tr >';
						$count = 0;
						foreach ($header_table as $val) {
							if ($count > 0) {
								echo '<th class="blue lighten-1 white-text text-darken-2">' . $val . '</th>';
							} else {
								echo '<th style="min-width: 120px;" class="blue lighten-1 white-text text-darken-2"> Date </th>';
							}
							$count++;
						}
						echo '<th style="min-width: 120px;" class="blue lighten-1 white-text text-darken-2">Acknowledge</th>';
						echo '</tr></thead><tbody>';
						foreach ($rst_daily as $key => $value) {
							echo '<tr>';
							$temp_arr = array_values($value);
							foreach ($header_table as $k => $v) {
								echo '<td>' . $temp_arr[$k + 1] . '</td>';
							}
							if ($value['Acknowledge_date'] == '') {
								//echo '<td><button class="btn waves-effect waves-green" name="btn_view" id="btn_view" onclick="javascript:return DepentDelete(this);" id="'.$value['id'].'">Acknowledge</button></td>';
								echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return DepentDelete(this);" id="' . $value['id'] . '" data-position="left" data-tooltip="Acknowledge">ohrm_edit</i> </td>';
							} else {
								echo '<td> Acknowledge </td>';
							}


							echo '</tr>';
						}
						echo '</tbody></table></div></div>';
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

<script>
	$(function() {
		$('#head_daily').click(function() {
			$('#pnl_daily').toggleClass('hidden');
		});
		$('#head_mis').click(function() {
			$('#pnl_mis').toggleClass('hidden');
		});

	});

	function DepentDelete(el) {
		$item = $(el);
		//alert(el);
		//alert($item.closest("td").html('sdfdsfsdf'));
		if (confirm('Are you sure to acknowledge ')) {
			//$item=$(el);
			$.ajax({
				url: "../Controller/ack_performance.php?ID=" + $item.attr("id"),
				success: function(result) {
					//$var=result.split('|');
					//alert(result);
					if (result != 'No') {
						//alert(result);
						//$item.closest("tr").remove();
						$item.closest("td").html('Acknowledge');
						//$('select').formSelect();
					}
					$('select').formSelect();
				}
			});
		}
	}
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>