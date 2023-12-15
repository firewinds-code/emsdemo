<?php
require_once(__dir__ . '/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
// require_once(CLS . 'MysqliDb.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

//$sql='call remove_client_new('.$_REQUEST['ID'].')';
$fileName = clean($_REQUEST['name']);
$rowId = clean($_REQUEST['rowId']);
$fileType = clean($_REQUEST['type']);
$BannerType = $_REQUEST['bannerType'];
$dirPath_web =  __DIR__ . '/../IndexEditPage/content_current/';
$dirPath_app =  __DIR__ . '/../IndexEditPage/appimg/';
$dirPath_disApp =  __DIR__ . '/../IndexEditPage/emp_discount_app/';
$dirPath_disWeb =  __DIR__ . '/../IndexEditPage/emp_discount_web/';
$dirPath_recogApp =  __DIR__ . '/../IndexEditPage/emp_recognition_app/';
$dirPath_recogWeb =  __DIR__ . '/../IndexEditPage/emp_recognition_web/';

if ($fileType == 'WEB') {

	$direPathToUpload = "";

	//Choosing Directory to Upload.
	if ($BannerType == "Banner") {
		$direPathToUpload = $dirPath_web;
	} else if ($BannerType == "Discount") {
		$direPathToUpload = $dirPath_disWeb;
	} else if ($BannerType == "Recognition") {
		$direPathToUpload = $dirPath_recogWeb;
	}
	if (!empty($direPathToUpload)) {

		//Dlete from the Server Storage location
		if (file_exists($direPathToUpload . $fileName)) {
			unlink($direPathToUpload . $fileName);
		}
		$myDB = new MysqliDb();
		$delQ = "DELETE from ipp_details where id = ?;";
		// $delRes = $myDB->rawQuery($delQ);
		$stmt = $conn->prepare($delQ);
		$stmt->bind_param("s", $rowId);
		$delt = $stmt->execute();
		$mysql_error = $myDB->getLastError();
		if ($delt) {
			echo "Photo Deleted : " . $fileName;
		} else {
			echo "Photo Not Deleted Try Again ";
		}
	} else {
		echo "Photo Not Found, Try Again ";
	}
} else {
	$direPathToUpload = "";
	//Choosing Directory to Upload.
	if ($BannerType == "Banner") {
		$direPathToUpload = $dirPath_app;
	} else if ($BannerType == "Discount") {
		$direPathToUpload = $dirPath_disApp;
	} else if ($BannerType == "Recognition") {
		$direPathToUpload = $dirPath_recogApp;
	}

	if (!empty($direPathToUpload)) {

		if (file_exists($direPathToUpload . $fileName)) {
			unlink($direPathToUpload . $fileName);
			echo "Photo Deleted : " . $fileName;
		}
		$myDB = new MysqliDb();
		$delQ = "DELETE from ipp_details where id = ?;";
		// $delRes = $myDB->rawQuery($delQ);
		$stmt = $conn->prepare($delQ);
		$stmt->bind_param("s", $rowId);
		$delt = $stmt->execute();
		$mysql_error = $myDB->getLastError();
		if ($delt) {
			echo "Photo Deleted : " . $fileName;
		} else {
			echo "Photo Not Deleted Try Again ";
		}
	} else {
		echo "Photo Not Found, Try Again ";
	}
}
