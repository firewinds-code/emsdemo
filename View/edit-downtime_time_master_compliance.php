<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Only for user type administrator
if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE01145570' || $_SESSION['__user_logid'] == 'CE12102224') {
	// proceed further
} else {
	$location = URL;
	echo "<script>location.href='" . $location . "'</script>";
}

?>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollCollapse: true,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			"sScrollX": "100%",
			buttons: [

				/* {
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
				 'print',*/
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
				}
				/*,'copy'*/
				, 'pageLength'

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
	<span id="PageTittle_span" class="hidden">Downtime Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Downtime Master</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<!--Reprot / Data Table start -->
				<div id="pnlTable">
					<?php
					//$sqlConnect = 'SELECT * FROM downtime_time_master inner join new_client_master on new_client_master.cm_id = downtime_time_master.cm_id inner join client_master  on new_client_master.client_name = client_master.client_id';
					$sqlConnect = ' SELECT dt.*,nc.*,cm.*,t1.location FROM downtime_time_master dt inner join new_client_master nc on nc.cm_id = dt.cm_id inner join client_master cm  on nc.client_name = cm.client_id inner join location_master t1 on t1.id = nc.location left outer join client_status_master cs on cs.cm_id=nc.cm_id where cs.cm_id is null order by cm.client_name';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="hidden">Process</th>
									<th>Process</th>
									<th>Client Training</th>
									<th>Location</th>
									<th>Total Time</th>
									<th>Min Time</th>
									<th>Max Time</th>
									<th>OJT Days</th>
									<th>Training Days</th>
									<th>OJT Day 1st</th>
									<th>OJT Day 2nd</th>
									<th>OJT Day 3rd</th>
									<th>OJT Day 4th</th>
									<th>OJT Day 5th</th>
									<th>OJT Day 6th</th>
									<th>OJT Day 7th</th>
									<th>OJT Day 8th</th>
									<th>OJT Day 9th</th>
									<th>OJT Day 10th</th>
									<th>OJT Day 11th</th>
									<th>OJT Day 12th</th>
									<th>OJT Day 13th</th>
									<th>OJT Day 14th</th>
									<th>OJT Day 15th</th>
									<th>OJT Day 16th</th>
									<th>OJT Day 17th</th>
									<th>OJT Day 18th</th>
									<th>OJT Day 19th</th>
									<th>OJT Day 20th</th>

								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="id hidden">' . $value['id'] . '</td>';
									echo '<td class="cm_id" data="' . $value['cm_id'] . '">' . $value['client_name'] . ' | ' . $value['process'] . ' | ' . $value['sub_process'] . '</td>';
									echo '<td class="client_training">' . $value['client_training'] . '</td>';
									echo '<td class="location">' . $value['location'] . '</td>';
									echo '<td class="client_time_ttl">' . $value['client_time_ttl'] . '</td>';
									echo '<td class="client_time_min">' . $value['client_time_min'] . '</td>';
									echo '<td class="client_time_max">' . $value['client_time_max'] . '</td>';
									echo '<td class="ojt_days">' . $value['ojt_days'] . '</td>';
									echo '<td class="training_days">' . $value['training_days'] . '</td>';
									echo '<td class="ojt_day_1">' . $value['ojt_day_1'] . '</td>';
									echo '<td class="ojt_day_2">' . $value['ojt_day_2'] . '</td>';
									echo '<td class="ojt_day_3">' . $value['ojt_day_3'] . '</td>';
									echo '<td class="ojt_day_4">' . $value['ojt_day_4'] . '</td>';
									echo '<td class="ojt_day_5">' . $value['ojt_day_5'] . '</td>';
									echo '<td class="ojt_day_6">' . $value['ojt_day_6'] . '</td>';
									echo '<td class="ojt_day_7">' . $value['ojt_day_7'] . '</td>';
									echo '<td class="ojt_day_8">' . $value['ojt_day_8'] . '</td>';
									echo '<td class="ojt_day_9">' . $value['ojt_day_9'] . '</td>';
									echo '<td class="ojt_day_10">' . $value['ojt_day_10'] . '</td>';
									echo '<td class="ojt_day_11">' . $value['ojt_day_11'] . '</td>';
									echo '<td class="ojt_day_12">' . $value['ojt_day_12'] . '</td>';
									echo '<td class="ojt_day_13">' . $value['ojt_day_13'] . '</td>';
									echo '<td class="ojt_day_14">' . $value['ojt_day_14'] . '</td>';
									echo '<td class="ojt_day_15">' . $value['ojt_day_15'] . '</td>';
									echo '<td class="ojt_day_16">' . $value['ojt_day_16'] . '</td>';
									echo '<td class="ojt_day_17">' . $value['ojt_day_17'] . '</td>';
									echo '<td class="ojt_day_18">' . $value['ojt_day_18'] . '</td>';
									echo '<td class="ojt_day_19">' . $value['ojt_day_19'] . '</td>';
									echo '<td class="ojt_day_20">' . $value['ojt_day_20'] . '</td>';


									echo '</tr>';
								}
								?>
							</tbody>
						</table>

					<?php
					}
					?>
				</div>
				<!--Reprot / Data Table End -->

				<!--Form container End -->
			</div>
			<!--Main Div for all Page End -->
		</div>
		<!--Content Div for all Page End -->
	</div>

