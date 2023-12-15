<?php
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
$datetime = date("Y-m-d h:i:s");
$SelectAdhar = "select dd.EmployeeID,p.location ,dd.dov_value,dd.INTID, DATE_SUB(p.first_dod, INTERVAL -3 DAY),dd.doc_file,p.first_dod,dd.aadhar_source from personal_details p inner join  doc_details dd on p.EmployeeID=dd.EmployeeID inner join aadhar_verifiaction av on dd.EmployeeID=av.EmployeeID where dd.aadhar_source='chooseFromAdharAPI' and dd.doc_stype='Aadhar Card' and dd.doc_type='Proof of Address'  and av.aadhar_status='verified'  and (av.ocr_date is NULL or av.ocr_date='') ";


//$SelectAdhar="select dd.EmployeeID,p.location ,dd.dov_value,dd.INTID, DATE_SUB(p.first_dod, INTERVAL -3 DAY),dd.doc_file,p.first_dod,dd.aadhar_source from personal_details p inner join doc_details dd on p.EmployeeID=dd.EmployeeID inner join aadhar_verifiaction av on dd.EmployeeID=av.EmployeeID where dd.aadhar_source='chooseFromAdharAPI' and dd.doc_stype='Aadhar Card' and dd.doc_type='Proof of Address' and av.aadhar_status='verified' and dd.EmployeeID='TE0421193397'";
if (isset($_REQUEST['empid']) && $_REQUEST['empid'] != "") {
	$userempid = $_REQUEST['empid'];
	$SelectAdhar .= " and dd.EmployeeID ='" . $userempid . "' ";
} else {
	//$SelectAdhar.=" and cast(DATE_SUB(p.first_dod, INTERVAL -3 DAY) as date)=cast(now() as date) ";
}
echo $SelectAdhar;
echo "<br>";
$myDB =  new MysqliDb();
$result_adhar = $myDB->query($SelectAdhar);
if (count($result_adhar) > 0 &&  $result_adhar[0]['EmployeeID'] != NULL && $result_adhar[0]['EmployeeID'] != "") {
	//echo $SelectAdhar;
	foreach ($result_adhar as $val) {
		if ($val['doc_file'] != "") {
			$filename = trim($val['doc_file']);
			$adhar_num = $val['dov_value'];
			$INTID = $val['INTID'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

			if ($ext == 'pdf' && $val['location'] != "") {
				$filename_withoutext = substr($filename, 0, -4);
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
				echo "<br><br><br>";
				$pdfURL = URL . $dir_location . $filename;
				echo  $url = "http://lb.cogentlab.com:8081/python/check.php?pfn=" . $pdfURL . "&fn=" . $filename_withoutext . "&tp=B";
				// echo $url="http://lb.cogentlab.com:8081/python/check.php?pfn=http://ems.cogentlab.com/erpm/Docs/AdharCard2/CE121622565_AadharCard.pdf&fn=CE121622565_AadharCard&tp=B";

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HEADER, 0);

				$result2 = curl_exec($ch);
				curl_close($ch);


				$result2 = substr($result2, '0', -1);

				$response_array = json_decode($result2);


				$time_taken = array();
				$f1 = 0;
				if (isset($result2)) {

					/*$result_array=explode(',',$result2);
							print_r($result_array);
							if(isset($result_array[5])){
								$time_taken=explode(":",$result_array[5]);
							}
							if(isset($time_taken[1]) && $time_taken[1]!=""){
								echo $time_taken[1];
							}*/
					//echo "ocr file name= ".$ocr_filr_name=$response_array->OutputDirectory.$filename;
					echo "ocr file name= " . $ocr_filr_name = "http://lb.cogentlab.com:8081/python/maskedfiles/" . $filename;
					//$file1=$employeeid.'_Aadhar.pdf';
					$file1 = $filename;
					$saveto1 = ROOT_PATH . $dir_location . $file1;
					$content1 = file_get_contents($ocr_filr_name);

					if (file_put_contents($saveto1, $content1)) {
						if (file_exists("../" . $dir_location . $filename)) {
							//@unlink("../".$dir_location.$filename);
						}

						$myDB =  new MysqliDb();
						/*  $deleteOldone="DELETE from doc_details where EmployeeID='".$employeeid."' and doc_stype='Aadhar Card' and doc_type='Proof of Address'";
								$delete_adhar=$myDB->query($deleteOldone);
								 $insertquery="Insert into doc_details set EmployeeID='".$employeeid."',doc_stype='Aadhar Card',doc_type='Proof of Address', dov_value='".$adhar_num."', doc_file='".$file1."',createdon=now(),modifiedon=now(),INTID='".$INTID."',aadhar_source='chooseFromAdharAPI'";
								
								$myDB =  new MysqliDb();
								$myDB->query($insertquery);
								$error = $myDB->getLastError();*/
						$f1 = 1;
					}

					if ($f1 == 1) {
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
}
