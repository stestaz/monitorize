<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>API Key Management Tool</title>

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<?php
	session_start();
	if($_SESSION["user"] ==''){
		header("location: ../admin/index.html");
	}
	?>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand">API Key Management Tool</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home <span class="sr-only">(current)</span></a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">API Key Management Panel</h1>
    </div>
  </div>
  <hr>
</div>
<div class="container">
	<div class="row">
    	<div class="col-lg-12">
        	<button type="button" class="btn btn-default col-lg-6 form-control" id="newBtn">New Key</button>
        </div>
    </div>
  <hr>
  	<div class="row">
    	<div class="col-lg-12">
        	<button type="button" class="btn btn-default col-lg-6 form-control" id="listBtn">Edit Key</button>
        </div>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="text-center col-md-6 col-md-offset-3">
      <p>Copyright &copy; 2016 &middot; All Rights Reserved &middot; <a href="http://www.stestaz.com/" >Stefano Falzaresi</a></p>
    </div>
  </div>
  <hr>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.2.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
<script src="scripts/adminScripts.js"></script>
</body>
</html>
