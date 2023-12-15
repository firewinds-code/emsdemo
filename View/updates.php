<?php  
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');

?>
<div class="item-container" id="updatesOnDashboard">
		    <div class="inner">
		        <ul class="collection" style="width:100%;">
		                        <?php 
 	
								 		$myDB=new MysqliDb();
								 		$query="SELECT Heading, createdon,Body FROM updates order by createdon,id desc";
								 		$result=$myDB->query($query);
								 		if(count($result) > 0 && $result)
								 		{
											foreach($result as $key=>$value)
											{
												
												?>
													 	<li class="collection-item avatar">
								                        <div class="row" style="margin: 0px;">
								                            <span style="width: 85%;">
								                                <a class=" topic left-space head-avatar" style="width: 80%;float:left;"><i class="material-icons left tiny">keyboard_arrow_down</i><?php echo $value['Heading']; ?></a></span>
								                            <span style="width: fit-content;float:right"><?php echo date('d M,Y',strtotime($value['createdon']));?></span>
								                            <div class="col s12 m12 l12 body-avatar hidden">
								                            	<pre><?php echo $value['Body'];?></pre>
								                            </div>
								                        </div>
								                        
								                    	</li>
												<?php
												
											}
										}
								 	?>
		        </ul>
		    </div>
		</div>
		<div class="count-text"> 
		    <div class="left" style="display: inline;margin-left: 30px;">
		         
		    </div>
		    <div class="right" style="display: inline;margin-right: 18px;padding-top: 5px;font-size: 13px;">
		        <!--Showing: <?php echo count($result); ?> / <?php echo count($result); ?> -->   </div>
		</div>
 	
 	