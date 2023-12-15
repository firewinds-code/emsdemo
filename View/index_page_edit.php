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
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
	}
	
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
// Trigger Button-Save Click Event and Perform DB Action
if(isset($_POST['btn_Leave_Save']))
{
	if($_POST['txtUploadFor'] == "Web")
	{
		
	$target_dir = ROOT_PATH.'IndexEditPage/content_current/';
	$pagename = 'index_help';
    $old_dir_name = ROOT_PATH.'IndexEditPage/content_old_'.time().'/';
    
    rename($target_dir,$old_dir_name);
    mkdir($target_dir); 
	if($_POST['txtCarouselType'] == 'single_carousel')
	{
        $target_file = $target_dir . basename($_FILES["img_file_single"]["name"]);
		$uploadOk = 1;
		$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$info = getimagesize($_FILES["img_file_single"]["tmp_name"]);
		if ($info === FALSE) {
		   $uploadOk = 0;
		}
		
		if ($uploadOk == 0) 
		{
			echo "<script>$(function(){ toastr.error(' Sorry, your file was not uploaded'); }); </script>";
		
		} 
		else
		{
			if (move_uploaded_file($_FILES["img_file_single"]["tmp_name"], $target_file)) 
			{
				echo "<script>$(function(){ toastr.success('The file ". basename( $_FILES["img_file_single"]["name"]). " has been uploaded'); }); </script>";
				
				$newFileName = '../IndexEditPage/content_current/'.$pagename.".html";
				$newFileContent = '<img id="blah" src="../IndexEditPage/content_current/'.basename( $_FILES["img_file_single"]["name"]).'" alt="Single Uploaded Img" height="370px" width="100%"/>';

				if (file_put_contents($newFileName, $newFileContent) !== false)
				{
				    echo "<script>$(function(){ toastr.success('Index File edited'); }); </script>";
				}
				else
				{
				    echo "<script>$(function(){ toastr.error('Index File is not edited'); }); </script>";
				}
			}
		}
	}
	elseif($_POST['txtCarouselType'] == 'slide_carousel')
	{
		$total = count($_FILES['img_file_multiple']['name']);
		$uploadCount = 0;
		$newFileContent = '<section class="crousal" style="height: 500px !important;">
						   <div class="crosscover" style="background-position: 100% 100%;background-size: 100% 100%;margin-top: 10px;">
                           <div class="crosscover-list">';
						
		// Loop through each file
		$count_img = 0;
		foreach ($_FILES['img_file_multiple']['name'] as $f => $name) {
		  //Get the temp file path
		  $tmpFilePath = $_FILES['img_file_multiple']['tmp_name'][$f];
		  
		  //Make sure we have a filepath
		  if ($tmpFilePath != ""){
		    //Setup our new file path
		    $newFilePath = $target_dir.$_FILES['img_file_multiple']['name'][$f];

		    //Upload the file into the temp dir
		    if(move_uploaded_file($tmpFilePath, $newFilePath)) {

		      //Handle other code here
		      $count_img++;
			  $target_file = $newFilePath;
		      $ext = pathinfo($target_file, PATHINFO_EXTENSION);
			  rename($target_file,$target_dir.'img'.$count_img.'.'.$ext);
			  
		      $newFileContent .= '<div class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;">
							        <img src="../IndexEditPage/content_current/'.'img'.$count_img.'.'.$ext.'" alt="image'.$f.'"/>
							      </div>';
			 $uploadCount++;

		    }
		  }
		}
		$newFileContent .=' </div>

							  </div>
							  <script>
							  	$(".crosscover").crosscover({
								      controller: false,
								      dotNav: true,
								      inClass:\'lightSpeedIn\',
							  		  outClass:\'lightSpeedOut\'
							  	});
							  </script>
						</section>';
		if($uploadCount > 0)
		{
				echo "<script>$(function(){ toastr.success('The  ".$uploadCount." files has been uploaded'); }); </script>";
				
				$newFileName = '../IndexEditPage/content_current/'.$pagename.".html";
				

				if (file_put_contents($newFileName, $newFileContent) !== false) {
					echo "<script>$(function(){ toastr.success('Index File edited'); }); </script>";
				
				    
				} else {
				    
				    echo "<script>$(function(){ toastr.error('Index File is not edited'); }); </script>";
				
				}
		}
		else
		{
			echo "<script>$(function(){ toastr.error('No file uploaded'); }); </script>";
			
		}
		
	}
	elseif($_POST['txtCarouselType'] == 'text_slide_carousel')
	{	
		$uploadCount = 0;
		$newFileContent = '<section class="crousal" style="height: 370px;">
							<div class="crosscover" style="background-position: 100% 100%;background-size: 100% 100%;margin-top: 10px;    background-color: white;">

						    <div class="crosscover-list">';
		for($i=0;$i < $_POST['txtEditCount'];$i++)
		{
			$newFileContent .= '<div class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;">
							        '.$_POST['TextSlide__'][$i].'
							      </div>';
			$uploadCount++;
		}
		$newFileContent .=' </div>

							  </div>
							  <script>
							  	$(".crosscover").crosscover({
								      controller: false,
								      dotNav: true,
								      inClass:\'lightSpeedIn\',
							  		  outClass:\'lightSpeedOut\'
							  	});
							  </script>
						</section>';
		if($uploadCount > 0)
		{
				echo "<script>$(function(){ toastr.success('The  ".$uploadCount. " files has been uploaded.'); }); </script>";
				$newFileName = '../IndexEditPage/content_current/'.$pagename.".html";
				

				if (file_put_contents($newFileName, $newFileContent) !== false) {
				    echo "<script>$(function(){ toastr.success('Index File edited.'); }); </script>";
				} else {
					echo "<script>$(function(){ toastr.error('Index File is not edited.'); }); </script>";
				}
		}
		else
		{
			echo "<script>$(function(){ toastr.error('No file uploaded.'); }); </script>";
			
		}					    
							    
	}
	elseif($_POST['txtCarouselType'] == 'template_carousel')
	{	
		$uploadCount = 0;
		$newFileContent = '<div class="col s12 m12" style="border: 1px solid #e8e7e7;margin-top: 5px;">'.$_POST['txt_template_hdr'].'</div>';
		$newFileContent .= '<div class="col s12 m12"  style="border: 1px solid #e8e7e7;margin-top: 5px;">'.$_POST['txt_template_body'].'</div>';
		
		if(!empty($newFileContent))
		{
				echo "<script>$(function(){ toastr.success('The  ".$uploadCount. " files has been uploaded.'); }); </script>";
				$newFileName = '../IndexEditPage/content_current/'.$pagename.".html";
				

				if (file_put_contents($newFileName, $newFileContent) !== false) {
					echo "<script>$(function(){ toastr.success('Index File edited.'); }); </script>";
				    
				} else {
					echo "<script>$(function(){ toastr.error('Index File is not edited.'); }); </script>";
				}
		}
		else
		{
			echo "<script>$(function(){ toastr.error('No file uploaded.'); }); </script>";
		}					    
							    
	}
	}
	
	else if($_POST['txtUploadFor'] == "Mobile")
	{
		$target_dir = ROOT_PATH.'IndexEditPage/appimg/';
		$files = glob($target_dir.'*'); // get all file names
		foreach($files as $file)
		{ // iterate files
		  if(is_file($file))
		  {
		    unlink($file); // delete file
		  }
		}
		
		
		if($_POST['txtCarouselType'] == 'single_carousel')
	{
        $target_file = $target_dir . basename($_FILES["img_file_single"]["name"]);
		$uploadOk = 1;
		$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$info = getimagesize($_FILES["img_file_single"]["tmp_name"]);
		if ($info === FALSE) {
		   $uploadOk = 0;
		}
		
		if ($uploadOk == 0) 
		{
			echo "<script>$(function(){ toastr.error(' Sorry, your file was not uploaded'); }); </script>";
		
		} 
		else
		{
			if (move_uploaded_file($_FILES["img_file_single"]["tmp_name"], $target_file)) 
			{
				echo "<script>$(function(){ toastr.success('The file ". basename( $_FILES["img_file_single"]["name"]). " has been uploaded'); }); </script>";
								
			}
		}
	}
	elseif($_POST['txtCarouselType'] == 'slide_carousel')
	{
		$total = count($_FILES['img_file_multiple']['name']);
		$uploadCount = 0;
								
		// Loop through each file
		$count_img = 0;
		foreach ($_FILES['img_file_multiple']['name'] as $f => $name) 
		{
		  //Get the temp file path
		  $tmpFilePath = $_FILES['img_file_multiple']['tmp_name'][$f];
		  
		  //Make sure we have a filepath
		  if ($tmpFilePath != "")
		  {
		    //Setup our new file path
		    $newFilePath = $target_dir.$_FILES['img_file_multiple']['name'][$f];

		    //Upload the file into the temp dir
		    if(move_uploaded_file($tmpFilePath, $newFilePath)) 
		    {

		      //Handle other code here
		      $count_img++;
			  $target_file = $newFilePath;
		      $ext = pathinfo($target_file, PATHINFO_EXTENSION);
			  rename($target_file,$target_dir.'img'.$count_img.'.'.$ext);
			  		      
			  $uploadCount++;

		    }
		  }
		}
		
		if($uploadCount > 0)
		{
			echo "<script>$(function(){ toastr.success('The  ".$uploadCount." files has been uploaded'); }); </script>";
					
		}
		else
		{
			echo "<script>$(function(){ toastr.error('No file uploaded'); }); </script>";
			
		}
		
	}
	
	
	}
	
}
?>



