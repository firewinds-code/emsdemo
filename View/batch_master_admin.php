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
$alert_msg ='';
// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_Department_Save']))
{
	$date=(isset($_POST['txt_date'])? $_POST['txt_date'] : null);
	$count=(isset($_POST['txt_count'])? $_POST['txt_count'] : null);
	$location=(isset($_POST['txt_location'])? $_POST['txt_location'] : null);
	$cmid=(isset($_POST['txt_subProcess'])? $_POST['txt_subProcess'] : null);
	$clientid=(isset($_POST['txt_Client'])? $_POST['txt_Client'] : null);
	$clientname=(isset($_POST['hiddenclient'])? $_POST['hiddenclient'] : null);
	$process=(isset($_POST['txt_Process'])? $_POST['txt_Process'] : null);
	$subprocess=(isset($_POST['hiddensubprocess'])? $_POST['hiddensubprocess'] : null);
	
	$createBy=$_SESSION['__user_logid'];
	
	
	
	$Insert='call add_bacth_by_admin("'.$clientid.'","'.$cmid.'","'.$clientname.'","'.$process.'","'.$subprocess.'","'.$date.'","'.$count.'","'.$createBy.'","'.$location.'")';
	$myDB=new MysqliDb();
    $myDB->rawQuery($Insert);
	$mysql_error = $myDB->getLastError();
	$var = 'Batch not Created '.str_replace("'","",$mysql_error);
	if(empty($mysql_error))
	{
		
		echo "<script>$(function(){ toastr.success('Batch Create Successfully'); }); </script>";
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('.$var.'); }); </script>";
	}
			
			
	/*$sqlConnect = "select id from batch_status where cm_id='".$cmid."' and status='Open';"; 
	$myDB=new MysqliDb();
	$result=$myDB->rawQuery($sqlConnect);
	$mysql_error = $myDB->getLastError();
	//echo array_count_values($result);
	if(empty($mysql_error) && empty($result))
	{
		$cmid=(isset($_POST['txt_Process'])? $_POST['txt_Process'] : null);
		$date=(isset($_POST['txt_date'])? $_POST['txt_date'] : null);
		$count=(isset($_POST['txt_count'])? $_POST['txt_count'] : null);
		$location=(isset($_POST['txt_location'])? $_POST['txt_location'] : null);
			
		$sqlConnect = "select t2.client_id,t2.client_name,t1.process,t1.sub_process from new_client_master t1 join client_master t2 on t1.client_name=t2.client_id where t1.cm_id='".$cmid."';"; 
		$myDB=new MysqliDb();
		$result=$myDB->rawQuery($sqlConnect);
		$mysql_error = $myDB->getLastError();
		if(empty($mysql_error))
		{
			$createBy=$_SESSION['__user_logid'];
			$Insert='call add_bacth_by_admin("'.$result[0]['client_id'].'","'.$cmid.'","'.$result[0]['client_name'].'","'.$result[0]['process'].'","'.$result[0]['sub_process'].'","'.$date.'","'.$count.'","'.$createBy.'","'.$location.'")';
			$myDB=new MysqliDb();
		    $myDB->rawQuery($Insert);
			$mysql_error = $myDB->getLastError();
			if(empty($mysql_error))
			{
				
				echo "<script>$(function(){ toastr.success('Batch Create Successfully'); }); </script>";
			}
			else
			{		
				echo "<script>$(function(){ toastr.error('Batch not Created ".$mysql_error."'); }); </script>";
			}
		}
	}
	else
	{
		echo "<script>$(function(){ toastr.error('Already another batch is open for this process'); }); </script>";
	}*/
		
	
						
	
	
}
// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btn_Department_Edit']))
{	
	$DataID=$_POST['hid_Department_ID'];
	
	$_date=(isset($_POST['txt_date'])? $_POST['txt_date'] : null);
	$_count=(isset($_POST['txt_count'])? $_POST['txt_count'] : null);
	$_status=(isset($_POST['txt_status'])? $_POST['txt_status'] : null);
	
	$ModifiedBy=$_SESSION['__user_logid'];
	$Update='call update_batch_by_admin("'.$DataID.'","'.$_date.'","'.$_count.'","'.$_status.'")';
	$myDB=new MysqliDb();
	if(!empty($DataID)|| $DataID!='')
	{
		$myDB->rawQuery($Update);
		$mysql_error = $myDB->getLastError();
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Updated Successfully'); }); </script>";
			$_Comp=$_Hod=$_Name='';
			$_Hod="NA";
		}
		else
		{
			echo "<script>$(function(){ toastr.success('Batch not updated'); }); </script>";
			//echo "<script>$(function(){ toastr.error('Batch not updated ".$mysql_error."'); }); </script>";
		}
	}
	else
	{		
		echo "<script>$(function(){ toastr.error('Something is wrong Plase click to Edit Button First :: (If Not Resolved then contact to technical person)'); }); </script>";
	}	
}
?>
<script>
	$(document).ready(function(){
		
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
				        "iDisplayLength": 25,	        
				        scrollCollapse: true,
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
						        }
						        /*,'copy'*/,
						        'pageLength'
						        
						    ]
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


