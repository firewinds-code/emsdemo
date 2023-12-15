<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
$label='';
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
	else
	{
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
?>

<link href="../FileContainer/crosscover-1.0.2/dist/css/crosscover.min.css" rel="stylesheet">
<script src="../FileContainer/crosscover-1.0.2/dist/js/crosscover.min.js" charset="utf-8"></script>


<script>

	$(function(){
		$('.crosscover').crosscover({
	      controller: false,
	      dotNav: true,
	      inClass:'lightSpeedIn',
  		  outClass:'lightSpeedOut'
  		  /*inClass:'fadeIn',
  		  outClass:'fadeOut'*/
	    });
    });
	
	</script>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Profile</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Profile</h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >


<?php 
$myDB = new MysqliDb();
$rst= $myDB->query('select edu_level,mobile,em_contact,address,sal_id,personal_details.employeeid from personal_details left outer join 
 education_details on  personal_details.employeeid= education_details.employeeid left outer join contact_details on education_details.EmployeeID = contact_details.EmployeeID left outer join address_details on education_details.EmployeeID = address_details.EmployeeID
left outer join salary_details on education_details.EmployeeID = salary_details.EmployeeID 
where personal_details.EmployeeID = "'.$_SESSION['__user_logid'].'" limit 1;');
 //print_r($rst);
    $MysqlError = $myDB->getLastError();
	if(empty($MysqlError))
		{
			echo '<table class="table table-bordered" style="width:100%;">';
			if($rst[0]['edu_level'] !='' || $rst[0]['edu_level'] != null)
			{
				echo '<tr><td>Education Details :</td><td  class="Filled"><img src="'.STYLE.'img/ok.png" style="height: 30px;width: 30px;" />Completed</td></tr>';	
			}
			else
			{
				echo '<tr><td>Education Details</td><td class="red-text">&nbsp;<img src="'.STYLE.'img/failed.png"  style="height: 22px;width: 22px;"/>&nbsp;Required</td></tr>';
			}
			
			if($rst[0]['mobile'] !='' || $rst[0]['mobile'] != null)
			{
				echo '<tr><td>Contact Details</td><td class="Filled"><img src="'.STYLE.'img/ok.png"  style="height: 30px;width: 30px;"/>Completed</td></tr>';	
			}
			else
			{
				echo '<tr><td>Contact Details</td><td  class="red-text">&nbsp;<img src="'.STYLE.'img/failed.png"  style="height: 22px;width: 22px;"/>&nbsp;Required</td></tr>';
			}
			
			if($rst[0]['address'] !='' || $rst[0]['address'] != null)
			{
				echo '<tr><td>Address Details</td><td  class="Filled">
				<img src="'.STYLE.'img/ok.png"  style="height: 30px;width: 30px;"/>Completed</td></tr>';	
			}
			else
			{
				echo '<tr><td>Address Details</td><td class="red-text">&nbsp;<img src="'.STYLE.'img/failed.png"  style="height: 22px;width: 22px;"/>&nbsp;Required</td></tr>';
			}
			/*if($rst[0]['salary_details']['sal_id'] !='' || $rst[0]['salary_details']['sal_id'] != null)
			{
				echo '<tr><td>PF/ESIC Details (Under PF/ESI Slab)</td><td  class="Filled"><img src="'.STYLE.'img/ok.png"  style="height: 30px;width: 30px;"/>Completed</td></tr>';	
			}
			else
			{
				echo '<tr><td>PF/ESIC Details (Under PF/ESI Slab)</td><td class="Required">&nbsp;<img src="'.STYLE.'img/failed.png"  style="height: 22px;width: 22px;"/>&nbsp;Required</td></tr>';
			}*/
			
			echo '</table>';
		}
	?>

</div>

<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>	
       	
	
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
<script>
	$(function(){
		$('button').click(function(event ){			
			event.preventDefault();
			//$(document).on("keydown", disableF5);
		});
		
	});
</script>