<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';

function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
settimestamp('AutoEmail_ActionLog','Strat');

// Set path to CSV file 4.12.2017
$csvFile = '../Log/log_'.date('j.n.Y',strtotime("previous day")).'.csv';
$sachin_data = array();
$csv = readCSV($csvFile);
foreach($csv as $key=>$value)
{
	if(isset($value[4]))
	{
		if($value[4] == 'User: CE03070003')
		{
		   $sachin_data[] = $value;
		}
	}
}

$fileName= 'Action_log_ems.csv';
$fp = fopen($fileName, 'w');
fputcsv($fp, array("IP Date","Year","Time","Action","User","Description"));

foreach ($sachin_data as $key=>$fields) {
    fputcsv($fp, $fields);
}
settimestamp('AutoEmail_ActionLog','END');
fclose($fp);
if(count($sachin_data) <= 0)
die("no data found");
	echo "filesize=".$file_size = filesize($fileName);
		if(file_exists($fileName) && !empty($fileName)){
	$count= 0;
		$myDB=new MysqliDb();
		$pagename='automail_action_log_eod';
		$select_email_array=$myDB->rawQuery("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."'");
		echo "filesize=".$file_size = filesize($fileName);
		
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = EMAIL_HOST; 
		$mail->SMTPAuth = EMAIL_AUTH;
		$mail->Username = EMAIL_USER;   
		$mail->Password = EMAIL_PASS;                        
		$mail->SMTPSecure = EMAIL_SMTPSecure;
		$mail->Port = EMAIL_PORT; 
		$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
		/*$mail->AddAddress('mithlesh.kumar@cogenteservices.com');*/
		if(count($select_email_array)>0)
		{
			foreach($select_email_array as $select_email_array_val)
			{
				$email_address=$select_email_array_val['email_address'];
				if($email_address!="")
				{
					$mail->AddAddress($email_address);
				}
				$cc_email=$select_email_array_val['ccemail'];
				if($cc_email!="")
				{
					$mail->addCC($cc_email);
				}
			}
		}
		$mail->AddAttachment($fileName);
		$mail->Subject = 'EMS '.EMS_CenterName.', Action Log Report ['.date('d M,Y',strtotime("previous day")).']';
		$mail->isHTML(true);
		$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear Sir / Mam,<br/><br/><span><b>Please find attached the Action Log Report for '.EMS_CenterName.'.</b></span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br /><br /><br />'.'Regards,<br /><br/> EMS Mailer Services.<div>';
		$mail->Body = $pwd_;
		$mymsg = '';
		if(!$mail->send())
	 	{
	 		settimestamp('AutoEmail_ActionLog','Email Not Sent');
	 		echo '.Mailer Error:'. $mail->ErrorInfo;
	  	} 
		else
		 {
		    settimestamp('AutoEmail_ActionLog','Email Sent');
		    echo  '.Mail Send successfully.';
		 }
}
        else
        {
			//var_dump($chk_task);
		}
?>