<?php 
require_once(__dir__.'/../Config/init.php');
// DB main Config / class file
require_once(CLS.'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');

//$now = date('Y-m-d'); // or your date as well
$date = date('Y-m-d',strtotime('-5 days'));
//echo $now;

 $sqlstr = "insert into mail_template(empid,name,gender,location,doj,assignment,contact_no,email,designation,immediate_manager,des_id,loc_id,client_name,img) 
select A.* from (select G1.EmployeeID as empid ,G4.EmployeeName as name,G4.Gender as gender,G5.location as location
,G1.DOJ as Doj,concat(G2.process,' - ',G3.client_name)as Assignment,G6.mobile as Contactno,ifnull(G6.ofc_emailid,'Email ID not created') as Email,concat(G8.Designation,' - ', G2.process) as Designation,G10.EmployeeName as `Immediate Manager` ,G7.des_id,G5.id ,G2.client_name,G4.img
from ActiveEmpID G1 join  new_client_master G2 on G1.cm_id= G2.cm_id join client_master G3 on G2.client_name=G3.client_id join personal_details G4 on G1.EmployeeID = G4.EmployeeID join location_master G5 on G4.location = G5.id join  contact_details G6 on  G1.EmployeeID = G6.EmployeeID join df_master G7  on  G1.df_id = G7.df_id join  designation_master G8 on G8.id = G7.des_id join  status_table G9 on G1.EmployeeID = G9.EmployeeID join personal_details G10 on G10.EmployeeID = G9.ReportTo  where G1.DOJ >= '".$date."' and  G7.des_id in (5,7,8,10,13,14,15,16,22,1,2,3,4,6,11)) A left join mail_template B on A.empid =B.empid where B.empid is null";

		$myDB =  new MysqliDb();
		$Results = $myDB->rawQuery($sqlstr);
		$mysql_error = $myDB->getLastError();
		
?>