<script>
	
$(document).ready(function(){
	
	//Model Assigned and initiation code on document load
	$('#divloc1').removeClass('hidden');
	$('#divclient1').removeClass('hidden');
	$('#divproc1').removeClass('hidden');
	$('#divsubproc1').removeClass('hidden');
	
	$('#divloc2').addClass('hidden');
	$('#divclient2').addClass('hidden');
	$('#divproc2').addClass('hidden');
	$('#divsubproc2').addClass('hidden');
	$('#divstatus').addClass('hidden');
	
	$('.modal').modal({
			onOpenStart:function(elm)
			{
				
				
			},
			onCloseEnd:function(elm)
			{
				$('#btn_Department_Can').trigger("click");
			}
		});
	// This code for cancel button trigger click and also for model close
     $('#btn_Department_Can').on('click', function(){
     	//alert($('#txt_location').val());
        $('#txt_location').val('NA');
        $('#txt_Process').children().remove();
        $('#txt_date').val('');
        $('#txt_count').val('');
        $('#btn_Department_Save').removeClass('hidden');
        $('#btn_Department_Edit').addClass('hidden');
        
        $('#divloc1').removeClass('hidden');
        $('#divclient1').removeClass('hidden');
		$('#divproc1').removeClass('hidden');
		$('#divsubproc1').removeClass('hidden');
		
		$('#divloc2').addClass('hidden');
		$('#divclient2').addClass('hidden');
		$('#divproc2').addClass('hidden');
		$('#divsubproc2').addClass('hidden');
		
		$('#divstatus').addClass('hidden');
		
		$('#txt_locationhidden').val('');
		$('#txt_clienthidden').val('');
        $('#txt_processhidden').val('');
        $('#txt_subprocesshidden').val('');
        $('#txt_status').empty();
        //$('#btn_Department_Can').addClass('hidden');
        
        // This code for remove error span from input text on model close and cancel
        
    });
    
    $('#txt_location').change(function(){
    	
    	$('#txt_Process').empty();
    	$('#txt_subProcess').empty();
    	var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Client').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $(this).val()+"&type=client", true);
			xmlhttp.send();
    });
    
    $('#txt_Client').change(function(){
    	$('#txt_subProcess').empty();
    	$('#hiddenclient').val($("#txt_Client option:selected").text());
    	
    	
    	var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_Process').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $('#txt_location').val()+"&client="+ $(this).val()+"&type=Process", true);
			xmlhttp.send();
    });
    
    $('#txt_Process').change(function(){
    	var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					//$('#txt_Client_ach').html(Resp);
					//$('#txt_vertical_head').html(Resp);
					/*$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);*/
					$('#txt_subProcess').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			//var location = <?php echo $_SESSION["__location"] ?>;
			//alert(el);
			//$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getalignmentforbatchmaster.php?loc=" + $('#txt_location').val()+"&client="+ $('#txt_Client').val()+"&process="+ $(this).val()+"&type=SubProcess", true);
			xmlhttp.send();
    });
    
    $('#txt_subProcess').change(function(){
    	$('#hiddensubprocess').val($("#txt_subProcess option:selected").text());
    });
    
    
    // This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
	$('#btn_Department_Edit').on('click', function(){
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
	        
	        if($('#txt_count').val()=='')
	        {
									
				$('#txt_count').addClass("has-error");
	        	if($('#spantxt_count').size() == 0)
				{
		            $('<span id="spantxt_count" class="help-block">*</span>').insertAfter('#txt_count');
		        }
		        validate=1;
			}
		
				
	        		    
	      	if(validate==1)
	      	{		      		
	      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(50000).fadeOut("slow");
				return false;
			} 
	});
    
     $('#btn_Department_Save').on('click', function(){
	       
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
	       if($('#txt_location').val()=='NA')
	        {
									
				validate=1;
				$('#txt_location').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_location').size() == 0)
				{
		            $('<span id="spantxt_location" class="help-block">*</span>').insertAfter('#txt_location');
		        }
			}
			
			if($('#txt_Client').val()=='NA')
	        {
									
				validate=1;
				$('#txt_Client').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_Client').size() == 0)
				{
		            $('<span id="spantxt_Client" class="help-block">*</span>').insertAfter('#txt_Client');
		        }
			}
			
			if($('#txt_Process').val()=='NA')
	        {
									
				validate=1;
				$('#txt_Process').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_Process').size() == 0)
				{
		            $('<span id="spantxt_Process" class="help-block">*</span>').insertAfter('#txt_Process');
		        }
			}
			
			if($('#txt_subProcess').val()=='NA')
	        {
									
				validate=1;
				$('#txt_subProcess').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
	        	if($('#spantxt_subProcess').size() == 0)
				{
		            $('<span id="spantxt_subProcess" class="help-block">*</span>').insertAfter('#txt_subProcess');
		        }
			}
		
			if($('#txt_date').val()=='')
	        {
									
				$('#txt_date').addClass("has-error");
	        	if($('#spantxt_date').size() == 0)
				{
		            $('<span id="spantxt_date" class="help-block">*</span>').insertAfter('#txt_date');
		        }
		        validate=1;
			}
		
			if($('#txt_count').val()=='')
	        {
									
				$('#txt_count').addClass("has-error");
	        	if($('#spantxt_count').size() == 0)
				{
		            $('<span id="spantxt_count" class="help-block">*</span>').insertAfter('#txt_count');
		        }
		        validate=1;
			}
		
		if(validate==1)
      	{		      		
      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
      		$('#alert_message').show().attr("class","SlideInRight animated");
      		$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		} 		
				
	});  
    // This code for remove error span from input text on model close and cancel
    $(".has-error").each(function(){
		if($(this).hasClass("has-error"))
		{
			$(this).removeClass("has-error");
			$(this).next("span.help-block").remove();
			if($(this).is('select'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
			if($(this).hasClass('select-dropdown'))
			{
				$(this).parent('.select-wrapper').find("span.help-block").remove();
			}
			
		}
	});
    	// This code for remove error span from all element contain .has-error class on listed events
	
    $('#txt_date').datetimepicker({timepicker:false,format:'Y-m-d',minDate:'+1970/01/01', scrollInput : false});
    
    $('#txt_count').keydown(function(event) 
    {
		    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 

		        // Allow: Ctrl+A
		    (event.keyCode == 65 && event.ctrlKey === true) ||

		        // Allow: Ctrl+V
		    (event.ctrlKey == true && (event.which == '118' || event.which == '86')) ||

		        // Allow: Ctrl+c
		    (event.ctrlKey == true && (event.which == '99' || event.which == '67')) ||

		        // Allow: Ctrl+x
		    (event.ctrlKey == true && (event.which == '120' || event.which == '88')) ||

		        // Allow: home, end, left, right
		    (event.keyCode >= 35 && event.keyCode <= 39)) {
		    // let it happen, don't do anything
		        return;
		    }
		    else {
		    // Ensure that it is a number and stop the keypress
		        if ( event.shiftKey|| (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ) ){
		        event.preventDefault(); 
		        }
	    }
		    });
});


// This code for trigger edit on all data table also trigger model open on a Model ID
   	
	function EditData(el)
	{
		var tr = $(el).closest('tr');
        var id = tr.find('.id').text();
        var loc = tr.find('.loc').text();
        var location = tr.find('.location').text();
        var process = tr.find('.process').text();
        var batch_no = tr.find('.batch_no').text();
        var target_date = tr.find('.target_date').text();
        var target_count = tr.find('.target_count').text();
        var status = tr.find('.status').text();
        var array = process.split('|');
        
        $('#divloc1').addClass('hidden');
        $('#divclient1').addClass('hidden');
        $('#divproc1').addClass('hidden');
        $('#divsubproc1').addClass('hidden');
        
        $('#divloc2').removeClass('hidden');
        $('#divclient2').removeClass('hidden');
        $('#divproc2').removeClass('hidden');
        $('#divsubproc2').removeClass('hidden');
        
        $('#divstatus').removeClass('hidden');
        
       // alert(array[0]);
        $('#hid_Department_ID').val(id);
        
        $('#txt_locationhidden').val(location);	
        $('#txt_clienthidden').val(array[0]);	
        	       
        $('#txt_processhidden').val(array[1]);	
        $('#txt_subprocesshidden').val(array[2]);	
        
        $('#txt_status').empty();
        if(status =='Open')
        {
        	$("#txt_status").append(new Option("Open", "Open"));
			$("#txt_status").append(new Option("Assign to TH", "Assign to TH"));
			$("#txt_status").append(new Option("Close", "Close"));
		}
		else if(status =='Assign to TH')
        {
        	$("#txt_status").append(new Option("Assign to TH", "Assign to TH"));
			$("#txt_status").append(new Option("Close", "Close"));
		}
        
        
        $('#txt_status').val(status);	
        	//alert($('#txt_status').val());       
        $('#txt_date').val(target_date);		       
        $('#txt_count').val(target_count);
        		       
        $('#btn_Department_Save').addClass('hidden');
        $('#btn_Department_Edit').removeClass('hidden');
        
        
        
        
        
        //$('#btn_Department_Can').removeClass('hidden');
        $('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element)
        {
        	
	         if($(element).val().length > 0)
	         {
	           $(this).siblings('label, i').addClass('active');
	         }
	         else
	         {
			 	$(this).siblings('label, i').removeClass('active');
			 }
			        
		});
		
		$('select').formSelect();
	}

// This code for trigger del*t*
	
	function ApplicationDataDelete(el)
	{
		var currentUrl = window.location.href;
		var Cnfm=confirm("Do You Want To Delete This ");
		if(Cnfm)
		{
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					alert(Resp);
					window.location.href = currentUrl;
					
				
			
				}
			}
		
			xmlhttp.open("GET", "../Controller/DeleteDepartment.php?ID=" + el.id, true);
			xmlhttp.send();
		}
	}

	
	function getProcess(el)
	{
		
		var currentUrl = window.location.href;
		
			var xmlhttp;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
			   
			
					var Resp=xmlhttp.responseText;
					$('#txt_Process').html(Resp);
					$('#txt_vertical_head').html(Resp);
					$('#txt_Client_oh').html(Resp);
					$('#txt_Client_qh').html(Resp);
					$('#txt_Client_th').html(Resp);
					$('select').formSelect();
				}
				
			}
			
			var location = <?php echo $_SESSION["__location"] ?>;
			alert(el);
			$("#txt_location option:contains(" + el + ")").attr('selected', 'selected');
			xmlhttp.open("GET", "../Controller/getprocessByLocation.php?loc=" + $('#txt_location').val(), true);
			xmlhttp.send();
			
		   
	}
	
	
