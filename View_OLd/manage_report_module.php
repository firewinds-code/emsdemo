<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
// require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_logid = clean($_SESSION['__user_logid']);
if (isset($_SESSION)) {

	if (!isset($user_logid)) {
		$location = URL . 'Login';
		header("Location: $location");
	}
} else {
	$location = URL . 'Login';
	header("Location: $location");
}



$classvarr = "'.byID'";
// Global variable used in Page Cycle
$searchBy = $question_num = $id = $mysql_error2 = '';
$email = array();
$ccemail = array();
$addbriefing = isset($_POST['addbriefing']);
if ($addbriefing) {
	if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
		$hidden_reportid = cleanUserInput($_POST['hidden_reportid']);
		$hidden_processid = cleanUserInput($_POST['hidden_processid']);
		$reportid = explode(",", $hidden_reportid);
		$processid = explode(",", $hidden_processid);
		//print_r($reportid);	print_r($processid);exit;
		$EmpID = substr(cleanUserInput($_POST['txt_empid']), strpos(cleanUserInput($_POST['txt_empid']), "(") + 1, (strpos(cleanUserInput($_POST['txt_empid']), ")")) - (strpos(cleanUserInput($_POST['txt_empid']), "(") + 1));

		// $myDB = new MysqliDb();
		$query = "DELETE from report_map where EmpID=?;";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $EmpID);
		$delt = $stmt->execute();

		$resultBy = $myDB->rawQuery($query);
		$createdby = clean($_SESSION['__user_logid']);
		foreach ($reportid as $rvar) {
			foreach ($processid as $pvar) {
				$myDB = new MysqliDb();
				$query = "INSERT into report_map set EmpID=?,reportID=?, processID=?, CreatedBy=?; ";
				$insQ = $conn->prepare($query);
				$insQ->bind_param("ssss", $EmpID, $rvar, $pvar, $createdby);
				$delt = $insQ->execute();
			}
		}
		echo "<script>$(function(){ toastr.info('New Report Mapped !!!'); }); </script>";
	}
}

?>

<style>
.error {
    color: red;
}

#data-container {
    display: block;
    background: #2a3f54;

    max-height: 250px;
    overflow-y: auto;
    z-index: 9999999;
    position: absolute;
    width: 100%;

}

#data-container li {
    list-style: none;
    padding: 5px;
    border-bottom: 1px solid #fff;
    color: #fff;
}

#data-container li:hover {
    background: #26b99a;
    cursor: pointer;
}

.form-control:focus {
    border-color: #d01010;
    outline: 0;
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

}

#overlay {
    position: fixed;
    top: 0;
    z-index: 100;
    width: 100%;
    height: 100%;
    display: none;
    background: rgba(0, 0, 0, 0.6);
}

.cv-spinner {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px #ddd solid;
    border-top: 4px #2e93e6 solid;
    border-radius: 50%;
    animation: sp-anime 0.8s infinite linear;
}

@keyframes sp-anime {
    100% {
        transform: rotate(360deg);
    }
}

.is-hide {
    display: none;
}
</style>

<script>
$(document).ready(function() {
    $('#from_date').datetimepicker({
        format: 'Y-m-d H:i',
        step: 30
    });
    $('#myTable').DataTable({
        dom: 'Bfrtip',
        "iDisplayLength": 25,
        scrollX: '100%',
        scrollCollapse: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
        ],
        buttons: [
            'pageLength'
        ]
        // buttons: ['copy', 'csv', 'excel', 'pdf', 'print','pageLength']
    });


    $('.buttons-excel').attr('id', 'buttons_excel');
    $('.buttons-page-length').attr('id', 'buttons_page_length');
    $('.byID').addClass('hidden');
    $('.byDate').addClass('hidden');
    $('.byDept').addClass('hidden');
    $('.byProc').addClass('hidden');
    $('.byName').addClass('hidden');
    var classvarr = <?php echo $classvarr; ?>;
    $(classvarr).removeClass('hidden');

});
</script>


