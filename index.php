<!DOCTYPE html>
<html>
<head>
<style>

body {
     background-image: url("background.jpg");
     background-size:cover;
}

h1 {
    color: orange;
    text-align: center;
}

p {
    font-family: "Times New Roman";
    font-size: 20px;
    text-align: center;
}
</style>
</head>
<body>

<br>
<br>
<br>
<br>
<br>

<h1>Welcome to this Local Community</h1>

<br>
<br>
<br>
<br>
<br>

<p>
<a href="photo_upload/photos.php">Photos Uploading and Sharing</a>
</p>

<br>

<?php

$question_title_file = "./questionnaire/title.txt";

$question_title=file_get_contents($question_title_file, true);

?>




<p>
<a href="questionnaire/poll-multi-choice/poll-multi.php"> <?php echo $question_title ?></a>
<p>

</body>
</html>
