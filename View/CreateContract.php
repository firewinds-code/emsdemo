<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
require CLS.'../lib/PHPMailer-master/class.phpmailer.php';
require CLS.'../lib/PHPMailer-master/PHPMailerAutoload.php';
include_once(__dir__.'/../Services/sendsms_API1.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH.'AppCode/nHead.php');
$createBy=$_SESSION['__user_logid'];
$pankj_mobile='';
ini_set('display_errors', '0');
function settimestamp($module,$type)
{
			$myDB=new MysqliDb();
			$sq1="insert into scheduler(modulename,type)values('".$module."','".$type."');";
			$myDB->query($sq1);
}
			$myDB=new MysqliDb();
			$sq_mobile=$myDB->query("select mobile from contact_details  where EmployeeID='CE091930141';");
			if(isset($sq_mobile[0]['mobile']) and $sq_mobile[0]['mobile']!=""){
				 $pankj_mobile=$sq_mobile[0]['mobile'];
			};
$EmployeeID=$btnShow='';
$Marriage_Status=$Marriage_Spouse=$Marriage_Date=$ChildStatus=$ChildGender=$BloodGroup=$ChildDob=$txt_child_name_1=$cname_array='';
$emp_id=$revisionQuery=$insert_data_val1=$processName='';
$mobile='';
$EmployeeName= $emsg='';
$EmployeeName= $emsg='';
//-------------------------- Personal Details TextBox Details ----------------------------------------------//
if(!isset($_SESSION['tablrquery'])){
	$_SESSION['tablrquery']="";
}
if(isset($_SESSION['__user_logid'])&& $_SESSION['__user_logid']!='')
{
	$createBy=$_SESSION['__user_logid'];
}
if (isset($_POST['btn_contact_Save1']) && $_POST['cm_id']!="" ) 
{
	
	$cm_id=$_POST['cm_id'];
	$processName=$_POST['processName'];
	$txt_para=(isset($_POST['txt_para'])? $_POST['txt_para'] : null);
	//$paravalue=(isset($_POST['paravalue'])? $_POST['paravalue'] : null);
	$type_array=(isset($_POST['type_array'])? $_POST['type_array'] : null);
	$ddvaldata=(isset($_POST['ddvaldata'])? $_POST['ddvaldata'] : null);
	$myDB=new MysqliDb();
	$revision=0;
	$select_revision=$myDB->query("SELECT * from cmid_revision where cm_id='".$cm_id."' ");
	if(count($select_revision)>0){
		$revision=$select_revision[0]['revision'];
		$revision=$revision+1;
	}else{
		$revision=$revision+1;
	}

     $tablename="CtrctDetails_".$cm_id."_".$revision;
    $create_Table1='';
	$create_Table="CREATE TABLE ".$tablename." (Revision int(10), cm_id varchar(20)";
	for($i=0;$i<count($txt_para);$i++){
		//$create_Table1.=",".$txt_para[$i]." varchar(100) , ".$type_array[$i]."_".$i." varchar(255)";
		$create_Table1.=",".$txt_para[$i]." TEXT ";
	}
	
	  $create_Table.=$create_Table1. " );";
	if($_SESSION['tablrquery']!=$create_Table1.$cm_id ){
		$_SESSION['tablrquery']=$create_Table1.$cm_id;
	}else{
		 $_SESSION['tablrquery']="";
	}

	
	if($_SESSION['tablrquery']!="")
	{
		$myDB=new MysqliDb();
		
		$myDB->query("INSERT INTO ctrctdetails_master set table_name='".$tablename."', revision='".$revision."', cm_id='".$cm_id."'");
		
		
		$myDB=new MysqliDb();
		$myDB->query($create_Table);
		
		if(count($select_revision)>0){
			$revisionQuery="Update cmid_revision set revision='".$revision."'  where cm_id='".$cm_id."' ";
		}else{
			$revisionQuery="Insert into cmid_revision set revision='".$revision."',cm_id='".$cm_id."' ";
		}
		if($revisionQuery!=""){
			$myDB=new MysqliDb();
			$myDB->query($revisionQuery);
		}
		$insert_data_val="Insert into ".$tablename." set revision='".$revision."',cm_id='".$cm_id."'" ;
		for($i=0;$i<count($txt_para);$i++){
			if($type_array[$i]=='File'){
				$uploadOk=1;
				//echo  $_FILES['ddvaldata']['name'];
				//echo  "<BR>";
				foreach ($_FILES['ddvaldata']['name'] as $j => $value) 
				{
					if ($_FILES['ddvaldata']['size'][$j] > 400000) {
						echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 400KB File only.'); }); </script>";
						$uploadOk = 0;
					}
					$filePath = ROOT_PATH."parafile/";
					$sourcePath =$_FILES['ddvaldata']['tmp_name'][$j];
					$targetPath = $filePath.basename($_FILES['ddvaldata']['name'][$j]);
					
					$FileType = strtolower(pathinfo($targetPath,PATHINFO_EXTENSION));
					if ($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg" && $FileType != "pdf") {
						echo "<script>$(function(){ toastr.error('Sorry, only jpg,jpeg, png and pdf files are allowed.'); }); </script>";
						$uploadOk = 0;
					}
					if($uploadOk==1) 
					{
						if (move_uploaded_file($sourcePath,$targetPath))
						{
							$ext = pathinfo(basename($_FILES['ddvaldata']['name'][$j]), PATHINFO_EXTENSION);
							$filename=$txt_para[$i]."_".$cm_id.'_'.$revision.'_file_'.'.'.$ext;
						
							$file=rename($targetPath,$filePath.$filename);
							$ddvaldata[$i]=$filename;
						}else{
							echo "Not uploaded";
						}
					}
			  	}
			}
			//$insert_data_val1.=",".$txt_para[$i]." ='".$paravalue[$i]."',".$type_array[$i]."_".$i."='".$ddvaldata[$i]."'";
			$insert_data_val1.=",".$txt_para[$i]." ='".addslashes($ddvaldata[$i])."'";
			
		
		}
		 $insert_data_val.=$insert_data_val1;
		$myDB=new MysqliDb();
		$myDB->query($insert_data_val);
		$mysql_error =$myDB->getLastError();
		if(empty($mysql_error))
		{
			$myDB=new MysqliDb();
			// $email_query="select a.cm_id, a.account_head,a.VH,a.oh,b.mobile,b.emailid,b.ofc_emailid,c.EmployeeName from new_client_master a inner join contact_details b on a.account_head=b.EmployeeID  inner Join  personal_details c on c.EmployeeID=b.EmployeeID where a.cm_id='".$cm_id."'";
			 $email_query="select cm_id, account_head,GetContact(account_head) as ah_contact,GetName(account_head) as ahname ,VH,GetContact(VH) as vh_contact,GetName(VH) as vhname ,oh,GetContact(oh) as oh_contact,GetName(oh) as ohname  from new_client_master a  where cm_id='".$cm_id."'";
			$select_email_array=$myDB->query($email_query);
			$contactN_array=array();
			$contactE_array=array();
			$ohname_array=array();
			if(isset($select_email_array[0]['account_head']))
			{
				$ah_id=$select_email_array[0]['account_head'];
				$oh_id=$select_email_array[0]['oh'];
				$vh_id=$select_email_array[0]['VH'];
				if($ah_id!=""){
					$ah_contact=explode(':',$select_email_array[0]['ah_contact']);
					$contactE_array[0]=$ah_contact[1];
					$contactN_array[0]=$ah_contact[0];
					$name_array[0]=$select_email_array[0]['ahname'];
				}
				if($oh_id!=""){
					$oh_contact=explode(':',$select_email_array[0]['oh_contact']);
					$contactE_array[1]=$oh_contact[1];
					$contactN_array[1]=$oh_contact[0];
					$name_array[1]=$select_email_array[0]['ohname'];
				}
				
				if($vh_id!=""){
					$Vh_contact=explode(':',$select_email_array[0]['vh_contact']);
					$contactE_array[2]=$Vh_contact[1];
					$contactN_array[2]=$Vh_contact[0];
					$name_array[2]=$select_email_array[0]['vhname'];
				}
				
			}
			
			$contactE_array= array_unique($contactE_array);
			$contactN_array= array_unique($contactN_array);
			for($i=0;$i<count($contactE_array);$i++)
			{	$email_address='';	
				if(isset($contactE_array[$i]))	{
					$EmployeeName=$name_array[$i];
					$email_address=$contactE_array[$i];	
				}	  
				if($email_address!=""){
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = EMAIL_HOST; 
					$mail->SMTPAuth = EMAIL_AUTH;
					$mail->Username = EMAIL_USER;   
					$mail->Password = EMAIL_PASS;                        
					$mail->SMTPSecure = EMAIL_SMTPSecure;
					$mail->Port = EMAIL_PORT; 
					$mail->setFrom(EMAIL_FROM, EMAIL_FROMWhere);
					$mail->AddAddress($email_address);
					$mail->addCC("pankaj.soin@cogenteservices.com");
					//$mail->AddAddress("rinku.kumari@cogenteservices.in");
					$mail->Subject = 'EMS contract checklist mailer';
					$mail->isHTML(true);
					$mysqlError = $myDB->getLastError();
					$pwd_='<style>table {    border-collapse: collapse;}table, td, th {border: 1px solid black;padding:5px;text-align: center;}table th{border-bottom: 2px solid black;}</style><span>Dear '.$EmployeeName.$email_address.',<br/><br/><span> Operational parameters related to Contract '.$processName.' process has been uploaded in EMS. Kindly acknowledge the same by login your EMS Portal and click <b>CONTRACT</b> link in EMS. For further details, feel free to contact.</span><br /><br/><div style="float:left;width:100%;"></div><div style="float:left;width:100%;"><br />'.'Regards,<br /> Pankaj Soin<br />M: +91 '.$pankj_mobile.'.<div>';
					$mail->Body = $pwd_;
					$mymsg = '';
					if(!$mail->send())
				 	{
				 		settimestamp('create_contract_'.$tablename,'Email Not Sent');
				 		 $emsg= ' and Mailer Error:'. $mail->ErrorInfo;
				  	} 
					else
					 {
					    settimestamp('create_contract_'.$tablename,'Email Sent');
					    $emsg= ' and Mail Send successfully.';
					 }
				} 
		}
		for($j=0;$j<count($contactN_array);$j++)
		{
			
			
			if(isset($contactN_array[$j]))
			{
				$mobile=$contactN_array[$j];
				$EmployeeName=$name_array[$j];
					//$msg ="Dear ".$EmployeeName.", Operational parameters related to Contract ".$processName." process has been uploaded in EMS. Kindly acknowledge the same by login your EMS Portal and click CONTRACT link in EMS. For further details, feel free to contact. Thanks Pankaj Soin ".$pankj_mobile;
					$msg ="Dear ".$EmployeeName.", Operational parameters related to Contract ".$processName." process has been uploaded in EMS. Kindly acknowledge the same by login your EMS Portal and click CONTRACT link in EMS. For further details, feel free to contact. Thanks Pankaj Soin ".$pankj_mobile." - Cogent E Services";
					$url = SMS_URL;
					$token = SMS_TOKEN;
					$credit = SMS_CREDIT;
					$sender = SMS_SENDER;
					$message = $msg;
					$number = $mobile;
					$TEMPLATEID='1707161752154901156';
					//$number = '7835857351';
					$sendsms = new sendsms($url,$token);
					$message_id = $sendsms->sendmessage($credit,$sender,$message,$number,$TEMPLATEID);
					$response = $message_id;
					echo "<script>$(function(){ toastr.success('message send Successfully ".$emsg."') });</script>";	
				}
				else
				{
					echo "<script>$(function(){ toastr.error('message Not send ".$mysql_error."') });</script>";
				}
			}
		}
			}else{
				echo "<script>$(function(){ toastr.error('Data Already Addedd ') });</script>";
				 
			}
}	

?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" >

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Create Contract</span>
	
<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main"> 	
<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Create Contract</h4>

<!-- Form container if any -->
<div class="schema-form-section row" >

 
	 <?php
	$sqlConnect = "Select parameters,Alias,ddType,id from ctrctpram where Status='1' "; 
	$myDB=new MysqliDb();
	$resultpara=$myDB->query($sqlConnect);
	$para_array='';
 if(count($resultpara)>0)
{
	foreach($resultpara as $val)
		{

			$para_array.='<option value="'.$val['Alias'].'" data="'.$val['ddType'].'" id="'.$val['id'].'"">'. $val['parameters'].'</option>';
		} 
	}	
 $process_Array= "call inc_Process()";
 $myDB=new MysqliDb();	
 $resultp=$myDB->query($process_Array);
 $process_option='';
 foreach($resultp as $val)
	{ 
		 $process_option.='<option  value="'.$val['cm_id'].'">'.$val['ProcessInfo'].'</option>';
	} 
  ?>
  <input type='hidden' name="tpara" id="tpara" value="<?php echo count($resultpara); ?>"> 
  <input type='hidden' name="processName" id="processName" value=""> 
  <div id="addmoreid">
   <div class="input-field col s12 m12">
		<select class="cm_id_class" name="cm_id" id="cm_id1"  required >
			<option value=''>Select Process</option>
			<?php echo  $process_option; ?>
		</select>
	    <label for="cm_id"  class="active-drop-down active">Process</label>
	</div> 
<div id="dclass1" class=" input-field col s12 m12 dclass" style="margin:0px;padding-left: 0px;">
	 <div class="input-field col s4 m4 ">
	    <select class="paraclass" id="txt_para1" name="txt_para[]" data="bb" required onchange="getType('1');">
			<option value="">Select</option>
			<?php echo $para_array; ?>
		</select>
		<label for="txt_para" class="active-drop-down active">Parameter</label>
	</div>
	<!--
	<div class="input-field col s4 m4 ">
		<input type="text" class="pval_class" value="" maxlength='100'  id="paravalue1" name="paravalue[]" required />
		<label for="paravalue">Para Value</label>
	</div>-->
	<div class="input-field col s4 m4 " id="txt_paradiv1">
	
	</div> 
	<div class="input-field col s4 m4 " id="ccdiv1" style="display:none;"></div>
	<input name="type_array[]" type="hidden"  id="type_array1" >	
	<!--<div class="input-field col s4 m4 " id="removeid1" style="display:none;" >
			<button type="button" name="btnChildcan" id="btnChildcan" data="" title="Remove  Row"  class="btn waves-effect modal-action modal-close waves-red close-btn" ><i class="fa fa-minus"></i> Remove</button>
	</div>-->
 </div>
	
		
</div>
<div class="form-inline">
		<div class="form-group">
			<button type="button" name="btn_childAdd" id="btn_childAdd" title="Add Row" class="btn waves-effect waves-green" ><i class="fa fa-plus"></i> Add</button>
			
			
		</div>
</div>
	
				<!--trail comment-->
<div class="input-field col s12 m12 right-align">
<!--	<button type="submit" title="Update Details" name="btn_contact_Save1" id="btn_contact_Save1" class="btn waves-effect waves-green">Create</button>-->
	<input type="submit" title="Update Details" name="btn_contact_Save1" id="btn_contact_Save1" class="btn waves-effect waves-green" Value="Create">
</div>
</div>
<script>
	$(document).ready(function() {
		$('#cm_id1').change(function(){
			var tval=$('#cm_id1 option:selected').text();
			$('#processName').val(tval);
		})
		//$('.ddvaldatac').datetimepicker({ format:'Y-m-d', timepicker:false});
		$('#btn_contact_Save1').on('click', function(){
			var arr = [];
			var ddv=0;
			$(".paraclass").each(function(){
				//var value1=$("this option:selected").val();
				//alert(value1);
			   var value1 =this.value;
			    if (arr.indexOf(value1) == -1)
			        arr.push(value1);
			    else{
					$(".paraclass").addClass("duplicate1");
					ddv='1';
					return false;
					
				}
			        
			        
			});
			if(ddv=='1'){
				//alert('Duplicate parameter are not allowed');
				$(function(){ toastr.error('Duplicate parameter are not allowed') });
				return false;
			}
		
			if( $("#cm_id1 option:selected").val()==''){
		  	 	alert('Please select process');
		   		return false;
			}
	    var paraf=0;
		 
	        $('.paraclass').each(function(){
	        	if(this.value==""){
						paraf='1';
						return false;
					}
	        });
	       
	        /* var parav=0;
	        $('.pval_class').each(function(){
	        	if(this.value==""){
						parav='1';
						return false;
					}
	        }); */

	        var parad=0;
	        $('.ddclass').each(function(){
	        	if(this.value==""){
						parad='1';
						return false;
					}
	        });
	         
	        if(paraf=='1' ||  parac=='1' || parad=='1' ){
				alert('Please fill all value')
				return false;
			}
			
	        //$('#indexForm').submit();
	       // return true;
	        return false;
		});
	
	 	var i=1; 	
	 	var tpara=$('#tpara').val(); 
	  $('#btn_childAdd').click(function(){  
	 total = $('.dclass').length;
	  i++;     
	  	var row=''; 	
	  	    row='<div class="input-field col s12 m12 dclass" id="dclass'+i+'" style="margin:-4px;padding-left:0px; " ><div class="input-field col s4 m4"><select class="paraclass" id="txt_para'+i+'" name="txt_para[]" required  onchange="getType('+i+');"><option value="">Select</option><?php echo $para_array; ?></select><label for="txt_para" class="active-drop-down active">Parameter</label></div><div class="input-field col s4 m4 " id="txt_paradiv'+i+'"></div><div class="input-field col s4 m4 " id="ccdiv'+i+'" style="display:none;"></div><input name="type_array[]" type="hidden"  id="type_array'+i+'"><div class="input-field col s4 m4 " id="removeid'+i+'" ><button type="button"   title="Remove  Row" style="position: relative;top: 10px !important;"  class="btn waves-effect modal-action modal-close waves-red close-btn tt"  onclick="removeRow('+i+')">Remove</button></div></div> ';
	if(total<tpara  ){
		 $('#addmoreid').append(row);
          $('select').formSelect();
	}
         
   	
	});


	});
function getType(id)

{
	
	var j=(parseInt(id)-1);
	var ddid="#txt_para"+id;
var dd= $(ddid+" option:selected").attr('data');
 var parID=$(ddid+" option:selected").attr('id');
 if(dd=='File'){
 	$("#type_array"+id+"").val('File');
 	$("#txt_paradiv"+id+"").show();
	$("#ccdiv"+id+"").hide();
 	var ddrow='<input type="file" class="ddclass" style="position: relative;top: 15px;border: 1px solid gray;padding: 3px;" id="ddvaldata'+id+'" name="ddvaldata['+j+']" required><label for="ddvaldata"> </label>';
 	$("#txt_paradiv"+id+"").html(ddrow);
}else
if(dd=='Txt'){ 	
	$("#type_array"+id+"").val('Txt');
	$("#txt_paradiv"+id+"").show();
	$("#ccdiv"+id+"").hide();
	var ddrow='<input type="text" class="ddclass" id="ddvaldata'+id+'" name="ddvaldata['+j+']" required><label for="ddvaldata">Text</label>';
 	$("#txt_paradiv"+id+"").html(ddrow);
}else
if(dd=='Calender'){
 	$("#type_array"+id+"").val('Calender');
 	$("#ccdiv"+id+"").show();
 	$("#txt_paradiv"+id+"").hide();
 	//$("#txt_paradiv"+id+"").show();
 	
 	var ddrow='<input type="text" class="ddvaldatac ddclass" name="ddvaldata['+j+']" required ><label for="ddvaldata">Calender</label>';
 	$("#ccdiv"+id+"").html(ddrow);	
}
else if(dd=='DropDown'){
	$("#type_array"+id+"").val('DropDown');
	$("#txt_paradiv"+id+"").show();
	$("#ccdiv"+id+"").hide();
		$.ajax({
			url:"../Controller/getSelectOptionValue.php?pid="+parID,			
			success:function(data) {
				var ddrow='<select class="ddclass" id="ddvaldatass'+id+'" name="ddvaldata['+j+']" required><option>select </option>'+data+'</select><label for="ddvaldata"  class="active-drop-down active" >Drop Down</label>';						
				$("#txt_paradiv"+id+"").html(ddrow);	
			}
		});
	}
// alert(dd);
$('.ddvaldatac').datetimepicker({ format:'Y-m-d', timepicker:false});
$('select').formSelect();
}
function removeRow(id){
	
         	
         		$("#dclass"+id+"").last().remove();
			
     
}
</script>
<style>
	.duplicate1 {
    border: 1px solid red;
    color: red;
}
</style>
  	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div> 
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>