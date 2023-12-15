<?php
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$EmployeeID = $_SESSION['__user_logid'];
$createBy = $_SESSION['__user_logid'];
$myDB = new MysqliDb();

if($EmployeeID=='CE07147134')
{
	$getEmployees = "select t1.EmployeeID,t1.EmployeeName,concat(clientname,'|',Process,'|',sub_process) as `Dept`,cm_id, designation, ReportTo,t2.EmployeeName as `ReportToName` from whole_details_peremp t1 join personal_details t2 on t1.ReportTo=t2.EmployeeID where TH='" . $EmployeeID . "' or OH ='" . $EmployeeID . "' or QH ='" . $EmployeeID . "' or ReportTo ='" . $EmployeeID . "' order by EmployeeName";
}
else
{
	$getEmployees = "select t1.EmployeeID,t1.EmployeeName,concat(clientname,'|',Process,'|',sub_process) as `Dept`,cm_id, designation, ReportTo,t2.EmployeeName as `ReportToName` from whole_details_peremp t1 join personal_details t2 on t1.ReportTo=t2.EmployeeID where TH='" . $EmployeeID . "' or OH ='" . $EmployeeID . "' or QH ='" . $EmployeeID . "' order by EmployeeName";	
}

$allEmp = $myDB->rawQuery($getEmployees);


if (isset($_POST['submit'])) {
    //echo "<pre>";print_r($_POST);exit;

    $Query = "insert into corrective_action_form (employee_id, employee_name, issued_date, position, department, supervisor_name, issue_type, description_of_issue, created_by) VALUES ('" . $_POST['employee_id'] . "','" . $_POST['employee_name'] . "','" . $_POST['issued_date'] . "','" . $_POST['position'] . "','" . $_POST['department'] . "','" . $_POST['supervisor_name'] . "','" . $_POST['issue_type'] . "','" . addslashes(trim($_POST['description_of_issue'])) . "','" . $EmployeeID . "')";

    $result_all = $myDB->rawQuery($Query);
    $mysql_error = $myDB->getLastError();
    $last_id = $myDB->getInsertId();
	if (empty($mysql_error)) {
    // Configure upload directory and allowed file types 
	echo "<script>$(function(){ toastr.success('Request Save Successfully '); }); </script>";
    $upload_dir = ROOT_PATH . 'corrective_action_form/';
    $allowed_types = array('jpg', 'png', 'jpeg','pdf','msg');

    // Define maxsize for files i.e 2MB 
    $maxsize = 10 * 1024 * 1024;

    // Checks if user sent an empty form  
    if (!empty(array_filter($_FILES['txt_doc_name']['name']))) {

        // Loop through each file in files[] array 
        foreach ($_FILES['txt_doc_name']['tmp_name'] as $key => $value) {

            $file_tmpname = $_FILES['txt_doc_name']['tmp_name'][$key];
            $file_name = $_FILES['txt_doc_name']['name'][$key];
            $file_size = $_FILES['txt_doc_name']['size'][$key];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

            // Set upload file path 
            $filepath = $upload_dir . $file_name;

            // Check file type is allowed or not 
            if (in_array(strtolower($file_ext), $allowed_types)) {

                // Verify file size - 2MB max  
                if ($file_size > $maxsize)
                    echo "Error: File size is larger than the allowed limit.";

                // If file with name already exist then append time in 
                // front of name of the file to avoid overwriting of file 
                $fileName = time() . $file_name;
                //$fileurl =URL.'corrective_action_form/'.$fileName;
                $filepath = $upload_dir . $fileName;

                if (move_uploaded_file($file_tmpname, $filepath)) {

                    $fileQuery = "insert into corrective_action_form_files (corrective_action_form_id, file_path) VALUES ('" .  $last_id . "','" . $fileName . "')";
                    $myDB->rawQuery($fileQuery);
                    $mysql_error_check = $myDB->getLastError();

                    echo "<script>$(function(){ toastr.success('{$file_name} successfully uploaded <br /> '); }); </script>";
                } else {
                    //echo "Error uploading {$file_name} <br />";
                    echo "<script>$(function(){ toastr.warning('Error uploading {$file_name}'); }); </script>";
                }
            } else {

                // If file extention not valid 
                echo "<script>$(function(){ toastr.warning('Error uploading {$file_name}'); }); </script>";
                echo "<script>$(function(){ toastr.warning('{$file_ext} file type is not allowed'); }); </script>";
            }
        }
    }

    
        
       
    } else {
        echo "<script>$(function(){ toastr.warning('Request Not Save'); }); </script>";
    }
    $location = URL . 'View/correctiveActionForm.php';
    echo "<script> window.location(" . $location . ")</script>";
}
?>


