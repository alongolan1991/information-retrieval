<?php

	//Gets all the html files in the directory
	$directory = $_GET['dir']."/*.{html}";
	$files = glob($directory, GLOB_BRACE);

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
	

    <?php

foreach($files as $file) {
	$meta = get_meta_tags($file);?>
					<!-- Project One -->
					<div class="row">
						<div class="col-md-5">
						<a href="#">
							<img class="img-fluid rounded mb-3 mb-md-0" src="images/sein-list.jpg" alt="">
						</a>
						</div>
						<div class="col-md-7">
						<h3><a href = "DB/DB.php?html=<?= $meta['key']?>&word=" target="_blank"><?=$meta['key'].'	'.$meta['episodename']?></a></h3>
						<p><strong>Directed By:</strong> <?= $meta['directed']?></p>
					    <p><strong>Written By:</strong> <?= $meta['written']?></p>
					    <p><strong>Acters:</strong><?= $meta['acters']?></p>
					    <p><strong>Original Air Date:</strong><?= $meta['originaldate']?></p>
					    <p><?= $meta['episodedescription']?></p>
						</div>
					</div>

			<?php }		?>
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

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/clean-blog.min.js"></script>

  </body>

</html>



