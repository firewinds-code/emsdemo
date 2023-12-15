<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

include(__dir__.'/../Controller/endecript.php');
require(ROOT_PATH.'AppCode/nHead.php');
$createBy=$_SESSION['__user_logid'];
$EmployeeID=$_SESSION['__user_logid'];
$imsrc=URL.'Style/images/agent-icon.png';
$target_dir = ROOT_PATH.'MedicalCertificate/';
include_once(__dir__.'/../Services/sendsms_API1.php');

$uploadOk=0;

			if(isset($_FILES["certificate"]["name"]) && $_FILES["certificate"]["name"]!="")
			{
				
				$uploadOk = 1;
			 	$target_file = $target_dir . basename($_FILES["certificate"]["name"]);	  		
				$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
				$basef1 =$_POST['Uempid'].'_'.date('ymdhis').'.'.$FileType;	
				$basef2="";
				
			
				if (strtolower($FileType) != "pdf" && strtolower($FileType) != "png" && strtolower($FileType) != "jpg" && strtolower($FileType) != "jpeg" ) 
				{
					$location=URL.'View/Covid.php';
					
					echo "<script>$(function(){ toastr.error('Sorry, only pdf files are allowed.'); }); </script>";
					$uploadOk = 0;
						
							echo "<script> window.location(".$location.")</script>";
				}
				 $file_size = str_replace(' ', '', $_FILES['certificate']['size']);
				if (($file_size > 3000000)){   
					echo "<script>$(function(){ toastr.error('File too large. File must be less than 3 MB.'); }); </script>";	
					$uploadOk = 0;
				}
				if($uploadOk==1){

				    if(move_uploaded_file($_FILES["certificate"]["tmp_name"], $target_file))
					{
						$ext = pathinfo( basename($_FILES["certificate"]["name"]), PATHINFO_EXTENSION);
				     	$file=rename($target_file,$target_dir.$basef1);
					}
					if(isset($_FILES["certificate2"]["name"]) && $_FILES["certificate2"]["name"]!="")
				{
					$target_file2 = $target_dir . basename($_FILES["certificate2"]["name"]);	  		
					$FileType2 = pathinfo($target_file2,PATHINFO_EXTENSION);
					$basef2 =$_POST['Uempid'].'_'.date('ymdhis').'_2.'.$FileType2;
					if (strtolower($FileType2) != "pdf" && strtolower($FileType2) != "png" && strtolower($FileType2) != "jpg" && strtolower($FileType2) != "jpeg" ) 
					{
						$location=URL.'View/Covid.php';
						
						echo "<script>$(function(){ toastr.error('Sorry, only pdf files are allowed.'); }); </script>";
						$uploadOk = 0;
							
								echo "<script> window.location(".$location.")</script>";
					}
					 $file_size = str_replace(' ', '', $_FILES['certificate2']['size']);
					if (($file_size > 3000000)){   
						echo "<script>$(function(){ toastr.error('File too large. File must be less than 3 MB.'); }); </script>";	
						$uploadOk = 0;
					}
					if($uploadOk==1){

				    if(move_uploaded_file($_FILES["certificate2"]["tmp_name"], $target_file2))
					{
							$ext = pathinfo( basename($_FILES["certificate2"]["name"]), PATHINFO_EXTENSION);
				     	$file=rename($target_file2,$target_dir.$basef2);
						}
					}
				}
						
					$sqlResponse = 'UPDATE  covid  SET remarks ="'.$_POST['remarks'].'", rejoining_date ="'.$_POST['rejoining_date'].'", updated_by ="'.$EmployeeID.'",certificate ="'.$basef1.'",certificate2 ="'.$basef2.'",updated_at=now(), flag=1 where id="'.$_POST['dataid'].'"' ; 	
					
					
					$myDB =  new MysqliDb();
					$Results = $myDB->rawQuery($sqlResponse);
					$mysql_error = $myDB->getLastError();
				if(empty($mysql_error))
				{
					$location=URL.'View/AccAllRequestDoc.php';
					echo "<script>$(function(){ toastr.success('Request Fullfill Successfully '); }); </script>";
							echo "<script> window.location(".$location.")</script>";
						
				}
			
			else{
				echo "<script>$(function(){ toastr.warning('Request Not Fullfill'); }); </script>";
			}
				}
				
				
			}
			
			
			if(isset($_POST['empid']) && $_POST['empid']!="" )
				{
				 $GetData = "select t1.EmployeeID,t1.EmployeeName,t1.cm_id, concat(t1.Process,' | ',t1.sub_process) as process,t2.mobile from whole_details_peremp t1 join contact_details t2 on t1.EmployeeID=t2.EmployeeID where t1.EmployeeID='".$_POST['empid']."'"; 	
						
						
						$myDB =  new MysqliDb();
						$GetDataResults = $myDB->rawQuery($GetData);
						$mysql_error = $myDB->getLastError();
					if($GetDataResults)
					{
						
							echo "<script>$(function(){ toastr.success('Employe ID Found '); }); </script>";
					
					}
					else{
						echo "<script>$(function(){ toastr.error('Employe ID Not Found '); }); </script>";
					}
					
				}
				
				
				if(isset($_POST['reporting_date']) && isset($_POST['btn_ED_Search']) && $_POST['reason']!="" )
				{
					//echo"<pre>";print_r($_POST);exit;
					 $sqlResponse = "INSERT INTO covid (empid,empname,cps,reason,reporting_date,created_by) VALUES ('".$_POST['EmployeeID']."','".$_POST['empname']."','".$_POST['cps']."','".$_POST['reason']."','".$_POST['reporting_date']."','".$EmployeeID."')";		
	
					$myDB =  new MysqliDb();
					$Results = $myDB->rawQuery($sqlResponse);
					$mysql_error = $myDB->getLastError();
				if(empty($mysql_error))
				{
					//$msg ="Dear ".$_POST['empname'].", Owing to your recent medical condition as reported by you and subsequent leave of absence from office, we advise you to resume duty only after getting a 'fit to resume duty' certificate from a regd medical practitioner on letter head. We appreciate your effort towards keeping your workplace healthy.";
					$msg ="Dear ".$_POST['empname'].", Owing to your recent medical condition as reported by you and subsequent leave of absence from office, we advise you to resume duty only after getting a 'fit to resume duty' certificate from a regd medical practitioner on letter head. We appreciate your effort towards keeping your workplace healthy- Cogent E Services";
			
			 	
				 	$TEMPLATEID='1707161725983253121';	
					$url = SMS_URL;
					$token = SMS_TOKEN;
					$credit = SMS_CREDIT;
					$sender = SMS_SENDER;
					$message = $msg;
					$number = $_POST['mobile'];
					$sendsms = new sendsms($url,$token);
					$message_id = $sendsms->sendmessage($credit,$sender,$message,$number,$TEMPLATEID);
					$response = $message_id;
					$ResultSMS=$response;
				
					$lbl_msg = ' SMS : '.$response;
					
					$smsinsert ="Call insertsmshistory('". $_POST['EmployeeID']."','". addslashes($msg)."','". $number."','". addslashes($response)."')";
				     $myDB =  new MysqliDb();
					 $query = $myDB->rawQuery($smsinsert);
					 $mysql_error = $myDB->getLastError();
					
					$location=URL.'View/Covid.php';
					echo "<script>$(function(){ toastr.success('Data Save Successfully '); }); </script>";
					echo '<script>history.pushState({}, "", "")</script>';
					echo "<script> window.location(".$location.")</script>";
				}
			
			else{
				echo "<script>$(function(){ toastr.warning('Data Not Save'); }); </script>";
			}
					
				}

			?>
