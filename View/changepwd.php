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
$Request_Emp='';
$_Description=$_Name=$alert_msg='';
if(isset($_POST['btn_change_password']))
{
	$_newpassord=(isset($_POST['txt_chg_pwd'])? $_POST['txt_chg_pwd'] : null);
	$userempid=$_SESSION['__user_logid'];
	
	$password_hash = md5($_newpassord);
	$chng_pwd='call change_pwd("'.$password_hash.'","'.$userempid.'")';
	$myDB=new MysqliDb();
    $myDB->rawQuery($chng_pwd);
	$mysql_error = $myDB->getLastError();
	if(empty($mysql_error))
	{
		echo "<script>$(function(){ toastr.success('Password Changed Successfully'); }); </script>";
		$_Description=$_Type=$_Name='';
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Data not updated ".addslashes($mysql_error)."'); }); </script>";
	}
}
?>
<script src="<?php echo SCRIPT.'pwdchk.js';?>"></script>
<script>
	$(document).ready(function(){
		$('input').blur(function(){
		    $('#txt_chg_pwd1').removeClass('has-error');
		    $('#txt_chg_pwd1').removeClass('has-success');
			if($('#txt_chg_pwd').val()===$('#txt_chg_pwd1').val())
			{
				$('#txt_chg_pwd1').addClass('has-success');
			}
			else
			{
				$('#txt_chg_pwd1').addClass('has-error');
				toastr.info("Password Not Matched");
			}
		});
	});
</script>
<style>
	.short,.weak
	{
		color:red;
	}
	.good
	{
		color:#e66b1a;
	}
	.strong
	{
		color:green;
	}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Change Password</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Change Password</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	
			<div class="input-field col s5 m5">
			    <input type="password" id="txt_chg_pwd"   name="txt_chg_pwd" placeholder="****"/>
			    <label for="txt_chg_pwd">New Password</label>
			    <span id="result"></span>
			    
			</div>
			
			<div class="input-field col s5 m5">
			  <input type="password" id="txt_chg_pwd1"   name="txt_chg_pwd1" placeholder="****"/>	
			   <label for="txt_chg_pwd1">Confirm Password</label>
			</div>

			<div class="input-field col s2 m2">
			   <button type="submit" name="btn_change_password" id="btn_change_password" class="btn waves-effect waves-green">Change Password</button>
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
		        $('#txt_chg_pwd').removeClass('has-error');
		        $('#txt_chg_pwd').removeClass('has-error');
		        if($('#txt_chg_pwd').val()==''||!($('#result').hasClass('strong')||$('#result').hasClass('good')))
		        {
					$('#txt_chg_pwd').addClass('has-error');
					validate=1;
					toastr.error("Password Should not be empty or Too Short");
				}
				if(!($('#txt_chg_pwd').val()===$('#txt_chg_pwd1').val()))
				{
					return false;
				}
		      	if(validate==1)
		      	{		      		
		      		return false;
				}
		       
		    });
	});
	
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
