<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
require(ROOT_PATH.'AppCode/nHead.php');
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login';
		header("Location: $location");
	}
	else
	{
		/* For Survey
		$myDB=new MysqliDb();
		$result_survey = $myDB->query('SELECT * FROM question_bank where id not in (select ques_id from  question_survey where  question_survey.EmployeeID = "'.$_SESSION['__user_logid'].'")');
		
		//$result_survey = $myDB->query('select EmployeeID from survey_details where EmployeeID  ="'.$_SESSION['__user_logid'].'" and survey_done = 1');
		
		if(!empty($result_survey) && count($result_survey) >= 10)
		{
			$linctosurvey= URL.'View/survey.php'; 
			header("Location: $linctosurvey");
			exit();
		}
		*/
			
			$myDB=new MysqliDb();
			$calc_check = $myDB->query('call get_login_calatnd_history("'.$_SESSION['__user_logid'].'")');
			$re=$myDB->getLastError();
			
			if(!$calc_check)
			{
				if($_SESSION["__cm_id"] == "88" )
				{
					$myDB=new MysqliDb();	
					$myDB->query('call save_login_calatnd_history("'.$_SESSION['__user_logid'].'")');
					// For Normal calculation
				
					//$url = URL.'View/check_calcAtnd_Range.php?empid='.$_SESSION['__user_logid'].'&from='.date('Y-m-d',strtotime('-3 days')).'&type=one';

					$url = URL.'View/calcRange_zomato.php?empid='.$_SESSION['__user_logid'].'&type=one&from='.date('Y-m-d',strtotime('-5 days'));
									
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HEADER, false);
					$data = curl_exec($curl);
				}
				else
				{
					$myDB=new MysqliDb();	
					$myDB->query('call save_login_calatnd_history("'.$_SESSION['__user_logid'].'")');
					// For Normal calculation
				
					//$url = URL.'View/check_calcAtnd_Range.php?empid='.$_SESSION['__user_logid'].'&from='.date('Y-m-d',strtotime('-3 days')).'&type=one';

					$url = URL.'View/calcRange.php?empid='.$_SESSION['__user_logid'].'&type=one&from='.date('Y-m-d',strtotime('-5 days'));
									
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HEADER, false);
					$data = curl_exec($curl);
					//var_dump($data);
					//curl_close($curl);
					
					/*$url = URL.'View/calcAtnd_logintime.php?empid='.$_SESSION['__user_logid'].'&month=01&year=2017';
					
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HEADER, false);
					//$data = curl_exec($curl);*/				
					
				}
				}
			
						
			$myDB=new MysqliDb();
			$rsttd = $myDB->query("SELECT created_on FROM tbl_contact_log where EmployeeID = '".$_SESSION['__user_logid']."' order by created_on desc limit 1; ");
			if(count($rsttd) > 0 && $rsttd)
			{
				$iTime_in = new DateTime($rsttd[0]['created_on']);
				$iTime_out =new DateTime();
				$interval = $iTime_in->diff($iTime_out);
				if($interval->format("%a") > 15)
				{
					$linctosurvey=URL.'View/update_mobile_alert.php';
					echo "<script>location.href='".$linctosurvey."'</script>";
					//header("Location: $linctosurvey");
					exit();
				}
			}
			else
			{
				$linctosurvey=URL.'View/update_mobile_alert.php';
				//header("Location: $linctosurvey");
				echo "<script>location.href='".$linctosurvey."'</script>";
				exit();
			}
			
			/*$myDB=new MysqliDb();
			$result_of = $myDB->query('select OnFloor from status_table where EmployeeID = "'.$_SESSION['__user_logid'].'"');
			if(!empty($result_of[0]['status_table']['OnFloor']))
			{
				$iTime_in = new DateTime($result_of[0]['status_table']['OnFloor']);
				$iTime_out =new DateTime();
				$interval = $iTime_in->diff($iTime_out);
				if($interval->format("%a") <= 15)
				{
					$myDB=new MysqliDb();
					$result_ALTOF = $myDB->query('select alerton from atnd_alert_master where EmployeeID = "'.$_SESSION['__user_logid'].'"');
					if(date('Y-m-d',strtotime($result_ALTOF[0]['atnd_alert_master']['alerton'])) != date('Y-m-d',time()))
					{
						$linctosurvey= URL.'View/alert_currentmonth.php'; 
						header("Location: $linctosurvey");
						exit();
					}
				}
			}
			else
			{
				$myDB=new MysqliDb();
				$result_ALTOF = $myDB->query('select alerton from atnd_alert_master where EmployeeID = "'.$_SESSION['__user_logid'].'"');
				if(date('Y-m-d',strtotime($result_ALTOF[0]['atnd_alert_master']['alerton'])) != date('Y-m-d',time()))
				{
					$linctosurvey= URL.'View/alert_currentmonth.php'; 
					header("Location: $linctosurvey");
					exit();
				}
			}
			*/	
			
			//For Refral
			
			//echo "<script>location.href='ref_registration.php'</script>";
			//
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
}
if(isset($_POST['btn_handover']))
{
	$validateBy = $_SESSION['__user_logid'];
	$myDB = new MysqliDb();
	$myDB->query('update doc_al_status set taken=1,takentime = now() where EmployeeID="'.$validateBy.'"');
	
		/*//$alert_msg = 'Click to link button given below for Appointment Letter';
		$link = '<div id="div_offerltr"><a href="#" class="button button-action" data_empID="'.$empID.'" id="a_print_card"><i class="fa fa-link"></i> Appointment Letter for '.$empName.'</a></div>';*/
	
	
}

