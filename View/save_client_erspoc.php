<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');
// Global variable used in Page Cycle

$myDB = new MysqliDb();
$conn = $myDB->dbConnect();
$user_type = clean($_SESSION['__user_type']);
$EmployeeID = clean($_SESSION['__user_logid']);

if ($user_type == 'ADMINISTRATOR' || $EmployeeID == 'CE10091236' || $EmployeeID == 'CE12102224' || $EmployeeID == 'CE01145570' || $EmployeeID == 'CE03146043') {
    // proceed further
} else {
    $location = URL . 'Error';
    header("Location: $location");
    exit();
}
if (isset($_POST['save'])) {
    if (isset($_POST["token"]) && isset($_SESSION["token"]) && $_POST["token"] == $_SESSION["token"]) {
        $location = cleanUserInput($_POST['location1']);
        $createBy = clean($_SESSION['__user_logid']);
        $er_spoc = substr(cleanUserInput($_POST['er_spoc']), strpos(cleanUserInput($_POST['er_spoc']), "(") + 1, (strpos(cleanUserInput($_POST['er_spoc']), ")")) - (strpos(cleanUserInput($_POST['er_spoc']), "(") + 1));

        $er_spoc2 = substr(cleanUserInput($_POST['er_spoc2']), strpos(cleanUserInput($_POST['er_spoc2']), "(") + 1, (strpos(cleanUserInput($_POST['er_spoc2']), ")")) - (strpos(cleanUserInput($_POST['er_spoc2']), "(") + 1));

        $er_spoc3 = substr(cleanUserInput($_POST['er_spoc3']), strpos(cleanUserInput($_POST['er_spoc3']), "(") + 1, (strpos(cleanUserInput($_POST['er_spoc3']), ")")) - (strpos(cleanUserInput($_POST['er_spoc3']), "(") + 1));

        $er_spoc4 = substr(cleanUserInput($_POST['er_spoc4']), strpos(cleanUserInput($_POST['er_spoc4']), "(") + 1, (strpos(cleanUserInput($_POST['er_spoc4']), ")")) - (strpos(cleanUserInput($_POST['er_spoc4']), "(") + 1));

        $cm_id = cleanUserInput($_POST['sub_process1']);

        $Insert = 'CALL update_erspoc("' . $createBy . '","' . $location . '","' . $cm_id . '","' . $er_spoc . '","' . $er_spoc2 . '","' . $er_spoc3 . '","' . $er_spoc4 . '")';
        //die;
        $resCount = $myDB->rawQuery($Insert);
        $mysql_error = $myDB->getLastError();
        if (empty($mysql_error)) {
            echo "<script>$(function(){ toastr.success('Client Save Successfully'); }); </script>";
        } else {
            echo "<script>$(function(){ toastr.error('Client not Added.'); }); </script>";
        }
    }
}
?>
<style>
    .error {
        color: red;
    }

    #data-container,
    #data-container2,
    #data-container3,
    #data-container4 {
        display: block;
        background: #2a3f54;

        max-height: 250px;
        overflow-y: auto;
        z-index: 9999999;
        position: absolute;
        width: 100%;

    }

    #data-container li,
    #data-container2 li,
    #data-container3 li,
    #data-container4 li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #fff;
        color: #fff;
    }

    #data-container li:hover,
    #data-container2 li:hover,
    #data-container3 li:hover,
    #data-container4 li:hover {
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
    <span id="PageTittle_span" class="hidden">New Client</span>
    <div class="pim-container">
        <div class="form-div">
            <h4>New Client</h4>
            <div class="schema-form-section row">
                <?php
                $_SESSION["token"] = csrfToken();
                ?>
                <input type="hidden" name="token" value="<?= $_SESSION["token"] ?>">

                <div class=" bylocation">
                    <div class="input-field col s6 m6 8">
                        <select class="" id="location1" name="location1" title="Select Location">
                            <option id="location1" value="NA">Select Location</option>
                            <?php
                            $sqlBy = 'select id,location from location_master';
                            $sql = $conn->prepare($sqlBy);
                            $sql->execute();
                            $resultBy = $sql->get_result();

                            foreach ($resultBy as $key => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['location']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <label title="" for="loction" class="active-drop-down active">Location</label>
                    </div>
                </div>
                <div class=" byclient">
                    <div class="input-field col s6 m6 8">
                        <div class="form-group">
                            <select class="form-control" name="client_name1" id="client_name1">
                                <option value="NA">Select Client</option>
                            </select>
                            <label title="Select Client Name" for="client_name" class="active-drop-down active">Client</label>
                        </div>
                    </div>
                </div>
                <div class=" byprocess">
                    <div class="input-field col s6 m6 8">
                        <div class="form-group">
                            <select class="form-control" name="process1" id="process1">
                                <option value="NA">Select Process</option>
                            </select>
                            <label title="Select Process" for="process" class="active-drop-down active">Process</label>
                        </div>
                    </div>
                </div>
                <div class=" bysubprocess">
                    <div class="input-field col s6 m6 8">
                        <div class="form-group">
                            <select class="form-control" name="sub_process1" id="sub_process1">
                                <option value="NA">Select Sub Process</option>
                            </select>
                            <label title="Select Sub_process" for="sub_process" class="active-drop-down active">Sub Process</label>
                        </div>
                    </div>
                </div>

                <div class="input-field col s6 m6">
                    <input type="text" id="er_spoc" name="er_spoc" required />
                    <label for="er_spoc">ER Spoc</label>
                    <div id="data-container"></div>
                </div>
                <div class="input-field col s6 m6">
                    <input type="text" id="er_spoc2" name="er_spoc2" required />
                    <label for="er_spoc2">ER Spoc2</label>
                    <div id="data-container2"></div>
                </div>
                <div class="input-field col s6 m6">
                    <input type="text" id="er_spoc3" name="er_spoc3" required />
                    <label for="er_spoc3">ER Spoc3</label>
                    <div id="data-container3"></div>
                </div>
                <div class="input-field col s6 m6">
                    <input type="text" id="er_spoc4" name="er_spoc4" required />
                    <label for="er_spoc4">ER Spoc4</label>
                    <div id="data-container4"></div>
                </div>
                <div class="input-field col s12 m12 right-align">
                    <button type="submit" name="save" id="save" class="btn waves-effect waves-green">Save</button>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#save').click(function() {
            var validate = 0;
            var alert_msg = '';

            if ($('#location1').val() == 'NA') {
                $('#location1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanlocation').size() == 0) {
                    $('<span id="spanlocation" class="help-block">Required *</span>').insertAfter('#location1');
                }
                validate = 1;
            }
            if ($('#client_name1').val() == 'NA') {
                $('#client_name1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanclient_name').size() == 0) {
                    $('<span id="spanclient_name" class="help-block">Required *</span>').insertAfter('#client_name1');
                }
                validate = 1;
            }
            if ($('#process1').val() == 'NA') {
                $('#process1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spanprocess1').size() == 0) {
                    $('<span id="spanprocess1" class="help-block">Required *</span>').insertAfter('#process1');
                }
                validate = 1;
            }
            if ($('#sub_process1').val() == 'NA') {
                $('#sub_process1').parent('.select-wrapper').find('input.select-dropdown').addClass("has-error");
                if ($('#spansub_process1').size() == 0) {
                    $('<span id="spansub_process1" class="help-block">Required *</span>').insertAfter('#sub_process1');
                }
                validate = 1;
            }
            if ($('#er_spoc').val() == '') {
                $('#er_spoc').addClass("has-error");
                if ($('#spaner_spoc').size() == 0) {
                    $('<span id="spaner_spoc" class="help-block">Required *</span>').insertAfter('#er_spoc');
                }
                validate = 1;
            }
            if ($('#er_spoc2').val() == '') {
                $('#er_spoc2').addClass("has-error");
                if ($('#spaner_spoc2').size() == 0) {
                    $('<span id="spaner_spoc2" class="help-block">Required *</span>').insertAfter('#er_spoc2');
                }
                validate = 1;
            }
            if ($('#er_spoc3').val() == '') {
                $('#er_spoc3').addClass("has-error");
                if ($('#spaner_spoc3').size() == 0) {
                    $('<span id="spaner_spoc3" class="help-block">Required *</span>').insertAfter('#er_spoc3');
                }
                validate = 1;
            }
            if ($('#er_spoc4').val() == '') {
                $('#er_spoc4').addClass("has-error");
                if ($('#spaner_spoc4').size() == 0) {
                    $('<span id="spaner_spoc4" class="help-block">Required *</span>').insertAfter('#er_spoc4');
                }
                validate = 1;
            }
            if (validate == 1) {
                $('#alert_msg').html('<ul class="text-danger">' + alert_msg + '</ul>');
                $('#alert_message').show().attr("class", "SlideInRight animated");
                $('#alert_message').delay(5000).fadeOut("slow");
                return false;
            }
        });
    });
