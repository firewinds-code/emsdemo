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
ini_set('display_errors', '1');
ini_set('log_errors', 'On');
ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL);
$myDB = new MysqliDb();
$classvarr = "'.By Aadhar'";

$emid = "";
if ($_SESSION['__user_type'] == 'ADMINISTRATOR' || $_SESSION['__user_logid'] == 'CE12102224') {
    // proceed further
} else {
    $location = URL;
    echo "<script>location.href='" . $location . "'</script>";
}
if (isset($_POST['btn_AadharNo'])) {
    $aadharno = (isset($_POST['aadharno']) ? $_POST['aadharno'] : null);
    $_SESSION['aadharno'] = $aadharno;
}

$empid = $_SESSION['EmployeeID_s'];

if (isset($_POST['btn_block'])) {
    $aadharno = $_POST['aadharno'];
    $_SESSION['aadharno'] = '';
    unset($_SESSION['aadharno']);
    $insert_EmpBlock = "insert into emp_block(EmployeeID,aadharNo,created_by)values('" . $empid . "','" . $aadharno . "','" . $_SESSION['__user_logid'] . "')";
    $insResult = $myDB->query($insert_EmpBlock);
    $mysql_error = $myDB->getLastError();
    if (empty($mysql_error)) {
        echo "<script>$(function(){toastr.success('Employee Blocked') });</script>";
        echo  "<script> $('#btn_block').hide(); </script>";
    } else {
        echo "<script>$(function(){toastr.error('Already Blocked') });</script>";
        echo  "<script> $('#btn_block').hide(); </script>";
    }
}