<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">CORRECTIVE ACTION FORM</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>CORRECTIVE ACTION FORM</h4>
            <div class="schema-form-section row">
                <form action="<?php echo URL . 'View/correctiveActionForm.php'; ?>" method="POST" enctype="multipart/form-data">
                    <div>
                        <input type="hidden" name="employee_name" id="employee_name">
                        <!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
                        <div class="input-field col s6 m6 8">

                            <select class="selectClass" id="employee_id" name="employee_id">
                                <option value="NA">Select Employee </option>
                                <?php foreach ($allEmp as $e) { ?>
                                    <option value="<?php echo $e['EmployeeID'] ?>" department="<?php echo $e['Dept'] ?>" position="<?php echo $e['designation'] ?>" supervisor_name="<?php echo $e['ReportToName'] ?>"employee_name="<?php echo $e['EmployeeName'] ?>" ><?php echo $e['EmployeeName'] . '(' . $e['EmployeeID'] . ')'; ?></option>

                                <?php } ?>
                            </select>

                            <label title="" for="issue_type" class="active-drop-down  active">Employee Name/ID</label>
                        </div>

                    </div>
                    <div>
                        <!--<label for="txt_ED_joindate_from">Joinning Date :</label>-->
                        <div class="input-field col s6 m6 8">

                            <input type="text" id="issued_date" name="issued_date">
                            <label for="issued_date"> Date Issued</label>


                        </div>
                    </div>
                    <div>
                        <!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
                        <div class="input-field col s6 m6 8">
                            <input type="text" id="position" name="position" readonly="true">
                            <label for="position" class="active-drop-down  active"> Position</label>
                        </div>

                    </div>
                    <div>
                        <!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
                        <div class="input-field col s6 m6 8">
                            <input type="text" id="department" name="department" readonly="true">
                            <label for="department" class="active-drop-down  active"> Department </label>
                        </div>

                    </div>
                    <div>
                        <!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
                        <div class="input-field col s6 m6 8">
                            <input type="text" id="supervisor_name" name="supervisor_name" readonly="true">
                            <label for="supervisor_name" class="active-drop-down  active"> Supervisor Name</label>
                        </div>

                    </div>
                    <div class=" byDept">
                        <!--<label for="txt_ED_Dept">Dept. Name :</label>-->
                        <div class="input-field col s6 m6 8">
                            <select class="" id="issue_type" name="issue_type" title="Select Department  Name">
                                <option value="NA">Issue Type </option>
                                <option value="Favourism/Partiality">Favourism/Partiality</option>
                                <option value="Repeat Operational Negligence">Repeat Operational Negligence</option>
                                <option value="Rude Behavior">Rude Behavior</option>
								<option value="Absenteeism">Absenteeism</option>
								<option value="Operational Negligence">Operational Negligence</option>
								<option value="Negligence Of Work">Negligence Of Work</option>
								<option value="Other">Other</option>
                            </select>
                            <label title="" for="issue_type" class="active-drop-down active">Issue Type </label>
                        </div>
                    </div>
                    <div>
                        <!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
                        <div class="input-field col s12 ">
                            <textarea class="materialize-textarea" title="Description of Issue" name="description_of_issue" id="description_of_issue" maxlength="500">

                       </textarea>
                            <label for="description_of_issue" class="active"> Description of Issue</label>
                        </div>

                    </div>
                    <div>
                        <!--<label for="ddl_ED_Emp_Name">Emp. ID :</label>-->
                        <div class="input-field col s12 m12 ">
                            <div class="input-field col s12 m12 " id="childtables">

                                <div class="form-inline addChildbutton " style="margin-bottom: 10px;">
                                    <div class="form-group">
                                        <button type="button" name="btn_docAdd" id="btn_docAdd" title="Add Doc Row in Table Down" class="btn btn-small waves-effect waves-green">
                                            <i class="fa fa-plus"></i> Add Document</button>
                                        <button type="button" name="btnDoccan" id="btnDoccan" title="Remove Doc Row in Table Down" class="btn btn-small waves-effect modal-action modal-close waves-red close-btn">
                                            <i class="fa fa-minus"></i> Remove Document</button>

                                    </div>

                                </div>

                                <table class="table table-hovered table-bordered" id="childtable">
                                    <thead class="bg-danger">
                                        <tr>
                                            <th class="hidden">Doc ID</th>
                                            <th>Evidence </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="trdoc" id="trdoc_1">
                                            <td class="doccount hidden">1</td>
                                            <td><input name="txt_doc_name[]" type="file" id="txt_doc_name_1" class="form-control clsInput file_input" /></td>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="input-field col s12 m12 right-align">
                        <button type="submit" name="submit" title="submit" id="submit" class="btn waves-effect waves-green">Submit</button>

                    </div>
                </form>
                <div id="pnlTable">


                    <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div>
                        <?php 
                        	$getAllData = "select * from corrective_action_form where created_by = '" . $EmployeeID . "' order by created_at desc";
							$allData = $myDB->rawQuery($getAllData);
							$mysql_error = $myDB->getLastError();
							if (isset($allData) and count($allData) > 0)
							 {
                                 
                                    
                          ?>
                            <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                <thead>
                                    <tr>

										<th>View</th>
                                        <th> Employee ID </th>
                                        <th> Name </th>
                                        <th> Issueed Date </th>
                                        <th> Position </th>
                                        <th> Department </th>
                                        <th> Supervisor Name: </th>
                                        <th> Issue Type </th>
                                        <!--<th> Description of Issue </th>-->
                                        <th> Created By </th>
										<th> Created At </th>
                                    </tr>
                                </thead>
                                <tbody>
										<?php foreach ($allData as $value)
                                  		{   ?>
                                    
                                            <tr>
												<td>
                                                   <button data-target="modal2" class="waves-effect waves-light btn-small modal-trigger req2" form_id=<?php echo $value['id'] ?>>View</button>
                                                </td>
                                                <td><?php echo $value['employee_id'] ?></td>
                                                <td><?php echo $value['employee_name'] ?></td>
                                                <td><?php echo $value['issued_date'] ?></td>
                                                <td><?php echo $value['position'] ?></td>
                                                <td><?php echo $value['department'] ?></td>
                                                <td><?php echo $value['supervisor_name'] ?></td>
                                                <td><?php echo $value['issue_type'] ?></td>
                                                <!--<td><?php echo $value['description_of_issue'] ?></td>-->
                                                <td><?php echo $value['created_by'] ?></td>
                                                <td><?php echo $value['created_at'] ?></td>
                                            </tr>

                                    <?php  }
                                     ?>

                                </tbody>
                            </table>
                            <?php  
                            }
                            else
						{
							
							echo "<script>$(function(){ toastr.info('No Records Found. ' ".$mysql_error."); }); </script>";
						} 
                                     ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<style>
		.modal .modal-content p {
    		padding: 6px;
		}
	</style>

