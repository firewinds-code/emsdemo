<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
//Interview DB main Config / class file     $myDB=new MysqliDb($db_int_config_i);
require_once(__dir__.'/../Config/DBConfig_interview_array.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form
require(ROOT_PATH.'AppCode/nHead.php');

$filename='';

if (isset($_POST['get-report'])  && $_POST['txt_date']!="" ) {
	$filename=$_POST['txt_date'].'.csv';
}
?>			


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" >

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Download Hybrid Report</span>
	
<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main"> 
	
<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Download Hybrid Report</h4>
<!-- Form container if any -->
<div class="schema-form-section row" >
<div class="input-field col s6 m6">
	<input type="text"  value="" id="txt_date" name="txt_date" required/>
	<label for="txt_date">Select Date *</label>
</div>

<div class="input-field col s6 m6 right-align">
		<button type="button" title="get-report" name="get-report" id="get-report" class="btn waves-effect waves-green">Get Report</button>
</div>

<script>
	$(document).ready(function() {
		
		$('#txt_date').datetimepicker({
			timepicker:false,
			format:'Y-m-d'
		});
		
		$('#get-report').click(function(){
			
			var f1= $('#txt_date').val();
			if(f1!=""){
				var filenname=f1+".csv";
			
				$.ajax({
						    url:"<?php echo URL; ?>Export_Report/hybrid_report/"+filenname,
						    type:'HEAD',
						    error: function()
						    {
						        alert('No File Exist');
						    },
						    success: function()
						    {
								location.href="<?php echo URL; ?>Export_Report/hybrid_report/"+filenname;
								
							}
		         
		        	 });	
			}else{
				alert('Please select date for Report Download');
				$('#txt_date').focus();
			}
           
        });
			
	});

</script>
  	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div> 
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>