</script>

<div id="content" class="content" >
<span id="PageTittle_span" class="hidden">Admin Batch Master</span>

	<div class="pim-container row" id="div_main" >
		<div class="form-div">
		<input type="hidden" id="hiddenclient" name="hiddenclient" />
		<input type="hidden" id="hiddensubprocess" name="hiddensubprocess" />
		
			 <h4>Admin Batch Master <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Create New Batch"><i class="material-icons">add</i></a></h4>				
			 <div class="schema-form-section row" >
			    <div id="myModal_content" class="modal" style="width: 70% !important">
		  
				    <!-- Modal content-->
				    <div class="modal-content">
				      <h4 class="col s12 m12 model-h4">Admin Batch Master</h4>
				      
				      <div class="modal-body">
				        <div class="input-field col s4 m4" id="divloc1">
				            <select id="txt_location" name="txt_location">
				            	<option value="NA">----Select----</option>	
						      	<?php		
								$sqlBy ='select id,location from location_master;'; 
								$myDB=new MysqliDb();
								$resultBy=$myDB->rawQuery($sqlBy);
								$mysql_error = $myDB->getLastError();
								if(empty($mysql_error)){													
									foreach($resultBy as $key=>$value)
									{						
										echo '<option value="'.$value['id'].'"  >'.$value['location'].'</option>';
									}
								}			
						      	?>
				            </select>
				            
		            		<label for="txt_location" class="active-drop-down active">Location *</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4" id="divloc2">
			   		 		<input type="text"  id="txt_locationhidden" name="txt_locationhidden" readonly="true"/>
		            		<label for="txt_locationhidden">Location</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4" id="divclient1">
				            <select id="txt_Client" name="txt_Client" >
								
							</select>
							
							<label for="txt_Client" class="active-drop-down active">Client *</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4" id="divclient2">
			   		 	
				            <input type="text"  id="txt_clienthidden" name="txt_clienthidden" readonly="true"/>
		            		<label for="txt_clienthidden">Client</label>
		            		
			   		 	</div>
			   		 				   		 	
			   		 	<div class="input-field col s4 m4" id="divproc1">
				            <select id="txt_Process" name="txt_Process" >
								
							</select>
							
							<label for="txt_Process" class="active-drop-down active">Process *</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4" id="divproc2">
			   		 		<input type="text"  id="txt_processhidden" name="txt_processhidden" readonly="true"/>
		            		<label for="txt_processhidden">Process</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4" id="divsubproc1">
				            <select id="txt_subProcess" name="txt_subProcess" >
								
							</select>
							
							<label for="txt_subProcess" class="active-drop-down active">Sub Process *</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4" id="divsubproc2">
			   		 		<input type="text"  id="txt_subprocesshidden" name="txt_subprocesshidden" readonly="true"/>
		            		<label for="txt_subprocesshidden">Sub Process</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4">
				            <input type="text"  id="txt_date" name="txt_date" readonly="true" required/>
							<label for="txt_date">Target Date *</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4">
				            <input type="text"  id="txt_count" name="txt_count" maxlength="3"/>
							<label for="txt_count">Target Count *</label>
			   		 	</div>
			   		 	
			   		 	<div class="input-field col s4 m4" id="divstatus">
				            <select id="txt_status" name="txt_status">
								<option value="Open">Open</option>
								<option value="Assign to TH">Assign to TH</option>
								<option value="Close">Close</option>
							</select>
							
							<label for="txt_status" class="active-drop-down active">Status</label>
			   		 	</div>
			   		 	
					     <div class="input-field col s12 m12 right-align">
					     <br/><br/><br/>
					    	<input type="hidden" class="form-control hidden" id="hid_Department_ID"  name="hid_Department_ID"/>
						    <button type="submit" name="btn_Department_Save" id="btn_Department_Save" class="btn waves-effect waves-green">Add</button>
						    <button type="submit" name="btn_Department_Edit" id="btn_Department_Edit" class="btn waves-effect waves-green hidden">Save</button>
						    <button type="button" name="btn_Department_Can" id="btn_Department_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
					    </div>
				      </div>
				    </div>
				</div>
			    
			  <div id="pnlTable">
			    <?php 
					//$sqlConnect = array('table' => 'dept_master','fields' => 'dept_id,dept_name','condition' =>"1"); 
					    $sqlConnect = "select t2.location,loc,concat(client_name,'|',process,'|',sub_process) as process,batch_no,target_date,target_count,cm_id,t1.id,status from batch_status t1 join location_master t2 on t1.loc=t2.id"; 
						$myDB=new MysqliDb();
						$result=$myDB->rawQuery($sqlConnect);
						$mysql_error = $myDB->getLastError();
						if(empty($mysql_error)){?>
                         <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						            <th class="hidden">ID</th>
						            <th class="hidden">loc</th>
						            <th>Location</th>						            
						            <th>Process</th>						            
						            <th>BatchNo</th>						            
						            <th>Target Date</th>						            
						            <th>Count</th>						            
						            <th>Status</th>						            
						            <th>Manage Batch</th>
						        </tr>
						    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';							
								echo '<td class="id hidden">'.$value['id'].'</td>';
								echo '<td class="loc hidden">'.$value['loc'].'</td>';
								echo '<td class="location">'.$value['location'].'</td>';							
								echo '<td class="process">'.$value['process'].'</td>';							
								echo '<td class="batch_no">'.$value['batch_no'].'</td>';							
								echo '<td class="target_date">'.$value['target_date'].'</td>';							
								echo '<td class="target_count">'.$value['target_count'].'</td>';							
								echo '<td class="status">'.$value['status'].'</td>';							
								echo '<td class="manage_item" >
								<i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['id'].'"   data-position="left" data-tooltip="Edit">ohrm_edit</i></td>';
								/*<i class="material-icons  imgBtn imgBtnEdit tooltipped delete_item" id="'.$value['dept_id'].'"  onclick="javascirpt:return ApplicationDataDelete(this);" data-position="left" data-tooltip="Delete">ohrm_delete</i>*/ 
								
								echo '</tr>';
								}	
								?>			       
						    </tbody>
						</table>
						<?php }  ?>
				</div>
			</div> 
		</div>
	</div>    
<!--Content Div for all Page End -->  
</div>



<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>

