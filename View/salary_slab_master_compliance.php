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
if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE01145570') {
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
			scrollCollapse: true,
			"iDisplayLength": 25,
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [
				/*   
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
	<span id="PageTittle_span" class="hidden">Salary Slab Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Salary Slab Master </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">

				<!--Reprot / Data Table start -->

				<div id="pnlTable">
					<?php
					$sqlConnect = 'SELECT t1.id, t1.cm_id, t1.min_lim, t1.max_lim, t1.avg_sal, t3.client_name, t2.process, t4.location, t2.sub_process
 FROM tbl_salary_slab_by_cps t1 inner join new_client_master t2 on t2.cm_id = t1.cm_id inner join client_master t3 on t2.client_name = t3.client_id inner join location_master t4 on t4.id=t2.location left outer join client_status_master cs on cs.cm_id=t2.cm_id where cs.cm_id is null  order by client_name';
					$myDB = new MysqliDb();
					$result = $myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if (empty($mysql_error)) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="hidden">Process</th>
									<th>Process</th>
									<th>Minimum Value</th>
									<th>Maximum Value</th>
									<th>Average</th>
									<th>Location</th>

								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';
									echo '<td class="id hidden">' . $value['id'] . '</td>';
									echo '<td class="cm_id" data="' . $value['cm_id'] . '">' . $value['client_name'] . ' | ' . $value['process'] . ' | ' . $value['sub_process'] . '</td>';
									echo '<td class="min_lim">' . $value['min_lim'] . '</td>';
									echo '<td class="max_lim">' . $value['max_lim'] . '</td>';
									echo '<td class="avg_sal">' . $value['avg_sal'] . '</td>';
									echo '<td class="loc">' . $value['location'] . '</td>';

									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php } ?>
				</div>

				<!--Reprot / Data Table End -->
			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		//Model Assigned and initiation code on document load	
		$('.modal').modal({
			onOpenStart: function(elm) {
				/*$('#txt_cm_id').attr('disabled', false);
				$('#txt_location').attr('disabled', false);*/
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
		// This code for cancel button trigger click and also for model close
		$('#btn_df_Can').on('click', function() {

			/*$('#txt_cm_id').attr('disabled', false);
	$('#txt_location').attr('disabled', false);*/
			$('#txtEditID').val('');
			$('#txt_maxValue').val('');
			$('#txt_minValue').val('');
			$('#txt_cm_id').val('NA');
			$('#txt_AverageValue').val('NA');
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
			/*$('#txt_cm_id').attr('disabled', false);
	$('#txt_location').attr('disabled', false);*/
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			if ($('#txt_location').val() == 'NA' || $('#txt_location').val() == '') {
				$('#txt_location').addClass("has-error");
				if ($('#spantxt_location').size() == 0) {
					$('<span id="spantxt_location" class="help-block">Required *</span>').insertAfter('#txt_location');
				}
				validate = 1;
			}
			if ($('#txt_cm_id').val() == 'NA') {
				$('#txt_cm_id').addClass("has-error");
				if ($('#spantxt_cm_id').size() == 0) {
					$('<span id="spantxt_cm_id" class="help-block">Required *</span>').insertAfter('#txt_cm_id');
				}
				validate = 1;
			}

			if ($('#txt_minValue').val() == '') {
				$('#txt_minValue').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_minValue').size() == 0) {
					$('<span id="spantxt_minValue" class="help-block">Required *</span>').insertAfter('#txt_minValue');
				}
				validate = 1;
			}
			if ($('#txt_maxValue').val() == '') {
				$('#txt_maxValue').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_maxValue').size() == 0) {
					$('<span id="spantxt_maxValue" class="help-block">Required *</span>').insertAfter('#txt_maxValue');
				}
				validate = 1;
			}
			if ($('#txt_AverageValue').val() == '') {
				$('#txt_AverageValue').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_AverageValue').size() == 0) {
					$('<span id="spantxt_AverageValue" class="help-block">Required *</span>').insertAfter('#txt_AverageValue');
				}
				validate = 1;
			}

			if (validate == 1) {
				/*$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
				$('#alert_message').show().attr("class","SlideInRight animated");
				$('#alert_message').delay(50000).fadeOut("slow");*/
				return false;
			}

		});

	});

	function EditData(el) {
		var tr = $(el).closest('tr');
		var id = tr.find('.id').text();
		var minVal = tr.find('.min_lim').text();
		var maxVal = tr.find('.max_lim').text();
		var AverageValue = tr.find('.avg_sal').text();
		var cm_id = tr.find('.cm_id').attr('data');

		$('#txtEditID').val(id);
		$('#txt_maxValue').val(maxVal);
		$('#txt_minValue').val(minVal);
		//$('#txt_cm_id').val(cm_id);
		$('#txt_AverageValue').val(AverageValue);

		$('#btn_df_Save').addClass('hidden');
		$('#btn_df_Edit').removeClass('hidden');
		//$('#btn_df_Can').removeClass('hidden');
		$('#txt_location').val('NA');
		var location = tr.find('.loc').text();

		$("#txt_location option:contains(" + location + ")").attr('selected', 'selected');
		getProcess($('#txt_location').val(), cm_id);

		/*$('#txt_cm_id').attr('disabled', true);
		$('#txt_location').attr('disabled', true);*/

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
		//$('#txt_clientname').val($("#txt_client option:selected").text());
		var location = <?php echo $_SESSION["__location"] ?>;
		xmlhttp.open("GET", "../Controller/getprocessByLocation.php?loc=" + $('#txt_location').val() + "&cmid=" + el1, true);
		xmlhttp.send();
		//$('#txt_cm_id').val(el1);
	}

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
			xmlhttp.open("GET", "../Controller/delete_salary_slab_master.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>