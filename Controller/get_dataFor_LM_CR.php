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

$DateTo = "../Export_Report/" . $month . " " . $year;
$DateToRes =  $month . " " . $year;
// $DateTo = date('F Y', strtotime("last day of previous month"));
$curr_date = date('Y-m-d');
$curr_month = date('F', strtotime($curr_date));
if ($month != $curr_month || !is_dir($DateTo)) {
    if ($month == $curr_month && $year) {
        // echo "this is current month";
        if ($center == "1") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Noida.csv';
        } else if ($center == "2") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Mumbai.csv';
        } else if ($center == "3") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Meerut.csv';
        } else if ($center == "4") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Bareilly.csv';
        } else if ($center == "5") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Vadodara.csv';
        } else if ($center == "6") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Mangalore.csv';
        } else if ($center == "7") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Bangalore.csv';
        } else if ($center == "8") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Nashik.csv';
        } else if ($center == "9") {
            $result['data'] =  URL . 'Export_Report/automail_payroll_Anantapur.csv';
        }
    } else if (is_dir($DateTo)) {
        if ($center == "1") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Noida.csv';
        } else if ($center == "2") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Mumbai.csv';
        } else if ($center == "3") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Meerut.csv';
        } else if ($center == "4") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Bareilly.csv';
        } else if ($center == "5") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Vadodara.csv';
        } else if ($center == "6") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Mangalore.csv';
        } else if ($center == "7") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Bangalore.csv';
        } else if ($center == "8") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Nashik.csv';
        } else if ($center == "9") {
            $result['data'] = URL . 'Export_Report/' . $DateToRes . '/automail_payroll_lm_Anantapur.csv';
        }
    }
} else {
    $result['data'] = "";
}
echo $result['data'];
