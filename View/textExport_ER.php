<?php
//include('db.php');

$con = mysqli_connect('172.105.57.125', 'localconnect', 'Ra34L@L9#yF793', 'ems');
//$con = mysqli_connect('172.104.207.201', 'localconnect', 'Asg@rd#123', 'ems');

//require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
//require_once(CLS . 'MysqliDb.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


//session_start();

// if ($_SESSION) {


// 	if ($_SESSION['query'] != '' || $_SESSION['query'] == NULL) {


// $userid = $_GET['usrid'];
// $month = $_GET['month'];
// $year = $_GET['year'];
// $dept = $_GET['dept'];
// $loc = $_GET['loc'];
// $type = $_GET['type'];
$query = $_GET['sp'];

//$query =  'call sp_get_atnd_Report("' . $userid . '","' . $month . '","' . $year . '","' . $dept . '","' . $type . '","' . $loc . '")';
//die;
//header to give the order to the browser
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=exported-data.csv');
//die;
//select table to export the data
//$myDB = new MysqliDb();



//$select_table = $myDB->query($query);

$select_table = mysqli_query($con,  $query);

$count = mysqli_num_rows($select_table);
$rows = mysqli_fetch_assoc($select_table);
// echo 'dasd';
// die;
if (ob_get_contents()) ob_end_clean();

function getcsv($no_of_field_names)
{

	$separate = '';
	// do the action for all field names as field name
	foreach ($no_of_field_names as $field_name) {
		if (preg_match('/\\r|\\n|,|"/', $field_name)) {



			$field_name = '' . str_replace('<em>', '', $field_name) . '';
			$field_name = str_replace(',', '.', $field_name);
			$field_name = str_replace(array("\n", "\r"), ' ', $field_name);
			$field_name = str_replace("\n", ' ', $field_name);
			$field_name = str_replace("\r", ' ', $field_name);
			$field_name = str_replace(PHP_EOL, '', $field_name);
		}
		echo $separate . $field_name;

		//sepearte with the comma
		$separate = ',';
	}

	//make new row and line
	echo "\r\n";
}
if ($rows) {
	getcsv(array_keys($rows));
}
while ($rows) {
	getcsv($rows);
	$rows = mysqli_fetch_assoc($select_table);
}

// get total number of fields present in the database
$_SESSION['mith'] = '';
exit();
// 	}
// }