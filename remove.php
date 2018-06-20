<?php
include "wordDBhandler.php";
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<title>Seinfeld</title>
	<meta name="description" content="Seinfeld" />
	<meta name="keywords" content="HTML,CSS,XML,JavaScript" />
	<meta charset="UTF-8" />
	<!--Style-->
	
	<!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

	<!--Scripts-->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
</head>

<body>
<!--Header-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand" href="index.html">Information Retrival Project</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.html">Search</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="all_files.php?dir=DB">All Documents</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="images.html">All Images</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin.php?dir=DB">Admin</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <header class="masthead" style="background-image: url('images/logo2.png');height:600px">
      
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">
              <h1></h1>
              <span class="subheading"></span>
            </div>
          </div>
        </div>
      </div>
    </header>
<!--Main-->
<div id="main">
<div id= "upload">
<?php

$db = initDB();

$file = $_GET['remove_file'];
$filename = 'DB/'.$file;

if(is_writable($filename)) {
	removeDocFromDB($db, $file);
	deleteDocFromDB($db, $file);
	unlink($filename);
} else {
	echo 'File is not writable <br/>';
}

echo 'File was removed from Data Base<br/>'
?>
</div>
</div>
<div class="clean"></div>

<!-- Footer -->
<footer>
	<p>Alon Golan & Yaniv Yona</p>
</footer>

</body>
</html>
