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

// $hidvalues=$_POST['hid'];
// if ($_SESSION['__user_logid'] == 'CE03070003' || $_SESSION['__user_logid'] == 'CE12102224') {
// 	// proceed further
// } else {
// 	$location = URL . 'error';
// 	echo '<script language="javascript">window.location.href ="' . $location . '"</script>';
// 	exit();
// }

$myDB = new MysqliDb();
$connn = $myDB->dbConnect();

if (isset($_POST['submit'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_loc = implode(" ", $_POST['location']);
		$clean_loc = cleanUserInput($clean_loc);
		$clean_loc = explode(" ", $clean_loc);
		if (isset($clean_loc)) {

			$location = implode(',', $_POST['location']);
			$location = cleanUserInput($location);
		} else {
			$location = "";
		}
		$created_at = date('Y-m-d h:i:s');
		$clean_Emp_Name = cleanUserInput($_POST['ddl_ED_Emp_Name']);
		$employee_id = $clean_Emp_Name;
		if (isset($clean_Emp_Name)) {
			$sql =  "select EmpID from salary_master where EmpID=?";
			$selectQury = $connn->prepare($sql);
			$selectQury->bind_param("s", $employee_id);
			$selectQury->execute();
			$res = $selectQury->get_result();

			if ($res->num_rows > 0) {
				$SQL = "UPDATE salary_master SET location = ?, created_at=? WHERE EmpID =?";
				$update = $connn->prepare($SQL);
				$update->bind_param("sss", $location, $created_at, $employee_id);
				$update->execute();
				$results = $update->get_result();
				echo "<script>$(function(){ toastr.success('Data Updated Successfully.') }); </script>";
			} else {
				$sqlConnect =  "insert into salary_master (EmpID,location, created_at)values(?,?,?);";
				$selectQu = $connn->prepare($sqlConnect);
				$selectQu->bind_param("sss", $employee_id, $location, $created_at);
				$selectQu->execute();
				$result = $selectQu->get_result();
				if ($selectQu->affected_rows === 1) {
					echo "<script>$(function(){ toastr.success('Data Inserted Successfully.') }); </script>";
				}
			}
		}
	}
}

if (isset($_POST['update'])) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$clean_loc = implode(" ", $_POST['location']);
		$clean_loc = cleanUserInput($clean_loc);
		$clean_loc = explode(" ", $clean_loc);
		if (isset($clean_loc)) {

			$location = implode(',', $_POST['location']);
			$location = cleanUserInput($location);
		} else {
			$location = "";
		}
		$created_at = date('Y-m-d h:i:s');
		$clean_Emp_Name = cleanUserInput($_POST['ddl_ED_Emp_Name']);
		$employee_id = $clean_Emp_Name;

		$SQL = "UPDATE salary_master SET location = ?, created_at=? WHERE EmpID =?";
		$stmt = $connn->prepare($SQL);
		$stmt->bind_param("sss", $location, $created_at, $employee_id);
		$updt = $stmt->execute();

		if ($stmt->affected_rows === 1) {
			echo "<script>$(function(){ toastr.success('Data Updated Successfully.') }); </script>";
		}
	}
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Salary Master</span>

	<!-- Main Div for all Page -->
	<div class="pim-container row" id="div_main">

		<!-- Sub Main Div for all Page -->
		<div class="form-div">

			<!-- Header for Form If any -->
			<h4> Salary Master</h4>

			<!-- Form container if any -->
			<div class="schema-form-section row">
				<?php

				$_SESSION["token"] = csrfToken();
				?>
				<input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

				<div class="form-div">
					<input type="hidden" id="hid" name="hid">

					<div class=" byID">
						<div class="input-field col s6 m6 6">
							<!-- <span>Employee ID</span> -->
							<input type="text" id="ddl_ED_Emp_Name" name="ddl_ED_Emp_Name" title="Enter Employee ID Must Start With CE and Not Less Then 10 Char" value="<?php echo $employee_id; ?>">
							<label id="labelEmpid" for="ddl_ED_Emp_Name"> Employee ID</label>

						</div>
					</div>
					<div class="col s12 m12 no-padding">
						<div class="input-field col s12 m12 no-padding" style="margin: 09px;margin-bottom: 20px;">
							<p><b>Location</b></p>
						</div>
						<?php
						// $employee_id=$clean_Emp_Name;
						$sqlQuery = 'SELECT * FROM location_master';
						$sel = $connn->prepare($sqlQuery);
						$sel->bind_param("s", $employee_id);
						$sel->execute();
						$resultQuery = $sel->get_result();
						$i = 1;
						// print_r($resultQuery);
						//  exit;
						//     for($i=0;$i<=6; $i++ ){
						// 	$newarray.=  $resultQuery[$i]['id'];
						//     $newarray.=',';
						// }
						// print_r ($newarray);
						// exit;  
						// $newarray2 = explode(',', $newarray);  

						if ($resultQuery) {
							foreach ($resultQuery as $key => $value) {
						?><div class="col s2 m2">
									<input type='checkbox' class="locat" name="location[]" id="locationID<?php echo $value['id']; ?>" value='<?php echo $value['id']; ?>'>
									<label for="locationID<?php echo $value['id']; ?>"><?php echo $value['location']; ?></label>
								</div>
						<?php $i++;
							}
						}  ?>

					</div>

					<div class="input-field col s12 m12 right-align">
						<button type="submit" name="submit" id="submit" class="btn waves-effect waves-green">Submit</button>
					</div>
					<div class="input-field col s12 m12 right-align">
						<button type="submit" name="update" id="update" class="btn waves-effect waves-green">Update</button>
					</div>
				</div>

			</div>
			<!--Form container End -->

			<?php
			$fetch = "select  X.id,X.EmpID, X.location as loc_id,Y.location from salary_master X join (SELECT ed.id, GROUP_CONCAT(b.location ORDER BY b.id) AS location FROM salary_master ed INNER JOIN location_master b ON FIND_IN_SET(b.id , ed.location) > 0 GROUP BY ed.id)Y on X.id =Y.id
        ";
			$myDB = new MysqliDb();
			$result = $myDB->Query($fetch);
			$my_error = $myDB->getLastError();
			if (empty($my_error)) {
				$table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                <div class=""><table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%"><thead><tr>';
				$table .= '<th>EmployeeID</th>';
				$table .= '<th class="hidden">ID</th>';
				$table .= '<th class="hidden">LocationID</th>';
				$table .= '<th>Location</th>';
				$table .= '<th>Action</th><thead><tbody>';

				foreach ($result as $key => $value) {
					$table .= '<tr id="Hid' . $value['id'] . '"><td class="ddl_ED_Emp_Name">' . $value['EmpID'] . '</td>';
					$table .= '<td class="hiddenIDs hidden">' . $value['id'] . '</td>';
					$table .= '<td class="location hidden">' . $value['loc_id'] . '</td>';
					$table .= '<td class="locationID">' . $value['location'] . '</td>';
					$table .= '<td class="manage_item" >
                    <i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="' . $value['id'] . '"   data-position="left" data-tooltip="Edit">ohrm_edit</i>
                   <i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id="' . $value['id'] . '"  onclick="javascirpt:return ApplicationDataDelete(this);" data-item="' . $value['id'] . '" data-position="left" data-tooltip="Delete">ohrm_delete</i></td>';
				}
				$table .= '</tbody></table></div></div>';
				echo $table;
			} else {
				echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
			}


			?>
		</div>
		<!--Main Div for all Page End -->
	</div>
	<!--Content Div for all Page End -->
