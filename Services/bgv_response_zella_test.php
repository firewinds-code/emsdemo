<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
// require(ROOT_PATH . 'AppCode/nHead.php');
// $curl = curl_init();

// curl_setopt_array($curl, array(
//     CURLOPT_URL => 'https://zella.in/zap/admin/App/getFileLink',
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => array('empId' => '324'),
//     CURLOPT_HTTPHEADER => array(
//         'API_KEY: 123456789',
//         'Cookie: ci_session=29dccafcbed1b4a5f52cc3c769ca1839b4be3fc1'
//     ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// echo $response;
// die;
// Global variable used in Page Cycle
ini_set('display_errors', '0');
$select_emp = "SELECT * from bgv where flag=1 and bgv_vender='2' and EmployeeID='CE0623952989'";
$myDB =  new MysqliDb();
$result = $myDB->query($select_emp);
// print_r($result);
$mysql_error = $myDB->getLastError();
if (empty($mysql_error)) {
    foreach ($result as $key => $value) {
        $Emp = $value['EmployeeID'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://Zellainformation.com/zap/admin/App/getFileLink',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'empId' => $Emp
            ),
            CURLOPT_HTTPHEADER => array(
                'API_KEY: 123456789',
                'Cookie: ci_session=2040205024bdd17e4273941f2c95dc4a665d1a75'
            ),
        ));

        $response = curl_exec($curl);
        // print_r($response);
        // die;
        curl_close($curl);
        //$response = preg_replace("/\r|\n/", "", $response);
        $response = trim($response);
        // echo $response . '<br/>';
        // echo strlen($response);
        // die;
        // $res = json_decode($response);

        if ($response == 'Report Not Found' || $response == '') {
            echo 'BGV not complete.';
        } else if ($response != '') {
            // echo '<br/>';
            // print_r($response);


            $pathdir = $response;
            if (trim($pathdir) != '' && substr($pathdir, strlen($pathdir) - 4, 4) == '.pdf') {
                // echo 'aaaa' . $pathdir . '---';
                // die;
                $name = pathinfo($pathdir, PATHINFO_FILENAME);
                $ext  = pathinfo($pathdir, PATHINFO_EXTENSION);
                $file_name = basename($Emp . 'BGverification_' . date('mdY_s.') . $ext);
                $INTID = "select INTID,loc from EmpID_Name where EmpID='" . $Emp . "'";
                $myDB = new MysqliDb();
                $resu = $myDB->query($INTID);
                $INTIDS = $resu[0]['INTID'];
                $location = $resu[0]['loc'];
                //echo $pathdir;
                // echo file_get_contents($pathdir);
                // die;

                if ($location == "1" || $location == "2") {
                    if (copy($pathdir, "../Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                } else if ($location == "3") {

                    if (copy($pathdir, "../Meerut/Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                } else if ($location == "4") {
                    if (copy($pathdir, "../Bareilly/Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                } else if ($location == "5") {
                    if (copy($pathdir, "../Vadodara/Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                } else if ($location == "6") {
                    if (copy($pathdir, "../Manglore/Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                } else if ($location == "7") {
                    if (copy($pathdir, "../Bangalore/Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                } else if ($location == "8") {
                    if (copy($pathdir, "../Nashik/Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                } else if ($location == "9") {
                    if (copy($pathdir, "../Anantapur/Docs/BGV/" . $file_name)) {
                        echo "File downloaded successfully";
                    }
                }

                $updateres = 'update bgv set  bgv_flag=1,bgv_uploaded=now() where EmployeeID="' . $Emp . '"';
                $myDB = new MysqliDb();
                $resultBy = $myDB->query($updateres);


                // $insert_doc = 'Insert into doc_details (doc_type,doc_stype,dov_value,doc_file,EmployeeID,INTID,createdby)values("BGV Report","BG verification","1","' . $file_name . '","' . $Emp . '","' . $INTIDS . '","SERVER")';
                echo $insert_doc = "call insert_bgv('" . $file_name . "','" . $Emp . "','" . $INTIDS . "')";
                $myDB = new MysqliDb();
                $resultss = $myDB->query($insert_doc);
                // die;
            } else {
                echo 'file not found';
            }
        }
    }
}