?>
<script>
    $(document).ready(function() {

        $('#myTable').DataTable({
            "bPaginate": false,
            "bInfo": false,
            dom: 'Bfrtip',
            scrollX: '100%',
            // "iDisplayLength": 25,
            scrollCollapse: true,
            // lengthMenu: [
            //     [5, 10, 25, 50, -1],
            //     ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
            // ],
            // buttons: [
            //     /*  
            //     {
            //         extend: 'csv',
            //         text: 'CSV',
            //         extension: '.csv',
            //         exportOptions: {
            //             modifier: {
            //                 page: 'all'
            //             }
            //         },
            //         title: 'table'
            //     }, 						         
            //     'print',*/
            //     // {
            //     //     extend: 'excel',
            //     //     text: 'EXCEL',
            //     //     extension: '.xlsx',
            //     //     exportOptions: {
            //     //         modifier: {
            //     //             page: 'all'
            //     //         }
            //     //     },
            //     //     title: 'table'
            //     // }
            //     /*,'copy'*/
            //     // , 'pageLength'

            // ]
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

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Employee Block</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>Employee Search</h4>
            <div class="schema-form-section row">
                <div class="">
                    <!-- <div class="input-field col s6 m6 8">
                        <select name="searchBy" id="searchBy" class="input-field col s12 m12 l6" title="Select Search Option">
                            <option <?php if ($searchBy == 'By Aadhar') {
                                        echo 'selected';
                                    } ?> value="By Aadhar">Employee Aadhar</option>
                        </select>
                        <label title="" for="searchBy" class="active-drop-down active"></label>
                    </div> -->
                </div>
                <!-- <input type="hidden" name="aadharno1" id="aadharno1" /> -->
                <div class="By Aadhar" id="adharDiv">
                    <!--<label for="ddl_ED_Emp_Mobile">Emp. Mobile :</label>-->
                    <div class="input-field col s5 m5">

                        <input type="text" id="aadharno" name="aadharno" title="Enter Aadhar Number Must Not Less Then 12 Number" maxlength="12" value="<?php echo $_SESSION['aadharno'] ?>">
                        <label for="aadharno"> Employee Aadhar No</label>
                    </div>
                </div>

                <!-- <input type="hidden" id="hidEmpID" name="hidEmpID" value="<?php echo $_POST['EmployeeID'] ?>"> -->

                <div class="input-field col s12 m12 right-align">
                    <button type="submit" name="btn_AadharNo" title="Click Here To Get Search Result" id="btn_AadharNo" class="btn waves-effect waves-green">Search</button>
                </div>



                <div id="pnlTable">
                    <?php

                    if (isset($_POST['btn_AadharNo'])) {
                        $aadhar = $_POST['aadharno'];

                        // echo "<script> $('#btn_AadharNo').hide(); </script>";

                        $sqlConnect = 'call getEmp_block_byAadhar("' . $aadhar . '")';
                        $result = $myDB->rawQuery($sqlConnect);
                        // $emid .= $result[0]['EmployeeID'];
                        $mysql_error = $myDB->getLastError();
                        if (count($result) > 0) {

                            $selectQry = "select aadharNo from emp_block where aadharNo='" . $aadhar . "'";
                            $myDB = new MysqliDb();

                            $res = $myDB->query($selectQry);

                            if ($myDB->count > 0) {
                                echo  "<script>$(function(){toastr.error('Employee Already Blocked') });</script>";
                                echo  "<script> $('#btn_block').hide(); </script>";
                            } else {
                                // echo "<script> $('#btn_block').show(); </script>";
                                // echo  "<script> $('#btn_AadharNo').hide(); </script>";
                    ?>
                                <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                                    <div class="">
                                        <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th> Employee ID </th>
                                                    <th> Employee Name </th>
                                                    <th> Client </th>
                                                    <th> Process </th>
                                                    <th> Sub Process </th>
                                                    <th> Date Of Leaving </th>
                                                    <th> Reason Of Leaving </th>
                                                    <th> Disposition </th>
                                                    <!-- <th> Action </th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($result as $key => $value) {
                                                    $_SESSION['EmployeeID_s'] = $value['EmployeeID'];
                                                    echo '<tr>';
                                                    echo '<td class="EmployeeID">' . $value['EmployeeID'] . '</td>';
                                                    echo '<td class="EmployeeName">' . $value['EmployeeName'] . '</td>';
                                                    echo '<td class="client_name">' . $value['client_name'] . '</td>';
                                                    echo '<td class="process">' . $value['process'] . '</td>';
                                                    echo '<td class="subprocess">' . $value['subprocess'] . '</td>';;
                                                    echo '<td class="dol">' . date('Y-m-d', strtotime($value['dol'])) . '</td>';
                                                    echo '<td class="rsnofleaving">' . $value['rsnofleaving'] . '</td>';
                                                    echo '<td class="disposition">' . $value['disposition'] . '</td>';
                                                    // echo '<td>
                                                    //     <button type="submit" name="btn_block" id="btn_block"  class="badge badge-danger" title="Click to block the Employee">Block</button>
                                                    // </td>';
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <?php if (isset($_POST['btn_AadharNo'])) { ?>
                                            <div class="input-field col s12 m12 right-align">
                                                <button type="submit" name="btn_block" id="btn_block" class="btn btn-danger" title="Click to block the Employee">Block</button>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>

                    <?php
                            }
                            // echo  "<script> $('#btn_block').hide(); </script>";
                        } else {
                            echo  "<script> $('#btn_block').hide(); </script>";
                            echo "<script>$(function(){ toastr.info('No Records Found " . $error . "'); }); </script>";
                            // echo  "<script> $('#btn_block').hide(); </script>";
                        }
                    } ?>
                </div>





            </div>
        </div>
    </div>
</div>

<script>
    // $('#btn_block').hide();

    // $('#btn_AadharNo').click(function() {
    //     if ($('#btn_AadharNo').val() == 1) {
    //         $('#btn_block').show();
    //     } else {
    //         $('#btn_block').hide();
    //     }

    // });

    $(document).ready(function() {
        // var value = $('.EmployeeID').text();
        // if (value !== '') {
        //     var aadharno = $('#aadharno').val();
        //     $('#aadharno1').val(aadharno);
        //     $('#aadharno').val('');
        //     $('#adharDiv').hide();
        // }

        // $('#btn_block').click(function() {
        //     $('#aadharno').val('');
        //     $('#aadharno').val('').removeAttr('#btn_block');
        // });

        $('#alert_msg_close').click(function() {
            $('#alert_message').hide();
        });
        if ($('#alert_msg').text() == '') {
            $('#alert_message').hide();
        }

        $('#btn_AadharNo').click(function() {
            var validate = 0;
            var alert_msg = '';

            $('#aadharno').removeClass('has-error');


            if ($('#aadharno').val().length < 12) {
                $('#aadharno').addClass('has-error');
                if ($('#spanaadharno').size() == 0) {
                    $('<span id="spanaadharno" class="help-block"></span>').insertAfter('#aadharno');
                }
                $('#spanaadharno').html('Employee Aadhar Number atleast contains 12 digits');
                validate = 1;
            }


            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(5000).fadeOut("slow");
                return false;
            }
        });
        // $('#btn_AadharNo').hide();

    });
</script>


<script>
    $(document).ready(function() {

        $('#aadharno').keydown(function(event) {
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||

                // Allow: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||

                // Allow: Ctrl+V
                (event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

                // Allow: Ctrl+c
                (event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

                // Allow: Ctrl+x
                (event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

                // Allow: home, end, left, right
                (event.keyCode >= 35 && event.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            } else {
                // Ensure that it is a number and stop the keypress
                if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                    event.preventDefault();
                }
            }
        });

    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>