<div id="modal2" class="modal" style="height: 550px;">
    <div class="modal-content ">
        
        <form method="POST" action="<?php echo URL . 'View/CAP_FormEmp'; ?>" enctype="multipart/form-data" name="headForm">
            <input type="hidden" name="hrFormid" id="hrFormid">

            <div class="row center">
              <h4 style="color:#19aec4">CAP Request Status</h4>  
                    <div class="row" style="padding: 0 0 0 25px;">
                    
                    
                    <div class="input-field center">
                            <div id="comment_container_emp" style="margin: 0px;max-height: 200px;overflow: auto;">
                        </div>
                    </div>
                    <hr/>
                    <div class="col s12">
                     <div class="col s4">
                        <div class="input-field center">
                            <select class="" id="ijphr" name="ijphr" title="Select IJP">
                               
                                
                            </select>
                            <label title="" for="ijphr" class="active-drop-down active">Disqualify For IJP (In Months)</label>
                        </div>
                    	</div>
                     	
                     	<div class="col s4">
                        <div class="input-field center">
                            <select class="" id="incentivehr" name="incentivehr" title="Select Incentive ">
                                
                                
                            </select>
                            <label title="" for="incentivehr " class="active-drop-down active">Incentive Deduction (In Months)</label>
                        </div>
                    </div>
                    
                    <div class="col s4">
                        <div class="input-field center">
                            <select class="" id="plihr" name="plihr" title="Select PLI ">
                                
                                
                            </select>
                            <label title="" for="plihr" class="active-drop-down active">PLI Deduction (In Months)</label>
                        </div>
                    </div>
                    </div>
                    <div class="col s12" id="divEmpComment">
                        <div class="input-field center">
                        
                            <textarea class="materialize-textarea" title="Comment" name="emp_comment" required></textarea>
                            <label for="emp_comment">Comment</label>
                        </div>
                    </div>
					<div style="clear: both"></div>
                    <div class="col s12" id="divEmpSubmit">
                        <div class="input-field">
                            <input class="validate input-field btn" type="submit" name="submit" value="Acknowledge">
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
<script>
	$('.req2').click(function() {
    		
        var hrFormid = $(this).attr("form_id");
        $("input[name='hrFormid']").val(hrFormid);
        
        $.ajax({url: "../Controller/getComment_CAP.php?ID="+$(this).attr("form_id"), success: function(result){
             //alert(result);                    
            if(result != '')
            {
            	
				
				$('#comment_container_emp').empty().append(result);
				
			}    
			$('select').formSelect();                         
        }});
        
        $.ajax({url: "../Controller/getCAPAH_Data.php?ID="+$(this).attr("form_id"), success: function(result3){
             //alert(typeof val); 
             //alert(result3);                   
            if($.trim(result3) == "Not Exist")
            {
            			
			} 
			else
			{	
				
				var Data  = result3.split('|$|');
            	var pli = Data[0];
            	/*if(pli!='')
            	{
					alert(pli.length);
				}*/
				
				$('#ijphr').append($("<option></option>")
                    .attr("value", Data[0])
                    .text(Data[0]));
                    
                $('#incentivehr').append($("<option></option>")
                .attr("value", Data[1])
                .text(Data[1]));
                
                $('#plihr').append($("<option></option>")
                .attr("value", Data[2])
                .text(Data[2]));
                
                $('#statusHead_hr').append($("<option></option>")
                .attr("value", Data[3])
                .text(Data[3]));
                
                /*$('#incentivehr').append($("<option></option>")
                .attr("value", Data[3])
                .text(Data[3]));	statusHead_hr*/
				
				
			}   
			$('select').formSelect();                         
        }});
		
		$.ajax({url: "../Controller/getCAPEmp_Data.php?ID="+$(this).attr("form_id"), success: function(result){
                                 
            if(result == 'Not Exist')
            {
            	$('#divEmpComment').removeClass('hidden');
            	$('#divEmpSubmit').removeClass('hidden');
            	
				
			}
			else
			{
				
            	$('#divEmpComment').addClass('hidden');
            	$('#divEmpSubmit').addClass('hidden');
			}    
			$('select').formSelect();                         
        }});
		
    });
    
    $(document).ready(function() {
        $('.modal').modal();
    });
    
    $(document).ready(function() {
    	
    	function checkRepeat(str)
		{
		    var repeats = /(.)\1{3,}/;
		    return repeats.test(str)
		}
		
    	$('#description_of_issue').val('');
        $('#btn_docAdd').click(function() {

            $count = $(".trdoc").length;
            if ($count > 5) {
                alert('No more Than 5 file allow');
                return false;
            }
            $id = "trdoc_" + parseInt($count + 1);
            $('#doc_child').val(parseInt($count + 1));
            $tr = $("#trdoc_1").clone().attr("id", $id);

            $('#childtable tbody').append($tr);
            $tr.children("td:first-child").html(parseInt($count + 1));


        });
        $('#btnDoccan').click(function() {
            $count = $(".trdoc").length;
            if ($count > 1) {
                $('#childtable tbody').children("tr:last-child").remove();
                $('#doc_child').val(parseInt($count - 1));
            }

        });
        $('#btn_document_add').click(function() {
            var rowlen = ($('.trdoc').length);
            for (i = 1; i <= rowlen; i++) {
                if ($('#txt_doc_value_' + i).val().trim() == '') {
                    $(function() {
                        toastr.error('Please enter document id in' + i + ' row')
                    });
                    return false;
                    break;
                }
                if ($('#txt_doc_name_' + i).val() == '') {
                    $(function() {
                        toastr.error('Please select document file for ' + i + ' row')
                    });
                    return false;
                    break;
                }
            }


        });
		
		$('#submit').click(function(){
			$validate=0;
			if($('#employee_id').val()=='NA')
			{
				$('#employee_id').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
				$validate = 1;
			}
			if($('#issued_date').val()=='')
			{
				$('#issued_date').addClass('has-error');
				$validate = 1;
			}
			if($('#issue_type').val()=='NA')
			{
				$('#issue_type').parent('.select-wrapper').find('input.select-dropdown').addClass('has-error');
				$validate = 1;
			}
			
			if($.trim($('#description_of_issue').val())=='')
			{
				$('#description_of_issue').addClass('has-error');
				$validate = 1;
				
			}
			
			if($.trim($('#description_of_issue').val()).length < 150)
	        {
				$('#description_of_issue').addClass('has-error');
				$validate=1;
				if($('#sremark1').size() == 0)
				{
				   $('<span id="sremark1" class="help-block">Remark should be greater than 150 character.</span>').insertAfter('#description_of_issue');
				}
			}
			
			if(checkRepeat($.trim($('#description_of_issue').val())))
	        {
				$('#description_of_issue').addClass('has-error');
				$validate=1;
				if($('#sremark').size() == 0)
				{
				   $('<span id="sremark" class="help-block">Remark should not contain Repeat character.</span>').insertAfter('#description_of_issue');
				}
			}
			
			if($validate == 1)
			{
				return false;	
			}
			
		});
		
		
    });
</script>
<script>
    $(document).ready(function() {
        $('#issued_date').datetimepicker({
            format: 'Y-m-d',
            timepicker: false
        });


        $('#myTable').DataTable({
            dom: 'Bfrtip',
            scrollX: '100%',
            "iDisplayLength": 25,
            scrollCollapse: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                /*  
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
                , 'pageLength'

            ]
            // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
        });


    });
    $('.selectClass').change(function() {
        var department = $(this).find('option:selected').attr('department');
        var designation = $(this).find('option:selected').attr('position');
        var supervisor_name = $(this).find('option:selected').attr('supervisor_name');
        var employee_name = $(this).find('option:selected').attr('employee_name');
         //this will show the value of the atribute of that option.

          $("#department").val(department);
          $("#position").val(designation);
          $("#supervisor_name").val(supervisor_name);
          $("#employee_name").val(employee_name);
          $("input,select,textarea,label").each(function(){
            $(this).addClass('active');
          
        });
    });
    
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>