<?php
	//Gets all the html files in the directory
	$directory = $_GET['dir']."/*.{html}";
	$files = glob($directory, GLOB_BRACE);
	$directory = $_GET['dir']."/*.{jpg,JPG,Jpg,gif,GIF,Gif,png,PNG,Png}";
	$images = glob($directory, GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="en">

  <head>
   <title>Seinfeld</title>
	<meta name="description" content="Seinfeld" />
	<meta name="keywords" content="HTML,CSS,XML,JavaScript" />
	<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
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
            <li class="nav-item">
              <a class="nav-link" href="help.pdf" target="_blank">Help</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Header -->
    <header class="masthead" style="background-image: url('images/logo.png');height:500px">
      
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

    <!-- Main Content -->
    <div class="container">
    <div class="row">
    <div class="col-sm-6">   
	<div id="DB" class="list-group">
		<h1> DataBase </h1>
	 <br/>
			<?php
				foreach($files as $file) {
					$pos = strpos($file, 'DB/');
					$key = substr($file, (-$pos+3), 6);
					echo "<a href = '".$file."' target='iframe' class='list-group-item list-group-item-action'>Doc ".$key."</a>";
				}
			?>

	</div>
    </div>
      </div>
  <br/>
	<div class="row">
		<div class="col-sm-12">
	<iframe src="" name="iframe" id="iframe" style="width: 890px;height: 606px;"></iframe>
	</div>
	</div>
  <br/>
    <div class="row">
    <div class="col-sm-6">
	<div class="list-group">
			<a href = "upload.php" class="list-group-item list-group-item-action">Upload document</a>
			<a href = "#" id = "inactive" onclick ="inactiveFile('iframe','inactive')" class="list-group-item list-group-item-action">Enable/Disable the document</a>
			<a href = "#" id = "delete" onclick ="deleteFile('iframe','delete')" class="list-group-item list-group-item-action">Delete the document</a>
			<a href = "initDB.php?dir=DB" id = "refresh" class="list-group-item list-group-item-action">Refresh Data Base</a>
	</div>
    </div>
      </div>
      <br/>
  </div>
 <hr>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">

            <p class="copyright text-muted">Copyright &copy; Alon Golan & Yaniv Yona 2018</p>
          </div>
        </div>
      </div>
    </footer>
	<script type="text/javascript" src="js/scripts.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/clean-blog.min.js"></script>

  </body>

</html>


