
 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test12";

// Create connection

if(isset($_POST['senddata'])){
	$description= $_POST['description'];

$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
 $sql="insert into testtable set description='".$description."'";
if (mysqli_query($conn, $sql)){
	echo 'inserted';
}

}


$sql = "SELECT id, description FROM testtable";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<div> " . $row["description"]. "</div>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<html>
<head>
	<meta charset="utf-8">
	<title>CKEditor Sample</title>
	<script src="../ckeditor.js"></script>
	<script src="js/sample.js"></script>
	<!--<link rel="stylesheet" href="css/samples.css">-->
	<!--<link rel="stylesheet" href="toolbarconfigurator/lib/codemirror/neo.css">-->
</head>
<body>
	
<main>
<form method="post">
	<div class="adjoined-bottom">
		<div class="grid-container">
			<div class="grid-width-100">
				<textarea id="editor" name='description'></textarea>
					<!--<h1>Hello world!</h1>
					<p>I'm an instance of <a href="http://ckeditor.com">CKEditor</a>.</p>
				</div>-->
			</div>
		</div>
	</div>
	<input type='submit' name='senddata' value='Add'  id='senddata'>
	
</form>	
	</main>
<script>
	initSample();
</script>	
</body>
</html>