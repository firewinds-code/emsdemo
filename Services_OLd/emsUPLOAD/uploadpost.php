<?php
    echo "<pre>";
    if($_REQUEST['btnUpload'] == 'Upload') {
        $ch = curl_init();
        //var_dump($_FILES);
        $filePath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
                 
        curl_setopt($ch, CURLOPT_URL, 'http://localhost/ems/Services/emsUPLOAD/emsRevert.php?view=Contact&EmployeeID=CE04146339&key=ahSHASNmashmK@kas$3,asmAAssk');
        curl_setopt($ch, CURLOPT_POST, 1);
        $args['file'] = new CurlFile($_FILES['file']['tmp_name'], $_FILES['file']['type'],$_FILES['file']['name']);
        curl_setopt($ch, CURLOPT_HEADER, false); // Suppress display of the response header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data")); 
        //curl_setopt($ch, CURLOPT_UPLOAD, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        $respo = curl_exec($ch);
        curl_close($ch);
        
    }
?>