</script>
<script>
    $("#location1").change(function() {
        var location1 = $(this).val();
        $.ajax({
            url: '../Controller/get_client.php',
            type: 'GET',
            data: {
                location1: location1,
            },
            dataType: 'json',
            success: function(response) {
                //alert(response);
                $("#client_name1").html(response.client_name1);
                $("#process1").html(response.process1);
                $("#sub_process1").html(response.sub_process1);

            }
        });

        $("#client_name1").change(function() {
            var client_name1 = $(this).val();
            $.ajax({
                url: '../Controller/get_clientprocess.php',
                type: 'GET',
                data: {
                    location1: location1,
                    client_name1: client_name1,
                },
                dataType: 'json',
                success: function(response) {
                    //   alert(response);
                    $("#process1").html(response.process1);
                    $("#sub_process1").html(response.sub_process1);
                }
            });

            $("#process1").change(function() {
                var process1 = $(this).val();
                $.ajax({
                    url: '../Controller/get_subprocess.php',
                    type: 'GET',
                    data: {
                        client_name1: client_name1,
                        location1: location1,
                        process1: process1,
                    },
                    dataType: 'json',
                    success: function(response) {
                        // alert(response);
                        $("#sub_process1").html(response.sub_process1);
                    }
                });

                $("#sub_process1").change(function() {
                    var sub_process1 = $(this).val();
                    $.ajax({
                        url: '../Controller/get_alignmentby_location.php',
                        type: 'GET',
                        data: {
                            location1: location1,
                            client_name1: client_name1,
                            process1: process1,
                            sub_process1: sub_process1,
                        },
                        dataType: 'json',
                        success: function(response) {
                            // alert(response);
                            $("#er_spoc").val(response.er_spoc);
                            $("#er_spoc2").val(response.er_spoc2);
                            $("#er_spoc3").val(response.er_spoc3);
                            $("#er_spoc4").val(response.er_spoc4);
                        }
                    });

                    $('#er_spoc').keyup(function() {
                        var term = $(this).val();

                        var resp_data_format = "";
                        $.ajax({
                            url: "../Controller/autocomplete_employee_active.php",
                            data: {
                                term: term,

                            },
                            method: "get",
                            dataType: "json",
                            success: function(response) {
                                for (var i = 0; i < response.length; i++) {
                                    resp_data_format = resp_data_format + "<li class='select_country'>" + response[i] + "</li>";
                                };
                                $("#data-container").html(resp_data_format);
                            }
                        });
                    });
                    $('#er_spoc2').keyup(function() {
                        var term = $(this).val();
                        var resp_data_format2 = "";
                        $.ajax({
                            url: "../Controller/autocomplete_employee_active.php",
                            data: {
                                term: term,

                            },
                            method: "get",
                            dataType: "json",
                            success: function(response) {
                                for (var i = 0; i < response.length; i++) {
                                    resp_data_format2 = resp_data_format2 + "<li class='select_country2'>" + response[i] + "</li>";
                                };

                                $("#data-container2").html(resp_data_format2);
                            }
                        });
                    });
                    $('#er_spoc3').keyup(function() {
                        var term = $(this).val();
                        var resp_data_format3 = "";
                        $.ajax({
                            url: "../Controller/autocomplete_employee_active.php",
                            data: {
                                term: term,

                            },
                            method: "get",
                            dataType: "json",
                            success: function(response) {
                                for (var i = 0; i < response.length; i++) {
                                    resp_data_format3 = resp_data_format3 + "<li class='select_country3'>" + response[i] + "</li>";
                                };
                                $("#data-container3").html(resp_data_format3);
                            }
                        });
                    });
                    $('#er_spoc4').keyup(function() {
                        var term = $(this).val();
                        var resp_data_format4 = "";
                        $.ajax({
                            url: "../Controller/autocomplete_employee_active.php",
                            data: {
                                term: term,
                            },
                            method: "get",
                            dataType: "json",
                            success: function(response) {
                                for (var i = 0; i < response.length; i++) {
                                    resp_data_format4 = resp_data_format4 + "<li class='select_country4'>" + response[i] + "</li>";
                                };
                                $("#data-container4").html(resp_data_format4);
                            }
                        });
                    });
                    $(document).on("click", ".select_country", function() {
                        var selected_country = $(this).html();
                        $('#er_spoc').val(selected_country);
                        $('#data-container').html('');
                    });
                    $(document).on("click", ".select_country2", function() {
                        var selected_country2 = $(this).html();
                        $('#er_spoc2').val(selected_country2);
                        $('#data-container2').html('');
                    });
                    $(document).on("click", ".select_country3", function() {
                        var selected_country3 = $(this).html();
                        $('#er_spoc3').val(selected_country3);
                        $('#data-container3').html('');
                    });
                    $(document).on("click", ".select_country4", function() {
                        var selected_country4 = $(this).html();
                        $('#er_spoc4').val(selected_country4);
                        $('#data-container4').html('');
                    });

                });
            });
        });
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>