<?php
	/*ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	*/
	/*
	
	Dear {{1}}, A very warm welcome to Cogent family. Please use {{2}} to signup and complete your profile. EMS shall be used for attendance, Roster, leaves etc. Cogent E Services
	
	
	Hi {{1}}, Your appointment letter is available in EMS. Now you can download the same. Cogent E Services
	
	Hi {{1}}, you have not logged-in your EMS since(Date: {{2}}). Kindly login EMS daily to check your roster & attendance correctness. Cogent E Services
	
	Dear {{1}}, You are not reporting to office since {{2}} without any information to us. To ensure that your employment status continues, You are advised to contact HR department in person by {{3}} Cogent E Services	
	
	Dear {{1}}, You are not reporting to office since [{{2}}] without any information to us. To ensure that your employment status continues, You are advised to contact HR department in person by [{{3}}] - Cogent E Services
	
	Dear {{1}}, You are not reporting to office since (Date : {{2}}) without any information to us. To ensure that your employment status continues, You are advised to contact HR department in person by {{3}} - Cogent E Services
	
	
	*/
	/*prepare sms text*/
	/*
	$var1="Sachin Siwach";
	$var2="";
	$var3="";
	$whatsapp_no="919990053884";
	$whatsapp_no="919891886100";
	$textmsg="Hi, we at Cogent are currently conducting walk-in interviews for the following designations only, if you are applying for any other job position, please mention the said job and I will assist you accordingly";
	$textmsg="Hi $var1, Your appointment letter is available in EMS. Now you can download the same. Cogent E Services";
    $msg = [];
    $msg['type'] = 'text';// fixed
    $msg['text'] = $textmsg;
	echo sendwhatappmsg($msg,$whatsapp_no);
*/
// call this function to send sms
function sendwhatappmsg($msg,$whatsapp_no)
{
	$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.gupshup.io/sm/api/v1/msg',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'channel=whatsapp&source=911204832550&destination=' . $whatsapp_no . '&message=' . urlencode(json_encode($msg)) . '&src.name=CogentSupport',
        CURLOPT_HTTPHEADER => array(
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
            'apikey: pib0jql0dhryaoufencgu4imo5e0gu4l',
            'cache-control: no-cache'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
?>