<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');

if(isset($_GET['action']) and $_GET['action']=='search' and $_GET['empid']!="")
{
	
	 $action=$_GET['action'];
	 $empid=trim($_GET['empid']);
	
	if($empid!=''){
		  $sqlConnect="select * from tbl_chat_message where to_empid='".$empid."' order by ID desc";
	
		$myDB=new MysqliDb();
		$result=$myDB->query($sqlConnect);
		$tableValue="";
		//echo "hello";
		//if(mysql_num_rows($result)>0)
		if(count($result)>0 && $result )
		{
		
		       $count=0;
		       $tableValue.="<table border='1' cellpadding='5' cellspacing='5' style='font-size:12px;'>";
		       $tableValue.="<tr>
		       		<td>Srl. No.</td>	
					<td>Date</td>
					<td>Message</td>
				</tr>";
		       foreach($result as $key=>$value)
				{
		        	$count++;
					$tableValue.="<tr>";
					$tableValue.="<td>".$count."</td>";
					$tableValue.="<td>".$value['msg_date']."</td>";
					$tableValue.="<td>".$value['text_msg']."</td>";
					
				$tableValue.="</tr>";
				}	
							
			$tableValue.="</table>";
		
			
		}else{
			$tableValue.="Message not available";
		}
		
		echo $tableValue;
	}
	
}	
?>

				
			
