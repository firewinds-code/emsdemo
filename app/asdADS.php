<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


error_reporting(E_ALL);
require_once(__dir__.'/../Config/init.php');
//require_once(CLS.'php_mysql_class.php');
require_once(CLS.'MysqliDb.php');
header("Content-Type: application/json; charset=UTF-8");
$_POST = file_get_contents('php://input');
$Data=json_decode($_POST,true);
$result=array();
$myDB =  new MysqliDb();
if(isset($Data['appkey']) && $Data['appkey'] !=' ')
{
	echo $Data['appkey'];
	     if( $Data['appkey']=='dashboard')
          {
          	    
echo	            $query = 'select 
client_id, t1.cm_id, client_name, process_id,process, sub_process, location, locationName,ifnull(active_on_floor,0) as active_on_floor,ifnull(rostered,0)- (ifnull(leaves,0)) rostered, case when (ifnull(ttrostered,0)-ifnull(leaves,0))<0 then 0 else ifnull(ttrostered,0)-ifnull(leaves,0) end ttrostered, ifnull(active_ojt,0) active_ojt ,ifnull(active_in_training,0) active_in_training,ifnull(present,0)present ,ifnull(on_floor_attrition,0)on_floor_attrition, ifnull(hd,0) hd,ifnull(leaves,0) leaves, ifnull(wo,0) wo, case when  ifnull((ttrostered -present-ifnull(leaves,0)),0)<0 then 0 else ifnull((ttrostered-present-ifnull(leaves,0)),0) end as absent,ifnull(notice,0)notice,ifnull(ncns,0)ncns 
from 
(
select cm.client_id,ncm.cm_id,cm.client_name,process_id, process,ncm.sub_process,ncm.location,ncm.locationName from client_master cm inner join (select Distinct pm.process_id, a.process as process, a.sub_process, a.client_name,a.cm_id,a.location,lm.location as locationName from new_client_master a inner Join location_master lm on lm.id=a.location 
left join process_map pm on a.process=pm.process
where a.cm_id Not IN(select cm_id from client_status_master) and process_id is not null) ncm on cm.client_id=ncm.client_name
)t1 left join 
(
select count(t1.EmployeeID) active_on_floor,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID where status in(4,5,6) and df_id=74  group by cm_id
) t2 on t1.cm_id=t2.cm_id 
left join 
(
select count(t.EmployeeID) rostered,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID  where DateOn=cast(now() as  date)  and InTime!='WO' and status in(4,5,6)  and df_id=74  group by cm_id
) t3 on t1.cm_id=t3.cm_id 
left join 
(
select count(t.EmployeeID) ttrostered,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID  where DateOn=cast(now() as  date) and  cast(InTime as time) <=cast(now() as time ) and InTime!='WO' and status in(4,5,6) and df_id=74  group by cm_id
) tr3 on t1.cm_id=tr3.cm_id 
left join 
(
select count(a.EmployeeID) present,a.cm_id from ActiveEmpID a inner Join (SELECT distinct EmpID FROM biopunchcurrentdata where dateOn=cast(now() as date)) b  on b.EmpID=a.EmployeeID left join status_table s on a.EmployeeID=s.EmployeeID where status in(4,5,6)  and df_id=74 group by cm_id 
) t6 on t1.cm_id=t6.cm_id 
left join
(
select count(t1.EmployeeID) active_ojt,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID
where status in (4,5)  and df_id=74 group by cm_id
) t4 on t1.cm_id=t4.cm_id 
left join 
(
select count(t1.EmployeeID) active_in_training,t2.cm_id from status_table t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID
 where status in (2,3)  and df_id=74  group by cm_id
) t5 on t1.cm_id=t5.cm_id 
left Join 
(
select count(t1.EmployeeID) on_floor_attrition,e.cm_id from exit_emp t1 Inner join employee_map e on t1.EmployeeID=e.EmployeeID left join status_table s on t1.EmployeeID=s.EmployeeID where status in (4,5,6) and month(t1.dol)=month(now()) and year(t1.dol)=year(now())  and df_id=74 group by cm_id
)t7 on t1.cm_id=t7.cm_id 
left join
(
select ifnull( count(t1.EmployeeID),0) hd,t2.cm_id from leavehistry t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID left join status_table s on t2.EmployeeID=s.EmployeeID where status in (4,5,6) and  (cast(t1.DateFrom as date)<=cast(now() as date) and cast(t1.DateTo as date)>=cast(now() as date) ) and LeaveType='Half Day' and MngrStatusID='Approve'  and df_id=74  group by cm_id
)t8 on t1.cm_id=t8.cm_id 
left join
(
select ifnull( count(t1.EmployeeID),0) leaves,t2.cm_id from leavehistry t1 inner join ActiveEmpID t2 on t1.EmployeeID=t2.EmployeeID left join status_table s on t2.EmployeeID=s.EmployeeID where status in (4,5,6) and (cast(t1.DateFrom as date)<=cast(now() as date) and cast(t1.DateTo as date)>=cast(now() as date) ) and LeaveType='Leave' and MngrStatusID='Approve'  and df_id=74  group by cm_id
)t9 on t1.cm_id=t9.cm_id 
left join
(
select ifnull(count(t.EmployeeID),0) wo,e.cm_id from roster_temp t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and DateOn=cast(now() as  date)  and InTime='WO'  and df_id=74  group by cm_id
)t11 on t1.cm_id=t11.cm_id  
left join
(
select ifnull(count(t.EmployeeID),0) ncns,e.cm_id from login_ncns_smsmail t inner join ActiveEmpID e on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and cast(t.createdOn as date)=cast(now() as  date)  and type='NCNS' and df_id=74 group by cm_id
)t12 on t1.cm_id=t12.cm_id
left join
(
select ifnull(count(t.EmployeeID),0) notice,e.cm_id from ActiveEmpID e  left join resign_details t on t.EmployeeID=e.EmployeeID left join status_table s on e.EmployeeID=s.EmployeeID where status in (4,5,6) and cast(now() as date) between nt_start and nt_end  and df_id=74 group by cm_id
)t13 on t1.cm_id=t13.cm_id
';
                $res =$myDB->query($query);
                if($res)
				 {
					$result['msg']="Data  Found.";
			        $result['status']=1;
			        $result['data']=$res;
				}
				else
				{
				     $result['msg']="Data Not Found....";
			         $result['status']=0;	
				}	        
			}	
		
		else
		{
			 $result['msg']="invalid appkey";
     			$result['status']=0;
		}
		
 }
else
 {
     $result['msg']="Bad Request";
     $result['status']=0;
 }
echo  json_encode($result);
?>

