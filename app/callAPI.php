<?php

require_once(__dir__.'/../Config/init.php');
?>
<script src="../jspdf/jquery.js"></script>
<script>
		var txtEmployeeID=$('#hempid').val();
		var dirLoc=$('#dirloc').val();
		var Comment='Validated_by_emp';
		//alert('ALetter_download_multipdf2.php?EmpID='+txtEmployeeID+"&dirloc="+dirLoc+'&Comment='+Comment);
		window.open('ALetter_download_app.php', '_blank');
		alert('tt');
	</script>