<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Global variable used in Page Cycle
$alert_msg =$aaprovedbyoh=$_ohcomment=$aaprovedby='';

$btnOPS =' hidden';
$oh_level=' hidden';
$Request_Emp=$_hodcomment='';
$disable='';
$appLink='';
$hr_level=$hod_level='hidden';
$leaveStatus='Pending';
$emp_id=$_SESSION['__user_logid'];
$emp_name=$_SESSION['__user_Name'];
$emp_dept=$_SESSION['__user_subprocess'];//__user_Dept'];
$_Description=$_Name=$alert_msg='';
$dateon=date('Y-m-d');
$Leavecount=1;
$DateTo=date('Y-m-d');
$DateFrom=date('Y-m-d');
$LeaveType ='Leave';
$RsnLeave='';
$readonly='';
$leaveID='';
$btnAdd=$btnSave='hidden';

$btnAdd='';
$hd_comment_hidden = 'hidden';
$result_commet = '';
$HRStatusID='';
$comment_type='Supervisor';

// Trigger Button-Save Click Event and Perform DB Action

if(isset($_POST['btn_Leave_Add'])&&!empty($_POST['txt_addleave_empid']))
{
	$myDB=new MysqliDb();
	$emp_id=$_POST['txt_addleave_empid'];	
	$leaveStatus=$_POST['txt_addleave_Status'];
	$RsnLeave=addslashes($_POST['txt_addleave_rsn']);
	$dateon=$_POST['txt_addleave_ondate'];
	$Leavecount=$_POST['txt_addleave_totald'];
	$DateTo=$_POST['txt_addleave_To'];
	$DateFrom=$_POST['txt_addleave_From'];
	$createBy=$_SESSION['__user_Name'];
	$LeaveType =$_POST['txt_LeaveType'];
	$Inser_Branch='call addLeave("'.$emp_id.'","'.$DateFrom.'","'.$DateTo.'","'.$RsnLeave.'","'.$dateon.'","'.$Leavecount.'","'.$leaveStatus.'","'.$createBy.'","'.$LeaveType.'","web-addLeave54")';
	
	$result = $myDB->query($Inser_Branch);
	$mysql_error= $myDB->getLastError();
	if(empty($mysql_error))
	{
		$myDB = new MysqliDb();
		$getAccountHead = $myDB->query('select account_head,personal_details.EmployeeName from employee_map  inner join new_client_master on new_client_master.cm_id = employee_map.cm_id left outer join personal_details on personal_details.EmployeeID = new_client_master.account_head where employee_map.EmployeeID = "'.$emp_id.'"');
		
		echo "<script>$(function(){ toastr.success('Leave Added  Successfully and Request Send to ".$getAccountHead[0]['EmployeeName']."...'); }); </script>";
		$_Description=$_Type=$_Name='';
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Data not Added ,Try again ".$mysql_error."'); }); </script>";
	}
	$hod_level=$btnAdd=$btnSave='hidden';
	$btnAdd='';
	$readonly='';
	echo '<div class="model-popup" style="    position: fixed;    height: 100%;    width: 100%;    background: rgba(14, 14, 14, 0.41);    z-index: 1000001;"><div style=" position: absolute;top: 45%;left: 35%;background: white;width: 20%;text-align: center;vertical-align: middle;padding: 1%;border-radius: 6px;border: 1px solid #272626;color: #8dc73b;font-size: 18px;">Leave Added Successfully <a href="'.$_SERVER['PHP_SELF'].'" class="btn waves-effect waves-orange"><i class="fa fa-plus"></i> New</a>&nbsp;<a href="'.URL.'" class="btn waves-effect waves-red close-btn"><i class="fa fa-Home"></i> Home</a></div></div>';
}
if(isset($_POST['btn_Leave_Save']))
{	
	$emp_id=$_POST['txt_addleave_empid'];	
	$leaveStatus=$_POST['txt_addleave_Status'];
	$RsnLeave=addslashes($_POST['txt_addleave_rsn']);
	$dateon=$_POST['txt_addleave_ondate'];
	$Leavecount=$_POST['txt_addleave_totald'];
	$DateTo=$_POST['txt_addleave_To'];
	$DateFrom=$_POST['txt_addleave_From'];
	$leaveID=$_POST['lvlID'];
	$leaveStatus=$_POST['txt_addleave_hodapp'];
	
	$HRStatusID=$_POST['txt_addleave_ohapp'];
	$aaprovedbyoh=$_POST['txt_addleave_ApproveBy'];
	if($HRStatusID =='Pending' && ($leaveStatus == 'Approve' || $leaveStatus =='Decline'))
	{
		$HRStatusID =$leaveStatus;
	}
	$_hodcomment=(isset($_POST['txt_addleave_hodcmt'])? $_POST['txt_addleave_hodcmt'] : null);
	$_Name=$_SESSION['__user_logid'];
	
	 $Update_Leave='call update_leave("'.$_Name.'","'.$_hodcomment.'","'.$leaveStatus.'","'.$leaveID.'","'.$HRStatusID.'","web-updateLeave96")';	
		$disable='disabled="true"';
		$readonly="readonly='true'";
		$hod_level=$btnAdd=$btnSave='hidden';
		$hod_level=$btnSave='';
		$aaprovedby=$_SESSION['__user_logid'];
	$myDB=new MysqliDb();
	if(!empty($leaveID)||$leaveID!='')
	{
		
		$result = $myDB->query($Update_Leave);					
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Leave Status Updated Successfully'); }); </script>";
			$myDB = new MysqliDb();
			$resultsa=$myDB->query('SELECT leavehistry.*,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.sub_process FROM leavehistry inner join whole_dump_emp_data on whole_dump_emp_data.EmployeeID=leavehistry.EmployeeID  where LeaveID='.$leaveID);
			//echo $sqlConnect;
			$error= $myDB->getLastError();
			if(empty($error))
			{
				$emp_name=$resultsa[0]['EmployeeName'];	
				$leaveStatus=$resultsa[0]['EmployeeComment'];	
				$dateon=$resultsa[0]['LeaveOnDate'];	
				$Leavecount=$resultsa[0]['TotalLeaves'];	
				$DateTo=$resultsa[0]['DateTo'];	
				$DateFrom=$resultsa[0]['DateFrom'];
				$HRStatusID = $resultsa[0]['HRStatusID'];				
				$LeaveType=$resultsa[0]['LeaveType'];
				$emp_dept = $resultsa[0]['sub_process'];
				
			}
			else
			{
				echo "<script>$(function(){ toastr.info('Data not found ".$error."'); }); </script>";
			}
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Data not updated ".$mysql_error."'); }); </script>";
		}
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('Something wnet wrong, Plase click to Edit Button First on View Leave Page, If Not Resolved then contact to technical person'); }); </script>";
	}
	
	$hod_level=$btnAdd=$btnSave='hidden';
	$hod_level=$btnSave='';
	$sql='select leave_comment.*,personal_details.EmployeeName from leave_comment left outer join personal_details on personal_details.EmployeeID = leave_comment.createdby where leave_comment.leave_id = '.$leaveID;
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error = $myDB->getLastError();
	if(count($result) > 0 && $result){
		
		foreach($result as $key => $value)
		{
			$result_commet =$result_commet. '<p ><span style="color: #218ea0;">'.$value['EmployeeName'].'(<b>'.$value['createdby'].'</b>)</span> <span style=" font-weight: bold;">'.$value['createdon'].'</span> : '.$value['comment'].'</p>';
		}
		
	}
	else
	{
		$result_commet= 'No Comment ';
		
	}
	$myDB=new MysqliDb();
	$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id where new_client_master.account_head="'.$emp_id.'" and employee_map.EmployeeID = "'.$_SESSION['__user_logid'].'"');
		if($data_ac)
		{
			foreach($data_ac as $k_d=>$v_d)
			{
				
				if($v_d['EmployeeID'] == $_SESSION['__user_logid'])
				{
					//echo 'HOD';
					$hod_level=$btnAdd=$btnSave='hidden';
					$btnAdd='hidden';
					$hd_comment_hidden = '';
				}
			}
		}
		$myDB=new MysqliDb();
		$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id inner join status_table on status_table.EmployeeID = employee_map.EmployeeID  where new_client_master.account_head="'.$emp_id.'" and employee_map.EmployeeID = "'.$emp_id.'" and status_table.ReportTo = "'.$_SESSION['__user_logid'].'"');
	
		if($data_ac)
		{
			foreach($data_ac as $k_d=>$v_d)
			{
				
				if($v_d['EmployeeID'] != $_SESSION['__user_logid'])
				{
					//echo 'Accout';
					$hod_level=$btnSave='';
					$hd_comment_hidden = ' hidden';
					
				}
			}
		}
		
		
		if($leaveStatus == 'Approve' || $leaveStatus == 'Decline')
		{
			$hod_level=' hidden';			
			$hd_comment_hidden=$btnSave = ' hidden';
			
		}
}
if(isset($_POST['btn_Leave_ops_Save']))
{	
	$emp_id=$_POST['txt_addleave_empid'];	
	$leaveStatus=$_POST['txt_addleave_Status'];
	$RsnLeave=$_POST['txt_addleave_rsn'];
	$dateon=$_POST['txt_addleave_ondate'];
	$Leavecount=$_POST['txt_addleave_totald'];
	$DateTo=$_POST['txt_addleave_To'];
	$DateFrom=$_POST['txt_addleave_From'];
	$leaveID=$_POST['lvlID'];
	$leaveStatus=$_POST['txt_addleave_hodapp'];
	
	$HRStatusID=$_POST['txt_addleave_ohapp'];
	$aaprovedbyoh=$_POST['txt_addleave_ApproveBy_oh'];
	
	$_ohcomment=(isset($_POST['txt_addleave_ohcmt'])? $_POST['txt_addleave_ohcmt'] : null);
	$_Name=$_SESSION['__user_logid'];
	
	$Update_Leave='call update_oh_leave("'.$_Name.'","'.$_ohcomment.'","'.$leaveID.'","'.$HRStatusID.'","web-addLeave223")';	
	$disable='disabled="true"';
	$readonly="readonly='true'";
		$hod_level=$btnAdd=$btnSave='hidden';
		$hod_level=$btnSave='';
		$aaprovedby=$_SESSION['__user_logid'];
	$myDB=new MysqliDb();
	if(!empty($leaveID)||$leaveID!='')
	{
		
		$result = $myDB->query($Update_Leave);					
		$mysql_error=$myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Leave Status Updated Successfully'); }); </script>";
			$myDB=new MysqliDb();
			$resultsa=$myDB->query('SELECT leavehistry.*,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.sub_process FROM leavehistry inner join whole_dump_emp_data on whole_dump_emp_data.EmployeeID=leavehistry.EmployeeID  where LeaveID='.$leaveID);
			//echo $sqlConnect;
			$error=$myDB->getLastError();
			if(count($resultsa) > 0 && $resultsa)
			{
				$emp_name=$resultsa[0]['EmployeeName'];	
				$emp_id=$resultsa[0]['EmployeeID'];	
				$leaveStatus=$resultsa[0]['EmployeeComment'];	
				$dateon=$resultsa[0]['LeaveOnDate'];	
				$Leavecount=$resultsa[0]['TotalLeaves'];	
				$DateTo=$resultsa[0]['DateTo'];	
				$DateFrom=$resultsa[0]['DateFrom'];
				$emp_dept = $resultsa[0]['sub_process'];
				
			}
			
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Data not updated :: ".$mysql_error."'); }); </script>";
			
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Something wnet wrong, Plase click to Edit Button First on View Leave Page :: If Not Resolved then contact to technical person'); }); </script>";
	}
	
	$hod_level=$btnAdd=$btnSave='hidden';
	
	$sql='select leave_comment.*,personal_details.EmployeeName from leave_comment left outer join personal_details on personal_details.EmployeeID = leave_comment.createdby where leave_comment.leave_id = '.$leaveID;
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if($result){
		
		foreach($result as $key => $value)
		{
			$result_commet =$result_commet. '<p ><span style="color: #218ea0;">'.$value['EmployeeName'].'(<b>'.$value['createdby'].'</b>)</span> <span style=" font-weight: bold;">'.$value['createdon'].'</span> : '.$value['comment'].'</p>';
		}
		
	}
	else
	{
		$result_commet= 'No Comment ';
		
	}
	$myDB=new MysqliDb();
	$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id where new_client_master.account_head="'.$emp_id.'" and employee_map.EmployeeID = "'.$_SESSION['__user_logid'].'"');
		if($data_ac)
		{
			foreach($data_ac as $k_d=>$v_d)
			{
				
				if($v_d['EmployeeID'] == $_SESSION['__user_logid'])
				{
					//echo 'HOD';
					$hod_level=$btnAdd=$btnSave='hidden';
					$btnAdd='hidden';
					$hd_comment_hidden = '';
				}
			}
		}
		$myDB=new MysqliDb();
		$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id inner join status_table on status_table.EmployeeID = employee_map.EmployeeID  where new_client_master.account_head="'.$emp_id.'" and employee_map.EmployeeID = "'.$emp_id.'" and status_table.ReportTo = "'.$_SESSION['__user_logid'].'"');
	
		if($data_ac)
		{
			foreach($data_ac as $k_d=>$v_d)
			{
				
				if($v_d['EmployeeID'] != $_SESSION['__user_logid'])
				{
					//echo 'Accout';
					$hod_level=$btnSave='';
					$hd_comment_hidden = ' hidden';
					
				}
			}
		}
		
		
		if($HRStatusID == 'Approve' ||$HRStatusID == 'Decline')
		{
			$hod_level=' hidden';	
			$oh_level=' hidden';			
			$btnOPS=' hidden';			
			$hd_comment_hidden=$btnSave = ' hidden';
		}
		else
		{
			$oh_level='';
			$btnOPS='';
			$aaprovedbyoh = $_SESSION['__user_logid'];
		}
}
if(isset($_POST['btn_save_supercommment']))
{
	$emp_id=$_POST['txt_addleave_empid'];	
	$leaveStatus=$_POST['txt_addleave_Status'];
	$RsnLeave=$_POST['txt_addleave_rsn'];
	$dateon=$_POST['txt_addleave_ondate'];
	$Leavecount=$_POST['txt_addleave_totald'];
	$DateTo=$_POST['txt_addleave_To'];
	$DateFrom=$_POST['txt_addleave_From'];
	$leaveID=$_POST['lvlID'];
	$leaveStatus=$_POST['txt_addleave_hodapp'];
	if(isset($_POST['comment_type']))
	{
		$comment_type = $_POST['comment_type'];
	}
	$myDB=new MysqliDb();
	$result=$myDB->query('SELECT leavehistry.*,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.sub_process FROM leavehistry inner join whole_dump_emp_data on whole_dump_emp_data.EmployeeID=leavehistry.EmployeeID  where LeaveID='.$leaveID);
	//echo $sqlConnect;
	$error=$myDB->getLastError();
	if(count($result) > 0 && $result)
	{
		$emp_name=$result[0]['EmployeeName'];	
		$leaveID=$result[0]['LeaveID'];	
		$emp_id=$result[0]['EmployeeID'];	
		$leaveStatus=$result[0]['EmployeeComment'];	
		$RsnLeave=$result[0]['ReasonofLeave'];	
		$dateon=$result[0]['LeaveOnDate'];	
		$Leavecount=$result[0]['TotalLeaves'];	
		$DateTo=$result[0]['DateTo'];	
		$DateFrom=$result[0]['DateFrom'];
		$HRStatusID = $result[0]['HRStatusID'];
		$emp_dept = $result[0]['sub_process'];	
		$disable='disabled="true"';
		$hd_comment_hidden = '';
		$hod_level=$btnAdd=$btnSave='hidden';
		
		
		$myDB = new MysqliDb();
		$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id  where new_client_master.account_head="'.$_SESSION['__user_logid'].'" and employee_map.EmployeeID = "'.$emp_id.'"');
		if($data_ac)
		{
			foreach($data_ac as $k_d=>$v_d)
			{
				
				if($v_d['EmployeeID'] == $emp_id)
				{
					$hod_level=$btnSave='';
					$hd_comment_hidden = '';
				}
			}
		}
		
		$aaprovedby=$_SESSION['__user_logid'];
		$readonly="readonly='true'";
		$LeaveType=$result[0]['LeaveType'];
	}
	else
	{
		$hod_level=$btnAdd=$btnSave='hidden';
		$btnAdd='';
		$hd_comment_hidden = 'hidden';
	}
	
	$addComment  = 'INSERT INTO leave_comment(`leave_id`,`createdby`,`comment`,`user_type`) VALUES('.$leaveID.',"'.$_SESSION['__user_logid'].'","'.$_POST['txt_super_comment'].'","'.$comment_type.'")'; 
	
	
	$myDB =  new MysqliDb();
	$rst_leave = $myDB->query($addComment);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		echo "<script>$(function(){ toastr.success('Comment Sent Successfully'); }); </script>";		
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Data not updated :: ".$mysql_error."'); }); </script>";		
	}
	
	$sql='select leave_comment.*,personal_details.EmployeeName from leave_comment left outer join personal_details on personal_details.EmployeeID = leave_comment.createdby where leave_comment.leave_id = '.$leaveID;
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	//var_dump($result);
	if(count($result) > 0 && $result ){
		
		foreach($result as $key => $value)
		{
			$result_commet =$result_commet. '<p ><span style="color: #218ea0;">'.$value['EmployeeName'].'(<b>'.$value['createdby'].'</b>)</span> <span style=" font-weight: bold;">'.$value['createdon'].'</span> : '.str_replace("'","",$value['comment']).'</p>';
		}
		$hd_comment_hidden = '';
	}
	else
	{
		$result_commet= 'No Comment ';
		
	}
	
}
if(strtoupper($_SERVER['REQUEST_METHOD']) != 'POST'&&isset($_REQUEST['id'])&&$_REQUEST['req']=='HOD')
{	$myDB=new MysqliDb();
	$result=$myDB->query('SELECT leavehistry.*,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.sub_process FROM leavehistry inner join whole_dump_emp_data on whole_dump_emp_data.EmployeeID=leavehistry.EmployeeID  where LeaveID='.$_REQUEST['id']);
	//echo $sqlConnect;
	$error=$myDB->getLastError();
	if(count($result) > 0 && $result)
	{
		$emp_name=$result[0]['EmployeeName'];	
		$leaveID=$result[0]['LeaveID'];	
		$emp_id=$result[0]['EmployeeID'];	
		$leaveStatus=$result[0]['EmployeeComment'];	
		$RsnLeave=$result[0]['ReasonofLeave'];	
		$dateon=$result[0]['LeaveOnDate'];	
		$Leavecount=$result[0]['TotalLeaves'];	
		$DateTo=$result[0]['DateTo'];	
		$DateFrom=$result[0]['DateFrom'];	
		$HRStatusID = $result[0]['HRStatusID'];
		$aaprovedbyoh=$result[0]['HR'];
		$emp_dept = $result[0]['sub_process'];
		$_ohcomment ='';
		$disable='disabled="true"';
		$hd_comment_hidden = '';		
		$hod_level=$btnAdd=$btnOPS=$btnSave='hidden';
		
		$myDB = new MysqliDb();
		$data_reportto = $myDB->query('select account_head,ReportTo from whole_details_peremp  where whole_details_peremp.EmployeeID = "'.$emp_id.'"');
		$checkAH=$checkReportTo='';
		if(count($data_reportto)>0){
			$checkReportTo = $data_reportto[0]['ReportTo'];
			$checkAH = $data_reportto[0]['account_head'];
		}
		
		
		if(($HRStatusID == 'Decline' || $HRStatusID =='Approve')|| $_SESSION['__user_logid'] == 'CE07147134' ||($checkReportTo == $_SESSION['__user_logid'] && $checkReportTo != $emp_id && $checkAH == $emp_id))
		{
			$myDB = new MysqliDb();
			$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id  where new_client_master.account_head="'.$_SESSION['__user_logid'].'"  and employee_map.EmployeeID = "'.$emp_id.'"');
			if($data_ac)
			{
				foreach($data_ac as $k_d=>$v_d)
				{
					
					if($v_d['EmployeeID'] == $emp_id )
					{
						//echo 'Freee';
						$hod_level=$btnSave='';
						$hd_comment_hidden = 'hidden';
						
					}
				}
			}
			$myDB = new MysqliDb();
			$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id where new_client_master.account_head="'.$emp_id.'" and employee_map.EmployeeID = "'.$_SESSION['__user_logid'].'"');
			if($data_ac)
			{
				foreach($data_ac as $k_d=>$v_d)
				{
					
					if($v_d['EmployeeID'] == $_SESSION['__user_logid'])
					{
						//echo 'HOD';
						$hod_level=$btnAdd=$btnSave='hidden';
						$btnAdd='hidden';
						$hd_comment_hidden = '';
						
					}
				}
			}
			$myDB = new MysqliDb();
			$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id inner join status_table on status_table.EmployeeID = employee_map.EmployeeID  where new_client_master.account_head="'.$emp_id.'" and employee_map.EmployeeID = "'.$emp_id.'" and status_table.ReportTo = "'.$_SESSION['__user_logid'].'"');
		
			if($data_ac)
			{
				foreach($data_ac as $k_d=>$v_d)
				{
					
					if($v_d['EmployeeID'] != $_SESSION['__user_logid'])
					{
						//echo 'Accout';
						$hod_level=$btnSave='';
						$hd_comment_hidden = ' hidden';
						
					}
				}
			}
			
			$myDB = new MysqliDb();
			$data_ac = $myDB->query('select employeeid from whole_details_peremp where ReportTo="CE07147134" and EmployeeID = "'.$emp_id.'"');
		
			if($data_ac)
			{
				foreach($data_ac as $k_d=>$v_d)
				{
					
					if($v_d['EmployeeID'] != $_SESSION['__user_logid'])
					{
						//echo 'Accout';
						$hod_level=$btnSave='';
						$hd_comment_hidden = ' hidden';
						
					}
				}
			}
			
			
			$aaprovedby=$_SESSION['__user_logid'];
			$readonly="readonly='true'";
			$LeaveType=$result[0]['LeaveType'];
			if($_SESSION['__user_logid'] == 'CE07147134' && $emp_id == 'CE07147134')
		    {
				$hod_level=$btnSave='';
				$hd_comment_hidden = ' hidden';
				$aaprovedby=$_SESSION['__user_logid'];
				
				
			}
		}
		else
		{
			$myDB = new MysqliDb();
			$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id  where new_client_master.account_head="'.$_SESSION['__user_logid'].'" and  new_client_master.oh="'.$_SESSION['__user_logid'].'"  and employee_map.EmployeeID = "'.$emp_id.'" and employee_map.EmployeeID = "'.$_SESSION['__user_logid'].'"');
			if($data_ac)
			{
				foreach($data_ac as $k_d=>$v_d)
				{
					
					if($v_d['EmployeeID'] == $emp_id )
					{
						//echo 'Freee';
						$aaprovedby=$_SESSION['__user_logid'];
						$oh_level =' ';
						$hod_level=$btnSave=' hidden';
						$aaprovedbyoh =$_SESSION['__user_logid'];
						$hd_comment_hidden = 'hidden';
						$_ohcomment ='NA';
						$appLink ='<a onclick="submitform(\''.$emp_id.'\',\''.$DateTo.'\');" > Check Biometric and Roster</a>';
						$btnOPS =' ';
						$btnSave='hidden';
						
					}
				}
			}
			$myDB = new MysqliDb();
			$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id  where new_client_master.account_head ="'.$_SESSION['__user_logid'].'" and  new_client_master.oh ="'.$_SESSION['__user_logid'].'"  and employee_map.EmployeeID = "'.$emp_id.'" and employee_map.EmployeeID != "'.$_SESSION['__user_logid'].'"');
			if($data_ac)
			{
				foreach($data_ac as $k_d=>$v_d)
				{
					
					if($v_d['EmployeeID'] == $emp_id )
					{
						//echo 'Freee';
						$aaprovedby=$_SESSION['__user_logid'];
						$oh_level =' hidden';
						$hod_level=$btnSave='';
						$aaprovedbyoh =$_SESSION['__user_logid'];
						$hd_comment_hidden = 'hidden';
						$_ohcomment ='NA';
						$appLink ='<a onclick="submitform(\''.$emp_id.'\',\''.$DateTo.'\');" > Check Biometric and Roster</a>';
						$btnOPS =' hidden';
						$btnSave='';
					}
				}
			}
			$myDB = new MysqliDb();
			$data_ac = $myDB->query('select employee_map.EmployeeID from employee_map inner join new_client_master on employee_map.cm_id = new_client_master.cm_id  where new_client_master.account_head !="'.$_SESSION['__user_logid'].'" and  new_client_master.oh ="'.$_SESSION['__user_logid'].'"  and employee_map.EmployeeID = "'.$emp_id.'"');
			if($data_ac)
			{
				foreach($data_ac as $k_d=>$v_d)
				{
					
					if($v_d['EmployeeID'] == $emp_id )
					{
						//echo 'Freee';
						$oh_level ='';
						$_ohcomment ='';
						$aaprovedbyoh =$_SESSION['__user_logid'];
						$hod_level=$btnSave='hidden';
						$hd_comment_hidden = 'hidden';
						$appLink ='<a onclick="submitform(\''.$emp_id.'\',\''.$DateTo.'\');"> Check Biometric and Roster</a>';
						$btnOPS ='';
						$btnSave=' hidden';
					}
				}
			}
			
			$readonly="readonly='true'";
			$LeaveType=$result[0]['LeaveType'];
		}
		$appLink ='<a onclick="submitform(\''.$emp_id.'\',\''.$DateTo.'\');" > Check Biometric and Roster</a>';
	}
	else
	{
		$hod_level=$btnAdd=$btnSave='hidden';
		$btnAdd='';
		$hd_comment_hidden = 'hidden';
	}
	$sql='select leave_comment.*,personal_details.EmployeeName from leave_comment left outer join personal_details on personal_details.EmployeeID = leave_comment.createdby where leave_comment.leave_id = '.$leaveID;
	$myDB=new MysqliDb();
	$result=$myDB->query($sql);
	$mysql_error=$myDB->getLastError();
	if(count($result) > 0 && $result){
		
		foreach($result as $key => $value)
		{
			$result_commet =$result_commet. '<p ><span style="color: #218ea0;">'.$value['EmployeeName'].'(<b>'.$value['createdby'].'</b>)</span> <span style=" font-weight: bold;">'.$value['createdon'].'</span> : '.str_replace("'","",$value['comment']).'</p>';
		}
		
	}
	else
	{
		$result_commet= 'No Comment ';
		
	}
}
elseif(strtoupper($_SERVER['REQUEST_METHOD']) != 'POST'&&isset($_REQUEST['id'])&&$_REQUEST['req']=='EX')
{
	$myDB=new MysqliDb();
	$result=$myDB->query('SELECT leavehistry.*,whole_dump_emp_data.EmployeeName,whole_dump_emp_data.sub_process FROM leavehistry inner join whole_dump_emp_data on whole_dump_emp_data.EmployeeID=leavehistry.EmployeeID  where LeaveID='.$_REQUEST['id']);
	//echo $sqlConnect;
	$error=$myDB->getLastError();
	if($result)
	{
		$emp_name=$result[0]['EmployeeName'];	
		$leaveID=$result[0]['LeaveID'];	
		$emp_id=$result[0]['EmployeeID'];	
		$leaveStatus=$result[0]['EmployeeComment'];	
		$RsnLeave=$result[0]['ReasonofLeave'];	
		$dateon=$result[0]['LeaveOnDate'];	
		$Leavecount=$result[0]['TotalLeaves'];	
		$DateTo=$result[0]['DateTo'];	
		$DateFrom=$result[0]['DateFrom'];
		$HRStatusID = $result[0]['HRStatusID'];	
		$emp_dept = $result[0]['sub_process'];
		
		$disable='disabled="true"';
		$hd_comment_hidden = '';
		$hod_level=$btnAdd=$btnSave='hidden';
		$hod_level=$btnAdd=$btnSave='hidden';
		$aaprovedbyoh=$result[0]['HR'];
		$aaprovedby=$_SESSION['__user_logid'];
		
		$readonly="readonly='true'";
		$LeaveType=$result[0]['LeaveType'];
	   // $appLink ='<a href="view_BioMetric_one.php?p_EmpID='.$emp_id.'&date='.$DateTo.'" target="_blank" > Check Biometric and Roster</a>';
		
	}
	if($leaveID!=''){
		$sql='select leave_comment.*,personal_details.EmployeeName from leave_comment left outer join personal_details on personal_details.EmployeeID = leave_comment.createdby where leave_comment.leave_id = '.$leaveID;
		$myDB=new MysqliDb();
		$result=$myDB->query($sql);
		$mysql_error=$myDB->getLastError();
		if($result){
			
			foreach($result as $key => $value)
			{
				$result_commet =$result_commet. '<p ><span style="color: #218ea0;">'.$value['EmployeeName'].'(<b>'.$value['createdby'].'</b>)</span> <span style=" font-weight: bold;">'.$value['createdon'].'</span> : '.str_replace("'","",$value['comment']).'</p>';
			}
			
		}
		else
		{
			$result_commet= 'No Comment ';
			
		}
	}
	if($leaveStatus == 'Decline' || $leaveStatus =='Approve')
	{
			$hod_level=' hidden';	
			$oh_level=' hidden';			
			$btnOPS='hidden';			
			$hd_comment_hidden=$btnSave = ' hidden';
	}
}
?>