</div>
<script>
	$(document).ready(function() {

		//Model Assigned and initiation code on document load	
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Client_Can').trigger("click");
			}
		});

		// This code for remove error span from all element contain .has-error class on listed events
		$(document).on("click blur focus change", ".has-error", function() {
			$(".has-error").each(function() {
				if ($(this).hasClass("has-error")) {
					$(this).removeClass("has-error");
					$(this).next("span.help-block").remove();
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
					if ($(this).hasClass('select-dropdown')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
				}
			});
		});

		$('#btn_df_Can').on('click', function() {
			$('#txtEditID').val('');
			$('#txt_cm_id').val('NA');
			$('#txt_client_training').val('NO');
			$('#txt_min_time').val('00:00:00');
			$('#txt_max_time').val('00:00:00');
			$('#txt_total_time').val('00:00:00');
			$('#txt_ojt_days').val('0');
			$('#txt_training_days').val('0');

			$("select[name^='txt_day_']").each(function() {

				$(this).val("00:00:00");

			});


			$('#btn_df_Save').removeClass('hidden');
			$('#btn_df_Edit').addClass('hidden');
			//$('#btn_df_Can').addClass('hidden');

			// This code for remove error span from input text on model close and cancel
			$(".has-error").each(function() {
				if ($(this).hasClass("has-error")) {
					$(this).removeClass("has-error");
					$(this).next("span.help-block").remove();
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}
					if ($(this).hasClass('select-dropdown')) {
						$(this).parent('.select-wrapper').find("span.help-block").remove();
					}

				}
			});
			// This code active label on value assign when any event trigger and value assign by javascript code.

			$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

				if ($(element).val().length > 0) {
					$(this).siblings('label, i').addClass('active');
				} else {
					$(this).siblings('label, i').removeClass('active');
				}

			});
			$('select').formSelect();

		});


		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.

		$('#btn_df_Save,#btn_df_Edit').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
					validate = 1;
					$(this).addClass('has-error');
					if ($(this).is('select')) {
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#' + spanID).size() == 0) {
						$('<span id="' + spanID + '" class="help-block"></span>').insertAfter('#' + $(this).attr('id'));
					}
					var attr_error = $(this).attr('data-error-msg');
					if (!(typeof attr_error !== typeof undefined && attr_error !== false)) {
						$('#' + spanID).html('Required *');
					} else {
						$('#' + spanID).html($(this).attr("data-error-msg"));
					}



				}
			})

			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			}

		});

	});

	function EditData(el) {
		var tr = $(el).closest('tr');
		var id = tr.find('.id').text();
		var cm_id = tr.find('.cm_id').attr('data');


		$('#txtEditID').val(id);
		//$('#txt_cm_id').val(cm_id);
		$('#txt_location').val('NA');
		var location = tr.find('.location').text();

		$("#txt_location option:contains(" + location + ")").attr('selected', 'selected');
		getProcess($('#txt_location').val(), cm_id);


		$('#txt_client_training').val(tr.find('.client_training').text());
		$('#txt_min_time').val(tr.find('.client_time_min').text());
		$('#txt_max_time').val(tr.find('.client_time_max').text());
		$('#txt_total_time').val(tr.find('.client_time_ttl').text());
		$('#txt_ojt_days').val(tr.find('.ojt_days').text());
		$('#txt_training_days').val(tr.find('.training_days').text());
		$('#txt_cm_id').val(cm_id);
		$("select[name^='txt_day_']").each(function() {
			var temp_id = $(this).attr('name');
			temp_id = temp_id.match(/\d+/)[0];
			$(this).val(tr.find('.ojt_day_' + temp_id).text());

		});

		$('#btn_df_Save').addClass('hidden');
		$('#btn_df_Edit').removeClass('hidden');
		//$('#btn_df_Can').removeClass('hidden');
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
	}

	// This code for trigger del*t*
	function ApplicationDataDelete(el) {
		var currentUrl = window.location.href;
		var Cnfm = confirm("Do You Want To Delete This ");
		if (Cnfm) {
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
					window.location.href = currentUrl;
				}
			}

			xmlhttp.open("GET", "../Controller/delete_downtime_time_master.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}

	function getProcess(el, el1) {
		var currentUrl = window.location.href;

		var xmlhttp;
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {


				var Resp = xmlhttp.responseText;
				$('#txt_cm_id').html(Resp);
				$('select').formSelect();
			}

		}
		$('#txt_clientname').val($("#txt_client option:selected").text());
		var location = <?php echo $_SESSION["__location"] ?>;
		xmlhttp.open("GET", "../Controller/getprocessByLocation.php?loc=" + $('#txt_location').val() + "&cmid=" + el1, true);
		xmlhttp.send();
		//$('#txt_cm_id').val(el1);
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>