<script>
	$(function(){
	
		$('#myTable').DataTable({
				        dom: 'Bfrtip',
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
									,'pageLength'
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"sScrollY" : "192",
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() {
								
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
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


<div id="content" class="content" >

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Quarantine</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main"> 
	
<!-- Sub Main Div for all Page -->
 
<div class="form-div">

<!-- Header for Form If any -->
<h4>Quarantine</h4>


<div class="schema-form-section row center">
	<form method="POST" action="<?php echo URL.'View/Covid.php' ;?>" id="covidForm">
		<div class="row ">
			<div class=" col s4 offset-s1 ">
				
			 <input type="text"  id="empid"   name="empid" placeholder="Enter EmployeeID"  required />
			
			</div>	
			<div class=" col s4 offset-s1 ">
				
			 <button name="lookup" id="lookup" class="btn waves-effect waves-green">Look Up</button>

			</div>	
		
     </div>
			
			</form>
	</div>
	<?php if(isset($GetDataResults) && count($GetDataResults) > 0 ){?>
<div class="schema-form-section row center">
	<form method="POST" action="<?php echo URL.'View/Covid.php' ;?>" id="covidForm2">
		<div class="row ">
			<div class=" col s4 offset-s1 ">
				
			 <input type="hidden"   id="EmployeeID"   name="EmployeeID" placeholder="Enter EmployeeID"  value="<?php echo $GetDataResults[0]['EmployeeID']?>" />
			 <input type="hidden"   id="cps"   name="cps" placeholder="Enter cm_id"  value="<?php echo $GetDataResults[0]['cm_id']?>" />
			<input type="hidden"   id="mobile"   name="mobile" placeholder="Enter cm_id"  value="<?php echo $GetDataResults[0]['mobile']?>" />
			</div>	
		
     </div>
			<div class="row ">
      	<div class=" col s6 offset-s1 ">
        
					 <input type="text"  id="empname" readonly  name="empname" placeholder="Enter Employee name" autocomplete="off" required value="<?php echo $GetDataResults[0]['EmployeeName']?>"/>
									
        </div>
      </div>
		
				<div class="row ">
      	<div class=" col s6 offset-s1 ">
        
					 <input type="text"  id="process"  readonly name="process" placeholder="process" autocomplete="off" required value="<?php echo $GetDataResults[0]['process']?>" />
									
        </div>
      </div>
		<div class="row ">
      	<div class=" col s6 offset-s1 ">
       	<input type="text"  id="reporting_date"   name="reporting_date" readonly placeholder="Select Date">	
									
        </div>
      </div>
<div class="row ">
       	<div class=" col s6 offset-s1 ">
        <textarea class="materialize-textarea"  id="reason"   name="reason" placeholder="Enter reason"></textarea>
					
									
        </div>
      </div>

		
			<div class=" col s6 offset-s1 ">
			    	<button type="submit" name="btn_ED_Search" title="Save" id="btn_ED_Search" class="btn waves-effect waves-green" value="submit">Save</button>
			    	
			    </div>
				</form>
				</div>
				<?php } ?>
				<div class="schema-form-section row center">
				<?php 

			$sqlConnect="SELECT t1.*, concat(t2.process,'|',t2.sub_process) as process  FROM covid t1	 join new_client_master  t2 on t1.cps=t2.cm_id where t1.flag=0;";
		
	
	$myDB=new MysqliDb();
	$result=$myDB->query($sqlConnect);
	if ($result) {
	?>
	
	
	

	<div class="panel panel-default col-sm-12" style="margin-top:10px;">
	<div class="panel-body" style="overflow-x: scroll;">
		<table id="myTable" class="data dataTable no-footer row-border"   style="width:100%;">

		<thead>
			<tr>
				<th>EmployeeID </th>
				<th>Employee Name</th>
				<th>Client/Process/Subprocess</th>
				<th>Reason</th>
				<th>Reporting Date</th>
				<th>Rejoining Date</th>
				<th>Certificate</th>
				<th>Remark</th>
				<th>Created By</th>
				<th>Created On</th>
				<th>Updated By</th>
				<th>Updated On</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($result as $key=>$value) {
				echo '<tr>';
				echo '<td class="test_name"><i class="fa fa-edit  modal-trigger req" style="font-size:15px;color:#19aec4"; data-target="modal1" Dataid="'.$value['id'].'" empid="'.$value['empid'].'"></i> '.$value['empid'].'</td>';
				echo '<td class="test_name">'.$value['empname'].'</td>';
				echo '<td class="test_name">'.$value['process'].'</td>';
				echo '<td class="test_name">'.$value['reason'].'</td>';
				if($value['reporting_date']!="" && $value['reporting_date']!= Null){
				echo '<td class="testid">'.date("j F Y",strtotime($value['reporting_date'])).'</td>';
				}else{
						echo '<td class="testid"></td>';
				}
				if($value['rejoining_date']!="" && $value['rejoining_date']!= Null){
				echo '<td class="testid">'.date("j F Y",strtotime($value['rejoining_date'])).'</td>';
				}else{
						echo '<td class="testid"></td>';
				}
			if($value['certificate']!="")
				{
					echo '<td class="manage_item" style="text-align:center"><a class=" waves-effect waves-light btn-small" href="../MedicalCertificate/'.$value['certificate'].'" target="_blank"> <i class="fa fa-download"></i>Download </a> </td>';
				}
				else{
					echo '<td class="manage_item" style="text-align:center">Download </td>';
				}
				
				echo '<td class="remarks">'.$value['remarks'].'</td>';
				echo '<td class="doc_value">'.$value['created_by'].'</td>';
				echo '<td class="testid">'.date("j F Y",strtotime($value['created_at'])).'</td>';
				echo '<td class="doc_value">ssss'.$value['updated_by'].'</td>';
			
			if($value['updated_at']!="" && $value['updated_at']!= Null){
				echo '<td class="testid">'.date("j F Y",strtotime($value['updated_at'])).'</td>';
				}else{
						echo '<td class="testid"></td>';
				}
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
	</div>
  	</div>
	<?php
	}
	?>
</div>
  	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>
 <div id="modal1" class="modal">
    <div class="modal-content ">
      <h4 style="color:#19aec4">Select File To Upload</h4>
			<form method="POST" action="<?php echo URL.'View/Covid.php' ;?>"  enctype="multipart/form-data" >
			
			<input type="hidden" name="dataid" id="dataid">
			<input type="hidden" name="Uempid" id="Uempid">
			<div class="row center">
		<div class="col s8 offset-s2">
			<div class="row  ">
        <div class="input-field center">
         	<input type="text"  id="rejoining_date"   name="rejoining_date" readonly placeholder="Select Date">	
          <label for="remarks">Rejoining Date</label>
        </div>
      </div>
			<div class="row  ">
        <div class="input-field center">
          <textarea id="remarks"  name="remarks" class="materialize-textarea " required></textarea>
          <label for="remarks">Remarks</label>
        </div>
      </div>
			
    <div class="file-field  input-field ">
		
      <div class="btn ">
        <span>Attach Medical Certificate</span>
        
				
        <input type="file" name="certificate" required>
      </div>
      <span>(Upload only pdf, jpg and png file)</span>
      <div class="file-path-wrapper ">
        <input class="file-path validate" type="text">
      </div>
			
    </div>	
    <div class="file-field  input-field ">
		
      <div class="btn ">
        <span>Attach Medical Certificate 2</span>
        
				
        <input type="file" name="certificate2" >
      </div>
      <span>(Upload only pdf, jpg and png file)</span>
      <div class="file-path-wrapper ">
        <input class="file-path validate" type="text">
      </div>
			
    </div>
			<div class="row">
        <div class="input-field">
          <input class="validate input-field btn" type="submit" value="submit">
        </div>
      </div>
		</div>
		</div>
  </form>
    </div>
    <div class="modal-footer">
      <a href="#" class="btn modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
  </div> 

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>

 <script>
  

  // Or with jQuery
	
	 $('.req').click(function() {
    var dataid =  $(this).attr("Dataid");
    var Uempid =  $(this).attr("empid");
			$("input[name='dataid']").val(dataid);
			$("input[name='Uempid']").val(Uempid);
    });

  $(document).ready(function(){
    $('.modal').modal();
  });
	
	
	 

 $(document).ready(function(){
	
				$('#reporting_date').datetimepicker({
	timepicker:false,
	format:'Y-m-d'
});
$('#rejoining_date').datetimepicker({
	timepicker:false,
	format:'Y-m-d'
});
});
$(document).ready(function(){
		    $('#lookup').on('click', function(){
		        $("#covidForm").submit();
		       
		    });
	});

</script>