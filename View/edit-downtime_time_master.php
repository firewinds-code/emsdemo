<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Only for user type administrator
if($_SESSION['__user_type']=='ADMINISTRATOR' || $_SESSION['__user_logid']=='CE10091236' || $_SESSION['__user_logid']=='CE12102224')
{
// proceed further
}
else
{
		$location= URL.'Error'; 
	header("Location: $location");
	exit();
	
}

// Global variable used in Page Cycle
$alert_msg ='';

// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_df_Save']))
{
	$_cm_id		=	$_POST['txt_cm_id'];
	$txt_client_training  = 	$_POST['txt_client_training'];
	$txt_min_time  = 	$_POST['txt_min_time'];
	$txt_max_time  = 	$_POST['txt_max_time'];
	$txt_total_time  = 	$_POST['txt_total_time'];
	$txt_ojt_days  = 	$_POST['txt_ojt_days'];
	$txt_training_days = 	$_POST['txt_training_days'];
	$ojt_day_1 = $_POST['txt_day_1'];
	$ojt_day_2 = $_POST['txt_day_2'];
	$ojt_day_3 = $_POST['txt_day_3'];
	$ojt_day_4 = $_POST['txt_day_4'];
	$ojt_day_5 = $_POST['txt_day_5'];
	$ojt_day_6 = $_POST['txt_day_6'];
	$ojt_day_7 = $_POST['txt_day_7'];
	$ojt_day_8 = $_POST['txt_day_8'];
	$ojt_day_9 = $_POST['txt_day_9'];
	$ojt_day_10 = $_POST['txt_day_10'];
	$ojt_day_11 = $_POST['txt_day_11'];
	$ojt_day_12 = $_POST['txt_day_12'];
	$ojt_day_13 = $_POST['txt_day_13'];
	$ojt_day_14 = $_POST['txt_day_14'];
	$ojt_day_15 = $_POST['txt_day_15'];
	$ojt_day_16 = $_POST['txt_day_16'];
	$ojt_day_17 = $_POST['txt_day_17'];
	$ojt_day_18 = $_POST['txt_day_18'];
	$ojt_day_19 = $_POST['txt_day_19'];
	$ojt_day_20 = $_POST['txt_day_20'];
	
	$myDB=new MysqliDb();
	$result_check = $myDB->query('SELECT * FROM downtime_time_master where cm_id = "'.$_cm_id.'"');
	if(count($result_check) > 0 && $result_check)
	{
		echo "<script>$(function(){ toastr.error('Already exists delete or edit existing entry first') }); </script>";			
	}
	else
	{
		$createBy=$_SESSION['__user_logid'];
		$Insert='INSERT INTO downtime_time_master(cm_id,client_training,client_time_ttl,client_time_min,client_time_max,ojt_days,ojt_day_1,ojt_day_2,ojt_day_3,ojt_day_4,ojt_day_5,ojt_day_6,ojt_day_7,ojt_day_8,ojt_day_9,ojt_day_10,ojt_day_11,ojt_day_12,ojt_day_13,ojt_day_14,ojt_day_15,ojt_day_16,ojt_day_17,ojt_day_18,ojt_day_19,ojt_day_20,createdby,training_days) VALUES("'.$_cm_id.'","'.$txt_client_training.'","'.$txt_total_time.'","'.$txt_min_time.'","'.$txt_max_time.'","'.$txt_ojt_days.'","'.$ojt_day_1.'","'.$ojt_day_2.'","'.$ojt_day_3.'","'.$ojt_day_4.'","'.$ojt_day_5.'","'.$ojt_day_6.'","'.$ojt_day_7.'","'.$ojt_day_8.'","'.$ojt_day_9.'","'.$ojt_day_10.'","'.$ojt_day_11.'","'.$ojt_day_12.'","'.$ojt_day_13.'","'.$ojt_day_14.'","'.$ojt_day_15.'","'.$ojt_day_16.'","'.$ojt_day_17.'","'.$ojt_day_18.'","'.$ojt_day_19.'","'.$ojt_day_20.'","'.$createBy.'","'.$txt_training_days.'");';
		
		$myDB=new MysqliDb();
		$result=$myDB->rawQuery($Insert);
		$mysql_error = $myDB->getLastError();
		$rowCount = $myDB->count;
		if(empty($mysql_error))
		{
			if($rowCount>0)
			{
				echo "<script>$(function(){ toastr.success('Added Successfully') }); </script>";
			}
			else
			{
			    echo "<script>$(function(){ toastr.error('Not Added :: '.$mysql_error.') }); </script>";	
			}
		}
		else
		{
			echo "<script>$(function(){ toastr.error('not Added :: '.$mysql_error.') }); </script>";	
		}
	}
	
	
	
}

