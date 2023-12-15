<?php 

require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');

$sql='SELECT personal_details.EmployeeID,img,EmployeeName,BloodGroup,em_contact from personal_details left outer join contact_details on  personal_details.EmployeeID=contact_details.EmployeeID where personal_details.EmployeeID="'.$_REQUEST['EmpID'].'" order by cont_id desc limit 1';
$myDB=new MysqliDb();
$result = $myDB->query($sql);

$img = $result[0]['img'];
$rd_proff = 'Emergency No. ';
$rd_type = ($result[0]['em_contact']=='')?': N/A':'<b>: '.$result[0]['em_contact'].'</b>';
$employeeName  =$result[0]['EmployeeName'];
$BloodGroup  ='<b>: '.$result[0]['BloodGroup'].'</b>';
$empid  ='<b>: '.$result[0]['EmployeeID'].'</b>';
?>
<script language="javascript">
    var gAutoPrint = true;

    function processPrint(){

    if (document.getElementById != null){
    var html = '<HTML>\n<HEAD>\n';
    if (document.getElementsByTagName != null){
    var headTags = document.getElementsByTagName("head");
    if (headTags.length > 0) html += headTags[0].innerHTML;
    }

    html += '\n</HE' + 'AD>\n<BODY>\n';
    var printReadyElem = document.getElementById("div_print");

    if (printReadyElem != null) html += printReadyElem.innerHTML;
    else{
    alert("Error, no contents.");
    return;
    }

    html += '\n</BO' + 'DY>\n</HT' + 'ML>';
    var printWin = window.open("","processPrint");
    printWin.document.open();
    printWin.document.write(html);
    printWin.document.close();

    if (gAutoPrint) printWin.print();
    } else alert("Browser not supported.");

    }
</script>
<script>
	function printdiv(printpage)
{
var headstr = "<html><head><title></title></head><body>";
var footstr = "</body>";
var newstr = document.all.item(printpage).innerHTML;
var oldstr = document.body.innerHTML;
document.body.innerHTML = headstr+newstr+footstr;
window.print();
document.body.innerHTML = oldstr;
return false;
}
</script>
<style>
body
{
	-webkit-print-color-adjust: exact;
}
	label {
    min-width: 100px;
    float: left;
}
p {
    margin: 2px 15px;
}
p,b
{
	    font-family: sans-serif;
}
</style>
<body style="overflow: hidden;">
<div id="div_print">
<div class="card" style="width: 250px;border: 1px solid #424040;height: auto;" >
  <div style="height: 55px;width: 100%;" align="center"><img src="../Style/images/cogent-logo.png" alt="Avatar" style="height: 55px;width: 150px; margin-top:8px;"/></div>
   <div class="container" style="height: 150px;margin: 13px 0px;" align="center">
    <img src="../Images/<?php echo $img;?>" style="width:130px;height: 165px;border: 1px solid #0c0c0c;"/>
  </div>
  <div class="container" style="font-size:12px;">
    <P align="center" style="padding: 5px; text-transform: uppercase;"><b style="font-weight:800"><?php echo $employeeName;?></b></P>
    <p><label><strong>Employee ID</strong></label><?php echo $empid;?></p>    
    <p><label><strong><?php echo $rd_proff;?></strong></label> <?php echo $rd_type;?></p> 
    <p><label><strong>Blood Group</strong></label> <?php echo $BloodGroup;?></p> 
    <p align="right"  style="font-size: 8px;font-family: cursive;position: relative;padding-top: 15px;"><img src="../Style/img/sk_sign.jpeg" style="width: 50px;float: right;position: absolute;top: -10px;right: 4px;"/><span >Auth. Signatory</span></p>
  </div>
  <div class="container" style="font-size: 10px;font-weight: bold;font-family: monospace;padding: 0px;border-top: 2px solid #007380;background-color: #a0ffbe;width: 100%;height: 78px;">
  	<p align="center" style="margin: 0px;margin-top: 1px;"><b style="font-weight: 800">COGENT E-SERVICES PRIVATE LIMITED</b></p>
	<p align="center" style="margin: 0px;">C-100,Sector 63, Noida 201301 India</p>
	<p align="center" style="margin: 0px;">Ph: +91 120 436517</p>
	<p align="center" style="margin: 0px;">www.cogenteservices.com</p>
  </div>
     
</div>
</div>
<a href="javascript:void(processPrint());">Print123</a>
<p align="center" style="margin: 5px;width: 250px;"><button type="button" onClick="printdiv('div_print');">Print</button></p>
</body>
