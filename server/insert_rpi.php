<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<style>
body  {
    background-image: url('insert_rpi.jpg');
    background-size: cover;   
}
</style>
<script>
dl {
    font:normal 12px/15px Arial;
    position: relative;
    width: 350px;
}
dt {
    clear: both;
    float:left;
    width: 130px;
    padding: 4px 0 2px 0;
    text-align: left;
}
dd {
    float: left;
    width: 200px;
    margin: 0 0 8px 0;
    padding-left: 6px;
}

</script>
</head>
<body>

<?php

//This is for assign the parameters passed from the map.

$id=$_GET['new_id'];
//echo $id;
$rpi_id="Rpi".$id;
$latitude=$_GET['latitude'];
//echo $latitude;
$longtitude=$_GET['longtitude'];
//echo $longtitude;

?>

<div class="container-fluid">
<form align="left" action="register_rpi.php" method="post" enctype="multipart/form-data">
<dl>
	<dt>
		<label for="rpi_id">Raspberry Pi ID:</label>
	</dt>
	<dd>
		<input
			name="rpi_id"
			id="rpi_id"
			type="text"
			value=<?php echo $rpi_id;?> />
	</dd>
	<dt>
		<label for="ssid">Raspberry Pi SSID:</label>
	</dt>
	<dd>
		<input
			name="ssid"
			id="ssid"
			type="text" />
	</dd>
	<dt>
		<label for="description">Description:</label>
	</dt>
	<dd>
                <input
                        name="description"
                        id="description"
                        type="text" />
        </dd>
	<dt>
                <label for="latitude">Latitude:</label>
        </dt>
        <dd>
                <input
                        name="latitude"
                        id="latitude";
                        type="text" 
			value=<?php echo $latitude;?>/>
        </dd>
	<dt>
                <label for="longtitude">Longtitude:</label>
        </dt>
        <dd>
                <input
                        name="longtitude"
                        id="longtitude";
                        type="text" 
			value=<?php echo $longtitude;?>/>
        </dd>
	<dt>
                <label for="fileToUpload">Select image of your Raspberry Pi to upload:</label>
        </dt>
        <dd>
                <input
                        name="fileToUpload"
                        id="fileToUpload"
                        type="file" />
        </dd>		
	<dt>
                <label for="Add This RaspberryPi">Clike this button to add this Raspberry Pi</label>
        </dt>
        <dd>
                <input
                        value="Add This RaspberryPi"
                        type="submit" />
        </dd>
</dl>		
</form>
</div>



</body>
</html>
