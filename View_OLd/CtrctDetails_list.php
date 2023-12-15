<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
/*if($_SESSION["__user_type"]!='HR'){
	$location=  URL.'Login'; 
	echo "<script>location.href='".$location."'</script>";
	exit();
}*/
$ack = $vh_ack = $oh_ack = '';
if ((clean($_SESSION['__status_ah']) != 'No' && clean($_SESSION['__status_ah']) == clean($_SESSION['__user_logid']) && clean($_SESSION['__status_ah']) != '') || (clean($_SESSION["__status_vh"]) == clean($_SESSION['__user_logid'])) || clean($_SESSION["__user_logid"]) == 'CE091930141') {
	$myDB = new MysqliDb();
	$sqlcmid = "call getCmidUsingAhVhId('" . clean($_SESSION['__user_logid']) . "')";
	$resultcm = $myDB->query($sqlcmid);
	$cmid_string = '';
	foreach ($resultcm as $val) {
		if ($val['cm_id'] != "") {
			$cmid_string .= $val['cm_id'] . ',';
		}
	}
} else {
	$location =  URL . 'Login';
	//echo "<script>location.href='".$location."'</script>";
	echo clean($_SESSION["__user_logid"]);
	exit();
}
if ($cmid_string != "") {
	$cmid_string = substr($cmid_string, 0, -1);
}
$disable = "readonly='true'";
$EmployeeID = $btnShow = '';
$usr_id = clean($_SESSION['__user_logid']);
if (isset($usr_id) && $usr_id != '') {
	$createBy = clean($_SESSION['__user_logid']);
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
			}, 'pageLength'],
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

			// buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
		});

		$('.buttons-excel').attr('id', 'buttons_excel');;
		$('.buttons-page-length').attr('id', 'buttons_page_length');

	});
</script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Contract Details</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4>Contract Details</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<div class="container col s12 m12" id="childtables">

					<div id="eduFormid" class="hidden">

					</div>
					<div id="pnlTable">
						<?php
						$user_logID = clean($_SESSION['__user_logid']);
						$sqlConnectTemp = "select  a.id, a.table_name, a.revision, a.cm_id, cast(a.dreatedOn as date) as dreatedOn, a.ack_flg,a.vh_ack,a.oh_ack, cast(a.ack_date as date) as ack_date,b.Client_Info from ctrctdetails_master a inner join (SELECT cm_id,concat(client_master.client_name,' | ',process,' | ',sub_process) as Client_Info FROM new_client_master b inner join client_master on client_master.client_id = b.client_name ";
						if ($user_logID != 'CE10091236' && $user_logID != 'CE091930141') {
							//
							$sqlConnectTemp .= " where cm_id in(" . $cmid_string . ") ";
						}
						$sqlConnectTemp .= " ) b on a.cm_id=b.cm_id  group by a.table_name order by a.id desc";
						// echo $sqlConnectTemp;
						$myDB = new MysqliDb();
						$resultTemp = $myDB->query($sqlConnectTemp);
						$mysql_error = $myDB->getLastError();
						if (empty($mysql_error)) { ?>
							<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
								<div style="overflow: auto;">
									<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Contatct ID/Revision</th>
												<th class="hidden ">cmid</th>
												<th>Sub-Process</th>
												<th>Created On</th>
												<th>AH Ack.?</th>
												<th>OH Ack.?</th>
												<th>VH Ack.?</th>
												<th>Ack. Date</th>
												<th>Action </th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (count($resultTemp) > 0) {
												foreach ($resultTemp as $key => $value) {
													if ($value['ack_flg'] == 1) {
														$ack = 'Yes';
													} else {
														$ack = 'No';
													}
													if ($value['vh_ack'] == 1) {
														$vh_ack = 'Yes';
													} else {
														$vh_ack = 'No';
													}
													if ($value['oh_ack'] == 1) {
														$oh_ack = 'Yes';
													} else {
														$oh_ack = 'No';
													}
													echo '<tr>';
													echo '<td class="table_name hidden">' . $value['table_name'] . '</td>';
													echo '<td class="revision">' . $value['table_name'] . ' / ' . $value['revision'] . '</td>';
													echo '<td class="cmid hidden">' . $value['cm_id'] . '</td>';
													echo '<td class="cm_id">' . $value['Client_Info'] . '</td>';
													echo '<td class="dreatedOn">' . $value['dreatedOn'] . '</td>';
													echo '<td class="ack">' . $ack . '</td>';
													echo '<td class="ack">' . $oh_ack . '</td>';
													echo '<td class="ack">' . $vh_ack . '</td>';
													echo '<td class="ackdate">' . $value['ack_date'] . '</td>';
													echo '<td class="manage_item">';
													echo '<a onclick="javascript:return Edit(this);" data="' . $value['id'] . '"   data-position="left" data-tooltip="Edit" >View</a>';
													echo '</td>';
													echo '</tr>';
												}
											}


											?>
										</tbody>
									</table>

								</div>
							</div>
						<?php
						}
						?>

					</div>
				</div>

			</div>
			<!--Form container End -->
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>

