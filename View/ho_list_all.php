<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
$emp_location='';
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
		exit();
	}
	else
	{
		$Empl=substr($_SESSION['__user_logid'],0,2);
		if($Empl=='MU'){
			$emp_location='Mumbai';
		}else{
			$emp_location='Noida';
		}
		$isPostBack = false;
		$referer = "";
		$alert_msg="";
		$thisPage = REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		if (isset($_SERVER['HTTP_REFERER'])){
		    $referer = $_SERVER['HTTP_REFERER'];
		}

		if ($referer == $thisPage){
		    $isPostBack = true;
		} 
		
		if($isPostBack && isset($_POST['txt_dept']))
		{
			
			$dept =$_POST['txt_dept'];
			
		}
		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
	exit();
}


?>

<script>
	$(function(){
		
		$('#myTable').DataTable({
				        dom: 'Bfrtip',				        
				         buttons: [
						          
						        {
						            extend: 'csv',
						            text: 'CSV',
						            extension: '.csv',
						            exportOptions: {
						                modifier: {
						                    page: 'all'
						                }
						            },
						            title: 'table'
						        }
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"sScrollY" : "192",
							"bScrollCollapse" : true,
							"bLengthChange" : false
							
				       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
			});
			$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
	});
</script>



<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Holiday List</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
<h4>Holiday List</h4>				
<style>
	.card-body
	{
		padding: 15px;
	}
	.card-body p 
	{
		text-align: center;
	}
	.card-content {

    	border-bottom: 1px solid #f0f0f0;
    }
</style>
<!-- Form container if any -->
<div class="schema-form-section row" >


		<?php 
		$table='<div class="col s12 m12">';
		
		$mydb = new MysqliDb();
		$data_ho = $mydb->query("SELECT DateOn,Reason as `Holiday List`,Associates,Support FROM ho_list_admin where location='".$_SESSION["__location"]."'order by DateOn;");
		if(count($data_ho) > 0 && $data_ho)
		{
			
			foreach($data_ho as $key=>$value)
			{
				$table .='<div class="col s4 m4"><div class="card"><div class="card-content ">';
				$table .='<span class="card-title">'.date('d',strtotime($value['DateOn'])).' '.date('F,Y',strtotime($value['DateOn'])).'</span></div>';
				
				$table .='<div class="card-body"><p>'.date('F,Y',strtotime($value['DateOn'])).'</p>';
				
				$table .='<p>'.$value['Holiday List'].'</p>';
				
				$table .='<p style="font-size: 10px;font-weight: bold;"><span>Associates :</span> '.$value['Associates'].' | <span>Support :</span> '.$value['Support'].'</p>';
				$table .='</div></div></div>';
			}
			
		}
		$table .='</div>';
		echo $table;
		
		?>
		

	  <div class="col s12 m12">
			<p><strong><span style="font-size: 10pt; font-family: 'Verdana',sans-serif; color: #19aec4;">Note:</span></strong><span style="font-size: 10pt; font-family: 'Verdana',sans-serif; color: #19aec4;"></span></p>
<p><i class="fa fa-circle"></i>&nbsp;&nbsp;<span style="font-size: 10pt; font-family: 'Verdana',sans-serif; color: #19aec4;">Employees who works on above mention days will get mentioned benefits </span></p>
<p><i class="fa fa-circle"></i>&nbsp;&nbsp;<span style="font-size: 10pt; font-family: 'Verdana',sans-serif; color: #19aec4;">** &ldquo;HO&rdquo; needs to be uploaded in roster if process is non-operational or employee is on provided holiday </span></p>

		</div>

	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>

<script>
	$(function(){
		
		
		$('#alert_msg_close').click(function(){
			$('#alert_message').hide();
		});
		if($('#alert_msg').text()=='')
		{
			$('#alert_message').hide();
		}
		else
		{
			$('#alert_message').delay(10000).fadeOut("slow");
		}
	});
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>