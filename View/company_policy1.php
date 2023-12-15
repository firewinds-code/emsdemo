<?php
// Server Config file
require_once(__dir__ . '/../Config/init.php');
// DB main Config / class file
require_once(CLS . 'MysqliDb.php');
// Default timezone for page and date time
date_default_timezone_set('Asia/Kolkata');
// Main contain Header file which contains html , head , body , one default form 
require(ROOT_PATH . 'AppCode/nHead.php'); ?>
<script>

  document.getElementById("toolbarViewerRight").style.display = "none";


</script>
   <div>
            <h4>Company Policy</h4>
		<!--	<embed
    src="../FileContainer/HR Policy Document.pdf#toolbar=0&navpanes=0&scrollbar=0"
    type="application/pdf"
    frameBorder="0"
    scrolling="auto"
    height="500px"
    width="100%"
></embed>
-->
    <iframe
    src="../FileContainer/HR Policy Document.pdf#toolbar=0&navpanes=0&scrollbar=0"
    frameBorder="0"
    scrolling="auto"
    height="900px"
    width="100%"></iframe>
</div>


<?php include(ROOT_PATH . 'AppCode/footer.mpt'); ?>
