<?php  
// Server Config file
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH.'AppCode/nHead.php');
// Global variable used in Page Cycle
$value=$counEmployee=$countProcess=$countClient=$countSubproc=0;
if(isset($_SESSION))
{
	if(!isset($_SESSION['__user_logid']))
	{
		$location= URL.'Login'; 
		header("Location: $location");
		exit();
	}
	else
	{		
	}
}
else
{
	$location= URL.'Login'; 
	header("Location: $location");
	exit();
}
$date_To = date('Y-m-d');
$date_From=date('Y-m-d', strtotime($date_To . "-2 months") );  
?>
<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content" > 

<!-- Header Text for Page and Title -->
<span id="PageTittle_span" class="hidden">Pendency Notification</span>

<!-- Main Div for all Page -->
<div class="pim-container row" id="div_main" >

<!-- Sub Main Div for all Page -->
<div class="form-div">

<!-- Header for Form If any -->
	 <h4>Pendency Notification <img alt="i" src="../Style/img/notification/announcement-icon sm.png" style="height: 24px;margin-bottom: -6px !important;"></h4>				

<!-- Form container if any -->
	<div class="schema-form-section row" >

<?php
if($tmp_countpendancy_hdr <= 0)
{
echo '<br/> <br/><h3 class="text-center text-success" style="font-size: 32;box-shadow: none;border-radius: 5px;padding: 12px;text-shadow: 1px 1px 1px #1a3e1a;color: #8bc640;border: 3px solid #ffc521;">Congratulations, <span style="color:#1dacc4">No Pendency Found !</span> <i class="fa fa-smile-o" style="color:#ffc521"></i></h3>';
?>
<script type="text/javascript">
// <![CDATA[
var bits=80; // how many bits
var speed=20; // how fast - smaller is faster
var bangs=15 // how many can be launched simultaneously (note that using too many can slow the script down)
var colours=new Array("#03f", "#f03", "#0e0", "#93f", "#0cf", "#f93", "#f0c"); 
//                     blue    red     green   purple  cyan    orange  pink

/****************************
*      Fireworks Effect     *
*(c)2004-14 mf2fm web-design*
*  http://www.mf2fm.com/rv  *
* DON'T EDIT BELOW THIS BOX *
****************************/
var bangheight=new Array();
var intensity=new Array();
var colour=new Array();
var Xpos=new Array();
var Ypos=new Array();
var dX=new Array();
var dY=new Array();
var stars=new Array();
var decay=new Array();
var swide=800;
var shigh=600;
var boddie;

if (typeof('addRVLoadEvent')!='function') function addRVLoadEvent(funky) {
  var oldonload=window.onload;
  if (typeof(oldonload)!='function') window.onload=funky;
  else window.onload=function() {
    if (oldonload) oldonload();
    funky();
  }
}

addRVLoadEvent(light_blue_touchpaper);

function light_blue_touchpaper() { if (document.getElementById) {
  var i;
  boddie=document.createElement("div");
  boddie.style.position="fixed";
  boddie.style.top="0px";
  boddie.style.left="0px";
  boddie.style.overflow="visible";
  boddie.style.width="1px";
  boddie.style.height="1px";
  boddie.style.backgroundColor="transparent";
  document.body.appendChild(boddie);
  set_width();
  for (i=0; i<bangs; i++) {
    write_fire(i);
    launch(i);
    setInterval('stepthrough('+i+')', speed);
  }
}}

function write_fire(N) {
  var i, rlef, rdow;
  stars[N+'r']=createDiv('|', 12);
  boddie.appendChild(stars[N+'r']);
  for (i=bits*N; i<bits+bits*N; i++) {
    stars[i]=createDiv('*', 13);
    boddie.appendChild(stars[i]);
  }
}

function createDiv(char, size) {
  var div=document.createElement("div");
  div.style.font=size+"px monospace";
  div.style.position="absolute";
  div.style.backgroundColor="transparent";
  div.appendChild(document.createTextNode(char));
  return (div);
}

function launch(N) {
  colour[N]=Math.floor(Math.random()*colours.length);
  Xpos[N+"r"]=swide*0.5;
  Ypos[N+"r"]=shigh-5;
  bangheight[N]=Math.round((0.5+Math.random())*shigh*0.4);
  dX[N+"r"]=(Math.random()-0.5)*swide/bangheight[N];
  if (dX[N+"r"]>1.25) stars[N+"r"].firstChild.nodeValue="/";
  else if (dX[N+"r"]<-1.25) stars[N+"r"].firstChild.nodeValue="\\";
  else stars[N+"r"].firstChild.nodeValue="|";
  stars[N+"r"].style.color=colours[colour[N]];
}

function bang(N) {
  var i, Z, A=0;
  for (i=bits*N; i<bits+bits*N; i++) { 
    Z=stars[i].style;
    Z.left=Xpos[i]+"px";
    Z.top=Ypos[i]+"px";
    if (decay[i]) decay[i]--;
    else A++;
    if (decay[i]==15) Z.fontSize="7px";
    else if (decay[i]==7) Z.fontSize="2px";
    else if (decay[i]==1) Z.visibility="hidden";
	if (decay[i]>1 && Math.random()<.1) {
	   Z.visibility="hidden";
	   setTimeout('stars['+i+'].style.visibility="visible"', speed-1);
	}
    Xpos[i]+=dX[i];
    Ypos[i]+=(dY[i]+=1.25/intensity[N]);

  }
  if (A!=bits) setTimeout("bang("+N+")", speed);
}

function stepthrough(N) { 
  var i, M, Z;
  var oldx=Xpos[N+"r"];
  var oldy=Ypos[N+"r"];
  Xpos[N+"r"]+=dX[N+"r"];
  Ypos[N+"r"]-=4;
  if (Ypos[N+"r"]<bangheight[N]) {
    M=Math.floor(Math.random()*3*colours.length);
    intensity[N]=5+Math.random()*4;
    for (i=N*bits; i<bits+bits*N; i++) {
      Xpos[i]=Xpos[N+"r"];
      Ypos[i]=Ypos[N+"r"];
      dY[i]=(Math.random()-0.5)*intensity[N];
      dX[i]=(Math.random()-0.5)*(intensity[N]-Math.abs(dY[i]))*1.25;
      decay[i]=16+Math.floor(Math.random()*16);
      Z=stars[i];
      if (M<colours.length) Z.style.color=colours[i%2?colour[N]:M];
      else if (M<2*colours.length) Z.style.color=colours[colour[N]];
      else Z.style.color=colours[i%colours.length];
      Z.style.fontSize="13px";
      Z.style.visibility="visible";
    }
    bang(N);
    launch(N);
  }
  stars[N+"r"].style.left=oldx+"px";
  stars[N+"r"].style.top=oldy+"px";
} 

window.onresize=set_width;
function set_width() {
  var sw_min=999999;
  var sh_min=999999;
  if (document.documentElement && document.documentElement.clientWidth) {
    if (document.documentElement.clientWidth>0) sw_min=document.documentElement.clientWidth;
    if (document.documentElement.clientHeight>0) sh_min=document.documentElement.clientHeight;
  }
  if (typeof(self.innerWidth)!="undefined" && self.innerWidth) {
    if (self.innerWidth>0 && self.innerWidth<sw_min) sw_min=self.innerWidth;
    if (self.innerHeight>0 && self.innerHeight<sh_min) sh_min=self.innerHeight;
  }
  if (document.body.clientWidth) {
    if (document.body.clientWidth>0 && document.body.clientWidth<sw_min) sw_min=document.body.clientWidth;
    if (document.body.clientHeight>0 && document.body.clientHeight<sh_min) sh_min=document.body.clientHeight;
  }
  if (sw_min==999999 || sh_min==999999) {
    sw_min=800;
    sh_min=600;
  }
  swide=sw_min;
  shigh=sh_min;
}
// ]]>
</script>
				<?php
			}
