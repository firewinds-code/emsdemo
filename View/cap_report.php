<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

if ($_SESSION["__user_logid"] == 'CE03070003' || $_SESSION["__user_logid"] == 'CE09134997' || $_SESSION["__user_logid"] == 'CE01145570' || $_SESSION["__user_logid"] == 'CE12102224' || ($_SESSION['__status_ah'] != 'No' && $_SESSION['__status_ah'] == $_SESSION['__user_logid'] && $_SESSION['__status_ah'] != '')) {
	// proceed further
} else {
	$location = URL . 'Error';
	header("Location: $location");
	exit();
}
?>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, 25, 50, -1],
				['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [{
					extend: 'excel',
					text: 'EXCEL',
					extension: '.xlsx',
					exportOptions: {
						modifier: {
							page: 'all'
						}
					},
					title: 'table'
				}, 'pageLength'

			],
			"bProcessing": true,
			"bDestroy": true,
			"bAutoWidth": true,
			"iDisplayLength": 10,
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {
				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}
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
	<span id="PageTittle_span" class="hidden">CAP Report</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>CAP Report<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Filter"><i class="material-icons">ohrm_filter</i></a></h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<!--Form element model popup start-->
				<div id="myModal_content" class="modal">
					<!-- Modal content-->
					<div class="modal-content">
						<h4 class="col s12 m12 model-h4">Report</h4>
						<div class="modal-body">

							<div class="input-field col s6 m6">
								<input type="text" id="DateFrom" name="DateFrom">
								<label for="DateFrom">Date From</label>
							</div>
							<div class="input-field col s6 m6">
								<input type="text" id="DateTo" name="DateTo">
								<label for="DateFrom">Date To</label>
							</div>
						</div>


						<div class="input-field col s12 m12 right-align">

							<button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">Search</button>
							<!--<button type="submit" class="button button-3d-action" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
							<button type="button" name="btn_Can" id="btn_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
						</div>

					</div>
				</div>
			</div>
			<!--Form element model popup End-->
			<!--Reprot / Data Table start -->

			<?php
			if (isset($_POST['btn_view'])) {
				$date_From = date("Y-m-d", strtotime($_POST['DateFrom']));
				$date_To = date("Y-m-d", strtotime($_POST['DateTo']));
				$myDB = new MysqliDb();
				$query = '';
				if ($_SESSION["__user_logid"] == 'CE03070003' || $_SESSION["__user_logid"] == 'CE09134997' || $_SESSION["__user_logid"] == 'CE01145570' || $_SESSION["__user_logid"] == 'CE12102224') {

					$query = " select A.*,B.*,C.HR_Comment,D.head_comment,E.emp_comment from 
  (select t1.id,t1.employee_id,employee_name,issued_date,position,department,supervisor_name,issue_type,description_of_issue,t1.statusHead,statusHr,t1.created_by,t1.created_at, l.location  from corrective_action_form t1 left join personal_details as w on w.EmployeeID=t1.employee_id left join location_master as l on l.id=w.location where  
  
  (cast(t1.created_at as date) between '" . $date_From . "' and '" . $date_To . "') order by t1.created_at) A
  left join 
(select ijp,incentive,pli,corrective_Formid from corrective_action_formhead where id in 
(select max(id) from corrective_action_formhead group by corrective_Formid))B
on A.id = B.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',hr_comment )) SEPARATOR ' | ') as HR_Comment,corrective_Formid from corrective_action_formhr group by corrective_Formid order by id)C
on A.id = C.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',head_comment )) SEPARATOR ' | ') as head_comment,corrective_Formid from corrective_action_formhead group by corrective_Formid order by id)D
on A.id = D.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',emp_comment )) SEPARATOR ' | ') as emp_comment,corrective_Formid from corrective_action_formemp group by corrective_Formid order by id)E
on A.id = E.corrective_Formid;";
				} else if ($_SESSION["__user_logid"] == 'CE01080195') {
					$query = " select A.*,B.*,C.HR_Comment,D.head_comment,E.emp_comment from 
					(select t1.id,t1.employee_id,employee_name,issued_date,position,department,supervisor_name,issue_type,description_of_issue,t1.statusHead,statusHr,t1.created_by,t1.created_at, w.EmployeeID,l.location  from corrective_action_form t1 left join personal_details as w on w.EmployeeID=t1.employee_id left join location_master as l on l.id=w.location where 
  position='CSA' and
  (cast(t1.created_at as date) between '" . $date_From . "' and '" . $date_To . "') order by t1.created_at) A
  left join 
(select ijp,incentive,pli,corrective_Formid from corrective_action_formhead where id in 
(select max(id) from corrective_action_formhead group by corrective_Formid))B
on A.id = B.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',hr_comment )) SEPARATOR ' | ') as HR_Comment,corrective_Formid from corrective_action_formhr group by corrective_Formid order by id)C
on A.id = C.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',head_comment )) SEPARATOR ' | ') as head_comment,corrective_Formid from corrective_action_formhead group by corrective_Formid order by id)D
on A.id = D.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',emp_comment )) SEPARATOR ' | ') as emp_comment,corrective_Formid from corrective_action_formemp group by corrective_Formid order by id)E
on A.id = E.corrective_Formid;";


					//echo $Q;


				} else if ($_SESSION['__status_ah'] != 'No' && $_SESSION['__status_ah'] == $_SESSION['__user_logid'] && $_SESSION['__status_ah'] != '') {
					$query = " select A.*,B.*,C.HR_Comment,D.head_comment,E.emp_comment from 
					(select t1.id,t1.employee_id,employee_name,issued_date,position,department,supervisor_name,issue_type,description_of_issue,t1.statusHead,statusHr,t1.created_by,t1.created_at,t4.location from corrective_action_form t1 join employee_map t2 on t1.employee_id=t2.EmployeeID join new_client_master t3 on t2.cm_id=t3.cm_id join location_master t4 on t4.id=t3.location where 
  t3.account_head= '" . $_SESSION['__user_logid'] . "' and
  (cast(t1.created_at as date) between '" . $date_From . "' and '" . $date_To . "') order by t1.created_at) A
  left join 
(select ijp,incentive,pli,corrective_Formid from corrective_action_formhead where id in 
(select max(id) from corrective_action_formhead group by corrective_Formid))B
on A.id = B.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',hr_comment )) SEPARATOR ' | ') as HR_Comment,corrective_Formid from corrective_action_formhr group by corrective_Formid order by id)C
on A.id = C.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',head_comment )) SEPARATOR ' | ') as head_comment,corrective_Formid from corrective_action_formhead group by corrective_Formid order by id)D
on A.id = D.corrective_Formid
left join
(select GROUP_CONCAT(CONCAT_WS('', concat(created_by,'-',created_at,' : ',emp_comment )) SEPARATOR ' | ') as emp_comment,corrective_Formid from corrective_action_formemp group by corrective_Formid order by id)E
on A.id = E.corrective_Formid;";
				}

				//echo $query;
				//die;
				$chk_task = $myDB->query($query);
				$my_error = $myDB->getLastError();
				if (count($chk_task) > 0 && $chk_task) {
					$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">';
					$table = '<div>';

					$table = '<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
					$table .= '<th>Employee ID</th>';
					$table .= '<th>Employee Name</th>';
					$table .= '<th>Location</th>';
					$table .= '<th>Issue Date</th>';
					$table .= '<th>Designation</th>';
					$table .= '<th>Department</th>';
					$table .= '<th>Supervisor Name</th>';
					$table .= '<th>IJP</th>';
					$table .= '<th>Incentive</th>';
					$table .= '<th>PLI</th>';
					$table .= '<th>Status Head</th>';
					$table .= '<th>Status HR</th>';
					$table .= '<th class="hidden">Craetion Comment</th>';
					$table .= '<th class="hidden">Head Comment</th>';
					$table .= '<th class="hidden">HR Comment</th>';
					$table .= '<th class="hidden">Employee Comment</th>';

					$table .= '<th>Created At</th></thead><tbody></tr>';
					foreach ($chk_task as $key => $value) {
						$table .= '<tr><td>' . $value['employee_id'] . '</td>';
						$table .= '<td>' . $value['employee_name'] . '</td>';
						$table .= '<td>' . $value['location'] . '</td>';
						$table .= '<td>' . $value['issued_date'] . '</td>';
						$table .= '<td>' . $value['position'] . '</td>';
						$table .= '<td>' . $value['department'] . '</td>';
						$table .= '<td>' . $value['supervisor_name'] . '</td>';
						$table .= '<td>' . $value['ijp'] . '</td>';
						$table .= '<td>' . $value['incentive'] . '</td>';
						$table .= '<td>' . $value['pli'] . '</td>';
						$table .= '<td>' . $value['statusHead'] . '</td>';
						$table .= '<td>' . $value['statusHr'] . '</td>';
						$table .= '<td class="hidden">' . $value['description_of_issue'] . '</td>';
						$table .= '<td class="hidden">' . $value['head_comment'] . '</td>';
						$table .= '<td class="hidden">' . $value['HR_Comment'] . '</td>';
						$table .= '<td class="hidden">' . $value['emp_comment'] . '</td>';
						$table .= '<td>' . $value['created_at'] . '</td></tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Record found. " . $my_error . "'); }); </script>";
				}
			}
			?>
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
		$('#DateFrom').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$('#DateTo').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		//Model Assigned and initiation code on document load
		$('.modal').modal({
			onOpenStart: function(elm) {

			},
			onCloseEnd: function(elm) {
				$('#btn_Can').trigger("click");
			}
		});

		// This code for cancel button trigger click and also for model close
		$('#btn_Can').on('click', function() {
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
		$('#btn_view').on('click', function() {
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

	});
</script>