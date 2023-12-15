<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
$alert_msg ='';
$EmployeeID=$btnShow=$df_id='';
$data_update_alert = '';
$experience_detail='';
$source_recruitment_Desc='';
$interview_id='';
$createBy=$_SESSION['__user_logid'];
$usertype=$_SESSION['__user_type'];
	$mailler_msg='';
	$cm_id='';
	$sub_process1='';
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
{
	$dept=(isset($_POST['txt_empmap_dept'])? $_POST['txt_empmap_dept'] : null);
	$client=(isset($_POST['txt_empmap_client'])? $_POST['txt_empmap_client'] : null);
	$process=(isset($_POST['txt_empmap_process'])? $_POST['txt_empmap_process'] : null);
	 $cm_id=$subprocess=(isset($_POST['txt_empmap_subprocess'])? $_POST['txt_empmap_subprocess'] : null);
	$desg=(isset($_POST['txt_empmap_desg'])? $_POST['txt_empmap_desg'] : null);
	$hdept=(isset($_POST['hdepartment'])? $_POST['hdepartment'] : null);
	$hdesg=(isset($_POST['hdesignation'])? $_POST['hdesignation'] : null);
	$doj=(isset($_POST['txt_empmap_doj'])? $_POST['txt_empmap_doj'] : null);
		
	$pass=(isset($_POST['txt_empmap_pass'])? $_POST['txt_empmap_pass'] : null);
	
	/*$pass = $_obj_ed_ems->Encrypt($pass);*/
	
	$level=(isset($_POST['txt_empmap_level'])? $_POST['txt_empmap_level'] : null);
	$function_=(isset($_POST['txt_empmap_function'])? $_POST['txt_empmap_function'] : null);
	$source_recruitment_id=(isset($_POST['txt_Personal_Ref_id'])? $_POST['txt_Personal_Ref_id'] : null);
	$source_recruitment_Desc=(isset($_POST['txt_Personal_Ref_Desc'])? $_POST['txt_Personal_Ref_Desc'] : null);
	$consutancy_id=(isset($_POST['txt_Personal_Ref_rName'])? $_POST['txt_Personal_Ref_rName'] : null);
 	//$cm_id=(isset($_POST['cm_id'])? $_POST['cm_id'] : null);
}
else
{
	$dept=$client=$process=$subprocess=$desg=$doj=$pass=$level=$function_=$source_recruitment_id=$source_recruitment_Desc=$consutancy_id=$emptype='';
}


/*--------elect rtype from salary by rinku-----------*/
$rt_type="NA";$emp_salary=0;
$location = $loc = '';
if(isset($_REQUEST['empid']))
{
	$EmpID=$_REQUEST['empid'];
	$getQuery='select rt_type,ctc from salary_details where EmployeeID = "'.$EmpID.'" limit 1';
	$myDB=new MysqliDb();
	$resultBy=$myDB->rawQuery($getQuery);						
		foreach($resultBy as $key=>$value)
		{	
			$rt_type = $value['rt_type'];
			$emp_salary = $value['ctc'];
		}
      
      
$myDB=new MysqliDb();	
	$EmployeeID=strtoupper($_REQUEST['empid']);
	$sql='select location from personal_details where EmployeeID = "'.$EmployeeID.'"';
	$result=$myDB->rawQuery($sql);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		if(isset($result[0]['location'])){
			$loc = $result[0]['location'];
		}
		
	}
	
	if($loc=="7" || $loc=="1" || $loc=="2" || $loc=="3" || $loc=="4")
	{
		if(substr($EmployeeID,0,2) != 'TE')
		{
			if($loc=="7")
			{
				if(substr($EmployeeID,0,3) == 'CEK')
				{
					$emptype = "Cogent";
				}
				else if(substr($EmployeeID,0,3) == 'FBK')
				{
					$emptype = "Flipkart";
				}
				else if(substr($EmployeeID,0,3) == 'CCE')
				{
					$emptype = "CCE";
				}
			}
			else
			{
				if(substr($EmployeeID,0,2) == 'CE' || substr($EmployeeID,0,2) == 'AE' || substr($EmployeeID,0,2) == 'RS' || substr($EmployeeID,0,2) == 'MU' || substr($EmployeeID,0,2) == 'OC')
				{
					$emptype = "Cogent";		
				}
				else if(substr($EmployeeID,0,3) == 'CCE')
				{
					$emptype = "CCE";
				}
			}
			
		}
	}
		
}
$EMS_CenterName='';
$dir_location = '';
if(isset($_POST['employment_type']) && $_POST['employment_type']!=""){

	if($_POST['employment_type']=="1")
	{
		$locationRaw=rawurlencode('Noida C121');
		$EMS_CenterName='Noida';
	}
	else if($_POST['employment_type']=="2")
	{
		$locationRaw=rawurlencode('Mumbai');
		$EMS_CenterName='Mumbai';
	}
	else if($_POST['employment_type']=="3")
	{
		$locationRaw=rawurlencode('Meerut');
		$EMS_CenterName='Meerut';
		$dir_location = 'Meerut/';
	}
	else if($_POST['employment_type']=="4")
	{
		$locationRaw=rawurlencode('Bareilly');
		$EMS_CenterName='Bareilly';
		$dir_location="Bareilly/";
	}
	else if($_POST['employment_type']=="5")
	{
		$locationRaw=rawurlencode('Vadodara');
		$EMS_CenterName='Vadodara';
		$dir_location="Vadodara/";
	}
	else if($_POST['employment_type']=="6")
	{
		$locationRaw=rawurlencode('Mangalore');
		$EMS_CenterName='Mangalore';
		$dir_location="Manglore/";
	}
	else if($_POST['employment_type']=="7")
	{
		$locationRaw=rawurlencode('Bangalore');
		$EMS_CenterName='Bangalore';
		$dir_location="Bangalore/";
	}
	
}										

/*--------End seleection  rtype from salary by rinku-----------*/
//Check Employee is exist or not

if(isset($_REQUEST['empid'])&& !isset($_POST['EmployeeID']))
{
	$EmployeeID=$_REQUEST['empid'];
	$getDetails='call get_empmap("'.$EmployeeID.'")';
	$myDB=new MysqliDb();
	$result_all=$myDB->query($getDetails);
	$predate=$cm_id="";
	if($result_all)
	{
		$cm_id=$result_all[0]['cm_id'];
		$df_id=$result_all[0]['df_id'];		
		$predate=$doj=$result_all[0]['dateofjoin'];
		$pass = $result_all[0]['password'];
		/*if(!empty($pass) && $_obj_ed_ems->Decrypt($pass))
		{
			$pass = $_obj_ed_ems->Decrypt($pass);
		}*/
		$level=$result_all[0]['emp_level'];
		
		  $call_cmid='call get_clientdata_bycmid('.$cm_id.')';
		 $myDB=new MysqliDb();
		 $rst_client=$myDB->query($call_cmid);
		 
		 if($rst_client)
		 {
		 	$dept=$rst_client[0]['dept_id'];
			$client=$rst_client[0]['client_name'];
			$process=$rst_client[0]['process'];
			$sub_process1=$rst_client[0]['sub_process'];
		 	
		 }
		 
		 $call_dfid='select des_id,function_id from df_master where df_id=("'.$df_id.'")';
		 $myDB=new MysqliDb();
		 $rst_df=$myDB->query($call_dfid);
		 
		 if($rst_df)
		 {
		 	
			$desg=$rst_df[0]['des_id'];
			$function_=$rst_df[0]['function_id'];
		 	
		 }
	}
	
	 
	 
	 
	 
	$getDetails='call get_personal("'.$EmployeeID.'")';
	$myDB=new MysqliDb();
	$result_all=$myDB->query($getDetails);
	if($result_all)
	{	
		$source_recruitment_id=$result_all[0]['ref_id'];
		$source_recruitment_Desc=$result_all[0]['ref_txt'];
		$interview_id=$result_all[0]['INTID'];
	}else
	{
		echo "<script>$(function(){ toastr.error('Wrong Employee To Search') });window.location='".URL."'</script>";
	}
}
elseif(isset($_POST['EmployeeID'])&&$_POST['EmployeeID']!='')
{
	$EmployeeID=$_POST['EmployeeID'];
}


	if($cm_id>0){
		$myDB=new MysqliDb();
		//echo 'call get_clientdata_bycmid('.$cm_id.')';
		$call_subprocess_array=$myDB->query('call get_clientdata_bycmid('.$cm_id.')');
		$sub_process1=$call_subprocess_array[0]['sub_process'];
	}
	
