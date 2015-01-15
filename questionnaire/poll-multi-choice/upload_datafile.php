<!DOCTYPE html>
<html>
<body>

<?php

// A simple PHP/FTP upload to a remote site
$id=file_get_contents('/var/www/questionnaire/id.txt');

//This is the file that I want to upload to the ftp server
$file_name="votes_".$id.".txt";

//Here is the path of this data file
$data_path="../questionnaire/app.data/poll-multi-choice.def/".$file_name;


//Here is the parameters of the ftp server
//Please put your ftp_server, user_name and password in the " " below.
$ftp_server = " ";
$ftp_user_name=" ";
$ftp_user_pass=" ";

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
if (ftp_put($conn_id, $file_name, $data_path, FTP_ASCII)) {
	echo "successfully uploaded $data_file\n";
} else {
	echo "There was a problem while uploading $file\n";
}

// close the connection
ftp_close($conn_id);
?>


</body>
</html>
