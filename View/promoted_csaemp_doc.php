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
$myDB =  new MysqliDb();
$conn = $myDB->dbConnect();
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Promoted Employee Document</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Promoted Employee Document</h4>

            <!-- Form container if any -->
            <div class="input-field col s12 m12" id="rpt_container">
                <?php $date_From = cleanUserInput($_POST['txt_dateFrom']); ?>
                <div class="input-field col s4 m4">
                    <span>Select Date</span>
                    <input type="text" class="form-control" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From; ?>" />
                </div>

                <div class="input-field col s12 m12 right-align">
                    <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
                        <i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            <div class="schema-form-section row">
                <?php
                $sla_id = '';

                $select_emp = "select t1.EmployeeID,t8.location,t8.id,Designation,t5.EmployeeName,t6.process,t6.sub_process,t7.client_name from employee_map t1 join df_master t2 on t1.df_id=t2.df_id join designation_master t3 on t2.des_id=t3.ID join tbl_log_altaration t4 on t1.EmployeeID=t4.EmployeeID join personal_details t5 on t1.EmployeeID=t5.EmployeeID join new_client_master t6 on t1.cm_id=t6.cm_id join client_master t7 on t7.client_id=t6.client_name join location_master t8 on t8.id=t5.location left join promoted_csa_emp_doc t9 on t1.EmployeeID=t9.EmployeeID  where cast(t4.log_date as date)=? and `type`='Designation' and  t4.df_id in (74,77) and t3.ID in (2,6,25,28)  and t9.download_flag is null ;";
                $selectQ = $conn->prepare($select_emp);
                $selectQ->bind_param("s", $date_From);
                $selectQ->execute();
                $result = $selectQ->get_result();

                $btn_view = isset($_POST['btn_view']);
                if ($btn_view) {
                    if ($result->num_rows > 0) {
                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th style="font-size: 13px;">EmployeeID</th>';
                        $table .= '<th style="font-size: 13px;">Employee Name</th>';
                        $table .= '<th style="font-size: 13px;">Client</th>';
                        $table .= '<th style="font-size: 13px;">Process</th>';
                        $table .= '<th style="font-size: 13px;">Sub Process</th>';
                        $table .= '<th style="font-size: 13px;">Location</th>';
                        $table .= '<th style="font-size: 13px;">Designation</th>';
                        $table .= '<th style="font-size: 13px;">Document</th></tr><thead><tbody>';
                        $i = 1;
                        foreach ($result as $key => $value) {
                            $designation = $value['Designation'];
                            $employee = $value['EmployeeID'];
                            $location = $value['id'];

                            $jaf_file_attachments = array();

                            $doc = "select distinct doc_file from doc_details where EmployeeID=? and doc_stype='Aadhar Card'";
                            $selectQury = $conn->prepare($doc);
                            $selectQury->bind_param("s", $employee);
                            $selectQury->execute();
                            $resultss = $selectQury->get_result();

                            foreach ($resultss as $key => $aadhar_value) {
                                $doc_adhar = $aadhar_value['doc_file'];

                                if ($location == "1" || $location == "2") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Docs/AdharCard/" . $doc_adhar . "";
                                } else if ($location == "3") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Meerut/Docs/AdharCard/" . $doc_adhar . "";
                                } else if ($location == "4") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Bareilly/Docs/AdharCard/" . $doc_adhar . "";
                                } else if ($location == "5") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Vadodara/Docs/AdharCard/" . $doc_adhar . "";
                                } else if ($location == "6") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Manglore/Docs/AdharCard/" . $doc_adhar . "";
                                } else if ($location == "7") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Bangalore/Docs/AdharCard/" . $doc_adhar . "";
                                } else if ($location == "8") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Nashik/Docs/AdharCard/" . $doc_adhar . "";
                                } else if ($location == "9") {
                                    $aadhar_path = "https://ems.cogentlab.com/erpm/Anantapur/Docs/AdharCard/" . $doc_adhar . "";
                                }
                                $jaf_file_attachments[] = $aadhar_path;
                            }
                            // print_r($jaf_file_attachments);
                            // die;


                            $educations = "select * from education_details where EmployeeID=?";
                            $selQury = $conn->prepare($educations);
                            $selQury->bind_param("s", $employee);
                            $selQury->execute();
                            $edufiles = $selQury->get_result();
                            if ($edufiles->num_rows > 0) {
                                if ($location == "1" || $location == "2") {
                                    $Location = "";
                                } else if ($location == "3") {
                                    $Location = "Meerut/";
                                } else if ($location == "4") {
                                    $Location = "Bareilly/";
                                } else if ($location == "5") {
                                    $Location = "Vadodara/";
                                } else if ($location == "6") {
                                    $Location = "Manglore/";
                                } else if ($location == "7") {
                                    $Location = "Bangalore/";
                                } else if ($location == "8") {
                                    $Location = "Nashik/";
                                } else if ($location == "9") {
                                    $Location = "Anantapur/";
                                }

                                foreach ($edufiles as $key => $val) {
                                    if (file_exists(ROOT_PATH . $Location . 'Edu/' . $val['edu_file'])) {
                                        $edu_path =  'https://ems.cogentlab.com/erpm/' . $Location . 'Edu/' . $val['edu_file'];
                                    } else {
                                        $edu_path =  'https://ems.cogentlab.com/erpm/' . $Location . 'Education/' . $val['edu_file'];
                                    }
                                    // echo $edu_path;
                                    // die;
                                    $jaf_file_attachments[] = $edu_path;
                                }
                            }

                            $experience_emp = "select * from experince_details where EmployeeID=? and exp_type='Experience' and employer not like'%Cogent%' order by `to` desc limit 2";
                            $selQuy = $conn->prepare($experience_emp);
                            $selQuy->bind_param("s", $employee);
                            $selQuy->execute();
                            $experfile = $selQuy->get_result();
                            if ($experfile->num_rows > 0) {
                                if ($location == "1" || $location == "2") {
                                    $ofc_loc = 'Docs/';
                                } else if ($location == "3") {
                                    $ofc_loc = 'Meerut/Docs/';
                                } else if ($location == "4") {
                                    $ofc_loc = 'Bareilly/Docs/';
                                } else if ($location == "5") {
                                    $ofc_loc = 'Vadodara/Docs/';
                                } else if ($location == "6") {
                                    $ofc_loc = 'Manglore/Docs/';
                                } else if ($location == "7") {
                                    $ofc_loc = 'Bangalore/Docs/';
                                } else if ($location == "8") {
                                    $ofc_loc = 'Nashik/Docs/';
                                } else if ($location == "9") {
                                    $ofc_loc = 'Anantapur/Docs/';
                                }

                                foreach ($experfile as $key => $values) {
                                    $filename = $values['releiving_experience_doc'];
                                    $filename2 = $values['appointment_offerletter_doc'];
                                    $filename3 = $values['salaryslip_bankstatement_doc'];

                                    if (file_exists(ROOT_PATH . $ofc_loc . 'Experience/' . $filename)) {
                                        $exp_path1 =  'https://ems.cogentlab.com/erpm/' . $ofc_loc . 'Experience/' . $filename;
                                        $jaf_file_attachments[] = $exp_path1;
                                    }
                                    if (file_exists(ROOT_PATH . $ofc_loc . 'offerletter/' . $filename2)) {
                                        $exp_path2 =  'https://ems.cogentlab.com/erpm/' . $ofc_loc . 'offerletter/' . $filename2;
                                        $jaf_file_attachments[] = $exp_path2;
                                    }
                                    if (file_exists(ROOT_PATH . $ofc_loc . 'salaryslip/' . $filename3)) {
                                        $exp_path3 =  'https://ems.cogentlab.com/erpm/' . $ofc_loc . 'salaryslip/' . $filename3;
                                        $jaf_file_attachments[] = $exp_path3;
                                    }
                                }
                            }


                            $pathdir = "../promoted_emp_docs/promoted_docs/$employee/";
                            mkdir($pathdir);
                            // print_r($jaf_file_attachments);
                            foreach ($jaf_file_attachments as $key) {
                                $file_name = basename($key);
                                $files = "../promoted_emp_docs/promoted_docs/$employee/" . $file_name;
                                if (file_put_contents($files, file_get_contents($key))) {
                                    // echo "File downloaded successfully";
                                } else {
                                    // echo "File downloading failed.";
                                }
                            }
                            // die;
                            $pathdir = "../promoted_emp_docs/promoted_docs/$employee/";
                            // Enter the name to creating zipped directory
                            $pathzip = '../promoted_emp_docs/promoted_docs_zip/';

                            $zipcreated = "../promoted_emp_docs/promoted_docs_zip/$employee.zip";

                            // Create new zip class
                            $zip = new ZipArchive;

                            if ($zip->open($zipcreated, ZipArchive::CREATE) === TRUE) {

                                // Store the path into the variable
                                $dir = opendir($pathdir);
                                while ($file = readdir($dir)) {
                                    if (is_file($pathdir . $file)) {
                                        $zip->addFile($pathdir . $file, $file);
                                    }
                                }
                                // print_r($zip->filename);
                                // die;
                            }


                            if ($result && $result->num_rows > 0) {
                                $doczip = "../promoted_emp_docs/promoted_docs_zip/$employee.zip";
                                $table .= '<tr><td class="empid">' . $value['EmployeeID'] . '</td>';
                                $table .= '<td>' . $value['EmployeeName'] . '</td>';
                                $table .= '<td>' . $value['client_name'] . '</td>';
                                $table .= '<td>' . $value['process'] . '</td>';
                                $table .= '<td>' . $value['sub_process'] . '</td>';
                                $table .= '<td>' . $value['location'] . '</td>';
                                $table .= '<td>' . $value['Designation'] . '</td>';
                                $table .= '<td> <a href="' . $doczip . '" target="_blank" download class="docsdownload" onclick="javascript:return getData(this);" id="' . $value['EmployeeID'] . '" > <i class="fa fa-download" ></i>Download</a></td></tr>';
                                $i++;
                            }
                        }
                        $table .= '</tbody></table></div></div>';
                        echo $table;
                    }
                }
                ?>
            </div>
            <!--Form container End -->
        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->

    <form></form>