if(isset($_POST['btn_empmap_Save'])&&$EmployeeID!='')
{
	if(isset($_POST['rt_type']) and $_POST['rt_type']!='NA')
	{
		
		$rt_type=$_POST['rt_type'];
		
		$emp_IDorg = $EmployeeID;
		$validate = 0 ;
		
		if($_SESSION["__ut_temp_check"] != 'ADMINISTRATOR')
		{
			
		
		
		$myDB = new MysqliDb();
		$result_ac_validate = $myDB->query("SELECT  distinct(edu_name)  FROM education_details where EmployeeID= '".$EmployeeID."'  and    edu_name in('10th','12th') "); 
		if(count($result_ac_validate )>=2)
		{
			
			$myDB = new MysqliDb();
			$experience_validate = $myDB->query("select  exp_type from experince_details where EmployeeID='".$EmployeeID."' and exp_type!=''  ");
			//echo "experince=".count($experience_validate);
			if(count($experience_validate)>0)
			{
				$myDB = new MysqliDb();
				$result_ac_validate = $myDB->query('select dov_value,doc_file from doc_details where EmployeeID = "'.$EmployeeID.'" and doc_stype = "Aadhar Card";');
				
				if(count($result_ac_validate) > 0 && $result_ac_validate && !empty($result_ac_validate[0]['doc_file']) && !empty($result_ac_validate[0]['dov_value']) && strlen($result_ac_validate[0]['dov_value']) == 12 && is_numeric($result_ac_validate[0]['dov_value']))
				{
					//print_r($_POST);
		
				$myDB = new MysqliDb();
				//echo 'call get_mapvalidation_check("'.$EmployeeID.'")';
			
				$result_validate = $myDB->query('call get_mapvalidation_check("'.$EmployeeID.'")');//for check address detail and contact detail
				if($result_validate)
				{
					if(count($result_validate) > 0)
					{
						$validate = 1;
					}
				}
				$validate_date = 0;
				if(substr($EmployeeID,0,2) == 'TE')
				{
					
					$tempdate = date('Y-m-d',strtotime('-2 days today'));
					$today_date = date('Y-m-d',strtotime('today'));
					if($doj < $tempdate || $doj > $today_date)
					{
						$validate_date = 1;
					}
				}
				$mailler_msg='';
				
				if($validate == 1 && $validate_date == 0)
				{
					if(substr($EmployeeID,0,2) == 'TE')
					{
						$myDB=new MysqliDb();
						
						$orderID = substr($EmployeeID,6,strlen($EmployeeID) - 1);
						$emp_alias = '';	
						
						if($_POST['employment_type']=="1")
						{
							if($_POST['employment_type_fk']=="CCE")
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='CCE';
							}
							else
							{
								if($desg == 9 || $desg == 12)
								{
									if($_POST['emp_salary'] >= '15800')
									{
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias ='CE';
									}
									else
									{
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RS%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias ='RS';	
									}
								}
								else
								{
									$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
									$orderID = $orderID[0]['EMPID'];
									$emp_alias ='CE';
								}
							}
							
						}
						
						else if($_POST['employment_type']=="2")
						{
							if($_POST['employment_type_fk']=="CCE")
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='CCE';
							}
							else
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "MU%"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='MU';
							}
							
						}
						
						else if($_POST['employment_type']=="3")
						{
							if($_POST['employment_type_fk']=="CCE")
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='CCE';
							}
							else
							{
								if($desg == 9 || $desg == 12)
								{
									if($_POST['emp_salary'] >= '15800')
									{
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias ='CEM';
									}
									else
									{
										$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RSM%"');
										$orderID = $orderID[0]['EMPID'];
										$emp_alias ='RSM';
									}
									
								}
								else
								{
									$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
									$orderID = $orderID[0]['EMPID'];
									$emp_alias ='CEM';
								}
							}
							
						}
						
						else if($_POST['employment_type']=="4")
						{
							if($_POST['employment_type_fk']=="CCE")
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='CCE';
							}
							else
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEB%"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='CEB';
							}
							
						}
						
						else if($_POST['employment_type']=="5")
						{
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEV%"');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias ='CEV';
						}
						
						else if($_POST['employment_type']=="6")
						{
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CMK%"');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias ='CMK';
						}
						
						else if($_POST['employment_type']=="7")
						{
							if($_POST['employment_type_fk']=="Cogent")
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEK%" ');
								$emp_alias ='CEK';
							}
							else if($_POST['employment_type_fk']=="Flipkart")
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "FBK%" ');
								$emp_alias ='FBK';
							}
							else if($_POST['employment_type_fk']=="CCE")
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
								$emp_alias ='CCE';
							}
							
							$orderID = $orderID[0]['EMPID'];
						}
										
						if($emp_alias != '')
						{
							if($orderID)
							{
								$getEmpID = $orderID;
								
								if($getEmpID < 10)
								{
									$EmployeeID = $emp_alias.date('my',strtotime($doj)).'000'.$getEmpID;
								}
								else if($getEmpID >= 10 && $getEmpID < 100)
								{
									$EmployeeID = $emp_alias.date('my',strtotime($doj)).'00'.$getEmpID;
								}
								else if($getEmpID >= 100 && $getEmpID < 1000)
								{
									$EmployeeID = $emp_alias.date('my',strtotime($doj)).'0'.$getEmpID;
								}		
								else
								{
									$EmployeeID = $emp_alias.date('my',strtotime($doj)).''.$getEmpID;
								}
								
								if($EmployeeID !='')
								{
									//echo 'call update_mapedemp("'.$EmployeeID.'","'.$emp_IDorg.'","'.$rt_type.'")'
									
									$int_url=INTERVIEW_URL."getSalary.php?intid=".$_POST['hiddenIntID'];
									$curl = curl_init();
									curl_setopt($curl, CURLOPT_URL, $int_url);
									curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($curl, CURLOPT_HEADER, false);
									$data = curl_exec($curl);	
									$salary_array=json_decode($data);
									
									$myDB=new MysqliDb();
									$sql_updateper = $myDB->query('delete from salary_details where EmployeeID="'.$emp_IDorg.'" ');
									
									if(count($salary_array)>0)
									{
										 $insertData="call manageOfferSalary('".$salary_array->salary."','".$_POST['hiddenIntID']."','".$emp_IDorg."','".$_SESSION['__user_logid']."')";
										$myDB=new MysqliDb();
										$myDB->query($insertData);
										$error = $myDB->getLastError();
									}
									//die;
									$myDB=new MysqliDb();
									$sql_updateper = $myDB->query('call update_mapedemp("'.$EmployeeID.'","'.$emp_IDorg.'","'.$rt_type.'")');
									$mysql_error = $myDB->getLastError();
									//$affacted_row = mysql_affected_rows();
									echo "<script>$(function(){ toastr.error('Employee ID is generated, Employee ID -".$EmployeeID." ".$mysql_error."'); }); </script>";
									$myDB=new MysqliDb();
									$select_empinfo=$myDB->query("select distinct t.EmployeeID,p.EmployeeName  ,doc_stype as Adharcard, dov_value ,ct.mobile , ad.address from doc_details t left join address_details ad on t.EmployeeID=ad.EmployeeID left join contact_details ct on ct.EmployeeID=t.EmployeeID Inner Join personal_details p on t.EmployeeID=p.EmployeeID  where t.EmployeeID='".$EmployeeID."' and doc_stype  in('Aadhar Card') order by doc_id desc");
									
									if(count($select_empinfo)>0)
									{
										$empdata_array=$select_empinfo[0];
										$Adharcard=$empdata_array['dov_value'];
										$address='NA';
										if($empdata_array['address']!=""){
											$address=rawurlencode($empdata_array['address']);
										}
										
										$mobile=$empdata_array['mobile'];
										if(strstr($empdata_array['EmployeeName'],' ')){
											$EmployeeName=rawurlencode($empdata_array['EmployeeName']);
										}else{
											$EmployeeName=$empdata_array['EmployeeName'];
										}
										

										
										$response = file_get_contents("http://lb.cogentlab.com:8081/Investment/putadhar.php?EmployeeID=".$EmployeeID."&EmployeeName=".$EmployeeName."&AdharCardNo=".$Adharcard."&PanCardNo=NA&ContactNo=".$mobile."&Address=".$address."&CreatedBy=".$createBy."&Location=".$locationRaw."");
										$myDB=new MysqliDb();
										$docAllStatus=$myDB->query("Insert into doc_al_status set EmployeeID='".$EmployeeID."',  DOJ='".$doj."',validate='0' ");
										?>
										
								<?php	
								}
									if(strtolower(date('l',strtotime($doj))) == 'sunday')
									{
										
										$myDB=new MysqliDb();
										$data_roster_insert = $myDB->query('call insert_roster_tmp("'.strtoupper($EmployeeID).'","WO","WO","'.date('Y-m-d',strtotime($doj)).'",1,"WFO")');				
									
									}
									else
									{
										$begin_roster = new DateTime($doj);
										$end_roster   = new DateTime(date('Y-m-d',strtotime('next sunday')));
										$weekOFF = 0;
										$j = 1;$jj = 1;$daycount = 1;

										for($i = $begin_roster; $begin_roster <= $end_roster; $i->modify('+1 day'))
									    {
									        $dateT_ins = $i->format('Y-m-d');
									        //$myDB =new MysqliDb();
											//$sql_insert_roster = $myDB->query('call insert_roster_tmp("'.strtoupper($EmployeeID).'","09:00","18:00","'.$dateT_ins.'",1)');
											$sql_roster_insert = '';
									        if(strtolower(date('l',strtotime($i->format('Y-m-d')))) == 'sunday')
									        {
												$sql_roster_insert = 'call insert_roster_tmp("'.strtoupper($EmployeeID).'","WO","WO","'.$dateT_ins.'",1,"WFO")';	
											}
											else	
											{
												$sql_roster_insert = 'call insert_roster_tmp("'.strtoupper($EmployeeID).'","09:00","18:00","'.$dateT_ins.'",1,"WFO")';	
											}
											$myDB=new MysqliDb();
											$data_roster_insert = $myDB->query($sql_roster_insert);
									    }
									}
									
								}
								//	------------------------------------------------------------------------------
									
									
								if(($_POST['employment_type']=="1" || $_POST['employment_type']=="3" || $_POST['employment_type']=="4" || $_POST['employment_type']=="5" || $_POST['employment_type']=="6" || $_POST['employment_type']=="7" || $_POST['employment_type']=="8") && $desg != 9 && $desg!= 12 && $desg!='')
								{
									
									$myDB=new MysqliDb();
									$pagename='employee_map';
									$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location='".$_POST['employment_type']."'");	
									
									$mail = new PHPMailer;
									$mail->isSMTP();
									$mail->Host = EMAIL_HOST; 
									$mail->SMTPAuth = EMAIL_AUTH;
									$mail->Username = EMAIL_USER;   
									$mail->Password = EMAIL_PASS;                        
									$mail->SMTPSecure = EMAIL_SMTPSecure;
									$mail->Port = EMAIL_PORT; 
									$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
									if(count($select_email_array) > 0 && $select_email_array){
										foreach($select_email_array as $Key=>$val)
							        	{
							        		$email_address = $val['email_address'];
											
											if($email_address!=""){
												$mail->AddAddress($email_address);
											}
											$cc_email=$val['ccemail'];
											
											if($cc_email!=""){
												$mail->addCC($cc_email);
											}
										}			
									}	
									$mail->Subject = 'Email ID Creation Request '.$EMS_CenterName.' ['.date('d M,Y',time()).']';
									$mail->isHTML(true);
									$myDB=new MysqliDb();
									$getDetails=$myDB->query("SELECT EmployeeName  FROM personal_details where EmployeeID='".$EmployeeID."'");
									$EmployeeName='';
									if(isset($getDetails[0]['EmployeeName'])){
										$EmployeeName=$getDetails[0]['EmployeeName'];
									}
									$myDB=new MysqliDb();
									$getCLientName=$myDB->query("select client_name  from client_master  where client_id='".$client."' ");
									$client_name='';
									if(isset($getCLientName[0]['client_name'])){
										$client_name=$getCLientName[0]['client_name'];
									}
							        $Body ="Hello sir,<br>Please create the Email ID of the Employee<br><br>
							        <table border='1'>";
							        $Body .="<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Client</b></td><td><b>Process</b></td><td><b>Sub-process</b></td><td><b>Designation</b></td><td><b>Department</b></td></tr>";
							       $Body.="<tr><td>".$EmployeeID."</td><td>".$EmployeeName."</td><td>".$client_name."</td><td>".$process."</td><td>".$sub_process1."</td><td>".$hdesg."</td><td>".$hdept."</td></tr>";
							       
							     	$Body .="</table><br><br>Thanks EMS Team";
									$mail->Body = $Body;
								
									if(!$mail->send())
								 	{
								 		$mailler_msg= 'Mailer Error:'. $mail->ErrorInfo;
								  	} 
									else
									 {
									    
									   $mailler_msg=   'and Email Id creation request raised.';
									 }
								}
																	 
							}
							else
							{
								$EmployeeID = '';
							}
						}
						
						else
						{
							$EmployeeID = '';
						}
					}
				
					if( $EmployeeID != '')
					{	
						$myDB=new MysqliDb();
						$createBy=$_SESSION['__user_logid'];
						
							$sqlInsertMap='call manage_map_employee("'.$EmployeeID.'","'.$dept.'","'.$client.'","'.$process.'","'.$subprocess.'","'.$desg.'","'.$doj.'","'.$level.'","'.$function_.'","'.$createBy.'")';
						//echo $sqlInsertMap;
						
							$result=$myDB->query($sqlInsertMap);
							if($desg != 9 && $desg != 12)
							{
								$sqlInsertMap='call update_apr_month("'.$EmployeeID.'","'.$doj.'")';
																
								$result=$myDB->query($sqlInsertMap);
							}
							
							
							$mysql_error =$myDB->getLastError();
							if(empty($mysql_error))
							{
								if($source_recruitment_id=='5'){
									
									$myDB=new MysqliDb();
								//echo 	"select payout,tenure from manage_consultancy where cm_id='".$cm_id."' and consultancy_id='".$consutancy_id."' ";
								$consult_array=$myDB->rawQuery("select payout,tenure from manage_consultancy where cm_id='".$cm_id."' and consultancy_id='".$consutancy_id."' ");
								$tenure='';
									if(count($consult_array)>0)
									{
										$tenure=$consult_array[0]["tenure"];
										$payout=$consult_array[0]["payout"];
										$myDB=new MysqliDb();
										$myDB=new MysqliDb();
										 $manage_query=$myDB->rawQuery('call consultancy_emp_manage("'.$EmployeeID.'","'.$consutancy_id.'" ,"'.$cm_id.'","'.$doj.'", "'.$tenure.'","'.$payout.'", "'.$createBy.'")');
									}	
									
									
								}
									
								$myDB=new MysqliDb();
								$myDB->rawQuery("update personal_details set ref_id='".$source_recruitment_id."', ref_txt='".$source_recruitment_Desc."' where EmployeeID='".$EmployeeID."'");
								echo "<script>$(function(){ toastr.success('Saved Successfully ".$mailler_msg."'); }); </script>";	
								
							}
							else
							{
								echo "<script>$(function(){ toastr.error('Employee Not Assigned Try again ".$mysql_error."'); }); </script>";
							}
					}
					else
					{
						echo "<script>$(function(){ toastr.error('Employee Not Assigned'); }); </script>";
					}
				}
				else
				{
					if($validate_date == 1)
					{
						echo "<script>$(function(){ toastr.error('Please fill two day older date for joining for Employee.'); }); </script>";
					}
					else
					{
						
						echo "<script>$(function(){ toastr.error('Please fill following page details to map the Employee.1.Personal Details, 2.Education Details, 3.Contact Details, 4.Address Details'); }); </script>";
					}
				}
				
			 
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Please provide a valid Aadhar Card 12 digit number and attachment file on Contact Page.'); }); </script>";
		}
			}else{
				echo "<script>$(function(){ toastr.error('Please add Experience Details on Experince Page.'); }); </script>";
			}	
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Please add educatinal details (10th & 12th) on Education Page.'); }); </script>";
		}
		
		}
		
		else
		{
			if(substr($EmployeeID,0,2) == 'TE')
			{
				
				$myDB=new MysqliDb();
				
				$orderID = substr($EmployeeID,6,strlen($EmployeeID) - 1);
				$emp_alias = '';	
				
				if($_POST['employment_type']=="1")
				{
					if($_POST['employment_type_fk']=="CCE")
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias ='CCE';
					}
					else
					{
						if($desg == 9 || $desg == 12)
						{
							if($_POST['emp_salary'] >= '15800')
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='CE';
							}
							else
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RS%"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='RS';	
							}
						}
						else
						{
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CE%" and substring(Employeeid,1,3)!="CEN"');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias ='CE';
						}
					}
					
				}
						
				else if($_POST['employment_type']=="2")
				{
					if($_POST['employment_type_fk']=="CCE")
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias ='CCE';
					}
					else
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 6)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "MU%"');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias ='MU';
					}
					
				}
						
				else if($_POST['employment_type']=="3")
				{
					if($_POST['employment_type_fk']=="CCE")
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias ='CCE';
					}
					else
					{
						if($desg == 9 || $desg == 12)
						{
							if($_POST['emp_salary'] >= '15800')
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='CEM';
							}
							else
							{
								$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "RSM%"');
								$orderID = $orderID[0]['EMPID'];
								$emp_alias ='RSM';
							}
							
						}
						else
						{
							$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEM%"');
							$orderID = $orderID[0]['EMPID'];
							$emp_alias ='CEM';
						}
					}
					
				}
						
				else if($_POST['employment_type']=="4")
				{
					if($_POST['employment_type_fk']=="CCE")
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias ='CCE';
					}
					else
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEB%"');
						$orderID = $orderID[0]['EMPID'];
						$emp_alias ='CEB';
					}
					
				}
						
				else if($_POST['employment_type']=="5")
				{
					$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEV%"');
					$orderID = $orderID[0]['EMPID'];
					$emp_alias ='CEV';
				}
				
				else if($_POST['employment_type']=="6")
				{
					$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CMK%"');
					$orderID = $orderID[0]['EMPID'];
					$emp_alias ='CMK';
				}
						
				else if($_POST['employment_type']=="7")
				{
					if($_POST['employment_type_fk']=="Cogent")
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CEK%" ');
						$emp_alias ='CEK';
					}
					else if($_POST['employment_type_fk']=="Flipkart")
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "FBK%" ');
						$emp_alias ='FBK';
					}
					else if($_POST['employment_type_fk']=="CCE")
					{
						$orderID = $myDB->query('select ifnull((max(cast(RIGHT(EmployeeID,(length(EmployeeID) - 7)) as unsigned)) + 1),1) EMPID from personal_details where EmployeeID like "CCE%" ');
						$emp_alias ='CCE';
					}
					
					$orderID = $orderID[0]['EMPID'];
				}
										
				if($emp_alias != '')
				{
					if($orderID)
					{
						$getEmpID = $orderID;
						
						if($getEmpID < 10)
						{
							$EmployeeID = $emp_alias.date('my',strtotime($doj)).'000'.$getEmpID;
						}
						else if($getEmpID >= 10 && $getEmpID < 100)
						{
							$EmployeeID = $emp_alias.date('my',strtotime($doj)).'00'.$getEmpID;
						}
						else if($getEmpID >= 100 && $getEmpID < 1000)
						{
							$EmployeeID = $emp_alias.date('my',strtotime($doj)).'0'.$getEmpID;
						}		
						else
						{
							$EmployeeID = $emp_alias.date('my',strtotime($doj)).''.$getEmpID;
						}
								
							if($EmployeeID !='')
							{
								//echo 'call update_mapedemp("'.$EmployeeID.'","'.$emp_IDorg.'","'.$rt_type.'")'
								
								$int_url=INTERVIEW_URL."getSalary.php?intid=".$_POST['hiddenIntID'];
								$curl = curl_init();
								curl_setopt($curl, CURLOPT_URL, $int_url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_HEADER, false);
								$data = curl_exec($curl);	
								$salary_array=json_decode($data);
								
								$myDB=new MysqliDb();
								$sql_updateper = $myDB->query('delete from salary_details where EmployeeID="'.$emp_IDorg.'" ');
								
								if(count($salary_array)>0)
								{
									 $insertData="call manageOfferSalary('".$salary_array->salary."','".$_POST['hiddenIntID']."','".$emp_IDorg."','".$_SESSION['__user_logid']."')";
									$myDB=new MysqliDb();
									$myDB->query($insertData);
									$error = $myDB->getLastError();
								}
									//die;
							$myDB=new MysqliDb();
							$sql_updateper = $myDB->query('call update_mapedemp("'.$EmployeeID.'","'.$emp_IDorg.'","'.$rt_type.'")');
							$mysql_error = $myDB->getLastError();
							//$affacted_row = mysql_affected_rows();
							echo "<script>$(function(){ toastr.error('Employee ID is generated, Employee ID -".$EmployeeID." ".$mysql_error."'); }); </script>";
							$myDB=new MysqliDb();
							$select_empinfo=$myDB->query("select distinct t.EmployeeID,p.EmployeeName  ,doc_stype as Adharcard, dov_value ,ct.mobile , ad.address from doc_details t left join address_details ad on t.EmployeeID=ad.EmployeeID left join contact_details ct on ct.EmployeeID=t.EmployeeID Inner Join personal_details p on t.EmployeeID=p.EmployeeID  where t.EmployeeID='".$EmployeeID."' and doc_stype  in('Aadhar Card') order by doc_id desc");
							
							if(count($select_empinfo)>0)
							{
								$empdata_array=$select_empinfo[0];
								$Adharcard=$empdata_array['dov_value'];
								$address='NA';
								if($empdata_array['address']!=""){
									$address=rawurlencode($empdata_array['address']);
								}
								
								$mobile=$empdata_array['mobile'];
								if(strstr($empdata_array['EmployeeName'],' ')){
									$EmployeeName=rawurlencode($empdata_array['EmployeeName']);
								}else{
									$EmployeeName=$empdata_array['EmployeeName'];
								}
										

										
										$response = file_get_contents("http://lb.cogentlab.com:8081/Investment/putadhar.php?EmployeeID=".$EmployeeID."&EmployeeName=".$EmployeeName."&AdharCardNo=".$Adharcard."&PanCardNo=NA&ContactNo=".$mobile."&Address=".$address."&CreatedBy=".$createBy."&Location=".$locationRaw."");
										$myDB=new MysqliDb();
										$docAllStatus=$myDB->query("Insert into doc_al_status set EmployeeID='".$EmployeeID."',  DOJ='".$doj."',validate='0' ");
										?>
										
								<?php	
								}
									if(strtolower(date('l',strtotime($doj))) == 'sunday')
									{
										
										$myDB=new MysqliDb();
										$data_roster_insert = $myDB->query('call insert_roster_tmp("'.strtoupper($EmployeeID).'","WO","WO","'.date('Y-m-d',strtotime($doj)).'",1,"WFO")');				
									
									}
									else
									{
										$begin_roster = new DateTime($doj);
										$end_roster   = new DateTime(date('Y-m-d',strtotime('next sunday')));
										$weekOFF = 0;
										$j = 1;$jj = 1;$daycount = 1;

										for($i = $begin_roster; $begin_roster <= $end_roster; $i->modify('+1 day'))
									    {
									        $dateT_ins = $i->format('Y-m-d');
									        //$myDB =new MysqliDb();
											//$sql_insert_roster = $myDB->query('call insert_roster_tmp("'.strtoupper($EmployeeID).'","09:00","18:00","'.$dateT_ins.'",1)');
											$sql_roster_insert = '';
									        if(strtolower(date('l',strtotime($i->format('Y-m-d')))) == 'sunday')
									        {
												$sql_roster_insert = 'call insert_roster_tmp("'.strtoupper($EmployeeID).'","WO","WO","'.$dateT_ins.'",1,"WFO")';	
											}
											else	
											{
												$sql_roster_insert = 'call insert_roster_tmp("'.strtoupper($EmployeeID).'","09:00","18:00","'.$dateT_ins.'",1,"WFO")';	
											}
											$myDB=new MysqliDb();
											$data_roster_insert = $myDB->query($sql_roster_insert);
									    }
									}
									
								}
								//	------------------------------------------------------------------------------
									
									
								if(($_POST['employment_type']=="1" || $_POST['employment_type']=="3" || $_POST['employment_type']=="4" || $_POST['employment_type']=="5" || $_POST['employment_type']=="6" || $_POST['employment_type']=="7" || $_POST['employment_type']=="8") && $desg != 9 && $desg!= 12 && $desg!='')
								{
									
									$myDB=new MysqliDb();
									$pagename='employee_map';
									$select_email_array=$myDB->query("select a.ID ,b.emailID,b.cc_email,e.email_address,f.email_address as ccemail from module_manager a INNER JOIN manage_module_email b on a.ID=b.moduleID  Left outer JOIN add_email_address e ON b.emailID=e.ID left outer join add_email_address f ON b.cc_email=f.ID where a.modulename='".$pagename."' and b.location='".$_POST['employment_type']."'");	
									
									$mail = new PHPMailer;
									$mail->isSMTP();
									$mail->Host = EMAIL_HOST; 
									$mail->SMTPAuth = EMAIL_AUTH;
									$mail->Username = EMAIL_USER;   
									$mail->Password = EMAIL_PASS;                        
									$mail->SMTPSecure = EMAIL_SMTPSecure;
									$mail->Port = EMAIL_PORT; 
									$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
									if(count($select_email_array) > 0 && $select_email_array){
										foreach($select_email_array as $Key=>$val)
							        	{
							        		$email_address = $val['email_address'];
											
											if($email_address!=""){
												$mail->AddAddress($email_address);
											}
											$cc_email=$val['ccemail'];
											
											if($cc_email!=""){
												$mail->addCC($cc_email);
											}
										}			
									}	
									$mail->Subject = 'Email ID Creation Request '.$EMS_CenterName.' ['.date('d M,Y',time()).']';
									$mail->isHTML(true);
									$myDB=new MysqliDb();
									$getDetails=$myDB->query("SELECT EmployeeName  FROM personal_details where EmployeeID='".$EmployeeID."'");
									$EmployeeName='';
									if(isset($getDetails[0]['EmployeeName'])){
										$EmployeeName=$getDetails[0]['EmployeeName'];
									}
									$myDB=new MysqliDb();
									$getCLientName=$myDB->query("select client_name  from client_master  where client_id='".$client."' ");
									$client_name='';
									if(isset($getCLientName[0]['client_name'])){
										$client_name=$getCLientName[0]['client_name'];
									}
							        $Body ="Hello sir,<br>Please create the Email ID of the Employee<br><br>
							        <table border='1'>";
							        $Body .="<tr><td><b>Employee ID</b></td><td><b>Employee Name</b></td><td><b>Client</b></td><td><b>Process</b></td><td><b>Sub-process</b></td><td><b>Designation</b></td><td><b>Department</b></td></tr>";
							       $Body.="<tr><td>".$EmployeeID."</td><td>".$EmployeeName."</td><td>".$client_name."</td><td>".$process."</td><td>".$sub_process1."</td><td>".$hdesg."</td><td>".$hdept."</td></tr>";
							       
							     	$Body .="</table><br><br>Thanks EMS Team";
									$mail->Body = $Body;
								
									if(!$mail->send())
								 	{
								 		$mailler_msg= 'Mailer Error:'. $mail->ErrorInfo;
								  	} 
									else
									 {
									    
									   $mailler_msg=   'and Email Id creation request raised.';
									 }
								}
																	 
							}
							else
							{
								$EmployeeID = '';
							}
						}
						
						else
						{
							$EmployeeID = '';
						}
					}
				
					if( $EmployeeID != '')
					{	
						
						$myDB=new MysqliDb();
						$createBy=$_SESSION['__user_logid'];
						
							$sqlInsertMap='call manage_map_employee("'.$EmployeeID.'","'.$dept.'","'.$client.'","'.$process.'","'.$subprocess.'","'.$desg.'","'.$doj.'","'.$level.'","'.$function_.'","'.$createBy.'")';
						//echo $sqlInsertMap;
						
							$result=$myDB->query($sqlInsertMap);
							if($desg != 9 && $desg != 12)
							{
								$sqlInsertMap='call update_apr_month("'.$EmployeeID.'","'.$doj.'")';
																
								$result=$myDB->query($sqlInsertMap);
							}
							
							
							$mysql_error =$myDB->getLastError();
							if(empty($mysql_error))
							{
								if($source_recruitment_id=='5'){
									
									$myDB=new MysqliDb();
								//echo 	"select payout,tenure from manage_consultancy where cm_id='".$cm_id."' and consultancy_id='".$consutancy_id."' ";
								$consult_array=$myDB->rawQuery("select payout,tenure from manage_consultancy where cm_id='".$cm_id."' and consultancy_id='".$consutancy_id."' ");
								$tenure='';
									if(count($consult_array)>0)
									{
										$tenure=$consult_array[0]["tenure"];
										$payout=$consult_array[0]["payout"];
										$myDB=new MysqliDb();
										$myDB=new MysqliDb();
										 $manage_query=$myDB->rawQuery('call consultancy_emp_manage("'.$EmployeeID.'","'.$consutancy_id.'" ,"'.$cm_id.'","'.$doj.'", "'.$tenure.'","'.$payout.'", "'.$createBy.'")');
									}	
									
									
								}
									
								$myDB=new MysqliDb();
								$myDB->rawQuery("update personal_details set ref_id='".$source_recruitment_id."', ref_txt='".$source_recruitment_Desc."' where EmployeeID='".$EmployeeID."'");
								echo "<script>$(function(){ toastr.success('Saved Successfully ".$mailler_msg."'); }); </script>";	
								
							}
							else
							{
								echo "<script>$(function(){ toastr.error('Employee Not Assigned Try again ".$mysql_error."'); }); </script>";
							}
					}
					else
					{
						echo "<script>$(function(){ toastr.error('Employee Not Assigned'); }); </script>";
					}	
		}
			
	}
	else
	{
		$data_update_alert = "<div id='div_mapping_aler_danger' class='alert alert-danger'>Please select Roster Type.</div>";	
	}	
}
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
if($pass =='')
{
	$pass = randomPassword();
}
?>