if(isset($_POST['btn_accessCard']))
{
	$validateBy = $_SESSION['__user_logid'];
	$myDB = new MysqliDb();
	$myDB->query('update access_card_master set confirmation=1,conf_On = now() where EmployeeID="'.$validateBy.'"');
	
		/*//$alert_msg = 'Click to link button given below for Appointment Letter';
		$link = '<div id="div_offerltr"><a href="#" class="button button-action" data_empID="'.$empID.'" id="a_print_card"><i class="fa fa-link"></i> Appointment Letter for '.$empName.'</a></div>';*/
	
	
}
?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">HOME</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<form method="post">
<div class="form-div">
<h4>HOME</h4>	
		<style>
			h2 {
			    display: block;
			    font-size: 1.5em;
			}
			
			.crosscover-item > img {
			    display: block;
			    width: 100%;
				height: 100%;
			}
			.crosscover
			{
				background-color: #fff;
			}
			.crosscover-item.is-active
			{
				z-index: 0!important;
			}
		</style>
		
		<div class="schema-form-section row">
		<!--
		<h3 style="color: #1593FF;text-align: center;border-bottom: 2px solid #1AC11A;box-shadow: 0px 4px 4px -3px rgba(0, 0, 0, 0.86);margin: 0px;padding: 5px;">Welcome To <span style="color: #03A60F;font-weight: bold;"> EMPLOYEE MANAGEMENT SYSTEM </span></h3>-->
		<?php
		
			$myDB=new MysqliDb();
			$chk_task=$myDB->query('call check_task_show("'.$_SESSION['__user_logid'].'")');
			$erro_mys=mysql_error();
			if(count($chk_task) > 0 && $chk_task)
			{
				if($_SESSION['__user_logid'] == "CE10091236")
				{
					echo '<div id="div_bVish" class="slideInDown animated row"  style="position:relative;border: 1px solid #f4f6f7;border-radius: 8px;padding: 15px;/* box-shadow: 0px 0px 6px 0px rgba(43, 196, 218, 0.26),0px 0px 6px 1px rgba(0, 120, 189, 0.38) inset; */background-image: url(../Style/img/happy-birthday.png);background-size: 100% 100%;height: 400px;margin-top: 20px;"><h1 class="text-center" style="color: #2196F3;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.67),1px 1px 1px #165A6F,1px 1px 1px #0C4252,1px 1px 1px #195D84;margin: 2px;">Happy Birthday</h1><h3 style="color: #6B4F23;padding: 0px 30px;text-shadow:1px 1px 1px #BDBDBD;background: #ffffff80;"> Happy Birthday <span style="color: #497B08;">'.$_SESSION['__user_Name'].'</span> !! Have a wonderful happy, healthy birthday and many more to come.We hope that today is the beginning of a great year for you. Happy Birthday. May you have another year of good times and great accomplishments. Here\'s to the boss! </h3><p style="text-align: right;color: green;font-weight: bold;margin-top: 130px;">cogent ems team </p></div>';
				}
				else
				{
					echo '<div id="div_bVish" class="slideInDown animated row"  style="position:relative;border: 1px solid #f4f6f7;border-radius: 8px;padding: 15px;/* box-shadow: 0px 0px 6px 0px rgba(43, 196, 218, 0.26),0px 0px 6px 1px rgba(0, 120, 189, 0.38) inset; */background-image: url(../Style/img/happy-birthday.png);background-size: 100% 100%;height: 400px;margin-top: 20px;"><h1 class="text-center" style="color: #2196F3;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.67),1px 1px 1px #165A6F,1px 1px 1px #0C4252,1px 1px 1px #195D84;margin: 2px;">Happy Birthday</h1><h3 style="color: #6B4F23;padding: 0px 30px;text-shadow:1px 1px 1px #BDBDBD;background: #ffffff80;"> Happy Birthday <span style="color: #497B08;">'.$_SESSION['__user_Name'].'</span> !! Have a wonderful happy, healthy birthday and many more to come.We hope that today is the beginning of a great year for you. Happy Birthday. </h3><p style="text-align: right;color: green;font-weight: bold;margin-top:130px;">cogent ems team</p></div>';	
				}
				
			} 
			
		 ?>
		<?php 
		if(file_exists('../IndexEditPage/content_current/index_help.html'))
		{
			include('../IndexEditPage/content_current/index_help.html');
			include('../View/ref_registration1.php');
		}
		else
		{
			?>
			
			<section class="crousal" style="height: 370px;">
			<div class="crosscover" style="background-image: url('../Style/img/slidemain.jpg');background-position: 100% 100%;background-size: 100% 100%;margin-top: 10px;">

			    <div class="crosscover-list">
			      <a class="crosscover-item" style="background-size: 100% 100%;"  target="_self">
			        <img src="../Style/img/slide4.jpg" alt="image01"/>
			      </a>
			      <div class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;">
			        <img src="../Style/img/slide3.1.jpg" alt="image02"/>
			      </div>
			      <a class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;"  target="_self">
			        <img src="../Style/img/slide3.1.jpg"  alt="image03"/>
			      </a>
			      <div class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;">
			        <img src="../Style/img/slider2.png" alt="image04"/>
			      </div>
			      <a class="crosscover-item" style="background-size: 100% 100%;background-repeat: no-repeat;background-color: #fff;"  target="_self">
			        <img src="../Style/img/slide5.jpg" alt="image05"/>
			      </a>
			    </div>

			  </div>
		</section>
		
			<?php
		}
		?>
		
		
		<hr class="soften"/>
			<section style="margin-top:40px">
			
				<?php 
				
				if($_SESSION['__user_type'] == "ADMINISTRATOR" || $_SESSION['__user_logid'] == "CE10091236")
				{
					$myDB=new MysqliDb();
                    $result=$myDB->query("select count( distinct EmployeeID)  Employee from whole_details_peremp union all select count(distinct client_id)  from client_master union all select count(distinct process)  from new_client_master union all select count(distinct sub_process)  from new_client_master;");

				   foreach($result as $key=>$row)
					{   
					   $value=$value.'|'.$row['Employee'];
					}
					$value=explode('|',$value);
					$counEmployee=$value[1];
					$countClient=$value[2];
					$countProcess=$value[3];
					$countSubproc=$value[4];
					?>
					
					  <div class="row">
					    <div class="col s6 m3 zoomInDown animated">
					      <div class="card">
					        <div class="card-image">
					          <img src="<?php echo STYLE.'Theme/webnet/index_tab_1.png'?>" style="height: 188px;">
					          <span class="card-title white-text text-darken-4 orange darken-3">Employee - <?php echo $counEmployee;?></span>
					        </div>
					       <!-- <div class="card-content">
					          <p><?php echo $counEmployee;?></p>
					        </div>	-->				        
					      </div>
					    </div>
					    
					    <div class="col s6 m3 zoomInDown animated"><!--zoomInDown animated-->
					      <div class="card">
					        <div class="card-image">
					          <img src="<?php echo STYLE.'Theme/webnet/index_tab_2.png'?>" style="    height: 188px;">
					          <span class="card-title white-text text-darken-4 orange darken-3">Client - <?php echo $countClient;?></span>
					        </div>
					        <!--<div class="card-content">
					          <p><?php echo $countClient;?></p>
					        </div>-->					        
					      </div>
					    </div>
					    
					    
					    <div class="col s6 m3 zoomInDown animated">
					      <div class="card">
					        <div class="card-image">
					          <img src="<?php echo STYLE.'Theme/webnet/index_tab_3.png'?>" style="height: 188px;">
					          <span class="card-title white-text text-darken-4 orange darken-3">Process - <?php echo $countProcess;?></span>
					        </div>
					        <!--<div class="card-content">
					          <p><?php echo $countProcess;?></p>
					        </div>-->					        
					      </div>
					    </div>
					 
						<div class="col s6 m3 zoomInDown animated">
						      <div class="card">
						        <div class="card-image">
						          <img src="<?php echo STYLE.'Theme/webnet/index_tab_4.png'?>" style="height: 188px;">
						          <span class="card-title white-text text-darken-4 orange darken-3">Sub Process - <?php echo $countSubproc;?></span>
						        </div>
						       <!-- <div class="card-content">
						          <p><?php echo $countSubproc;?></p>
						        </div>-->					        
						      </div>
						    </div>
					 </div>
					  
					<?php
				}
				?>
				
				
			</section>
		</div>
		
	<?php 
