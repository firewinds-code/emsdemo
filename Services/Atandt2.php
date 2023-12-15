  <?php
 #ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
 include 'Include/config.php';
include 'DBConnect/MysqliDb.php';
  //session_start();// Starting Session
   
  //print_r( $_SESSION['login_user']);
  
   /*if(!isset($_SESSION['login_user'])){
			 header('Location: index.php?error=1'); // Redirecting To Home Page
		}
		else{
			$_SESSION['page'] = "Atnd";
			//print_r($_SESSION['login_user']);
			//exit;
			$empname=$_SESSION['login_user']['emp_name'];
			$empid=$_SESSION['login_user']['emp_id'];
		}*/
		///	$empname="Surya ";
			//$empid='CE0820912502';
			
			
		
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>COGENT| Attendance </title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <!--<link href="css/font-awesome.min.css" rel="stylesheet">-->

 	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
 	
    <!-- Custom Theme Style -->
    <!--<link href="css/custom-menu.min.css" rel="stylesheet">-->
    
    <link href="css/custom.min.css" rel="stylesheet">
    <link href="css/custom1.css" rel="stylesheet">
	 <script src="js/jquery.min.js"></script>
  </head>
<style>
h2:focus, h2:active {
  color: green;
}
</style>

<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}


</style>

<?php 

	/*$myDB=new MysqliDb();
		$calc_check = $myDB->query('call get_login_calatnd_history("'.$_SESSION['emp_id'].'")');
		$re=$myDB->getLastError();
		
		if(!$calc_check)
		{
			if($_SESSION['cm_id'] == "88" )
			{
				$myDB=new MysqliDb();	
				$myDB->query('call save_login_calatnd_history("'.$_SESSION['emp_id'].'")');
				$url = URL.'calcRange_zomato.php?empid='.$_SESSION['emp_id'].'&type=one&from='.date('Y-m-d',strtotime('-10 days'));
								
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
			}
			else
			{
				$myDB=new MysqliDb();	
				$myDB->query('call save_login_calatnd_history("'.$_SESSION['emp_id'].'")');
				$url = URL.'calcRange.php?empid='.$_SESSION['emp_id'].'&type=one&from='.date('Y-m-d',strtotime('-10 days'));				
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
			}
		}*/
	
	if(isset($_GET['month']))
	{
		$month=$_GET['month'];
	}
	else
	{
		//$month=date('m');
		$month=8;
	}
//$month=date('m');
$year=date('Y');

$Data['month']=$month;
			$Data['year']=$year;
			$Data['EmployeeID']='CE0820912502';
$CurrontMonthDay=cal_days_in_month(CAL_GREGORIAN,$month,$year);
// empid, date, RosterIn, RosterOut
// CE10091234 2020-05-01 9:00		18:00
// CE10091234 2020-05-02 10:00		19:00
// CE10091234 2020-05-04 10:00		19:00 -
	$myDB =  new MysqliDb();
	$myDB=new MysqliDb();

				
				$CurrontMonthDay=cal_days_in_month(CAL_GREGORIAN,$Data['month'],$Data['year']);
$attnd="SELECT D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 FROM attandance  where Year = '".$Data['year']."' AND Month = '".$Data['month']."' AND EmployeeID = '".$Data['EmployeeID']."'";
						$resattnd =$myDB->query($attnd);
				
$hours_hlp="SELECT D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31 FROM hours_hlp  where Year = '".$Data['year']."' AND Month = '".$Data['month']."' AND EmployeeID = '".$Data['EmployeeID']."'";
						$reshours_hlp =$myDB->query($hours_hlp);
						//echo "<pre>";print_r($reshours_hlp);exit;
						
$bio="SELECT EmpID,DateOn,CAST(MIN(`biometric`.`PunchTime`) AS TIME) AS `InTime`,(CASE WHEN ((TIME_TO_SEC(TIMEDIFF(MAX(`biometric`.`PunchTime`),MIN(`biometric`.`PunchTime`))) / 3600) > 2) THEN CAST(MAX(`biometric`.`PunchTime`) AS TIME) ELSE NULL END) AS `OutTime` FROM biometric where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND Empid = '".$Data['EmployeeID']."' group by Dateon ,EmpID;";
//$bio="SELECT Empid,cast(DateOn as date) as  DateOn, cast( min(PunchTime) as time) InTime, cast( max(PunchTime) as time) OutTime FROM biometric  where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND Empid = '".$Data['EmployeeID']."' group by  Empid ,cast(DateOn as date) order by DateOn";
						$resbio =$myDB->query($bio);
