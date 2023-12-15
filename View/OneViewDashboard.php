<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
require(ROOT_PATH.'AppCode/nHead.php');

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">DashBoard</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>DashBoard</h4>	
	 <!-- Form container if any -->
	<div class="schema-form-section row" >
	

	<?php 
	  include('chart_dashboard_highchart.php');
	?>
	
	
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
