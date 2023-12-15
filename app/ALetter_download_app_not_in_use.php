<style>
body {

background-color: white;
}
</style>
<?php
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
//header("Content-Type: application/json; charset=UTF-8");
$Data=array();
//$_POST = file_get_contents('php://input');
//$Data=json_decode($_POST,true);
	$show = '';
	$empName ='';
	$name='';
	$target_dir='';
	$EmpID='';
	$EmployeeID='';
	$alert_msg['msg']='';
	$EmployeeID="CE121622565";
if($EmployeeID)
{
	
	//if(isset($Data['EmpID']))
//{
		//$EmpID=$EmployeeID=$Data['EmpID'];

	

$myDB=new MysqliDb();	
 $sql='select location from personal_details where EmployeeID = "'.$EmployeeID.'"';
$result=$myDB->rawQuery($sql);
$mysql_error = $myDB->getLastError();
if(empty($mysql_error))
{
	 $loc = $result[0]['location'];
}
if($loc=="1" || $loc=="2")
{
	$dirloc = '';
	
}
else if($loc=="3")
{
	$dirloc = 'Meerut/';
}
else if($loc == "4")
{
	$dirloc="Bareilly/";
}
else if($loc == "5")
{
	$dirloc="Vadodara/";
}
else if($loc == "6")
{
	$dirloc="Manglore/";
}
else if($loc == "7")
{
	$dirloc="Bangalore/";
}


	
$Comment='Created by app';

echo "<input type='hidden' id='dirloc' name='dirloc' value='".$dirloc."'>";
echo "<input type='hidden' id='EmployeeID' name='EmployeeID' value='".$EmployeeID."'>";
	
	$locationid = $loc;
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Convert Div to pdf</title>

<script src="../jspdf/jquery.js"></script>
<script src="../jspdf/jspdf.min.js"></script>
<script src="../jspdf/html2canvas2.js"></script>
<script>
$(document).ready(function(){
	 var pdfname=$('#EmployeeID').val();
	 var dirloc=$('#dirloc').val();
if(pdfname!="")	{
var HTML_Width=1160;
var HTML_Height=3515;
 var top_left_margin = 15;
 var PDF_Width = HTML_Width+(top_left_margin*2);
 var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
 var canvas_image_width = HTML_Width;
 var canvas_image_height = HTML_Height;
 
 var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
  var totalPDFPages=1;
 //alert(totalPDFPages);
 
 html2canvas($(".canvas_div_pdf")[0],{allowTaint:true}).then(function(canvas) {
 canvas.getContext('2d');
 console.log(canvas.height+"  "+canvas.width);
 var imgData = canvas.toDataURL("image/jpeg", 1.0);
 var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
     pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
 for (var i = 1; i <= totalPDFPages; i++) { 
 pdf.addPage(PDF_Width, PDF_Height);
 pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
 }
 
      var blob =  pdf.output('blob');
            var formData = new FormData();
            formData.append('pdf', blob);
           // alert('../jspdf/upload.php?empname='+pdfname+'&dirloc='+dirloc);
                       $.ajax({
			url: '../jspdf/upload.php?empname='+pdfname+'&dirloc='+dirloc,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                	if(data==1){
                		// $(".canvas_div_pdf").attr("hidden", true);
						//window.close();
					}else{
						
						 $(function(){ toastr.success('Appintment letter not Created, please check this EmployeeID'); });
					}
             	
                },
                error: function(data){ $(function(){ toastr.success(data); });}
            });
      
     
        });
    }
     
});
</script>

</head>
<body >

<?php 


