<?php $curl = curl_init();
//$zipcreated = "https://demo.cogentlab.com/erpm/Services/Docs_Inactivezip/RSM06221301.zip";
$zipcreated = "/var/www/html/erpm/Services/Docs_Inactivezip/RSM06221301.zip";
$data['file[ ]'] = new CURLFILE($zipcreated);
$data['clientId'] = '38';
$data['candidateName'] = 'Srishti';
$data['dob'] = '1984-12-12';
$data['doj'] = '2021-12-12';
$data['empId'] = 'ce0921939837';
$data['fatherName'] = 'chauhan';
$data['phoneNumber'] = '3242343345';
$data['candidateEmail'] = 'asdf@gmail.com';
$data['location'] = '1';
$data['sla_id'] = '107';
//$data['filelink'] = 'https://demo.cogentlab.com/erpm/Services/Docs_Inactivezip/RSM06221301.zip';


print_r($data);
// die;
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://zella.in/zap/admin/App/insertApplicationWithAuth',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        'API_KEY: 123456789'
    ),
));
// print_r($curl);
// die;
$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
print curl_error($curl);
// print_r($response);
// die;
curl_close($curl);
echo $response;