$myDB = new MysqliDb();
$data_val = $myDB->query('SELECT * FROM doc_al_status where EmployeeID = "'.$_SESSION['__user_logid'].'" and validate = 1 and handover = 1;');
if(count($data_val) > 0 && $data_val[0]['handover'] == 1 &&  $data_val[0]['taken'] == 1  &&  date('Ymd',strtotime($data_val[0]['takentime'])) == date('Ymd',time()) && $data_val)
{
	?>
	<div class="had-container">
	<a onclick="javascript:return get_popUp('done',<?php echo "'".date('d M,Y h:m:s A',strtotime($data_val[0]['takentime']))."'";?>);" style="text-shadow: 1px 1px 1px white,1px 1px 1px gray;font-weight: bold;cursor: pointer;border: 2px solid #ec7a03;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 8px -2px #ec1818,1px 1px 8px -2px #867f7f inset;color: #c16a0f;width: calc(100% - 30px);margin-bottom: 10px;margin-left: 15px;margin-right: 15px;" class="waves-effect waves-red modal-trigger" href="#myModal" > <i class="fa fa-thumbs-o-up"></i> Appointment Letter confirmation was done. </a>
</div>
	<?php
}
elseif(count($data_val) > 0 && $data_val[0]['handover'] == 1 &&  $data_val[0]['taken'] == 0 && $data_val)
{
	?>
<div class="had-container no-padding">
	<a onclick="javascript:return get_popUp('','');" style="text-shadow: 1px 1px 1px white,1px 1px 1px gray;font-weight: bold;cursor: pointer;border: 2px solid #ea5700;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 8px -2px gray,1px 1px 8px -2px gray inset;color: #8a371e;width: calc(100% - 30px);margin-bottom: 10px;margin-left: 15px;margin-right: 15px;"  class="waves-effect waves-red modal-trigger" href="#myModal"  ><i class="fa fa-exclamation-triangle"></i> Appointment Letter is <b>Handover</b> to you, Kindly acknowledge the same.</a>
</div>
	<?php
	
} 
$myDB = new MysqliDb();
$accessData = $myDB->query('SELECT * FROM access_card_master where EmployeeID = "'.$_SESSION['__user_logid'].'" and confirmation = 0;');
if(count($accessData) > 0 && $accessData[0]['confirmation'] == 0 && $accessData)
{
	?>
	<div class="had-container no-padding">
	<a class="waves-effect waves-red modal-trigger" style="text-shadow: 1px 1px 1px white,1px 1px 1px gray;font-weight: bold;cursor: pointer;border: 2px solid #ec7a03;padding: 10px;border-radius: 10px;box-shadow: 1px 1px 8px -2px #ec1818,1px 1px 8px -2px #867f7f inset;color: #c16a0f;width: calc(100% - 30px);margin-bottom: 10px;margin-left: 15px;margin-right: 15px;" href="#myModal1"> <i class="fa fa-thumbs-o-up"></i> Access Card confirmation . </a>
</div>


<div id="myModal1" class="modal">
		  
		    <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Confirmation of Access Card</h4>
		      
		      <div class="modal-body">
		        <span class="text-warning" style="float: left;margin-right: 100px;"><b>Confirmation !</b> I hereby confirm that i have received my Access Card [<b>#<?php echo $accessData[0]['card_no']; ?></b>].</span><!--
		        <button type="submit" class="btn waves-effect waves-green" name="btn_accessCard" id="btn_accessCard" >Confirm</button>-->
		        <input type="submit" class="btn waves-effect waves-green" value="Confirm" name="btn_accessCard" id="btn_accessCard">
		        
		      </div>
		      
		    </div>
			<div class="modal-footer">
		        
		        <button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
		      </div>
		  
</div>
	<?php
}
?>
<div id="myModal" class="modal" >
		  
		    <!-- Modal content-->
		    <div class="modal-content">
		      <h4 class="col s12 m12 model-h4">Confirmation of Appointment Letter</h4>
		      <div class="modal-body">
		      	<p>No Data Found</p>
		      </div>
		    </div>
			<div class="modal-footer">
		        <button type="button" class="btn waves-effect modal-action modal-close waves-red close-btn">Close</button>
		    </div>
</div>

	<style>
		
div.formpage {
    width: 750px !important;
}

.tableArrange {
    padding-left: 15px;
    padding-top: 15px;
}

.draggable {
    width: 90px;
    height: 90px;
    padding: 0.5em;
    float: left;
    margin: 0 10px 10px 0;
}

#containment-wrapper-1 {
    width: 95%;
    height: 150px;
    border: 2px solid #ccc;
    padding: 10px;
}

