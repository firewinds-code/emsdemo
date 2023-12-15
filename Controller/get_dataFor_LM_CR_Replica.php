<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 0);
$center = $_REQUEST['center'];
$month = $_REQUEST['month'];
$year = $_REQUEST['year'];

$DateTo = "../Export_Report/" . $month . "_" . $year;

// $url = "http://172.104.207.201/erpm/Controller/get_Directory.php?dir= $DateTo";
// // echo $url;
// // die;
// $int_url = '';
// // $intid = $__interview_id;
// $int_url = "http://172.104.207.201/erpm/Controller/get_Directory.php?dir= $DateTo";
// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, $int_url);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_HEADER, false);
// echo $data = curl_exec($curl);
// echo 'sdfdfdf';
// die;

// $salary_array = json_decode($data);

// //print_r($data_array);
// if (count($salary_array) > 0) {
// }


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://172.104.207.201/erpm/Controller/get_Directory.php?dir=' . $DateTo,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Cookie: PHPSESSID=3khctb0uclpc21ftqefiba2nb8'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
