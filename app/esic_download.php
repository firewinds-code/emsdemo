 <?php  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
//require(ROOT_PATH.'AppCode/nHead.php');
ini_set('display_errors',0); 
ini_set('display_startup_errors', 0); 
error_reporting(E_ALL);
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
//require(ROOT_PATH.'AppCode/nHead.php');
include_once("../Services/sendsms_API1");
$EmployeeID =$Data['EmployeeID'];
$loc=$Data['locationid']; 
$result1=array();
if(isset($EmployeeID) && $EmployeeID !="" && isset($Data['appkey']) && $Data['appkey']=='esic' && $loc!="" && $Data['emailAddress']!="") 
{
	
	if($loc=="1" || $loc=="2")
{
	$dir_location = '';
	
}
else if($loc=="3")
{
	$dir_location = 'Meerut/';
}
else if($loc == "4")
{
	$dir_location="Bareilly/";
}
else if($loc == "5")
{
	$dir_location="Vadodara/";
}
else if($loc == "6")
{
	$dir_location="Manglore/";
}
else if($loc == "7")
{
	$dir_location="Bangalore/";
}
else if($loc == "8")
{
	$dir_location="Banglore_Fk/";
}
//////////////////////////////////////////////download esic ///////////////////////////////////
$message=$cm_id="";
if(isset($Data['emailAddress']) && trim($Data['emailAddress'])!="" )
{

			$myDB=new MysqliDb();
			$select_email_array=$myDB->rawQuery("select mobile,emailid,b.cm_id from contact_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID='".$EmployeeID."'");
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = EMAIL_HOST; 
			$mail->SMTPAuth = EMAIL_AUTH;
			$mail->Username = EMAIL_USER;   
			$mail->Password = EMAIL_PASS;                        
			$mail->SMTPSecure = EMAIL_SMTPSecure;
			$mail->Port = EMAIL_PORT; 
			$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
			$contactNum='';
			if($rowCount>0){	
				$contactNum=$select_email_array[0]['mobile'];
				$cm_id=$select_email_array[0]['cm_id'];
				$email_address=$Data['emailAddress'];
				$mail->AddAddress($email_address);
			}	
			if(file_exists('../'.$dir_location.'esicard/'.$EmployeeID.'_esicard.pdf'))
			{	
			
			$mail->AddAttachment('../'.$dir_location.'esicard/'.$EmployeeID.'_esicard.pdf',"esicard.pdf");
			$mail->Subject = 'ESI Card';
			$mail->isHTML(true);
			
			$mysqlError = '';
			
			$body='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Hello ,<br/><br/><span><b> Please find your attached ESIC Card</b></span><br /><br/><div style="float:left;width:100%;"><br/> Thanks.<div>';
			
			$mail->Body = $body;
			$mymsg="";
			if(!$mail->send())
		 	{
		 		$mymsg .='.Mailer Error:'. $mail->ErrorInfo;
		 		$result1['msg']=$mymsg;
	            $result1['status']=0;
		  	} 
			else
			 {
			 	$myDB = new MysqliDb();
				//$myDB->rawQuery("update esicard set status=1,email_address='".addslashes($Data['emailAddress'])."',received_date=now(),cm_id='".$cm_id."' where EmployeeID='".$EmployeeID."'");				
				$myDB->rawQuery("update esicard set status=1,email_address='".addslashes($Data['emailAddress'])."',received_date=now(),cm_id='".$cm_id."',source_from='App' where EmployeeID='".$EmployeeID."'");				
				$emailaddress= $Data['emailAddress'];
			 	$result1['msg']="ESIC Card sent successfully on your email address ".$emailaddress."  - Cogent E Services";
	            $result1['status']=1;
			 }
				/* SMS on mobile */
				$mobilenum=$contactNum;
				 if(!empty($mobilenum))
				{
					$templateid='1707161526685489215';
					$msg="Hi , your ESIC Card has been sent on your given Email ID : ".$Data['emailAddress'];
				 	$url = SMS_URL;
					$token = SMS_TOKEN;
					$credit = SMS_CREDIT;
					$sender = SMS_SENDER;
					$message = $msg;
					$number = $mobilenum;
					$sendsms = new sendsms($url,$token);
					$message_id = $sendsms->sendmessage($credit,$sender,$message,$number,$templateid);
					
					
				}
			}
			else
			{
				$result1['msg']='ESIC Card not found';
	            $result1['status']=0;
			}
	}
///////////////////////////end download esic///////////////////////////////////////////////////

}
else
{
	$result1['msg']='Bad Request.';
	$result1['status']=0;
}

echo  json_encode($result1);
?>

					

