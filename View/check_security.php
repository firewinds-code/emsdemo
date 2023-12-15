<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}

// Global variable used in Page Cycle
$_Description=$_Name=$alert_msg=$Request_Emp=$phpqusn=$phpans='';

// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_change_password']))
{
	$quesn=trim($_POST['txt_chg_qusn']);
	$anssn=trim($_POST['txt_chg_ans']);
	$empid=$_SESSION['__user_logid'];
	
	$chng_sec='call change_sec("'.$quesn.'","'.$anssn.'","'.$empid.'")';
	$myDB=new MysqliDb();
	
	$myDB->rawQuery($chng_sec);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{		
		echo "<script>$(function(){ toastr.success('Security Key Changed Successfully'); }); </script>";
		$_Description=$_Type=$_Name='';
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('Data not updated ".addslashes($mysql_error)."'); }); </script>";
	}
	
}


$myDB=new MysqliDb();
$result=$myDB->query('call get_ques("'.$_SESSION['__user_logid'].'")');
if($result)
{
	foreach($result as $key=>$value)
	{
		$phpqusn=$value['secques'];
		$phpans=$value['secans'];
	}
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Change Security Question</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Change Security Question</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	

							    <div class="input-field col s5 m5">
								    <input type="text" id="txt_chg_qusn" name="txt_chg_qusn"  value="<?php echo $phpqusn;?>"></input>
								    <label for="txt_chg_qusn">Question</label> 
							    </div>
							    <div class="input-field col s5 m5">
						            <input type="text" id="txt_chg_ans" name="txt_chg_ans" value="<?php echo $phpans;?>"></input>
						         	<label for="txt_chg_ans">Answer</label>
							    </div>
							    
							    <div class="input-field col s2 m2">
						  	        <button type="submit" name="btn_change_password" id="btn_change_password" class="btn waves-effect waves-green">Change</button>
						  	    </div>    
				
		</div>
	</div>    
  </div>
</div>

<script>
	$(document).ready(function(){
		$('#btn_change_password').on('click', function(){
		    var validate=0;
		    var alert_msg='';
		    $('#txt_chg_qusn').removeClass('has-error');
		    $('#txt_chg_ans').removeClass('has-error');
		    if($('#txt_chg_qusn').val()=='')
		    {
				$('#txt_chg_qusn').addClass('has-error');
				validate=1;
			}
			if($('#txt_chg_ans').val()=='')
			{
				$('#txt_chg_ans').addClass('has-error');
				validate=1;
			}
		  	if(validate==1)
		  	{		      		
		  		return false;
			}
		   
		});
	});
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>