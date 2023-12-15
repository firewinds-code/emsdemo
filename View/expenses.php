<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
include "../Readpdf/vendor/autoload.php";
// echo 'ffffff';
// die;
$parser = new \Smalot\PdfParser\Parser();

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$uploadOk = 1;
$flag = 0;
$EmployeeID = clean($_SESSION['__user_logid']);
$empName = clean($_SESSION['__user_Name']);


$myDB = new MysqliDb();
$hotelSql = "select id,location from location_master";
$resulthotel = $myDB->query($hotelSql);

if (isset($_POST['btn_food'])) //for food submit
{
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {

        $foodDate = cleanUserInput($_POST['food_date']);
        $amount = cleanUserInput($_POST['amount']);
        $recptNo = cleanUserInput($_POST['receipt_no']);
        $remarks = cleanUserInput($_POST['remarks']);
        if (isset($_FILES["receipt_image"]) && !empty($_FILES["receipt_image"]["name"])) {
            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseFood/';

            $target_file = $target_dir . basename($_FILES["receipt_image"]["name"]);
            // $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['receipt_image']['tmp_name']);
            // Check file size
            if ($_FILES['receipt_image']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }

            // Allow certain file formats
            // if ($FileType != "jpeg" && $FileType != "jpg" && $FileType != "png" && $FileType != "pdf" && $FileType != "msg") {
            //     echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
            //     $uploadOk = 0;
            // }

            $actual_pdf_file = $_FILES["receipt_image"]["name"];
            $allowedExtentions = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension, $allowedExtentions) || !in_array($fileType, $allowedFileTypes)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension == "pdf") {
                $pdf = $parser->parseFile($_FILES['receipt_image']['tmp_name']);
                $text = $pdf->getText();

                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["receipt_image"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["receipt_image"]["name"]), PATHINFO_EXTENSION);
                    $food_fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $food_fileNameFinal);
                    if (file_exists(ROOT_PATH . 'ExpenseFood/' . $food_fileNameFinal)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {

                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }
        if ($uploadOk == 1) {
            $insert_food = 'INSERT INTO expense_food(EmployeeID,empName,date,amount,receipt_no,receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus) VALUES(?,?,?,?,?,?,?,"Pending","FoodRequest","Pending","Pending","Pending") ';


            $stmt = $conn->prepare($insert_food);
            $stmt->bind_param("sssisss", $EmployeeID, $empName, $foodDate, $amount, $recptNo, $food_fileNameFinal, $remarks);
            $Foodinsert = $stmt->execute();
            // print_r($insert);
            if ($stmt->affected_rows === 1) {
                echo "<script>$(function(){toastr.success('Record Saved Successfully'); }); </script>";
                $show = "Food Expenses";
            } else {
                echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
            }
        }
    }
}



$receipt_image = '';
$returnDate = '';
$car_km_receipt = isset($car_km_receipt) && $car_km_receipt['car_km_receipt'] ? $car_km_receipt['car_km_receipt'] : '';
$car_toll_receipt = isset($car_toll_receipt) ? $car_toll_receipt : '';
$car_parking_receipt = isset($car_parking_receipt) ? $car_parking_receipt : '';
$date = cleanUserInput($_POST['travel_date']);
$placefrom = cleanUserInput($_POST['placeFrom']);
$placeto = cleanUserInput($_POST['placeTO']);
$modeoftravel = cleanUserInput($_POST['modeOftravel']);
// echo "fdfsfdsfdfsdsssssssssssssssssssssssssssssssssssssssssssssssss" . $travelAmount = $_POST['amount'];
// $receipt_no='';
$receipt_no = cleanUserInput($_POST['receipt_no']);
// $receipt_image = $_POST['receipt_image'];
$remarks = cleanUserInput($_POST['remarks']);
$reqStatus = 'Pending';
$reviewerStatus = 'Pending';
$approverStatus = 'Pending';
$mgrStatus = 'Pending';
$reqType = 'TravelRequest';
$returnDate = cleanUserInput($_POST['returnDate']);
$car_km = cleanUserInput($_POST['car_km']);


if (isset($_POST['expenses1'])) //for travel submit
{
    $show = cleanUserInput($_POST['expenses1']);
}


if (isset($_POST['btn_travel'])) //for travel submit
{
    if (isset($_POST["token1"]) && isset($_SESSION["token1"]) && $_POST["token1"] == $_SESSION["token1"]) {
        if (isset($_FILES["receipt_image"]) && !empty($_FILES["receipt_image"]["name"])) {

            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseTravel/';

            $target_file = $target_dir . basename($_FILES["receipt_image"]["name"]);
            $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['receipt_image']['tmp_name']);
            // Check file size
            if ($_FILES['receipt_image']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }
            $actual_pdf_file = $_FILES["receipt_image"]["name"];
            $allowedExtentions = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension, $allowedExtentions) || !in_array($fileType, $allowedFileTypes)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension == "pdf") {
                $pdf = $parser->parseFile($_FILES['receipt_image']['tmp_name']);
                $text = $pdf->getText();
                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["receipt_image"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["receipt_image"]["name"]), PATHINFO_EXTENSION);
                    $receipt_image = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $receipt_image);
                    if (file_exists(ROOT_PATH . 'ExpenseTravel/' . $receipt_image)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {
                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }

        if ((isset($_FILES['car_km_receipt']['name']) ? $_FILES['car_km_receipt']['name'] : "")  && $_POST['modeOftravel'] == 'Car') {
            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseTravel/';

            $target_file = $target_dir . basename($_FILES["car_km_receipt"]["name"]);
            $fileExtension1 = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType1 = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['car_km_receipt']['tmp_name']);
            // $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            // Check file size
            if ($_FILES['car_km_receipt']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            $actual_pdf_file = $_FILES["car_km_receipt"]["name"];
            $allowedExtentions1 = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes1 = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension1, $allowedExtentions1) || !in_array($fileType1, $allowedFileTypes1)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension1 == "pdf") {
                $pdf = $parser->parseFile($_FILES['car_km_receipt']['tmp_name']);
                $text1 = $pdf->getText();
                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text1);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text1)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["car_km_receipt"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["car_km_receipt"]["name"]), PATHINFO_EXTENSION);
                    $car_km_receipt = $EmployeeID . '_carKm_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $car_km_receipt);
                    if (file_exists(ROOT_PATH . 'ExpenseTravel/' . $car_km_receipt)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {
                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }
        if ((isset($_FILES['car_parking_receipt']['name']) ? $_FILES['car_parking_receipt']['name'] : "") && $_POST['modeOftravel'] == 'Car') {

            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseTravel/';
            $target_file = $target_dir . basename($_FILES["car_parking_receipt"]["name"]);
            $fileExtension2 = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType2 = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['car_parking_receipt']['tmp_name']);
            // $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            // Check file size
            if ($_FILES['car_parking_receipt']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            $actual_pdf_file = $_FILES["car_parking_receipt"]["name"];
            $allowedExtentions2 = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes2 = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension2, $allowedExtentions2) || !in_array($fileType2, $allowedFileTypes2)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension2 == "pdf") {
                $pdf = $parser->parseFile($_FILES['car_parking_receipt']['tmp_name']);
                $text2 = $pdf->getText();
                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text2);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text2)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["car_parking_receipt"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["car_parking_receipt"]["name"]), PATHINFO_EXTENSION);
                    $car_parking_receipt = $EmployeeID . '_parking_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $car_parking_receipt);
                    if (file_exists(ROOT_PATH . 'ExpenseTravel/' . $car_parking_receipt)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {
                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }
        if ((isset($_FILES['car_toll_receipt']['name']) ? $_FILES['car_toll_receipt']['name'] : "") && $_POST['modeOftravel'] == 'Car') {

            // $fileName4 = $_FILES['car_toll_receipt']['name'];
            // $tempPath4 = $_FILES['car_toll_receipt']['tmp_name'];
            // $dir_locationToSave4 = __DIR__ . '/../ExpenseTravel/';
            // $fileExt4 = strtolower(pathinfo($fileName4, PATHINFO_EXTENSION)); // get  extension
            // $car_toll_receipt = $EmployeeID . '_toll_' . date('Y-m-d_His') . '.' . $fileExt4;
            // move_uploaded_file($tempPath4, $dir_locationToSave4 . $car_toll_receipt);

            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseTravel/';

            $target_file = $target_dir . basename($_FILES["car_toll_receipt"]["name"]);
            // $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileExtension3 = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType3 = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['car_toll_receipt']['tmp_name']);
            // Check file size
            if ($_FILES['car_toll_receipt']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            // if ($FileType != "jpeg" && $FileType != "jpg" && $FileType != "png" && $FileType != "pdf" && $FileType != "msg") {
            //     echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
            //     $uploadOk = 0;
            // }
            $actual_pdf_file = $_FILES["car_toll_receipt"]["name"];
            $allowedExtentions3 = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes3 = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension3, $allowedExtentions3) || !in_array($fileType3, $allowedFileTypes3)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension3 == "pdf") {
                $pdf = $parser->parseFile($_FILES['car_toll_receipt']['tmp_name']);
                $text3 = $pdf->getText();
                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text3);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text3)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["car_toll_receipt"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["car_toll_receipt"]["name"]), PATHINFO_EXTENSION);
                    $car_toll_receipt = $EmployeeID . '_toll_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $car_toll_receipt);
                    if (file_exists(ROOT_PATH . 'ExpenseTravel/' . $car_toll_receipt)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {
                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }
        if ($uploadOk == 1) {
            if ($_POST['modeOftravel'] == "Car") {

                $insert_travel = "INSERT INTO expense_travel(EmployeeID,empName,date,placeFrom,placeTo,modeOftravel,amount, receipt_no, receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus,returnDate,car_km,car_km_receipt,car_toll_receipt,car_parking_receipt) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                $stmt1 = $conn->prepare($insert_travel);
                $stmt1->bind_param("ssssssisssssssssisss", $EmployeeID, $empName, $date, $placefrom, $placeto, $modeoftravel, $_POST['amount'], $receipt_no, $receipt_image, $remarks, $reqStatus, $reqType, $reviewerStatus, $mgrStatus, $approverStatus, $returnDate, $car_km, $car_km_receipt, $car_toll_receipt, $car_parking_receipt);
            } else {

                $insert_travel = "INSERT INTO expense_travel(EmployeeID,empName,date,placeFrom,placeTo,modeOftravel,amount, receipt_no, receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus,returnDate,car_km,car_km_receipt,car_toll_receipt,car_parking_receipt) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                $stmt1 = $conn->prepare($insert_travel);
                $stmt1->bind_param("ssssssisssssssssisss", $EmployeeID, $empName, $date, $placefrom, $placeto, $modeoftravel, $_POST['amount1'], $receipt_no, $receipt_image, $remarks, $reqStatus, $reqType, $reviewerStatus, $mgrStatus, $approverStatus, $returnDate, $car_km, $car_km_receipt, $car_toll_receipt, $car_parking_receipt);
            }

            $Travelinsert = $stmt1->execute();
            if ($stmt1->affected_rows === 1) {
                echo "<script>$(function(){toastr.success('Record Saved Successfully'); }); </script>";
                $show = "Travel Expenses";
                // echo "<script>jQuery('body').load(window.location.href);</script>";
            } else {
                echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
            }
        }
    }
}

