<?php

// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
require('../TCPDF/tcpdf.php');
$EmployeeID = $_SESSION['__user_logid'];
$myDB = new MysqliDb();

$getAllData = "select t1.* from corrective_action_form t1 inner join (select distinct id,corrective_Formid,statusHead from corrective_action_formhead where corrective_Formid in (select id from corrective_action_form where employee_id='" . $EmployeeID . "') order by id desc limit 1) t2 on t1.id = t2.corrective_Formid inner join ( select distinct id,corrective_Formid, statusHr from corrective_action_formhr where corrective_Formid in (select id from corrective_action_form where employee_id='" . $EmployeeID . "') order by id desc limit 1) t3 on t1.id=t3.corrective_Formid where t1.employee_id='" . $EmployeeID . "' and t2.statusHead='Approved' and t3.statusHr='Approved'";

#$getAllData = "select t1.* from corrective_action_form t1 inner join corrective_action_formhead t2 on t1.id = t2.corrective_Formid inner join corrective_action_formhr t3 on t1.id=t3.corrective_Formid where t1.employee_id='".$EmployeeID."' and t2.statusHead='Approved' and t3.statusHr='Approved'";
$allData = $myDB->rawQuery($getAllData);

/*if (isset($_POST['submitHead'])) {
    //echo "<pre>";print_r($_POST);exit;
    $EmployeeID = $_SESSION['__user_logid'];
    $createBy = $_SESSION['__user_logid'];
    $Query = "insert into corrective_action_formhead (corrective_Formid, ijp, incentive, pli, statusHead, head_comment, created_by) VALUES ('" . $_POST['headFormid'] . "','" . $_POST['ijp'] . "','" . $_POST['incentive'] . "','" . $_POST['pli'] . "','" . $_POST['statusHead'] . "','" . $_POST['head_comment'] . "','" . $EmployeeID . "')";

    $result_Hr = $myDB->rawQuery($Query);
    $mysql_error = $myDB->getLastError();
    $last_id = $myDB->getInsertId();

    if (empty($mysql_error)) {
        echo "<script>$(function(){ toastr.success('Status Update Successfully '); }); </script>";
        $location = URL . 'View/CAP_FormEmp';
        echo "<script> window.location(" . $location . ")</script>";
    } else {
        echo "<script>$(function(){ toastr.warning('Status Not Updated '); }); </script>";
    }
    //	exit;
}*/

if (isset($_POST['submit'])) {
    //echo "<pre>";print_r($_POST);exit;
    $EmployeeID = $_SESSION['__user_logid'];
    $createBy = $_SESSION['__user_logid'];
    $QueryHr = "insert into corrective_action_formemp (corrective_Formid, emp_comment, created_by) VALUES ('" . $_POST['hrFormid'] . "','" . addslashes(trim($_POST['emp_comment'])) . "','" . $EmployeeID . "')";

    $result_Hr = $myDB->rawQuery($QueryHr);
    $mysql_error = $myDB->getLastError();
    $last_id = $myDB->getInsertId();
    if (empty($mysql_error)) {
        echo "<script>$(function(){ toastr.success('Acknowledgement Successfully'); }); </script>";
        warning_pdf($EmployeeID);
        $location = URL . 'View/CAP_FormEmp.php';
        echo "<script> window.location(" . $location . ")</script>";
    } else {
        echo "<script>$(function(){ toastr.warning('Status Not Updated '); }); </script>";
    }

    //	exit;
}

