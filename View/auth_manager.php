<?php
// Server Config file

require_once(__dir__ . '/../Config/init.php');

// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 

require(ROOT_PATH . 'AppCode/nHead.php');

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();

if (isset($_REQUEST['submit'])) {
    // echo "<pre>";
    // print_r($_POST);
    $name = clean($_REQUEST['name']);
    $password = clean($_REQUEST['password']);
    $fullname = clean($_REQUEST['fullname']);
    $extension = clean($_REQUEST['extension']);
    $webadminrole = clean($_REQUEST['webadminrole']);
    $usergroupid = "1662712885439";
    $parentuser = "1663940165559";
    $domainid = "1656663175250";
    $authtoken = "RqhBwZXzgJJdAYQOiU1TgkMZ";
    $arr = array(
        "name" => $name,
        "password" => $password,
        "fullname" => $fullname,
        "extension" => $extension,
        "webadminrole" => $webadminrole,
        "usergroupid" => $usergroupid,
        "parentuser" => $parentuser,
        "domainid" => $domainid,
        "authtoken" => $authtoken,
    );
    $jsonErr =  '[' . json_encode($arr) . ']';

    $curl = curl_init();
    $url = 'https://verve.cogentlab.com/auth/InsertUser';
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonErr,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Request Error:' . curl_error($curl);
    }

    curl_close($curl);
    // echo $response;
    $res = json_decode($response);

    if ($res->responseCode == '200') {
        $succMsg = $res->responseMsg;
    } else {
        $succMsg =  $res->responseMsg;
    }

    $sql = "insert into auth_manage_userAPI(type,request,response,url) values('create','" . $jsonErr . "', '" . $response . "', '" . $url . "')";
    $resultss = $myDB->query($sql);
    $mysql_error = $myDB->getLastError();
    // $create = "create";
    // echo $sql = "insert into auth_manage_userAPI(type,request,response,url) values(?,?,?,?)";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("ssss", $create, $jsonErr, $response, $url);
    // $stmt->execute();
    // $finalRes = $stmt->get_result();
    // print_r($finalRes);
    // die;
    // if ($stmt->affected_rows === 1) {
    if (empty($mysql_error)) {
        echo "<script>$(function(){toastr.success('Created Successfully'); }); </script>";
    } else {
        echo "<script>$(function(){toastr.error('Failed !'); }); </script>";
    }
}

?>

<style>
    .error {
        color: red;
    }

    #data-container {
        display: block;
        background: #2a3f54;

        max-height: 250px;
        overflow-y: auto;
        z-index: 9999999;
        position: absolute;
        width: 100%;

    }

    #data-container li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    #data-container li:hover {
        background: #26b99a;
        cursor: pointer;
    }

    .form-control:focus {
        border-color: #d01010;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(233, 102, 139, 0.6);

    }

    #overlay {
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height: 100%;
        display: none;
        background: rgba(0, 0, 0, 0.6);
    }

    .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }

    @keyframes sp-anime {
        100% {
            transform: rotate(360deg);
        }
    }

    .is-hide {
        display: none;
    }
</style>

<div id="content" class="content">
    <span id="PageTittle_span" class="hidden">Create User</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>Create User</h4>
            <div class="schema-form-section row">
                <div class="input-field col s12 m12">
                    <div class="col s12 m12">
                        <form method="post" enctype="multipart/form-data">
                            <div class="input-field col s6 m6 l6">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="input-field col s6 m6 l6">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="input-field col s6 m6 l6">
                                <label for="fullname">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname">
                            </div>

                            <div class=" input-field col s4 m4 l4" style="display:none">
                                <label for="extension">Extension</label>
                                <input type="hidden" class="form-control" id="extension" name="extension" value="3004">
                            </div>

                            <div class="input-field col s4 m4 l4" style="display:none">
                                <label for="webadminrole">Web Admin Role</label>
                                <input type="hidden" class="form-control" id="webadminrole" name="webadminrole" value="0">
                            </div>

                            <div class="input-field col s4 m4 l4" style="display:none">
                                <label for="usergroupid">User Group Id</label>
                                <input type="hidden" class="form-control" id="usergroupid" name="usergroupid" value="1662712519892">
                            </div>

                            <div class="input-field col s4 m4 l4" style="display:none">
                                <label for="parentuser">Parent User</label>
                                <input type="hidden" class="form-control" id="parentuser" name="parentuser" value="1656866989006">
                            </div>

                            <div class="input-field col s4 m4 l4" style="display:none">
                                <label for="domainid">Domain Id</label>
                                <input type="hidden" class="form-control" id="domainid" name="domainid" value="1656847043235">
                            </div>

                            <div class="input-field col s4 m4 l4" style="display:none">
                                <label for="authtoken">authtoken</label>
                                <input type="hidden" class="form-control" id="authtoken" name="authtoken" value="1656847043235">
                            </div>
                            <div class="input-field col s12 m12 right-align">
                                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Submit" />
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Content Div for all Page End -->
</div>

<script>
    $(document).ready(function() {
        $('#submit').on('click', function() {
            var validate = 0;
            var alert_msg = '';
            if ($('#name').val().trim() == '') {
                $('#name').addClass('has-error');
                if ($('#spanname').size() == 0) {
                    $('<span id="spanname" class="help-block">Required *</span>').insertAfter('#name');
                }
                validate = 1;
            }
            if ($('#password').val().trim() == '') {
                $('#password').addClass('has-error');
                if ($('#spanpassword').size() == 0) {
                    $('<span id="spanpassword" class="help-block">Required *</span>').insertAfter('#password');
                }
                validate = 1;
            }
            if ($('#fullname').val().trim() == '') {
                $('#fullname').addClass('has-error');
                if ($('#spanfullname').size() == 0) {
                    $('<span id="spanfullname" class="help-block">Required *</span>').insertAfter('#fullname');
                }
                validate = 1;
            }

            if (validate == 1) {

                //alert('1');
                return false;
            }
        });
    });
</script>

<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>