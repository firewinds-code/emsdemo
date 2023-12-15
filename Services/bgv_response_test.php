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
//ini_set('display_errors', '0');
#$select_emp = "SELECT * from bgv where flag=1 and bgv_flag=0 and bgv_vender='1' and cast(Modifiedon as date)>='2022-09-23'";
$select_emp = "SELECT * from bgv where EmployeeID='CE0623952841'";
$myDB =  new MysqliDb();
$result = $myDB->query($select_emp);
$count = 0;
// print_r($result);
$mysql_error = $myDB->getLastError();
if (empty($mysql_error)) {
    foreach ($result as $key => $value) {
        $count++;
        echo $Emp = $value['EmployeeID'];

        $candidate_id = $value['Candidate_id'];
        $candidate = substr($candidate_id, 10);
        $reference_number = 'COGE-0000001831';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://my-bcd.com/api/client/report/download',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "candidate_id":"' . $candidate . '",
           "reference_number":"COGE-0000001831"
        }',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response . '<br/>';
        die;
        $res = json_decode($response);

        if ($res->success == '1') {

            print_r($res->download_link);
            die;
            $pathdir = $res->download_link;
            $name = pathinfo($pathdir, PATHINFO_FILENAME);
            $ext  = pathinfo($pathdir, PATHINFO_EXTENSION);
            $file_name = basename($Emp . 'BGverification_' . date('mdY_s.') . $ext);
            $INTID = "select INTID,loc from EmpID_Name where EmpID='" . $Emp . "'";
            $myDB = new MysqliDb();
            $resu = $myDB->query($INTID);
            $INTIDS = $resu[0]['INTID'];
            $location = $resu[0]['loc'];
            $pathdir = str_replace(" ", "%20", $pathdir);
            //echo file_get_contents($pathdir);


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


            //$insert_doc = 'Insert into doc_details (doc_type,doc_stype,dov_value,doc_file,EmployeeID,INTID,createdby)values("BGV Report","BG verification","1","' . $file_name . '","' . $Emp . '","' . $INTIDS . '","SERVER")';
            $insert_doc = "call insert_bgv('" . $file_name . "','" . $Emp . "','" . $INTIDS . "')";
            $myDB = new MysqliDb();
            $resultss = $myDB->query($insert_doc);

            // die;
        } else {
            echo "bgv is not complete";
        }
        echo '<br/>';
    }
    echo '<br/><br/><br/><br/><br/>' . $count . ' Complete';
}
