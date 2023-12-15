 <?php  
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
require(ROOT_PATH.'AppCode/nHead.php');
if(isset($_POST['submit']))
{
	$q="select lower(BankName)as BankName from bank_master where BankName=lower('".$_POST['txt_bank_name']."');";
	$myDB= new MysqliDb();
	$result_q =$myDB->query($q);
	if(count($result_q) > 0)
	{
		echo "<script>$(function(){ toastr.error('Bank name already exists. ') }); </script>";
	}
	else
	{
	  $insert="insert into bank_master (`BankName`)values (upper('".trim($_POST['txt_bank_name'])."'));"; //insert upper case approval of rinku mam 2021-01-01
	 // $insert="insert into bank_master (`BankName`)values ('".$_POST['txt_bank_name']."');";
	  $myDB= new MysqliDb();
	  $result =$myDB->rawQuery($insert);
	  if($myDB->count > 0)
		  {
		  	echo "<script>$(function(){ toastr.success('Bank name added successfully.') }); </script>";
		  }
	  else
		  {
		  	echo "<script>$(function(){ toastr.error('Error! Try Again..') }); </script>";
		  }
	}
}

if(isset($_POST['update']))
{
	$q="select lower(BankName)as BankName from bank_master where BankName=lower('".$_POST['txt_bank_name']."');";
	$myDB= new MysqliDb();
	$result_q =$myDB->query($q);
	if(count($result_q) > 0)
	{
		echo "<script>$(function(){ toastr.error('Bank name already exists. ') }); </script>";
	}
	else
	{
	 $update="update  bank_master set BankName =upper('".trim($_POST['txt_bank_name'])."') where id='".$_POST['UpdateId']."';";//update upper case approval of rinku mam 2021-01-01
	   //$update="update  bank_master set BankName ='".$_POST['txt_bank_name']."' where id='".$_POST['UpdateId']."';";
	  $myDB= new MysqliDb();
	  $result =$myDB->rawQuery($update);
	  if($myDB->count > 0)
		  {
		  	echo "<script>$(function(){ toastr.success('Bank name updated successfully.') }); </script>";
		  }
	  else
		  {
		  	echo "<script>$(function(){ toastr.error('Error! Try Again..') }); </script>";
		  }
	}
}
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 
	<!-- Header Text for Page and Title -->
	<span id="PageTittle_span" class="hidden">Add/ Update Bank Name</span>
		<!-- Main Div for all Page -->
		<div class="pim-container row" id="div_main" >
			<!-- Sub Main Div for all Page -->
			<div class="form-div">
			<!-- Header for Form If any -->
				 <h4>Add/ Update Bank Name</h4>		
			<!-- Form container if any -->
				<div class="schema-form-section row" >
					<!--<div class="input-field col s12 m12 ">-->
						<div class="input-field col s6 m6">
						 <input type="hidden" name="UpdateId" id="UpdateId"/>
							<input type="text" name="txt_bank_name" id="txt_bank_name"  />
							<label id="bank_name" class="activeted_al active">Bank Name</label><span id="spnError" style="color: Red; display: none">*Valid characters: Letters and space.</span>
						</div>
						<div class="input-field col s6m6 right-align">
							<button type="submit" id="submit" name="submit" value = "Validate"  class="btn waves-effect waves-green" onclick = "return Validate()">Submit</button>
				 		</div>
				 		<div class="input-field col s6m6 right-align">
							<button type="submit" id="update" name="update" value = "Validate"  onclick = "return Validate()" class="btn waves-effect waves-green" style="display: none;">Update</button>
				 		</div>
		    <div id="pnlTable">
		    <?php
		            $sqlConnect = 'select id, BankName from bank_master;'; 
					$myDB=new MysqliDb();
					$result = $myDB->query($sqlConnect);
					$error=$myDB->getLastError();
					if(count($result) > 0 && $result)
					{?>
				   <div class="had-container pull-left row card" style="margin-top: 10px;width: 100%;padding: 15px;">
				     <div class=""  >																											              <table id="myTable1" class="data dataTable no-footer row-border" cellspacing="0" width="100%">
					    <thead>
					        <tr>
					        	<th>Edit</th>
					            <th>BankName</th>
					        </tr>
					    </thead>
					    <tbody>					        
					       <?php
					       $count=0;
					        foreach($result as $key=>$value){
							echo '<tr>';							
							$count++;
							echo '<td class="BankName manage_item"><a id="'.$value['id'].'" class="a__ID" onclick="javascript:return EditData(this);"><i class="material-icons edit_item imgBtn imgBtnEdit tooltipped" data-position="left" data-tooltip="Edit">ohrm_edit</i></a></td>';
							echo '<td class="BankName1">'.$value['BankName'].'</a></td>';
							echo '</tr>';
							}	
							?>			       
					    </tbody>
				      </table>
				    </div>
				  </div>
				<?php
				 }
				else
				{
				    echo '<div class="alert alert-danger">Data Not Found :: <code >'.$error.'</code> </div>';
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
<script type="text/javascript">
$(document).ready(function(){
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
						        },'pageLength'
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"iDisplayLength": 10,
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() 
							{
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
			});
		$('#myTable1').DataTable({
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
						        },'pageLength'
						        
						    ],
						    "bProcessing" : true,
							"bDestroy" : true,
							"bAutoWidth" : true,
							"iDisplayLength": 10,
							"sScrollX" : "100%",
							"bScrollCollapse" : true,
							"bLengthChange" : false,
							"fnDrawCallback":function() 
							{
								$(".check_val_").prop('checked', $("#chkAll").prop('checked'));
							}
			});

		   	
		   	$('.buttons-copy').attr('id','buttons_copy');
		   	$('.buttons-csv').attr('id','buttons_csv');
		   	$('.buttons-excel').attr('id','buttons_excel');
		   	$('.buttons-pdf').attr('id','buttons_pdf');
		   	$('.buttons-print').attr('id','buttons_print');
		   	$('.buttons-page-length').attr('id','buttons_page_length');
		   	
	});
	
    

 
	$('#submit').click(function()
	{
		/*$("#txt_bank_name").keypress(function(event)
	{
            var inputValue = event.which;
            // allow letters and whitespaces only.
            if(!(inputValue >= 65 && inputValue <= 123) && (inputValue != 32 && inputValue != 0))
             { 
                event.preventDefault(); 
             }
            console.log(inputValue);
  });*/
	       if($('#txt_bank_name').val()=='')
	        {
				$('#txt_bank_name').css('border-color','red');
				$('#txt_bank_name').focus();
				alert('Please add bank name.')
				return false;
			}
			else
			{
				$('#txt_bank_name').css('border-color','');
			}
			});
function EditData(el)
	{
		$('#update').show();
		 $('#submit').hide()
		$('#BankName').val('');
		var BankName=$(el).parents('td').parents('tr').find('.BankName1').text();
			$('#txt_bank_name').val(BankName);
			$('#UpdateId').val(el.id);
			$('#bank_name').addClass("active-drop-down active");
			$('select').formSelect();		     
}	
function Validate() 
    {
        var isValid = false;
        var regex = /^[a-zA-Z\s]*$/;
        isValid = regex.test(document.getElementById("txt_bank_name").value);
        document.getElementById("spnError").style.display = !isValid ? "block" : "none";
        return isValid;
    }			
</script>
<?php 
include(ROOT_PATH.'AppCode/footer.mpt'); 
?>