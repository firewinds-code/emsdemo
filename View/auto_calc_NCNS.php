<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
//require(ROOT_PATH.'AppCode/nHead.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Auto calculate: NCNS </span>

<!-- Main Div for all Page -->
<div class="pim-container " >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Auto calculate: NCNS </h4>	
	 
<!-- Form container if any -->
	<div class="schema-form-section row" >
	
<?php	
$myDB=new MysqliDb();
$data_ncns = $myDB->rawQuery('select EmployeeID,createdon from ncns_cases where ncns_cases.status = 0;');
echo $mysql_error = $myDB->getLastError();
$rowCount = $myDB->count;
function dt_diff($d1 ,$d2)
{

$datetime1 = new DateTime($d1);

$datetime2 = new DateTime($d2);

$difference = $datetime1->diff($datetime2);

return($difference->d);
 
}
$myDB=new MysqliDb();
$account_head_hr = $myDB->query('select account_head from new_client_master where sub_process  = "Human Resource" limit 1');
//echo 'select account_head from new_client_master where sub_process  = "Human Resource" limit 1';
//echo "<br>";
$account_head_hr = $account_head_hr[0]['account_head'];
$alert_msg='';

if($rowCount > 0 && $data_ncns)
{
	foreach($data_ncns as $ncns_key => $ncns_value )
	{
		$PDay_dt= $myDB->query('call get_calcAtnd_fromDate("'.$account_head_hr.'","'.$ncns_value['createdon'].'")');
		
		$validate_for_ncns = 0;	
		$start = strtotime($ncns_value['createdon']);
		$end = strtotime(date('Y-m-d'));

		$days = ceil(abs($end- $start) / 86400);
		
		if((intval($days)>= 2 ) && $account_head_hr !='CE07147134')
		{
			
			
		 $empid = $ncns_value['EmployeeID'];
		 $remark = "SERVER";
		 $myDB=new MysqliDb();
		
		$rstl = $myDB->rawQuery('call manage_ncns_hr("'.$empid.'","'.$remark.'","'."SERVER".'","1")');
		$my_error = $myDB->getLastError();
		$rowCount = $myDB->count;
			if($rowCount>0)
			{
				    $_empid = $empid;
					$_rsnleave= 'NCNS Request (ABSC)';
					$_dispo= 'ABSC';
					$_dol= date('Y-m-d',time());
					$_hrcmt= 'NCNS Request (ABSC)';
					$_opscmt= 'NCNS Request (ABSC)';					
					$createBy="SERVER";
				/*	$Insert='INSERT INTO exit_emp(EmployeeID,dol,rsnofleaving,hrcmt,optcmt,disposition,createdby)VALUES("'.$_empid.'","'.$_dol.'","'.$_rsnleave.'","'.$_hrcmt.'","'.$_opscmt.'","'.$_rsnleave.'","'.$createBy.'")'; */
					$Insert='call exit_employee("'.$_empid.'","'.$_dol.'","'.$_rsnleave.'","'.$_hrcmt.'","'.$_opscmt.'","'.$createBy.'","'.$_dispo.'")';
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($Insert);
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;

					if($result && $rowCount>0)
					{
						
						$myDB=new MysqliDb();
						$result2 = $myDB->rawQuery('INSERT INTO alert_details(EmployeeID,alert_start,alert_end,type,createdon,createdby)VALUES("'.$_empid.'","'.date('Y-m-d',time()).'","'.date('Y-m-d',strtotime('+3 days')).'","NCNS In-active","'.date('Y-m-d H:i:s',time()).'","'.$createBy.'")');
						$erpr = $myDB->getLastError();
						$rowCount = $myDB->count;
						
						$myDB=new MysqliDb();
						$result_whole = $myDB->rawQuery('select whole_dump_emp_data.EmployeeID,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.clientname,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,whole_dump_emp_data.account_head,whole_dump_emp_data.designation,whole_dump_emp_data.DOJ,cd1.ofc_emailid as ah_mail,cd2.ofc_emailid as oh_mail,cd3.ofc_emailid as th_mail ,cd4.ofc_emailid as qh_mail from whole_dump_emp_data left outer join contact_details cd1 on cd1.EmployeeID = whole_dump_emp_data.account_head left outer join contact_details cd2 on cd2.EmployeeID = whole_dump_emp_data.oh left outer join contact_details cd3 on cd3.EmployeeID = whole_dump_emp_data.th left outer join contact_details cd4 on cd4.EmployeeID = whole_dump_emp_data.qh  where whole_dump_emp_data.EmployeeID  ="'.$_empid.'"');
					//	echo 'select whole_dump_emp_data.EmployeeID,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.clientname,whole_dump_emp_data.Process,whole_dump_emp_data.sub_process,whole_dump_emp_data.account_head,whole_dump_emp_data.designation,whole_dump_emp_data.DOJ,cd1.ofc_emailid as ah_mail,cd2.ofc_emailid as oh_mail,cd3.ofc_emailid as th_mail ,cd4.ofc_emailid as qh_mail from whole_dump_emp_data left outer join contact_details cd1 on cd1.EmployeeID = whole_dump_emp_data.account_head left outer join contact_details cd2 on cd2.EmployeeID = whole_dump_emp_data.oh left outer join contact_details cd3 on cd3.EmployeeID = whole_dump_emp_data.th left outer join contact_details cd4 on cd4.EmployeeID = whole_dump_emp_data.qh  where whole_dump_emp_data.EmployeeID  ="'.$_empid.'"';
						$erpr = $myDB->getLastError();
						$rowCount = $myDB->count;
						if($rowCount> 0 && $result_whole)
						{
							$email_empliyeeID[] = $result_whole[0]['ah_mail'];
							$email_empliyeeID[] = $result_whole[0]['oh_mail'];
							$email_empliyeeID[] = $result_whole[0]['th_mail'];
							$email_empliyeeID[] = $result_whole[0]['qh_mail'];
							$email_table .= '<td style="padding:5px;">'.$result_whole[0]['EmployeeID'].'</td>';
							$email_table .= '<td  style="padding:5px;">'.$result_whole[0]['EmployeeName'].'</td>';
							$email_table .= '<td  style="padding:5px;"><b>NCNS Request (ABSC)</b></td>';					
							$email_table .= '<td style="padding:5px;">'.$result_whole[0]['designation'].'</td>';
							$email_table .= '<td style="padding:5px;">'.$result_whole[0]['clientname'].'</td>';
							$email_table .= '<td style="padding:5px;">'.$result_whole[0]['Process'].'</td>';
							$email_table .= '<td style="padding:5px;">'.$result_whole[0]['sub_process'].'</td>';
							$email_table .= '<td style="padding:5px;">'.$result_whole[0]['account_head'].'</td>';
							$email_table .= '</tr>';
						}
						
						
					}				
				
					$msg ="You are not reporting to office since long time without any information to us, So you are In-active from Cogent on ".date('d/m/Y',time()).". To ensure that advised you to contact HR department in person ASAP";
				
				 	
				 	
				 	$myDB=new MysqliDb();
				 	
					$rst_contact = $myDB->rawQuery('select mobile,altmobile from contact_details where EmployeeID = "'.$empid.'" limit 1');
					if(!empty($rst_contact[0]['mobile']))
					{
						
						//$lbl_msg = ' SMS : '.$response;
					}
				 	
				    $alert_msg = '<span class="text-success">Request to In-active for selected Employee is complete Successfully.</span>';
			}
			else
			{
				$alert_msg = '<span class="text-danger">Request to In-active for selected Employee is not completed '.$my_error.'</span>';
			}
		
		}	
	}
	if(!empty($email_empliyeeID) && count($email_empliyeeID) > 0 && !empty($email_table))
	{
			$myDB=new MysqliDb();
			$pagename='auto_calc_NCNS';
			$select_email_array=$myDB->rawQuery("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."'");
			$mysql_error = $myDB->getLastError();
			$rowCount = $myDB->count;
			$array_to_mail = array_unique($email_empliyeeID);
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = EMAIL_HOST; 
			$mail->SMTPAuth = EMAIL_AUTH; 
			$mail->Username =EMAIL_USER;
			$mail->Password =EMAIL_PASS;
			$mail->SMTPSecure = EMAIL_SMTPSecure;
			$mail->Port = EMAIL_PORT;
			$mail->setFrom(EMAIL_FROM, 'EMS:Cogent Alert System');
			$validate = 0;
			//$mail->AddAddress('rinku.kumari@cogenteservices.in');
			foreach($array_to_mail as $email)
			{
				if($email != '')
				{
					$mail->AddAddress($email);
						
				}
				
			}
			
			
			if($rowCount>0){
				foreach($select_email_array as $key=>$email_array){
					$email_address=$email_array['email_address'];
					if($email_address!=""){
						$mail->AddAddress($email_address);
					}
					$cc_email=$email_array['ccemail'];
					if($cc_email!=""){
						$mail->addCC($cc_email);
					}
				}
				
			}			
		
			$mail->Subject = 'EMS '.EMS_CenterName.':  Alert for Auto NCNS Request Accept ['.date('d M,Y',time()).']';
			
			$mail->isHTML(true);
			
			$pwd_='<span>Dear All,<br/><br/><span><b>Please find below the concern Employee in table are Inactive from the system</b></span>.<br /><br/><table border="1"><thead><tr style="font-weight: bold;border-bottom:2px solid black;"><td style="padding:5px;">EmployeeID</td><td style="padding:5px;">Employee Name</td><td style="padding:5px;">Alert For</td><td style="padding:5px;">Designation</td><td style="padding:5px;">Client</td><td style="padding:5px;">Process</td><td style="padding:5px;">Sub-process</td><td style="padding:5px;">Account Head ID</td></tr></thead><tbody>'.$email_table.'</tbody></table><br/><br/> Thank You</b>.<br/>Regard,<br/>EMS - Alert <br />';
			$mail->Body = $pwd_;
			
			if(!$mail->send())
		 	{
		 		$mymsg .='.Mailer Error:'. $mail->ErrorInfo;
		  	} 
			else
			{
			    
			   $mymsg .='.Mail Send successfully.';
			  
			}
			if($mymsg!=""){
				//$(function(){ toastr.error($mymsg) });
				 echo "<script>$(function(){ toastr.error('".$mymsg."') }); </script>";
			}
			
	}

}

?>	
		</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div> 