</div>
<script>
    function getData(el) {
        var tr = $(el).closest('tr');
        var empid = tr.find('.empid').text();
        alert
        // var url = "../Controller/promoted_csa.php?empid=" + empid;
        // window.location.href = url;
        // return false;
        $.ajax({
            url: "../Controller/promoted_csa.php?empid=" + empid,
        });

    }
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
        $('#btn_view').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#txt_dateFrom').val() == '') {
                $('#txt_dateFrom').addClass('has-error');
                if ($('#spantxt_dateFrom').length == 0) {
                    $('<span id="spantxt_dateFrom" class="help-block"></span>').insertAfter('#txt_dateFrom');
                }
                $('#spantxt_dateFrom').html('Required');
                validate = 1;
            }
            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(5000).fadeOut("slow");
                return false;
            }
        });


        $('#txt_dateFrom').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',
            maxDate: '0',
            scrollInput: false
        });
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: false,
            bInfo: true,
            buttons: [{
                extend: 'excel',
                text: 'EXCEL',
                extension: '.xlsx',
                exportOptions: {
                    modifier: {
                        page: 'all'
                    }
                },
                title: 'table',
            }],
            "bProcessing": true,
            "bDestroy": true,
            "bAutoWidth": true,
            "sScrollY": "300px",
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

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>