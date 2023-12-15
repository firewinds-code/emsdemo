<?php

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
$sqlConnect='select * from trainee_doc where EmployeeID="'.$_REQUEST['ID'].'"';	
$myDB=new MysqliDb();
$result=$myDB->query($sqlConnect);
	if(count($result) > 0 && $result)
	{?>
		<table id="myTable1" class="data dataTable no-footer" cellspacing="0" style="width:100%;">
							    <thead>
							        <tr>
							            <th>Doc ID</th>
							            <th>Doc File</th>
							            <th>Doc Desc</th>						            
							            <th style="width:100px;">Manage Doc </th>
							        </tr>
							    </thead>
						    <tbody>					        
						       <?php
						        foreach($result as $key=>$value){
								echo '<tr>';							
								echo '<td class="ExpID">'.$value['ID'].'</td>';
								echo '<td>'.$value['docfile'].'</td>';
								echo '<td>'.$value['docdesc'].'</td>';				
								echo '<td><img alt="Delete" class="imgBtn imgBtnUploadDelete" src="../Style/images/users_delete.png" title="Delete Data Item"  id="'.$value['ID'].'" data-file="'.$value['docfile'].'" /><img alt="Download" class="imgBtn imgbtnDownload" src="../Style/images/download.png" title="View Data Item" data="'.$value['docfile'].'" /></td>';
								
								echo '</tr>';
								}	
							?>			       
					    </tbody>
						</table>
		<?php
	}
	else
	{
		echo '<code>No Doc Upladed For This Employee...</code>';
		
	}
?>