// Trigger Button-Edit Click Event and Perform DB Action
if(isset($_POST['btn_df_Edit']))
{
	$_cm_id		=	$_POST['txt_cm_id'];
	
	if(empty($_POST['txtEditID']) && !is_numeric($_POST['txtEditID']))
	{
		echo "<script>$(function(){ toastr.error('No data found to update') }); </script>";
	}
	else
	{
		$createBy = $_SESSION['__user_logid'];
		$txt_client_training  = 	$_POST['txt_client_training'];
		$txt_min_time  = 	$_POST['txt_min_time'];
		$txt_max_time  = 	$_POST['txt_max_time'];
		$txt_total_time  = 	$_POST['txt_total_time'];
		$txt_ojt_days  = 	$_POST['txt_ojt_days'];
		$txt_training_days = 	$_POST['txt_training_days'];
		$ojt_day_1 = $_POST['txt_day_1'];
		$ojt_day_2 = $_POST['txt_day_2'];
		$ojt_day_3 = $_POST['txt_day_3'];
		$ojt_day_4 = $_POST['txt_day_4'];
		$ojt_day_5 = $_POST['txt_day_5'];
		$ojt_day_6 = $_POST['txt_day_6'];
		$ojt_day_7 = $_POST['txt_day_7'];
		$ojt_day_8 = $_POST['txt_day_8'];
		$ojt_day_9 = $_POST['txt_day_9'];
		$ojt_day_10 = $_POST['txt_day_10'];
		$ojt_day_11 = $_POST['txt_day_11'];
		$ojt_day_12 = $_POST['txt_day_12'];
		$ojt_day_13 = $_POST['txt_day_13'];
		$ojt_day_14 = $_POST['txt_day_14'];
		$ojt_day_15 = $_POST['txt_day_15'];
		$ojt_day_16 = $_POST['txt_day_16'];
		$ojt_day_17 = $_POST['txt_day_17'];
		$ojt_day_18 = $_POST['txt_day_18'];
		$ojt_day_19 = $_POST['txt_day_19'];
		$ojt_day_20 = $_POST['txt_day_20'];
	
		$Update='update downtime_time_master set client_training = "'.$txt_client_training.'",client_time_ttl = "'.$txt_total_time.'",client_time_min = "'.$txt_min_time.'",client_time_max = "'.$txt_max_time.'",ojt_days = "'.$txt_ojt_days.'",ojt_day_1 = "'.$ojt_day_1.'",ojt_day_2 = "'.$ojt_day_2.'",ojt_day_3 = "'.$ojt_day_3.'",ojt_day_4 = "'.$ojt_day_4.'",ojt_day_5 = "'.$ojt_day_5.'",ojt_day_6 = "'.$ojt_day_6.'",ojt_day_7 = "'.$ojt_day_7.'",ojt_day_8 = "'.$ojt_day_8.'",ojt_day_9 = "'.$ojt_day_9.'",ojt_day_10 = "'.$ojt_day_10.'",ojt_day_11 = "'.$ojt_day_11.'",ojt_day_12 = "'.$ojt_day_12.'",ojt_day_13 = "'.$ojt_day_13.'",ojt_day_14 = "'.$ojt_day_14.'",ojt_day_15 = "'.$ojt_day_15.'",ojt_day_16 = "'.$ojt_day_16.'",ojt_day_17 = "'.$ojt_day_17.'",ojt_day_18 = "'.$ojt_day_18.'",ojt_day_19 = "'.$ojt_day_19.'",ojt_day_20 = "'.$ojt_day_20.'",createdby = "'.$createBy.'",training_days="'.$txt_training_days.'" where cm_id = "'.$_cm_id.'" and id = "'.$_POST['txtEditID'].'";';	
		//echo($Update);
		$myDB=new MysqliDb();	
		$result=$myDB->query($Update);
		$mysql_error = $myDB->getLastError();
		//$rowCount = $myDB->count;
		if(empty($mysql_error))
		{
			echo "<script>$(function(){ toastr.success('Updated Successfully') }); </script>";	
		}
		else
		{
			echo "<script>$(function(){ toastr.error('Not Updated :: '.$mysql_error.') }); </script>";		
		}
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
		"sScrollX" : "100%",
		buttons: [
		      
		   /* {
		        extend: 'csv',
		        text: 'CSV',
		        extension: '.csv',
		        exportOptions: {
		            modifier: {
		                page: 'all'
		            }
		        },
		        title: 'table'
		    }, 						         
		    'print',*/
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
		    /*,'copy'*/
		    ,'pageLength'
		    
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
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Downtime Master</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Downtime Master<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped" id="ContentAdd" href="#myModal_content" data-position="bottom" data-tooltip="Add Downtime"><i class="material-icons">add</i></a></h4>				

<!-- Form container if any -->
<div class="schema-form-section row" >
<!--Form element model popup start-->

	 <div id="myModal_content" class="modal modal_big">
	 	 <!-- Modal content-->
	    <div class="modal-content">
	      <h4 class="col s12 m12 model-h4">Manage Downtime Master</h4>
	      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto;">
		    <div class="col s12 m12">
		    	
		    	<div class="input-field col s4 m4">
	            <select id="txt_location" name="txt_location" required onchange="javascript:return getProcess(this,'');">
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
	            <label for="txt_location" class="active-drop-down active">Location</label>
			    </div>
			    
			    <div class="input-field col s4 m4">
			    <select id="txt_cm_id"  name="txt_cm_id" required></select>
			      <!--<select id="txt_cm_id" name="txt_cm_id" required>
		            	<option value="NA">---Select---</option>
		            	<?php			
							$sqlBy ='select * from new_client_master inner join client_master  on new_client_master.client_name = client_master.client_id order by client_master.client_name '; 
							$myDB=new MysqliDb();
							$resultBy=$myDB->rawQuery($sqlBy);
							$mysql_error = $myDB->getLastError();
							if(empty($mysql_error)){													
								foreach($resultBy as $key=>$value)
								{						
								echo '<option value="'.$value['cm_id'].'"  >'.$value['client_name'].' | '.$value['process'].' | '.$value['sub_process'].'</option>';
								}
							}				
				      	?>
		            </select>-->
		            <label for="txt_cm_id" class="active-drop-down active">Process</label>
		            </div>
	            <div class="input-field col s4 m4">
				        <input type="number" min="0" value="0" max="60"  id="txt_training_days" name="txt_training_days" required />	
				        <label for="txt_training_days">Training Days</label>
			    </div> 
			    
			    
			    
			    <div class="input-field col s4 m4">
				        <input type="number" min="0" value="0" max="40"  id="txt_ojt_days" name="txt_ojt_days" required/>	
				        <label for="txt_ojt_days">OJT Days</label>
			    </div>    
		    </div>
			<div class="col s12 m12">
			    <div class="input-field col s6 m6">
					<select  id="txt_client_training" name="txt_client_training" required>
						<option>NO</option>
						<option>YES</option>
					</select>
					<label for="txt_client_training" class="active-drop-down active">Client Training</label>
			    </div>
			    <div class="input-field col s6 m6">
				        <input type="text"  id="txt_total_time" name="txt_total_time" required />
				        <label for="txt_total_time">Total Time</label>
			    </div>
			</div>
			
			<div class="col s12 m12">
			    <div class="input-field col s6 m6">
		            <select  id="txt_min_time" name="txt_min_time" required>
		            		<option>00:00:00</option>
		            		<option>00:10:00</option>
		            		<option>00:20:00</option>
							<option>00:30:00</option>
							<option>01:00:00</option>
							<option>01:30:00</option>
							<option>02:00:00</option>
							<option>02:30:00</option>
							<option>03:00:00</option>
							<option>03:30:00</option>
							<option>04:00:00</option>
							<option>04:30:00</option>
							<option>05:00:00</option>
							<option>05:30:00</option>
							<option>06:00:00</option>
							<option>06:30:00</option>
							<option>07:00:00</option>
							<option>07:30:00</option>
							<option>08:00:00</option>
							<option>08:30:00</option>
							<option>09:00:00</option>
							<option>09:30:00</option>
							<option>10:00:00</option>
							<option>10:30:00</option>
							<option>11:00:00</option>
							<option>11:30:00</option>
							<option>12:00:00</option>
							<option>12:30:00</option>
							<option>13:00:00</option>
							<option>13:30:00</option>
							<option>14:00:00</option>
							<option>14:30:00</option>
							<option>15:00:00</option>
							<option>15:30:00</option>
							<option>16:00:00</option>
							<option>16:30:00</option>
							<option>17:00:00</option>
							<option>17:30:00</option>
							<option>18:00:00</option>
							<option>18:30:00</option>
							<option>19:00:00</option>
							<option>19:30:00</option>
							<option>20:00:00</option>
		            </select>
		            <label for="txt_min_time" class="active-drop-down active">Min Time :</label>
			    </div>
			    <div class="input-field col s6 m6">
			            <select id="txt_max_time" name="txt_max_time" required>
			            		<option>00:00:00</option>
			            		<option>00:30:00</option>
								<option>01:00:00</option>
								<option>01:30:00</option>
								<option>02:00:00</option>
								<option>02:30:00</option>
								<option>03:00:00</option>
								<option>03:30:00</option>
								<option>04:00:00</option>
								<option>04:30:00</option>
								<option>05:00:00</option>
								<option>05:30:00</option>
								<option>06:00:00</option>
								<option>06:30:00</option>
								<option>07:00:00</option>
								<option>07:30:00</option>
								<option>08:00:00</option>
								<option>08:30:00</option>
								<option>09:00:00</option>
								<option>09:30:00</option>
								<option>10:00:00</option>
								<option>10:30:00</option>
								<option>11:00:00</option>
								<option>11:30:00</option>
								<option>12:00:00</option>
								<option>12:30:00</option>
								<option>13:00:00</option>
								<option>13:30:00</option>
								<option>14:00:00</option>
								<option>14:30:00</option>
								<option>15:00:00</option>
								<option>15:30:00</option>
								<option>16:00:00</option>
								<option>16:30:00</option>
								<option>17:00:00</option>
								<option>17:30:00</option>
								<option>18:00:00</option>
								<option>18:30:00</option>
								<option>19:00:00</option>
								<option>19:30:00</option>
								<option>20:00:00</option>
			            </select>	
			            <label for="txt_max_time" class="active-drop-down active">Max Time</label>
			    </div>
			</div>
			    
			<div class="col s12 m12">
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_1" name="txt_day_1" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				            <label for="txt_day_1" class="active-drop-down active">Day 1</label>
			    </div>
			    <div class="input-field col s4 m4">
			             <select  id="txt_day_2" name="txt_day_2" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
												            	
				            </select>
				         <label for="txt_day_2" class="active-drop-down active">Day 2</label>
			    </div>
			    <div class="input-field col s4 m4">
				        <select id="txt_day_3" name="txt_day_3" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
									
				            </select>
				        <label for="txt_day_3" class="active-drop-down active">Day 3</label>
			    </div>
			    
			</div>
			<div class="col s12 m12">
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_4" name="txt_day_4" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
									
				            </select>
				            <label for="txt_day_4" class="active-drop-down active">Day 4</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select id="txt_day_5" name="txt_day_5" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
			                <label for="txt_day_5" class="active-drop-down active">Day 5</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select id="txt_day_6" name="txt_day_6" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
									
				            </select>
				            <label for="txt_day_6" class="active-drop-down active">Day 6</label>
			    </div>  
			</div>
			<div class="col s12 m12">
			    <div class="input-field col s4 m4">
				            <select id="txt_day_7" name="txt_day_7" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
			             <label for="txt_day_7" class="active-drop-down active">Day 7</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select id="txt_day_8" name="txt_day_8" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				            <label for="txt_day_8" class="active-drop-down active">Day 8</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_9" name="txt_day_9" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				            <label for="txt_day_9" class="active-drop-down active">Day 9</label>
				         </div> 
			</div>
			 
			<div class="col s12 m12">
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_10" name="txt_day_10" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				           <label for="txt_day_10" class="active-drop-down active">Day 10</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_11" name="txt_day_11" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				         <label for="txt_day_11" class="active-drop-down active">Day 11</label> 
			    </div>
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_12" name="txt_day_12" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				         <label for="txt_day_12"  class="active-drop-down active">Day 12</label> 
			    </div>
			</div>
			
			<div class="col s12 m12">
			    <div class="input-field col s4 m4">
			            <select  id="txt_day_13" name="txt_day_13" required>
			            		<option>00:00:00</option>
								<option>01:00:00</option>
								<option>01:30:00</option>
								<option>02:00:00</option>
								<option>02:30:00</option>
								<option>03:00:00</option>
								<option>03:30:00</option>
								<option>04:00:00</option>
								<option>04:30:00</option>
								<option>05:00:00</option>
								<option>05:30:00</option>
								<option>06:00:00</option>
								<option>06:30:00</option>
								<option>07:00:00</option>
								<option>07:30:00</option>
								<option>08:00:00</option>
			            </select>
			         <label for="txt_day_13" class="active-drop-down active" >Day 13</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_14" name="txt_day_14" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
									
				            </select>
				         <label for="txt_day_14" class="active-drop-down active">Day 14</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select id="txt_day_15" name="txt_day_15" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				          <label for="txt_day_15" class="active-drop-down active">Day 15</label>
			    </div>
			</div>
			    
			<div class="col s12 m12">
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_16" name="txt_day_16" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				         <label for="txt_day_16" class="active-drop-down active">Day 16</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_17" name="txt_day_17" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
									
				            </select>
				         <label for="txt_day_17" class="active-drop-down active">Day 17</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_18" name="txt_day_18" required>
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
				            </select>
				        <label for="txt_day_18" class="active-drop-down active">Day 18</label>
			    </div>
			</div>
			
			<div class="col s12 m12">
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_19" name="txt_day_19" required >
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
									
				            </select>
				         <label for="txt_day_19" class="active-drop-down active">Day 19</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select  id="txt_day_20" name="txt_day_20" required >
				            		<option>00:00:00</option>
									<option>01:00:00</option>
									<option>01:30:00</option>
									<option>02:00:00</option>
									<option>02:30:00</option>
									<option>03:00:00</option>
									<option>03:30:00</option>
									<option>04:00:00</option>
									<option>04:30:00</option>
									<option>05:00:00</option>
									<option>05:30:00</option>
									<option>06:00:00</option>
									<option>06:30:00</option>
									<option>07:00:00</option>
									<option>07:30:00</option>
									<option>08:00:00</option>
									
				            </select>
				            <label for="txt_day_20" class="active-drop-down active">Day 20</label>
			    </div>
			    
			</div>
			
			<div class="input-field col s12 m12 right-align">
		    		<input type="hidden"  id="txtEditID" name="txtEditID" />
				    <button type="submit" name="btn_df_Save" id="btn_df_Save" class="btn waves-effect waves-green">Save</button>
				    <button type="submit" name="btn_df_Edit" id="btn_df_Edit" class="btn waves-effect waves-green hidden">Update</button>
				    <button type="button" name="btn_df_Can" id="btn_df_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
			    		
			</div>
			    
            </div>
		</div>
	</div>
<!--Form element model popup End-->
			    
<!--Reprot / Data Table start -->
	<div id="pnlTable">
			    <?php 
					//$sqlConnect = 'SELECT * FROM downtime_time_master inner join new_client_master on new_client_master.cm_id = downtime_time_master.cm_id inner join client_master  on new_client_master.client_name = client_master.client_id';
					$sqlConnect = ' SELECT dt.*,nc.*,cm.*,t1.location FROM downtime_time_master dt inner join new_client_master nc on nc.cm_id = dt.cm_id inner join client_master cm  on nc.client_name = cm.client_id inner join location_master t1 on t1.id = nc.location left outer join client_status_master cs on cs.cm_id=nc.cm_id where cs.cm_id is null order by cm.client_name';
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if(empty($mysql_error)){?>
						<table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
						    <thead>
						        <tr>
						        	<th class="hidden">Process</th>
						            <th>Process</th>
						            <th>Client Training</th>
						            <th>Location</th>						            
						            <th>Total Time</th>
						            <th>Min Time</th>						            
						            <th>Max Time</th>
						            <th>OJT Days</th>
						            <th>Training Days</th>
						            <th>OJT Day 1st</th>
						            <th>OJT Day 2nd</th>
						            <th>OJT Day 3rd</th>
						            <th>OJT Day 4th</th>
						            <th>OJT Day 5th</th>
						            <th>OJT Day 6th</th>
						            <th>OJT Day 7th</th>
						            <th>OJT Day 8th</th>
						            <th>OJT Day 9th</th>
						            <th>OJT Day 10th</th>
						            <th>OJT Day 11th</th>
						            <th>OJT Day 12th</th>
						            <th>OJT Day 13th</th>
						            <th>OJT Day 14th</th>
						            <th>OJT Day 15th</th>
						            <th>OJT Day 16th</th>
						            <th>OJT Day 17th</th>
						            <th>OJT Day 18th</th>
						            <th>OJT Day 19th</th>
						            <th>OJT Day 20th</th>
						            <th>Manage</th>
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php
					        foreach($result as $key=>$value){
							echo '<tr>';							
							echo '<td class="id hidden">'.$value['id'].'</td>';	
							echo '<td class="cm_id" data="'.$value['cm_id'].'">'.$value['client_name'].' | '.$value['process'].' | '.$value['sub_process'].'</td>';
							echo '<td class="client_training">'.$value['client_training'].'</td>';	
							echo '<td class="location">'.$value['location'].'</td>';
							echo '<td class="client_time_ttl">'.$value['client_time_ttl'].'</td>';	
							echo '<td class="client_time_min">'.$value['client_time_min'].'</td>';	
							echo '<td class="client_time_max">'.$value['client_time_max'].'</td>';
							echo '<td class="ojt_days">'.$value['ojt_days'].'</td>';
							echo '<td class="training_days">'.$value['training_days'].'</td>';
							echo '<td class="ojt_day_1">'.$value['ojt_day_1'].'</td>';
							echo '<td class="ojt_day_2">'.$value['ojt_day_2'].'</td>';
							echo '<td class="ojt_day_3">'.$value['ojt_day_3'].'</td>';
							echo '<td class="ojt_day_4">'.$value['ojt_day_4'].'</td>';
							echo '<td class="ojt_day_5">'.$value['ojt_day_5'].'</td>';
							echo '<td class="ojt_day_6">'.$value['ojt_day_6'].'</td>';
							echo '<td class="ojt_day_7">'.$value['ojt_day_7'].'</td>';
							echo '<td class="ojt_day_8">'.$value['ojt_day_8'].'</td>';
							echo '<td class="ojt_day_9">'.$value['ojt_day_9'].'</td>';
							echo '<td class="ojt_day_10">'.$value['ojt_day_10'].'</td>';
							echo '<td class="ojt_day_11">'.$value['ojt_day_11'].'</td>';
							echo '<td class="ojt_day_12">'.$value['ojt_day_12'].'</td>';
							echo '<td class="ojt_day_13">'.$value['ojt_day_13'].'</td>';
							echo '<td class="ojt_day_14">'.$value['ojt_day_14'].'</td>';
							echo '<td class="ojt_day_15">'.$value['ojt_day_15'].'</td>';
							echo '<td class="ojt_day_16">'.$value['ojt_day_16'].'</td>';
							echo '<td class="ojt_day_17">'.$value['ojt_day_17'].'</td>';
							echo '<td class="ojt_day_18">'.$value['ojt_day_18'].'</td>';
							echo '<td class="ojt_day_19">'.$value['ojt_day_19'].'</td>';
							echo '<td class="ojt_day_20">'.$value['ojt_day_20'].'</td>';
							
							echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['id'].'" data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
							echo '</tr>';
							}	
							?>			       
					    </tbody>
						</table>
						 
						<?php
					 } 
					?>
				</div>
<!--Reprot / Data Table End -->	
	
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>

</div>
<script>
$(document).ready(function(){
		
//Model Assigned and initiation code on document load	
$('.modal').modal(
{
	onOpenStart:function(elm)
	{
		
	},
	onCloseEnd:function(elm)
	{
		$('#btn_Client_Can').trigger("click");
	}
});
		
// This code for remove error span from all element contain .has-error class on listed events
$(document).on("click blur focus change",".has-error",function(){
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
});
		
$('#btn_df_Can').on('click', function(){
    $('#txtEditID').val('');	        
    $('#txt_cm_id').val('NA');
    $('#txt_client_training').val('NO');
    $('#txt_min_time').val('00:00:00');
    $('#txt_max_time').val('00:00:00');
    $('#txt_total_time').val('00:00:00');
    $('#txt_ojt_days').val('0');
    $('#txt_training_days').val('0');
    
    $("select[name^='txt_day_']").each(function(){
    	
    	$(this).val("00:00:00");
    	
    });
    
    
    $('#btn_df_Save').removeClass('hidden');
    $('#btn_df_Edit').addClass('hidden');
    //$('#btn_df_Can').addClass('hidden');
    
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
// This code active label on value assign when any event trigger and value assign by javascript code.

$("#myModal_content input,#myModal_content textarea").each(function(index, element) {

     if($(element).val().length > 0) {
       $(this).siblings('label, i').addClass('active');
     }
     else
     {
	 	$(this).siblings('label, i').removeClass('active');
	 }
	        
});
$('select').formSelect();
   
});
		    
	    
// This code for submit button and form submit for all model field validation if this contain a requored attributes also has some manual code validation to if needed.
    
$('#btn_df_Save,#btn_df_Edit').on('click', function(){
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
        		    
      	if(validate==1)
      	{		      		
      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
      		$('#alert_message').show().attr("class","SlideInRight animated");
      		$('#alert_message').delay(50000).fadeOut("slow");
			return false;
		}
       
    });
		    
});	
function EditData(el)
{			
	        var tr = $(el).closest('tr');
	        var id = tr.find('.id').text();
	        var cm_id = tr.find('.cm_id').attr('data');
	       
	       
	        $('#txtEditID').val(id);
	        //$('#txt_cm_id').val(cm_id);
	         $('#txt_location').val('NA');
	        var location=tr.find('.location').text();
	        
	        $("#txt_location option:contains(" + location + ")").attr('selected', 'selected');
	        getProcess($('#txt_location').val(),cm_id);
	        
	         
	        $('#txt_client_training').val(tr.find('.client_training').text());
	        $('#txt_min_time').val(tr.find('.client_time_min').text());
	        $('#txt_max_time').val(tr.find('.client_time_max').text());
	        $('#txt_total_time').val(tr.find('.client_time_ttl').text());
	        $('#txt_ojt_days').val(tr.find('.ojt_days').text());
	        $('#txt_training_days').val(tr.find('.training_days').text());
	        $('#txt_cm_id').val(cm_id);
	        $("select[name^='txt_day_']").each(function(){
	        	var temp_id = $(this).attr('name');
	        	temp_id = temp_id.match(/\d+/)[0]; 
	        	$(this).val(tr.find('.ojt_day_'+temp_id).text());
	        	
	        });
	        
	        $('#btn_df_Save').addClass('hidden');
	        $('#btn_df_Edit').removeClass('hidden');
	        //$('#btn_df_Can').removeClass('hidden');
	        $('#myModal_content').modal('open');
	        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
	        	
		         if($(element).val().length > 0) {
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
	
		xmlhttp.open("GET", "../Controller/delete_downtime_time_master.php?ID=" + el.id, true);
		xmlhttp.send();
	}
}

	function getProcess(el,el1)
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
					$('#txt_cm_id').html(Resp);
					$('select').formSelect();
				}
				
			}
			$('#txt_clientname').val($("#txt_client option:selected").text());
			var location = <?php echo $_SESSION["__location"] ?>;
			xmlhttp.open("GET", "../Controller/getprocessByLocation.php?loc=" + $('#txt_location').val()+"&cmid="+el1, true);
			xmlhttp.send();
			//$('#txt_cm_id').val(el1);
	}
	
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