<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Manage Report Module</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Manage Report Module</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">
                <?php
				$_SESSION["token"] = csrfToken();
				?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <?php
				$ccemail = array();
				$email = array();
				$emailId3 = "";
				$emailId = "";
				/*if(isset($_GET['id']) and $_GET['id']!="")
			    {
					$Id=$_GET['id'];
					$sqlConnect2 = 'select  report_name from  report_master  where id="'.$Id.'" '; 
					
					$myDB=new MysqliDb();
					$result2=$myDB->query($sqlConnect2);
					foreach($result2 as $key=>$value)
					{
						if($value['emailID']!="")
						{
							$email[]=$value['emailID'];
						}
						if($value['cc_email']!="")
						{
							$ccemail[]=$value['cc_email'];
						}	
					}
				}*/
				?>
                <div class="form-inline" id="ajaxid">
                    <br />
                    <div class="input-field col s12 m12">
                        <div class="input-field col s6 m6">
                            <input type="text" id="txt_empid" name="txt_empid" required />
                            <label for="txt_empid">Search Employee</label>
                            <div id="data-container"></div>
                        </div>
                        <div id="bom_list" class="col-md-8 col-sm-8 col-xs-12">

                        </div>
                    </div>
                    <h4>Report Section</h4>
                    <div class="input-field col s12 m12">
                        <?php
						$sqlBy2 = 'SELECT id,report_name FROM report_master';
						$myDB = new MysqliDb();
						$resultBy2 = $myDB->query($sqlBy2);
						if ($myDB->count > 0) {
							foreach ($resultBy2 as $key => $value2) {

								//echo '&nbsp;&nbsp;&nbsp;'.$value2['email_address'];
						?><div class="col s4 m4 l4">
                            <input type='checkbox' name="report[]" id="<?php echo  $value2['id']; ?>"
                                value="<?php echo $value2['id']; ?>">
                            <label for="<?php echo  $value2['id']; ?>"><?php echo $value2['report_name']; ?></label>
                        </div>
                        <?php	}
						}  ?>
                    </div>


                    <div class="col s12 m12">

                        <br />
                        <iframe name="irsc" src="dropdown.php" style="width: 100%;height: 370px;"
                            frameborder="0"></iframe>

                    </div>
                </div>

                <input type='hidden' name='id' id="mid" value='<?php echo $id; ?>'>
                <input type='hidden' name='hidden_processid' id="hidden_processid">
                <input type='hidden' name='hidden_reportid' id="hidden_reportid">
                <div class="input-field col s12 m12 right-align">
                    <!--<button type="submit" name="savebriefing"  id="savebriefing" class="btn waves-effect waves-green" style="display:none;" >Save</button>
		    	<a href="<?php echo URL; ?>View/manage_email_module.php" class="btn waves-effect modal-action modal-close waves-red close-btn" style="display:none;" id='cancelID'>Cancel</a>-->
                    <button type="submit" name="addbriefing" id="addbriefing"
                        class="btn waves-effect waves-green">Submit</button>
                </div>



            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>