.panel-preview{
    cursor: auto !important;
}


.no-border .top .left,  .no-border .top .right, .no-border .top .middle{
    background: none !important;
}
.no-border .bottom .left,  .no-border .bottom .right, .no-border .bottom .middle{
    background: none !important;
}
.no-border .maincontent {
    border: none !important;
}

.config-listing{
    margin-top: 10px;
    width: 100%;
}

.config-listing thead tr th{
    text-align: center;
    padding: 5px 10px;
}
.config-listing tbody tr td{
    text-align: left;
    padding: 3px 3px 3px 5px;
}

.config-listing thead tr {
    background-color: #F28C38;
}

.config-listing tbody tr {
    background-color: #FFF3D6;
}

.config-wrapper {
    margin-bottom: 30px;

}

.ui-widget-content{
    background: none !important;
}
/*
.ui-resizable, .ui-draggable { position: absolute !important;}*/

.panel_draggable {
    padding: 0px;
    float: left;
    display: block;
    margin: 0px;
    cursor: move;
    overflow : hidden;
    position: relative !important;
}

tr.tableArrangetr td{
    padding-bottom: 3px;
    padding-left: 10px;
}

fieldset.panel_resizable legend{
    font-weight: bold;
    font-size: 14px;
    margin-left: 10px;
    padding-top: 1px;
}

