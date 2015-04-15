<!DOCTYPE html>
<html>
<style>
body {
    background-color: #d0e4fe;
}

h1 {
    color: orange;
    text-align: center;
}

.message{
    font-family: "Times New Roman";
    font-size: 15px;
}
</style>
</head>
<body>

<?php

//Here are the three files storing the inputs of the administrator
//The raspberry pi id, the title of the questionnaire, the questions of the questionnaire

$id_file1="../questionnaire/id.txt";
$title_file2="../questionnaire/title.txt";
$questions_file3="../questionnaire/questions.txt";

if (!empty($_POST["text1"])) {
   $text1 = $_POST["text1"];
   file_put_contents($id_file1,$text1);
}

if (!empty($_POST["text2"])) {
   $text2 = $_POST["text2"];
   file_put_contents($title_file2,$text2);
}
if (!empty($_POST["text3"])) {
   $text3 = $_POST["text3"];
   file_put_contents($questions_file3,$text3);
}

$id_text = file_get_contents($id_file1, true);
$title_text = file_get_contents($title_file2, true);
$questions_text = file_get_contents($questions_file3, true);

?>

<h1>Please set the id of the raspberry pi, the title and the questions of the questionnaire</h1>

<div class= "message", align = "center">
  <p> Please type the ID of the rasbperry pi you have set
  <p> For example "Rpi1"
</div>

<form align="center" method="POST" action="?">

<textarea id="text1" name="text1" class="form-control" rows="1"><?php echo $id_text?></textarea>
<br><br>

<div class= "message", align = "center">
  <p> Please type the title of the questionnaire here
  <p> For example "Please select one or more types of music"
</div>

<textarea id="text2" name="text2" class="form-control" rows="10"><?php echo $title_text?></textarea>
<br><br>

<div class= "message", align = "center">
  <p> Please type the questions of the questionnaire here
  <p> When you finish one question, press \n, then enter the next one 
  <p> Please don't type \n at the last question you enter
  <p> For example "Jazz\n Classic Music\n R&B"
</div>

<textarea id="text3" name="text3" class="form-control" rows="10"><?php echo $questions_text?></textarea>
<br>

<div class= "message", align = "center">
  <p> Please press save when you finish the setting
</div>

<button type="submit" class="btn btn-primary">Save</button>
</form>

<?php
    if($title_text!=null){
	$file_dir = "../questionnaire/";
	$new_name ="title_".$id_text.".txt";
	$file_rename = $file_dir.$new_name;

	$fp=fopen("$file_rename","w");
	fwrite($fp, $title_text);
	fclose($fp);

	//rename($title_file2, $file_rename);

	//Here is the path of this data file
	$data_path="../questionnaire/".$new_name;

	
	//Here is the parameters of the ftp server
	//Please put your ftp_server, user_name and password in the " " below.
	$ftp_server = "";
	$ftp_user_name="";
	$ftp_user_pass="";

	// set up basic connection
	$conn_id = ftp_connect($ftp_server)  or die ("Cannot connect to host");

	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);


	// check connection
	if ((!$conn_id) || (!$login_result)) {
	    echo "FTP connection has failed!";
	    echo "Attempted to connect to $ftp_server for user $ftp_user_name";
    	      exit;
	} else {
	    echo "Connected to $ftp_server.";
	}	


	//Change to the correct directory.
	//Please customize this path.
	//Here is only an example.
	ftp_chdir($conn_id, 'www/server/votes/');

	// get contents of the current directory
	//$contents = ftp_nlist($conn_id, ".");
	//
	// // output $contents
	//var_dump($contents);

	// upload a file
	if (ftp_put($conn_id, $new_name, $data_path, FTP_ASCII)) {
	        echo "successfully uploaded $data_file\n";	
	} else {
	        echo "There was a problem while uploading $file\n";
	}

	// close the connection
	ftp_close($conn_id);

     }
?>



</body>
</html>