function warning_pdf($EmployeeID)
{
    $filename = $EmployeeID . "_Warning.pdf";

    //$GetData="select w.EmployeeID , w.EmployeeName , w.designation ,p.location, wr.issue_type,wr.issue_comment,w.Gender,head.pli, head.incentive from whole_details_peremp w inner join personal_details p on w.EmployeeID=p.EmployeeID inner join corrective_action_form wr on w.EmployeeID=wr.employee_id join corrective_action_formhead head on wr.id=head.corrective_Formid where w.EmployeeID='".$EmployeeID."';";
    $GetData = "select w.EmployeeID , w.EmployeeName , w.designation ,p.location, wr.created_at,wr.issue_type,wr.issue_comment,w.Gender,head.pli, head.incentive from whole_dump_emp_data w inner join personal_details p on w.EmployeeID=p.EmployeeID inner join corrective_action_form wr on w.EmployeeID=wr.employee_id join corrective_action_formhead head on wr.id=head.corrective_Formid where w.EmployeeID='" . $EmployeeID . "';";
    $myDB = new MysqliDb();

    $resultsE = $myDB->query($GetData);
    if (count($resultsE) > 0) {
        $EmployeeID = $resultsE[0]['EmployeeID'];
        $EmployeeName = $resultsE[0]['EmployeeName'];
        $Designation = $resultsE[0]['designation'];
        $locationid = $resultsE[0]['location'];
        $WarningReason = $resultsE[0]['issue_type'];
        $Comments = $resultsE[0]['issue_comment'];
        $pli = $resultsE[0]['pli'];
        $incentive = $resultsE[0]['incentive'];
        $Gender = $resultsE[0]['Gender'];
        $IssueDate = $resultsE[0]['created_at'];
    }
    $fullname = $EmployeeName;
    list($firstName, $lastName) = explode(' ', $fullname);
    if ($Gender == 'Male') {
        $Title = "Mr.";
    } else {
        $Title = "Mrs.";
    }
    // $dateformat = date('j F Y', strtotime($_date1 = date('Y-m-d')));
    $dateformat = date('j F Y', strtotime($IssueDate));
    ///// pdf creation
    $pdf = "<h4><u>Warning  Letter</u></h4>";
    $pdf1 = "<p><b>" . $dateformat . "</b></p>
				<table><tr><td><b>Name - " . $fullname . "</b></td></tr>
				<tr><td><b>Employee ID - " . $EmployeeID . "</b></td></tr>
				<tr><td><b>Designation - " . $Designation . "</b></td></tr></table>
				<P><b><u>Sub: - Warning Letter for " . $WarningReason . " </u></b><br/><br/>
				<b>" . $Title . "  " . $firstName . " ," . "</b></p>
		        <p>With reference to the terms and conditions of your employment, we have found that you have displayed lack of commitment and responsibility towards your work, in your capacity as <b>" . $Designation . ".</b></p>
		        <p>This is to notify you that you have been found non-complaint as per the set standard of company, below listed are reasons: -<br/> <b>" . $WarningReason . "." . "</b></p>
				<p><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Issue Description:</u><br/></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>" . $Comments . "</u></p>
				<p>This issue has been duly discussed between you and your supervisor wherein you had acknowledged the same.</p>
				<p>Please treat this as a formal warning letter accordingly. You will be monitored for next three Months by your supervisors and HR. Any reoccurrence of issue will lead to termination of employment.</p>
				<p>Please note given severity of the issue your variable pay (PLI)/Incentive, if applicable, will not be payable for said period " . $pli . "/" . $incentive . " month.</p>
				<p>We value positive association of all resources with organization and accordingly would encourage you to seek out any help needed in this regard from your supervisor & HR department.</p><br/><br/><br/><br/><br/>
				";
    //$pdf2='<p style="text-align: center"><u>Address :- '.$lval['Address'].'</u></p>';
    $pdf2 = '';
    $filename = $EmployeeID . "_Warning.pdf";
    $path = ROOT_PATH . "/warning_pdf/" . $filename;
    $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $tcpdf->SetTitle('Cogent|Warning Checklist');

    $tcpdf->SetMargins(10, 10, 10, 10);
    $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    $tcpdf->setPrintHeader(false);
    $tcpdf->setPrintFooter(false);
    $tcpdf->setListIndentWidth(3);

    $tcpdf->SetAutoPageBreak(TRUE, 11);

    $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $tcpdf->AddPage();

    $tcpdf->SetFont('times', '', 10.5);
    $tcpdf->Image('../Style/img/sk_sign.jpeg', 10, 210, 30, 15, 'JPEG');
    //$tcpdf->Image('../Style/images/newLogo cogent.jpg', 10, 5, 60,30, 'JPG'); //logo left
    $tcpdf->Image('../Style/images/Cogent.png', 140, 5, 60, 30, 'PNG'); //logo right

    $pdf2 = "<p>Sincerely,<br/>Authorized signatory </p>";

    //$tcpdf->writeHTML($pdf, true, false, false, false, '');
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 50, $pdf1, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 5, $y = 40, $pdf, $border = 0, $ln = 1, $fill = false, $reseth = true, $align = 'C'); //$fill use for pent that line
    $tcpdf->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 225, $pdf2, $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '');
    $tcpdf->Output($path, 'F');
    /////end pdf


}
?>


