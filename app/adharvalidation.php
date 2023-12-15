<?php 
error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
$config = array (
 'serviceurl' => 'https://services.digitallocker.gov.in',
  'lockerurl' => 'https://partners.digitallocker.gov.in'
 );
	$orgid = 'com.cogenteservices';
 	$requester_id = "ems1";
    $date = date_create();
    $timstamp = date_timestamp_get($date);
    $secret_key = "8374c6bb12b5f0439acb693d8281a35a";
    $hash_key = hash('sha256', $requester_id.$secret_key.$timstamp);


	if(isset($_POST['employeeid']) && $_POST['employeeid']!=""){
		
		 $employeeid = $_POST['employeeid'];
		 $loc = $_POST['loc'];
		
		 if($loc=="1" || $loc=="2")
		{
			$dir_location = "Docs/AdharCard/";
			
		}
		else if($loc=="3")
		{
			$dir_location = "Meerut/Docs/AdharCard/";
		}
		else if($loc == "4")
		{
			$dir_location="Bareilly/Docs/AdharCard/";
		}
		else if($loc == "5")
		{
			$dir_location="Vadodara/Docs/AdharCard/";
		}
		else if($loc == "6")
		{
			$dir_location="Manglore/Docs/AdharCard/";
		}
		else if($loc == "7")
		{
			//$dir_location="Bangalore/Docs/Adhar/";
			$dir_location="Bangalore/Docs/AdharCard/";
		}
		/*if (!is_dir(ROOT_PATH.$dir_location)) 
		{
			@mkdir(ROOT_PATH.$dir_location, 0777, true);
			
		}
	*/
	//$username = $sessionName.'_Aadhar';

	//$userName = str_replace(" ","",$_POST['name']).'_aadhar';	
	//echo $userName;die;
    $timestamp = '2018-01-24T12:23:27+05:30';
    
    //var_dump($_POST['addressProof']);
    	
    if(isset($_POST['addressProof']['uri'])){
    	
   
	$uri = $_POST['addressProof']['uri'];
	/*$dd=explode('-',$uri);
	$uri_array=end($dd);
	$uristring=substr($uri_array,0,-1);
	echo base64_decode($uristring);
	exit;*/
	//$uri = 'in.gov.uidai-ADHAR-498745468922';
	
	$txn = $_POST['addressProof']['txn'];
	//$txn = '1090509704';
	$filename = $_POST['addressProof']['filename'];
	$aarray=explode('-',$filename);
	$adhar_num=end($aarray);
	//$filename = 'in.gov.uidai-ADHAR-498745468922';
	//echo $addressProof['uri'];
	
	$hash_key = hash('sha256',$secret_key.$timstamp);
	//echo $uri.'//////'.$txn.'//////'.$filename;
  $post_data_string	='<PullDocRequest xmlns:ns2="http://tempuri.org/" ver="1.0" orgId="'.$orgid.'" ts="'.$timstamp.'" txn="'.$txn.'" appId="'.$requester_id.'" keyhash="'.$hash_key.'"><DocDetails><URI>'.$uri.'</URI></DocDetails></PullDocRequest>';
	$url = $config['lockerurl'].'/public/requestor/api/pulldoc/1/xml';
	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data_string);
	$result = curl_exec($ch);
	curl_close($ch);
	//echo $result;
	
  	$data = (string) $result;
    $r1 = explode('>',$data);
    $r2 =str_replace("</docContent","",$r1[6]);
		
	$encoded_string = $r2;
	//$filePath = ROOT_PATH."Docs/AddressProof/";
	//$target_dir = ROOT_PATH."Docs/AddressProof/";
	
	
	$decoded_file = base64_decode($encoded_string); // decode the file
   // $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
    //$extension = mime2ext($mime_type); // extract extension from mime type
    $extension = 'pdf'; // extract extension from mime type
    $file = $employeeid.'_AadharCard_Front.'.$extension;// rename file as a unixque name
    
    
   // $dir_location2='Docs/Adhar/';
   $savepath="../".$dir_location.$file;
 // $dir_location2="https://cogentems.in/erpm/Meerut/Docs/AddressProof/";
  // echo  $savepath=$dir_location2.$file;
   
  //echo "directory=".$dir_location.$file;
  if($decoded_file!="")
  {
  	
	 	if(file_put_contents($savepath, $decoded_file))
	 	{
 		
 	
			//doc_stype='Aadhar Card', doc_type='Proof of Address',dov_value=createdon,modifiedon;
			$SelectAdhar="SELECT doc_type,EmployeeID,INTID,doc_stype,doc_type,dov_value,doc_file  FROM doc_details where EmployeeID='".$employeeid."' and doc_stype='Aadhar Card' and doc_type='Proof of Address'"; 
			$myDB =  new MysqliDb();
			$result_adhar=$myDB->query($SelectAdhar);
			$INTID='';
			if(count($result_adhar)>0){
				$adhar_num=$result_adhar[0]['dov_value'];
				$INTID=$result_adhar[0]['INTID'];
				/*foreach($result_adhar as $val){
					if($val['doc_file']!=""){
						$file_name=$val['doc_file'];
						if(file_exists("../".$dir_location.$file_name)){
							unlink("../".$dir_location.$file_name);
						}
								
					
					}
				}*/
				$myDB =  new MysqliDb();
				$deleteOldone="DELETE from doc_details where EmployeeID='".$employeeid."' and doc_stype='Aadhar Card' and doc_type='Proof of Address'";
				$delete_adhar=$myDB->query($deleteOldone);
				}
				$insertquery="Insert into doc_details set EmployeeID='".$employeeid."',doc_stype='Aadhar Card',doc_type='Proof of Address', dov_value='".$adhar_num."', doc_file='".$file."',createdon=now(),modifiedon=now(),INTID='".$INTID."',aadhar_source='FromdigilockerApp'";
				$myDB =  new MysqliDb();
				$myDB->query($insertquery);
				$error = $myDB->getLastError();
				if($error==""){
					echo "1";
				}else{
					echo "Verification not done";
				}
			}else{
				echo "File not move";
			}
		}else{
			echo "DG locker has not responce";
		}	
	}else{
		echo "Aadhar verification not done";
	}

}else{
	echo "EmployeeID does not exists";
}
?>