.panel_wrapper{
    overflow: hidden !important;

}
.clear{
    float: none !important;
    clear: both !important;
}

.outerbox {
    display: inline-block;
    float: left;
}
.dashboardCard-title {
    text-align: center;
    font-size: 14px;
    font-weight: 700;
    line-height: 50px;
    margin-left:-12px;
    padding-left: 30px;
    padding-right: 30px;
    height:40px;
    margin-top: 0;
    margin-bottom: 0;
    padding-top: 0px;
    padding-bottom: 0px;
}
div.dashboardCard-title-for-card{
    font-size: 14px;
    font-weight: 700;
    line-height: 50px;
    margin-top: 0;
    margin-left:-16px;
    padding-left: 50px;
    padding-right: 30px;
    text-align: left;
    
}
.panel_resizable {
    border: 0px !important;
}
.collection .collection-item.avatar {
    min-height: 30px;
    padding: 15px;
}
.collection .collection-item:hover,.collection .collection-item:active,.collection .collection-item:focus{
	background-color: #fff;
}

.item-container .collection .collection-item.avatar .title {
    font-size: 13px;
}
#anouncementOnDashboard .collection .collection-item.avatar,#updatesOnDashboard .collection .collection-item.avatar {
    
    min-height: 70px !important;
    border-bottom: 1px solid #e0e0e0;
    padding-left: 30px !important;
    padding-right: 50px  !important;
}


