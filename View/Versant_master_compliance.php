<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224' || $_SESSION['__user_logid'] == 'CE01145570') {
	// proceed further
} else {
	$location = URL;
	echo "<script>location.href='" . $location . "'</script>";
}
$ceatedBy = $_SESSION['__user_logid'];
$date = date('Y-m-d H-i-s');
//print_r($_SESSION);
$clientID = $module_name = $searchBy = $id = '';
$classvarr = "'.byID'";

?>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,
			scrollX: '100%',
			scrollCollapse: true,
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

			]
			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-excel').attr('id', 'buttons_excel');
		$('.buttons-page-length').attr('id', 'buttons_page_length');
		$('.byID').addClass('hidden');

		var classvarr = <?php echo $classvarr; ?>;
		$(classvarr).removeClass('hidden');

	});
</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Test Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Test Master </h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">


				<?php
				//$sqlConnect = "SELECT a.ID, a.cm_id, a.cert_name, a.filename,b.process,b.sub_process from  certification_require_by_cmid  a Inner Join new_client_master b where a.cm_id=b.cm_id "; 
				$sqlConnect = "SELECT a.ID, a.cm_id, a.cert_name, a.filename,b.process,b.sub_process,c.client_name,d.location,d.id from  certification_require_by_cmid  a Inner Join new_client_master b inner join client_master c on b.client_name=c.client_id join location_master d on b.location=d.id where a.cm_id=b.cm_id";
				$myDB = new MysqliDb();
				$result = $myDB->query($sqlConnect);
				//print_r($result);
				$error = $myDB->getLastError();
				if ($result) { ?>
					<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th> Srl.No.</th>
								<th>Test </th>
								<th> Client </th>
								<th> Process </th>
								<th> Sub-Process </th>
								<th> Filename </th>
								<th> Location </th>

								<th class="hidden"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach ($result as $key => $val) {

								$ID = $val['ID'];
								echo '<tr>';
								echo '<td class="module_id" >' . $i . '</td>';
								echo '<td class="cert_name">' . $val['cert_name'] . '</td>';
								echo '<td class="cm_id">' . $val['client_name'] . '</td>';
								echo '<td class="cm_id">' . $val['process'] . '</td>';
								echo '<td class="cm_id">' . $val['sub_process'] . '</td>';
								echo '<td class="filename">' . $val['filename'] . '</td>';
								echo '<td class="location">' . $val['location'] . '</td>';
							?>



							<?php
								echo '<td class="hidden">' . $val['id'] . '</td>';
								echo '</tr>';
								$i++;
							}
							?>
						</tbody>
					</table>
				<?php } else {
					echo "<script>$(function(){ toastr.error('No Data Found " . $error . "'); }); </script>";
				}

				?>

			</div>


		</div>
	</div>



</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
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

		$('#ContentAdd').click(function() {
			$('#medit').show();
			$('#cert_name').val('');
			$('#cm_id').val('');
			$('#filename').val('');
			$('select').formSelect();
			$('#id').val('');
			$('#savemodule').hide();
			$('#addmodule').show();
		});
		// This code for cancel button trigger click and also for model close
		$('#cancelID').on('click', function() {
			$('#module_name').val('');
			$('#myModal_content').modal('close');
			//$('#medit').hide();
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

		$('#savemodule ,#addmodule').on('click', function() {
			var validate = 0;
			// <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
			$("input,select,textarea").each(function() {
				var spanID = "span" + $(this).attr('id');
				$(this).removeClass('has-error');
				if ($(this).is('select')) {
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
				var attr_req = $(this).attr('required');
				if (($(this).val().trim() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown')) {
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
				return false;
			}
		});
		$('#medit').hide();

		$('#txt_location').change(function() {
			//alert('kavya');
			var tval = $(this).val();
			getProcess(tval);
			//alert(tval);
			//alert(<?php echo '"' . URL . '"'; ?>+"Controller/getProcessNameByLocation.php?id="+tval);

		});

	});

	function getProcess(val) {
		//alert(val);
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + val
		}).done(function(data) { // data what is sent back by the php page
			$('#cm_id').html(data);
			$('select').formSelect();
		});
	}

	function getProcess1(val, id) {
		//alert(val);
		$.ajax({
			url: <?php echo '"' . URL . '"'; ?> + "Controller/getProcessNameByLocation.php?id=" + val
		}).done(function(data) { // data what is sent back by the php page
			$('#cm_id').html(data);
			$('#cm_id').val(id);
			$('select').formSelect();
		});
	}

	function editData(id, cert_name, cm_id, filename, locid) {

		$('#medit').show();
		$('#cert_name').val(cert_name);

		$('#txt_location').val(locid);
		$('#filename').val(filename);
		$('select').formSelect();
		$('#id').val(id);
		$('#savemodule').show();
		$('#addmodule').hide();
		//alert(id); alert(locid);
		//$('#cm_id').empty().append('<option selected="selected" value="">Select Process</option>');
		getProcess1($('#txt_location').val(), cm_id);
		$('#cm_id').val(cm_id);
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
</script>
<script>
	function Check(e) {

		var keyCode = (e.keyCode ? e.keyCode : e.which);
		if (keyCode > 47 && keyCode < 58) {
			e.preventDefault();
		}
	}
</script>