<script src="../ckeditor/ckeditor.js"></script>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Home Page Editer</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Home Page Editer<a class="btn-floating btn-large waves-effect waves-light modal-trigger tooltipped"  href="<?php echo URL.'View/index_page_editlist.php' ;?>" data-position="bottom" target="_blank" data-tooltip="History" id="refer_link_to_anotherPage"><i class="fa fa-external-link fa-2"></i></a></h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >
			<div class="">	
					<div class="input-field col s4 m4 6">
						      
						      <select id="txtUploadFor" name="txtUploadFor">
									<option value="NA">----Select----</option>
									<option value="Web">Web</option>
									<option value="Mobile">Mobile</option>
						      </select>
						      <label for="txtUploadFor" class="active-drop-down active">Upload For</label>
					</div>
					
					<div class="input-field col s6 m6 8" id="main_carousel">
						      
						      <select id="txtCarouselType" name="txtCarouselType">
									
						      </select>
						      <label for="txtCarouselType" class="active-drop-down active">Carousel Type</label>
					</div>
					
					<div class="input-field col s12 m12 hidden all_check_cls" id="single_carousel">
						     <input type="file" name="img_file_single" id="img_file_single"  />
						     <img id="blah" src="../Style/img/slidemain.jpg" alt="your image" height="370px" width="100%"/> 
					</div>
					
					<div class="input-field col s12 m12 hidden all_check_cls" id="slide_carousel" style="overflow: auto;height:300px">
						    <input type="file" name="img_file_multiple[]" id="img_file_multiple"  multiple="true"/>
						    
						    <section class="crousal" style="height: 370px;">
								<div class="crosscover">
								    <div class="crosscover-list">
										<div class="crosscover-item">
										   <img src="../Style/img/slide3.1.jpg" alt="image02"/>
										</div>
										<div class="crosscover-item" >
										   <img src="../Style/img/slider2.png" alt="image04"/>
										</div>
								    </div>
								  </div>
							  <script>
							  	$('.crosscover').crosscover({
								      controller: false,
								      dotNav: true,
								      inClass:'lightSpeedIn',
							  		  outClass:'lightSpeedOut'
							  	});
							  </script>
						    </section>
					</div>
					
					<div class="col s12 m12 no-padding hidden all_check_cls" id="text_slide_carousel">
						     <div class="input-group col s12 m12">
							     <input type="number" id="txtEditCount" class="form-control" name="txtEditCount"/>
							     <button name="addTextarea" class="btn btn-danger input-group-addon" id="addTextarea" type="button">
							      <i class="fa fa-edit"></i>Create Field
							     </button>
						     </div>
						     <div class="col s12 m12 l12" id="txt_input_container">
						     	
						     </div>
					</div>
					
					<div class="input-field col s12 m12 hidden all_check_cls" id="template_carousel">
						    <div>
						    		<h4>Header</h4>
						           <textarea class="form-control" name="txt_template_hdr" id="txt_template_hdr" ></textarea>
							</div>
							<div >
								   <h4>Body</h4>
						           <textarea name="txt_template_body" id="txt_template_body" ></textarea>
							</div>
					</div>
					
					<div class="input-field col s12 m12 right-align">
						<button type="submit" name="btn_Leave_Save" id="btn_Leave_Save" class="btn waves-effect waves-green">
						Update Request</button>
			  		</div>
			  		
            </div>
      </div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>

