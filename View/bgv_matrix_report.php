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
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">BGV Matrix Report</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> BGV Matrix Report</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <script>
                    $(function() {
                        $('#myTable').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [25, 50, -1],
                                ['10 rows', '25 rows', '50 rows', 'Show all']
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
                            "sScrollY": '500px',
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
                $conn = $myDB->dbConnect();
                $query = "select b.*,concat( c.client_name,' | ',cm.process,' | ',cm.sub_process) as Client from bgv_matrix as b join new_client_master as cm on cm.cm_id=b.cm_id join client_master as c on c.client_id=cm.client_name where cm.cm_id not in (select cm_id from client_status_master) order by c.client_name";

                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->num_rows;

                if ($result->num_rows > 0) {
                    $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                    $table .= '<th>Process</th>';
                    $table .= '<th>cm_id</th>';
                    $table .= '<th>Designation</th>';
                    $table .= '<th>Address BGV</th>';
                    $table .= '<th>Education BGV</th>';
                    $table .= '<th>Employeement BGV</th>';
                    $table .= '<th>Criminal BGV</th><thead><tbody>';

                    foreach ($result as $key => $value) {
                        $table .= '<tr><td>' . $value['Client'] . '</td>';
                        $table .= '<td>' . $value['cm_id'] . '</td>';
                        $table .= '<td>' . $value['desig'] . '</td>';
                        $table .= '<td>' . $value['Addr'] . '</td>';
                        $table .= '<td>' . $value['Edu'] . '</td>';
                        $table .= '<td>' . $value['Emp'] . '</td>';
                        $table .= '<td>' . $value['Crim'] . '</td></tr>';
                    }
                    $table .= '</tbody></table></div></div>';
                    echo $table;
                } else {
                    echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
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