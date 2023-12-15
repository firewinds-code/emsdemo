<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$myDB = new MysqliDb();


ini_set('memory_limit', '900M');

for ($flg = 0; $flg <= 1; $flg++) {
    if ($flg == "0") // 1 indicates current data
    {
        $sql = "select EmployeeID, InTime, DateOn, OutTime, work_from , type_ from roster_temp order by EmployeeID ";
    } else if ($flg == "1") // 2 indicates historical data
    {
        $sql = "select EmployeeID, InTime, DateOn, OutTime, work_from , type_ from roster_temp_history_new order by EmployeeID ";
    }
    $data = $myDB->query($sql);
    // echo "Size -->".array_size($data);
    //     echo  mb_strlen(serialize((array)$data), '8bit');
    //    die;
    $finalres = array();
    $res = array();
    //echo count($data);
    for ($arr = 0; $arr < count($data); $arr++) {

        $emp_id_current = $data[$arr]["EmployeeID"];

        if (count($res)) {

            $index = count($res) - 1;

            if ($res[$index]["EmployeeID"] != $emp_id_current) {


                for ($res_arr = 0; $res_arr < count($res); $res_arr++) {
                    array_push($finalres, $res[$res_arr]);
                }
                $res = array();
                $cur_data = array();
                $MonthYear_current = substr($data[$arr]["DateOn"], 0, 7);

                $cur_data["EmployeeID"] = $data[$arr]["EmployeeID"];
                $cur_data["type_"] = $data[$arr]["type_"];
                $cur_data["MonthYear"] = $MonthYear_current;
                $Day =  (int)substr($data[$arr]["DateOn"], -2);
                $cur_data[$Day] = $data[$arr]["InTime"] . "-" . $data[$arr]["OutTime"] . " [" . $data[$arr]["work_from"] . "]";
                array_push($res, $cur_data);
            } else {

                $MonthYear_res = array_column($res, "MonthYear");


                $MonthYear_current = substr($data[$arr]["DateOn"], 0, 7);

                if (!in_array($MonthYear_current, $MonthYear_res)) {

                    $cur_data = array();
                    $cur_data["EmployeeID"] = $data[$arr]["EmployeeID"];
                    $cur_data["type_"] = $data[$arr]["type_"];
                    $cur_data["MonthYear"] = $MonthYear_current;
                    $Day =  (int)substr($data[$arr]["DateOn"], -2);
                    $cur_data[$Day] = $data[$arr]["InTime"] . "-" . $data[$arr]["OutTime"] . " [" . $data[$arr]["work_from"] . "]";
                    array_push($res, $cur_data);
                } else {

                    $update_index =  array_search($MonthYear_current, $MonthYear_res);
                    $Day =  (int)substr($data[$arr]["DateOn"], -2);
                    $res[$update_index][$Day] = $data[$arr]["InTime"] . "-" . $data[$arr]["OutTime"] . " [" . $data[$arr]["work_from"] . "]";
                }
            }
        } else {

            $cur_data = array();
            $MonthYear_current = substr($data[$arr]["DateOn"], 0, 7);

            $cur_data["EmployeeID"] = $data[$arr]["EmployeeID"];
            $cur_data["type_"] = $data[$arr]["type_"];
            $cur_data["MonthYear"] = $MonthYear_current;
            $Day =  (int)substr($data[$arr]["DateOn"], -2);
            $cur_data[$Day] = $data[$arr]["InTime"] . "-" . $data[$arr]["OutTime"] . " [" . $data[$arr]["work_from"] . "]";
            array_push($res, $cur_data);
        }
    }
    //echo count($res);
    for ($res_arr = 0; $res_arr < count($res); $res_arr++) {
        array_push($finalres, $res[$res_arr]);
    }

    $finalresult = array();
    for ($arr = 0; $arr < count($finalres); $arr++) {
        $temparray = array();
        $current_arr = $finalres[$arr];
        $temparray["EmployeeID"] = $current_arr["EmployeeID"];
        $temparray["type_"] = $current_arr["type_"];
        $month_number = (int)substr($current_arr["MonthYear"], 5, 7);

        if ($month_number == 1) {
            $month = 'Jan';
        } else if ($month_number == 2) {
            $month = 'Feb';
        } else if ($month_number == 3) {
            $month = 'Mar';
        } else if ($month_number == 4) {
            $month = 'Apr';
        } else if ($month_number == 5) {
            $month = 'May';
        } else if ($month_number == 6) {
            $month = 'Jun';
        } else if ($month_number == 7) {
            $month = 'Jul';
        } else if ($month_number == 8) {
            $month = 'Aug';
        } else if ($month_number == 9) {
            $month = 'Sep';
        } else if ($month_number == 10) {
            $month = 'Oct';
        } else if ($month_number == 11) {
            $month = 'Nov';
        } else if ($month_number == 12) {
            $month = 'Dec';
        }

        $year = (int)substr($current_arr["MonthYear"], 0, 4);
        $temparray["Month"] = $month;
        $temparray["Year"] = $year;

        for ($number = 1; $number <= 31; $number++) {
            $temparray[$number] = array_key_exists($number, $current_arr) ? $current_arr[$number] : "-";
        }

        array_push($finalresult, $temparray);
    }

    if (is_array($finalresult)) {
        $DataArr = array();

        foreach ($finalresult as $row) {

            $EmployeeID = $row["EmployeeID"];
            $type_ = $row["type_"];
            $Month = $row["Month"];
            $Year = $row["Year"];
            $D1 = $row["1"];
            $D2 = $row["2"];
            $D3 = $row["3"];
            $D4 = $row["4"];
            $D5 = $row["5"];
            $D6 = $row["6"];
            $D7 = $row["7"];
            $D8 = $row["8"];
            $D9 = $row["9"];
            $D10 = $row["10"];
            $D11 = $row["11"];
            $D12 = $row["12"];
            $D13 = $row["13"];
            $D14 = $row["14"];
            $D15 = $row["15"];
            $D16 = $row["16"];
            $D17 = $row["17"];
            $D18 = $row["18"];
            $D19 = $row["19"];
            $D20 = $row["20"];
            $D21 = $row["21"];
            $D22 = $row["22"];
            $D23 = $row["23"];
            $D24 = $row["24"];
            $D25 = $row["25"];
            $D26 = $row["26"];
            $D27 = $row["27"];
            $D28 = $row["28"];
            $D29 = $row["29"];
            $D30 = $row["30"];
            $D31 = $row["31"];

            $DataArr[] = "('$EmployeeID', '$Month', '$Year', '$type_', '$D1', '$D2', '$D3', '$D4', '$D5', '$D6', '$D7', '$D8', '$D9', '$D10', '$D11', '$D12', '$D13', '$D14', '$D15', '$D16', '$D17', '$D18', '$D19', '$D20', '$D21', '$D22', '$D23', '$D24', '$D25', '$D26', '$D27', '$D28', '$D29', '$D30', '$D31')";
        }
        if ($flg == 0) {
            $sql = "call truncate_roster_master();";
            $data = $myDB->query($sql);
        }

        $sql = "insert into roster_master (EmployeeID,Month,Year,type_, D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, D12, D13, D14, D15, D16, D17, D18, D19, D20, D21, D22, D23, D24, D25, D26, D27, D28, D29, D30, D31) values";
        $sql .= implode(',', $DataArr);

        $data = $myDB->query($sql);
        echo count($DataArr);
        echo '<br/>';
    }
    echo 'Complete';
    echo '<br/>';
}