//print_r($resbio);exit;
$rost=" SELECT InTime as roasterIn, OutTime as roasterOut,DateOn FROM roaster  where YEAR(DateOn) = '".$Data['year']."' AND MONTH(DateOn) = '".$Data['month']."' AND EmployeeID = '".$Data['EmployeeID']."' order by DateOn";
						$resrost =$myDB->query($rost);
						
					$attandanceList=array();
				$r=$b=0;
				 for($i=1;$i <= $CurrontMonthDay ;$i++)
				 {
						$fdate=$Data['year'].'-'.$Data['month'].'-'.$i;
						$data['id']=$i;
						$data['dayName']=date("l",strtotime($fdate));
						/*attnd start*/
						if( count($resattnd) > 0 )
						{
							$data['attandance'] =$resattnd[0]['D'.$i];
							
							}
							else{
								$data['attandance'] = 'NA';
							}
						
						/*attnd end*/
						/*net hours*/
					if( count($reshours_hlp) > 0 )
						{
						 $data['netHours'] = $reshours_hlp[0]['D'.$i];
						/*net hours end*/
						}
						else{
						$data['netHours']  = '00:00';
						}
						$data['date'] =  $fdate;
						/*bio matric*/
						
						if( count($resbio) > 0 && count($resbio) > $b )
						{
							
						$dateon =  date('d',strtotime($resbio[$b]['DateOn']) );
							if($dateon==$i)
							{
								$data['InTime'] =  $resbio[$b]['InTime'] ;
								$data['OutTime'] = $resbio[$b]['OutTime'] ;
								$b++;
							}
							else
							{
								$data['InTime'] = '00:00' ;
								$data['OutTime'] = '00:00' ;
							}
						
						}
						else{
							$data['InTime'] = '00:00' ;
							$data['OutTime'] = '00:00' ;
						}
							/*bio matric end*/
						
						if( count($resrost) > 0 && count($resrost) > $r )
						{
							$dateon =  date('d',strtotime($resrost[$r]['DateOn']) );
							if($dateon==$i)
							{
								$data['roasterIn'] =  $resrost[$r]['roasterIn'] ;
								$data['roasterOut'] = $resrost[$r]['roasterOut'] ;
								$r++;
							}
							else
							{
								$data['roasterIn'] = '00:00' ;
								$data['roasterOut'] = '00:00' ;
							}
						
						}
						else{
						$data['roasterIn'] =  '00:00' ;
						$data['roasterOut'] = '00:00' ;
						}

						$attandanceList[]=$data;
					}
				 
				
				
				 $rosterData['EmployeeID']=$Data['EmployeeID'];
				 $rosterData['year']=$Data['year'];
				 $rosterData['month']=$Data['month'];
				  $rosterData=$attandanceList;

			//echo"<pre>";print_r($rosterData);exit;

?>

  <body class="login">
 
 
	<div class="">
	<div class="row" style="margin-top:10px; margin-right: 0px;" align="center">
			
		
			<?php include('Config/left_menu.php'); ?>
		</div>
	
     
		<div class="row" style="margin-top:5px">
			
		
			<div class="col-md-10 col-md-offset-1">
				<div class="x_panel">
				
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " style="padding:10px" align="center">
					
					<img src="images/logo-1.png" class="imageclass" width="150px" height="75px">&nbsp;&nbsp;
					
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " style="padding:10px" align="center">
					
					 <span style="font-size:15px;"> <?php echo strtoupper($_SESSION['emp_name']);?> </span>|<a href="index?logout=1" style="color:red !important;margin-bottom:10px:">  Logout</a>
					
				</div>
				
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 " style="padding:10px" align="center">
						 <button class="btn btn-primary" ><a href="Atandt.php?month=<?php echo $month-1;?>"style="color:#fff" ><< Prev </a> </button>
						 <span style="font-size:20px;padding:20px"><?php echo date('F',strtotime($year.'-'.$month.'-1'))?> </span>
						 <button class="btn btn-primary" ><a href="Atandt.php?month=<?php echo $month + 1?>"  style="color:#fff">Next >></a></button>
					</div>
				</div>
			</div>
		</div><br>
     
        <div class="row">
				<div class="col-md-10 col-md-offset-1">
		
                    <div class="row ">
                     
						<?php for($i=0;$i <= $CurrontMonthDay-1 ;$i++){?>
						   <?php $day=$i+1;?>
						  <?php  $Newdate=$year.'-'.$month.'-'.$day;
						  $currentday=date('d');
						  $activeday= $currentday-2;
						  if($activeday==$day)
						  {
							  $id='id="active"';
						  }
						  else{
							  $id="";
						  }
						  ?>
                      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 " >
                        <div class="x_panel">
						
						
                          <div class="x_title" >

                         
                            <h2><a  <?php echo $id ?> href="#"><?php echo date("l",strtotime($Newdate)) ;?> [ <?php echo $day;?> ] </a></h2>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">

                            <div>
                              <ul class="list-inline widget_tally">
                                <li>
                                  <p>
                                    <span class="">Attendance </span>
                                    <span class="pull-right"><?php echo $rosterData[$i]['attandance'];?></span>
                                  </p>
                                </li> 
								
								<li>
                                  <p>
                                    <span class="">Roster </span>
                                    <span class="pull-right"><?php echo $rosterData[$i]['roasterIn'].'-'.$rosterData[$i]['roasterOut'];?></span>
                                  </p>
                                </li>
								<li>
                                  <p>
                                    <span class="">InTime  </span>
                                    <span class="pull-right"><?php echo $rosterData[$i]['InTime'];?></span>
                                  </p>
                                </li>
                                <li>
                                  <p>
                                    <span class="">OutTime  </span>
                                    <span class="pull-right"><?php echo $rosterData[$i]['OutTime'];?></span>
                                  </p>
                                </li>
                                <li>
                                  <p>
                                    <span class="">NetHours </span>
                                    <span class="pull-right"><?php echo $rosterData[$i]['netHours'];?></span>
                                  </p>
                                </li> 
								<li>
                                  <p>
                                    <span class="">Performance </span>
                                    <span class="pull-right"><?php echo '00:00';?></span>
                                  </p>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
						<?php }?>
                    

                    </div>
                    </div>
                    </div>
                
     <a href="#active" type="button" id="watchButton"> </a>
        
    </div>
	<script>
$(document).ready(function() {
	//$('#watchButton').click();
	//$("#watchButton").trigger('click'); 
	 document.getElementById("watchButton").click();
	  document.getElementById('active').scrollIntoView();
});


</script>
   <?php
	include_once 'Config/menuscript.php';	
?>
</script>
   
    </div>
  </body>
</html>