<script>
	$(document).ready(function() {
		/*$('#btn_ack').click(function(){
			alert('rrr');
		});*/
		$('#txt_eduName').val('NA');
		$('input[type="text"]').click(function() {
			$(this).removeClass('has-error');
		});
		$('select').click(function() {
			$(this).removeClass('has-error');
		});
		$('#btn_education_Save,#btn_education_Add').on('click', function() {

			var validate = 0;
			var alert_msg = '';

		});
	});

	function Edit(el) {
		var id = $(el).attr('data');
		$("#eduFormid").removeClass('hidden');
		var tr = $(el).closest('tr');
		var table_name = tr.find('.table_name').text();
		var cmid = tr.find('.cmid').text();

		// alert(table_name);   
		var datadiv = '';
		$.ajax({
			url: "../Controller/getContractDetail.php?tablename=" + table_name + "&cmid=" + cmid + "&id=" + id,
			async: false,
			success: function(data) {
				var j = 1;
				/*	$dataarray=JSON.parse(data);
			        $.each($dataarray, function(d, v){
			         	 //alert(d);
			         	datadiv+='<div class="input-field col s6 m6"><input id="txt_edu_lvl_'+j+'" name="txt_edu_lvl_1[]"  value="'+v+'" ><label for="txt_edu_lvl_'+j+'" class="active-drop-down active">'+d+'</label></div>';
				        //alert(d+"  "+datadiv);
				        j++;
				    });*/
				// alert(datadiv);
				$("#eduFormid").html(data);
			}
		});
	}

	function Download(el) {

		if ($(el).attr("data") != '') {
			function getImageDimensions(path, callback) {
				var img = new Image();
				img.onload = function() {
					callback({
						width: img.width,
						height: img.height,
						srcsrc: img.src
					});
				}
				img.src = path;
			}

			$.ajax({
				url: "../" + $(el).attr("data"),
				type: 'HEAD',
				error: function() {
					alert('No File Exist');
				},
				success: function() {
					imgcheck = function(filename) {
						return (filename).split('.').pop();
					}
					imgchecker = imgcheck("../" + $(el).attr("data"));

					if (imgchecker.match(/(jpg|jpeg|png|gif)$/i)) {
						getImageDimensions("../" + $(el).attr("data"), function(data) {
							var img = data;

							$('<img>', {
								src: "../" + $(el).attr("data")
							}).watermark({
								//text: 'ⓒ For Cogent E Services Ltd.',
								text: 'Cogent E Services Ltd.',
								//path:'../Style/images/cogent-logobkp.png',
								textWidth: 370,
								opacity: 1,
								textSize: (img.height / 15),
								nH: img.height,
								nW: img.width,
								textColor: "rgb(0,0,0,0.4)",
								outputType: 'jpeg',
								gravity: 'sw',
								done: function(imgURL) {
									var link = document.createElement('a');
									link.href = imgURL;
									link.download = $(el).attr("data");
									document.body.appendChild(link);
									link.click();

								}
							});




						});
					} else if (imgchecker.match(/(pdf)$/i)) {
						window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src=" + "../../" + $(el).attr("data"));
					} else {
						window.open("../" + $(el).attr("data"));
					}

				}
			});

			/*$('.schema-form-section img').watermark({
					    
				  	});*/

		} else {
			alert('No File Exist');
		}
	}
	var windowObjectReference;

	function openRequestedPopup() {
		$path = $('#fileselect').val();
		windowObjectReference = window.open(
			"../ContractTemp/" + $path,
			"DescriptiveWindowName", "resizable,scrollbars,status,width=420,height=500"
		);
	}

	function ack1(id) {
		var tblname = $('#btn_ack').attr('data');
		if ($('#ack').prop("checked") == true) {
			$.ajax({
				url: "../Controller/setContactFlag.php?tablename=" + tblname + "&id=" + id,
				async: false,
				success: function(data) {
					$('.ackdiv').html(data);
				}
			});

		} else {
			alert('please check the checked box first');
		}

	}
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>