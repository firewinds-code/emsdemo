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
    <span id="PageTittle_span" class="hidden">Background Verification</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Background Verification</h4>

            <!-- Form container if any -->
            <div class="input-field col s12 m12" id="rpt_container">
                <?php $date_From = $_POST['txt_dateFrom']; ?>
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

                $myDB =  new MysqliDb();
                // if (isset($_POST['btn_view'])) {
                $select_emp = "select b.id, b.EmployeeID,c.client_name,w.Process,w.sub_process,w.cm_id,case when w.df_id in (74,77,146, 147,148,149) then 'CSA' else 'Support' end as des, w.EmployeeName,w.designation,l1.location,w.DOJ from whole_details_peremp as w  join client_master as c on w.client_name=c.client_id join bgv as b on w.EmployeeID=b.EmployeeID join location_master l1 on l1.id=w.location where b.flag=0 and w.DOJ='" . $date_From . "'";
                $myDB =  new MysqliDb();
                $result = $myDB->query($select_emp);
                if (isset($_POST['btn_view'])) {
                    if (count($result) > 0) {
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
                            $CD = "SELECT * FROM bgv_matrix where cm_id='" . $cmid . "' and desig='" . $designation . "'";
                            $cm_des = $myDB->query($CD);
                            if (count($cm_des) > 0) {
                                $add = $cm_des[0]['Addr'];
                                $edu = $cm_des[0]['Edu'];
                                $Emp = $cm_des[0]['Emp'];
                                $crim = $cm_des[0]['Crim'];
                                $SLA = "select * from sla_matrix where Addr='" . $add . "' and  Edu='" . $edu . "' and Emp='" . $Emp . "' and Crim='" . $crim . "'";
                                $Sla_id = $myDB->query($SLA);
                                if (count($Sla_id) > 0) {
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
                                    $bg = "select * from doc_details where  doc_stype='BG verification' and EmployeeID='" . $employee . "'";
                                    $bgv = $myDB->query($bg);
                                    if (count($bgv) <= 0) {
                                        $mysql_error = $myDB->getLastError();
                                        $rowCount = $myDB->count;

                                        if (empty($my_error) && count($result) > 0) {
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
                        if (isset($_POST['btn_view'])) { ?>
                            <div class="input-field col s12 m12 right-align">
                                <button type="submit" name="submit" id="submit" class="btn waves-effect waves-green">GENERATE DOCUMENT</button>
                            </div>

                <?php
                        }
                    } else {
                        if (isset($_POST['btn_view'])) {
                            echo "<script>$(function(){ toastr.error('No Data Found " . $my_error . "'); }); </script>";
                        }
                    }
                }

                ?>

                <?php
                // echo "sdfgd" . $temp_emp = $_SESSION['jhgjh'];

                // print_r($temp_emp);
                if (isset($_POST['submit'])) {
                    // print_r($emp_id);
                    // die;
                    if (isset($_POST['tcid'])) {
                        $EMP = implode(',', $_POST['tcid']);

                        // echo $temp_emp = $_SESSION[$EMP];
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
                            $select = "select FirstName, LastName, FatherName,dob, Gender,mobile,emailid, location,cm_id,case when df_id in (74,77,146, 147,148,149) then 'CSA' else 'Support' end as des from personal_details t1 left join contact_details t2 on t1.employeeid=t2.EmployeeID join employee_map t3 on t1.EmployeeID=t3.EmployeeID where t1.EmployeeID='" . $emp . "' and emp_status='Active'";
                            // $htmlresp = $htmlresp  . $emp;
                            $results = $myDB->query($select);

                            if (count($results) > 0) {
                                $cm_id = $results[0]['cm_id'];
                                $des = $results[0]['des'];

                                $sql = "SELECT * FROM bgv_matrix where cm_id='" . $cm_id . "' and desig='" . $des . "'";
                                $query = $myDB->query($sql);
                                if (count($query) > 0) {
                                    $Addr = $query[0]['Addr'];
                                    $Edu = $query[0]['Edu'];
                                    $Empl = $query[0]['Emp'];
                                    $Crim = $query[0]['Crim'];

                                    $sla = "select * from sla_matrix where Addr='" . $Addr . "' and  Edu='" . $Edu . "' and Emp='" . $Empl . "' and Crim='" . $Crim . "'";
                                    $SQL = $myDB->query($sla);
                                    if (count($SQL) > 0) {
                                        $sla_id = $SQL[0]['sla_id'];
                                        if ($sla_id != '') {
                                            $aadhar_path = '';
                                            $edu_path = '';
                                            $exp_path = '';
                                            $location = $results[0]['location'];
                                            $jaf_file_attachments = array();

                                            $doc = "select distinct doc_file from doc_details where EmployeeID='" . $emp . "' and doc_stype='Aadhar Card'";
                                            $resultss = $myDB->query($doc);
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


                                            if ($Edu == 'Yes') {
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

                                                $education = "select * from education_details where EmployeeID='" . $emp . "'";
                                                $edufile = $myDB->query($education);
                                                foreach ($edufile as $key => $val) {

                                                    //echo   $val['edu_file'];
                                                    if (file_exists(ROOT_PATH . $Location . 'Edu/' . $val['edu_file'])) {
                                                        $edu_path =  'https://ems.cogentlab.com/erpm/' . $Location . 'Edu/' . $val['edu_file'];
                                                    } else {
                                                        $edu_path =  'https://ems.cogentlab.com/erpm/' . $Location . 'Education/' . $val['edu_file'];
                                                    }
                                                    // echo $edu_path;
                                                    // die;
                                                    //$edu_path = $edu_path . ',';
                                                    $jaf_file_attachments[] = $edu_path;
                                                }
                                            }

                                            if ($Empl == "Yes") {
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

                                                $experience = "select * from experince_details where EmployeeID='" . $emp . "' and exp_type='Experience' and employer not like'%Cogent%' order by `to` desc limit 2";
                                                $exper = $myDB->query($experience);
                                                foreach ($exper as $key => $values) {
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

                                            // echo "<pre>";
                                            // print_r($jaf_file_attachments);
                                            // die;
                                            $pathdir = "../Services/Docs_Inactive/$emp/";

                                            mkdir($pathdir);
                                            foreach ($jaf_file_attachments as $key) {
                                                $file_name = basename($key);
                                                if (file_put_contents("../Services/Docs_Inactive/$emp/" . $file_name, file_get_contents($key))) {
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
                    $table = '<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                    <div class=""><table id="myTable1" cellspacing="0" width="100%"><thead><tr>';
                    $table .= '<th style="font-size: 13px;">EmployeeID</th>';
                    $table .= '<th style="font-size: 13px;">EmployeeName</th>';
                    $table .= '<th style="font-size: 13px;">Document</th></tr><thead><tbody>';
                    $i = 1;
                    foreach ($_SESSION['tempemp'] as $key => $value) {
                        $sel_EmployeeName = "select EmployeeName from personal_details where EmployeeID='" . $value . "'";
                        $EmployeeName = $myDB->query($sel_EmployeeName);
                        $table .= '<td style="font-size:medium" class="red-text text-darken">' . $value . '---SEND DOCUMENT</td>';
                        $table .= '<td style="font-size:medium" class="red-text text-darken">' . $EmployeeName[0]['EmployeeName'] . '</td>';
                        $doczip = "../Services/Docs_Inactivezip/$value.zip";
                        $table .= '<td> <a href="' . $doczip . '" target="_blank" download> <i class="fa fa-download"></i>Download</a></td></tr>';
                        // $table .= '<td style="font-size:medium" class="red-text text-darken">' . $value . '</td></tr>';
                        $i++;
                    }
                    $table .= '</tbody></table></div></div>';
                    echo $table;
                ?>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="send" id="send" class="btn waves-effect waves-green">SEND</button>
                    </div>
                <?php
                }

                if (isset($_POST['send'])) {
                    //$_SESSION['tempemp'];
                    $htmlresp = '';
                    foreach ($_SESSION['tempemp'] as $key => $value) {
                        $emp = $value;
                        $chkflag = '';
                        $select = "select FirstName,MiddleName, LastName, FatherName,dob, Gender,mobile,emailid, location,cm_id,case when df_id in (74,77,146, 147,148,149) then 'CSA' else 'Support' end as des,address_p from personal_details t1 left join contact_details t2 on t1.employeeid=t2.EmployeeID join employee_map t3 on t1.EmployeeID=t3.EmployeeID join address_details t4 on t3.EmployeeID=t4.EmployeeID where t1.EmployeeID='" . $emp . "' and emp_status='Active'";
                        $htmlresp = $htmlresp  . $emp;
                        $results = $myDB->query($select);
                        if (count($results) > 0) {
                            $cm_id = $results[0]['cm_id'];
                            $des = $results[0]['des'];

                            $sql = "SELECT * FROM bgv_matrix where cm_id='" . $cm_id . "' and desig='" . $des . "'";
                            $query = $myDB->query($sql);
                            if (count($query) > 0) {
                                $Addr = $query[0]['Addr'];
                                $Edu = $query[0]['Edu'];
                                $Empl = $query[0]['Emp'];
                                $Crim = $query[0]['Crim'];

                                $sla = "select * from sla_matrix where Addr='" . $Addr . "' and  Edu='" . $Edu . "' and Emp='" . $Empl . "' and Crim='" . $Crim . "'";
                                $SQL = $myDB->query($sla);
                                if (count($SQL) > 0) {
                                    $sla_id = $SQL[0]['sla_id'];
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
                                        $doc = "select distinct dov_value from doc_details where EmployeeID='" . $emp . "' and doc_stype='Aadhar Card'";
                                        $resultss = $myDB->query($doc);
                                        $doc_adhar = $resultss[0]['dov_value'];
                                        // print_r($resultss);
                                        $reference_number = 'COGE-0000001831';
                                        //$reference_number = 'ZNaLkJ2S20ean3GfJSzTgFQ3MNIMz3JFBxdejSXs';
                                        $first_name = $results[0]['FirstName'];
                                        $MiddleName = $results[0]['MiddleName'];
                                        $LastName = $results[0]['LastName'];
                                        $father_name = $results[0]['FatherName'];
                                        $phone = $results[0]['mobile'];
                                        $emailid = $results[0]['emailid'];
                                        $dob = $results[0]['dob'];
                                        $gender = $results[0]['Gender'];
                                        $adress = $results[0]['address_p'];
                                        //$gender = $doc_adhar;
                                        $sla_id = $SQL[0]['sla_id'];
                                        $jaf_access = 'vendor';
                                        //echo 'sla-id:- ' . $sla_id;
                                        //die;
                                        $location = $results[0]['location'];
                                    }
                                }
                            }
                        }

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
                        $data['address'] = $adress;
                        $data['address_type'] = "Permanent";
                        $zipcreated = "/var/www/html/erpm/Services/Docs_Inactivezip/$emp.zip";
                        //echo $emp . '<br/>';
                        $data['jaf_file_attachments[ ]'] = new CURLFILE($zipcreated);
                        // if (file_exists($zipcreated)) {
                        //     echo $zipcreated;
                        // }
                        // echo 'test';
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
                        //print_r($curl);   
                        $response = curl_exec($curl);
                        //print_r($response);
                        curl_close($curl);
                        //die;
                        //   $htmlresp = $htmlresp . '--- Tested Case ----' . '<br/>';
                        $res = json_decode($response);
                        // print_r($res);
                        if ($res->status == 'True') {
                            // print_r($res->candidate_id);
                            $htmlresp = $htmlresp . '---' . $res->candidate_id . ' Successfully Send Document' . '<br/>';
                            $updateres = 'update bgv set Candidate_id="' . $res->candidate_id . '", flag=1, Modifiedon = now(),ModifiedBy= "' . $_SESSION['__user_logid'] . '",check_flag= "' . $chkflag . '" where EmployeeID="' . $emp . '" ';
                            $myDB = new MysqliDb();
                            $resultBy = $myDB->query($updateres);
                        } else {
                            // echo $res->errors->user;
                            $htmlresp = $htmlresp . '---' . $res->errors->user . '<br/>';
                            $update = 'Insert into bgv_api_log (Emp_id,response,createdby)values("' . $emp . '","' . $res->message . '","' . $_SESSION['__user_logid'] . '")';
                            $myDB = new MysqliDb();
                            $resu = $myDB->query($update);
                        }
                    }
                    // }
                }

                // echo $GET['tcid'];

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
    //$('#send').hide();

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
                if ($('#spantxt_dateFrom').size() == 0) {
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