<script>
	$(document).ready(function(){
		var usrtype=<?php echo "'".$_SESSION["__user_type"]."'"; ?>;
		var usrID=<?php echo "'".$_SESSION["__user_logid"]."'"; ?>;
		if(usrtype === 'ADMINISTRATOR'||usrtype === 'HR'||usrID === 'CE12102224')
		{
		}
		else if(usrtype === 'AUDIT')
		{
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled','true');
			$('button:not(.drawer-toggle)').remove();
			
			$('.imgbtnEdit').remove();
			$('.imgBtnUploadDelete').remove();
			
		}
		else if(usrtype === 'CENTRAL MIS')
		{
			
			$('input,button:not(.drawer-toggle),select,textarea').attr('disabled','true');
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
		}
		else
		{
			$('input,button:not(.drawer-toggle),select,textarea').remove();
			$('.imgBtn').remove();
			$('button:not(.drawer-toggle)').remove();
			window.location = <?php echo '"'.URL.'/undefined"';?>;
		}
		   $('#txt_empmap_doj').datetimepicker({timepicker:false,format:'Y-m-d',minDate:'-1970/01/03',maxDate:'+1970/01/01', scrollInput : false});
	    $('#div_tempCard').click(function(){
	    	
	    	var popup = window.open("../Controller/get_tempCard.php?EmpID="+ $(this).children('a').attr('data_EmpID'), "popupWindow", "width=600px,height=600px,scrollbars=yes");
	    

	    });
	});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Map Employee</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >
	<?php include('shortcutLinkEmpProfile.php'); ?>