</div>



<script>
	$(function() {
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
			"sScrollY": "192",
			"sScrollX": "100%",
			"bScrollCollapse": true,
			"bLengthChange": false,
			"fnDrawCallback": function() {

				$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
			}
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


	$(document).ready(function() {
		//$(".locat").prop( "checked", false );

		$('#submit,#update').on('click', function() {
			var validate = 0;
			var alert_msg = '';
			if ($('#ddl_ED_Emp_Name').val() == '') {
				$('#ddl_ED_Emp_Name').addClass('has-error');
				if ($('#spantxt_dateFrom').length == 0) {
					$('<span id="spantxt_dateFrom" class="help-block"></span>').insertAfter('#ddl_ED_Emp_Name');
				}
				$('#spantxt_dateFrom').html('Required');
				validate = 1;
			}


			if ($('input[type=checkbox]:checked').length == 0) {
				alert('Please select atleast one checkbox');
			}

			// if ($('#locationID').val() == '') {
			// 		$('#locationID').addClass('has-error');
			// 		if ($('#spantxt_dateTo').length == 0) {
			// 			$('<span id="spantxt_dateTo" class="help-block"></span>').insertAfter('#locationID');
			// 		}
			// 		$('#spantxt_dateTo').html('Required');
			// 		validate = 1;

			// 	}


			if (validate == 1) {
				$('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
				$('#alert_message').show().attr("class", "SlideInRight animated");
				$('#alert_message').delay(5000).fadeOut("slow");
				return false;
			}
		});

	});

	// function ApplicationDataDelete(el) {

	//     if (confirm('Are You Sure Want To Delete?? ')) {
	//         $item = $(el);
	//         // alert($item);
	//         $.ajax({
	//             url: "../Controller/delete_sal.php?id=" + $(el).attr('data-item'),
	//             success: function(result) {

	//                 var data = result.split('|');

	//                 // alert(data[0]);
	//                 // alert(data[1]);
	//                 $("#Hid" + $(el).attr('data-item')).remove();

	//                 toastr.success(data[0]);
	//             }
	//         });
	//     }
	// }



	function ApplicationDataDelete(el) {
		//var currentUrl = window.location.href;
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
					window.location.replace("../View/salary_master.php");
				}
			}
			xmlhttp.open("GET", "../Controller/delete_sal.php?id=" + el.id, true);
			xmlhttp.send();
			alert("Data Deleted Successfully");
		}
	}
</script>
<script>
	$('#submit').show();
	$('#update').hide();

	function EditData(el) {
		$('#labelEmpid').addClass('Active');
		$(".locat").prop("checked", false);
		var tr = $(el).closest('tr');
		var Emp_id = tr.find('.ddl_ED_Emp_Name').text();
		var loc = tr.find('.location').text();
		//   alert(loc)
		var locID = loc.split(",");
		var hidValue = tr.find('.hiddenIDs').text();
		var locLength = locID.length;

		// alert(locID.length);

		for (var j = 0; j < locLength; j++) {
			// alert(locID[j]);
			$('#locationID' + locID[j]).prop('checked', true);
		}




		$('#ddl_ED_Emp_Name').val(Emp_id);
		$('#hid').val(hidValue);

		// alert($('#location1').val(loc));
		//$("#location2").prop( "checked", true );
		// $('#tblid').val(id);
		$('#update').show();
		$('#submit').hide();
	}
</script>


<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>