else
{
			$colorset[0] = array("linear-gradient(#b8efba,#9ed2a0)","#316d33","#4CAF50","slideInDown");
			$colorset[1] = array("linear-gradient(#8defff,#23dfff)","#058196","#1790a5","bounce");
			$colorset[2] = array("linear-gradient(#ffe59d,#ffc82c)","#d46412","#FF9800","slideInUp");
			$count = 0;
			$div_downtime = '';
			
			$dt_info ='';
			$myDB  =new MysqliDb();
			$data_info  = $myDB->query("select count(EmpID) count from downtime where FAID ='".$_SESSION['__user_logid']."' and FAStatus = 'Pending' and CreatedOn >= '".$date_From."'");
			$total= 0;
			
			if(count($data_info) > 0 && $data_info)
			{
				foreach($data_info as $key=>$value)
				{
					//if($value[0]['count'] > 0)
					//$dt_info .= '<div class="col s12 m12 " style="padding: 4px;border: 1px solid #d6d6d6;border-radius: 5px;font-weight: bold;font-size: 11px;background: rgba(255, 255, 255, 0.44);margin-top: 2px;color:black;"><span>&nbsp;&nbsp; <b>'.$value[0]['count'].'</b> request pending at First Approver Level</span></div>';
					$total += $value['count'];
				}	
			}
			$myDB  =new MysqliDb();
			$data_info  = $myDB->query("select count(EmpID) count from downtime where RTID ='".$_SESSION['__user_logid']."' and (RTStatus = 'Pending' and FAStatus = 'Approve') and CreatedOn >= '".$date_From."'");
			if(count($data_info) > 0 && $data_info)
			{
				foreach($data_info as $key=>$value)
				{
					//if($value[0]['count'] > 0)
					//$dt_info .= '<div class="col s12 m12 fadein animated" style="padding: 4px;border: 1px solid #d6d6d6;border-radius: 5px;font-weight: bold;font-size: 11px;background: rgba(255, 255, 255, 0.44);margin-top: 2px;color:black;"><span>&nbsp;&nbsp; <b>'.$value[0]['count'].'</b> request pending at Second Approver Level</span></div>';
					$total +=$value['count'];
					$colorset_tt = $colorset[0];
					if( $total <= 3 )
					{
						$colorset_tt = $colorset[0];
					}
					elseif($total > 3 && $total <= 10)
					{
						$colorset_tt = $colorset[1];
					}
					else
					{
						$colorset_tt = $colorset[2];
					}
					//$expression  = '<i class="fa fa-frown-o"></i>';
					$expression  = '<i class="fa fa-frown-o" style="color:red !important"></i>';
					if($total === 0 )
					{
						//$expression = '<i class="fa fa-smile-o"></i>';
						$expression = '<i class="fa fa-smile-o" style="color:green !important"></i>';
					}
					
					$div_downtime .= '<a href="downtime.php" >
					<div class=" col s4 m4 not_div '.$colorset_tt[3].' animated">
					<div class="col s12 m12">
					<h6 style="background: '.$colorset_tt[0].';color: '.$colorset_tt[1].';text-align: left;border-bottom: 1px solid '.$colorset_tt[2].';box-shadow: 0px 4px 4px -3px #9E9E9E;margin: 0px;padding: 5px;border-left: 4px solid '.$colorset_tt[2].';margin-top: 1px;border-right: 4px solid '.$colorset_tt[2].';border-top: 1px solid '.$colorset_tt[2].';font-size: 20px;text-align: center;">
					<span><strong>&nbsp;&nbsp;Downtime&nbsp;|&nbsp;</strong></span>
					<span  style="font-size: 13px;font-weight: bold;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.82);">Total Request </span>
					&nbsp;&nbsp;<p class="counter">'.$total.'</p> &nbsp;'.$expression.'</h6>
					</div></div></a>';
				}
				
			}
			
			
			
			if($total >= 0)
			{
				$count = 1;
				//$div_downtime = '<a href="downtime.php"><div style="border: 1px solid #8BC34A;padding-left: 10px;background: rgba(76, 175, 80, 0.24);    " class="col-sm-6 not_div"><div class="col-sm-2 fadein animated" style="padding: 0px;"><img alt="D" src="../Style/img/notification/Letter_Dicon.png" style="width: 80px;height: 80px;"/></div><div class="col-sm-10"><h6 style="background: linear-gradient(#b8efba,#9ed2a0);color: #316d33;text-align: left;border-bottom: 1px solid #4CAF50;box-shadow: 0px 4px 4px -3px #9E9E9E;margin: 0px;padding: 5px;border-left: 4px solid #4CAF50;margin-top: 1px;"><span><strong>&nbsp;&nbsp;Downtime</strong></span><span>&nbsp;|&nbsp;Request count </span>&nbsp;&nbsp;<kbd>'.$total.'</kbd><span style="color: black;font-weight: bold;">&nbsp;|&nbsp;</span></h6>'.$dt_info.'</div></div></a>';
				
				
			}
					
			
			$total = 0;
			$div_exception1  = '';
			$exp_info ='';
			$myDB  =new MysqliDb();
			
			//$data_info  = $myDB->query("select  Exception,sum(count) count from (SELECT Exception,count(*) count FROM exception inner join whole_details_peremp on whole_details_peremp.EmployeeID = exception.EmployeeID where MgrStatus='Pending' and exception.EmployeeID!='".$_SESSION['__user_logid']."' and whole_details_peremp.account_head = '".$_SESSION['__user_logid']."' and exception.CreatedOn >= '".$date_From."' group by Exception union all SELECT Exception,count(*) count FROM exception inner join whole_details_peremp on whole_details_peremp.EmployeeID = exception.EmployeeID where MgrStatus='Pending' and exception.EmployeeID = whole_details_peremp.account_head and whole_details_peremp.ReportTo = '".$_SESSION['__user_logid']."' and exception.CreatedOn >= '".$date_From."' group by Exception ) t1 group by Exception");			
			$data_info  = $myDB->query("SELECT Exception,count(*) count FROM exception inner join module_master_new t1 on t1.EmployeeID = exception.EmployeeID where MgrStatus='Pending' and t1.l1empid = '".$_SESSION['__user_logid']."' and t1.module_name='Exception' and exception.CreatedOn >= '".$date_From."' group by Exception");			
			
			$arra_exception = array('Back Dated Leave','Biometric issue','Roster Change','Shift Change','Working on Leave','Working on WeekOff');
			if(count($arra_exception) > 0 && $arra_exception)
			{
				foreach($arra_exception as $key=>$value)
				{
					//if($value[0]['count'] > 0)
					//$exp_info .= '<div class="col-sm-6 fadein animated" style="padding: 4px;border: 1px solid #d6d6d6;border-radius: 5px;font-size: 11px;background: rgba(255, 255, 255, 0.44);margin-top: 2px;color:black;"><span>&nbsp;&nbsp; <b>'.$value['Exception'].' </b>: '.$value[0]['count'].' Pending</span></div>';
					
					$Exception = $value;
					$exception_count = 0;
					if(count($data_info) > 0 && $data_info)
					{
						foreach($data_info as $key=>$value)
						{
							if($value['Exception'] == $Exception)
							{
								$exception_count = $value['count'];
								
							}
						}	
					}
					
					$colorset_tt = $colorset[0];
					if( $exception_count <= 3 )
					{
						$colorset_tt = $colorset[0];
					}
					elseif($exception_count > 3 && $exception_count <= 10)
					{
						$colorset_tt = $colorset[1];
					}
					else
					{
						$colorset_tt = $colorset[2];
					}
					//$expression = '<i class="fa fa-frown-o" style="color:red"></i>';
					$expression  = '<i class="fa fa-frown-o" style="color:red !important"></i>';
					if($exception_count === 0 )
					{
						$expression = '<i class="fa fa-smile-o" style="color:green !important"></i>';
					}
					$div_exception1 .= '<a href="addRequest.php" ><div style="padding-left: 10px;padding-bottom: 7px;" class=" col s4 m4 not_div '.$colorset_tt[3].' animated"><div class="col s12 m12"><h6 style="background: '.$colorset_tt[0].';color: '.$colorset_tt[1].';text-align: left;border-bottom: 1px solid '.$colorset_tt[2].';box-shadow: 0px 4px 4px -3px #9E9E9E;margin: 0px;padding: 5px;border-left: 4px solid '.$colorset_tt[2].';margin-top: 1px;border-right: 4px solid '.$colorset_tt[2].';border-top: 1px solid '.$colorset_tt[2].';font-size: 20px;text-align: center;"><span><strong>&nbsp;&nbsp;Exception&nbsp;|&nbsp;</strong></span><span style="font-size: 13px;font-weight: bold;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.82);">'.$Exception.' </span>&nbsp;&nbsp;<p class="counter">'.$exception_count.'</p> &nbsp;'.$expression.'</h6></div></div></a>';
					
					$total +=$exception_count;
					
					
				}
			}
			if($total >= 0)
			{
				$count = 1;
				//$div_exception1 = '<a href="addRequest.php"><div style="border: 1px solid #BF360C;padding-left: 0px;background:rgba(208, 62, 10, 0.21);  " class="col-sm-6 not_div"><div class="col-sm-2 fadein animated" ><img alt="E" src="../Style/img/notification/letter_icon_e.png" style="width: 80px;height: 80px;"/></div><div class="col-sm-10"><h6 style="background: linear-gradient(#ffaa90,#fb8762);color: #980b00;text-align: left;border-bottom: 1px solid #F44336;box-shadow: 0px 4px 4px -3px #9E9E9E;margin: 0px;padding: 5px;border-left: 4px solid #d03707;margin-top: 1px;"><span><strong>&nbsp;&nbsp;Exception :</strong></span><span>&nbsp;|&nbsp;Request count </span>&nbsp;&nbsp;<kbd>'.$total.'</kbd><span style="color: black;font-weight: bold;">&nbsp;|&nbsp;</span></h6>'.$exp_info.'</div></div></a>';
				
			}
			
			
			$total = 0;
			$div_leave2  = '';
			$leave_info2 ='';
			$myDB  =new MysqliDb();
			//$data_info  = $myDB->query("select LeaveType,sum(count) count from (select LeaveType,count(*) count from leavehistry t2 inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID where  (t2.HRStatusID='Approve' or t2.HRStatusID = 'Decline') and (EmployeeComment = 'Pending' and (MngrStatusID is null or MngrStatusID ='Pending'))  and t3.account_head!=t2.EmployeeID and cast(t2.DateCreated as date)>cast('".$date_From."' as date) and   ReasonofLeave !='Back Dated Leave' and account_head = '".$_SESSION['__user_logid']."' group by LeaveType union all  select LeaveType,count(*) count from leavehistry t2  inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID where  ((t2.HRStatusID='Pending' or t2.HRStatusID is null)  and EmployeeComment = 'Pending' and (MngrStatusID is null or MngrStatusID ='Pending')) and cast(t2.DateCreated as date)>cast('".$date_From."' as date)  and ReasonofLeave !='Back Dated Leave' and oh = '".$_SESSION['__user_logid']."' and (t3.ReportTo != 'CE07147134' ) group by LeaveType union all  select LeaveType,count(*) count from leavehistry t2  inner join whole_details_peremp t3 on t2.EmployeeID = t3.EmployeeID where ((t2.HRStatusID='Pending' or t2.HRStatusID is null) and EmployeeComment = 'Pending' and MngrStatusID is null) and  cast(t2.DateCreated as date)>cast('".$date_From."' as date) and ReasonofLeave !='Back Dated Leave' and ('CE07147134' = '".$_SESSION['__user_logid']."'  and ((account_head = t2.EmployeeID and t3.ReportTo = '".$_SESSION['__user_logid']."') or (account_head = '".$_SESSION['__user_logid']."' and t3.ReportTo = '".$_SESSION['__user_logid']."'))) group by LeaveType union all select LeaveType,count(*) count from leavehistry t2  inner join whole_details_peremp t3 on t2.EmployeeID=t3.EmployeeID where  (t2.HRStatusID='Approve' or t2.HRStatusID = 'Decline') and (EmployeeComment = 'Pending' and (MngrStatusID is null or  MngrStatusID ='Pending')) and t3.account_head = t2.EmployeeID and cast(t2.DateCreated as date)>cast('".$date_From."' as date)  and ReasonofLeave !='Back Dated Leave' and ReportTo = '".$_SESSION['__user_logid']."' group by LeaveType )t1 group by LeaveType;");	
					
			$data_info  = $myDB->query("select LeaveType,sum(count) count from (
select LeaveType,count(*) count from leavehistry t2 inner join module_master_new t3 on t2.EmployeeID=t3.EmployeeID where  (t2.HRStatusID='Approve' or t2.HRStatusID = 'Decline') and (EmployeeComment = 'Pending' and (MngrStatusID is null or MngrStatusID ='Pending')) and t3.l2empid='".$_SESSION['__user_logid']."' and t3.module_name='Leave'  and cast(t2.DateCreated as date)>cast('".$date_From."' as date) and   ReasonofLeave !='Back Dated Leave'  group by LeaveType 
union all  
select LeaveType,count(*) count from leavehistry t2  inner join module_master_new t3 on t2.EmployeeID=t3.EmployeeID where  ((t2.HRStatusID='Pending' or t2.HRStatusID is null)  and EmployeeComment = 'Pending' and (MngrStatusID is null or MngrStatusID ='Pending')) and cast(t2.DateCreated as date)>cast('".$date_From."' as date)  and ReasonofLeave !='Back Dated Leave' and t3.l1empid = '".$_SESSION['__user_logid']."' and t3.module_name='Leave'  group by LeaveType 
 )t1 group by LeaveType;");			
			
			$arra_exception = array('Leave','Half Day');
			if(count($arra_exception) > 0 && $arra_exception)
			{
				
				
				foreach($arra_exception as $key=>$value)
				{
					//if($value[0]['count'] > 0)
					//$exp_info .= '<div class="col-sm-6 fadein animated" style="padding: 4px;border: 1px solid #d6d6d6;border-radius: 5px;font-size: 11px;background: rgba(255, 255, 255, 0.44);margin-top: 2px;color:black;"><span>&nbsp;&nbsp; <b>'.$value['Exception'].' </b>: '.$value[0]['count'].' Pending</span></div>';
					
					$Exception = $value;
					$exception_count = 0;
					if(count($data_info) > 0 && $data_info)
					{
						foreach($data_info as $key=>$value)
						{
							if($value['LeaveType'] == $Exception)
							{
								$exception_count = $value['count'];
								
							}
						}	
					}
					
					$colorset_tt = $colorset[0];
					if( $exception_count <= 3 )
					{
						$colorset_tt = $colorset[0];
					}
					elseif($exception_count > 3 && $exception_count <= 10)
					{
						$colorset_tt = $colorset[1];
					}
					else
					{
						$colorset_tt = $colorset[2];
					}
					$expression  = '<i class="fa fa-frown-o" style="color:red !important"></i>';
					if($exception_count === 0 )
					{
						$expression = '<i class="fa fa-smile-o" style="color:green !important"></i>';
					}
					$div_leave2 .= '<a href="add_leave.php" ><div style="padding-left: 10px;padding-bottom: 7px;" class=" col s4 m4 not_div '.$colorset_tt[3].' animated"><div class="col s12 m12"><h6 style="background: '.$colorset_tt[0].';color: '.$colorset_tt[1].';text-align: left;border-bottom: 1px solid '.$colorset_tt[2].';box-shadow: 0px 4px 4px -3px #9E9E9E;margin: 0px;padding: 5px;border-left: 4px solid '.$colorset_tt[2].';margin-top: 1px;border-right: 4px solid '.$colorset_tt[2].';border-top: 1px solid '.$colorset_tt[2].';font-size: 20px;text-align: center;"><span><strong>&nbsp;&nbsp;Leave&nbsp;|&nbsp;</strong></span><span style="font-size: 13px;font-weight: bold;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.82);">'.$Exception.' </span>&nbsp;&nbsp;<p class="counter">'.$exception_count.'</p> &nbsp;'.$expression.'</h6></div></div></a>';
					
					$total +=$exception_count;
					
					
				}
			}
			/*if(count($data_info) > 0 && $data_info)
			{
				
				
				
				foreach($data_info as $key=>$value)
				{
					//if($value[0]['count'] > 0)
					//$leave_info2 .= '<div class="col-sm-6 fadein animated" style="padding: 4px;border: 1px solid #d6d6d6;border-radius: 5px;font-size: 11px;background: rgba(255, 255, 255, 0.44);margin-top: 2px;color:black;"><span>&nbsp;&nbsp; <b>'.$value['LeaveType'].' </b>: '.$value[0]['count'].' Pending</span></div>';
					$total +=$value[0]['count'];
					
					$colorset_tt = $colorset[0];
					if( $value[0]['count'] <= 5 )
					{
						$colorset_tt = $colorset[0];
					}
					elseif($value[0]['count'] > 5 && $value[0]['count'] <= 15)
					{
						$colorset_tt = $colorset[1];
					}
					else
					{
						$colorset_tt = $colorset[2];
					}
					$expression  = '';
					if($value[0]['count'] === 0 )
					{
						$expression = '<i class="fa fa-smile-o"></i>';
					}
					$div_leave2 .= '<a href="add_leave.php" ><div style="padding-left: 10px;padding-bottom: 7px;" class=" col s4 m4 not_div '.$colorset_tt[3].' animated"><div class="col s12 m12"><h6 style="background: '.$colorset_tt[0].';color: '.$colorset_tt[1].';text-align: left;border-bottom: 1px solid '.$colorset_tt[2].';box-shadow: 0px 4px 4px -3px #9E9E9E;margin: 0px;padding: 5px;border-left: 4px solid '.$colorset_tt[2].';margin-top: 1px;border-right: 4px solid '.$colorset_tt[2].';border-top: 1px solid '.$colorset_tt[2].';font-size: 20px;text-align: center;"><span><strong>&nbsp;&nbsp;Leave&nbsp;|&nbsp;</strong></span><span style="font-size: 13px;font-weight: bold;text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.82);">'.$value['LeaveType'].' </span>&nbsp;&nbsp;<p class="counter">'.$value[0]['count'].'</p> &nbsp;'.$expression.'</h6></div></div></a>';
					
					
				}
			}*/
			if($total >= 0)
			{
				$count = 1;
				//$div_leave2 = '<a href="add_leave.php"><div style="border: 1px solid #2196F3;padding-left: 0px;background: rgba(33, 150, 243, 0.12);" class="col-sm-6 not_div"><div class="col-sm-2 fadein animated" ><img alt="L" src="../Style/img/notification/Letter_Licon.png" style="width: 80px;height: 80px;"/></div><div class="col-sm-10"><h6 style="background: linear-gradient(#81c3f7,rgb(55, 132, 193));color: #0b406b;text-align: left;border-bottom: 1px solid #22679e;box-shadow: 0px 4px 4px -3px #9E9E9E;margin: 0px;padding: 5px;border-left: 4px solid #3F51B5;margin-top: 1px;"><span><strong>&nbsp;&nbsp;Leave :</strong></span><span>&nbsp;|&nbsp;Request count </span>&nbsp;&nbsp;<kbd>'.$total.'</kbd><span style="color: black;font-weight: bold;">&nbsp;|&nbsp;</span></h6>'.$leave_info2.'</div></div></a>';
				
			}
			
			
			?>
			
				<div class="col s12 m12">
				<?php 
				echo $div_downtime;
				echo $div_leave2;
				echo $div_exception1;
				?>
				</div>
				
			<?php }?>


	</div>
<!--Form container End -->	
</div>     
<!--Main Div for all Page End -->
</div>     
<!--Content Div for all Page End -->  
</div>	
		
<?php include(ROOT_PATH.'AppCode/footer.mpt'); ?>