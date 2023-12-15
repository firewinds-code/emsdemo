<?php
require_once(__dir__ . '/../Config/init.php');
require_once(CLS . 'MysqliDb.php');
header("Content-Type: application/json");
$POST = file_get_contents('php://input');
$data = json_decode($POST, true);
$response = array();
$result['msg'] = '';

 $EmployeeID = $data['EmployeeID'];
$address = $data['address'];
$country = $data['country'];
$city = $data['city'];
$state = $data['state'];
$tehsil = $data['tehsil'];
$district = $data['district'];
$createdby = $data['createdby'];
if(isset($data['latitude']) && $data['latitude']!=''){
    $lat = $data['latitude'];

}else{
    $lat='';
}
if(isset($data['longitude']) && $data['longitude']!=''){
    $long = $data['longitude'];

}else{
    $long='';
}
$modifiedby = $data['modifiedby'];
$latlongaddress = $data['latlongaddress'];
$zip = $data['zip'];

if(isset($data['EmployeeID']) && $data['EmployeeID']!=''){
    $selectQry =  "SELECT * FROM address_geo where EmployeeID='" . $data['EmployeeID'] . "'";
 
    $myDB = new MysqliDb();
    $response1 = $myDB->rawQuery($selectQry);
    // print_r($response1);
    if (count($response1) > 0) {  // if data exist update the data
    
     $updateQry = "UPDATE address_geo SET address='" . $address . "', latlongaddress='" . $latlongaddress . "', country='" . $country . "',city='" . $city . "',state='" . $state . "',tehsil='" . $tehsil . "',district='" . $district . "',modifiedby='" . $modifiedby . "',modifiedon= now(),zip='" . $zip . "',latitude='".$lat."',longitude='".$long."' WHERE EmployeeID='" . $EmployeeID . "'";
    
     
      
       
        $myDB = new MysqliDb();
        $response2 = $myDB->rawQuery($updateQry);
        $result['msg'] = "Data successfully Updated";
        $result['status'] = 1;
    } else // insert the data if data is not exist
    {
        $insertQry = "INSERT INTO address_geo(EmployeeID,address,country, city, state,tehsil,district,createdby,zip,latitude,longitude,latlongaddress) VALUES('" . $data['EmployeeID'] . "','" . $address . "','" . $country . "','" . $city . "','" . $state . "','" . $tehsil . "','" . $district . "','" . $createdby . "','" . $zip . "','".$lat."','".$long."','".$latlongaddress."') ";
        $myDB = new MysqliDb();
        $response3 = $myDB->rawQuery($insertQry);
        $result['msg'] = "Data successfully Inserted";
        $result['status'] = 1;
    }
    
    

}else{
    $result['msg'] = "Invalid employee id";
    $result['status'] = 0;
}
echo json_encode($result);