<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">CORRECTIVE ACTION FORM</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>CORRECTIVE ACTION FORM</h4>
            <div class="schema-form-section row">

                <div id="pnlTable">


                    <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
                        <div>
                            <?php if (isset($allData) and count($allData) > 0) {
                            ?>
                                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>

                                            <th> Acknowledge </th>
                                            <th> Employee ID </th>
                                            <th> Name </th>
                                            <th> Issueed Date </th>
                                            <th> Position </th>
                                            <th> Department </th>
                                            <th> Supervisor Name: </th>
                                            <th> Issue Type </th>
                                            <!-- <th> Description of Issue </th>-->
                                            <th> Created By </th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($allData as $value) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <button data-target="modal2" class="waves-effect waves-light btn-small modal-trigger req2" form_id=<?php echo $value['id'] ?>>Acknowledge</button>
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
                                            </tr>

                                        <?php
                                        }

                                        ?>

                                    </tbody>

                                </table>
                            <?php
                            } else {

                                echo "<script>$(function(){ toastr.info('No Records Found. ' ); }); </script>";
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
                <h4 style="color:#19aec4">Employee Acknowledgement</h4>
                <div class="row">


                    <div class="input-field center">
                        <div id="comment_container_emp" style="margin: 0px;max-height: 200px;overflow: auto;">
                        </div>
                    </div>
                    <hr />
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
                    <div class="col s12" id="divEmpAck">
                        <div class="input-field center">

                            <p style="font-family: Verdana; font-size: 14px;"> <?php echo htmlentities(' I have read this "Warning Notice" and understand it. I acknowledge that a copy of this warning has been given to me this day. Failure to correct the issue(s) stated above may result in further disciplinary action up to and including termination. I further understand that my signature indicates that I have received and reviewed this notice with my supervisor. I acknowledge I have been provided an opportunity to respond, in writing, to this notice and understand that my signature does not necessarily mean I agree.') ?></p>
                            <br />
                            <input type='checkbox' name="Accept" id="Accept">
                            <label for="Accept">Accept</label>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div class="col s12" id="divEmpSubmit">
                        <div class="input-field">
                            <input class="validate input-field btn" type="submit" name="submit" id="submit" value="Acknowledge">
                        </div>
                    </div>

                    <?php $file = '../warning_pdf/' . $EmployeeID . "_Warning.pdf";

                    if (file_exists($file)) {


                    ?>
                        <div class="col s12" id="divpdf" style="font-size: 18px; font-weight: bold;padding-top: 15;">

                            <a href="<?php echo $file  ?>" target="_blank">Warning Letter</a>

                        </div>

                    <?php }  ?>

                </div>

            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
</div>
<script>
    $('.req').click(function() {
        var headFormid = $(this).attr("form_id");
        $("input[name='headFormid']").val(headFormid);

    });

    $('.req2').click(function() {
        var hrFormid = $(this).attr("form_id");
        $("input[name='hrFormid']").val(hrFormid);

        $.ajax({
            url: "../Controller/getComment_CAP.php?ID=" + $(this).attr("form_id"),
            success: function(result) {
                //alert(result);                    
                if (result != '') {


                    $('#comment_container_emp').empty().append(result);

                }
                $('select').formSelect();
            }
        });

        $.ajax({
            url: "../Controller/getCAPAH_Data.php?ID=" + $(this).attr("form_id"),
            success: function(result3) {
                //alert(typeof val); 
                //alert(result3);                   
                if ($.trim(result3) == "Not Exist") {

                } else {

                    var Data = result3.split('|$|');
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
            }
        });

        $.ajax({
            url: "../Controller/getCAPEmp_Data.php?ID=" + $(this).attr("form_id"),
            success: function(result) {
                var res = parseInt(result);
                //alert(res);                 
                if (res == 1) {
                    $('#divEmpComment').addClass('hidden');
                    $('#divEmpSubmit').addClass('hidden');
                    $('#divEmpAck').addClass('hidden');
                    /*if(result !='Exist')
            	{
					alert('123');	
				}
				else
				{
					alert('1');
					$('#divEmpComment').removeClass('hidden');
            		$('#divEmpSubmit').removeClass('hidden');
				}*/



                } else {
                    /*alert('dffdsf');
                    if(result =='Exist')
                    {
                    	alert('rrrrr');
                    }
                    alert('2');*/
                    $('#divEmpComment').removeClass('hidden');
                    $('#divEmpSubmit').removeClass('hidden');
                    $('#divEmpAck').removeClass('hidden');
                }
                $('select').formSelect();
            }
        });

    });
    $(document).ready(function() {
        $('.modal').modal();
    });




    $(document).ready(function() {
        $('#alert_msg_close').click(function() {
            $('#alert_message').hide();
        });
        if ($('#alert_msg').text() == '') {
            $('#alert_message').hide();
        }


        $('#div_error').removeClass('hidden');
    });
</script>
<script>
    $(document).ready(function() {
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

        $('#submit').click(function() {

            if ($('#Accept').prop('checked') == false) {
                alert('Please Accept first...');
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
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>