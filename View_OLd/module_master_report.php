<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require_once(LIB . 'PHPExcel/IOFactory.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
// Global variable used in Page Cycle
$value = $counEmployee = $countProcess = $countClient = $countSubproc = 0;

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
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}

function coordinates($x)
{
	return PHPExcel_Cell::stringFromColumnIndex($x);
}

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Module Master Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Module Master Report</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <script>
                $(function() {

                    $('#myTable').DataTable({
                        dom: 'Bfrtip',
                        scrollX: '100%',
                        "iDisplayLength": 25,
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
                        }, 'pageLength'],
                        "bProcessing": true,
                        "bDestroy": true,
                        "bAutoWidth": "50%",
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

                <?php
				$myDB = new MysqliDb();
				$chk_taskq = ("SELECT t1.EmployeeID,t4.EmployeeName,t2.location,concat(t3.process,'|',t3.sub_process) as Process,t1.level,t1.l1empid as L1,t1.l1name as L1Name,t1.l2empid as L2, t1.l2name as L2Name, case when flag=1 then 'Module Level' when flag=2 then 'Employee Level' end as Mapping from module_master_new t1 join location_master t2 on t1.loc_id=t2.id join new_client_master t3 on t1.cm_id=t3.cm_id join personal_details t4 on t1.EmployeeID=t4.EmployeeID ;");
				$stmt = $conn->prepare($chk_taskq);
				$stmt->execute();
				$chk_task = $stmt->get_result();
				$count = $chk_task->num_rows;
				$mysql_error = $myDB->getLastError();
				if ($chk_task->num_rows > 0) {
					// if (empty($my_error)) {
					$table = '<div class="panel panel-default col-sm-12" style="margin-top:10px;"><div class="panel-body"><table id="myTable" class="data dataTable no-footer row-border"><thead><tr>';
					$table .= '<th>Employee ID</th>';
					$table .= '<th>Employee Name</th>';
					$table .= '<th>Location</th>';
					$table .= '<th>Process</th>';
					$table .= '<th>Level</th>';
					$table .= '<th>L1 ID</th>';
					$table .= '<th>L1 Name</th>';
					$table .= '<th>L2 ID</th>';
					$table .= '<th>L2 Name</th>';
					$table .= '<th>Mapping</th></tr><thead><tbody>';

					foreach ($chk_task as $key => $value) {

						$table .= '<tr><td>' . $value['EmployeeID'] . '</td>';
						$table .= '<td>' . $value['EmployeeName'] . '</td>';
						$table .= '<td>' . $value['location'] . '</td>';
						$table .= '<td>' . $value['Process'] . '</td>';
						$table .= '<td>' . $value['level'] . '</td>';
						$table .= '<td>' . $value['L1'] . '</td>';
						$table .= '<td>' . $value['L1Name'] . '</td>';
						$table .= '<td>' . $value['L2'] . '</td>';
						$table .= '<td>' . $value['L2Name'] . '</td>';
						$table .= '<td>' . $value['Mapping'] . '</td></tr>';
					}
					$table .= '</tbody></table></div></div>';
					echo $table;
				} else {
					echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
				}




				?>
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