<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
require_once(LIB.'PHPExcel/IOFactory.php');
$msgFile ='';
$insert_row=$btnUploadCheck=0;
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Upload Promotion/ Increment</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Upload Promotion/ Increment
	 <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="../FileContainer/upload_format_promotion.xlsx" data-position="bottom" data-tooltip="Download Formate For Promotion"><i class="material-icons">file_download</i></a>
	 <a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="../FileContainer/upload_format_increment.xlsx" data-position="bottom" data-tooltip="Download Formate For Increment"><i class="material-icons">file_download</i></a></h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

			<div class="panel panel-default" style="margin-top: 10px;">
				<div class="panel-body"  >
				<div class="form-inline" >
			 		<div class="form-inline" >
			 	
				   	 <div class="input-field col s12 m12">
					            <select id="txt_Type_Upload" name="txt_Type_Upload" >
					            	<option value="NA">----Select----</option>
					            	<option value="Promotion">Promotion</option>
					            	<option value="Increment">Increment</option>
					            </select>
				         	    <label for="txt_Type_Upload" class="active-drop-down active">Upload For</label>
				      </div>
				    
				     <div class="file-field input-field col s12 m12">
						<div class="btn">
							<span>Upload File</span>
							<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;" >
						</div>
						<div class="file-path-wrapper" >
							 <input class="file-path" type="text" style="">						    
						</div>
					</div>
				     
				    <div class="input-field col s12 m12 right-align">
				       <input  type="submit" value="Submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green"/>
				    </div>
		</div>
	</div>
<!--upload data-->
<?php  
			if(isset($_POST['UploadBtn']))
			{		
			if($_POST['txt_Type_Upload']=='Promotion')
			{
		        $btnUploadCheck=1;
				$target_dir = ROOT_PATH.'UploadPromotion/';
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Allow certain file formats
				if($FileType != "xlsx")
				{
				    echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed'); }); </script>";
				    $uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0)
				{
				     echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded'); }); </script>";
				// if everything is ok, try to upload file
				} 
				else {
				    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
				    {
				        echo "<script>$(function(){ toastr.success('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded.'); }); </script>";
				        $document = PHPExcel_IOFactory::load($target_file);
						// Get the active sheet as an array
						$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
						//var_dump($activeSheetData);
						 echo "<script>$(function(){ toastr.info('Rows available In Sheet " . (count($activeSheetData)-1) . " '); }); </script>";
						 $row_counter=0;
						 
							foreach ($activeSheetData as $row)
							{							 	
							 	if($row_counter>0 && !empty($row['A']) && $row['A']!='')
							 	{	
										$wef=strtotime($row['C']);
									  $wef = date('Y-m-d', $wef);
							 			 @$sql_p_inert='CALL insert_promotion("'.strtoupper($row['A']).'","'.$row['B'].'","'.$wef.'","'.$row['D'].'","'.$_SESSION['__user_logid'].'");';
							 		
							 			$myDB=new MysqliDb();
							 			$rst=$myDB->rawQuery($sql_p_inert);
							 			$mysqlerror=$myDB->getLastError();
										if($myDB->count > 0)
							 				$insert_row=$insert_row + $myDB->count;
								}
								 $row_counter++;
								
						    }

								echo "<script>$(function(){ toastr.success('No. of Row Uploaded ".$insert_row." '); }); </script>";
					    } 
					    else 
					    {
					        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
					    }
					}
				}
				else
				{
				$btnUploadCheck=1;
				$target_dir = ROOT_PATH.'UploadIncrement/';
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Allow certain file formats
				if($FileType != "xlsx")
				{
				    echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed'); }); </script>";
				    $uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0)
				{
				     echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded'); }); </script>";
				// if everything is ok, try to upload file
				} 
				else {
				    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
				    {
				        echo "<script>$(function(){ toastr.success('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded.'); }); </script>";
				        $document = PHPExcel_IOFactory::load($target_file);
						// Get the active sheet as an array
						$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
						//var_dump($activeSheetData);
						 echo "<script>$(function(){ toastr.info('Rows available In Sheet " . (count($activeSheetData)-1) . " '); }); </script>";
						 $row_counter=0;
						 
							foreach ($activeSheetData as $row)
							{							 	
							 	if($row_counter>0 && !empty($row['A']) && $row['A']!='')
							 	{	
									$wef=strtotime($row['C']);
									$wef = date('Y-m-d', $wef);   
							 			 @$sql_p_inert='CALL insert_increment("'.strtoupper($row['A']).'","'.$row['B'].'","'.$wef.'","'.$row['D'].'","'.$row['E'].'","'.$row['F'].'","'.$_SESSION['__user_logid'].'");';
							 		
							 			$myDB=new MysqliDb();
							 			$rst=$myDB->rawQuery($sql_p_inert);
							 			$mysqlerror=$myDB->getLastError();
										if($myDB->count > 0)
							 				$insert_row=$insert_row + $myDB->count;
								}
								 $row_counter++;
								
						    }

								echo "<script>$(function(){ toastr.success('No. of Row Uploaded ".$insert_row." '); }); </script>";
					    } 
					    else 
					    {
					        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
					    }
					}
				}	
					
			}

?>
		<div  class="alert" id="alert_message">
				    	<div class="container" id="alert_msg"><?php echo $msgFile; ?></div>
				    	<a href="javascript:void(0);" id="alert_msg_close" style="position: absolute;background-image: url('../Style/images/Oxygen480-actions-dialog-close.png');float: right;margin: 0px;padding: 0px;right: 0px;color: royalblue;height: 40px;width: 40px;background-position: 20px;background-size: 20px 20px;background-repeat: no-repeat;top: 0px;"></a></div>
				</div>
</div>

    
<!--Reprot / Data Table End -->	       
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
		}else
		{
			$('#alert_message').delay(10000).fadeOut("slow");
		}
		
		$('#UploadAgain').click(function(){
			$('.pannel_upload').removeClass('hidden');
			$('#UploadAgain').addClass('hidden');
			$('#txt_Type_Upload').val('NA');
		});
		$('#UploadBtn').click(function(){
	        var validate=0;
	        var alert_msg='';		
	        $('#txt_Type_Upload').closest('div').removeClass('has-error');
	        
	        if($('#txt_Type_Upload').val()=='NA')
	        {
				$('#txt_Type_Upload').closest('div').addClass('has-error');
				validate=1;
				alert_msg+='<li> First Select Upload For  </li>';
			}
			if($('#fileToUpload').val()=='')
	        {
				validate=1;
				alert_msg+='<li> First Choose File  </li>';
			}
	        
	      	if(validate==1)
	      	{		      		
	      		$('#alert_msg').html('<ul class="text-danger">'+alert_msg+'</ul>');
	      		$('#alert_message').show().attr("class","SlideInRight animated");
	      		$('#alert_message').delay(10000).fadeOut("slow");
				return false;
			}
			else
			{
				$('#UploadBtn').hide();
				$('#alert_msg').html('<ul class="text-warning"> Wait ! Data Uploading In Process Please Do not Skip or shut down the page ...</ul>');
	      		$('#alert_message').show();
			}
	       
	    });
		<?php 
			if($btnUploadCheck>0)
			{
				?>
					$('.pannel_upload').addClass('hidden');
					$('#UploadAgain').removeClass('hidden');
				<?php 
			}
		
		?>
		
	});
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>