<script>
$(document).ready(function() {

    $('#div_error').removeClass('hidden');

    $('#addbriefing').click(function() {

        //var name = $('iframe[name=irsc]').contents().find('#target1').val();
        if ($('#txt_empid').val() == "") {
            validate = 1;
            $('#txt_empid').addClass('has-error');
            $('#txt_empid').parent('.select-wrapper').find('input.select-dropdown').addClass(
                "has-error");
            if ($('#span_txt_empid').size() == 0) {
                $('<span id="span_txt_empid" class="help-block">Require *</span>').insertAfter(
                    '#txt_empid');
            }
            return false;
        }

        var checkvalues = '';
        checkvalues = $("input[name='report[]']:checked")
            .map(function() {
                return $(this).val();
            }).get();

        if (checkvalues == '') {
            alert('Please select atleast one report');
            return false;
        }
        //alert(checkvalues);    
        var pval = '';
        /*$('iframe[name=irsc]').contents().find("#target1 option").each(function()
		{
			pval = pval + $(this).val() + ',';
		    
		});*/
        pval = $('iframe[name=irsc]').contents().find("#target1 option")
            .map(function() {
                return $(this).val();
            }).get();

        if (pval == '') {
            alert('Please select atleast one process');
            return false;
        }
        //alert(pval);
        $('#hidden_reportid').val(checkvalues);
        $('#hidden_processid').val(pval);

        /*alert($('#hidden_reportid').val());
        alert($('#hidden_processid').val());*/
        //return false;
    });

    /*$("#txt_empid").keyup(function(){
    	$('#txt_empid').autocomplete({source:'../Controller/autocomplete_employee.php', minLength:2,disabled: false});
    	$('select').formSelect();
    });	*/

    $('#txt_empid').keyup(function() {
        var term = $(this).val();

        var resp_data_format = "";
        $.ajax({
            url: "../Controller/autocomplete_employee_active.php",
            data: {
                term: term,

            },
            method: "get",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    resp_data_format = resp_data_format + "<li class='select_country'>" +
                        response[i] + "</li>";
                };
                $("#data-container").html(resp_data_format);
            }
        });
    });

    $(document).on("click", ".select_country", function() {
        var selected_country = $(this).html();
        $('#txt_empid').val(selected_country);
        $('#data-container').html('');
        var empid = $('#txt_empid').val().substr($('#txt_empid').val().lastIndexOf("(") + 1, ($(
            '#txt_empid').val().lastIndexOf(")") - $('#txt_empid').val().lastIndexOf("(")) - 1);

        $('iframe[name=irsc]').contents().find("#source1").find('option').remove();
        $('iframe[name=irsc]').contents().find("#target1").find('option').remove();
        $("input[name='report[]']:checkbox").prop('checked', false);
        //$('iframe[name=irsc]').contents().find("#source1").append('<option value="Masood">Masood</option>' );
        $.ajax({
            url: "../Controller/getsource_cmid.php?empid=" + empid,
            success: function(result) {
                if (result != '') {
                    //alert(result); 
                    //$('#PromotionPostApr').append(new Option("---Select---","NA"));
                    var Data = result.split('|$$|');
                    jQuery.each(Data, function(i, val) {
                        if (val != '') {
                            arr = val.split('|$|');
                            //alert(arr[0]);
                            //alert(arr[1]);
                            $('iframe[name=irsc]').contents().find("#source1")
                                .append('<option value="' + arr[0] + '">' + '' +
                                    arr[1] + '</option>');

                        }

                    });
                }
                $('select').formSelect();
            }
        });

        $.ajax({
            url: "../Controller/gettarget_cmid.php?empid=" + empid,
            success: function(result) {
                if (result != '') {
                    //alert(result); 
                    //$('#PromotionPostApr').append(new Option("---Select---","NA"));
                    var Data = result.split('|$$|');
                    jQuery.each(Data, function(i, val) {
                        if (val != '') {
                            arr = val.split('|$|');
                            //alert(arr[0]);
                            //alert(arr[1]);
                            $('iframe[name=irsc]').contents().find("#target1")
                                .append('<option value="' + arr[0] + '">' + '' +
                                    arr[1] + '</option>');

                        }

                    });
                }
                $('select').formSelect();
            }
        });

        //checkvalues = $("input[name='report[]']")
        //.map(function(){return $(this).val();}).get();	
        //$('#report[1]').attr('checked');
        //$("input[name='report[].attr('1')']:checkbox").prop('checked',true);


        $.ajax({
            url: "../Controller/getReportID.php?empid=" + empid,
            success: function(result) {
                if (result != '') {
                    //alert(result); 
                    //$('#PromotionPostApr').append(new Option("---Select---","NA"));
                    var Data = result.split('|$|');
                    jQuery.each(Data, function(i, val) {
                        if (val != '') {
                            //alert(val);
                            $.each($("input[name='report[]']"), function() {

                                if ($(this).attr("id") == val) {
                                    $(this).prop('checked', true);
                                }

                            });

                        }

                    });
                }
                $('select').formSelect();
            }
        });

    });

});


function editIT(moduleID, locid) {
    //alert(locid);
    $('#mid').val(moduleID);
    $('#savebriefing').show();
    $('#addbriefing').hide();
    $('#cancelID').show();
    $("#module_id").prop('disabled', 'disabled');
    $("#module_id").val();
    if (moduleID != "") {
        $.ajax({
            url: <?php echo '"' . URL . '"'; ?> + "Controller/getAssignedEmail.php?mid=" + moduleID +
                "&locid=" + locid
        }).done(function(data) {
            $('#ajaxid').html(data);
            $('select').formSelect();
        });
    }
}
</script>