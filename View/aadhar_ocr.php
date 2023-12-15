<?php
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$datetime = date("Y-m-d h:i:s");
$SelectAdhar = "select dd.EmployeeID,p.location ,dd.dov_value,dd.INTID, DATE_SUB(p.first_dod, INTERVAL -3 DAY),dd.doc_file,p.first_dod,dd.aadhar_source from personal_details p inner join  doc_details dd on p.EmployeeID=dd.EmployeeID inner join aadhar_verifiaction av on dd.EmployeeID=av.EmployeeID where dd.aadhar_source='chooseFromAdharAPI' and dd.doc_stype='Aadhar Card' and dd.doc_type='Proof of Address'  and av.aadhar_status='verified'  and av.ocr_date is NULL";

if (isset($_REQUEST['empid']) && $_REQUEST['empid'] != "") {
	$userempid = $_REQUEST['empid'];
	$SelectAdhar .= " and dd.EmployeeID ='" . $userempid . "' ";
} else {
	$SelectAdhar .= " and cast(DATE_SUB(p.first_dod, INTERVAL -3 DAY) as date)=cast(now() as date) ";
}

$myDB =  new MysqliDb();
$result_adhar = $myDB->query($SelectAdhar);
if (count($result_adhar) > 0 &&  $result_adhar[0]['EmployeeID'] != NULL && $result_adhar[0]['EmployeeID'] != "") {
	//echo $SelectAdhar;
	foreach ($result_adhar as $val) {
		if ($val['doc_file'] != "") {
			$filename = $val['doc_file'];
			$adhar_num = $val['dov_value'];
			$INTID = $val['INTID'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			if ($ext == 'pdf' && $val['location'] != "") {
				$employeeid = $val['EmployeeID'];
				$loc = $val['location'];
				if ($loc == "1" || $loc == "2") {
					$dir_location = "Docs/AdharCard/";
				} else if ($loc == "3") {
					$dir_location = "Meerut/Docs/AdharCard/";
				} else if ($loc == "4") {
					$dir_location = "Bareilly/Docs/AdharCard/";
				} else if ($loc == "5") {
					$dir_location = "Vadodara/Docs/AdharCard/";
				} else if ($loc == "6") {
					$dir_location = "Manglore/Docs/AdharCard/";
				} else if ($loc == "7") {
					$dir_location = "Bangalore/Docs/AdharCard/";
				} else if ($loc == "8") {
					$dir_location = "Nashik/Docs/AdharCard/";
				} else if ($loc == "9") {
					$dir_location = "Anantapur/Docs/AdharCard/";
				} else if ($loc == "10") {
					$dir_location = "Gurgaon/Docs/AdharCard/";
				} else if ($loc == "11") {
					$dir_location = "Hyderabad/Docs/AdharCard/";
				}
				/*echo "<br>";	
						echo $dir_location.$filename;
						echo "<br>";
						echo "<br>";
						echo "<br>";*/
				if (file_exists('../' . $dir_location . $filename)) {
					echo "file exist";
				} else {
					echo "file not exist";
				}
				echo "<br>";
				//echo "<br>";
				$mask_aadhaar = true;
				$use_pdf = true;
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://kyc-api.aadhaarkyc.io/api/v1/ocr/aadhaar",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					//CURLOPT_POSTFIELDS => array('file'=> new CurlFile('./931920210203094620722-2021-02-03-041622.pdf'),'mask_aadhaar' => $mask_aadhaar,'use_pdf' => $use_pdf),
					CURLOPT_POSTFIELDS => array('file' => new CurlFile('../' . $dir_location . $filename), 'mask_aadhaar' => $mask_aadhaar, 'use_pdf' => $use_pdf),

					//CURLOPT_POSTFIELDS => $fields,
					CURLOPT_HTTPHEADER => array(
						"Content-Type: multipart/form-data",
						"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1Nzg0NzM3MTEsImp0aSI6IjJhODI2OGIwLTA4YTgtNGU2YS1iNGVhLTFiOThjNjJlNzQ3YiIsImV4cCI6MTg5MzgzMzcxMSwiZnJlc2giOmZhbHNlLCJ1c2VyX2NsYWltcyI6eyJzY29wZXMiOlsicmVhZCJdfSwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmNvZ2VudHNlcnZpY2VzQGFhZGhhYXJhcGkuaW8iLCJuYmYiOjE1Nzg0NzM3MTF9.cmTpsW3U9ro0vCv2TYYnEzw5PYh0iObj-IbPUcA0QXU"
					),
				));

				$response = curl_exec($curl);
				//	echo $response;
				if (curl_errno($curl)) {

					echo 'Error:' . curl_error($curl);
				}
				curl_close($curl);
				$response_array = json_decode($response);
				$adhar_front = $response_array->data->ocr_fields[0]->image_url;
				$adhar_back = $response_array->data->ocr_fields[1]->image_url;
				$file1 = $employeeid . '_AadharCard_Front.jpg';
				$file2 = $employeeid . '_AadharCard_Back.jpg';
				$saveto1 = ROOT_PATH . $dir_location . $employeeid . '_AadharCard_Front.jpg';

				$saveto2 = ROOT_PATH . $dir_location . $employeeid . '_AadharCard_Back.jpg';
				$content1 = file_get_contents($adhar_front);

				$f1 = 0;
				if (file_put_contents($saveto1, $content1)) {
					if (file_exists("../" . $dir_location . $filename)) {
						unlink("../" . $dir_location . $filename);
					}

					$myDB =  new MysqliDb();
					$deleteOldone = "DELETE from doc_details where EmployeeID='" . $employeeid . "' and doc_stype='Aadhar Card' and doc_type='Proof of Address'";
					$delete_adhar = $myDB->query($deleteOldone);
					$insertquery = "Insert into doc_details set EmployeeID='" . $employeeid . "',doc_stype='Aadhar Card',doc_type='Proof of Address', dov_value='" . $adhar_num . "', doc_file='" . $file1 . "',createdon=now(),modifiedon=now(),INTID='" . $INTID . "',aadhar_source='chooseFromAdharAPI'";

					$myDB =  new MysqliDb();
					$myDB->query($insertquery);
					$error = $myDB->getLastError();
					$f1 = 1;
				}
				$f2 = 0;
				$content2 = file_get_contents($adhar_back);
				if (file_put_contents($saveto2, $content2)) {
					$insertquery = "Insert into doc_details set EmployeeID='" . $employeeid . "',doc_stype='Aadhar Card',doc_type='Proof of Address', dov_value='" . $adhar_num . "', doc_file='" . $file2 . "',createdon=now(),modifiedon=now(),INTID='" . $INTID . "',aadhar_source='chooseFromAdharAPI'";
					$myDB =  new MysqliDb();
					$myDB->query($insertquery);
					$error = $myDB->getLastError();
					$f2 = 1;
				}
				if ($f1 == 1 && $f2 == 1) {
					$datetime = date("Y-m-d h:i:s");

					$updateQuery = "Update aadhar_verifiaction set ocr_date='" . $datetime . "' where  EmployeeID='" . $employeeid . "'";
					$myDB =  new MysqliDb();
					$myDB->query($updateQuery);
					echo "1";
				}
			}
		}
	}
}
