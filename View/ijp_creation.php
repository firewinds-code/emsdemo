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

$myDB = new MysqliDb();
$connn = $myDB->dbConnect();
// $empIDs = '';
// $tcount = '';
function calcAtnd($flag, $loc)
{
    // echo $flag . 'First';
    $myDB = new MysqliDb();

    if ($loc != 'ALL') {
        $sql = 'inner join EmpID_Name t2 on t1.EmployeeID=t2.EmpID where t2.loc=' . $loc . ' and ';
    } else {
        $sql = ' where ';
    }


    $calAtndQry =  $myDB->query("select EmployeeID,month,year,(case when D1='A' then 1 else 0 end+case when D2='P(BiometricIssue)' then 1 else 0 end+case when D3='A' then 1 else 0 end+case when D4='A' then 1 else 0 end+case when D5='A' then 1 else 0 end+case when D6='A' then 1 else 0 end+case when D7='A' then 1 else 0 end+case when D8='A' then 1 else 0 end+case when D9='A' then 1 else 0 end+case when D10='A' then 1 else 0 end+case when D11='A' then 1 else 0 end+case when D12='A' then 1 else 0 end+case when D13='A' then 1 else 0 end+case when D14='A' then 1 else 0 end+case when D14='A' then 1 else 0 end+case when D16='A' then 1 else 0 end+case when D17='A' then 1 else 0 end+case when D18='A' then 1 else 0 end+case when D19='A' then 1 else 0 end+case when D20='A' then 1 else 0 end+case when D21='A' then 1 else 0 end+case when D22='A' then 1 else 0 end+case when D23='A' then 1 else 0 end+case when D24='A' then 1 else 0 end+case when D25='A' then 1 else 0 end+case when D26='A' then 1 else 0 end+case when D27='A' then 1 else 0 end+case when D28='A' then 1 else 0 end+case when D29='A' then 1 else 0 end+case when D30='A' then 1 else 0 end+case when D31='A' then 1 else 0 end)as TotalCount from calc_atnd_master t1 $sql
    month between month( DATE_SUB(CURDATE(),INTERVAL 3 month) ) and month( DATE_SUB(CURDATE(), INTERVAL 1 month))and year between year(DATE_SUB(CURDATE(), INTERVAL 3 month)) and year( DATE_SUB(CURDATE(), INTERVAL 1 month)) group by EmployeeID,month,year");
    // and EmployeeID='ce0821939593' 
    $empIDs = '';
    $finalExp1 = '';
    if (count($calAtndQry) > 0 && $calAtndQry) {
        // echo "<pre>";
        // print_r($calAtndQry);
        $AllEmp = [];
        $notemp = [];
        foreach ($calAtndQry as $value) {
            // global $empIDs;
            // global $tcount;
            //$tcount = $value['TotalCount'];
            // $tcount .= '"' . $value["TotalCount"] . '"' . ',';
            if ((int)$value['TotalCount'] > (int)$flag) {
                //$empIDs .= '"' . $value["EmployeeID"] . '"' . ',';
                (int)$value['TotalCount'] . '--' . (int)$flag . '<br/>';
                array_push($notemp, $value["EmployeeID"]);
            }
            array_push($AllEmp, $value["EmployeeID"]);
        }
    } else {
        $empIDs = "";
    }
    $notemp = array_unique($notemp);
    // echo count($notemp) . '<br/>';
    $AllEmp = array_unique($AllEmp);
    // echo count($AllEmp);
    $final = array_diff($AllEmp, $notemp);
    // echo "<pre>";
    // print_r($final);
    $finalExp = implode("','", $final);
    // $finalExp1 = '"' . $finalExp . '"' . ',';
    //die;
    // print_r($notemp) . '----- <br/>';
    // print_r($AllEmp) . '<br/>';
    // die;
    return $finalExp;
}
// // echo $empIDs;
// $ans = substr($empIDs, 0, -1);
// echo "ffff" . $ans;
if (isset($_POST['postIJP'])) {
    // echo "<pre>";
    // print_r($_POST);
    $ijpName = trim(addslashes($_POST['ijp_name']));
    $remarks = trim(addslashes($_POST['remarks']));

    $insQry = "insert into ijp_master(ijp_name,remarks)values(?,?)";
    $stmt = $connn->prepare($insQry);
    $stmt->bind_param("ss", $ijpName, $remarks);
    if (!$stmt) {
        echo "failed to run";
        die;
    }

    $inst = $stmt->execute();

    // echo $insertId = $myDB->getInsertId();
    $insertId = $connn->insert_id;

    $dataArr = $_POST['empID'];
    $expID = $dataArr[0];
    $expID1 = explode(",", $expID);
    // print_r($expID1) . "ddddd";

    if ($stmt->affected_rows > 0) {
        foreach ($expID1 as $valEMP) {
            $insertQry = "insert into ijp_emp(EmployeeID,ijpID)values(?,?)";
            $stmt1 = $connn->prepare($insertQry);
            $stmt1->bind_param("si", $valEMP, $insertId);
            if (!$stmt1) {
                echo "failed to run";
                die;
            }
            // die;
            $insert = $stmt1->execute();
            // print_r($insert);
        }

        if ($stmt1->affected_rows === 1) {
            echo "<script>$(function(){toastr.success('IJP Post Successfully')})</script>";
        } else {
            echo "<script>$(function(){toastr.error('Not Inserted')})</script>";
        }
    } else {
        echo "<script>$(function(){toastr.error('Please Select a unique IJP NAME')})</script>";
    }
    // $getIDS = json_encode($dataArr);
}

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Employee IJP</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4> Employee IJP </h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <script>
                    //contain load event for data table and other importent rand required trigger event and searches if any
                    $(document).ready(function() {
                        $('#myTable').DataTable({
                            dom: 'Bfrtip',
                            scrollX: '100%',
                            "iDisplayLength": 10,
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

                <div class="input-field col s12 m12" id="rpt_container">

                    <div class="input-field col s4 m4">
                        <select id="location1" name="location1">
                            <option value="NA">----Select----</option>
                            <option value="ALL">ALL</option>
                            <?php
                            $sqlBy = 'select id,location from location_master';
                            $myDB = new MysqliDb();
                            $resultBy = $myDB->rawQuery($sqlBy);
                            $mysql_error = $myDB->getLastError();
                            if (empty($mysql_error)) {
                                foreach ($resultBy as $key => $value) {
                                    echo '<option value="' . $value['id'] . '"  >' . $value['location'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <label for="location1" class="active-drop-down active">Location</label>
                    </div>
                    <div class="input-field col s4 m4">
                        <div class="form-group">
                            <select class="form-control" name="client_name1" id="client_name1">
                                <option value="NA">select Client</option>
                            </select>
                            <label title="Select Client Name" for="client_name" class="active-drop-down active">Client</label>
                        </div>
                    </div>

                    <div class="input-field col s4 m4">
                        <select name="tenure" id="tenure">
                            <option value="NA">----Select Tenure----</option>
                            <option value=">06">GreaterThan 6 month</option>
                            <option value=">1">GreaterThan 1 Year</option>
                            <option value=">2">GreaterThan 2 Year</option>
                            <option value=">3">GreaterThan 3 Year</option>
                        </select>
                        <label for="tenure" class="active-drop-down active">Tenure</label>
                    </div>

                    <div class="input-field col s12 m12">
                        <div class="input-field col s4 m4">
                            <select name="versant" id="versant">
                                <option value="NA">No Assessment</option>
                                <?php
                                $sqlBy = 'select distinct cert_name from certification_require_by_cmid order by cert_name;';
                                $myDB = new MysqliDb();
                                $resultBy = $myDB->rawQuery($sqlBy);
                                $mysql_error = $myDB->getLastError();
                                if (empty($mysql_error)) {
                                    foreach ($resultBy as $key => $value) {
                                        echo '<option value="' . $value['cert_name'] . '"  >' . $value['cert_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="versant" class="active-drop-down active">Assessment Details</label>
                        </div>


                        <div class="input-field col s4 m4">
                            <select name="education" id="education">
                                <option value="NA">----Select----</option>
                                <option value="Basic">12th</option>
                                <option value="Graduation">Graduation</option>
                                <option value="PostGraduation">Post Graduation</option>
                            </select>
                            <label for="education" class="active-drop-down active">Education Details</label>
                        </div>
                        <div class="input-field col s4 m4">
                            <select name="abesentism" id="abesentism">
                                <option value="NA">----Select----</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                            </select>
                            <label for="abesentism" class="active-drop-down active">Abesentism </label>
                        </div>
                    </div>

                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" class="btn waves-effect waves-green" name="btn_search" id="btn_search">
                            <i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

                <?php
                if (isset($_POST['btn_search'])) {
                    $myDB = new MysqliDb();
                    // echo $_POST['abesentism'];
                    // die;
                    $loc = $_POST['location1'];
                    $tenure = $_POST['tenure'];
                    $versant = $_POST['versant'];
                    $education = $_POST['education']; ?>

                    <form method="post" action="">
                        <div id="pnlTable">
                            <?php
                            if ($_POST['education'] == 'Basic') {
                                $EduLevel .= '(edu_level="Basic") ';
                            } else {
                                $EduLevel .= 'edu_level="' . $education . '"';
                            }
                            if ($_POST['tenure'] == '>06') {
                                $dateDOJ .= "(DATEDIFF(now(),doj) >= 365/2)";
                            } else if ($_POST['tenure'] == '>1') {
                                $dateDOJ .= "(DATEDIFF(now(),doj) >= 365)";
                            } else if ($_POST['tenure'] == '>2') {
                                $dateDOJ .= "(DATEDIFF(now(),doj) >= 365*2)";
                            } else if ($_POST['tenure'] == '>3') {
                                $dateDOJ .= "(DATEDIFF(now(),doj) >= 365*3) ";
                            }
                            if ($_POST['versant'] == 'NA') {
                                $VerSant .= '';
                            } else if ($_POST['versant'] == 'Versant - 3') {
                                $VerSant .= 'and test_name in("Versant - 3","Versant - 4","Versant - 5","Versant - 6")';
                            } else if ($_POST['versant'] == 'Versant - 4') {
                                $VerSant .= 'and test_name in("Versant - 4","Versant - 5","Versant - 6")';
                            } else if ($_POST['versant'] == 'Versant - 5') {
                                $VerSant .= 'and test_name in("Versant - 5","Versant - 6")';
                            } else if ($_POST['versant'] == 'Versant - 6') {
                                $VerSant .= 'and test_name in("Versant - 6")';
                            } else {
                                $VerSant .= 'and test_name="' . $versant . '"';
                            }
                            $sqlConnect .= 'select distinct t1.EmployeeID as EmployeeID,t1.EmployeeName,t8.Designation,t6.client_name,t5.process,t5.sub_process,t9.location,t4.edu_level,t2.DOJ,t2.df_id from ( 
                                select X.EmployeeID, DOJ, df_id,cm_id  from ActiveEmpID X   left join  (select EmployeeID from resign_details where (cast(now() as date) between cast(nt_start as date) and cast(nt_end as date)) ) Y on X.EmployeeID =Y.EmployeeID 
                                left join  (select employee_id from corrective_action_form where statusHr="Approved" and cast(created_at as date) between DATE_SUB( NOW() , INTERVAL 3 month ) and cast(now() as date) ) Z on X.EmployeeID =Z.employee_id left join (select EmployeeID from ijp_emp where date(date_sub(now(), interval 3 month)) < date(created_on)) A 
                                on X.EmployeeID =A.EmployeeID where ' . $dateDOJ . ' and Y.EmployeeID is null and Z.employee_id is null and  A.EmployeeID is  null)t2 left join personal_details t1 on t1.EmployeeID=t2.EmployeeID left join test_score t3 on t3.EmpID=t1.EmployeeID left join (select EmployeeID,edu_level from education_details where  ' . $EduLevel . ') t4 on t4.EmployeeID=t1.EmployeeID left join new_client_master t5 on t5.cm_id=t2.cm_id left join client_master t6 on t6.client_id=t5.client_name left join df_master t7 on t7.df_id=t2.df_id left join designation_master t8 on t8.ID=t7.des_id left join location_master t9 on t9.id=t1.location where t1.EmployeeID is not null ' . $VerSant . ' ';

                            //     $sqlConnect .= 'select distinct t1.EmployeeID,t1.EmployeeName,t8.Designation,t6.client_name,t5.process,t5.sub_process,t9.location,t3.test_name,t4.edu_level,t2.DOJ,t2.df_id from 
                            //     ( select * from ActiveEmpID where ' . $dateDOJ . ')t2 
                            //     left join personal_details t1  on t1.EmployeeID=t2.EmployeeID 
                            //  left join test_score t3 on t3.EmpID=t1.EmployeeID 
                            //     left join (select EmployeeID,edu_level from education_details where  ' . $EduLevel . ') t4 on t4.EmployeeID=t1.EmployeeID 
                            //  left join new_client_master t5 on t5.cm_id=t2.cm_id
                            //  left join client_master t6 on t6.client_id=t5.client_name
                            //  left join df_master t7 on t7.df_id=t2.df_id
                            //  left join designation_master t8 on t8.ID=t7.des_id
                            //  left join location_master t9 on t9.id=t1.location
                            //     where t1.EmployeeID is not null ' . $VerSant . ' ';

                            if ($_POST['location1'] == 'ALL') {
                                $loc = '';
                            } else {
                                $sqlConnect .= " and t1.location='" . $loc . "' ";
                            }
                            if ($_POST['client_name1'] == 'ALL') {
                                $_POST['client_name1'] = '';
                            } else {
                                $sqlConnect .= " and t6.client_id='" . $_POST['client_name1'] . "' ";
                            }
                            $res = '';
                            if ($_POST['abesentism'] != 'NA') {

                                $res = calcAtnd($_POST['abesentism'], $_POST['location1']);
                                $sqlConnect .= " and t2.df_id=74 and t1.EmployeeID in('" . $res . "') ";
                                // die;
                            }

                            // echo $sqlConnect;
                            // die;
                            $myDB = new MysqliDb();
                            $result = $myDB->rawQuery($sqlConnect);
                            $mysql_error = $myDB->getLastError();
                            $rowCount = $myDB->count;
                            if ($rowCount > 0) {
                                // echo $sqlConnect;
                            ?>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>EmployeeID</th>
                                            <th>Employee Name</th>
                                            <th>DOJ</th>
                                            <th>Designation</th>
                                            <th>Client</th>
                                            <th>Process</th>
                                            <th>Subprocess</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        $empArr = array();
                                        foreach ($result as $key => $value) {
                                            // echo $ijpdata = implode(' ', $value['EmployeeID']);
                                            $empArr[] = $value['EmployeeID'];

                                        ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td class="empid"><?php echo $value['EmployeeID']; ?></td>
                                                <td><?php echo $value['EmployeeName']; ?></td>
                                                <td><?php echo $value['DOJ']; ?></td>
                                                <td><?php echo $value['Designation']; ?></td>
                                                <td><?php echo $value['client_name']; ?></td>
                                                <td><?php echo $value['process']; ?></td>
                                                <td><?php echo $value['sub_process']; ?></td>
                                                <td><?php echo $value['location']; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        // echo "test";
                                        // echo "<pre>";
                                        // print_r($empArr);

                                        ?>
                                    </tbody>
                                </table>
                                <br>
                                <div class="input-field col s12 m12 right-align">
                                    <input type="hidden" name="empID[]" value="<?php echo implode(",", $empArr); ?>">

                                    <div class="input-field col s4 m4">
                                        <input type="text" name="ijp_name" id="ijp_name">
                                        <label for="ijp_name" class="active-drop-down active">IJP Name</label>
                                    </div>
                                    <div class="input-field col s8 m8">
                                        <input type="text" name="remarks" id="remarks" maxlength="150">
                                        <label for="remarks" class="active-drop-down active">Remarks</label>
                                    </div>

                                    <div class="input-field col s12 m12">
                                        <button type="submit" name="postIJP" id="postIJP" class="btn btn-primary">POST IJP</button>
                                    </div>

                                </div>
                    </form>

                <?php } else {
                                echo "<script>$(function(){ toastr.error('No Data Found " . $mysql_error . "'); }); </script>";
                            }
                ?>
            </div>
        </div>
    <?php }
    ?>
    </div>
    <!--Form container End -->
</div>
<!--Main Div for all Page End -->
</div>
<!--Content Div for all Page End -->
</div>
<script>
    $('#btn_search').on('click', function() {
        var validate = 0;
        var alert_msg = '';
        if ($('#location1').val() == 'NA') {
            $('#location1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
            if ($('#spanlocation1').size() == 0) {
                $('<span id="spanlocation1" class="help-block">Required *</span>').insertAfter('#location1');
            }
            validate = 1;
        }
        if ($('#client_name1').val() == 'NA') {
            $('#client_name1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
            if ($('#spanclient_name1').size() == 0) {
                $('<span id="spanclient_name1" class="help-block">Required *</span>').insertAfter('#client_name1');
            }
            validate = 1;
        }
        if ($('#tenure').val() == 'NA') {
            $('#tenure').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
            if ($('#spantenure').size() == 0) {
                $('<span id="spantenure" class="help-block">Required *</span>').insertAfter('#tenure');
            }
            validate = 1;
        }
        if ($('#education').val() == 'NA') {
            $('#education').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
            if ($('#spaneducation').size() == 0) {
                $('<span id="spaneducation" class="help-block">Required *</span>').insertAfter('#education');
            }
            validate = 1;
        }
        if ($('#abesentism').val() == 'NA') {
            $('#abesentism').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
            if ($('#spanabesentism').size() == 0) {
                $('<span id="spanabesentism" class="help-block">Required *</span>').insertAfter('#abesentism');
            }
            validate = 1;
        }

        if (validate == 1) {
            alert_msg = 'Please fill, required field';
            $(function() {
                toastr.error(alert_msg);
            });
            return false;
        }
    });

    $('#postIJP').on('click', function() {
        var validate = 0;
        var alert_msg = '';
        if ($('#ijp_name').val() == '') {
            $('#ijp_name').addClass("has-error");
            if ($('#spanijp_name').size() == 0) {
                $('<span id="spanijp_name" class="help-block">Required *</span>').insertAfter('#ijp_name');
            }
            validate = 1;
        }
        if ($('#remarks').val() == '') {
            $('#remarks').addClass("has-error");
            if ($('#spanremarks').size() == 0) {
                $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#remarks');
            }
            validate = 1;
        }
        if (validate == 1) {
            alert_msg = 'Please fill, required field';
            $(function() {
                toastr.error(alert_msg);
            });
            return false;
        }
    });

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
<script>
    $("#location1").change(function() {
        var location1 = $(this).val();
        $.ajax({
            url: '../Controller/get_ijpclient.php',
            type: 'GET',
            data: {
                location1: location1,
            },
            dataType: 'json',
            success: function(response) {
                //alert(response);
                $("#client_name1").html(response.client_name1);
            }
        });
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>