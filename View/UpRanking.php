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

$msgFile ='';
$insert_row=$btnUploadCheck=0;
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Upload Rank</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Upload Rank<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="#myModal_content" data-position="bottom" data-tooltip="Download Formate"><i class="material-icons">file_download</i></a></h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
	   	 <div class="input-field col s5 m5">
	            <select id="txt_Type_Upload" name="txt_Type_Upload" >
	            	<option value="NA">----Select----</option>
	            	<option value="Detail">Detail</option>
	            	<option value="MIS">MIS</option>
	            </select>
	     	    <label for="txt_Type_Upload" class="active-drop-down active">Upload For</label>
	      </div>
	      
	      <div class="file-field input-field col s5 m5">
			<div class="btn">
				<span>Upload File</span>
				<input type="file" id="fileToUpload" name="fileToUpload" style="text-indent: -99999em;" >
				<br>
				<span class="file-size-text">Accepts up to 2MB</span>
			</div>
			<div class="file-path-wrapper" >
				 <input class="file-path" type="text" style="">						    
			</div>
		 </div>
		
	    <div class="input-field col s12 m12 right-align">
	       <input  type="submit" name="UploadBtn" id="UploadBtn" value="Upload File" class="btn waves-effect waves-green"/>
	       <input  type="button" name="UploadAgain" id="UploadAgain" value="Upload Again" class="btn waves-effect waves-green hidden"/>
	    </div>

<!--Form element model popup End-->
<!--Reprot / Data Table start -->
	    <div id="pnlTable">	    
    
		<?php 
			if(isset($_POST['UploadBtn']))
			{		
				$btnUploadCheck=1;
				$target_dir = ROOT_PATH.'Upload/';
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
				
				// Check if file already exists
				/*if (file_exists($target_file)) {
				    $msgFile =$msgFile."<p  class='msgFile text-danger'>Sorry, file already exists.</p>";
				    $uploadOk = 0;
				}*/
				// Check file size
				if ($_FILES["fileToUpload"]["size"] > 5000000)
				{
				    echo "<script>$(function(){ toastr.error('Sorry, your file is too large.'); }); </script>";
				    $uploadOk = 0;
				}
				// Allow certain file formats
				if($FileType != "xlsx")
				{
				    echo "<script>$(function(){ toastr.error('Sorry, only XLS and XLSX files are allowed.'); }); </script>";
				    $uploadOk = 0;
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
				    echo "<script>$(function(){ toastr.error('Sorry, your file was not uploaded.'); }); </script>";
				// if everything is ok, try to upload file
				} else {
				    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				        
				        echo "<script>$(function(){ toastr.error('The file ".basename( $_FILES["fileToUpload"]["name"])." has been uploaded'); }); </script>";
				        $document = PHPExcel_IOFactory::load($target_file);
						// Get the active sheet as an array
						$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
						//var_dump($activeSheetData);
						 echo "<script>$(function(){ toastr.error('Rows available In Sheet : ".(count($activeSheetData)-1)."'); }); </script>";
						 $row_counter=0;
						foreach ($activeSheetData as $row)
						{							 	
						 	if($row_counter>0 && !empty($row['A']) && $row['A']!='')
						 	{	
						 	   $sql_p_inert='CALL insert_rank("'.strtoupper($row['A']).'","'.$row['B'].'","'.$_POST['txt_Type_Upload'].'","'.$row['C'].'","'.$row['D'].'","'.$_SESSION['__user_logid'].'");';
					 			$myDB=new MysqliDb();
					 			$rst=$myDB->rawQuery($sql_p_inert);
					 			$mysqlerror=$myDB->getLastError();
					 			if(!empty($mysqlerror))
					 			{
									echo "<script>$(function(){ toastr.error('Error In Query ".$mysqlerror."'); }); </script>";
								}
								if($myDB->count > 0)
					 				$insert_row=$insert_row + $myDB->count;
						    }
							$row_counter++;
					     }
						echo "<script>$(function(){ toastr.success('No. of Row Uploaded ".$insert_row."'); }); </script>";
					
					
				 	/*else if($_POST['txt_Type_Upload']=='Attendance')
					{
						foreach ($activeSheetData as $row) {							 	
						 	if($row_counter>0 && !empty($row['A']) && $row['A']!='')
						 	{	
						 			
						 			$sql_p_inert='CALL insert_attendance("'.$row['A'].'","'.$row['B'].'","'.$row['C'].'","'.$row['D'].'","'.$row['E'].'","'.$row['F'].'","'.$row['G'].'","'.$row['H'].'","'.$row['I'].'","'.$row['J'].'","'.$row['K'].'","'.$row['L'].'","'.$row['M'].'","'.$row['N'].'","'.$row['O'].'","'.$row['P'].'","'.$row['Q'].'","'.$row['R'].'","'.$row['S'].'","'.$row['T'].'","'.$row['U'].'","'.$row['V'].'","'.$row['W'].'","'.$row['X'].'","'.$row['Y'].'","'.$row['Z'].'","'.$row['AA'].'","'.$row['AB'].'","'.$row['AC'].'","'.$row['AD'].'","'.$row['AE'].'","'.$row['AF'].'","'.$row['AG'].'","'.$row['AH'].'","'.$row['AI'].'", "'.$_SESSION['__user_logid'].'");';
						 			
						 			$myDB=new MysqliDb();
						 			$rst=$myDB->query($sql_p_inert);
						 			$mysqlerror=$myDB->getLastError();
						 			if(!empty($mysqlerror))
						 			{
										echo '<p class="msgFile text-danger"> Error In Query :<kbd>'.mysql_errno().'</kbd> (<code>'.$mysqlerror.'</code>)</p>';
									}
									if(mysql_affected_rows()>0)
						 				$insert_row=$insert_row + mysql_affected_rows();
									     
							}
							$row_counter++;
				    	}
						$msgFile=$msgFile.'<p class="msgFile text-success" >No. of Row Uploaded :: <kbd>'.$insert_row.'</kbd></p>';
						
					}*/
				    
			      } 
			      else
			     {
			        echo "<script>$(function(){ toastr.error('Sorry, there was an error uploading your file.'); }); </script>";
			     }
			}
	     }		
		?>
		
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