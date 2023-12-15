<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH.'AppCode/nHead.php');
$alert_msg ='';
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(isset($_POST['btn_Issue_Save']))
{
	
	$SubType=$_POST['SubType'];
	if($SubType!="" && $_POST['StatusAH']!="" && $_POST['TerminationDate']!="")
	{
		
		if(is_array($_FILES)) 
	{
	$count=0;
		foreach ($_FILES['txt_doc_name_']['name'] as $name => $value)
		{
		$count++;
		if(is_uploaded_file($_FILES['txt_doc_name_']['tmp_name'][$name]))
		{
		$sourcePath = $_FILES['txt_doc_name_']['tmp_name'][$name];
		$targetPath = ROOT_PATH."wt_docs/".basename($_FILES['txt_doc_name_']['name'][$name]);
		//$targetPath = $_SERVER['DOCUMENT_ROOT'].'ems/'."wt_docs/".basename($_FILES['txt_doc_name_']['name'][$name]);
		//$targetPath = URL."ir_Documents/".basename($_FILES['txt_doc_name_']['name'][$name]);
		$FileType = pathinfo($targetPath,PATHINFO_EXTENSION);
//		$val_stype = trim($_POST['txt_doc_stype_'.$count]);
		$uploadOk = 1;
		$validation_check = 0;
		if ($_FILES['txt_doc_name_']['size'][$name] > 400000) 
		{
		    echo "<script>$(function(){ toastr.error('Sorry, your file is too large. Accepts up to 400KB File only.'); }); </script>";
		    $uploadOk = 0;
		}
			if($validation_check === 0)
			{
				if(move_uploaded_file($sourcePath,$targetPath)) 
				{
					$ext = pathinfo(basename($_FILES['txt_doc_name_']['name'][$name]), PATHINFO_EXTENSION);
					$filename=$_POST['EmployeeID'].'_'.preg_replace('/\s+/','',$_POST['txt_doc_value_'.$count]).'_'.date("dmYhis").'.'.$ext;
					//$file=rename($targetPath,$_SERVER['DOCUMENT_ROOT'].'ems/wt_docs/'.$filename);
					$file=rename($targetPath,ROOT_PATH.'wt_docs/'.$filename);
							
				
					 $Insert="Call insert_TerminationData('".$_POST['EmployeeID']."','".$_POST['EmployeeName']."','".$_POST['Process']."','".$_POST['SubProcess']."','".$_POST['AccountHead']."','".$_POST['Clientname']."','".$_POST['Designation']."','".$_POST['ReportTo']."','".addslashes($_POST['remark'])."','".$_POST['TerminationDate']."','".$_POST['StatusAH']."','".$_POST['DOJ']."','".$SubType."',@SRID_)";
					$myDB=new MysqliDb();
					$myDB->rawQuery($Insert);
						$result2 =$myDB->rawQuery("SELECT @v_id1");
						$LastID = '';
						foreach($result2 as $key=>$value)
						{
						 $LastID=$value['@v_id1'];
						}
					$mysql_error = $myDB->getLastError();
					$rowCount = $myDB->count;
					if(empty($mysql_error) && $LastID!='')
					{
				//////////////////////////////////////////////////////////////////////////////////
						$file_counter = 0;
						$myDB=new MysqliDb();
	 $sqlInsertDoc="insert into warning_rth_documents(EmployeeID,DataId,Document,Title,UploadedBy,`By`,UploadedDate)values('".$_POST['EmployeeID']."','".$LastID."','".$filename."','".$_POST['txt_doc_value_'.$count]."','".$_SESSION['__user_logid']."','AH',Now());";
							$result=$myDB->rawQuery($sqlInsertDoc);
				//////////////////////////////////////////////////////////////////////////////////
						echo "<script>$(function(){ toastr.success('Request Accepted Successfully.'); }); </script>";
					}
					else
					{
						echo "<script>$(function(){ toastr.success('Request not Accepted.'.$mysql_error) }); </script>";
					}
				
				}
				
				
			}
		}
	}
	}
	
     
	}
	else
	{
		echo "<script>$(function(){ toastr.success('Please select all values.'); }); </script>";
	}
}
?>
<script>
	$(document).ready(function(){
		//$('#TerminationDate').datetimepicker({timepicker:false,format:'Y-m-d', minDate: 0, maxDate: '+3D'});
		$('#TerminationDate').datetimepicker({timepicker:false,format:'Y-m-d',minDate: 0, maxDate:'10D'});
		$('#txt_doc_name_1').change(function()
		{
			var f=this.files[0]
			if(f.size > 400000)
			{
				alert("Allowed file size exceeded. (Max. 2 MB)");
			}
		})
		$('#myTable').DataTable({
			dom: 'Bfrtip',
			"iDisplayLength": 25,		        
			scrollCollapse: true,
			lengthMenu: [
			    [ 5,10, 25, 50, -1 ],
			    ['5 rows' ,'10 rows', '25 rows', '50 rows', 'Show all' ]
			],
			 buttons: [
			       /*{
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
			        },
			        /*'copy',*/
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
<div id="content" class="content" > 
<span id="PageTittle_span" class="hidden">Refer to HR</span>
<div class="pim-container row" id="div_main" >

<div class="form-div">
	 <h4>Refer to HR Request</h4>

	<div class="schema-form-section row" >
        <div id="myModal_content" class="modal modal_big ">
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Refer to HR Request</h4>
		      <div class="modal-body" style="max-height: 100%;float:left;overflow: auto; width: 100%">
                <div class="col s12 m12">
				 <div class="input-field col s6 m6">
				    <input type="text" class="form-control" readonly id="EmployeeID" name="EmployeeID" required/>
				    <input type="hidden" id="Process" name="Process" />
				    <input type="hidden" id="SubProcess" name="SubProcess" />
				    <input type="hidden" id="AccountHead" name="AccountHead" />
				    <input type="hidden" id="Clientname" name="Clientname" />
				    <input type="hidden" id="Designation" name="Designation" />
				    <input type="hidden" id="ReportTo" name="ReportTo" />
				    <input type="hidden" id="DOJ" name="DOJ" />
				    
				    <label for="empID">Employee ID</label>
			    </div>
			    <div class="input-field col s6 m6">
				    <input type="text" class="form-control" readonly id="EmployeeName" name="EmployeeName" required/>
				    <label for="empID">Employee Name</label>
			    </div>
			    <div class="input-field col s4 m4">
				            <select id="StatusAH" name="StatusAH" required>
					            <option value="NA">---Select---</option>
					            <option value="Refer to QH">Refer to HR</option>				
					            <!--<option value="Warnig Letter">Warnig Letter</option>-->				         
				            </select>
				            <label for="StatusAH"  class="active-drop-down active">Request Type</label>
			    </div>
			    <div class="input-field col s4 m4" id="SubType2">
				            <select id="SubType" name="SubType" required>
					            <option value="NA">-Select-</option>
				            </select>
				      <label for="SubType"  class="active-drop-down active">Sub Type</label>      
			    </div>
			    <div class="input-field col s4 m4" id="OtherSubType2" style="display: none;" >
				            <input type="text" class="form-control"  id="OtherSubType" name="OtherSubType">
				      <label for="OtherSubType"  class="active-drop-down active"> Other Sub Type
				      <a class="tip-top" title="" data-original-title="Process List">
					<img src="../Style/img/back.jpg" id="back" width="17px;" style="line-height: 10px;"></a></label>      
			    </div>
			     <div class="input-field col s4 m4">
				    <input type="text" class="form-control" id="TerminationDate" name="TerminationDate" required/>
				    <label for="empID">Date</label>
			    </div>
			    
			    
			    <div class="input-field col s12 m12">
							<div class="form-group">
								<button type="button" name="btn_docAdd" id="btn_docAdd" title="Add Doc Row in Table Down" class="btn waves-effect waves-green" ><i class="fa fa-plus"></i>  Add Document</button>
								<button type="button" name="btnDoccan" id="btnDoccan" title="Remove Doc Row in Table Down"  class="btn waves-effect waves-red close-btn" ><i class="fa fa-minus"></i>  Remove Document</button>
							</div>
				</div>
					<table class="table table-hovered table-bordered" id="childtable">
					<thead class="bg-danger">
						<tr>
							<th class="hidden">Doc ID</th>
							<th>Document File</th>
							<th>Document Name</th>
						</tr>
					</thead>
					<tbody>
						<tr class="trdoc" id="trdoc_1" >
						    <td class="doccount hidden">1</td>
						<td><input name="txt_doc_name_[]" type="file" required id="txt_doc_name_1" class="form-control clsInput file_input" /></td>
						<td><input type="text" value="" required  name="txt_doc_value_1" id="txt_doc_value_1"/></td>
						</tr>
					</tbody>
					</table>
			    </div>
			    <!--////////////////////////////////-->
			    <div class="input-field col s12 m12">
					<ul class="collapsible">
					<li>
					<div class="collapsible-header topic">Previous History</div>
					<div class="collapsible-body">
						<div id="PreviousHistory" class="list-container">
						</div>
					</div>
					</li>

				</div>
<!--////////////////////////////////-->
			    <div class="input-field col s12 m12">
					<textarea name="remark" id="remark" required minlength="300" class="materialize-textarea"></textarea>
					<label for="remark">AH Remark</label>
				</div>
				<SPAN ID='A'></SPAN>
			    <div class="input-field col s12 m12">
				    <input type="hidden" class="form-control hidden" id="empid"  name="empid"/>
				    <button type="submit" name="btn_Issue_Save" id="btn_Issue_Save" class="btn waves-effect waves-green">Submit</button>
			<button type="submit" name="btn_Issue_Edit" id="btn_Issue_Edit" class="btn waves-effect waves-green hidden">Save</button>
<button type="button" name="btn_Issue_Can" id="btn_Issue_Can" class="btn waves-effect modal-action modal-close waves-red close-btn">Cancel</button>
			     </div>
		     </div>
		    </div>
        </div>
        </form>
      <!-- <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="search_form">-->
      <form method="post">
        <div class="card left col s12 m12">
			<div class="input-field col s10 m10">
			      	 <input type='text' name="p_EmpID" id="p_EmpID"><label for="p_EmpID"> Employee ID</label>
			</div>
			<div class="ibtn btn-danger btn-lg">
		<button type="submit" name="btnSearch"  id="btnSearch" data-toggle="modal" class="btn waves-effect waves-green" >Search</button>
			</div>
		</div>
	
		<div id="pnlTable">
			    <?php
			    if(isset($_POST['btnSearch']))
			    {
				$sqlConnect = " call `getEmpForTermination`('".$_POST['p_EmpID']."','".$_SESSION['__user_logid']."')";
					$myDB=new MysqliDb();
					$result=$myDB->rawQuery($sqlConnect);
					$mysql_error = $myDB->getLastError();
					if(empty($mysql_error)){?>
			   			 <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="80%">
						    <thead>
						        <tr>
						            <th>Employee ID</th>
						            <th>Employee Name</th>	
						            <th>Designation</th>
						            <th>DOJ</th>
						            <th style="text-align: center">Process</th>
						           <th>Account Head</th>
						            <th>Report To</th>
						            <th>Action</th>
						        </tr>
						    </thead>
					    <tbody>					        
					       <?php				       
					        foreach($result as $key=>$value){
							echo '<tr>';							
							echo '<td class="EmployeeID">'.$value['EmployeeID'].'</td>';
							echo '<td class="EmployeeName">'.$value['EmployeeName'].'</td>';
							echo '<td class="designation">'.$value['designation'].'</td>';
							echo '<td class="doj">'.date("d-m-Y", strtotime($value['DOJ'])).'</td>';
							echo '<td class="sub_process">'.$value['sub_process'].'</td>';
							echo '<td class="Process hidden">'.$value['Process'].'</td>';
							echo '<td class="Clientname hidden">'.$value['clientname'].'</td>';
							echo '<td class="account_head">'.$value['account_headName'].'</td>';
							echo '<td class="ReportTo">'.$value['ReportToName'].'</td>';
							echo '<td class="manage_item" ><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" onclick="javascript:return EditData(this);" id="'.$value['EmployeeID'].'" data-position="left" data-tooltip="Edit">ohrm_edit</i> </td>';
							echo '</tr>';
							}	
							?>			       
					    </tbody>
						</table>
						<?php
					 }
}

					?>
				</div>
	</div>
  </div>
</div>
</div>

<script>
$('.modal').modal({
	onOpenStart:function(elm)
	{
		
	},
	onCloseEnd:function(elm)
	{
		$('#btn_Client_Can').trigger("click");
	}
});
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
$('#btn_Issue_Can').on('click', function()
{
	$('#StatusAH').val('NA');
	$('#TerminationDate').val('');
	$('#remark').val('');
	$('#txt_doc_value_1').val('');
	
	 $($('[id^=trdoc_]')).each(function(){
	 	if(this.id!='trdoc_1'){
			$(this).remove();  
		}
     });
	$('#btn_Issue_Save').removeClass('hidden');
	$('#btn_Issue_Edit').addClass('hidden');
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
    
/*$('#btn_Issue_Edit,#btn_Issue_Save').on('click', function(){*/
	$('#btn_Issue_Save').on('click', function(){
        var validate=0;
        var alert_msg='';
        // <!-- add this attribute for full msg in error data-error-msg="Client Name Required, Should not be empty."-->
        $("input,select,textarea").each(function(){
        	var spanID =  "span" + $(this).attr('id');		        	
        	$(this).removeClass('has-error');
        	if($('#remark').val()!="")
	        {
	        	str=$('#remark').val();
				var repeats = /(.)\1{3,}/;
				 if(repeats.test(str)){
					 validate=1;
					 $('#A').addClass("has-error help-block");
					 $('#A').html('Remark should not contain Repeat character.');
				            validate=1;	
				 }
				  var str1 = $('#remark').val();
			    str2='<';
			     str3='>';
			  	 if(str1.indexOf(str2) != -1 || str1.indexOf(str3) != -1){ 
			  	  	$('#A').addClass("has-error help-block");
			  	 	 $('#A').html('Remark should not contain "<" or  ">" character.');
			  	 	validate=1;	
			  	 }
			}
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
			return false;
		}
       
    });

// This code for trigger edit on all data table also trigger model open on a Model ID
function EditData(el)
{
	//alert(el.id);
	var tr = $(el).closest('tr');
	var EmployeeID_ = tr.find('.EmployeeID').text();
	var EmployeeName = tr.find('.EmployeeName').text();
	var Process = tr.find('.Process').text();
	var sub_process = tr.find('.sub_process').text();
	var account_head = tr.find('.account_head').text();
	var clientname = tr.find('.Clientname').text();
	var designation = tr.find('.designation').text();
	var ReportTo = tr.find('.ReportTo').text();
	var DOJ = tr.find('.doj').text();
	$('#DOJ').val(DOJ);
	$('#EmployeeID').val(EmployeeID_);
	$('#EmployeeName').val(EmployeeName);
	$('#Process').val(Process);
	$('#SubProcess').val(sub_process);
	$('#AccountHead').val(account_head);
	$('#Clientname').val(clientname);
	$('#Designation').val(designation);
	$('#ReportTo').val(ReportTo);
	$('#myModal_content').modal('open');
        $("#myModal_content input,#myModal_content textarea").each(function(index, element) {
        	
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
		$.ajax({ url: <?php echo '"'.URL.'"';?>+"View/BingPreviousHistoryForTermination.php?empid="+EmployeeID_ }).done(function(data) {
					$('#PreviousHistory').html(data)      
				});
}

$('#btn_docAdd').click(function()
{
	
				$count=$(".trdoc").length;
				$id="trdoc_"+parseInt($count+1);
				$('#doc_child').val(parseInt($count+1));
				$tr=$("#trdoc_1").clone().attr("id",$id);
				$('#childtable tbody').append($tr);
				$tr.children("td:first-child").html(parseInt($count+1));
		$tr.children("td:nth-child(2)").children("input").attr({"id":"txt_doc_name_"+parseInt($count+1),"name":"txt_doc_name_[]"}).val('');
		$tr.children("td:nth-child(3)").children("input").attr({"id":"txt_doc_value_"+parseInt($count+1),"name":"txt_doc_value_"+parseInt($count+1)}).empty();
});				
$('#btnDoccan').click(function(){
	$count=$(".trdoc").length;
	if($count>1)
	{
		$('#childtable tbody').children("tr:last-child").remove();
		$('#doc_child').val(parseInt($count-1));
	}			
});  
$('#StatusAH').change(function(){
	
	 if($('#StatusAH').val()=='Refer to QH')
	 {
		 $.ajax({
		  url:"../Controller/getTerminationsubtype.php"
		}).done(function(data) { // data what is sent back by the php page
			$('#SubType').html(data);
			$('select').formSelect();	
		});
	 }
	/* else if($('#StatusAH').val()=='Warnig Letter')
	 {
	 	$("#SubType").empty().append("<option value='NA'>Select Sub Type</option><option>Call Disconnection</option><option>Unplanned Leave</option><option>No Response On Call</option><option>Bottom Performance</option><option>Behavior Issue</option><option>Insubordination</option><option>Other</option>");
	 }*/
	 else{
	 	$("#SubType").empty().append("<option value='NA'>Select Sub Type</option>");
	 }
})

	$('#back').click(function() {
		    $("#SubType2").show();
			$("#OtherSubType2").hide();$("#OtherSubType").val('');
	})
</script>
 <script type="text/javascript">
   function Download(el)
	{
				if($(el).attr("data")!='')
				{
					function getImageDimensions(path,callback){
					    var img = new Image();
					    img.onload = function(){
					        callback({
					            width : img.width,
					            height : img.height,
					            srcsrc: img.src
					        });
					    }
					    img.src = path;
					}

					$.ajax({
					    //url:"../Docs/"+$(el).attr("data"),
					    url:"../wt_docs/"+$(el).attr("data"),
					    type:'HEAD',
					    error: function()
					    {
					        alert('No File Exist');
					    },
					    success: function()
					    {
					    	imgcheck = function(filename){
								return (filename).split('.').pop();
							}
							//imgchecker = imgcheck("../Docs/"+$(el).attr("data"));
							imgchecker = imgcheck("../wt_docs/"+$(el).attr("data"));
							
							if(imgchecker.match(/(jpg|jpeg|png|gif)$/i))
							{
								//getImageDimensions("../Docs/"+$(el).attr("data"),function(data){
									getImageDimensions("../wt_docs/"+$(el).attr("data"),function(data){
								    var img = data;	
								    							
								    $('<img>', {
							        src: "../wt_docs/"+$(el).attr("data")
								    }).watermark({
									    	//text: '? For Cogent E Services Pvt. Ltd.',
									    	text: 'Cogent E Services Pvt. Ltd.',
										    //path:'../Style/images/cogent-logobkp.png',
										    textWidth: 370,
										    opacity: 1,
										    textSize: (img.height/15) ,
										    nH: img.height,
										    nW: img.width,
										    textColor: "rgb(0,0,0,0.4)",			    
										    outputType:'jpeg',
										    gravity:'sw',
									        done: function (imgURL) {
									            var link = document.createElement('a');
												link.href = imgURL;
												link.download = $(el).attr("data");
												document.body.appendChild(link);
												link.click();		
									        }
									});
								});	
							}
							else if(imgchecker.match(/(pdf)$/i))
							{
						window.open("../FileContainer/pdf_watermark/watermark-edit-existing-pdf.php?src="+"../../wt_docs/"+$(el).attr("data"));
							}
							else
							{
								//window.open("../Docs/"+$(el).attr("data"));
								window.open("../wt_docs/"+$(el).attr("data"));
							}
					        
					    }
					});
					
					/*$('.schema-form-section img').watermark({
					    
				  	});*/
					
				}
				else
				{
					alert('No File Exist');
				}
	}
  </script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>



