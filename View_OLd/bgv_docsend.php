<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
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
    <span id="PageTittle_span" class="hidden">Background Verification</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Background Verification</h4>

            <!-- Form container if any -->
            <div class="input-field col s12 m12" id="rpt_container">
                <?php $date_From = cleanUserInput($_POST['txt_dateFrom']); ?>
                <div class="input-field col s4 m4">
                    <span>Select DOJ</span>
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


                // if (isset($_POST['btn_view'])) {
                $select_emp = "select b.id, b.EmployeeID,c.client_name,w.Process,w.sub_process,w.cm_id,case when w.df_id=74 then 'CSA' else 'Support' end as des, w.EmployeeName,w.designation,l1.location,w.DOJ from whole_details_peremp as w  join client_master as c on w.client_name=c.client_id join bgv as b on w.EmployeeID=b.EmployeeID join location_master l1 on l1.id=w.location where b.flag=0 and w.DOJ=?";
                $selectQ = $conn->prepare($select_emp);
                $selectQ->bind_param("s", $date_From);
                $selectQ->execute();
                $result = $selectQ->get_result();
                // $result = $myDB->query($select_emp);
                $btn_view = isset($_POST['btn_view']);
                if ($btn_view) {
                    if ($result->num_rows > 0) {
                        $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                    <div class=""><table id="myTable" class="data dataTable no-footer row-border cellspacing="0" width="100%"><thead><tr>';
                        $table .= '<th><input type="checkbox" name="cAll" id="cAll" value="ALL"><label for="cAll"></label></th>';
                        $table .= '<th class="hidden">EmployeeID</th>';
                        $table .= '<th style="font-size: 13px;">EmployeeID</th>';
                        $table .= '<th style="font-size: 13px;">Employee Name</th>';
                        $table .= '<th style="font-size: 13px;">Client</th>';
                        $table .= '<th style="font-size: 13px;">Process</th>';
                        $table .= '<th style="font-size: 13px;">Sub Process</th>';
                        $table .= '<th style="font-size: 13px;">Location</th>';
                        $table .= '<th style="font-size: 13px;">Designation</th>';
                        $table .= '<th style="font-size: 13px;">Date of Joining</th>';
                        $table .= '<th style="font-size: 13px;">Check</th></tr><thead><tbody>';
                        $i = 1;
                        foreach ($result as $key => $value) {
                            $chkflag = '';
                            $cmid = $value['cm_id'];
                            $designation = $value['des'];
                            $employee = $value['EmployeeID'];
                            $CD = "SELECT * FROM bgv_matrix where cm_id=? and desig=?";
                            $selectQr = $conn->prepare($CD);
                            $selectQr->bind_param("is", $cmid, $designation);
                            $selectQr->execute();
                            $cmdes = $selectQr->get_result();
                            $cm_des = $cmdes->fetch_row();
                            // $cm_des = $myDB->query($CD);
                            if ($cmdes->num_rows > 0) {
                                // $add = $cm_des[0]['Addr'];
                                // $edu = $cm_des[0]['Edu'];
                                // $Emp = $cm_des[0]['Emp'];
                                // $crim = $cm_des[0]['Crim'];
                                $add = $cm_des[3];
                                $edu = $cm_des[4];
                                $Emp = $cm_des[5];
                                $crim = $cm_des[6];
                                $SLA = "select * from sla_matrix where Addr=? and  Edu=? and Emp=? and Crim=?";
                                $selectQry = $conn->prepare($SLA);
                                $selectQry->bind_param("ssss", $add, $edu, $Emp, $crim);
                                $selectQry->execute();
                                $Sla_id = $selectQry->get_result();
                                // $Sla_id = $myDB->query($SLA);
                                if ($Sla_id->num_rows > 0) {
                                    if ($add == 'Yes') {
                                        $chkflag = $chkflag . "Address/";
                                    }
                                    if ($edu == 'Yes') {
                                        $chkflag = $chkflag . "Education/";
                                    }
                                    if ($Emp == 'Yes') {
                                        $chkflag = $chkflag . "Employment/";
                                    }
                                    if ($crim == 'Yes') {
                                        $chkflag = $chkflag . "Criminal";
                                    }
                                    // echo $chkflag;
                                    // die;
                                    $bg = "select * from doc_details where  doc_stype='BG verification' and EmployeeID=?";
                                    $selectQu = $conn->prepare($bg);
                                    $selectQu->bind_param("s", $employee);
                                    $selectQu->execute();
                                    $bgv = $selectQu->get_result();
                                    // $bgv = $myDB->query($bg);
                                    if ($bgv->num_rows <= 0) {
                                        // $mysql_error = $myDB->getLastError();
                                        // $rowCount = $myDB->count;

                                        if ($result && $result->num_rows > 0) {
                                            // foreach ($result as $key => $value) {
                                            $table .=  '<tr><td class="EmpId"><input type="checkbox" id="cb' . $i . '" class="cb_child" name="tcid[' . $i . ']" value="' . $value['EmployeeID'] . '"><label for="cb' . $i . '" ></label></td>';
                                            $table .= '<td class="EmployeeID hidden"><a onclick="javascript:return checklistdata(this);"  class="ckeckdata" data="' . $value['EmployeeID'] . '">' . $value['EmployeeID'] . '</a></td>';
                                            $table .= '<td>' . $value['EmployeeID'] . '</td>';
                                            $table .= '<td>' . $value['EmployeeName'] . '</td>';
                                            $table .= '<td>' . $value['client_name'] . '</td>';
                                            $table .= '<td>' . $value['Process'] . '</td>';
                                            $table .= '<td>' . $value['sub_process'] . '</td>';
                                            $table .= '<td>' . $value['location'] . '</td>';
                                            $table .= '<td>' . $value['designation'] . '</td>';
                                            $table .= '<td>' . $value['DOJ'] . '</td>';
                                            $table .= '<td>' . $chkflag . '</td></tr>';
                                            $i++; ?>
                            <?php
                                        }
                                    }
                                }
                            }
                        }

                        $table .= '</tbody></table></div></div>';
                        echo $table;
                        $btn_views = isset($_POST['btn_view']);
                        if ($btn_views) { ?>
                            <div class="input-field col s12 m12 right-align">
                                <button type="submit" name="submit" id="submit" class="btn waves-effect waves-green">GENERATE DOCUMENT</button>
                            </div>

                <?php
                        }
                    } else {
                        $btn_views = isset($_POST['btn_view']);
                        if ($btn_views) {
                            echo "<script>$(function(){ toastr.error('No Data Found.'); }); </script>";
                        }
                    }
                }

                ?>

                <?php
                // echo "sdfgd" . $temp_emp = $_SESSION['jhgjh'];

                // print_r($temp_emp);
                $submit = isset($_POST['submit']);
                if ($submit) {
                    // print_r($emp_id);
                    // die;
                    $tcid = isset($_POST['tcid']);
                    if ($tcid) {
                        $tcID = ($_POST['tcid']);
                        $tcIDAr = '';
                        foreach ($tcID as $row) {
                            $tcIDAr .= clean($row) . ',';
                        }
                        $tcIDAr = rtrim($tcIDAr, ',');
                        $EMP  = $tcIDAr;

                        // $EMP = implode(',', $_POST['tcid']);
                        $myDB =  new MysqliDb();
                        $emp_id = explode(',', $EMP);

                        $_SESSION['tempemp'] = $emp_id;
                        // print_r($emp_id);
                        // print_r($_SESSION);
                        //echo $temp_emp = $_SESSION[$emp_id];
                        $sla_id = '';
                        //$htmlresp = '';
                        foreach ($emp_id as $key => $value) {

                            $emp = $value;
                            // $chkflag = '';
                            // print_r($emp);
                            // die;
                            $select = "select FirstName, LastName, FatherName,dob, Gender,mobile,emailid, location,cm_id,case when df_id=74 then 'CSA' else 'Support' end as des from personal_details t1 left join contact_details t2 on t1.employeeid=t2.EmployeeID join employee_map t3 on t1.EmployeeID=t3.EmployeeID where t1.EmployeeID=? and emp_status='Active'";
                            // $htmlresp = $htmlresp  . $emp;
                            $selectQ = $conn->prepare($select);
                            $selectQ->bind_param("s", $emp);
                            $selectQ->execute();
                            $results = $selectQ->get_result();
                            $res = $results->fetch_row();
                            // $results = $myDB->query($select);

                            if ($results->num_rows > 0) {
                                // $cm_id = $res[0]['cm_id'];
                                // $des = $res[0]['des'];
                                $cm_id = $res[8];
                                $des = $res[9];

                                $sql = "SELECT * FROM bgv_matrix where cm_id=? and desig=?";
                                $selectQr = $conn->prepare($sql);
                                $selectQr->bind_param("is", $cm_id, $des);
                                $selectQr->execute();
                                $resu = $selectQr->get_result();
                                $query = $resu->fetch_row();
                                // $query = $myDB->query($sql);
                                if ($resu->num_rows > 0) {
                                    // $Addr = $query[0]['Addr'];
                                    // $Edu = $query[0]['Edu'];
                                    // $Empl = $query[0]['Emp'];
                                    // $Crim = $query[0]['Crim'];
                                    $Addr = $query[3];
                                    $Edu = $query[4];
                                    $Empl = $query[5];
                                    $Crim = $query[6];

                                    $sla = "select * from sla_matrix where Addr=? and  Edu=? and Emp=? and Crim=?";
                                    $selectQry = $conn->prepare($sla);
                                    $selectQry->bind_param("ssss", $Addr, $Edu, $Empl, $Crim);
                                    $selectQry->execute();
                                    $resul = $selectQry->get_result();
                                    $SQL = $resul->fetch_row();
                                    // $SQL = $myDB->query($sla);
                                    if ($resul->num_rows > 0) {
                                        $sla_id = $SQL[1];
                                        if ($sla_id != '') {
                                            $aadhar_path = '';
                                            $edu_path = '';
                                            $exp_path = '';
                                            $location = $res[7];
                                            $jaf_file_attachments = array();

                                            $doc = "select distinct doc_file from doc_details where EmployeeID=? and doc_stype='Aadhar Card'";
                                            $selectQury = $conn->prepare($doc);
                                            $selectQury->bind_param("s", $emp);
                                            $selectQury->execute();
                                            $resultss = $selectQury->get_result();
                                            // $resultss = $myDB->query($doc);
                                            foreach ($resultss as $key => $aadhar_value) {
                                                $doc_adhar = $aadhar_value['doc_file'];

                                                if ($location == "1" || $location == "2") {
                                                    $aadhar_path = "https://demo.cogentlab.com/erpm/Docs/AdharCard/" . $doc_adhar . "";
                                                } else if ($location == "3") {
                                                    $aadhar_path = "https://demo.cogentlab.com/erpm/Meerut/Docs/AdharCard/" . $doc_adhar . "";
                                                } else if ($location == "4") {
                                                    $aadhar_path = "https://demo.cogentlab.com/erpm/Bareilly/Docs/AdharCard/" . $doc_adhar . "";
                                                } else if ($location == "5") {
                                                    $aadhar_path = "https://demo.cogentlab.com/erpm/Vadodara/Docs/AdharCard/" . $doc_adhar . "";
                                                } else if ($location == "6") {
                                                    $aadhar_path = "https://demo.cogentlab.com/erpm/Manglore/Docs/AdharCard/" . $doc_adhar . "";
                                                } else if ($location == "7") {
                                                    $aadhar_path = "https://demo.cogentlab.com/erpm/Bangalore/Docs/AdharCard/" . $doc_adhar . "";
                                                }

                                                $jaf_file_attachments[] = $aadhar_path;
                                            }


                                            if ($edu == 'Yes') {
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
                                                }

                                                $education = "select * from education_details where EmployeeID=?";
                                                $selQury = $conn->prepare($education);
                                                $selQury->bind_param("s", $emp);
                                                $selQury->execute();
                                                $edufile = $selQury->get_result();
                                                // $edufile = $myDB->query($education);
                                                foreach ($edufile as $key => $val) {

                                                    //echo   $val['edu_file'];
                                                    if (file_exists(ROOT_PATH . $Location . 'Edu/' . $val['edu_file'])) {
                                                        $edu_path =  'https://demo.cogentlab.com/erpm/' . $Location . 'Edu/' . $val['edu_file'];
                                                    } else {
                                                        $edu_path =  'https://demo.cogentlab.com/erpm/' . $Location . 'Education/' . $val['edu_file'];
                                                    }
                                                    //$edu_path = $edu_path . ',';
                                                    $jaf_file_attachments[] = $edu_path;
                                                }
                                            }

                                            if ($Emp == "Yes") {
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
                                                }

                                                $experience = "select * from experince_details where EmployeeID=? and exp_type='Experience' and employer not like'%Cogent%' order by `to` desc limit 2";
                                                $selQuy = $conn->prepare($experience);
                                                $selQuy->bind_param("s", $emp);
                                                $selQuy->execute();
                                                $exper = $selQuy->get_result();
                                                // $exper = $myDB->query($experience);
                                                foreach ($exper as $key => $values) {
                                                    $filename = $values['releiving_experience_doc'];
                                                    $filename2 = $values['appointment_offerletter_doc'];
                                                    $filename3 = $values['salaryslip_bankstatement_doc'];

                                                    if (file_exists(ROOT_PATH . $ofc_loc . 'Experience/' . $filename)) {
                                                        $exp_path1 =  'https://demo.cogentlab.com/erpm/' . $ofc_loc . 'Experience/' . $filename;
                                                        $jaf_file_attachments[] = $exp_path1;
                                                    }
                                                    if (file_exists(ROOT_PATH . $ofc_loc . 'offerletter/' . $filename2)) {
                                                        $exp_path2 =  'https://demo.cogentlab.com/erpm/' . $ofc_loc . 'offerletter/' . $filename2;
                                                        $jaf_file_attachments[] = $exp_path2;
                                                    }
                                                    if (file_exists(ROOT_PATH . $ofc_loc . 'salaryslip/' . $filename3)) {
                                                        $exp_path3 =  'https://demo.cogentlab.com/erpm/' . $ofc_loc . 'salaryslip/' . $filename3;
                                                        $jaf_file_attachments[] = $exp_path3;
                                                    }
                                                }
                                            }

                                            // echo "<pre>";
                                            // print_r($jaf_file_attachments);
                                            // die;
                                            $pathdir = "../Services/Docs_Inactive/$emp/";

                                            mkdir($pathdir);
                                            foreach ($jaf_file_attachments as $key) {
                                                $file_name = basename($key);
                                                $files = "../Services/Docs_Inactive/$emp/" . $file_name;
                                                if (file_put_contents($files, file_get_contents($key))) {
                                                    // echo "File downloaded successfully";
                                                } else {
                                                    // echo "File downloading failed.";
                                                }
                                            }
                                            // Enter the name of directory

                                            $pathdir = "../Services/Docs_Inactive/$emp/";
                                            // Enter the name to creating zipped directory
                                            $pathzip = '../Services/Docs_Inactivezip/';

                                            $zipcreated = "../Services/Docs_Inactivezip/$emp.zip";

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
                                        }
                                    }
                                }
                            }
                        }
                        // echo $emp;
                    }
                    $_SESSION['tempemp'];
                    $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 35%;padding: 15px;">
                    <div class=""><table id="myTable1" cellspacing="0" width="100%"><thead><tr>';
                    $table .= '<th style="font-size: 13px;">EmployeeID</th></tr><thead><tbody>';
                    $i = 1;
                    foreach ($_SESSION['tempemp'] as $key => $value) {
                        $table .= '<td style="font-size:medium" class="red-text text-darken">' . $value . '---SEND DOCUMENT</td></tr>';
                        $i++;
                    }
                    $table .= '</tbody></table></div></div>';
                    echo $table;
                ?>

                    <div class="form-group">
                        <div class="input-field col s8 m8">
                            <select class="form-control" name="bgv" id="bgv" required>
                                <option value="NA">Select Consultancy</option>
                                <?php
                                $sqlBy = 'select id,bgv_vender_name from bgv_vender;';
                                $myDB = new MysqliDb();
                                $resultBy = $myDB->rawQuery($sqlBy);
                                $mysql_error = $myDB->getLastError();
                                if (empty($mysql_error)) {
                                    foreach ($resultBy as $key => $value) {
                                        echo '<option value="' . $value['id'] . '"  >' . $value['bgv_vender_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="bgv" class="active-drop-down active">BGV</label>
                        </div>
                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="send" id="send" class="btn waves-effect waves-green">SEND</button>
                    </div>
                <?php
                }
                $send = isset($_POST['send']);
                if ($send) {
                    //$_SESSION['tempemp'];
                    $htmlresp = '';
                    foreach ($_SESSION['tempemp'] as $key => $value) {
                        $emp = $value;
                        $chkflag = '';
                        $select = "select FirstName,MiddleName, LastName, FatherName,dob, Gender,mobile,emailid, location,cm_id,case when df_id=74 then 'CSA' else 'Support' end as des,EmployeeName,dateofjoin from personal_details t1 left join contact_details t2 on t1.employeeid=t2.EmployeeID join employee_map t3 on t1.EmployeeID=t3.EmployeeID where t1.EmployeeID=? and emp_status='Active'";
                        $htmlresp = $htmlresp  . $emp;
                        $selQuy = $conn->prepare($select);
                        $selQuy->bind_param("s", $emp);
                        $selQuy->execute();
                        $results = $selQuy->get_result();

                        $res = $results->fetch_row();
                        if ($results->num_rows > 0) {
                            $cm_id = $res[9];
                            $des = $res[10];
                            $first_name = $res[0];
                            $MiddleName = $res[1];
                            $LastName = $res[2];
                            $father_name = $res[3];
                            $phone = $res[6];
                            $emailid = $res[7];
                            $dob = $res[4];
                            $gender = $res[5];
                            $location = $res[8];
                            $EmployeeName = $res[11];
                            $doj = $res[12];

                            $sql = "SELECT * FROM bgv_matrix where cm_id=? and desig=?";
                            $sel = $conn->prepare($sql);
                            $sel->bind_param("is", $cm_id, $des);
                            $sel->execute();
                            $resul = $sel->get_result();
                            $query = $resul->fetch_row();

                            if ($resul->num_rows > 0) {
                                $Addr = $query[3];
                                $Edu = $query[4];
                                $Empl = $query[5];
                                $Crim = $query[6];

                                $sla = "select * from sla_matrix where Addr=? and  Edu=? and Emp=? and Crim=?";
                                $selects = $conn->prepare($sla);
                                $selects->bind_param("ssss", $Addr, $Edu, $Empl, $Crim);
                                $selects->execute();
                                $res = $selects->get_result();
                                $SQL = $res->fetch_row();

                                if ($res->num_rows > 0) {
                                    $sla_id = $SQL[1];
                                    if ($sla_id != '') {
                                        if ($Addr == 'Yes') {
                                            $chkflag = $chkflag . "Address/";
                                        }
                                        if ($Edu == 'Yes') {
                                            $chkflag = $chkflag . "Education/";
                                        }
                                        if ($Empl == 'Yes') {
                                            $chkflag = $chkflag . "Employment/";
                                        }
                                        if ($Crim == 'Yes') {
                                            $chkflag = $chkflag . "Criminal";
                                        }
                                        $doc = "select distinct dov_value from doc_details where EmployeeID=? and doc_stype='Aadhar Card'";
                                        $selectQ = $conn->prepare($doc);
                                        $selectQ->bind_param("s", $emp);
                                        $selectQ->execute();
                                        $resultQ = $selectQ->get_result();
                                        $resultss = $resultQ->fetch_row();
                                        $doc_adhar = $resultss[0];
                                        $reference_number = 'COGE-0000001831';
                                        $jaf_access = 'vendor';
                                    }
                                }
                            }
                        }
                        if ($_POST['bgv'] == '1') {
                            $curl = curl_init();
                            $data['reference_number'] = $reference_number;
                            $data['client_emp_code'] = $emp;
                            $data['first_name'] = $first_name;
                            $data['middle_name'] = $MiddleName;
                            $data['last_name'] = $LastName;
                            $data['father_name'] = $father_name;
                            $data['phone'] = $phone;
                            $data['email'] = $emailid;
                            $data['dob'] = $dob;
                            $data['gender'] = $gender;
                            $data['aadhar_number'] = $doc_adhar;
                            $data['sla_id'] = $sla_id;
                            $data['jaf_access'] = $jaf_access;
                            $zipcreated = "/var/www/html/erpm/Services/Docs_Inactivezip/$emp.zip";
                            $data['jaf_file_attachments[ ]'] = new CURLFILE($zipcreated);
                            // echo "<pre>";
                            // print_r($data);
                            // die;
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://my-bcd.com/api/client/candidate/upload',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $data,
                                CURLOPT_HTTPHEADER => array(
                                    'Accept: application/json',
                                    'Content-Type: multipart/form-data',
                                    'Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs'
                                    //'Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNIMz3JFBxdejSXs'
                                ),
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $res = json_decode($response);
                            // print_r($res);
                            if ($res->status == 'True') {
                                // print_r($res->candidate_id);
                                $userID = clean($_SESSION['__user_logid']);
                                $htmlresp = $htmlresp . '---' . $res->candidate_id . ' Successfully Send Document' . '<br/>';
                                $candi_id = $res->candidate_id;
                                $bgv_vender = cleanUserInput($_POST['bgv']);
                                $updateres = 'update bgv set Candidate_id=?, flag=1, Modifiedon = now(),ModifiedBy= ?,check_flag= ?,bgv_vender=? where EmployeeID=? ';
                                $update = $conn->prepare($updateres);
                                $update->bind_param("sssis", $candi_id, $userID, $chkflag, $bgv_vender, $emp);
                                $update->execute();
                                $resultBy = $update->get_result();
                                // $myDB = new MysqliDb();
                                // $resultBy = $myDB->query($updateres);
                            } else {
                                // echo $res->errors->user;
                                $userID = clean($_SESSION['__user_logid']);
                                $htmlresp = $htmlresp . '---' . $res->errors->user . '<br/>';
                                $respon = $res->message;
                                $update = 'Insert into bgv_api_log (Emp_id,response,createdby)values(?,?,?)';
                                $ins = $conn->prepare($update);
                                $ins->bind_param("sss", $emp, $respon, $userID);
                                $ins->execute();
                                $resu = $ins->get_result();
                                // $myDB = new MysqliDb();
                                // $resu = $myDB->query($update);
                            }
                        } else {
                            if ($EmployeeName &&  $dob &&  $doj && $father_name && $location && $sla_id != '') {
                                $curl = curl_init();
                                $data['file[ ]'] = '';
                                $data['clientId'] = '38';
                                $data['candidateName'] = $EmployeeName;
                                $data['dob'] = $dob;
                                $data['doj'] = $doj;
                                $data['empId'] = $emp;
                                $data['fatherName'] = $father_name;
                                $data['phoneNumber'] = $phone;
                                $data['candidateEmail'] = $emailid;
                                $data['location'] = $location;
                                $data['sla_id'] = $sla_id;
                                $data['filelink'] = "/var/www/html/erpm/Services/Docs_Inactivezip/$emp.zip";

                                // print_r($data);
                                // die;
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'https://zella.in/zap/admin/App/insertApplicationWithAuth',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                    CURLOPT_HTTPHEADER => array(
                                        'API_KEY: 123456789',
                                        'Cookie: ci_session=33827fb7dfa3f5ed3be1a367e3fd6069a9a804a5'
                                    ),
                                ));

                                $response = curl_exec($curl);
                                // $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                // print curl_error($curl);
                                // print_r($response);
                                // die;
                                curl_close($curl);
                                $response;
                                $res = json_decode($response);

                                if ($res->status == '1') {
                                    $userID = clean($_SESSION['__user_logid']);
                                    $htmlresp = $htmlresp . ' Successfully Send Document' . '<br/>';

                                    $bgv_vender = cleanUserInput($_POST['bgv']);
                                    echo $updateres = 'update bgv set Candidate_id=?, flag=1, Modifiedon = now(),ModifiedBy= ?,check_flag= ?,bgv_vender=? where EmployeeID=? ';
                                    $update = $conn->prepare($updateres);
                                    $update->bind_param("sssis", $emp, $userID, $chkflag, $bgv_vender, $emp);
                                    $update->execute();
                                    $resultBy = $update->get_result();
                                } else {
                                    $userID = clean($_SESSION['__user_logid']);
                                    $htmlresp = $htmlresp . 'Application is already exist with this empId' . '<br/>';
                                    $respon = 'Application is already exist with this empId';
                                    $update = 'Insert into bgv_api_log (Emp_id,response,createdby)values(?,?,?)';
                                    $ins = $conn->prepare($update);
                                    $ins->bind_param("sss", $emp, $respon, $userID);
                                    $ins->execute();
                                    $resu = $ins->get_result();
                                }
                            } else {
                                echo "<script>$(function(){ toastr.error('Application value is blank.Please give some value.') });</script>";
                            }
                        }
                    }
                }


                ?>
                <p style="font-size:medium" class="red-text text-darken"><?php echo $htmlresp; ?></p>
                <?php

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

        $("#cAll").change(function() {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
        $("input:checkbox").change(function() {
            if ($('input.cb_child:checkbox:checked').length > 0) {
                checklistdata();
                if ($('input.cb_child:checkbox:checked').length == $('input.cb_child:checkbox').length) {
                    $("#cAll").prop("checked", true);
                } else {
                    $("#cAll").prop("checked", false);
                }
            } else {
                $("#cAll").prop("checked", false);
            }
        });
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
        $('#send').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#bgv').val() == 'NA') {
                $('#bgv').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanbgv').size() == 0) {
                    $('<span id="spanbgv" class="help-block">Required *</span>').insertAfter('#bgv');
                }
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