<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Map Employee</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	<?php 
		if($EmployeeID==''&&empty($EmployeeID))
		{
			echo "<script>$(function(){ toastr.info('Click on your previous action and Try again...'); }); </script>";
			exit();
		}
	?>
           <input type="hidden" name="EmployeeID" id="EmployeeID" value="<?php echo $EmployeeID;?>"/>
           <input type="hidden" id="emp_type" name="emp_type" />
           <input type="hidden" name="employment_type" id="employment_type" value="<?php echo $loc;?>"/>
           <input type="hidden" name="emp_salary" id="emp_salary" value="<?php echo $emp_salary;?>"/>
           <input type="hidden" name="hiddenIntID" id="hiddenIntID" value="<?php echo $interview_id;?>"/>
			 <?php echo $data_update_alert; ?>
			  
			<div class="input-field col s6 m6">
				<select id="txt_empmap_dept" name="txt_empmap_dept" required>
					<option value="NA">----Select----</option>	
					<?php
						$sqlBy="select dept_id,dept_name from dept_master";	
						$myDB=new MysqliDb();
						$resultBy=$myDB->query($sqlBy);
						if($resultBy){													
							$selec='';	
							foreach($resultBy as $key=>$value){
								if($value['dept_id']==$dept)	
								{
									$selec=' selected ';
								}
								else
								{
									$selec='';
								}														
								echo '<option value="'.$value['dept_id'].'" '.$selec.' >'.$value['dept_name'].'</option>';
							}
						}
					?>
				</select>
			<label for="txt_empmap_dept" class="active-drop-down active">Department *</label>
			</div>
			
			<div class="input-field col s6 m6">
			<select id="txt_empmap_client" name="txt_empmap_client" required>
			<option value="NA">----Select----</option>	
			<?php
			if($_SESSION["__location"]=="1")
			{
				$sqlBy="select distinct client_id,t1.client_name FROM client_master t1 join new_client_master t2 on t1.client_id=t2.client_name left join client_status_master t3 on t2.cm_id=t3.cm_id where location in (1,2) and t3.cm_id is null order by client_name "; 
			}
			else
			{
				$sqlBy="select distinct client_id,t1.client_name FROM client_master t1 join new_client_master t2 on t1.client_id=t2.client_name left join client_status_master t3 on t2.cm_id=t3.cm_id where location='".$_SESSION["__location"]."' and t3.cm_id is null order by client_name"; 
			}
				
				$myDB=new MysqliDb();
				$resultBy=$myDB->query($sqlBy);
				if($resultBy){		
					$selec='';											
						foreach($resultBy as $key=>$value){
							if($value['client_id']==$client)	
							{
							  $selec=' selected ';
							}	
							else
							{
							  $selec='';
							}								
							  echo '<option value="'.$value['client_id'].'"  '.$selec.'>'.$value['client_name'].'</option>';
						}

				}
			?>
			</select>
			<label for="txt_empmap_client" class="active-drop-down active">Client *</label>
			</div>
			
		    <div class="input-field col s6 m6">
	            <select id="txt_empmap_process" name="txt_empmap_process" required>
	           		<option value="NA">----Select----</option>
	           		<option selected="true"><?php echo($process);?></option>
	            </select>
	            <label for="txt_empmap_process" class="active-drop-down active">Process *</label>
		    </div>
			    
		    <div class="input-field col s6 m6">
	            <select  id="txt_empmap_subprocess" name="txt_empmap_subprocess" required>
	           		<option value="NA">----Select----</option>
	           		<option  selected="true" value="<?php echo $subprocess; ?>"><?php echo($sub_process1);?></option>
	           	</select>
			    <label for="txt_empmap_subprocess" class="active-drop-down active">Sub Process *</label>
		    </div>
		    <input type="hidden" value="<?php echo $cm_id;?>" name='cm_id' id='cm_id'>
		    <div class="input-field col s6 m6">
	            <select   id="txt_empmap_level" name="txt_empmap_level" required>
	           		<?php 
	           		if($_SESSION["__user_type"] === 'ADMINISTRATOR' && $_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
	           		{
						?>
						<option value="NA" <?php if($level=='NA'||$level==''||empty($level)){ echo('selected'); }?>>----Select----</option>
		           		<option value="EXECUTIVE" <?php if($level=='EXECUTIVE'){ echo('selected'); }?>>User</option>
		           		<option value="ADMINISTRATOR" <?php if($level=='ADMINISTRATOR'){ echo('selected'); }?>>Administrator</option>										<option value="SITEADMIN" <?php if($level=='SITEADMIN'){ echo('selected'); }?>>Site Administrator</option>
		           		<option value="AUDIT" <?php if($level=='AUDIT'){ echo('selected'); }?>>Auditor</option>
		           		<option value="CENTRAL MIS" <?php if($level=='CENTRAL MIS'){ echo('selected'); }?>>Central MIS</option>
		           		<option value="COMPLIANCE" <?php if($level=='COMPLIANCE'){ echo('selected'); }?>>Compliance</option>
						<?php
					}
					else
					{
						?>
						<option value="NA" <?php if($level=='NA'||$level==''||empty($level) || $level=='ADMINISTRATOR' || $level=='SITEADMIN' || $level=='AUDIT' || $level=='CENTRAL MIS' || $level=='COMPLIANCE' ){ echo('selected'); }?>>----Select----</option>
	           		
	           		<option value="EXECUTIVE" <?php if($level=='EXECUTIVE'){ echo('selected'); }?>>User</option>
	           		
	           		<!--<option value="ADMINISTRATOR" <?php if($level=='ADMINISTRATOR'){ echo('selected'); }?>>Administrator</option>											<option value="SITEADMIN" <?php if($level=='SITEADMIN'){ echo('selected'); }?>>Site Administrator</option>
	           		<option value="AUDIT" <?php if($level=='AUDIT'){ echo('selected'); }?>>Auditor</option>
	           		<option value="CENTRAL MIS" <?php if($level=='CENTRAL MIS'){ echo('selected'); }?>>Central MIS</option>
	           		<option value="COMPLIANCE" <?php if($level=='CENTRAL MIS'){ echo('selected'); }?>>Compliance</option>
	           		-->
						<?php
					}
	           		?>
	           		<option value="HR" <?php if($level=='HR'){ echo('selected'); }?>>Human Resources</option>				           		
	           		<!--<option value="ER" <?php if($level=='ER'){ echo('selected'); }?>>ER</option>				           		-->
	           		<option value="MIS" <?php if($level=='MIS'){ echo('selected'); }?>>MIS </option>
	           		<option value="HOD" <?php if($level=='HOD'){ echo('selected'); }?>>Head Of Department</option>
	           	</select>
			    <label for="txt_empmap_level"  class="active-drop-down active">Access Level *</label>
		    </div>
			    
		    <div class="input-field col s6 m6">
            <select id="txt_empmap_function" name="txt_empmap_function" required>
           		<option value="NA" <?php if($function_=='NA'||$function_==''||empty($function_)){ echo('selected'); }?>>----Select----</option>
           		<?php		
				$sqlBy="select id,function from function_master";	 
				$myDB=new MysqliDb();
				$resultBy=$myDB->query($sqlBy);
				if($resultBy){													
					$selec='';	
					foreach($resultBy as $key=>$value){
						if($value['id']==$function_)	
						{
							$selec=' selected ';
						}
						else
						{
							$selec='';
						}														
						echo '<option value="'.$value['id'].'" '.$selec.' >'.$value['function'].'</option>';
					}

				}
				
		      	?>	
           	</select>
           	<label for="txt_empmap_function" class="active-drop-down active">Function *</label>
		    </div>
	        
	        <div class="input-field col s6 m6">
		            <select  id="rt_type" name="rt_type" required>
		            	<option value="NA" <?php if($rt_type == 'NA') echo 'selected'; ?>>---Select---</option>
		           		<option value="1" <?php if($rt_type == '1') echo 'selected'; ?>>Full Time</option>
						<option value="4" <?php if($rt_type == '4') echo 'selected'; ?> >Split Time</option>
						<option value="3" <?php if($rt_type == '3') echo 'selected'; ?> >Part Time</option>
							
		           	</select>
	      	        <label for="rt_type" class="active-drop-down active">Roster Type *</label>
	       </div>
	      
		    <div class="input-field col s6 m6">
		            <select  id="txt_empmap_desg" name="txt_empmap_desg" required/>
		            <option value="NA">----Select----</option>	
				      	<?php
						$sqlBy="select ID,Designation from designation_master";
						$myDB=new MysqliDb();
						$resultBy=$myDB->query($sqlBy);
						if($resultBy){													
							$selec='';	
							foreach($resultBy as $key=>$value){
								if($value['ID']==$desg)	
								{
									$selec=' selected ';
								}
								else
								{
									$selec='';
								}														
								echo '<option value="'.$value['ID'].'" '.$selec.' >'.$value['Designation'].'</option>';
							}
						}				
				      	?>
				      	</select>
				      	<label for="txt_empmap_desg" class="active-drop-down active">Designation *</label>
					</div> 
		    
			<div class="input-field col s6 m6">
				<input type="text"  value="<?php echo($doj);?>" id="txt_empmap_doj" name="txt_empmap_doj" readonly="true" required/>
				<label for="txt_empmap_doj">DOJ *</label>
			</div>
	       <?php 
	       if($loc=="7" || $loc=="1" || $loc=="2" || $loc=="3" || $loc=="4")
	        { ?>
	       <div class="input-field col s6 m6">
		            <select  id="employment_type_fk" name="employment_type_fk" required>
		            	<option value="NA">---Select---</option>
		            	
		           		<option value="Cogent" <?php if($emptype == 'Cogent') echo 'selected'; ?>>Cogent</option>
		           		<?php  if($loc=="7"){ ?>
												
							<option value="Flipkart" <?php if($emptype == 'Flipkart') echo 'selected'; ?>>Flipkart</option> 
						<?php } ?>
						<option value="CCE" <?php if($emptype == 'CCE') echo 'selected'; ?>>CCE</option>
		           	</select>
	      	        <label for="employment_type_fk" class="active-drop-down active">Employment Type *</label>
	       </div> 
	       <?php  } ?>
	       
			<div class="input-field col s6 m6">
		            <select id="txt_Personal_Ref_id" name="txt_Personal_Ref_id" required>
		            <option value="NA">----Select----</option>	
				      	<?php		
							$sqlBy ='SELECT ref_id,Type FROM ref_master'; 
							$myDB=new MysqliDb();
							$resultBy=$myDB->query($sqlBy);
							if($resultBy){													
								foreach($resultBy as $key=>$value){
									if($source_recruitment_id==$value['ref_id'])
									{
										$selected='Selected';
									}
									else
									{
										$selected='';
									}														
									echo '<option value="'.$value['ref_id'].'" '.$selected.' >'.$value['Type'].'</option>';
								}
							}			
				      	?>
		            </select>
		            <label for="txt_Personal_Ref_id"  class="active-drop-down active">Source Of Recruitment *</label>
				</div> 
				 <?php
				 $hiddenDesc="";
				 $hiddenCons="";
				$ConsultancyName='';
				$consultancy_id='';
				$requireDesc='';
				$requireCons='';
			    if($source_recruitment_id=='5'){
			    	$myDB=new MysqliDb();
			    	$query=$myDB->rawQuery("select a.consultancy_id,b.ConsultancyName from consultancy_empref a inner join consultancy_master b on a.consultancy_id=b.id where a.EmployeeID='".$EmployeeID."' ");
			    	if(count($query)>0)	{
			    		$ConsultancyName=$query[0]['ConsultancyName'];
			    		$consultancy_id=$query[0]['consultancy_id'];
			    		$hiddenDesc="hidden";
			    	 	$hiddenCons="";
			    	 	$requireDesc='';
				 		$requireCons='required';
				    }
				}
			    else{
			    	 	$hiddenDesc="";
				 		$hiddenCons="hidden";
				 		$requireDesc='required';
				 		$requireCons='';
			    }
				   
			    ?>
		
			    <div class="input-field col s6 m6 ref_txt_to <?php echo $hiddenDesc;?>">
					<input type="text" id="txt_Personal_Ref_Desc" value="<?php echo $source_recruitment_Desc;?>" name="txt_Personal_Ref_Desc" <?php echo $requireDesc; ?> />
					<label for="txt_Personal_Ref_Desc">Referred By *</label>
			    </div>
			   
			    <div class="input-field col s6 m6 ref_option_to <?php echo $hiddenCons;?>">
					<select id="txt_Personal_Ref_rName" name="txt_Personal_Ref_rName" <?php echo $requireCons; ?> >
						<option value="<?php echo $consultancy_id; ?>"><?php echo $ConsultancyName; ?> </option>
					</select>
					<label for="txt_Personal_Ref_rName" class="active-drop-down active">Referred By *</label> 
			    </div>
			    
			<div class="input-field col s6 m6 hidden">
		        <input type="password" class="hidden"  value="NAN" id="txt_empmap_pass" name="txt_empmap_pass" readonly="true"/>
		        <label for="txt_empmap_pass">Password *</label>
			</div>
			<input type='hidden' name='hdesignation' id='hdesignation'>
			<input type='hidden' name='hdepartment' id='hdepartment'>
			<div class="input-field col s12 m12 right-align">		  	 
		  	     <button type="submit" title="Update Details" name="btn_empmap_Save" id="btn_empmap_Save" class="btn waves-effect waves-green">Save</button>
			</div>
		
			<?php 
			if($loc=="1" || $loc=="2")
			{
				if((substr($EmployeeID,0,2) == 'CE' || substr($EmployeeID,0,2) == 'AE' || substr($EmployeeID,0,2) == 'RS' || substr($EmployeeID,0,2) == 'MU') && $dept !="" &&  $sub_process1 != "" && $desg != "")
				{
					echo '<div id="div_tempCard"><a href="#" data_empID="'.$EmployeeID.'" id="a_print_card">
					      <i class="fa fa-link"></i> Print ID Card</a>
					      </div>';
				}
			}
			else if($loc=="3")
			{
				if((substr($EmployeeID,0,2) == 'CE' || substr($EmployeeID,0,2) == 'OC' || substr($EmployeeID,0,2) == 'RS') && $dept !="" &&  $sub_process1 != "" && $desg != "")
				{
					echo '<div id="div_tempCard"><a href="#" data_empID="'.$EmployeeID.'" id="a_print_card">
					      <i class="fa fa-link"></i> Print ID Card</a>
					      </div>';
				}
			}
			else if($loc=="4" || $loc=="5" || $loc=="6" || $loc=="7" || $loc=="8")
			{
				if(substr($EmployeeID,0,2) != 'TE' && $dept !="" &&  $sub_process1 != "" && $desg != "")
				{
					echo '<div id="div_tempCard"><a href="#" data_empID="'.$EmployeeID.'" id="a_print_card">
					      <i class="fa fa-link"></i> Print ID Card</a>
					      </div>';
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
<script>
	$(document).ready(function(){
		
		
		$('input[type="text"]').click(function(){
			$(this).closest('div').removeClass('has-error');	       
		});
		$('select').click(function(){
			$(this).closest('div').removeClass('has-error');	       
		});	
		
		<?php 
		if(substr($EmployeeID,0,2) != 'TE' && $df_id !='')
		{
			if(substr($EmployeeID,0,2) != 'TE' && !($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR' || $_SESSION["__ut_temp_check"] == 'COMPLIANCE'))
			{
		
		
			?>
			
			$('#btn_empmap_Save').addClass('hidden').attr('disabled',true);
			$('#btn_empmap_Save').remove();
			<?php 
			}
		}
		?>
		var value_click_cl= 1;	
		var value_click_pro= 1;	
		var value_click_spro= 1;
		
	    $('#txt_empmap_client').change(function(){
	    		var clientId = $(this).val();
	    		var dept=$("#txt_empmap_dept").val();
	    		var location = <?php echo $_SESSION["__location"] ?>;
	    		
	    		var lvl = '1';
	    		
	    		<?php 
				if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
				{
					?>
					lvl='2';
					
					<?php 
				}
				?>
				
	    		value_click_pro =2;
			    $.ajax({
				  url: <?php echo '"'.URL.'"';?>+"Controller/getprocess.php?id="+clientId+"&loc="+location+"&lvl="+lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_process').html(data);
					$('#txt_empmap_process').val('NA');
					$('#txt_empmap_subprocess').val('NA');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
				});
	    });
	     $('#txt_empmap_process').change(function(){
	    		var tval = $(this).val();
	    		var dept=$("#txt_empmap_dept").val();
	    		value_click_spro=2;
	    		var id = $('#txt_empmap_client').val();
	    		var location = <?php echo $_SESSION["__location"] ?>;
	    		var lvl = '1';
	    		
	    		<?php 
				if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
				{
					?>
					lvl='2';
					
					<?php 
				}
				?>
	    		//alert(location);
			    $.ajax({
				  url: <?php echo '"'.URL.'"';?>+"Controller/getsubprocess_map.php?id="+id+"&proc="+tval+"&dept="+dept+"&loc="+location+"&lvl="+lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_subprocess').html(data);
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
				});
	    });
	   
	     // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_empmap_Save').on('click', function(){
			/*var emptype = $("#employment_type option:selected").text();
			$("#emp_type").val(emptype);*/
	        var validate=0;
	        var alert_msg='';
	        // <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
	        $("input,select,textarea").each(function(){
	        	var spanID =  "span" + $(this).attr('id');		        	
	        	$(this).removeClass('has-error');
	        	if($(this).is('select'))
				{
					$(this).parent('.select-wrapper').find('.select-dropdown').removeClass("has-error");
				}
	        	var attr_req = $(this).attr('required');
	        	if(($(this).val() == '' || $(this).val() == 'NA' || $(this).val() == undefined) && (typeof attr_req !== typeof undefined && attr_req !== false) && !$(this).hasClass('.select-dropdown'))
	        	{
					validate=1;	
					$(this).addClass('has-error');
					if($(this).is('select'))
					{
						$(this).parent('.select-wrapper').find('.select-dropdown').addClass("has-error");
					}
					if ($('#'+spanID).size() == 0) {
				            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#'+$(this).attr('id'));
				        }
				    var attr_error = $(this).attr('data-error-msg');
				    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
				    {
						$('#'+spanID).html('Required *');	
					}
					else
					{
						$('#'+spanID).html($(this).attr("data-error-msg"));
					}
				}
	        })
	        	
	       // $('#txt_empmap_dept').selected()	
	      var departText= $("#txt_empmap_dept option:selected").text();	    
	      $("#hdepartment").val(departText);	    
	      var desigText= $("#txt_empmap_desg option:selected").text();	    
	      $("#hdesignation").val(desigText);	  
	     
	      	if(validate==1)
	      	{	
				return false;
			} 
	});
	    
	    			
	     $('#txt_empmap_dept').change(function(){
	     	
	    		var tval = $(this).val();
	    		var lvl = '1';
	    		
	    		<?php 
				if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
				{
					?>
					lvl='2';
					
					<?php 
				}
				?>
	    		value_click_cl =2;
	    		//alert(lvl);
			    $.ajax({
				  url: <?php echo '"'.URL.'"';?>+"Controller/get_client_bydept.php?id="+tval+"&lvl="+lvl
				}).done(function(data) { // data what is sent back by the php page
					$('#txt_empmap_client').html(data);
					if(tval=='1')
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else if(tval=='NA')
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else if(tval=='2')
					{
						
						<?php 
							if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
							{
								?>
								$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option>');
								
								<?php 
							}
							else
							{
							?>
								$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option>');
							
							<?php  }  ?>
								
								
						
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else if(tval=='3')
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="1">Administration</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
				});
	    });
	    
	     $('#txt_empmap_function').change(function(){
	     	
	    		var tval = $(this).val();
			    $.ajax({
				  url: <?php echo '"'.URL.'"';?>+"Controller/get_desby_function.php?id="+tval
				}).done(function(data) { // data what is sent back by the php page
				//alert(data);
					$('#txt_empmap_desg').html(data);
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
				});
	    });
					
					if($('#txt_empmap_dept').val()=='1')
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else if($('#txt_empmap_dept').val()=='NA')
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else if($('#txt_empmap_dept').val()=='2')
					{
						<?php 
							if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
							{
								?>
								$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option>');
								
								<?php 
							}
							else
							{
							?>
								$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option>');
							
							<?php  }  ?>
												
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else if($('#txt_empmap_dept').val()=='3')
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					else
					{
						$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
						$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
					}
					
					
					<?php 
						if($function_!='')
						{
							?>
							var val_funct=<?php echo '"'.$function_.'"'; ?>;
							$('#txt_empmap_function').val(val_funct);
							$('#txt_empmap_function').trigger('change');
							
							
							var val_desg=<?php echo '"'.$desg.'"'; ?>;	
							$( document ).ajaxComplete(function() {
								
								 $('#txt_empmap_desg').val(val_desg);	
								 $( document ).unbind('ajaxComplete');
								$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
								 }
							});
							$('select').formSelect(); 				
								 
							});	
							
							
							
							<?php 
						}
					
					?>		
		$('#txt_empmap_client').click(function(){
				if(value_click_cl == 1)
				{
					var lvl = '1';
	    		
		    		<?php 
					if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
					{
						?>
						lvl='2';
						
						<?php 
					}
					?>
					var tval = $('#txt_empmap_dept').val();
				    $.ajax({
					  url: <?php echo '"'.URL.'"';?>+"Controller/get_client_bydept.php?id="+tval+"&lvl="+lvl
					}).done(function(data) { // data what is sent back by the php page
						$('#txt_empmap_client').html(data);
						if(tval=='1')
						{
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
						});
						$('select').formSelect();
						}
						else if(tval=='NA')
						{
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
							});
							$('select').formSelect();
						}
						else if(tval=='2')
						{
							<?php 
							if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
							{
								?>
								$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option>');
								
								<?php 
							}
							else
							{
							?>
								$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option>');
							
							<?php  }  ?>
							
							
								$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							     if($(element).val().length > 0) {
							       $(this).siblings('label, i').addClass('active');
							     }
							     else
							     {
								 	$(this).siblings('label, i').removeClass('active');
								 }
								});
								$('select').formSelect();
						}
						else if(tval=='3')
						{
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
								$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							     if($(element).val().length > 0) {
							       $(this).siblings('label, i').addClass('active');
							     }
							     else
							     {
								 	$(this).siblings('label, i').removeClass('active');
								 }
								});
								$('select').formSelect();
						}
						else
						{
							$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
								$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
							     if($(element).val().length > 0) {
							       $(this).siblings('label, i').addClass('active');
							     }
							     else
							     {
								 	$(this).siblings('label, i').removeClass('active');
								 }
								});
								$('select').formSelect();
						}
					});
					value_click_cl =2;
				}
				
		});
		$('#txt_empmap_process').click(function(){
				if(value_click_pro == 1)
				{
					var tval = $('#txt_empmap_client').val();
					var lvl = '1';
	    		
		    		<?php 
					if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
					{
						?>
						lvl='2';
						
						<?php 
					}
					?>
					var location = <?php echo $_SESSION["__location"] ?>;
				    $.ajax({
					  url: <?php echo '"'.URL.'"';?>+"Controller/getprocess.php?id="+tval+"&loc="+location+"&lvl="+lvl
					}).done(function(data) { // data what is sent back by the php page
						$('#txt_empmap_process').html(data);
						$('#txt_empmap_process').val('NA');
						$('#txt_empmap_subprocess').val('NA');
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
							});
							$('select').formSelect();
					});
					value_click_pro=2;
				}
				 
		});				
		$('#txt_empmap_subprocess').click(function(){
	    		if(value_click_spro == 1)
	    		{
					var tval = $('#txt_empmap_process').val();
		    		var id = $('#txt_empmap_client').val();
		    		var location = <?php echo $_SESSION["__location"] ?>;
		    		
		    		var lvl = '1';
	    		
		    		<?php 
					if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
					{
						?>
						lvl='2';
						
						<?php 
					}
					?>
				
				    $.ajax({
					  url: <?php echo '"'.URL.'"';?>+"Controller/getsubprocess_map.php?id="+id+"&proc="+tval+"&loc="+location+"&lvl="+lvl
					}).done(function(data) { // data what is sent back by the php page
						$('#txt_empmap_subprocess').html(data);
							$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
						     if($(element).val().length > 0) {
						       $(this).siblings('label, i').addClass('active');
						     }
						     else
						     {
							 	$(this).siblings('label, i').removeClass('active');
							 }
							});
							$('select').formSelect();
					});
					value_click_spro=2;
				}
	    		
	    });
		 
		$('#txt_empmap_client').focusin(function(){
		if(value_click_cl == 1)
		{
			var lvl = '1';
	    		
    		<?php 
			if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
			{
				?>
				lvl='2';
				
				<?php 
			}
			?>
		var tval = $('#txt_empmap_dept').val();
		$.ajax({
		  url: <?php echo '"'.URL.'"';?>+"Controller/get_client_bydept.php?id="+tval+"&lvl="+lvl
		}).done(function(data) { // data what is sent back by the php page
		$('#txt_empmap_client').html(data);
		if(tval=='1')
		{
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="7">Operation</option><option value="8">Quality</option><option value="10">Training</option>');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			     if($(element).val().length > 0) {
			       $(this).siblings('label, i').addClass('active');
			     }
			     else
			     {
				 	$(this).siblings('label, i').removeClass('active');
				 }
				});
				$('select').formSelect();
		}
		else if(tval=='NA')
		{
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			     if($(element).val().length > 0) {
			       $(this).siblings('label, i').addClass('active');
			     }
			     else
			     {
				 	$(this).siblings('label, i').removeClass('active');
				 }
				});
				$('select').formSelect();
		}
		else if(tval=='2')
		{
			<?php 
				if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
				{
					?>
					$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="1">Administration</option><option value="2">Business Support</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="7">Operation</option><option value="8">Quality</option><option value="9">Software Delovepment</option><option value="10">Training</option>');
					
					<?php 
				}
				else
				{
				?>
					$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="2">Business Support</option><option value="1">Administration</option>');
				
				<?php  }  ?>
							
			
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			     if($(element).val().length > 0) {
			       $(this).siblings('label, i').addClass('active');
			     }
			     else
			     {
				 	$(this).siblings('label, i').removeClass('active');
				 }
				});
				$('select').formSelect();
		}
		else if(tval=='3')
		{
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option><option value="3">Finance</option><option value="4">Human Resource</option><option value="5">Information Technology</option><option value="6">MIS & WFM</option><option value="9">Software Delovepment</option>');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			     if($(element).val().length > 0) {
			       $(this).siblings('label, i').addClass('active');
			     }
			     else
			     {
				 	$(this).siblings('label, i').removeClass('active');
				 }
				});
				$('select').formSelect();
		}
		else
		{
			$('#txt_empmap_function').empty().append('<option value="NA">----Select----</option>');
				$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
			     if($(element).val().length > 0) {
			       $(this).siblings('label, i').addClass('active');
			     }
			     else
			     {
				 	$(this).siblings('label, i').removeClass('active');
				 }
				});
				$('select').formSelect();
		}
		});
		value_click_cl =2;
		}
		});
		$('#txt_empmap_process').focusin(function(){
		if(value_click_pro == 1)
		{
			var tval = $('#txt_empmap_client').val();
			var lvl = '1';
	    		
	    		<?php 
				if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
				{
					?>
					lvl='2';
					
					<?php 
				}
				?>
			var location = <?php echo $_SESSION["__location"] ?>;
		    $.ajax({
		    	
			  url: <?php echo '"'.URL.'"';?>+"Controller/getprocess.php?id="+tval+"&loc="+location+"&lvl="+lvl
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_empmap_process').html(data);
				//alert(data);
				$('#txt_empmap_process').val('NA');
				$('#txt_empmap_subprocess').val('NA');
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
					});
					$('select').formSelect();
			});
			value_click_pro=2;
		}
		 
		});				
		$('#txt_empmap_subprocess').focusin(function(){
		if(value_click_spro == 1)
		{
			var tval = $('#txt_empmap_process').val();
		var id = $('#txt_empmap_client').val();
			var location = <?php echo $_SESSION["__location"] ?>;
			var lvl = '1';
	    		
	    		<?php 
				if($_SESSION["__ut_temp_check"] == 'ADMINISTRATOR')
				{
					?>
					lvl='2';
					
					<?php 
				}
				?>
		    $.ajax({
			  url: <?php echo '"'.URL.'"';?>+"Controller/getsubprocess_map.php?id="+id+"&proc="+tval+"&loc="+location+"&lvl="+lvl
			}).done(function(data) { // data what is sent back by the php page
				$('#txt_empmap_subprocess').html(data);
					$(".schema-form-section input,.schema-form-section textarea").each(function(index, element) {
				     if($(element).val().length > 0) {
				       $(this).siblings('label, i').addClass('active');
				     }
				     else
				     {
					 	$(this).siblings('label, i').removeClass('active');
					 }
					});
					$('select').formSelect();
			});
			value_click_spro=2;
		}
		});
		
	    $('#txt_Personal_Ref_id').change(function(){
	    	
				$('#txt_Personal_Ref_Desc').autocomplete({disabled: true});
				//if($(this).val()=='Employee'||$(this).val()=='NewsPaper'||$(this).val()=='Other'||$(this).val()=='WebSite'||$('#txt_Personal_Ref_id').val()=='WalkIn')
				if($(this).val()=='1'||$(this).val()=='1'||$(this).val()=='3'||$(this).val()=='4'||$('#txt_Personal_Ref_id').val()=='6')
				{
					$('.ref_txt_to').removeClass('hidden');
					$('.ref_option_to').addClass('hidden');
					$('#txt_Personal_Ref_Desc').prop('required',true);
					$('#txt_Personal_Ref_rName').prop('required',false);
					if($(this).val()=='Employee')
					{
						$('#txt_Personal_Ref_Desc').autocomplete({source:'../Controller/autocomplete_employee.php', minLength:2,disabled: false});
						$('select').formSelect();
					}
					  $('select').formSelect();
				}
				else if($(this).val()=='NA' || $(this).val()=='')
				{
					$('.ref_txt_to').addClass('hidden');
					$('.ref_option_to').addClass('hidden');
					//$('#txt_Personal_Ref_rName').val('NA');
					$('#txt_Personal_Ref_Desc').val('');
					$('select').formSelect();
				}
				else if($(this).val()=='5' && $(this).val()!='')
				{
					$('.ref_txt_to').addClass('hidden');
					$('.ref_option_to').removeClass('hidden');
					$('#txt_Personal_Ref_Desc').val('');
					$('#txt_Personal_Ref_Desc').prop('required',false);
					$('#txt_Personal_Ref_rName').prop('required',true);
					if($('#txt_empmap_subprocess').val()!=""){
						 cm_id=$('#txt_empmap_subprocess').val();
					}else{
						cm_id=$('#cm_id').val();
					}
					//alert('cm_id'+cm_id);
					if(cm_id!=""){
						$.ajax({url: "../Controller/getRefrence.php?cm_id="+cm_id, success: function(result){
							//alert(result);
		                    $('#txt_Personal_Ref_rName').empty().append(result);
		                    $('select').formSelect();             
		                                                        
		                }});
					}
	                
		        $('select').formSelect();
				}
			});
	});
	
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>  