<script>
var crousal_image_files=[];
$(function(){
		
	$('#txtCarouselType').on('change',function(){
		$(".all_check_cls").addClass('hidden');
		if($(this).val() != 'NA' && $(this).val() != '')
		{
			$("#"+$(this).val()).removeClass('hidden');
		}
		$("txt_input_container").html("");
	});
	
	$('#txtUploadFor').on('change',function(){
		$(".all_check_cls").addClass('hidden');
		if($(this).val() != 'NA' && $(this).val() != '')
		{
			$("#"+$(this).val()).removeClass('hidden');
		}
		$("txt_input_container").html("");
		
		$('#txtCarouselType').empty();	
		
		$('#txtCarouselType').append(new Option("---Select---","NA"));
		if($('#txtUploadFor').val()=='Web')
		{
			$('#txtCarouselType').append(new Option("Single Image","single_carousel"));
			$('#txtCarouselType').append(new Option("Slide Image","slide_carousel"));
			$('#txtCarouselType').append(new Option("Text Slider","text_slide_carousel"));
			$('#txtCarouselType').append(new Option("Template Text","template_carousel"));
		}
		else if($('#txtUploadFor').val()=='Mobile')
		{
			$('#txtCarouselType').append(new Option("Single Image","single_carousel"));
			$('#txtCarouselType').append(new Option("Slide Image","slide_carousel"));
		}
	});
	
	$("#addTextarea").click(function(){
		if($("#txtEditCount").val() > 5 ||  $("#txtEditCount").val() < 1)
		{
			alert('Count could not be greater then 5');
			$("#txt_input_container").html("");
		}
		else
		{
			var string_textarea = '';
			
			for(i = 1;i <= $("#txtEditCount").val() ;i++ )
			{
				string_textarea += '<textarea name="TextSlide__[]" id="text_slide_'+i+'"></textarea>';
				
			}
			$("#txt_input_container").html(string_textarea);
			for(i = 1;i <= $("#txtEditCount").val() ;i++ )
			{
				 CKEDITOR.replace( 'text_slide_'+i,{
						height: 250,
						toolbar :'Full'
						
				});
			}
		}
	});
	CKEDITOR.replace( 'txt_template_body',{
		height: 250,
		toolbar :'Full'
	});
	CKEDITOR.replace( 'txt_template_hdr',{
		height: 50,
		toolbar :'Full'
	});
	$("#img_file_single").change(function(){
	    readURL(this);
    });

	$('#img_file_multiple').change(function(v){
		$('.crosscover-list').html("");
		 $.each(v.target.files,function(n,i){
		    var reader = new FileReader(); //need to create new ones...they get busy..
		    reader.readAsDataURL(i);
		    reader.onload = (function(i) {
		        return function(x) {
		            crousal_image_files.push({file:i,data:x.target.result});
		            updateList(crousal_image_files.length-1);
		        }
		    })(i);
		 });
	});
});

