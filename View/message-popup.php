<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
ini_set('display_errors', '0');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');

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
$sender_ID=$_SESSION['__user_logid'];
$classvarr="'.byID'";
$searchBy='';
$msg='';
	
	if(isset($_POST['txt_dateFrom']))
	{
		$date_From =$_POST['txt_dateFrom'];	
	}
	else
	{
		$date_From = date('Y-m-d',time()); 
		
	}
	
	if(isset($_POST['txt_dateto']))
	{
		$date_to =$_POST['txt_dateto'];	
	}
	else
	{
		$date_to = date('Y-m-d',time()); 
		
	}
	
	if($sender_ID=='CE03070003')
	{
		$whereClause ="where trim(sender_empid)='CE03070003'";
	}
	else
	{
		$whereClause ="where trim(to_empid)='".$sender_ID."' ";
	}
	
	if(isset($_POST['btn_view']))
	{
		$sqlConnect="select t1.ID, t1.ackstatus, t1.msg_date, t1.text_msg, t1.to_empid, t1.sender_empid, t1.sender_name,t2.EmployeeName from tbl_chat_message t1 inner join personal_details t2 on trim(t1.to_empid)=trim(t2.EmployeeID) $whereClause and cast(msg_date as date) between '".$date_From."' and '".$date_to."' order by msg_date";
	}
	else
	{
		$sqlConnect="select t1.ID, t1.ackstatus, t1.msg_date, t1.text_msg, t1.to_empid, t1.sender_empid, t1.sender_name,t2.EmployeeName from tbl_chat_message t1 inner join personal_details t2 on trim(t1.to_empid)=trim(t2.EmployeeID) $whereClause order by msg_date desc limit 5";
	}
	
	
	
	

?>

<script>
$(document).ready(function() {

    $('#txt_dateFrom').datetimepicker({
        timepicker: false,
        format: 'Y-m-d'
    });

    /* $('.statuscheck').addClass('hidden'); */
    $('#txt_ED_joindate_to').datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });
    $('#txt_ED_joindate_from').datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });
    $('#myTable').DataTable({
        dom: 'Bfrtip',
        "iDisplayLength": 25,
        scrollCollapse: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            ['5 rows', '10 rows', '25 rows', '50 rows', 'Show all']
        ],
        buttons: [{
            extend: 'excel',
            text: 'EXCEL',
            extension: '.xlsx',
            exportOptions: {
                modifier: {
                    page: 'all'
                }
            },
            title: 'table'
        }, 'pageLength']
    });

    $('.buttons-copy').attr('id', 'buttons_copy');
    $('.buttons-copy').hide();
    $('.buttons-csv').hide();
    $('.buttons-excel').hide();
    $('.buttons-csv').attr('id', 'buttons_csv');
    $('.buttons-excel').attr('id', 'buttons_excel');
    $('.buttons-pdf').attr('id', 'buttons_pdf');
    $('.buttons-pdf').hide();
    $('.buttons-print').attr('id', 'buttons_print');
    $('.buttons-print').hide();
    $('.buttons-page-length').attr('id', 'buttons_page_length');
    //$(".dt-buttons").hide();

    $('.byID').addClass('hidden');
    $('.byDate').addClass('hidden');
    $('.byDept').addClass('hidden');
    var classvarr = <?php echo $classvarr; ?>;
    $(classvarr).removeClass('hidden');
    $('#searchBy').change(function() {
        $('.byID').addClass('hidden');
        $('.byDate').addClass('hidden');
        $('.byDept').addClass('hidden');

        if ($(this).val() == 'By ID') {
            $('.byID').removeClass('hidden');
        } else if ($(this).val() == 'By Date') {
            $('.byDate').removeClass('hidden');
        } else if ($(this).val() == 'By Dept') {
            $('.byDept').removeClass('hidden');
        }
    });
});
</script>

