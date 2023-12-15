<?php

require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

$mysquery = "select * from transfer_docs where empid='CEV122178400'";
$myDB = new MysqliDb();
$dsEmpID = $myDB->rawQuery($mysquery);
$mysql_error = $myDB->getLastError();
//$testrowCount = $myDB->count;
$filename = "";
if (empty($mysql_error) && $myDB->count > 0) {
    foreach ($dsEmpID as $key => $value) {
        $from_loc = $value['from_loc'];
        $to_loc = $value['to_loc'];

        if ($from_loc == "1") {
            $dir_location = 'Noida';
        } else if ($from_loc == "2") {
            $dir_location = 'Mumbai/';
        } else if ($from_loc == "3") {
            $dir_location = 'Meerut/';
        } else if ($from_loc == "4") {
            $dir_location = "Bareilly/";
        } else if ($from_loc == "5") {
            $dir_location = "Vadodara/";
        } else if ($from_loc == "6") {
            $dir_location = "Mangalore/";
        } else if ($from_loc == "7") {
            $dir_location = "Bangalore/";
        } else if ($from_loc == "8") {
            $dir_location = "Nashik/";
        } else if ($from_loc == "9") {
            $dir_location = "Anantapur/";
        } else if ($from_loc == "10") {
            $dir_location = "Gurgaon/";
        } else if ($from_loc == "11") {
            $dir_location = "Hyderabad/";
        }

        if ($to_loc == "1") {
            $dir_location1 = 'Noida';
        } else if ($to_loc == "2") {
            $dir_location = 'Mumbai/';
        } else if ($to_loc == "3") {
            $dir_location1 = 'Meerut/';
        } else if ($to_loc == "4") {
            $dir_location1 = "Bareilly/";
        } else if ($to_loc == "5") {
            $dir_location1 = "Vadodara/";
        } else if ($to_loc == "6") {
            $dir_location1 = "Mangalore/";
        } else if ($to_loc == "7") {
            $dir_location1 = "Bangalore/";
        } else if ($to_loc == "8") {
            $dir_location1 = "Nashik/";
        } else if ($to_loc == "9") {
            $dir_location1 = "Anantapur/";
        } else if ($to_loc == "10") {
            $dir_location1 = "Gurgaon/";
        } else if ($to_loc == "11") {
            $dir_location1 = "Hyderabad/";
        }

        ///=============Get QrCode files===============///

        $strsql = $myDB->rawQuery('select QrCode from employee_qrcode where employeeid="' . $value['EmpID'] . '" limit 1');
        if ($myDB->count > 0) {
            $filename = $strsql[0]['QrCode'];

            $source_dir = ROOT_PATH . 'QrSetup/' . $dir_location;
            $target_dir = ROOT_PATH . 'QrSetup/' . $dir_location1;
            //echo $target_dir;
            echo $source_file = $source_dir . $filename;
            echo '<br/>';
            echo $target_file = $target_dir . $filename;

            if (copy($source_file, $target_file)) {
                echo "QrCode file copied";
            } else {
                echo "QrCode file not copied";
            }
            echo "<br>";
        }

        ///=============Get QrCode files===============///

        if ($from_loc == "1" || $from_loc == "2") {
            $dir_location = '';
        } else if ($from_loc == "6") {
            $dir_location = "Manglore/";
        }


        if ($to_loc == "1" || $to_loc == "2") {
            $dir_location1 = '';
        } else if ($to_loc == "6") {
            $dir_location1 = "Manglore/";
        }
        ///=============Get Images files===============///

        $strsql = $myDB->rawQuery('select img from personal_details where employeeid="' . $value['EmpID'] . '"');
        if ($myDB->count > 0) {
            $filename = $strsql[0]['img'];


            $source_dir = ROOT_PATH . $dir_location . 'Images/';
            $target_dir = ROOT_PATH . $dir_location1 . 'Images/';
            //echo $target_dir;
            echo $source_file = $source_dir . $filename;
            echo '<br/>';
            echo $target_file = $target_dir . $filename;

            if (copy($source_file, $target_file)) {
                echo "Image file copied";
            } else {
                echo "Image file not copied";
            }
            echo "<br>";
        }

        ///=============Get Images files===============///

    }
}
