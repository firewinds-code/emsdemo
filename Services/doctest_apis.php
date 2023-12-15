<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
// require(ROOT_PATH . 'AppCode/nHead.php');


// Global variable used in Page Cycle
ini_set('display_errors', '0');

//$select_emp = "select EmployeeID from bgv where flag=0 order by id limit 10";
$select_emp = "select EmployeeID from bgv where EmployeeID='CEK062280713'";
$myDB =  new MysqliDb();
$result = $myDB->query($select_emp);
// print_r($result);
$mysql_error = $myDB->getLastError();
$rowCount = $myDB->count;
$sla_id = '';
if (empty($mysql_error)) {
    foreach ($result as $key => $value) {
        $emp = $value['EmployeeID'];
        $select = "select FirstName, LastName, FatherName,dob, Gender,mobile,emailid, location,cm_id,case when df_id in (74,77,146, 147,148,149) then 'CSA' else 'Support' end as des from personal_details t1 left join contact_details t2 on t1.employeeid=t2.EmployeeID join employee_map t3 on t1.EmployeeID=t3.EmployeeID where t1.EmployeeID='" . $emp . "' and emp_status='Active'";
        $results = $myDB->query($select);
        if (count($results) > 0) {
            $cm_id = $results[0]['cm_id'];
            $des = $results[0]['des'];

            $sql = "SELECT * FROM bgv_matrix where cm_id='" . $cm_id . "' and desig='" . $des . "'";
            $query = $myDB->query($sql);
            if (count($query) > 0) {
                $add = $query[0]['Addr'];
                $edu = $query[0]['Edu'];
                $Emp = $query[0]['Emp'];
                $crim = $query[0]['Crim'];

                $sla = "select * from sla_matrix where Addr='" . $add . "' and  Edu='" . $edu . "' and Emp='" . $Emp . "' and Crim='" . $crim . "'";
                $SQL = $myDB->query($sla);
                if (count($SQL) > 0) {
                    $sla_id = $SQL[0]['sla_id'];
                    if ($sla_id != '') {
                        $doc = "select distinct doc_file from doc_details where EmployeeID='" . $emp . "' and doc_stype='Aadhar Card'";
                        $resultss = $myDB->query($doc);
                        $doc_adhar = $resultss[0]['doc_file'];
                        // print_r($resultss);
                        $reference_number = 'COGE-0000001118';
                        $first_name = $results[0]['FirstName'];
                        $father_name = $results[0]['FatherName'];
                        $phone = $results[0]['mobile'];
                        $dob = $results[0]['dob'];
                        $gender = $results[0]['Gender'];
                        $sla_id = $SQL[0]['sla_id'];
                        $jaf_access = 'vendor';
                        //echo 'sla-id:- ' . $sla_id;
                        //die;
                        $location = $results[0]['location'];
                        $aadhar_path = '';
                        $edu_path = '';
                        $exp_path = '';

                        //$jaf_file_attachments = '';
                        $jaf_file_attachments = array();

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
                        } else if ($location == "10") {
                            $aadhar_path = "https://ems.cogentlab.com/erpm/Gurgaon/Docs/AdharCard/" . $doc_adhar . "";
                        } else if ($location == "11") {
                            $aadhar_path = "https://ems.cogentlab.com/erpm/Hyderabad/Docs/AdharCard/" . $doc_adhar . "";
                        }

                        $jaf_file_attachments[] = $aadhar_path;

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
                            } else if ($location == "8") {
                                $Location = "Nashik/";
                            } else if ($location == "9") {
                                $Location = "Anantapur/";
                            } else if ($location == "10") {
                                $Location = "Gurgaon/";
                            } else if ($location == "11") {
                                $Location = "Hyderabad/";
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
                            } else if ($location == "8") {
                                $ofc_loc = 'Nashik/Docs/';
                            } else if ($location == "9") {
                                $ofc_loc = 'Anantapur/Docs/';
                            } else if ($location == "10") {
                                $ofc_loc = 'Gurgaon/Docs/';
                            } else if ($location == "11") {
                                $ofc_loc = 'Hyderabad/Docs/';
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
                        $pathdir = 'Docs_Inactive/';
                        if (!is_dir($pathdir)) {
                            mkdir($pathdir);
                        } else {
                            $files = glob($pathdir . '/*');
                            //Loop through the file list.
                            foreach ($files as $file) {
                                //Make sure that this is a file and not a directory.
                                if (is_file($file)) {
                                    //Use the unlink function to delete the file.
                                    unlink($file);
                                }
                            }
                            // $pathzip = '../Services/Docs_Inactivezip/';
                            rmdir($pathdir);
                            mkdir($pathdir);
                        }

                        // mkdir($pathdir);
                        foreach ($jaf_file_attachments as $key) {
                            $file_name = basename($key);
                            if (file_put_contents('Docs_Inactive/' . $file_name, file_get_contents($key))) {
                                // echo "File downloaded successfully";
                            } else {
                                // echo "File downloading failed.";
                            }
                        }
                        // Enter the name of directory

                        $pathdir = "Docs_Inactive/";
                        // Enter the name to creating zipped directory
                        $pathzip = 'Docs_Inactivezip/';
                        // if (!is_dir($pathzip)) {
                        //     mkdir($pathzip);
                        // } else {
                        //     //Get a list of all of the file names in the folder.
                        //     $files = glob($pathzip . '/*');

                        //     //Loop through the file list.
                        //     foreach ($files as $file) {
                        //         //Make sure that this is a file and not a directory.
                        //         if (is_file($file)) {
                        //             //Use the unlink function to delete the file.
                        //             unlink($file);
                        //         }
                        //     }
                        //     // $pathzip = '../Services/Docs_Inactivezip/';
                        //     rmdir($pathzip);
                        //     mkdir($pathzip);
                        // }

                        $zipcreated = "Docs_Inactivezip/$emp.zip";

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
                            // print_r($zip);
                        }


                        $curl = curl_init();
                        $data['reference_number'] = $reference_number;
                        $data['client_emp_code'] = $emp;
                        $data['first_name'] = $first_name;
                        $data['father_name'] = $father_name;
                        $data['phone'] = $phone;
                        $data['dob'] = $dob;
                        $data['gender'] = $gender;
                        $data['sla_id'] = $sla_id;
                        $data['jaf_access'] = $jaf_access;
                        $data['jaf_file_attachments[ ]'] = new CURLFILE($zip->filename);

                        //$data['jaf_file_attachments[]'] = $str;
                        echo "<pre>";
                        print_r($data);
                        //die;

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://mybcd.live/api/client/candidate/upload',
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
                            ),
                        ));
                        print_r($curl);
                        $response = curl_exec($curl);
                        curl_close($curl);

                        echo $response;
                        die;
                        $res = json_decode($response);
                        //print_r($res);
                        if ($res->status == 'True') {
                            print_r($res->candidate_id);
                            $updateres = 'update bgv set Candidate_id="' . $res->candidate_id . '", flag=1 where EmployeeID="' . $emp . '" ';
                            $myDB = new MysqliDb();
                            $resultBy = $myDB->query($updateres);
                        } else {
                            echo $res->errors->user;
                            $update = 'Insert into bgv_api_lock (Emp_id,response)values("' . $emp . '","' . $res->message . '")';
                            $myDB = new MysqliDb();
                            $resu = $myDB->query($update);
                        }
                    }
                }
            }
        }
    }
}