<script>
	

$(document).ready(function(){
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         buttons: [
						        {
						            extend: 'excel',
						            text: 'EXCEL',
						            extension: '.xlsx',
						            exportOptions: {
						                modifier: {
						                    page: 'all'
						                }
						            },
						            title: 'table'
						        },'pageLength'
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"iDisplayLength": 10,
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() 
							{
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
			});
		$('#myTable1').DataTable({
				        dom: 'Bfrtip',
				        lengthMenu: [
				            [ 5,10, 25, 50, -1 ],
				            ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
				        ],
				         buttons: [
						        {
						            extend: 'excel',
						            text: 'EXCEL',
						            extension: '.xlsx',
						            exportOptions: {
						                modifier: {
						                    page: 'all'
						                }
						            },
						            title: 'table'
						        },'pageLength'
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"iDisplayLength": 10,
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() 
							{
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
			});

		   	
		   	$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	
	});
</script>
<style>
	.daterangepicker.dropdown-menu 
	{
		display: none;
	}
	.daterangepicker
	{
		border: 1px solid #e0e0e0;
	}
	.daterangepicker .input-mini
	{
		border: none!important;
		max-width: 200px!important;
	    min-width: 200px!important;
	    width: 200px!important;
	}
	.daterangepicker .input-mini.active
	{
		border: none!important;
	}
	.daterangepicker.ltr .ranges,.ranges
	{
		float:none!important;
	}
	@media (min-width: 730px)
	{
		.daterangepicker.ltr .ranges,.ranges
		{
			float:none!important;
		}
	}
	.daterangepicker.ltr .range_inputs
	{
		float: right;	
		margin-top: 10px;
   		width: 100%;
   		text-align: right;
	}
	.daterangepicker td.disabled, .daterangepicker option.disabled
	{
		color: #999!important;
	}
	.daterangepicker td.off, .daterangepicker td.off.in-range, .daterangepicker td.off.start-date, .daterangepicker td.off.end-date
	{
		background-color: #fff!important;
	    border-color: transparent!important;
	    color: #999!important;
	}
	.daterangepicker td.active, .daterangepicker td.active:hover {
	    background-color: #357ebd!important;
	    border-color: transparent!important;
	    color: #fff!important;
	} 
	.daterangepicker td.in-range
	{
	    background-color: #ebf4f8!important;
	    border-color: transparent!important;
	    color: #000!important;
	    border-radius: 0;
	}  
	.ui-accordion .ui-accordion-content {
    	padding: 0px 15px;
	    padding-bottom: 10px;
	    background: #fdfdfd;
	    border-radius: 0px;
	}
	.daterangepicker td.active, .daterangepicker td.active:hover
    {
        background-color: #357ebd!important;
        border-color: transparent!important;
        color: #fff!important;
    }
    .daterangepicker .calendar th, .daterangepicker .calendar td
    {
		min-width: 20px !important;
	}
</style>

<div id="content" class="content">
<span id="PageTittle_span" class="hidden">Add Leave</span>

	<div class="pim-container">
	<div class="form-div">
	 <?php 
				$link_to_report = '';
				if(($_SESSION['__status_th']==$_SESSION['__user_logid'] || $_SESSION['__status_oh']==$_SESSION['__user_logid'] || $_SESSION['__status_qh']==$_SESSION['__user_logid']) || ((($_SESSION['__status_ah']!='No' && $_SESSION['__status_ah']==$_SESSION['__user_logid']) && $_SESSION['__status_ah']!='')|| $_SESSION['__user_type']=='ADMINISTRATOR' || $_SESSION['__user_type']=='CENTRAL MIS' || $_SESSION['__user_type']=='HR'))
				{
					 $link_to_report = '<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="ex_lh" data-position="bottom" data-tooltip="Leave Report" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>';
					 
				}
				elseif(!($_SESSION["__user_Desg"] == "CSE" || $_SESSION["__user_Desg"] == "C.S.E." || $_SESSION["__user_Desg"] == "Sr. C.S.E" || $_SESSION["__user_Desg"] == "C.S.E" || $_SESSION["__user_Desg"] == "Senior Customer Care Executive" || $_SESSION["__user_Desg"] == "Customer Care Executive" || $_SESSION["__user_Desg"] == "CSA" || $_SESSION["__user_Desg"] == "Senior CSA"))
			    {
					$link_to_report = '<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="rpt_Leave_for_rt.php" data-position="bottom" data-tooltip="Leave Report" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a>';		 	
				}
		       ?>
	
	 <h4>Add Leave<?php echo $link_to_report; ?><?php include(__dir__.'/../Controller/coleave_cal.php');?></h4>	
	 <div class="schema-form-section row">
	   <div class="hid-container col s12 m12" id="app_link"><?php echo $appLink;?></div>
			
			  

			<input type="hidden" id="comment_type" name="comment_type" value="<?php echo $comment_type;?>"/>
			<input type="hidden" id="lvlID" name="lvlID" value="<?php echo $leaveID;?>"/>
		
			
			<div class="input-field col s3" >
				<div class="input-field col s12">
					<label for="txt_branch_name"> Select Date</label>
					<input type="text" <?php echo $disable; ?> id="txt_addleave_period"   name="txt_addleave_period"  >	
				</div>
			</div>
			
			
			
			<div class="input-field col s9">
				 
						    <div class="input-field col s4 m4">
							           <input type="text"   id="txt_addleave_ondate" readonly="true"  name="txt_addleave_ondate"  value="<?php echo $dateon;?>" required="true"/>						         
							         <label for="txt_addleave_ondate" >Leave On Date</label> 
						    </div>
						    <div class="input-field col s4 m4">
							           <input type="text"   id="txt_addleave_totald" readonly="true"  name="txt_addleave_totald"  value="<?php echo $Leavecount;?>" required="true"/>	
							           <label for="txt_addleave_totald" >Total Days </label>
						    </div>
						  
						   <div class="input-field col s4 m4">
						     
					           <input type="text"   id="txt_addleave_From"  readonly="true"   name="txt_addleave_From"  value="<?php echo $DateFrom;?>" required="true"/>	
					            <label for="txt_addleave_From" >Leave From </label>
							 
						    </div>
						    <div class="input-field col s4 m4">
						   
					           <input type="text"   id="txt_addleave_To"  readonly="true"   name="txt_addleave_To"  value="<?php echo $DateTo;?>" required="true"/>	
					           <label for="txt_addleave_To" >Leave To </label>
							         
						    </div>
						    
						    <div class="input-field col s4 m4">
						       <select  id="txt_LeaveType" name="txt_LeaveType"  required="true">
							           		<option value="Leave" <?php echo ($LeaveType=='Leave')?'Selected':''; ?>>Leave</option>
							           		<?php 
												
												$myDB=new MysqliDb();
												$rst = $myDB->query('select type_ from roster_temp where EmployeeID = "'.$_SESSION['__user_logid'].'" and DateOn ="'.date('Y-m-d',time()).'" order by id desc limit 1');
												if(count($rst) > 0)
												{
																								
													if(intval($rst[0]['type_']) != 3)
													{
														?>
														<option value="Half Day" <?php echo ($LeaveType=='Half Day')?'Selected':''; ?>>Half Day</option>
														<?php
													}
													else
													{
														?>
														<option value="Half Day" <?php echo ($LeaveType=='Half Day')?'Selected':''; ?>>Half Day</option>
														<?php
													}
												}else
												{
													?>
													<option value="Half Day" <?php echo ($LeaveType=='Half Day')?'Selected':''; ?>>Half Day</option>
													<?php
												}
												?>
							           		
							           		
							           </select>	
							   <label for="txt_LeaveType" class="dropdown-active active">Leave Type </label>
						    </div>
						    <div class="input-field col s4 m4">
						      
							           <input type="text"   id="txt_addleave_empname" readonly="true"   name="txt_addleave_empname"  value="<?php echo $emp_name; ?>" />	
							           <label for="txt_addleave_empname" >Employee Name </label>
							
						    </div>
						    
						    <div class="input-field col s4 m4">
						      
					           <input type="text"   id="txt_addleave_dept"  readonly="true"  name="txt_addleave_dept"  value="<?php echo $emp_dept; ?>" />	
					           <label for="txt_addleave_dept" >Sub Process </label>
							       
						    </div>
						    <div class="input-field col s4 m4">
						      
					           <input type="text"   id="txt_addleave_empid"  readonly="true"  name="txt_addleave_empid"   value="<?php echo $emp_id; ?>" required="true"/>	
					           <label for="txt_addleave_empid" >Employee ID </label>
							       
						    </div>
						    <div class="input-field col s4 m4">
						     
					           <input type="text"   id="txt_addleave_Status" readonly="true"  name="txt_addleave_Status" value="<?php echo $leaveStatus;?>" />	
					            <label for="txt_addleave_Status">Leave Status </label>
							         
						    </div>
						    <div class="input-field col s4 m4">
						      
					           <input type="text" id="txt_addleave_OHStatus" readonly="true"  name="txt_addleave_OHStatus" value="<?php echo $HRStatusID;?>" />	
					           <label for="txt_addleave_OHStatus">OH Status </label>
							        
						    </div>
						    
						   
						    <div id="divLeaveReason" class="input-field col s12 m12" >
						           <textarea <?php echo $readonly;?>  class="materialize-textarea " id="txt_addleave_rsn"  name="txt_addleave_rsn" ><?php echo $RsnLeave; ?></textarea>	
						           <label for="txt_addleave_rsn">Reason For Leave </label>
							</div> 
							         
							<div class="OH_level <?php echo $oh_level; ?> ">
							
								<div class="input-field col s4 m4">
							      
						           <select  id="txt_addleave_ohapp"   name="txt_addleave_ohapp">
						           <option <?php if($HRStatusID=='Pending'){echo 'selected';}?>>Pending</option>
						           <option <?php if($HRStatusID=='Approve'){echo 'selected';}?>>Approve</option>
						           <option <?php if($HRStatusID=='Decline'){echo 'selected';}?>>Decline</option>
						           
						           </select>
						           <label for="txt_addleave_ohapp" class="dropdown-active active">OH Approval:</label>	
								    
							    </div>
							    <div class="input-field col s4 m4">
							      
							     		
						           <input type="text"  id="txt_addleave_ApproveBy_oh" readonly="true"  name="txt_addleave_ApproveBy_oh"  value="<?php echo $aaprovedbyoh;?>" title="Approve By You"/>	
						           <label for="txt_addleave_ApproveBy_oh">Approve By </label>
								      
							    </div>
							    <div class="input-field col s12 m12" >
							     
						             <textarea   class="materialize-textarea"  id="txt_addleave_ohcmt"  name="txt_addleave_ohcmt" ><?php echo $_ohcomment;?></textarea>	
						            <label for="txt_addleave_hodcmt">OH Comment </label>
						         </div> 
						    </div> 
							<div class="HOD_level <?php echo $hod_level; ?>">
							
								<div class="input-field col s4 m4">
							      
						           <select  id="txt_addleave_hodapp"   name="txt_addleave_hodapp">
							           <option>Pending</option>
							           <option>Approve</option>
							           <option>Decline</option>
						           </select>
						           <label for="txt_addleave_hodapp" class="dropdown-active active">Account Head Approval:</label>	
								    
							    </div>
							    <div class="input-field col s4 m4">
							     
						           <input type="text"  id="txt_addleave_ApproveBy" readonly="true"  name="txt_addleave_ApproveBy"  value="<?php echo $aaprovedby;?>" title="Approve By You"/>
						           <label for="txt_addleave_ApproveBy">Approve By </label>	
								 
							    </div>
							    <div class="input-field col s12 m12" >
						           <textarea   class="materialize-textarea" id="txt_addleave_hodcmt"  name="txt_addleave_hodcmt" ><?php echo $_hodcomment;?></textarea>	
						           <label for="txt_addleave_hodcmt">Account Head Comment </label>
						         </div> 
						    </div> 
						   <div id="comment_box" class="hidden col s12 m12 l12">
								<h3 style="border-bottom: 2px solid #1a90a2;border-right: 2px solid #1daec5;background: #1daec5;color: white;">Comments Section</h3>
								
								<div id="comment_container" style="margin: 1px;">
									
									
								</div>	
							</div>
			
						   <div class="input-field col s12 m12 <?php echo $hd_comment_hidden;?>" id="hd_newCom" style="    padding-left: 0px;">
							      
							     		<textarea   class="materialize-textarea" id="txt_super_comment"  name="txt_super_comment" ></textarea>	
							     		<label for="txt_super_comment">Enter Comment </label>
							     		<input type="submit" class="btn waves-effect waves-green" id="btn_save_supercommment" name="btn_save_supercommment" value="Submit"/>
				          </div> 
						  <div class="input-field col s12 m12 right-align" >
						    
						    <button type="submit" name="btn_Leave_Add" id="btn_Leave_Add" class="btn waves-effect waves-green  <?php echo $btnAdd;?>">Add</button>
						    <button type="submit" name="btn_Leave_Save" id="btn_Leave_Save" class="btn waves-effect waves-green  <?php echo $btnSave;?>">Save</button>
						    <button type="submit" name="btn_Leave_ops_Save" id="btn_Leave_ops_Save" class="btn waves-effect waves-green  <?php echo $btnOPS;?>">Save</button>
						</div> 
						    	
				
			</div>
			
			
		    <div id="pnlTable">
		     
			  	
			    <?php 
					$sqlConnect = 'call get_pendingleave("'.$_SESSION['__user_logid'].'")'; 
					$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					//echo $sqlConnect;
					$error=$myDB->getLastError();
					if(count($result) > 0 && $result)
					{?>
					<div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
				       <div class=""  >																											                 <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
					       <thead>
					        <tr>
					            <th>Action</th>
					            <th>EmployeeID</th>
					            <th>FullName</th>
					            <th>DateFrom</th>
					            <th>DateTo</th>
					            <th>TotalLeaves</th>
					            <th>Status</th>						            
					     		<th>Employee Comment</th>	
					     		<th>Remark</th>					            
					        </tr>
					      </thead>
					       <tbody>					        
					       <?php
					        foreach($result as $key=>$value){
							echo '<tr>';							
							
							
				     		echo '<td class="EmployeeID manage_item"><a href="'.URL.'View/addleave?id='.$value['LeaveID'].'&req=HOD"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>';
				     		
							echo '<td class="EmployeeID">'.$value['EmployeeID'].'</a></td>';
							echo '<td class="EmployeeName">'.$value['EmployeeName'].'</td>';					
							echo '<td class="DateFrom">'.$value['DateFrom'].'</td>';					
							echo '<td class="DateTo">'.$value['DateTo'].'</td>';	
							echo '<td class="TotalLeaves">'.$value['TotalLeaves'].' Days</td>';	
							echo '<td class="EmployeeComment">'.$value['EmployeeComment'].'</td>';					
							echo '<td class="ReasonofLeave">'.$value['ReasonofLeave'].'</td>';	
							echo '<td class="ManagerComment">'.$value['ManagerComment'].'</td>';	
							echo '</tr>';
							}	
							?>			       
					      </tbody>
						  </table>
						</div>
					</div>
					<?php
						 }
						else
						{
							//echo '<div class="alert alert-danger">No Applied Leave Data Found  :: <code >'.$error.'</code> </div>';
						} 
					$sqlConnect = 'call get_pendingleave_emp("'.$_SESSION['__user_logid'].'")'; 
					$myDB=new MysqliDb();
					$result = $myDB->query($sqlConnect);
					//echo $sqlConnect;
					$error=$myDB->getLastError();
					if(count($result) > 0 && $result)
					{?>
				   <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
				     <div class=""  >																											              <table id="myTable1" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
					    <thead>
					        <tr>
					        	<th>View</th>
					            <th>EmployeeID</th>
					            <th>FullName</th>
					            <th>DateFrom</th>
					            <th>DateTo</th>
					            <th>TotalLeaves</th>
					            <th>Status</th>	
					     		
					     		<th>action</th>	
					        </tr>
					    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($result as $key=>$value){
							echo '<tr>';							
							$count++;
							echo '<td class="EmployeeID manage_item"><a href="'.URL.'View/addleave?id='.$value['LeaveID'].'&req=EX"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>';
							echo '<td class="EmployeeID">'.$value['EmployeeID'].'</a></td>';
							echo '<td class="EmployeeName">'.$value['EmployeeName'].'</td>';				
							echo '<td class="DateFrom">'.$value['DateFrom'].'</td>';					
							echo '<td class="DateTo">'.$value['DateTo'].'</td>';	
							echo '<td class="TotalLeaves">'.$value['TotalLeaves'].' Days</td>';	
							echo '<td class="EmployeeComment">'.$value['EmployeeComment'].'</td>';	/*				
							echo '<td class="ReasonofLeave">'.$value['leavehistry']['ReasonofLeave'].'</td>';	
							echo '<td class="ManagerComment">'.$value['leavehistry']['ManagerComment'].'</td>';	*/
							if($value['EmployeeComment'] != 'Approve' && $value['EmployeeComment'] != 'Decline')
							{
								echo '<td class="manage_item"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Delete" onclick="javascript:return delete_item(this);" data-item="'.$value['LeaveID'].'">ohrm_delete</i></td>';
							}
							else
							{
								echo '<td class="delete"><span>None</span></td>';
							}
							echo '</tr>';
							}	
							?>			       
					    </tbody>
				      </table>
				    </div>
				  </div>
				<?php
				 }
				else
				{
				    echo '<div class="alert alert-danger">Data Not Found :: <code >'.$error.'</code> </div>';
				} 
				?>
				
	       </div>
      </div>
	</div>
   </div>		 
</div>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
<script>
	$(document).ready(function(){
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}
		else
		{
			$('#alert_message').delay(5000).fadeOut("slow");
		}
		/*$('#btn_Leave_Add,#btn_Leave_Save').on('click', function(){
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
						alert(spanID);
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
		        		    
		      	if(validate==1)
		      	{
					return false;
				} 
		    });*/
		    
		    $('#btn_Leave_Add').on('click', function(){
		        var validate=0;
		        var alert_msg='';		        
		        
		        var spanID =  "spantxt_addleave_rsn";		        	
		        $('#txt_addleave_rsn').removeClass('has-error');
		        var attr_req = $('#txt_addleave_rsn').attr('required');
				if($('#txt_addleave_rsn').val()=='')
		        {
					validate=1;
					$('#txt_addleave_rsn').addClass('has-error');
					if ($('#'+spanID).size() == 0) 
					{
			            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#txt_addleave_rsn');
			        }
			        var attr_error = $('#txt_addleave_rsn').attr('data-error-msg');
				    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
				    {
						$('#'+spanID).html('Required *');	
					}
					else
					{
						$('#'+spanID).html($('#txt_addleave_rsn').attr("data-error-msg"));
					}
				}
		      	if(validate==1)
		      	{
					return false;
				}
		       
		    });
		    $('#btn_Leave_Save').on('click', function(){
		        
				var validate=0;
		        var alert_msg='';		        
		        
		        var spanID =  "spantxt_addleave_hodcmt";		        	
		        $('#txt_addleave_hodcmt').removeClass('has-error');
		        var attr_req = $('#txt_addleave_hodcmt').attr('required');
				if($('#txt_addleave_hodcmt').val()=='')
		        {
					validate=1;
					
					$('#txt_addleave_hodcmt').addClass('has-error');
					if ($('#'+spanID).size() == 0) 
					{
			            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#txt_addleave_hodcmt');
			        }
			        var attr_error = $('#txt_addleave_hodcmt').attr('data-error-msg');
				    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
				    {
						$('#'+spanID).html('Required *');	
					}
					else
					{
						$('#'+spanID).html($('#txt_addleave_hodcmt').attr("data-error-msg"));
					}
				}
		      	if(validate==1)
		      	{
					return false;
				}
		       
		    });
		    
			    $('#comment_box').removeClass('hidden');
					$('#comment_container').empty().append(<?php echo "'".preg_replace( "/\r|\n/", " ", $result_commet)."'"; ?>);
					$('#comment_box').accordion({
				      collapsible: true,
				      heightStyle: "content" 
				});
			if($('#txt_addleave_rsn').attr('readonly'))
			{
				$('#txt_addleave_rsn').hide();
				$('#divLeaveReason').addClass('hidden');
			}	
			
			if($('#comment_container').text()== '')
			{
				$('#comment_box').addClass('hidden')
			}	
			$('#btn_save_supercommment').click(function(){
				
				
				var validate=0;
		        var alert_msg='';		        
		        
		        var spanID =  "spantxt_super_comment";		        	
		        $('#txt_super_comment').removeClass('has-error');
		        var attr_req = $('#txt_super_comment').attr('required');
				if($('#txt_super_comment').val()=='')
		        {
					validate=1;
					$('#txt_super_comment').addClass('has-error');
					if ($('#'+spanID).size() == 0) 
					{
			            $('<span id="'+spanID+'" class="help-block"></span>').insertAfter('#txt_super_comment');
			        }
			        var attr_error = $('#txt_super_comment').attr('data-error-msg');
				    if(!(typeof attr_error !== typeof undefined && attr_error !== false))
				    {
						$('#'+spanID).html('Required *');	
					}
					else
					{
						$('#'+spanID).html($('#txt_super_comment').attr("data-error-msg"));
					}
				}
		      	if(validate==1)
		      	{
					return false;
				}
			});	
	});
	function delete_item(el)
	{
		
		if(confirm('you want to delete Leave?? '))
		{
			$item=$(el);
			
			$.ajax({url: "../Controller/deleteLeave.php?ID="+$(el).attr('data-item'), success: function(result){	                   
                var data=result.split('|');
				toastr.success(data[1]);
	      		
                if(data[0]=='Done')
                {
		      		$item.closest('td').parent('tr').remove(); 
				}
	      		           
                                                    
            }});
		}
	}
	
	
function submitform(emp_id,DateTo){
	$('#p_EmpID').val(emp_id);
	$('#pdate').val(DateTo);
	document.getElementById('sendID').submit();
}
</script>

<form  target='_blank' id='sendID' name='sendID' method='post' action='view_BioMetric_one.php' style="min-height: 5px;height: 5px;">
 	<input type='hidden' name='p_EmpID' id='p_EmpID' >
 	<input type='hidden' name='date' id='pdate'  >
</form>
<script type="text/javascript" src="<?php echo SCRIPT.'rangepicker/moment.min.js' ;?>"></script>
<script type="text/javascript" src="<?php echo SCRIPT.'rangepicker/daterangepicker.js' ;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SCRIPT.'rangepicker/daterangepicker.css' ;?>" />
<script>
	$(document).ready(function(){
		function calcBusinessDays(dDate1, dDate2) {         // input given as Date objects

				  var iWeeks, iDateDiff, iAdjust = 0;

				  if (dDate2 < dDate1) return -1;                 // error code if dates transposed

				  var iWeekday1 = dDate1.getDay();                // day of week
				  var iWeekday2 = dDate2.getDay();

				  iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1;   // change Sunday from 0 to 7
				  iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;

				  if ((iWeekday1 > 6) && (iWeekday2 > 6)) iAdjust = 1;  // adjustment if both days on weekend

				  iWeekday1 = (iWeekday1 > 6) ? 6 : iWeekday1;    // only count weekdays
				  iWeekday2 = (iWeekday2 > 6) ? 6 : iWeekday2;

				  // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
				  iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)

				  if (iWeekday1 <= iWeekday2) {
				    iDateDiff = (iWeeks * 6) + (iWeekday2 - iWeekday1)
				  } else {
				    iDateDiff = ((iWeeks + 1) * 6) - (iWeekday1 - iWeekday2)
				  }

				  iDateDiff -= iAdjust                            // take into account both days on weekend

				  return (iDateDiff + 1);                         // add 1 because dates are inclusive

				}
		var today = new Date();
		var maxDate= today.setMonth(today.getMonth() + 2);
		
		$('#txt_addleave_period').daterangepicker(
			{
			    locale: {
			      format: 'YYYY-MM-DD'
			    }
			    ,
			    minDate:new Date(),
    			maxDate: new Date(maxDate)
			}, 
			function(start, end, label) {
			    
			    
			    $('#alert_message').show();
			  	
			  	$('#txt_addleave_From').val(start.format('YYYY-MM-DD'));
			  	$('#txt_addleave_To').val(end.format('YYYY-MM-DD'));
			  
		        var date1=new Date(start);
		        var date2=new Date(end);
		        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
				var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
				 //alert(diffDays);
				 //For Leave Without Sunday;
		        //$("#txt_addleave_totald").val(calcBusinessDays(date1,date2));
		        // For Leave With Sunday./..
		        $("#txt_addleave_totald").val(diffDays);
			  	toastr.success("A new date range was chosen:" + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD')+'  Sundays are :<b>'+parseInt(diffDays-calcBusinessDays(date1,date2))+"  Working Day count :"+calcBusinessDays(date1,date2) + ' Total Leave are : '+ diffDays +'');
			});
			if($('.applyBtn').html()=='Apply')
			{
				$('.applyBtn').html('Ok');
			}
	});
</script>
