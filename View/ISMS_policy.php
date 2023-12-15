<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php');

?>

<!-- This div not contain a End on this Page because this activity already done in footer Page -->
<div id="content" class="content">

    <!-- Header Text for Page and Title -->
    <span id="PageTittle_span" class="hidden">ISMS Policy</span>

    <!-- Main Div for all Page -->
    <div class="pim-container row" id="div_main">

        <!-- Sub Main Div for all Page -->
        <div class="form-div">

            <!-- Header for Form If any -->
            <h4>ISMS Policy</h4>

            <!-- Form container if any -->
            <div class="schema-form-section row">

                <div class="input-field col s8 m8 8">
                    <select class="form-control" name="policy" id="policy">

                        <option value="policy1">Cogent E Services Access Control Policy</option>
                        <option value="policy2">Cogent E Services Change Management Policy</option>
                        <option value="policy3">Cogent E Services Patch Management Policy</option>
                        <option value="policy4">Cogent E Services Risk Management Policy</option>

                        <option value="policy5">Human Resource Security Policy</option>
                        <option value="policy6">Information Security Policy</option>

                    </select>
                    <label for="policy" class="active-drop-down active">ISMS Policy</label>
                </div>
                <br><br><br>

                <div class="input-field col s12 m12 12">
                    <embed id='emd1' src="../FileContainer/Polices_ISMS/Cogent  E Services  Access Control Policy  V.4.0.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd2' src="../FileContainer/Polices_ISMS/Cogent  E Services  Change Management Policy V4.0.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd3' src="../FileContainer/Polices_ISMS/Cogent  E Services  Patch Management Policy V.4.0.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd4' src="../FileContainer/Polices_ISMS/Cogent  E Services  Risk Management Policy V.4.0.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />

                    <embed id='emd5' src="../FileContainer/Polices_ISMS/Cogent E Services Human Resource Security Policy Version 4.0.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />
                    <embed id='emd6' src="../FileContainer/Polices_ISMS/Cogent E Services Information Security Policy Version 4.0.pdf#toolbar=0" type="application/pdf" style="height: 800px;overflow-y: auto;width :100%" />

                </div>
            </div>
        </div>
    </div>
</div>
<!--Content Div for all Page End -->

<script>
    $(document).ready(function() {
        $('#emd1').show();
        $('#emd2').hide();
        $('#emd3').hide();
        $('#emd4').hide();
        $('#emd5').hide();
        $('#emd6').hide();


        $('#policy').on('change', function() {
            if ($('#policy').val() == 'policy1') {
                $('#emd1').show();
                $('#emd2').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();

                return false;
            };
            if ($('#policy').val() == 'policy2') {
                $('#emd2').show();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();

                return false;
            };
            if ($('#policy').val() == 'policy3') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').show();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').hide();

                return false;
            };
            if ($('#policy').val() == 'policy4') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').show();
                $('#emd5').hide();
                $('#emd6').hide();

                return false;
            };
            if ($('#policy').val() == 'policy5') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').show();
                $('#emd6').hide();

                return false;
            };
            if ($('#policy').val() == 'policy6') {
                $('#emd2').hide();
                $('#emd1').hide();
                $('#emd3').hide();
                $('#emd4').hide();
                $('#emd5').hide();
                $('#emd6').show();

                return false;
            };

        });
    });
</script>
<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>