if (isset($_POST['btn_hotel'])) //for hotel submit
{
    if (isset($_POST["token2"]) && isset($_SESSION["token2"]) && $_POST["token2"] == $_SESSION["token2"]) {
        if (isset($_FILES["receipt_image"]) && !empty($_FILES["receipt_image"]["name"])) {
            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseHotel/';

            $target_file = $target_dir . basename($_FILES["receipt_image"]["name"]);
            // $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['receipt_image']['tmp_name']);
            // Check file size
            if ($_FILES['receipt_image']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            $actual_pdf_file = $_FILES["receipt_image"]["name"];
            $allowedExtentions = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension, $allowedExtentions) || !in_array($fileType, $allowedFileTypes)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension == "pdf") {
                $pdf = $parser->parseFile($_FILES['receipt_image']['tmp_name']);
                $text = $pdf->getText();
                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["receipt_image"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["receipt_image"]["name"]), PATHINFO_EXTENSION);
                    $hotel_fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $hotel_fileNameFinal);
                    if (file_exists(ROOT_PATH . 'ExpenseHotel/' . $hotel_fileNameFinal)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {

                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }
        if ($_POST['clienthotel'] == "Other") {
            $clientval = $_POST['otherclient'];
        } else {
            $clientval = $_POST['clienthotel'];
        }
     if ($uploadOk == 1) {
            $insert_hotel = 'INSERT INTO expense_hotel(EmployeeID,empName,dateFrom,dateTo,noOfdays,location,client_name,hotelName,amount,receipt_no,receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,"Pending","HotelRequest","Pending","Pending","Pending") ';

            $stmt3 = $conn->prepare($insert_hotel);
            $stmt3->bind_param("ssssiissiiss", $EmployeeID, $empName, $_POST['dateFrom'], $_POST['dateTo'], $_POST['noOfdays'],$_POST['lochotel'],$clientval, $_POST['hotelName'], $_POST['amount'], $_POST['receipt_no'], $hotel_fileNameFinal, $_POST['remarks']);
            $Hotelinsert = $stmt3->execute();
            // print_r($insert);
            if ($stmt3->affected_rows === 1) {
                echo "<script>$(function(){toastr.success('Record Saved Successfully'); }); </script>";
                $show = "Hotel Expenses";
            } else {
                echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
            }

        }
    }
}

if (isset($_POST['btn_misc'])) //for Miscellaneous submit
{
    if (isset($_POST["token3"]) && isset($_SESSION["token3"]) && $_POST["token3"] == $_SESSION["token3"]) {
        if (isset($_FILES["receipt_image"]) && !empty($_FILES["receipt_image"]["name"])) {
            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseMiscellaneous/';

            $target_file = $target_dir . basename($_FILES["receipt_image"]["name"]);
            // $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['receipt_image']['tmp_name']);
            // Check file size
            if ($_FILES['receipt_image']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            $actual_pdf_file = $_FILES["receipt_image"]["name"];
            $allowedExtentions = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension, $allowedExtentions) || !in_array($fileType, $allowedFileTypes)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension == "pdf") {
                $pdf = $parser->parseFile($_FILES['receipt_image']['tmp_name']);
                $text = $pdf->getText();
                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["receipt_image"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["receipt_image"]["name"]), PATHINFO_EXTENSION);
                    $misc_fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $misc_fileNameFinal);
                    if (file_exists(ROOT_PATH . 'ExpenseMiscellaneous/' . $misc_fileNameFinal)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {

                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }

        // $insert_miscellaneous = 'INSERT INTO expense_miscellaneous(EmployeeID,empName,date,amount,receipt_no,receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus) VALUES("' . $EmployeeID . '","' . $empName . '","' . $_POST['miscellaneous_date'] . '","' . $_POST['amount'] . '","' . $_POST['receipt_no'] . '","' . $misc_fileNameFinal . '","' . $_POST['remarks'] . '","Pending","MiscellaneousRequest","Pending","Pending","Pending") ';
        if ($uploadOk == 1) {
            $insert_miscellaneous = 'INSERT INTO expense_miscellaneous(EmployeeID,empName,date,amount,receipt_no,receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus) VALUES(?,?,?,?,?,?,?,"Pending","MiscellaneousRequest","Pending","Pending","Pending") ';
            $stmt4 = $conn->prepare($insert_miscellaneous);
            $stmt4->bind_param("sssisss", $EmployeeID, $empName, $_POST['miscellaneous_date'], $_POST['amount'], $_POST['receipt_no'], $misc_fileNameFinal, $_POST['remarks']);
            $Misclinsert = $stmt4->execute();
            // print_r($insert);
            if ($stmt4->affected_rows === 1) {
                echo "<script>$(function(){toastr.success('Record Saved Successfully'); }); </script>";
                $show = "Miscellaneous Expenses";
            } else {
                echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
            }
        }
    }
}

if (isset($_POST['btn_mobile'])) //for Mobile submit
{
    // echo "<pre>";
    // print_r($_POST);
    // die;
    if (isset($_POST["token4"]) && isset($_SESSION["token4"]) && $_POST["token4"] == $_SESSION["token4"]) {
        if (isset($_FILES["mobile_receipt_image"]) && !empty($_FILES["mobile_receipt_image"]["name"])) {
            $uploadOk = 1;
            $target_dir = ROOT_PATH . 'ExpenseMobile/';

            $target_file = $target_dir . basename($_FILES["mobile_receipt_image"]["name"]);
            // $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileExtension = pathinfo($target_file, PATHINFO_EXTENSION);
            $fileType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['mobile_receipt_image']['tmp_name']);
            // Check file size
            if ($_FILES['mobile_receipt_image']['size'] > 2000000) {
                echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
                $uploadOk = 0;
            }
            // Allow certain file formats
            $actual_pdf_file = $_FILES["mobile_receipt_image"]["name"];
            $allowedExtentions = array("pdf", "doc", "docx", "jpeg", "jpg", "png");
            $allowedFileTypes = array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/jpg", "image/png",);

            if (!in_array($fileExtension, $allowedExtentions) || !in_array($fileType, $allowedFileTypes)) {
                echo "<script>$(function(){ toastr.error('Sorry, only jpg,png,doc,docx and pdf files are allowed.'); }); </script>";
                $uploadOk = 0;
            }
            if ($fileExtension == "pdf") {
                $pdf = $parser->parseFile($_FILES['mobile_receipt_image']['tmp_name']);
                $text = $pdf->getText();
                $string = preg_match('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $text);
                if ($string) {
                    echo "<script>$(function(){ toastr.error('Sorry, url files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
                if (preg_match('/<\s?[^\>]*\/?\s?>/i', $text)) {
                    echo "<script>$(function(){ toastr.error('Sorry, files are not allowed.'); }); </script>";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["mobile_receipt_image"]["tmp_name"], $target_file)) {
                    $fileExt = pathinfo(basename($_FILES["mobile_receipt_image"]["name"]), PATHINFO_EXTENSION);
                    $mobile_fileNameFinal = $EmployeeID . '_' . date('Y-m-d_His') . '.' . $fileExt;
                    $file = rename($target_file, $target_dir . '' . $mobile_fileNameFinal);
                    if (file_exists(ROOT_PATH . 'ExpenseMobile/' . $mobile_fileNameFinal)) {
                        echo "<script>$(function(){ toastr.success('The file has been uploaded.') });</script>";
                        $uploadOk == 1;
                    } else {
                        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                        $uploadOk = 0;
                    }
                } else {

                    echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.') });</script>";
                    $uploadOk = 0;
                }
            }
        }

        // $insert_miscellaneous = 'INSERT INTO expense_miscellaneous(EmployeeID,empName,date,amount,receipt_no,receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus) VALUES("' . $EmployeeID . '","' . $empName . '","' . $_POST['miscellaneous_date'] . '","' . $_POST['amount'] . '","' . $_POST['receipt_no'] . '","' . $mobile_fileNameFinal . '","' . $_POST['remarks'] . '","Pending","MiscellaneousRequest","Pending","Pending","Pending") ';
        if ($uploadOk == 1) {
            $insert_mobile = 'INSERT INTO expense_mobile(EmployeeID,empName,date,amount,receipt_no,receipt_image,remarks,req_status,reqType,reviewerStatus,mgrStatus,approverStatus) VALUES(?,?,?,?,?,?,?,"Pending","MobileRequest","Pending","Pending","Pending") ';
            $stmt5 = $conn->prepare($insert_mobile);
            $stmt5->bind_param("sssisss", $EmployeeID, $empName, $_POST['mobile_date'], $_POST['mobile_amount'], $_POST['mobile_receipt_no'], $mobile_fileNameFinal, $_POST['mobile_remarks']);
            $mobileinsert = $stmt5->execute();
            // print_r($insert);
            if ($stmt5->affected_rows === 1) {
                echo "<script>$(function(){toastr.success('Record Saved Successfully'); }); </script>";
                $show = "Mobile Expenses";
            } else {
                echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
            }
        }
    }
}


?>

