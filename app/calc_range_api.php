<?php
if($cm_id == "88")
{
	$url = URL.'View/calcRange_zomato.php?empid='.$EmployeeID.'&type=one&from='.date('Y-m-d',strtotime($DateFrom));
}
else
{
	$url = URL.'View/calcRange.php?empid='.$EmployeeID.'&type=one&from='.date('Y-m-d',strtotime($DateFrom));
}	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data = curl_exec($curl);
	curl_close($curl);	
?>