function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
        'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    );
    $list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
    $list3 = array('', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'Quintillion', 'Sextillion', 'Septillion',
        'Octillion', 'Nonillion', 'Decillion', 'Undecillion', 'Duodecillion', 'Tredecillion', 'Quattuordecillion',
        'Quindecillion', 'Sexdecillion', 'Septendecillion', 'Octodecillion', 'Novemdecillion', 'Vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}
$func = '';
$gender = 'Mr.';
 $sql='select whole_details_peremp.EmployeeID,EmployeeName,DOJ,designation,ctc,status_table.onFloor,des_id,function,Gender from whole_details_peremp left outer join salary_details on salary_details.EmployeeID = whole_details_peremp.EmployeeID left outer join status_table on whole_details_peremp.EmployeeID = status_table.EmployeeID where whole_details_peremp.EmployeeID="'.$EmployeeID.'" limit 1';
$myDB=new MysqliDb();
$result = $myDB->query($sql);
$location_address="SELECT a.id, a.companyname, a.locationId, a.sub_location, a.address,b.location from  location_address_master  a Inner Join location_master b on a.locationId=b.id where a.locationId ='".$locationid."' ";
$myDB=new MysqliDb();
$Locationresult = $myDB->query($location_address);
if(count($result) && $result && $EmployeeID!="")
{
	$EmployeeID = $result[0]['EmployeeID'];
	$EmployeeName=$name = $result[0]['EmployeeName'];
	if(intval($result[0]['des_id']) == 9 || intval($result[0]['des_id']) == 12)
	{
		$DOJ = $result[0]['onFloor'];
	}
	else
	{
		$DOJ = $result[0]['DOJ'];
		
	}
	
	if($DOJ!="")
	{

		if(strtoupper($result[0]['Gender']) == 'FEMALE')
		{
			$gender = 'Ms.';
		}
		else
		{
			$gender = 'Mr.';
			
		}
		$func = $result[0]['function'];
		$designation = $result[0]['designation'];
		$ctc = $result[0]['ctc'];

	 $varhtnl='<div  align="justify" id="zoomfor" class="container canvas_div_pdf"  style="padding:25px; font-size:25px;"> <div class="col-sm-12 pull-left" style="width: 100%;">';
	 
	 if($locationid=="1" || $locationid=="2" || $locationid=="4" || $locationid=="5" || $locationid=="6" || $locationid=="7" || $locationid=="8")
	 {
	 	if('AE' == substr($EmployeeID, 0, 2))
		{

		$varhtnl .='<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;">';
		$varhtnl .='<img src="../Style/images/client_logo.JPG" style="width:220px;height: 100px;"/></div>';


		}
		else
		{
			
			$varhtnl .='<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;">';
			
			$varhtnl .='<img src="../Style/images/newLogo cogent.jpg" style="width:220px;height: 100px;"/></div>';
			
		}
	 }
	 else if($locationid=="3")
	 {
	 	if('OC' == substr($EmployeeID, 0, 2))
	 	{
			$varhtnl .='<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;">';
			$varhtnl .='<img src="../Style/images/client_logo.png" style="width:220px;height: 100px;"/></div>';
		}
		else
		{
			
			$varhtnl .='<div class="col-sm-3 text-left" style="margin: 0px;padding: 0px;width: 220px;position: relative;right: 0px;float: right;">';
			
			$varhtnl .='<img src="../Style/images/newLogo cogent.jpg" style="width:220px;height: 100px;"/></div>';
			
		}
	 }



$varhtnl .='</div>';
$varhtnl .='<h4 style="text-align: center;padding-left:180px;"> Appointment Letter</h4><p>Dear<b>&nbsp;'.$gender.' '.$EmployeeName.'</b>,<br />
  <strong>Reference ID: <label>';
  $varhtnl .=$EmployeeID;
  $varhtnl .=' </label></strong></p><p>We are pleased to appoint you in <strong>';
if(count($Locationresult)>0)
{
	foreach($Locationresult as $val)
	{
		if($locationid=="1" || $locationid=="2")
		{
			if($val['sub_location'] == substr($EmployeeID, 0, 2))
			{
				$varhtnl .= $val['companyname'];
			}	
		}
		else
		{
			if($val['sub_location'] == substr($EmployeeID, 0, 3))
			{
				$varhtnl .= $val['companyname'];
			}else{
				$varhtnl .= $val['companyname'];
			}
		}
		
	}
}

$varhtnl .='</strong>(&ldquo;<strong>Company</strong>&rdquo;) as <strong>&lsquo;';
	if($designation == 'CSA')
	{
		$varhtnl .='Customer Support Associate';
	}
	elseif($designation == 'Senior CSA')
	{
		$varhtnl .='Senior Customer Support Associate';
	}
	else
	{
		 $varhtnl .=$designation.' - '.$func;
	}
$varhtnl .='&rsquo;</strong> at our <strong>'.$Locationresult[0]['location'].'</strong> office as per the  employment terms and conditions stated below. Please note that the employment  terms contained in this letter (&ldquo;<strong>Appointment  Letter</strong>&rdquo;) are subject to Company policy.</p>
<p><strong>1.&nbsp;&nbsp;&nbsp;&nbsp;Appointment </strong><br />
  Your  date of appointment is effective from the date of execution of this Appointment Letter (&ldquo;<strong>';
  
 
	  if(date('d',strtotime($DOJ))=='1')
	{
		
		$varhtnl .= '1<sup>st</sup> ';
	}
	elseif(date('d',strtotime($DOJ))=='2')
	{
		$varhtnl .='2<sup>nd</sup> ';
	}
	elseif(date('d',strtotime($DOJ))=='3')
	{
		$varhtnl .='3<sup>rd</sup> ';
	}
	else
	{
		$varhtnl .= date('d',strtotime($DOJ)).'<sup>th</sup> ';
	}
$varhtnl .= date('F Y',strtotime($DOJ));
$varhtnl .='</strong>&rdquo;). The term of  your employment with the Company shall commence on the Effective Date and shall  continue unless this Appointment Letter is terminated earlier in accordance  with its terms (&ldquo;<strong>Employment Term</strong>&rdquo;).You  will be on probation for a period of 3 months from the date of your joining  employment with the Company. There will be periodic review of your work,  adaptability, acceptability and demeanor. If the review rating does not meet  the acceptable standards, the Company reserves its right to extend this period  of probation beyond 3 months but in no event shall the period of probation  exceed 6 months. On completion of initial probation period till such time that  you are intimated in writing regarding your confirmation, you shall continue to  be on probation.</p><p><strong>2.&nbsp;&nbsp;&nbsp;&nbsp;Assignment  / transfer</strong>
  Your  usual place of work will be at our office at <b>'.$Locationresult[0]['location'].'</b>, India. However, the  Company reserves the right to transfer/ assign you to specific projects,  assignments, jobs etc. in which case you will be required to perform your  services at such location, division, department or branch of the Company as the  Company may deem fit.</p>
<p><strong>3.&nbsp;&nbsp;&nbsp;&nbsp;Compensation</strong><br />
  (a)&nbsp;&nbsp;&nbsp;&nbsp;Your  cost to the company (CTC) will be Rs.<strong style="min-width: 50px;display: inline-block;text-align: center;">';
  $varhtnl .= round($ctc);
 $varhtnl .='/-&nbsp;</strong><b>(&nbsp;</b><strong  style="min-width: 150px;display: inline-block;">';
 
$varhtnl .= convertNumberToWord(round($ctc));
 $varhtnl .='Rupees Only</strong><b>&nbsp;)</b> per month including all statutory requirements.  Your salary will be reviewed periodically as per Company policy.</p>
<p>(b)&nbsp;&nbsp;&nbsp;&nbsp;Changes in your compensation are  discretionary and will be subject to and on basis of effective performance and  results during the period and other relevant criteria.</p>  <strong>
4.&nbsp;&nbsp;&nbsp;&nbsp;Working hours</strong><br />
(a)&nbsp;&nbsp;&nbsp;&nbsp;Your normal office hours shall be  intimated at the time of joining. The Company reserves the right to require you  to work outside your normal working hours if necessary in furtherance of your duties. Suitable remedies / remuneration will be provided by the company to you in such case.<br /><br />
<strong>5.&nbsp;&nbsp;&nbsp;&nbsp;Responsibilities</strong>
 You  must effectively, diligently and to the best of your ability perform all  responsibilities and ensure results.<br/><br/><strong>
 6.&nbsp;&nbsp;&nbsp;&nbsp;Non-disclosure obligations and  confidentiality</strong>
  At all times  during and after the Employment Term, you will hold in strictest confidence and  not use for your own purposes or the purposes of others or disclose any  confidential information pertaining to the Company or its clients. Further, in  consideration of the opportunities, training and access to new training and  know-how that will be made available to you, you will be required to comply  with the confidentiality policy of the Company and/or its clients.<p></p><strong>
  7.&nbsp;&nbsp;&nbsp;&nbsp;Company property</strong>
  Any  and all notes, records, other documents, in any  way relating to the business or affairs of the Company or clients shall at all  times remain the property of the Company and shall be returned to the Company  upon you ceasing to be in the Company&rsquo;s employment or at any other time at the  request of the Company.<br />In  the event of the termination of your employment for any reason, and subject to  any other provisions hereof, the Company reserves the right, to the extent  required by law, and in addition to any other remedy the Company may have, to  deduct from any monies otherwise payable to you the full amount of any specifically determined debt you owe to the Company at the time of or  subsequent to the termination of your employment with the Company including but  not limited to salary in lieu of notice period. <br /><br />';
 
 
  $varhtnl.='<hr style="margin-top: 15px;margin-bottom: 15px;border: 1px solid #9a9a9a;"/><p style="text-align: center;color: #a0a0a0;"><b><br>';

		if(count($Locationresult)>0)
		{
			foreach($Locationresult as $val)
			{
				if($locationid=="1" || $locationid=="2")
				{
					if($val['sub_location'] == substr($EmployeeID, 0, 2))
					{
						$varhtnl.= $val['companyname']."</b> ".$val['address']. ' Website : www.cogenteservices.com';
					}
				}
				else
				{
					if($val['sub_location'] == substr($EmployeeID, 0, 3))
					{
						$varhtnl.= $val['companyname']."</b> ".$val['address']. ' Website : www.cogenteservices.com';
					}else{
						$varhtnl.= $val['companyname']."</b> ".$val['address']. ' Website : www.cogenteservices.com';
					}
				}
				
			}
		}
$varhtnl.='<br><br><br><br></p> </label><p></p><br><br>';
$varhtnl.='<p><strong>
8.&nbsp;&nbsp;&nbsp;&nbsp;Termination  of Employment</strong><br />
  You  are required to give the Company the following period of notice, in writing, to terminate your employment, namely:-</p>
<p>(a)&nbsp;&nbsp;&nbsp;&nbsp;Thirty Days till you have completed your probationary period, as mentioned in clause 1.</p>
<p>(b)&nbsp;&nbsp;&nbsp;&nbsp;Thirty days after  your confirmation as a permanent employee.</p>
<p>(c)&nbsp;&nbsp;&nbsp;&nbsp;The Company may, in its sole discretion, terminate your employment without cause by giving 30  day notice or salary in lieu thereof. <br />
  <br />
  (d)&nbsp;&nbsp;&nbsp;&nbsp;Your  employment is liable to be terminated forthwith by the Company without prior  notice if, any declaration/statement or information forthwith given by you in  your application or in connection with your appointment is at any time found to  be false or untrue or any material particulars are suppressed. Further the  Company reserves the right to terminate your services without any notice or  salary in lieu thereof for misconduct, negligence of duty, disloyalty,  dishonesty, indiscipline, disobedience, irregular attendance, long period of  absence from duty due to ill-health, infirmity or accident or inefficiency as  compared to other employees.</p>
<p>If you absent yourself without leave or remain absent from work with no  information for more than 3 days or remain absent beyond the period of leave  originally granted or subsequently extended, you shall be considered as having  voluntarily terminated your employment without giving any notice and as per the  terms above your salary will be deducted in lieu of notice period. </p><p><strong>
9.&nbsp;&nbsp;&nbsp;&nbsp;Exclusivity  / Prior Commitment</strong><br />
  You  agree to work exclusively for the Company, within the context of the responsibilities  defined above, and not to accept or perform any other paid/ unpaid employment  or consulting in addition to this, even temporary. You agree, represent and warrant  to the Company that you are not subject to/party to any agreements or restrictions,  including, without limitation, those arising out of any prior employments which  would be breached or violated by your execution of this Appointment Letter.</p><label style="font-weight:normal !important;"><strong>
  10.&nbsp;&nbsp;&nbsp;&nbsp;Jurisdiction </strong><br />The appointment  shall be governed by and interpreted in accordance with the laws of India and the courts of New Delhi.</label>
<p ><strong>
11.&nbsp;&nbsp;&nbsp;&nbsp;Entire  Agreement </strong><br />
  You agree and  acknowledge that with effect from the Effective Date this Appointment Letter  represents the entire agreement between you and the Company and supersedes any  previous appointment letters/ contracts entered into between you and the  Company.</p>
  
<p>We take pleasure in welcoming you to  our Company and looking forward to a mutually beneficial association.</p>

<p>Yours truly,<br />';
$varhtnl.='<strong>';
		if(count($Locationresult)>0)
		{
			foreach($Locationresult as $val)
			{
				if($locationid=="1" || $locationid=="2")
				{
					if($val['sub_location'] == substr($EmployeeID, 0, 2))
					{
						$varhtnl.= 'For '.$val['companyname'];
					}
				}
				else
				{
					if($val['sub_location'] == substr($EmployeeID, 0, 3))
					{
						$varhtnl.= 'For '.$val['companyname'];
					}else{
						$varhtnl.= 'For '.$val['companyname'];
					}
				}
				
			}
		}
$varhtnl.='</strong></p>';
$varhtnl.='<p><img src="../Style/img/sk_sign.jpeg" style="width: 120px;height: 45px;"/></p>';
$varhtnl.='<p><strong>(S.K<a name="_GoBack" id="_GoBack"></a> Garg)</strong><br />';
  $varhtnl.='<strong>Authorized Signatory</strong></p>';

 $varhtnl.='<div style="border: 1px solid gray;padding: 10px;">';
   $varhtnl.='<p>I have carefully read and understood the terms and conditions mentioned above. All the terms and conditions of this appointment have been accepted by me.</p>';
  $varhtnl.=' <p style="width: 100%;">Name: <b>'.$EmployeeName.'</b>'; 
  $varhtnl.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature: <br />';
    $varhtnl.=' Date:</p>';
 $varhtnl.='</div>';
 $varhtnl.='</div><div>';


 echo $varhtnl;
 
 $validateBy = $EmployeeName;
	$myDB = $myDB=new MysqliDb();
	  $Update='update doc_al_status set validate=1,validateby="'.$validateBy.'",validatetime = now(),comment="'.$Comment.'" where EmployeeID="'.$EmpID.'" ';
	$body="";
	$myDB->rawQuery($Update);
	$mysql_error = $myDB->getLastError();
	if($myDB->count>0){
		$myDB=new MysqliDb();
		$selectCount=$myDB->rawQuery("Select EmployeeID from appointmentlonline Where EmployeeID='".$EmployeeID."' ");
		if($myDB->count<1){
			$myDB=new MysqliDb();
			$select_email_array=$myDB->rawQuery("select mobile,emailid,b.cm_id from contact_details a inner Join employee_map b on a.EmployeeID=b.EmployeeID where  a.EmployeeID='".$EmployeeID."'");
			$cm_id='';
			if(isset($select_email_array[0]['cm_id'])){
				$cm_id=$select_email_array[0]['cm_id'];
			}
			$myDB=new MysqliDb();
			$insert_Query=$myDB->rawQuery("Insert Into appointmentlonline set EmployeeID='".$EmployeeID."',EmpName='".$name."',cm_id='".$cm_id."',CreateDate='".date('Y-m-d')."',status='0'");
		}
	}
	
	if(file_exists('../'.$dirloc.'appointpdf/'.$EmployeeID.'.pdf'))
	{	
	echo "file is available";
	}
			
}else{
	echo "<script>alert('Onfloor/Doj Date should not be empty');</script>";
}
}
}else{
	$alert_msg['msg']='Data Not found';
}
print_r(json_encode($alert_msg));
?>
</body>

</html>
