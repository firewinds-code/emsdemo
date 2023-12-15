<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

ini_set('display_errors', '0');
$select = "SELECT Candidate_id,EmployeeID from bgv where flag=1 and bgv_flag=0 and bgv_vender='3'";
$myDB =  new MysqliDb();
$result = $myDB->query($select);
$mysql_error = $myDB->getLastError();

if (empty($mysql_error)) {
    foreach ($result as $key => $value) {
        $Candidate_id = $value['Candidate_id'];
        $Emp = $value['EmployeeID'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://mistitservices.in/mist-it/api/resource/candid_list?candid_id=$Candidate_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: COGENTMISPL',
                'Cookie: HttpOnly'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);
        if ($res->error_sts == '400' || $res->data_list->report_url == '') {
            echo 'bgv not complete';
        } else {
            // print_r($res);
            // die;
            // print_r($res->data_list->report_url);
            $pathdir = $res->data_list->report_url;
            // $ext  = pathinfo($pathdir, PATHINFO_EXTENSION);
            $file_name = basename($Emp . 'BGverification_' . date('mdY_s.') . 'pdf');

            $INTID = "select INTID,location from personal_details where EmployeeID='" . $Emp . "'";
            $myDB = new MysqliDb();
            $resu = $myDB->query($INTID);
            $INTIDS = $resu[0]['INTID'];
            $location = $resu[0]['location'];

            if ($location == "1" || $location == "2") {
                if (file_put_contents("../Docs/BGV/" . $file_name, file_get_contents($pathdir))) {
                    // echo "File downloaded successfully";
                }
            } else if ($location == "3") {
                if (file_put_contents("../Meerut/Docs/BGV/" . $file_name, file_get_contents($pathdir))) {
                    // echo "File downloaded successfully";
                }
            } else if ($location == "4") {
                if (file_put_contents("../Bareilly/Docs/BGV/" . $file_name, file_get_contents($pathdir))) {
                    // echo "File downloaded successfully";
                }
            } else if ($location == "5") {
                if (file_put_contents("../Vadodara/Docs/BGV/" . $file_name, file_get_contents($pathdir))) {
                    // echo "File downloaded successfully";
                }
            } else if ($location == "6") {
                if (file_put_contents("../Manglore/Docs/BGV/" . $file_name, file_get_contents($pathdir))) {
                    // echo "File downloaded successfully";
                }
            } else if ($location == "7") {
                if (file_put_contents("../Bangalore/Docs/BGV/" . $file_name, file_get_contents($pathdir))) {
                    // echo "File downloaded successfully";
                }
            } else if ($location == "8") {
                if (copy($pathdir, "../Nashik/Docs/BGV/" . $file_name)) {
                    echo "File downloaded successfully";
                }
            } else if ($location == "9") {
                if (copy($pathdir, "../Anantapur/Docs/BGV/" . $file_name)) {
                    echo "File downloaded successfully";
                }
            } else if ($location == "10") {
                if (copy($pathdir, "../Gurgaon/Docs/BGV/" . $file_name)) {
                    echo "File downloaded successfully";
                }
            } else if ($location == "11") {
                if (copy($pathdir, "../Hyderabad/Docs/BGV/" . $file_name)) {
                    echo "File downloaded successfully";
                }
            }

            $updateres = 'update bgv set  bgv_flag=1,bgv_uploaded=now() where EmployeeID="' . $Emp . '"';
            $myDB = new MysqliDb();
            $resultBy = $myDB->query($updateres);

            //$insert_doc = 'Insert into doc_details (doc_type,doc_stype,dov_value,doc_file,EmployeeID,INTID,createdby)values("BGV Report","BG verification","1","' . $file_name . '","' . $Emp . '","' . $INTIDS . '","SERVER")';
            $insert_doc = "call insert_bgv('" . $file_name . "','" . $Emp . "','" . $INTIDS . "')";
            $myDB = new MysqliDb();
            $resultss = $myDB->query($insert_doc);
        }
    }
}
