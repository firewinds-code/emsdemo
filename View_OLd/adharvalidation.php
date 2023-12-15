<?php
// error_reporting(E_ALL);
require_once(__dir__ . '/../Config/init.php');
// require_once(CLS.'MysqliDb.php');
$config = array(
	'serviceurl' => 'https://services.digitallocker.gov.in',
	'lockerurl' => 'https://partners.digitallocker.gov.in'
);
$orgid = 'com.cogenteservices';
$requester_id = "ems1";
$date = date_create();
$timstamp = date_timestamp_get($date);
$secret_key = "8374c6bb12b5f0439acb693d8281a35a";
$hash_key = hash('sha256', $requester_id . $secret_key . $timstamp);

$empid = isset($_POST['employeeid']);
if ($empid && $_POST['employeeid'] != "") {

	$sessionName = cleanUserInput($_POST['employeeid']);

	$username = $sessionName . '_Aadhar';

	//$userName = str_replace(" ","",$_POST['name']).'_aadhar';	
	//echo $userName;die;
	$timestamp = '2018-01-24T12:23:27+05:30';

	//var_dump($_POST['addressProof']);
	$addressPro = isset($_POST['addressProof']['uri']);
	if ($addressPro) {


		$uri = cleanUserInput($_POST['addressProof']['uri']);
		/*$dd=explode('-',$uri);
	$uri_array=end($dd);
	$uristring=substr($uri_array,0,-1);
	echo base64_decode($uristring);
	exit;*/
		//$uri = 'in.gov.uidai-ADHAR-498745468922';

		$txn = cleanUserInput($_POST['addressProof']['txn']);
		//$txn = '1090509704';
		$filename = $_POST['addressProof']['filename'];
		$aarray = explode('-', $filename);
		$adhar_num = end($aarray);
		//$filename = 'in.gov.uidai-ADHAR-498745468922';
		//echo $addressProof['uri'];

		$hash_key = hash('sha256', $secret_key . $timstamp);
		//echo $uri.'//////'.$txn.'//////'.$filename;
		$post_data_string	= '<PullDocRequest xmlns:ns2="http://tempuri.org/" ver="1.0" orgId="' . $orgid . '" ts="' . $timstamp . '" txn="' . $txn . '" appId="' . $requester_id . '" keyhash="' . $hash_key . '"><DocDetails><URI>' . $uri . '</URI></DocDetails></PullDocRequest>';
		$url = $config['lockerurl'] . '/public/requestor/api/pulldoc/1/xml';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_string);
		$result = curl_exec($ch);
		curl_close($ch);
		echo $result;

		$data = (string) $result;
		$r1 = explode('>', $data);
		$r2 = str_replace("</docContent", "", $r1[6]);

		$encoded_string = $r2;
		//$filePath = ROOT_PATH."Docs/AddressProof/";
		$target_dir = ROOT_PATH . "Docs/Rinku/";


		$decoded_file = base64_decode($encoded_string); // decode the file
		// $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
		//$extension = mime2ext($mime_type); // extract extension from mime type
		$extension = 'jpg'; // extract extension from mime type
		$file = $username . '.' . $extension; // rename file as a unique name
		$file_dir = $target_dir . $username . '.' . $extension;
		file_put_contents($file_dir, $decoded_file); // save


		$myDB =  new MysqliDb();
		$conn = $myDB->dbConnect();
		$query = "Insert into app_emp_adhar_validate set EmployeeID=?, adhar_num=?, adhar_string=?";
		$insQ = $conn->prepare($query);
		$insQ->bind_param("sss", $sessionName, $adhar_num, $encoded_string);
		$insQ->execute();
		$result = $insQ->get_result();
		// $myDB->query($query);
		// $error = $myDB->getLastError();
		// if ($error == "") {
		if ($insQ->affected_rows === 1) {
			echo "1";
		} else {
			echo "Verification not done";
		}
	} else {
		echo "Adhar verification not done";
	}
} else {
	echo "EmployeeID does not exists";
}