function updateList(n){
    var e = $('.crosscover-list');
    var _strpage = '<div class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;"><img src="'+crousal_image_files[n].data+'" alt="image'+n+'"/></div>';
    e.append(_strpage);
}

function readURL(input) 
{
	  if (input.files && input.files[0])
	  {
	    var reader = new FileReader();
	    reader.onload = function(e)
	    {
	      $('#blah').attr('src', e.target.result);
	    }
	    reader.readAsDataURL(input.files[0]);
	  }
}
$.fn.extend({
	  crosscover: function() {
	    return true;
	  }
});		
$('select').formSelect(); 					
</script>

<script>
	
	$(document).ready(function(){
		
		$('#btn_Leave_Save').click(function(){
		
			validate=0;
			if($('#txtUploadFor').val()=="NA")
			{
				validate = 1;
				$('#txtUploadFor').addClass('has-error');
			 	$('#txtUploadFor').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if($('#span_txtUploadFor').size() == 0)
				{
				   $('<span id="span_txtUploadFor" class="help-block">Require *</span>').insertAfter('#txtUploadFor');
				}
			}
			
			if($('#txtCarouselType').val()=="NA")
			{
				validate = 1;
				$('#txtCarouselType').addClass('has-error');
			 	$('#txtCarouselType').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
				if($('#span_txtCarouselType').size() == 0)
				{
				   $('<span id="span_txtCarouselType" class="help-block">Require *</span>').insertAfter('#txtCarouselType');
				}
			}
			
			if(validate==1)
		  	{
				return false;
			}	
			
		});
		
	});
	
</script>

<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>