<style>
    .error {
        color: red;
    }

    #data-container {
        display: block;
        background: #2a3f54;

        max-height: 250px;
        overflow-y: auto;
        z-index: 9999999;
        position: absolute;
        width: 100%;

    }

    #data-container li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    #data-container li:hover {
        background: #26b99a;
        cursor: pointer;
    }

    .form-control:focus {
        border-color: #d01010;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

    }

    #overlay {
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height: 100%;
        display: none;
        background: rgba(0, 0, 0, 0.6);
    }

    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }

    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }

    .is-hide {
        display: none;
    }

    .is-show {
        display: block;
    }
</style>

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Reimbursement</span>
    <div class="pim-container">

        <div class="form-div">
            <h4>Reimbursement</h4>
            <div class="schema-form-section row">
                <div class="input-field col s12 m12">
                    <div class="col s12 m12">

                        <div class="input-field col s12 m12 l12">
                            <select id="expenses1R" name="expenses1" onchange="this.form.submit();">
                                <option value="NA">__Select__</option>
                                <?php

                                $sql_expemp = "select empid from expense_mobile_csa where empid=?";
                                $expemp_stmt = $conn->prepare($sql_expemp);
                                $expemp_stmt->bind_param("s", $EmployeeID);
                                $expemp_stmt->execute();
                                $res_expemp = $expemp_stmt->get_result();

                                if ($res_expemp->num_rows > 0) {
                                    $flag = 1;
                                }
                                if ($_SESSION['__df_id'] != '74' && $_SESSION['__df_id'] != '77' && $_SESSION['__df_id'] != '146' && $_SESSION['__df_id'] != '147' && $_SESSION['__df_id'] != '148' && $_SESSION['__df_id'] != '149') {

                                ?>

                                    <option value="Food Expenses" <?php echo (isset($show) && $show == "Food Expenses") ? 'selected' : '' ?>>Food </option>
                                    <option value="Travel Expenses" <?php echo (isset($show) && $show == "Travel Expenses") ? 'selected' : '' ?>>Travel </option>
                                    <option value="Hotel Expenses" <?php echo (isset($show) && $show == "Hotel Expenses") ? 'selected' : '' ?>>Hotel </option>
                                    <option value="Miscellaneous Expenses" <?php echo (isset($show) && $show == "Miscellaneous Expenses") ? 'selected' : '' ?>>Miscellaneous </option>
                                <?php }
                                if ($flag == 1) {

                                ?>

                                    <option value="Mobile Expenses" <?php echo (isset($show) && $show == "Mobile Expenses") ? 'selected' : '' ?>>Mobile </option>
                                <?php } ?>

                            </select>
                            <label for="expenses1" class="active-drop-down active">Reimbursement</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- START FOOD EXPENSES -->
        <?php if (isset($show) &&  $show == "Food Expenses") {   ?>

            <div id="foodExpensesR">
                <h4>Food Reimbursement</h4>
                <div class="schema-form-section row">
                    <div class="input-field col s12 m12">
                        <div class="col s12 m12">
                            <!-- <div class="row" id="foodExpenses"> -->
                            <form action="" id="food_form" method="post" onchange="this.form.submit();">
                                <?php
                                $_SESSION["token"] = csrfToken();
                                ?>
                                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">
                                <input type="hidden" name="empid" id="empid" value="<?php echo $_SESSION['__user_logid'] ?>">
                                <input type="hidden" name="empname" id="empname" value="<?php echo $_SESSION['__user_Name'] ?>">

                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="food_date" id="food_date" value="">
                                    <label for="food_date">Date</label>
                                </div>

                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="amount" id="food_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label for="amount">Amount</label>
                                </div>

                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="receipt_no" id="food_receipt_no">
                                    <label for="receipt_no">Receipt No</label>
                                </div>

                                <div class="file-field input-field col s6 m6 l6">
                                    <div class="btn">
                                        <span>Browse File</span>
                                        <input type="file" id="food_receipt_image" name="receipt_image" style="text-indent: -99999em;">
                                        <br>
                                        <input type="hidden" name="h_food_receipt_image" id="h_food_receipt_image" class="form-control" style="padding-top: 0px;" />
                                        <span class="file-size-text">Accepts up to 2MB</span>
                                    </div>

                                    <div class="file-path-wrapper">
                                        <input class="file-path" type="text">
                                    </div>
                                </div>
                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="remarks" id="food_remarks"> -->
                                    <textarea class="materialize-textarea" id="food_remarks" name="remarks" maxlength="200"></textarea>
                                    <label for="remarks">Remarks</label>
                                </div>
                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_food" id="btn_food" class="btn waves-effect waves-green align-right">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END FOOD EXPENSES -->


            <!-- START FOOD DATA TABLE -->
            <div class="form-div " id="divfoodR">
                <h4>Food Report</h4>
                <!-- Form container if any -->
                <div class="schema-form-section row">

                    <script>
                        //contain load event for data table and other importent rand required trigger event and searches if any
                        $(document).ready(function() {
                            $('#foodTable').DataTable({
                                dom: 'Bfrtip',
                                scrollX: '100%',
                                "iDisplayLength": 25,
                                scrollCollapse: true,
                                lengthMenu: [
                                    [5, 10, 25, 50, -1],
                                    ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                                ],
                                buttons: [{
                                        extend: 'excel',
                                        text: 'EXCEL',
                                        extension: '.xlsx',
                                        exportOptions: {
                                            modifier: {
                                                page: 'all'
                                            }
                                        },
                                        title: 'table'
                                    }
                                    /*,'copy'*/
                                    , 'pageLength'
                                ]
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
                    <div id="foodTableDivRR" class="table-responsive">
                        <?php
                        $sqlConnect = 'SELECT * FROM expense_food where EmployeeID=?';
                        $stmtFood = $conn->prepare($sqlConnect);
                        $stmtFood->bind_param("s", $EmployeeID);
                        $stmtFood->execute();
                        $FoodResult = $stmtFood->get_result();
                        if ($FoodResult->num_rows > 0) { ?>
                            <table id="foodTable" class="data dataTable no-footer row-border table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>Sr No</th> -->
                                        <th>Action</th>
                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Image</th>
                                        <th>Remarks</th>
                                        <th>Reviewer Status</th>
                                        <th>Reviewer Remark</th>
                                        <th>Approver Status</th>
                                        <th>Approver Remark</th>
                                        <th>Created On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($FoodResult as $key => $value) { ?>

                                        <tr id="<?php echo $value['id']; ?>">
                                            <?php if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>

                                                <td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_item_food(this);" data-item="<?php echo $value['id']; ?>">ohrm_delete</i></td>

                                            <?php  } else if ($value['reviewerStatus'] == 'Decline' || $value['mgrStatus'] == 'Decline') { ?>
                                                <td class="delete"><span>Decline</span></td>
                                            <?php  } else if ($value['reviewerStatus'] == 'Approve' && $value['mgrStatus'] == 'Approve') { ?>
                                                <td class="delete"><span>Approved</span></td>
                                            <?php } else { ?>
                                                <td class="delete"><span>None</span></td>
                                            <?php } ?>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td><?php echo $value['date']; ?></td>
                                            <td><?php echo $value['amount']; ?></td>
                                            <td><?php echo $value['receipt_no']; ?></td>
                                            <td><?php echo $value['receipt_image']; ?></td>
                                            <td><?php echo $value['remarks']; ?></td>
                                            <td><?php echo $value['reviewerStatus']; ?></td>
                                            <td><?php echo $value['reviewComment']; ?></td>
                                            <td><?php echo $value['mgrStatus']; ?></td>
                                            <td><?php echo $value['mgrComment']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- END FOOD DATA TABLE -->

        <?php } ?>


        <?php if (isset($show) &&  $show == "Travel Expenses") { ?>


            <!-- START TRAVEL EXPENSES -->
            <div id="travelExpensesRRR">
                <h4>Travel Reimbursement</h4>
                <div class="schema-form-section row">
                    <div class="input-field col s12 m12">
                        <div class="col s12 m12">
                            <!-- <div class="row" id="foodExpenses"> -->
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php
                                $_SESSION["token1"] = csrfToken();
                                ?>
                                <input type="hidden" name="token1" value="<?= $_SESSION["token1"] ?>">
                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="travel_date" id="travel_date">
                                    <label for="travel_date">Date</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="placeFrom" id="placeFrom">
                                    <label for="placeFrom">Place from</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="placeTO" id="placeTO">
                                    <label for="placeTO">Place To</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <select name="modeOftravel" id="modeOftravel">
                                        <option value="NA">__Select__</option>
                                        <option value="Car">Car</option>
                                        <option value="Flight">Flight</option>
                                        <option value="Train">Train</option>
                                        <option value="Bus">Bus</option>
                                        <option value="Cab/Auto">Cab/Auto</option>
                                    </select>
                                    <label for="expenses1" class="active-drop-down active">Mode Of Travel</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="retDate">
                                    <input type="text" name="returnDate" id="returnDate">
                                    <label for="returnDate">Return Date</label>
                                </div>


                                <div class="input-field col s4 m4 l4" id="kiloMeter">
                                    <input type="text" name="car_km" id="car_km" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label for="kilometer">Kilometer</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="kmAmount">
                                    <input type="text" name="km_amount" id="km_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                    <label class="Active" for="km_amount">KM Amount</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="carReceipt">
                                    <input type="file" name="car_km_receipt" id="car_km_receipt">
                                    <span>KM_Receipt</span>
                                </div>

                                <div class="input-field col s4 m4 l4" id="parkReceipt">
                                    <input type="file" name="car_parking_receipt" id="car_parking_receipt">
                                    <span>Parking_Receipt</span>
                                </div>

                                <div class="input-field col s4 m4 l4" id="tollReceipt">
                                    <input type="file" name="car_toll_receipt" id="car_toll_receipt">
                                    <span>Toll_Receipt</span>
                                </div>

                                <div class="input-field col s4 m4 l4" id="parkAmount">
                                    <input type="text" name="parking_amount" id="parking_amount" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label for="parking_amount">Parking Amount</label>
                                </div>



                                <div class="input-field col s4 m4 l4" id="tollAmount">
                                    <input type="text" name="toll_amount" id="toll_amount" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label for="toll_amount">Toll_Amount</label>
                                </div>


                                <div class="input-field col s4 m4 l4" id="showForCar">
                                    <input type="text" name="amount" id="travel_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label id="lableAmount" class="Active" for="amount">Total Amount</label>
                                </div>


                                <input type="hidden" name="amount" id="actAmount" value="<?php echo $_POST['amount'] ?>">



                                <div class="input-field col s4 m4 l4" id="showForOthers">
                                    <input type="text" name="amount1" id="travel_amount1" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label id="lableAmount" class="Active" for="amount"> Amount</label>
                                </div>


                                <div class="input-field col s4 m4 l4" id="receiptNo">
                                    <input type="text" name="receipt_no" id="travel_receipt_no">
                                    <label for="receipt_no">Receipt No</label>
                                </div>

                                <div class="file-field input-field col s4 m4" id="receiptImage">
                                    <div class="btn">
                                        <span>Browse File</span>
                                        <input type="file" id="travel_receipt_image" name="receipt_image" style="text-indent: -99999em;">
                                        <br>
                                        <span class="file-size-text">Accepts up to 2MB</span>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path" type="text">
                                    </div>
                                </div>

                                <!-- <div class="input-field col s4 m4 l4">
                                <input type="file" name="receipt_image" id="travel_receipt_image">
                                <span>Receipt Image</span>
                            </div> -->

                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="remarks" id="travel_remarks"> -->
                                    <textarea class="materialize-textarea" id="travel_remarks" name="remarks" maxlength="200"></textarea>
                                    <label for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_travel" id="btn_travel" class="btn waves-effect waves-green align-right">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END TRAVEL EXPENSES -->


            <!-- START TRAVEL DATA TABLE -->
            <div class="form-div" id="divTravelRR">
                <h4>Travel Report</h4>
                <!-- Form container if any -->
                <div class="schema-form-section row">
                    <script>
                        //contain load event for data table and other importent rand required trigger event and searches if any
                        $(document).ready(function() {
                            $('#travelTable').DataTable({
                                dom: 'Bfrtip',
                                scrollX: '100%',
                                "iDisplayLength": 25,
                                scrollCollapse: true,
                                lengthMenu: [
                                    [5, 10, 25, 50, -1],
                                    ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                                ],
                                buttons: [{
                                        extend: 'excel',
                                        text: 'EXCEL',
                                        extension: '.xlsx',
                                        exportOptions: {
                                            modifier: {
                                                page: 'all'
                                            }
                                        },
                                        title: 'table'
                                    }
                                    /*,'copy'*/
                                    , 'pageLength'
                                ]
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

                    <div id="travelTableDiv">
                        <?php
                        $sqlConnect = 'SELECT * FROM expense_travel where EmployeeID=?';
                        $stmtTravel = $conn->prepare($sqlConnect);
                        $stmtTravel->bind_param("s", $EmployeeID);
                        $stmtTravel->execute();
                        $TravelResult = $stmtTravel->get_result();
                        if ($TravelResult->num_rows > 0) { ?>

                            <table id="travelTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>Sr No</th> -->
                                        <th>Action</th>
                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Date</th>
                                        <th>Place From</th>
                                        <th>Place To</th>
                                        <th>Mode Of Travel</th>
                                        <th>Return Date</th>
                                        <th>Amount</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Image</th>
                                        <th>Car KM</th>
                                        <th>Car Km Receipt</th>
                                        <th>Car Toll Receipt</th>
                                        <th>Car Parking Receipt</th>
                                        <th>Remarks</th>
                                        <th>Reviewer Status</th>
                                        <th>Reviewer Remark</th>
                                        <th>Approver Status</th>
                                        <th>Approver Remark</th>
                                        <th>Created On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($TravelResult as $key => $value) { ?>
                                        <tr>
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <?php if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>
                                                <td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_item_travel(this);" data-item="<?php echo $value['id']; ?>">ohrm_delete</i></td>
                                            <?php  } else if ($value['reviewerStatus'] == 'Decline' || $value['mgrStatus'] == 'Decline') { ?>
                                                <td class="delete"><span>Decline</span></td>
                                            <?php  } else if ($value['reviewerStatus'] == 'Approve' && $value['mgrStatus'] == 'Approve') { ?>
                                                <td class="approve"><span>Approved</span></td>
                                            <?php  } else { ?>
                                                <td class="delete"><span>None</span></td>
                                            <?php } ?>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td><?php echo $value['date']; ?></td>
                                            <td><?php echo $value['placeFrom']; ?></td>
                                            <td><?php echo $value['placeTO']; ?></td>
                                            <td><?php echo $value['modeOftravel']; ?></td>
                                            <td><?php echo $value['returnDate']; ?></td>
                                            <td><?php echo $value['amount']; ?></td>
                                            <td><?php echo $value['receipt_no']; ?></td>
                                            <td><?php echo $value['receipt_image']; ?></td>
                                            <td><?php echo $value['car_km']; ?></td>
                                            <td><?php echo $value['car_km_receipt']; ?></td>
                                            <td><?php echo $value['car_toll_receipt']; ?></td>
                                            <td><?php echo $value['car_parking_receipt']; ?></td>
                                            <td><?php echo $value['remarks']; ?></td>
                                            <td><?php echo $value['reviewerStatus']; ?></td>
                                            <td><?php echo $value['reviewComment']; ?></td>
                                            <td><?php echo $value['mgrStatus']; ?></td>
                                            <td><?php echo $value['mgrComment']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>
                                        <?php

                                    } ?>

                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- END TRAVEL DATA TABLE -->

        <?php } ?>


        <?php if (isset($show) &&  $show == "Hotel Expenses") { ?>


            <!-- START HOTEL EXPENSES -->
            <div id="hotelExpensesRR">
                <h4>Hotel Reimbursement</h4>
                <div class="schema-form-section row">
                    <div class="input-field col s12 m12">
                        <div class="col s12 m12">
                            <!-- <div class="row" id="foodExpenses"> -->
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php
                                $_SESSION["token2"] = csrfToken();
                                ?>
                                <input type="hidden" name="token2" value="<?= $_SESSION["token2"] ?>">
                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="dateFrom" id="hotel_dateFrom">
                                    <label class="Active" for="hotel_dateFrom">Date From</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="dateTo" id="hotel_dateTo">
                                    <label class="Active" for="hotel_dateTo">Date To</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="noOfdays" id="noOfdays" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                    <label class="Active" for="noOfdays">No Of Days</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <select name="lochotel" id="lochotel">
                                        <option value="NA">Select Location</option>
                                        <?php
                                        foreach ($resulthotel as $hotelVal) {
                                            echo '<option value=' . $hotelVal["id"] . '>' . $hotelVal["location"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="lochotel" class="active-drop-down active">Visited Location</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <select name="clienthotel" id="clienthotel" onclick="javascript:return otherclient(this)">
                                        <option value="NA">Select</option>
                                    </select>
                                    <label for="clienthotel" class="active-drop-down active">Visited Client Name</label>
                                </div>

                                <div class="input-field col s4 m4 l4" id="otherclientDIV">
                                    <input type="text" name="otherclient" id="otherclient">
                                    <label for="otherclient">Client Name</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="hotelName" id="hotelName">
                                    <label for="hotelName">Hotel Name</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="amount" id="hotel_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label for="amount">Amount</label>
                                </div>

                                <div class="input-field col s4 m4 l4">
                                    <input type="text" name="receipt_no" id="hotel_receipt_no">
                                    <label for="receipt_no">Receipt No</label>
                                </div>

                                <div class="file-field input-field col s6 m6">
                                    <div class="btn">
                                        <span>Browse File</span>
                                        <input type="file" id="hotel_receipt_image" name="receipt_image" style="text-indent: -99999em;">
                                        <br>
                                        <span class="file-size-text">Accepts up to 2MB</span>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path" type="text">
                                    </div>
                                </div>

                                <!-- <div class="input-field col s4 m4 l4">
                                <input type="file" name="receipt_image" id="hotel_receipt_image">
                                <span>Receipt Image</span>
                            </div> -->


                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="remarks" id="hotel_remarks"> -->
                                    <textarea class="materialize-textarea" id="hotel_remarks" name="remarks" maxlength="200"></textarea>
                                    <label for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_hotel" id="btn_hotel" class="btn waves-effect waves-green align-right">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END HOTEL EXPENSES -->


            <!-- START HOTEL DATA TABLE -->
            <div class="form-div" id="divHotelRRR">
                <h4>Hotel Report</h4>
                <!-- Form container if any -->
                <div class="schema-form-section row">
                    <script>
                        //contain load event for data table and other importent rand required trigger event and searches if any
                        $(document).ready(function() {
                            $('#hotleTable').DataTable({
                                dom: 'Bfrtip',
                                scrollX: '100%',
                                "iDisplayLength": 25,
                                scrollCollapse: true,
                                lengthMenu: [
                                    [5, 10, 25, 50, -1],
                                    ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                                ],
                                buttons: [{
                                        extend: 'excel',
                                        text: 'EXCEL',
                                        extension: '.xlsx',
                                        exportOptions: {
                                            modifier: {
                                                page: 'all'
                                            }
                                        },
                                        title: 'table'
                                    }
                                    /*,'copy'*/
                                    , 'pageLength'
                                ]
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

                    <div id="hotleTableDiv">
                        <?php
                        $sqlConnect = 'SELECT t1.*,t2.location as visited_location FROM expense_hotel t1 left join location_master t2 on t1.location=t2.id where EmployeeID=?';
                        $stmtHotel = $conn->prepare($sqlConnect);
                        $stmtHotel->bind_param("s", $EmployeeID);
                        $stmtHotel->execute();
                        $HotelResult = $stmtHotel->get_result();
                        if ($HotelResult->num_rows > 0) { ?>
                            <table id="hotleTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>Sr No</th> -->
                                        <th>Action</th>
                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>No Of Days</th>
                                        <th>Visited Location</th>
                                        <th>Visited Client Name</th>
                                        <th>Hotel Name</th>
                                        <th>Amount</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Image</th>
                                        <th>Remarks</th>
                                        <th>Reviewer Status</th>
                                        <th>Reviewer Remark</th>
                                        <th>Approver Status</th>
                                        <th>Approver Remark</th>
                                        <th>Created On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($HotelResult as $key => $value) { ?>
                                        <tr id="Hid<?php echo $value['id']; ?>">
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <?php if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>
                                                <td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_item_hotel(this);" data-item="<?php echo $value['id']; ?>">ohrm_delete</i></td>
                                            <?php  } else 
                                        if ($value['reviewerStatus'] == 'Decline' || $value['mgrStatus'] == 'Decline') { ?>
                                                <td class="delete"><span>Decline</span></td>
                                            <?php  } else if ($value['reviewerStatus'] == 'Approve' && $value['mgrStatus'] == 'Approve') { ?>
                                                <td class="approve"><span>Approved</span></td>
                                            <?php  } else { ?>
                                                <td class="delete"><span>None</span></td>
                                            <?php } ?>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td><?php echo $value['dateFrom']; ?></td>
                                            <td><?php echo $value['dateTo']; ?></td>
                                            <td><?php echo $value['noOfdays']; ?></td>
                                            <td><?php echo $value['visited_location']; ?></td>
                                            <td><?php echo $value['client_name']; ?></td>
                                            <td><?php echo $value['hotelName']; ?></td>
                                            <td><?php echo $value['amount']; ?></td>
                                            <td><?php echo $value['receipt_no']; ?></td>
                                            <td><?php echo $value['receipt_image']; ?></td>
                                            <td><?php echo $value['remarks']; ?></td>
                                            <td><?php echo $value['reviewerStatus']; ?></td>
                                            <td><?php echo $value['reviewComment']; ?></td>
                                            <td><?php echo $value['mgrStatus']; ?></td>
                                            <td><?php echo $value['mgrComment']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>
                                        </tr>
                                    <?php

                                    } ?>

                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- END HOTEL DATA TABLE -->

        <?php } ?>


        <?php if (isset($show) &&  $show == "Miscellaneous Expenses") { ?>


            <!-- START MISCELLANEOUS EXPENSES -->
            <div id="miscellaneousExpensesRR">
                <h4>Miscellaneous Reimbursement</h4>
                <div class="schema-form-section row">
                    <div class="input-field col s12 m12">
                        <div class="col s12 m12">
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php
                                $_SESSION["token3"] = csrfToken();
                                ?>
                                <input type="hidden" name="token3" value="<?= $_SESSION["token3"] ?>">
                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="miscellaneous_date" id="miscellaneous_date">
                                    <label for="miscellaneous_date">Date</label>
                                </div>

                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="amount" id="miscellaneous_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label for="amount">Amount</label>
                                </div>


                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="receipt_no" id="misc_receipt_no">
                                    <label for="receipt_no">Receipt No</label>
                                </div>

                                <div class="file-field input-field col s6 m6">
                                    <div class="btn">
                                        <span>Browse File</span>
                                        <input type="file" id="misc_receipt_image" name="receipt_image" style="text-indent: -99999em;">
                                        <br>
                                        <span class="file-size-text">Accepts up to 2MB</span>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path" type="text">
                                    </div>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <!-- <input type="text" name="remarks" id="miscellaneous_remarks"> -->
                                    <textarea class="materialize-textarea" id="miscellaneous_remarks" name="remarks" maxlength="200"></textarea>
                                    <label for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_misc" id="btn_misc" class="btn waves-effect waves-green align-right">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MISCELLANEOUS EXPENSES -->

            <!-- START MISCELLANEOUS DATA TABLE -->
            <div class="form-div" id="divMiscellaneousRRR">
                <h4>Miscellaneous Report</h4>
                <!-- Form container if any -->
                <div class="schema-form-section row">
                    <script>
                        //contain load event for data table and other importent rand required trigger event and searches if any
                        $(document).ready(function() {
                            $('#miscellaneousTable').DataTable({
                                dom: 'Bfrtip',
                                scrollX: '100%',
                                "iDisplayLength": 25,
                                scrollCollapse: true,
                                lengthMenu: [
                                    [5, 10, 25, 50, -1],
                                    ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                                ],
                                buttons: [{
                                        extend: 'excel',
                                        text: 'EXCEL',
                                        extension: '.xlsx',
                                        exportOptions: {
                                            modifier: {
                                                page: 'all'
                                            }
                                        },
                                        title: 'table'
                                    }
                                    /*,'copy'*/
                                    , 'pageLength'
                                ]
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

                    <div id="miscellaneousTableDiv">
                        <?php
                        $sqlConnect = 'SELECT * FROM expense_miscellaneous where EmployeeID=?';
                        $stmtMisc = $conn->prepare($sqlConnect);
                        $stmtMisc->bind_param("s", $EmployeeID);
                        $stmtMisc->execute();
                        $MiscResult = $stmtMisc->get_result();
                        if ($MiscResult->num_rows > 0) { ?>
                            <table id="miscellaneousTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>Sr No</th> -->
                                        <th>Action</th>
                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Image</th>
                                        <th>Remarks</th>
                                        <th>Reviewer Status</th>
                                        <th>Reviewer Remark</th>
                                        <th>Approver Status</th>
                                        <th>Approver Remark</th>
                                        <th>Created On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($MiscResult as $key => $value) { ?>
                                        <tr id="Mid<?php echo $value['id']; ?>">
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <?php if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>
                                                <td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_item_miscellaneous(this);" data-item="<?php echo $value['id']; ?>">ohrm_delete</i></td>
                                            <?php  } else 
                                        if ($value['reviewerStatus'] == 'Decline' || $value['mgrStatus'] == 'Decline') { ?>
                                                <td class="delete"><span>Decline</span></td>
                                            <?php  } else if ($value['reviewerStatus'] == 'Approve' && $value['mgrStatus'] == 'Approve') { ?>
                                                <td class="approve"><span>Approved</span></td>
                                            <?php  } else { ?>
                                                <td class="delete"><span>None</span></td>
                                            <?php } ?>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td><?php echo $value['date']; ?></td>
                                            <td><?php echo $value['amount']; ?></td>
                                            <td><?php echo $value['receipt_no']; ?></td>
                                            <td><?php echo $value['receipt_image']; ?></td>
                                            <td><?php echo $value['remarks']; ?></td>
                                            <td><?php echo $value['reviewerStatus']; ?></td>
                                            <td><?php echo $value['reviewComment']; ?></td>
                                            <td><?php echo $value['mgrStatus']; ?></td>
                                            <td><?php echo $value['mgrComment']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>

                                        </tr>
                                    <?php

                                    } ?>

                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- END MISCELLANEOUS DATA TABLE -->

        <?php } ?>


        <?php if (isset($show) &&  $show == "Mobile Expenses") { ?>


            <!-- START MOBILE EXPENSES -->
            <div id="mobileExpensesRR">
                <h4>Mobile Reimbursement</h4>
                <div class="schema-form-section row">
                    <div class="input-field col s12 m12">
                        <div class="col s12 m12">
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php
                                $_SESSION["token4"] = csrfToken();
                                ?>
                                <input type="hidden" name="token4" value="<?= $_SESSION["token4"] ?>">
                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="mobile_date" id="mobile_date">
                                    <label for="mobile_date">Date</label>
                                </div>

                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="mobile_amount" id="mobile_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    <label for="amount">Amount</label>
                                </div>


                                <div class="input-field col s6 m6 l6">
                                    <input type="text" name="mobile_receipt_no" id="mobile_receipt_no">
                                    <label for="receipt_no">Receipt No</label>
                                </div>

                                <div class="file-field input-field col s6 m6">
                                    <div class="btn">
                                        <span>Browse File</span>
                                        <input type="file" id="mobile_receipt_image" name="mobile_receipt_image" style="text-indent: -99999em;">
                                        <br>
                                        <span class="file-size-text">Accepts up to 2MB</span>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path" type="text">
                                    </div>
                                </div>

                                <div class="input-field col s12 m12 l12">
                                    <textarea class="materialize-textarea" id="mobile_remarks" name="mobile_remarks" maxlength="200"></textarea>
                                    <label for="remarks">Remarks</label>
                                </div>

                                <div class="input-field col s12 m12 right-align">
                                    <button type="submit" name="btn_mobile" id="btn_mobile" class="btn waves-effect waves-green align-right">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MOBILE EXPENSES -->

            <!-- START MOBILE DATA TABLE -->
            <div class="form-div" id="divMobileRRR">
                <h4>Mobile Report</h4>
                <!-- Form container if any -->
                <div class="schema-form-section row">
                    <script>
                        //contain load event for data table and other importent rand required trigger event and searches if any
                        $(document).ready(function() {
                            $('#mobileTable').DataTable({
                                dom: 'Bfrtip',
                                scrollX: '100%',
                                "iDisplayLength": 25,
                                scrollCollapse: true,
                                lengthMenu: [
                                    [5, 10, 25, 50, -1],
                                    ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
                                ],
                                buttons: [{
                                        extend: 'excel',
                                        text: 'EXCEL',
                                        extension: '.xlsx',
                                        exportOptions: {
                                            modifier: {
                                                page: 'all'
                                            }
                                        },
                                        title: 'table'
                                    }
                                    /*,'copy'*/
                                    , 'pageLength'
                                ]
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

                    <div id="mobileTableDiv">
                        <?php
                        $sqlConnect = 'SELECT * FROM expense_mobile where EmployeeID=?';
                        $stmtMob = $conn->prepare($sqlConnect);
                        $stmtMob->bind_param("s", $EmployeeID);
                        $stmtMob->execute();
                        $MobResult = $stmtMob->get_result();
                        if ($MobResult->num_rows > 0) { ?>
                            <table id="mobileTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <!-- <th>Sr No</th> -->
                                        <th>Action</th>
                                        <th>EmployeeID</th>
                                        <th>Employee Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Image</th>
                                        <th>Remarks</th>
                                        <th>Reviewer Status</th>
                                        <th>Reviewer Remark</th>
                                        <th>Approver Status</th>
                                        <th>Approver Remark</th>
                                        <th>Created On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($MobResult as $key => $value) { ?>
                                        <tr id="Mid<?php echo $value['id']; ?>">
                                            <!-- <td><?php //echo $i++; 
                                                        ?></td> -->
                                            <?php if ($value['reviewerStatus'] != 'Approve' && $value['reviewerStatus'] != 'Decline') { ?>
                                                <td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_item_mobile(this);" data-item="<?php echo $value['id']; ?>">ohrm_delete</i></td>
                                            <?php  } else 
                                        if ($value['reviewerStatus'] == 'Decline' || $value['mgrStatus'] == 'Decline') { ?>
                                                <td class="delete"><span>Decline</span></td>
                                            <?php  } else if ($value['reviewerStatus'] == 'Approve' && $value['mgrStatus'] == 'Approve') { ?>
                                                <td class="approve"><span>Approved</span></td>
                                            <?php  } else { ?>
                                                <td class="delete"><span>None</span></td>
                                            <?php } ?>
                                            <td><?php echo $value['EmployeeID']; ?></td>
                                            <td><?php echo $value['empName']; ?></td>
                                            <td><?php echo $value['date']; ?></td>
                                            <td><?php echo $value['amount']; ?></td>
                                            <td><?php echo $value['receipt_no']; ?></td>
                                            <td><?php echo $value['receipt_image']; ?></td>
                                            <td><?php echo $value['remarks']; ?></td>
                                            <td><?php echo $value['reviewerStatus']; ?></td>
                                            <td><?php echo $value['reviewComment']; ?></td>
                                            <td><?php echo $value['mgrStatus']; ?></td>
                                            <td><?php echo $value['mgrComment']; ?></td>
                                            <td><?php echo $value['created_at']; ?></td>

                                        </tr>
                                    <?php

                                    } ?>

                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- END MOBILE DATA TABLE -->

        <?php } ?>

    </div>
    <!--Content Div for all Page End -->
</div>

<script>
    $(document).ready(function() {

        $('#otherclientDIV').hide();
        $('#clienthotel').change(function() {
            var val = $(this).find("option:selected").text();
            if (val == "Other") {
                $('#otherclientDIV').show();
            } else {
                $('#otherclientDIV').hide();
            }
        }).change();

        $("#lochotel").on('change', function() {
            var loc = $("#lochotel").val();
            // alert(loc)
            $.ajax({
                type: "get",
                url: "../Controller/getclientbylocation.php",
                data: {
                    "loc": loc
                },
                success: function(response) {
                    $("#clienthotel").html(response);
                }
            });
        });



        // START VALIDATION FOR FOOD
        $('#btn_food').on('click', function() {

            var validate = 0;
            var alert_msg = '';

            // var fileExtension = ['jpeg', 'jpg', 'png', 'pdf', 'msg'];
            if ($('#food_date').val().trim() == '') {
                $('#food_date').addClass('has-error');
                if ($('#spanfood_date').size() == 0) {
                    $('<span id="spanfood_date" class="help-block">Required *</span>').insertAfter('#food_date');
                }
                validate = 1;
            }

            if ($('#food_amount').val().trim() == '') {
                $('#food_amount').addClass('has-error');
                if ($('#spanamount').size() == 0) {
                    $('<span id="spanamount" class="help-block">Required *</span>').insertAfter('#food_amount');
                }
                validate = 1;
            }

            if ($('#food_receipt_no').val().trim() == '') {
                $('#food_receipt_no').addClass('has-error');
                if ($('#spanreceipt_no').size() == 0) {
                    $('<span id="spanreceipt_no" class="help-block">Required *</span>').insertAfter('#food_receipt_no');
                }
                validate = 1;
            }

            if ($('#food_remarks').val().trim() == '') {
                $('#food_remarks').addClass('has-error');
                if ($('#spanremarks').size() == 0) {
                    $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#food_remarks');
                }
                validate = 1;
            }

            if ($('#food_receipt_image').val().trim() == '') {
                $('#food_receipt_image').addClass('has-error');
                if ($('#spanfood_receipt_image').size() == 0) {
                    $('<span id="spanfood_receipt_image" class="help-block">Required *</span>').insertAfter('#food_receipt_image');
                }
                validate = 1;
            }

            // if ($('#food_receipt_image').val().trim() == "" && $('#h_food_receipt_image').val() == '') {
            //     alert('Upload profile Image photo');
            //     $('#food_receipt_image').css('border-color', 'red');
            //     return false;
            // } else {
            // if ($('#food_receipt_image').val() != '') {
            //     if ($.inArray($('#food_receipt_image').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            //         // alert('dfdf')
            //         alert("Food Receipt Image Only formats are allowed :" + fileExtension.join(', '));
            //         $('#food_receipt_image').css('border-color', 'red');
            //         $('#food_receipt_image').focus();
            //         return false;
            //     } else {
            //         var file_size = $('#food_receipt_image')[0].files[0].size;
            //         // alert(file_size)
            //         var calf1 = file_size / 1024;
            //         if (calf1 >= "2000") {
            //             alert("Food Receipt Image File size is greater than 2MB");
            //             $('#food_receipt_image').css('border-color', 'red');
            //             $('#food_receipt_image').focus();
            //             return false;
            //         }
            //     }
            //     $('#food_receipt_image').css('border-color', '');
            // }
            // }

            if (validate == 1) {

                //alert('1');
                return false;
            }

        });
        // END VALIDATION FOR FOOD

        // // START VALIDATION FOR TRAVEL
        $('#btn_travel').on('click', function() {
            // alert($('#travel_amount').val());

            //alert("START");
            var validate = 0;
            var alert_msg = '';
            //var fileExtension = ['jpeg', 'jpg', 'png', 'pdf', 'msg'];

            if ($('#travel_date').val().trim() == '') {
                $('#travel_date').addClass('has-error');
                if ($('#spantravel_date').size() == 0) {
                    $('<span id="spantravel_date" class="help-block">Required *</span>').insertAfter('#travel_date');
                }
                validate = 1;
            }

            if ($('#placeFrom').val().trim() == '') {
                $('#placeFrom').addClass('has-error');
                if ($('#spanplaceFrom').size() == 0) {
                    $('<span id="spanplaceFrom" class="help-block">Required *</span>').insertAfter('#placeFrom');
                }

                validate = 1;
            }

            if ($('#placeTO').val().trim() == '') {
                $('#placeTO').addClass('has-error');
                if ($('#spanplaceTO').size() == 0) {
                    $('<span id="spanplaceTO" class="help-block">Required *</span>').insertAfter('#placeTO');
                }

                validate = 1;
            }

            if ($('#modeOftravel').val().trim() == 'NA') {
                $('#modeOftravel').addClass('has-error');
                if ($('#spanmodeOftravel').size() == 0) {
                    $('<span id="spanmodeOftravel" class="help-block">Required *</span>').insertAfter('#modeOftravel');
                }

                validate = 1;
            }

            if ($('#placeTO').val().trim() == '') {
                $('#placeTO').addClass('has-error');
                if ($('#spanplaceTO').size() == 0) {
                    $('<span id="spanplaceTO" class="help-block">Required *</span>').insertAfter('#placeTO');
                }

                validate = 1;
            }

            if ($('#modeOftravel').val() == "Car") {
                if ($('#travel_amount').val().trim() == '') {
                    $('#travel_amount').addClass('has-error');
                    if ($('#spantravel_amount').size() == 0) {
                        $('<span id="spantravel_amount" class="help-block">Required *</span>').insertAfter('#travel_amount');
                    }

                    validate = 1;
                }
            }

            if ($('#modeOftravel').val() != "Car") {
                if ($('#travel_amount1').val().trim() == '') {
                    $('#travel_amount1').addClass('has-error');
                    if ($('#spantravel_amount1').size() == 0) {
                        $('<span id="spantravel_amount1" class="help-block">Required *</span>').insertAfter('#travel_amount1');
                    }

                    validate = 1;
                }
            }

            if ($('#modeOftravel').val() != "Car") {
                if ($('#travel_receipt_no').val().trim() == '') {
                    $('#travel_receipt_no').addClass('has-error');
                    if ($('#spanreceipt_no').size() == 0) {
                        $('<span id="spanreceipt_no" class="help-block">Required *</span>').insertAfter('#travel_receipt_no');
                    }
                    validate = 1;
                }
            }

            if ($('#modeOftravel').val() == "Car") {
                if ($('#car_km').val().trim() == '') {
                    $('#car_km').addClass('has-error');
                    if ($('#spancar_km').size() == 0) {
                        $('<span id="spancar_km" class="help-block">Required *</span>').insertAfter('#car_km');
                    }
                    validate = 1;
                }
            }

            if ($('#modeOftravel').val() != "Car") {
                if ($('#travel_receipt_image').val().trim() == '') {
                    $('#travel_receipt_image').addClass('has-error');
                    if ($('#spantravel_receipt_image').size() == 0) {
                        $('<span id="spantravel_receipt_image" class="help-block">Required *</span>').insertAfter('#travel_receipt_image');
                    }
                    validate = 1;
                }
            }

            // if ($('#travel_receipt_image').val() != '') {
            //     if ($.inArray($('#travel_receipt_image').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            //         // alert('dfdf')
            //         alert("Travel Receipt Image Only formats are allowed :" + fileExtension.join(', '));
            //         $('#travel_receipt_image').css('border-color', 'red');
            //         $('#travel_receipt_image').focus();
            //         return false;
            //     } else {
            //         var file_size = $('#travel_receipt_image')[0].files[0].size;
            //         // alert(file_size)
            //         var calf1 = file_size / 1024;
            //         if (calf1 >= "2000") {
            //             alert("Travel Receipt Image File size is greater than 2MB");
            //             $('#travel_receipt_image').css('border-color', 'red');
            //             $('#travel_receipt_image').focus();
            //             return false;
            //         }
            //     }
            //     $('#travel_receipt_image').css('border-color', '');
            // }

            if ($('#travel_remarks').val().trim() == '') {
                $('#travel_remarks').addClass('has-error');
                if ($('#spanremarks').size() == 0) {
                    $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#travel_remarks');
                }

                // validate = 1;
            }


            // if ($('#parking_amount').val().trim() == '') {
            //     $('#parking_amount').addClass('has-error');
            //     if ($('#spanremarks').size() == 0) {
            //         $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#parking_amount');
            //     }
            //     validate = 1;
            // }
            // if ($('#travel_amount').val().trim() == '') {
            //     $('#travel_amount').addClass('has-error');
            //     if ($('#spanremarks').size() == 0) {
            //         $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#travel_amount');
            //     }
            //     validate = 1;
            // }
            // if ($('#toll_amount').val().trim() == '') {
            //     $('#toll_amount').addClass('has-error');
            //     if ($('#spanremarks').size() == 0) {
            //         $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#toll_amount');
            //     }
            //     validate = 1;
            // }



            //alert("START 2");

            if (validate == 1) {
                //alert('1');
                return false;
            }

            //alert("START 3");
        });
        // END VALIDATION FOR TRAVEL

        //START VALIDATION FOR HOTEL
        $('#btn_hotel').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            // var fileExtension = ['jpeg', 'jpg', 'png', 'pdf', 'msg'];

            if ($('#hotel_dateFrom').val().trim() == '') {
                $('#hotel_dateFrom').addClass('has-error');
                if ($('#spanhotel_dateFrom').size() == 0) {
                    $('<span id="spanhotel_dateFrom" class="help-block">Required *</span>').insertAfter('#hotel_dateFrom');
                }
                validate = 1;
            }

            if ($('#hotel_dateTo').val().trim() == '') {
                $('#hotel_dateTo').addClass('has-error');
                if ($('#spanhotel_dateTo').size() == 0) {
                    $('<span id="spanhotel_dateTo" class="help-block">Required *</span>').insertAfter('#hotel_dateTo');
                }
                validate = 1;
            }

            if ($('#lochotel').val().trim() == 'NA') {
                $('#lochotel').addClass('has-error');
                if ($('#spanlochotel').size() == 0) {
                    $('<span id="spanlochotel" class="help-block">Required *</span>').insertAfter('#lochotel');
                }
                validate = 1;
            }

            if ($('#clienthotel').val().trim() == 'NA') {
                $('#clienthotel').addClass('has-error');
                if ($('#spanclienthotel').size() == 0) {
                    $('<span id="spanclienthotel" class="help-block">Required *</span>').insertAfter('#clienthotel');
                }

                validate = 1;
            }
            var clienthtl = $("#clienthotel").val();
            if (clienthtl == "Other") {
                if ($('#otherclient').val().trim() == "") {
                    $('#otherclient').addClass('has-error');
                    if ($('#spanotherclient').size() == 0) {
                        $('<span id="spanotherclient" class="help-block">Required *</span>').insertAfter('#otherclient');
                    }
                    validate = 1;
                }
            }

            // if ($('#noOfdays').val().trim() == '') {
            //     $('#noOfdays').addClass('has-error');
            //     if ($('#spannoOfdays').size() == 0) {
            //         $('<span id="spannoOfdays" class="help-block">Required *</span>').insertAfter('#noOfdays');
            //     }
            //     validate = 1;
            // }

            if ($('#hotelName').val().trim() == '') {
                $('#hotelName').addClass('has-error');
                if ($('#spanhotelName').size() == 0) {
                    $('<span id="spanhotelName" class="help-block">Required *</span>').insertAfter('#hotelName');
                }
                validate = 1;
            }

            if ($('#hotel_amount').val().trim() == '') {
                $('#hotel_amount').addClass('has-error');
                if ($('#spanhotel_amount').size() == 0) {
                    $('<span id="spanhotel_amount" class="help-block">Required *</span>').insertAfter('#hotel_amount');
                }
                validate = 1;
            }

            if ($('#hotel_receipt_no').val().trim() == '') {
                $('#hotel_receipt_no').addClass('has-error');
                if ($('#spanhotel_receipt_no').size() == 0) {
                    $('<span id="spanhotel_receipt_no" class="help-block">Required *</span>').insertAfter('#hotel_receipt_no');
                }
                validate = 1;
            }

            if ($('#hotel_receipt_image').val().trim() == '') {
                $('#hotel_receipt_image').addClass('has-error');
                if ($('#spanhotel_receipt_image').size() == 0) {
                    $('<span id="spanhotel_receipt_image" class="help-block">Required *</span>').insertAfter('#hotel_receipt_image');
                }
                validate = 1;
            }
            // if ($('#hotel_receipt_image').val() != '') {
            //     if ($.inArray($('#hotel_receipt_image').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            //         // alert('dfdf')
            //         alert("Hotel Receipt Image Only formats are allowed :" + fileExtension.join(', '));
            //         $('#hotel_receipt_image').css('border-color', 'red');
            //         $('#hotel_receipt_image').focus();
            //         return false;
            //     } else {
            //         var file_size = $('#hotel_receipt_image')[0].files[0].size;
            //         // alert(file_size)
            //         var calf1 = file_size / 1024;
            //         if (calf1 >= "2000") {
            //             alert("Hotel Receipt Image File size is greater than 2MB");
            //             $('#hotel_receipt_image').css('border-color', 'red');
            //             $('#hotel_receipt_image').focus();
            //             return false;
            //         }
            //     }
            //     $('#hotel_receipt_image').css('border-color', '');
            // }



            if ($('#hotel_remarks').val().trim() == '') {
                $('#hotel_remarks').addClass('has-error');
                if ($('#spanhotel_remarks').size() == 0) {
                    $('<span id="spanhotel_remarks" class="help-block">Required *</span>').insertAfter('#hotel_remarks');
                }
                validate = 1;
            }

            if (validate == 1) {

                //alert('1');
                return false;
            }
        });
        // END VALIDATION FOR HOTEL

        //START VALIDATION FOR MISCELLANEOUS
        $('#btn_misc').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            // var fileExtension = ['jpeg', 'jpg', 'png', 'pdf', 'msg'];
            if ($('#miscellaneous_date').val().trim() == '') {
                $('#miscellaneous_date').addClass('has-error');
                if ($('#spanmiscellaneous_date').size() == 0) {
                    $('<span id="spanmiscellaneous_date" class="help-block">Required *</span>').insertAfter('#miscellaneous_date');
                }
                validate = 1;
            }

            if ($('#miscellaneous_amount').val().trim() == '') {
                $('#miscellaneous_amount').addClass('has-error');
                if ($('#spanamount').size() == 0) {
                    $('<span id="spanamount" class="help-block">Required *</span>').insertAfter('#miscellaneous_amount');
                }
                validate = 1;
            }

            if ($('#miscellaneous_remarks').val().trim() == '') {
                $('#miscellaneous_remarks').addClass('has-error');
                if ($('#spanremarks').size() == 0) {
                    $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#miscellaneous_remarks');
                }
                validate = 1;
            }

            // if ($('#misc_receipt_image').val() != '') {
            //     if ($.inArray($('#misc_receipt_image').val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            //         // alert('dfdf')
            //         alert("Miscellaneous Receipt Image Only formats are allowed :" + fileExtension.join(', '));
            //         $('#misc_receipt_image').css('border-color', 'red');
            //         $('#misc_receipt_image').focus();
            //         return false;
            //     }
            // }


            // if ($('#misc_receipt_no').val().trim() == '') {
            //     $('#misc_receipt_no').addClass('has-error');
            //     if ($('#spanmisc_receipt_no').size() == 0) {
            //         $('<span id="spanmisc_receipt_no" class="help-block">Required *</span>').insertAfter('#misc_receipt_no');
            //     }
            //     validate = 1;
            // }

            // if ($('#misc_receipt_image').val().trim() == '') {
            //     $('#misc_receipt_image').addClass('has-error');
            //     if ($('#spanmisc_receipt_image').size() == 0) {
            //         $('<span id="spanmisc_receipt_image" class="help-block">Required *</span>').insertAfter('#misc_receipt_image');
            //     }
            //     validate = 1;
            // }

            if (validate == 1) {

                //alert('1');
                return false;
            }
        });
        // END VALIDATION FOR MISCELLANEOUS

        //START VALIDATION FOR MOBILE
        $('#btn_mobile').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            // var fileExtension = ['jpeg', 'jpg', 'png', 'pdf', 'msg'];
            if ($('#mobile_date').val().trim() == '') {
                $('#mobile_date').addClass('has-error');
                if ($('#spanmobile_date').size() == 0) {
                    $('<span id="spanmobile_date" class="help-block">Required *</span>').insertAfter('#mobile_date');
                }
                validate = 1;
            }

            if ($('#mobile_amount').val().trim() == '') {
                $('#mobile_amount').addClass('has-error');
                if ($('#spanmobile').size() == 0) {
                    $('<span id="spanmobile" class="help-block">Required *</span>').insertAfter('#mobile_amount');
                }
                validate = 1;
            }

            if ($('#mobile_remarks').val().trim() == '') {
                $('#mobile_remarks').addClass('has-error');
                if ($('#spanremarks').size() == 0) {
                    $('<span id="spanremarks" class="help-block">Required *</span>').insertAfter('#mobile_remarks');
                }
                validate = 1;
            }

            if ($('#mobile_receipt_no').val().trim() == '') {
                $('#mobile_receipt_no').addClass('has-error');
                if ($('#spanmobile_receipt_no').size() == 0) {
                    $('<span id="spanmobile_receipt_no" class="help-block">Required *</span>').insertAfter('#mobile_receipt_no');
                }
                validate = 1;
            }

            if ($('#mobile_receipt_image').val().trim() == '') {
                $('#mobile_receipt_image').addClass('has-error');
                if ($('#spanmobile_receipt_image').size() == 0) {
                    $('<span id="spanmobile_receipt_image" class="help-block">Required *</span>').insertAfter('#mobile_receipt_image');
                }
                validate = 1;
            }

            if (validate == 1) {
                //alert('1');
                return false;
            }
        });
        // END VALIDATION FOR MOBILE
    });
</script>

<script>
    $('#food_date, #travel_date, #returnDate, #miscellaneous_date, #mobile_date').datetimepicker({
        timepicker: false,
        format: 'Y-m-d',
        scrollMonth: false,
    });


    $("#hotel_dateFrom").datepicker({
        timepicker: false,
        dateFormat: 'yy-mm-dd',
        scrollMonth: false,
    });
    $("#hotel_dateTo").datepicker({
        timepicker: false,
        dateFormat: 'yy-mm-dd',
        scrollMonth: false,
        onSelect: function() {
            myfunc();
        }
    });

    function myfunc() {
        var start = $("#hotel_dateFrom").datepicker("getDate");
        var end = $("#hotel_dateTo").datepicker("getDate");
        days = (end - start) / (1000 * 60 * 60 * 24);
        // alert(Math.round(days));
        $('#noOfdays').val(days);

    }

    // $("#hotel_dateTo").click(function() {
    //     $(this).addClass("active");
    //     $(this).text("Active Class");
    // });
</script>


<script>
    $('#retDate').hide()
    $('#modeOftravel').change(function() {
        // $('#travel_amount').val('');
        var travelValue = $(this).val()
        //alert(travelValue)
        if (travelValue == 'Flight') {
            $('#retDate').show();
        } else {
            $('#retDate').hide();
        }
    });


    $('#car_km').on('change keyup check', function() {
        $('#km_amount').val(parseInt($(this).val() * 10));
        var kmAmount = parseInt($('#km_amount').val());
        var toll_amount = parseInt($('#toll_amount').val());
        var parking_amount = parseInt($('#parking_amount').val());
        //alert(toll_amount);
        if (isNaN(toll_amount)) {
            toll_amount = 0;
        } else {
            toll_amount = toll_amount;
        }
        if (isNaN(parking_amount)) {
            parking_amount = 0;
        } else {
            parking_amount = parking_amount;
        }

        $("#travel_amount").val(kmAmount + parking_amount + toll_amount);
        $("#actAmount").val(kmAmount + parking_amount + toll_amount);
    });
    $('#toll_amount').on('change keyup check', function() {
        var kmAmount = parseInt($('#km_amount').val());
        var toll_amount = parseInt($('#toll_amount').val());
        var parking_amount = parseInt($('#parking_amount').val());
        if (isNaN(toll_amount)) {
            toll_amount = 0;
        } else {
            toll_amount = toll_amount;
        }
        if (isNaN(parking_amount)) {
            parking_amount = 0;
        } else {
            parking_amount = parking_amount;
        }
        $("#travel_amount").val(kmAmount + parking_amount + toll_amount);
        $("#actAmount").val(kmAmount + parking_amount + toll_amount);
    });
    $('#parking_amount').on('change keyup check', function() {
        var kmAmount = parseInt($('#km_amount').val());
        var toll_amount = parseInt($('#toll_amount').val());
        var parking_amount = parseInt($('#parking_amount').val());
        if (isNaN(toll_amount)) {
            toll_amount = 0;
        } else {
            toll_amount = toll_amount;
        }
        if (isNaN(parking_amount)) {
            parking_amount = 0;
        } else {
            parking_amount = parking_amount;
        }
        $("#travel_amount").val(kmAmount + parking_amount + toll_amount);
        $("#actAmount").val(kmAmount + parking_amount + toll_amount);
    });

    // $('#parking_amount,#toll_amount').c
</script>


<!-- START CAR VALUE -->
<script>
    $('#showForOthers').show();
    $('#carReceipt').hide();
    $('#parkReceipt').hide();
    $('#parkAmount').hide();
    $('#tollReceipt').hide();
    $('#tollAmount').hide();
    $('#kiloMeter').hide();
    $('#kmAmount').hide();
    $('#showForCar').hide();


    $('#modeOftravel').change(function() {
        var travelValue = $(this).val()

        //alert(travelValue)
        if (travelValue == 'Car') {
            $('#lableAmount').text('Total Amount');
            $("#travel_amount").prop("disabled", true);
            $('#receiptNo').hide();
            $('#receiptImage').hide();
            $('#showForOthers').hide();
            $('#showForCar').show();
            $('#carReceipt').show();
            $('#parkReceipt').show();
            $('#parkAmount').show();
            $('#tollReceipt').show();
            $('#tollAmount').show();
            $('#kiloMeter').show();
            $('#kmAmount').show();

        } else {
            $('#lableAmount').text('Amount');
            $("#travel_amount").prop("disabled", false);
            $('#carReceipt').hide();
            $('#parkReceipt').hide();
            $('#parkAmount').hide();
            $('#tollReceipt').hide();
            $('#tollAmount').hide();
            $('#kiloMeter').hide();
            $('#kmAmount').hide();
            $('#showForCar').hide();
            $('#showForOthers').show();
            $('#receiptNo').show();
            $('#receiptImage').show();
        }
    });
</script>
<!-- END CAR VALUE -->

<script>
    $('#foodExpenses').hide();
    $('#divfood').hide();
    $('#travelExpenses').hide();
    $('#divTravel').hide();
    $('#hotelExpenses').hide();
    $('#divHotel').hide();
    $('#miscellaneousExpenses').hide();
    $('#divMiscellaneous').hide();
    $('#mobileExpenses').hide();
    $('#divMobile').hide();

    <?php if (isset($show) &&  $show == "food") { ?>
        // alert('food')
        $('#foodExpenses').show();
        $('#divfood').show();
    <?php } ?>

    <?php if (isset($show) &&  $show == "travel") { ?>
        // alert('travel')
        $('#travelExpenses').show();
        $('#divTravel').show();
    <?php } ?>

    <?php if (isset($show) &&  $show == "hotel") { ?>
        // alert('hotel')
        $('#hotelExpenses').show();
        $('#divHotel').show();
    <?php } ?>

    <?php if (isset($show) &&  $show == "miscellaneous") { ?>
        // alert('miscellaneous')
        $('#miscellaneousExpenses').show();
        $('#divMiscellaneous').show();
    <?php } ?>

    <?php if (isset($show) &&  $show == "mobile") { ?>
        alert('miscellaneous')
        $('#mobileExpenses').show();
        $('#divMobile').show();
    <?php } ?>


    $('#expenses1').change(function() {
        var expvalue = $(this).val();
        if (expvalue == 'Food Expenses') {
            $('#foodExpenses').show();
            $('#divfood').show();
            // $('#divfood').removeClass("is-hide");
            // $('#divfood').addClass("is-show");
            $('#travelExpenses').hide();
            $('#divTravel').hide();
            $('#hotelExpenses').hide();
            $('#divHotel').hide();
            $('#miscellaneousExpenses').hide();
            $('#divMiscellaneous').hide();
            $('#mobileExpenses').hide();
            $('#divMobile').hide();

        } else if (expvalue == 'Travel Expenses') {
            // $('#divfood').removeClass("is-show");
            // $('#divfood').addClass("is-hide");
            $('#travelExpenses').show();
            $('#divTravel').show();
            $('#foodExpenses').hide();
            $('#divfood').hide();
            $('#hotelExpenses').hide();
            $('#divHotel').hide();
            $('#miscellaneousExpenses').hide();
            $('#divMiscellaneous').hide();
            $('#mobileExpenses').hide();
            $('#divMobile').hide();
        } else if (expvalue == 'Hotel Expenses') {
            $('#hotelExpenses').show();
            $('#divHotel').show();
            $('#travelExpenses').hide();
            $('#divTravel').hide();
            $('#foodExpenses').hide();
            $('#divfood').hide();
            $('#miscellaneousExpenses').hide();
            $('#divMiscellaneous').hide();
            $('#mobileExpenses').hide();
            $('#divMobile').hide();

        } else if (expvalue == 'Miscellaneous Expenses') {
            $('#miscellaneousExpenses').show();
            $('#divMiscellaneous').show();
            $('#hotelExpenses').hide();
            $('#divHotel').hide();
            $('#travelExpenses').hide();
            $('#divTravel').hide();
            $('#foodExpenses').hide();
            $('#divfood').hide();
            $('#mobileExpenses').hide();
            $('#divMobile').hide();
        } else if (expvalue == 'Mobile Expenses') {
            $('#mobileExpenses').show();
            $('#divMobile').show();
            $('#miscellaneousExpenses').hide();
            $('#divMiscellaneous').hide();
            $('#hotelExpenses').hide();
            $('#divHotel').hide();
            $('#travelExpenses').hide();
            $('#divTravel').hide();
            $('#foodExpenses').hide();
            $('#divfood').hide();
        } else if (expvalue == 'NA') {
            alert('Please Select A Specific')
        }

    });
</script>

<!--START DELETE FOR FOOD -->
<script>
    function delete_item_food(el) {
        if (confirm('Are you sure want to delete??')) {
            $item = $(el);

            $.ajax({
                url: "../Controller/deleteExpense_food.php?id=" + $(el).attr('data-item'),
                success: function(result) {
                    var data = result.split('|');
                    toastr.success(data[1]);

                    if (data[0] == 'Done') {
                        $item.closest('td').parent('tr').remove();
                    }
                }
            });
        }
    }


    // <!-- END DELETE FOR FOOD -->

    // <!-- START DELETE FOR TRAVEL -->

    function delete_item_travel(el) {

        if (confirm('Are you sure want to delete??')) {
            $item = $(el);

            $.ajax({
                url: "../Controller/deleteExpense_travel.php?id=" + $(el).attr('data-item'),
                success: function(result) {
                    var data = result.split('|');
                    toastr.success(data[1]);

                    if (data[0] == 'Done') {
                        $item.closest('td').parent('tr').remove();
                    }
                }
            });
        }
    }

    // <!-- END DELETE FOR TRAVEL -->

    // <!-- START DELETE HOTEL -->

    function delete_item_hotel(el) {

        if (confirm('Are you sure want to delete??')) {
            $item = $(el);
            // alert($item);
            $.ajax({
                url: "../Controller/deleteExpense_hotel.php?id=" + $(el).attr('data-item'),
                success: function(result) {

                    var data = result.split('|');

                    // alert(data[0]);
                    // alert(data[1]);

                    //$item.closest('td').parent('tr').remove();
                    $("#Hid" + $(el).attr('data-item')).remove();

                    toastr.success(data[1]);
                }
            });
        }
    }

    // <!-- END DELETE HOTEL -->

    // <!-- START DELETE MISCELLANEOUS -->

    function delete_item_miscellaneous(el) {

        if (confirm('Are you sure want to delete??')) {
            $item = $(el);

            $.ajax({
                url: "../Controller/deleteExpense_miscellaneous.php?id=" + $(el).attr('data-item'),
                success: function(result) {
                    var data = result.split('|');

                    $("#Mid" + $(el).attr('data-item')).remove();
                    // if (data[0] == 'Done') {
                    //     $item.closest('td').parent('tr').remove();
                    // }
                    toastr.success(data[1]);
                }
            });
        }
    }

    // <!-- END DELETE MISCELLANEOUS -->

    // <!-- START DELETE MOBILE -->

    function delete_item_mobile(el) {

        if (confirm('Are you sure want to delete??')) {
            $item = $(el);

            $.ajax({
                url: "../Controller/deleteExpense_mobile.php?id=" + $(el).attr('data-item'),
                success: function(result) {
                    var data = result.split('|');

                    $("#Mid" + $(el).attr('data-item')).remove();
                    // if (data[0] == 'Done') {
                    //     $item.closest('td').parent('tr').remove();
                    // }
                    toastr.success(data[1]);
                }
            });
        }
    }

    // <!-- END DELETE MOBILE -->
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>