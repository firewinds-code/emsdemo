<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
?>
<link href="../FileContainer/crosscover-1.0.2/dist/css/crosscover.min.css" rel="stylesheet" />
<script src="../FileContainer/crosscover-1.0.2/dist/js/crosscover.min.js" charset="utf-8"></script>
<style>
	div#the_message_blist {

    position: fixed;
    top: 45%;
    left: 20%;
    right: 20%;
    background-color: #000;
    background: #000c;
    border: 1px solid #a6a6a6;
    padding: 10px;    
    color: #ffffff;
    text-align: left;
    

}
</style>
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
	     $("#scroll_div1").scroll(function(){
	     	$("#scroll_div1").removeClass('hidden');
	     });
	     $('#main').scroll(function () {
		    var y = $(this).scrollTop();	
		    if (y > 80) {
		    	
		        $('#scroll_div1').removeClass('hidden');
		    } else {
		        $('#scroll_div1').addClass('hidden');
		    }

		});
		$('#the_message_blist').removeClass('hidden').addClass('fadeInright animated');
		 //$('#the_message_blist').delay(10000).fadeOut();
    });
	
	</script>
	
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Birth Day List</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Birth Day List</h4>				

<!-- Form container if any -->
<div class="schema-form-section row">
		
	
	<div class="col s12 m12">
		<?php
			$sql_blist='call get_birthday_list()';
			$myDB=new MysqliDb();
			$result_blist=$myDB->query($sql_blist);
			$table = '';
			if($result_blist){
				$count=0;
				foreach($result_blist as $key=>$value){
						    $img = '<img alt="user" style="margin: 0px;padding-top: 0px;height: 150px;width: 150px;" src="';
							if(file_exists ("../Images/".$value['img']) && $value['img']!='')
							{
								$img .= "../Images/".$value['img'];
							}
							else
							{
								$img .= "../Style/images/agent-icon.png";
							}
							$img .='"/>';
							$table .='<div class="col s12 m12" style="box-shadow: none;border: 1px solid #f5f5f5;padding: 10px;margin-bottom: 10px;">';
							$table .='<div class="col s3 m3">'.$img.'</div>';
							$table .='<div class="col s9 m9" style="font-weight: bold;height: 100%;margin: 3% 0px;"><span style="width:100px;float:left;font-weight: normal;">Employee ID  &nbsp; &nbsp; </span>:<span >&nbsp;&nbsp;&nbsp;&nbsp;'.$value['EmployeeID'].'</span></br>';				
							$table .='<span style="width:100px;float:left;font-weight: normal">Name  </span>:&nbsp;&nbsp;&nbsp;&nbsp;'.$value['EmployeeName'].'<br />';
							
							$table .='<span style="width:100px;float:left;font-weight: normal">Process  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;'.$value['process'].'</br>';
							$table .='<span style="width:100px;float:left;font-weight: normal">Sub Process  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;'.$value['sub_process'].'</br>';
							$table .='<span style="width:100px;float:left;font-weight: normal">Client  &nbsp; &nbsp; </span>:&nbsp;&nbsp;&nbsp;&nbsp;'.$value['client_name'].'</br>';				
							
							$table .= '</div>';
							$table .='</div>';
							
						}
						echo $table;	
							
				}
			?>
						
	</div>
	<!--<div id="myModal_content" class="modal">
    <div class="modal-content">
      <h4>Birthday Wishes</h4>
      <p>Wishing you all the great things in life, hope this day will bring you an extra share of all that makes you happiest. Happy Birthday to ALL.</p>
    </div>
    <div class="modal-footer">
	<a href="#!" class="btn waves-effect modal-action modal-close waves-red close-btn">Cogent EMS</a>      
    </div>
  </div>-->
	
	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>
<script>
			$(function(){
				$('button').click(function(event ){			
					event.preventDefault();
					//$(document).on("keydown", disableF5);
				});
				/*$('.modal').modal(
				{
					onOpenStart:function(elm)
					{
						
					},
					onCloseEnd:function(elm)
					{
						
					}
				});
				$('#myModal_content').modal('open');
				setTimeout(function(){$('#myModal_content').modal('close');},10000);*/
			});
			
			
</script>	
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>

