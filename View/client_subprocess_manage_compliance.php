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
	//contain load event for data table and other importent rand required trigger event and searches if any
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
				/*  {
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
				},
				/* 'copy',*/
				'pageLength'

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
	<span id="PageTittle_span" class="hidden">Client Master Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Client Process Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->

				<div id="pnlTable">
					<?php
					$sqlConnect = 'select client_id,client_master.client_name,process,sub_process,client_status_master.id,new_client_master.cm_id,t1.location  from new_client_master inner join client_master on client_master.client_id = new_client_master.client_name left outer join client_status_master on new_client_master.cm_id = client_status_master.cm_id join location_master t1 on new_client_master.location=t1.id';
					$myDB = new MysqliDb();
					$result = $myDB->query($sqlConnect);
					if ($result) { ?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
							<thead>
								<tr>

									<th>Client</th>
									<th>Process</th>
									<th>Sub Process</th>
									<th>Location</th>
									<th>Status</th>

								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($result as $key => $value) {
									echo '<tr>';

									echo '<td class="client_name">' . $value['client_name'] . '</td>';
									echo '<td class="process">' . $value['process'] . '</td>';
									echo '<td class="sub_process">' . $value['sub_process'] . '</td>';
									echo '<td class="location">' . $value['location'] . '</td>';
									if (empty($value['id'])) {
										echo '<td class="text-fff green">Active</td>';
									} else {
										echo '<td class="text-fff orange">Inactive</td>';
									}

									echo '</tr>';
								}
								?>
							</tbody>
						</table>
					<?php
					}
					?>
					<!--Reprot / Data Table End -->
				</div>
				<!--Form container End -->
			</div>
			<!--Sub Main Div for all Page End -->
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

			},
			onCloseEnd: function(elm) {
				$('#btn_Department_Can').trigger("click");
			}
		});
		// This code for cancel button trigger click and also for model close
		$('#btn_Client_Can').on('click', function() {
			$('#txt_Client_Name').val('');
			$('#hid_Client_ID').val('');
			$('#btn_Client_Save').removeClass('hidden');
			$('#btn_Client_Edit').addClass('hidden');
			//$('#btn_Client_Can').addClass('hidden');
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
		});

		// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
		$('#btn_Client_Save').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#txt_Client_Name').val() == 'NA') {
				$('#txt_Client_Name').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_Client_Name').size() == 0) {
					$('<span id="spantxt_Client_Name" class="help-block">Required *</span>').insertAfter('#txt_Client_Name');
				}
				validate = 1;
			}
			if ($('#txt_status').val() == 'NA') {
				$('#txt_status').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if ($('#spantxt_status').size() == 0) {
					$('<span id="spantxt_status" class="help-block">Required *</span>').insertAfter('#txt_status');
				}
				validate = 1;
			}

			if (validate == 1) {
				return false;
			}
		});

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
	});
	// This code for trigger edit on all data table also trigger model open on a Model ID
	function EditData(el, elv) {
		$('#txt_Client_Name').val(el.id);
		$('#txt_status').val(elv);
		$('#modal-content').modal('open');
		$("#modal-content input,#modal-content textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
		$('select').formSelect();
		$('#myModal_content').modal('open');
		$("#myModal_content input,#myModal_content textarea").each(function(index, element) {
			if ($(element).val().length > 0) {
				$(this).siblings('label, i').addClass('active');
			} else {
				$(this).siblings('label, i').removeClass('active');
			}

		});
	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>