<style>
.selected {
    color: red;
    background-color: yellow;
}
</style>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">Message</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>Message</h4>

            <!-- Form container if any -->

            <?php if($sender_ID=='CE03070003') {?>
            <div class="schema-form-section row">

                <div class="input-field col s12 m12" id="rpt_container">
                    <div class="input-field col s4 m4">

                        <input type="text" name="txt_dateFrom" id="txt_dateFrom" value="<?php echo $date_From;?>" />
                    </div>

                    <div class="input-field col s4 m4">

                        <input type="text" name="txt_dateto" id="txt_dateto" value="<?php echo $date_to;?>" />
                    </div>

                    <div class="input-field col s4 m4">

                        <button type="submit" class="btn waves-effect waves-green" name="btn_view" id="btn_view">
                            Search</button>
                        <!--<button type="submit" class="button button-3d-action button-rounded" name="btn_export" id="btn_export"><i class="fa fa-download"></i> Export</button>-->
                    </div>

                </div>

            </div>

            <?php } ?>

            <div id="pnlTable">
                <?php 
			    	//echo $sqlConnect;
			    	$myDB=new MysqliDb();
					$result=$myDB->query($sqlConnect);
					$count0='';
					if($result)
					{
						$count0= $result[0]['ackstatus'];						
					}
					 
					if($count0=="0" && $sender_ID !=='CE03070003')
					{
						echo "
						<style> .disablediv {
							pointer-events:none;
							opacity:70% !important;
							}</style>";
					}
					else
					{
						echo "<style> .disablediv {
							pointer-events:unset;
							opacity:100% !important;
							}</style>";
					}
					
					$error=$myDB->getLastError();
					if($result){?>
                <table id="myTable" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
                    <!--<center style="border: 1px solid #1dadc4;margin: 10px;padding: 10px;font-weight: bold;" class="green-text";> New Message </center>-->

                    <thead>
                        <tr>
                            <th>Srl. No. </th>
                            <?php 
						       		if($sender_ID=='CE03070003'){?>
                            <th>To ID</th>
                            <th>To Name</th>
                            <?php } else{?>
                            <th>Sender</th>
                            <?php } ?>
                            <th>Message</th>
                            <th>Message Date</th>
                            <?php
									if($sender_ID!=='CE03070003'){?>
                            <th>Status</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
					       $count=0;
					        foreach($result as $key=>$value){
					        	$count++;
					        	$id=$count;
								$dataid=$value['ID'];
								$msgstatus=$value['ackstatus'];
								
								
								
								if($sender_ID=='CE03070003'){
								echo '<tr>';
								}
								else
								{
								echo '<tr  onclick="abc('.$dataid.','.$msgstatus.')" >';
								
								}
								echo '<td id="countc'.$id.'">'.$count.'</td>';						
								
								if($sender_ID=='CE03070003')
								{
									echo  '<td class="to_empid" id="to_empid'.$id.'">'.$value['to_empid'].'</td>';
									echo  '<td class="to_empid" id="to_empid'.$id.'">'.$value['EmployeeName'].'</td>';
								}
								else
								{ 	
								    echo '<td class="sender_empid" id="sender_empid'.$id.'">'.$value['sender_name'].'</td>';
								}		
								echo '<td class="text_msg"  id="text_msg'.$id.'" >'.wordwrap($value['text_msg'],90,"<br>\n").'</td>';					
								echo '<td class="msg_date" id="msg_date'.$id.'"  >'.date('d-m-y h:i:s',strtotime($value['msg_date'])).'</td>';
								
								//echo '<td class="tbl__ID"><a href="edit-downtime.php?did='.$value['downtimereqid1']['ID'].'" data-ID="'.$value['downtimereqid1']['ID'].'" class="a__ID" onclick="javascript:return EditData(this);"><img class="imgBtn imgBtnEdit editClass"  src="../Style/images/users_edit.png"/></a></td>';
								if($sender_ID!=='CE03070003')
								{
									if($msgstatus==1)
									{
								echo '<td class="tbl__ID"><span>Acknowledged</span></td>';
									}
									else
									{
								echo '<td class="tbl__ID"><span>Pending</span></td>';
									}
								}
							echo '</tr>';
							}	
							?>
                    </tbody>
                </table>
                <?php
							 }
						else
						{
							echo "<script>$(function(){ toastr.info('Data Not Found(May be You Not Have Any Employee Assigned). ".$error."'); }); </script>";
						} 
						
						
						
	if(isset($_POST['btnSave'])){
	if($_POST['dataid']!="" && $_POST['txt_comment']!="")
	{
		$id=$_POST['dataid'];
		$comment=$_POST['txt_comment'];
		$myDB=new MysqliDb();
		date_default_timezone_set('Asia/Kolkata');
        $date = date('Y-m-d H:i:s', time());
		$query="UPDATE tbl_chat_message SET acknowledge='".addslashes($_POST['txt_comment'])."', acknowledgedate='".$date."', ackstatus=1 WHERE ID='".$_POST['dataid']."'";
				$myDB = new MysqliDb();	
				$result=$myDB->query($query);
				//echo "<script>$(function(){ toastr.success('Acknowledged for this message Successfully'); }); </script>";
				echo "<style> .disablediv {
							pointer-events:unset;
							opacity:100% !important;
							}</style>";
							
		echo "<script>location.href='index.php'; </script>";	   
	}
}					
						
						
						
				      ?>
            </div>
            <!--Reprot / Data Table End -->

            <!--<form method="post" action="">-->
            <input type="hidden" name="dataid" id="messageid">
            <div class="input-field col s12 m12 " style="display:none;" id="messagebox">

                <div class="input-field col s12 m12">
                    <div class="input-field col s6 m6 clsIDHome">

                        <textarea class="materialize-textarea" id="txt_comment" name="txt_comment"></textarea>
                        <label for="reply">Reply Messeage</label>
                    </div>

                </div>


                <div class="input-field col s12 m12 right-align">
                    <input type="submit" value="Acknowledge" name="btnSave" id="btnSave1"
                        class="btn waves-effect waves-green" />
                </div>

            </div>
            <!--</form>-->
            <!--Form container End -->
        </div>
        <!--Main Div for all Page End -->
    </div>
    <!--Content Div for all Page End -->
</div>
<script>
$(function() {

    $('#btnSave1').click(function() {
        //alert('hide');
        var msg = $('#txt_comment').val().trim();
        //alert_msg="hello";
        //alert("hello");
        validate = 0;
        if (msg == "") {
            $('#txt_comment').addClass('has-error');
            validate = 1;
            if ($('#stxt_comment').size() == 0) {
                $('<span id="stxt_comment" class="help-block">Reply Message should not be empty.</span>')
                    .insertAfter('#txt_comment');
            }
        }


        if (validate == 1) {

            return false;
        }


    });
    <?php 
		
		/* if(isset($_POST['btnSave']))
		{
			?>
    window.location = <?php echo  "'".URL.'View/'."'"; ?>
    <?php 
		} */
		?>
});
</script>

<script>
function abc(id, msg) {
    //alert("hello");

    if (msg == 0) {
        $('#messagebox').show();
    } else {
        $('#messagebox').hide();
    }

    $('#messageid').val(id);

}
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>