</style>
	<div id="card" class="col s12 no-padding"  style="box-shadow: none;background: #fff;">
	<div id="card" class="col s6"  style="box-shadow: none;background: #fff;">
    <div id="panel_resizable_2_9" class="card ohrm-card "  style=" height: 400px;border: 1px solid #f2f2f2;">
    <div class="dashboardCard-title-for-card">Announcement</div>
                            
    <div class="" style="height: 100%;" id="dashboard__viewAnnouncementOnDashboard">
	    <p></p>
	    <div class="col s12 m12 center">
	    	
	    <div class="preloader-wrapper big active">
      <div class="spinner-layer spinner-blue">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-red">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-yellow">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-green">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>
		
	    </div>
	</div>


    <script type="text/javascript">

        $(document).ready(function () {
            var moduleUrl = <?php echo "'".URL.'View/announcement.php'."'";?>;
            var divId = 'dashboard__viewAnnouncementOnDashboard';

            var loaderCallback = function() {
                $.ajax({
                    url: moduleUrl,
                    cache: true,
                    success: function (obj) {
                        $.ajaxSetup({
                            // Enable caching of AJAX responses
                            cache: true
                        });
                        $("#" + divId).html(obj);
                    },
                    complete: function () {
                        $("#" + divId).removeClass('loadmask');
                        
                    }
                });

            };

            if (document.readyState == 'complete') {
                loaderCallback();
            } else {
                $(window).load(function () {
                    loaderCallback();
                });
            }
        });
    </script>
    </div> 
	</div>	
	<div id="card" class="col s6"  style="box-shadow: none;background: #fff;">
    <div id="panel_resizable_2_9" class="card ohrm-card "  style=" height: 400px;border: 1px solid #f2f2f2;">
    <div class="dashboardCard-title-for-card">Updates</div>
                            
    <div class="" style="height: 100%;" id="dashboard__viewUpdatesOnDashboard">
	    <p></p>
	    <div class="col s12 m12 center">
	    	
	    <div class="preloader-wrapper big active">
      <div class="spinner-layer spinner-blue">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-red">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-yellow">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-green">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>
		
	    </div>
	</div>


    <script type="text/javascript">
		$(document).on("click",".head-avatar",function(){
        	var itm_check = $(this).children("i").text();
        	$(".head-avatar").closest("span").siblings("div.body-avatar").addClass("hidden");
        	$(".head-avatar").children("i").text("keyboard_arrow_down");  
        	 	
        	if(itm_check == "keyboard_arrow_down")
        	{
				$(this).closest("span").siblings("div.body-avatar").removeClass("hidden");        	
	        	 $(this).children("i").text("keyboard_arrow_up");
			}
			
		       
		    
        });
        $(document).ready(function () {
            var moduleUrl = <?php echo "'".URL.'View/updates.php'."'";?>;
            var divId = 'dashboard__viewUpdatesOnDashboard';

            var loaderCallback = function() {
                $.ajax({
                    url: moduleUrl,
                    cache: true,
                    success: function (obj) {
                        $.ajaxSetup({
                            // Enable caching of AJAX responses
                            cache: true
                        });
                        $("#" + divId).html(obj);
                    },
                    complete: function () {
                        $("#" + divId).removeClass('loadmask');
                       
                    }
                });

            };

            if (document.readyState == 'complete') {
                loaderCallback();
            } else {
                $(window).load(function () {
                    loaderCallback();
                });
            }
        });
    </script>
    </div> 
	</div>
	</div>
</div>	
 </form>   
</div>

</div>

<script>
	$(function(){
		$('button').click(function(event ){			
			event.preventDefault();
			//$(document).on("keydown", disableF5);
			
		});
		$('#div_bVish').delay(60000).fadeOut();
		$('.modal').modal();
	});
	function get_popUp(el,el1)
	{
		if(el !='' && el =='done')
		{
			$('.modal-body').html('<div><span class="text-warning" style="float: left;margin-right: 100px;"></span></div><div class="alert alert-success">Confirmation of Appointment Letter was done by you on <b>'+el1+'</b>.</div>');		
		
		}
		else if(el =='' && el !='done')
		{
			$('.modal-body').html('<span class="text-warning" style="float: left;margin-right: 100px;">Confirmation !Appointment Letter Handover</span><input type="submit" class="waves-effect waves-light btn" value="Confirm" name="btn_handover" id="btn_handover">');		
		
		}
		
		
	}
</script>
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>
