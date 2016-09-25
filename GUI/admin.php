<?php
if( !isset($_COOKIE["raspeinUser"]) ){
    header("location:index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Raspein Project - Admin Page</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/footer.css">
<link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<header>
        <nav class="myNavBar navbar navbar-default navbar-fixed-top">
            <div class="container-fluid col-md-10 col-md-offset-1 col-xs-12 col-xs-offset-0"> 
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar">		</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="myBrand" href="index.html">Raspèin</a>
                </div>
                <div class="collapse navbar-collapse" id="defaultNavbar1">
                    <ul class="nav navbar-nav navbar-right myUl">
                        <li><a class="loginHref" href="#">Welcome</a></li>
                        <li><a href="#" id="loginBtn">Login</a></li>
                    </ul>
                </div>
        <!-- /.navbar-collapse --> 
        <div class="modal-dialog myLoginModal pull-right">
            <div class="container containerLogin">
                <form class="form-inline" role="form">
                    <div class = "input-group myLoginGroup">
                        <span class = "input-group-addon glyphicon glyphicon-user myLoginIcon myGrey" aria-hidden="true"></span>
                        <label for="username" class="sr-only">Username</label>
                        <input type = "text" id="username" class="form-control myLoginbox" placeholder = "Username">
                    </div>
                </form>
                <form class="form-inline" role="form">
                    <div class = "input-group myLoginGroup">
                        <span class="input-group-addon myLoginIcon"><img src="img/glyphicon-key.png" aria-hidden="true" alt="password icon"/></span>
                        <label for="password" class="sr-only">Password</label>
                        <input type = "password" id="password" class="form-control myLoginbox" placeholder = "Password">
                    </div>
                </form>	
                <form class="form-inline" role="form">
                    <div class="input-group myLoginGroup">
                        <button type="button" id="loginBtnGo" class="btn btn-info form-control">Login</button>
                    </div>
                </form>
            </div>
        </div> 
      </div>
      <!-- /.container-fluid --> 
    </nav>
    <div class="adminText col-xs-12 col-xs-offset-0 col-md-8 col-md-offset-2">
        <h1 >ADMIN PAGE</h1>
        <div class="col-xs-12 col-xs-offset-o col-md-4 col-md-offset-4">
            <p > With this page administrators can manage their installation and all devices.
    
            </p>
		</div>
    </div>
<img class="img-responsive" src="img/headerAdmin.png" alt="header image"/>
</header>
<body>
<div class="container-fluid">
    <div class="container col-xs-12 col-xs-offset-0 col-md-10 col-md-offset-1">
        <div class="devicesContainer">
            <div class="row">
                <h2>Devices</h2>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-xs-2 col-xs-offset-10 pull-right">
                        <!-- <span id="removeTower" class="glyphicon glyphicon-remove redGreyBtn"></span> -->
                        <span id="addTower" class="glyphicon glyphicon-plus redGreyBtn"></span>
                    </div>
                </div>
                <input type="hidden" id="curTowerId">
                <div class="towerContainer">
                </div>
            </div>
        </div>
        <div class="systemcontainer">
            <div class="row">
                <h2>System</h2>
            </div>
            <div class="container-fluid">
                <div class="row ">
                    <div class="col-xs-12 mySystemContainer">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 text-center">
                                <form class="form-inline myDataForm" role="form">
                                    <div class = "input-group">
                                        <span class="input-group-addon myDataIcon myGrey"><img src="img/glyphicon-key.png" aria-hidden="true" alt="password icon"/></span>
                                        <label for="key" class="sr-only">Key</label>
                                        <input type = "text" id="key" class="form-control myDataBox" placeholder = "Key">
                                    </div>
                                </form>
                            </div>
                            <div class="col-xs-12 col-sm-6 text-center">
                                <form class="form-inline myDataForm " role="form">
                                    <div class = "input-group">
                                        <span class = "input-group-addon glyphicon glyphicon-folder-open myDataIcon myGrey" aria-hidden="true"></span>
                                        <label for="directory" class="sr-only">Files folder</label>
                                        <input type = "text" id="directory" class="form-control myDataBox" placeholder = "Files Directory">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 text-center">
                                <form class="form-inline myDataForm" role="form">
                                    <div class = "input-group">
                                        <span class = "input-group-addon glyphicon glyphicon-wrench myDataIcon myGrey" aria-hidden="true"></span>
                                        <label for="twConsKey" class="sr-only">Twitter Consumer Key</label>
                                        <input type = "text" id="twConsKey" class="form-control myDataBox" placeholder = "Twitter Consumer Key">
                                    </div>
                                </form>
                            </div>
                            <div class="col-xs-12 col-sm-6 text-center">
                                <form class="form-inline myDataForm" role="form">
                                    <div class = "input-group">
                                        <span class = "input-group-addon glyphicon glyphicon-wrench myDataIcon myGrey" aria-hidden="true"></span>
                                        <label for="twConsSec" class="sr-only">Twitter Consumer Secret</label>
                                        <input type = "text" id="twConsSec" class="form-control myDataBox" placeholder = "Twitter Consumer Secret">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 text-center">
                                <form class="form-inline myDataForm" role="form">
                                    <div class = "input-group">
                                        <span class = "input-group-addon glyphicon glyphicon-lock myDataIcon myGrey" aria-hidden="true"></span>
                                        <label for="twAccTok" class="sr-only">Twitter Access Token</label>
                                        <input type = "text" id="twAccTok" class="form-control myDataBox" placeholder = "Twitter Access Token">
                                    </div>
                                </form>
                            </div>
                            <div class="col-xs-12 col-sm-6 text-center">
                                <form class="form-inline myDataForm" role="form">
                                    <div class = "input-group">
                                        <span class = "input-group-addon glyphicon glyphicon-lock myDataIcon myGrey" aria-hidden="true"></span>
                                        <label for="twAccTokSec" class="sr-only">Twitter Access Token Secret</label>
                                        <input type = "text" id="twAccTokSec" class="form-control myDataBox" placeholder = "Twitter Access Token Secret">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 text-center">
                                <form class="form-inline myDataForm" role="form">
                                    <div class = "input-group">
                                        <span class = "input-group-addon glyphicon glyphicon-user myDataIcon myGrey" aria-hidden="true"></span>
                                        <label for="twContact" class="sr-only">Twitter Contact Name</label>
                                        <input type = "text" id="twContact" class="form-control myDataBox" placeholder = "Twitter Contact Name">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-8">
                                <form class="form-inline" role="form">
                                    <div>
                                        <button type="button" id="submitSystem" class="btn btn-info generalBtn pull-right">SUBMIT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
     </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  		<div class="modal-dialog myModalDialog">
    		<!-- Modal content-->
    		<div class="modal-content myModal">
				<div class="myWhiteDiv">
          			<div class="modal-header myModalHeader">
                        <div class="col-xs-10 col-xs-offset-0 col-sm-6 col-sm-offset-3">
                       		<h2>New Device</h2>
                        </div>
                        <div class="col-xs-2">
        					<span class = "input-group-btn close glyphicon glyphicon-remove myBlue pull-right" aria-hidden="true" data-dismiss="modal"></span>
                        </div>
          			</div>
          			<div class="modal-body ">
                        <div class="container-fluid myModalBody">
                        	<div class="col-xs-12 col-xs-offset-0 col-md-8 col-md-offset-2">
                                <div class="row">
                                    <div class="col-xs-6 text-center">
                                        <form class="form-inline myDataForm" role="form">
                                            <div class = "input-group">
                                                <span class="input-group-addon myDataIcon myGrey">#</span>
                                                <label for="modDevName" class="sr-only">Device Name</label>
                                                <input type = "text" class="form-control myDataBox" id="modDevName" placeholder = "Name">
                                                <input type="hidden" id="modDevId">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <form class="form-inline myDataForm " role="form">
                                            <div class = "input-group mySelect">
                                                <span class = "input-group-addon glyphicon glyphicon-tint myDataIcon myGrey" aria-hidden="true"></span>
                                                <label for="modDevColor" class="sr-only">Device Color</label>
                                                <select id="modDevColor" class="myDataBox form-control">
                                                  <option value="0">Color</option>
                                                  <option value="blue">Blue</option>
                                                  <option value="green">Green</option>
                                                  <option value="red">Red</option>
                                                  <option value="yellow">Yellow</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 text-center">
                                        <form class="form-inline myDataForm" role="form">
                                            <div class = "input-group">
                                                <span class="input-group-addon myDataIcon myGrey glyphicon glyphicon-tower" aria-hidden="true"></span>
                                                <label for="modDevTower" class="sr-only">Tower</label>
                                                <input type = "text"  class="form-control myDataBox" id="modDevTower" placeholder = "Tower">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <form class="form-inline myDataForm " role="form">
                                            <div class = "input-group">
                                                <span class = "input-group-addon glyphicon glyphicon-map-marker myDataIcon myGrey" aria-hidden="true"></span>
                                                <label for="modDevPosition" class="sr-only">Position</label>
                                                <input type = "text" class="form-control myDataBox" id="modDevPosition" placeholder = "Position">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                	<p>Sensors</p>
                                </div>
 								<div class="row">
                                    <div class="col-xs-12 text-center">
                                        <form class="form-inline myDataForm" role="form">
                                            <div class = "input-group">
                                                <span class="input-group-addon myDataIcon myGrey"><img src="img\littleCPUIcon.png" aria-hidden="true" class="img" alt="DISK icon"></span>
                                                <label for="cpuFrom" class="sr-only">CPU Range From</label>
                                                <input type = "number"  class="myDataBox" id="cpuFrom" placeholder = "From">
                                                <label for="cpuTo" class="sr-only">CPU Range To</label>
                                                <input type = "number"  class="myDataBox" id="cpuTo" placeholder = "To">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-xs-12 text-center">
                                        <form class="form-inline myDataForm" role="form">
                                            <div class = "input-group">
                                                <span class="input-group-addon myDataIcon myGrey"><img src="img\littleMEMIcon.png" aria-hidden="true" class="img" alt="MEM icon"></span>
                                                <label for="memFrom" class="sr-only">Memory Range From</label>
                                                <input type = "number"  class="myDataBox" id="memFrom" placeholder = "From">
                                                <label for="memTo" class="sr-only">Memory Range to</label>
                                                <input type = "number"  class="myDataBox" id="memTo" placeholder = "To">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-xs-12 text-center">
                                        <form class="form-inline myDataForm" role="form">
                                            <div class = "input-group">
                                                <span class="input-group-addon myDataIcon myGrey"><img src="img\littleTEMPIcon.png" aria-hidden="true" class="img" alt="TEMP icon"></span>
                                                <label for="tempFrom"class="sr-only">Temperature Range From</label>
                                                <input type = "number"  class="myDataBox" id="tempFrom" placeholder = "From">
                                                <label for="tempTo"class="sr-only">Temperature Range To</label>
                                                <input type = "number"  class="myDataBox" id="tempTo" placeholder = "To">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-xs-12 text-center">
                                        <form class="form-inline myDataForm" role="form">
                                            <div class = "input-group">
                                                <span class="input-group-addon myDataIcon myGrey"><img src="img\littleDISKIcon.png" aria-hidden="true" class="img" alt="DISK icon"></span>
                                                <label for="diskFrom"class="sr-only">Disk Range From</label>
                                                <input type = "number"  class="myDataBox" id="diskFrom" placeholder = "From">
                                                <label for="diskTo"class="sr-only">Disk Range To</label>
                                                <input type = "number"  class="myDataBox" id="diskTo" placeholder = "To">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                 
                                <form class="form-inline" role="form">
                                    <div>
                                        <button type="button" id="submitDevice" class="btn btn-info generalBtn pull-right" data-dismiss="modal">SUBMIT</button>
                                    </div>
                                </form>
                                </div>
                        	</div>
          				</div>
        			</div>
        		</div>
    		</div>
    	</div>
</div>
<hr>
<footer>
    <div class="nav myFooter">
    <div class="container-fluid col-md-10 col-md-offset-1 col-xs-12 col-xs-offset-0">
        <div class="row">
            <div class="col-xs-12"> 
                <div class="col-xs-3">
                    <img src="img/logo_alma_mater.png" class="logoAlma img-responsive" alt="alma mater footer logo"/>
                    <a href="http://www.w3.org/WAI/WCAG2AAA-Conformance"
      						title="Explanation of WCAG 2.0 Level Triple-A Conformance">
 						<img height="32" class="logoW3C" width="88" 
          						src="http://www.w3.org/WAI/wcag2AAA"
          						alt="Level Triple-A conformance, 
          						W3C WAI Web Content Accessibility Guidelines 2.0"></a>
                </div>
                <div class="col-xs-3 pull-right">
                    <img src="img/social_icons.png" class="loghisocial pull-right" alt="social media footer icons"/>
                </div> 
            </div>
        </div>
    </div>
    </div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.2.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.min.js"></script>
<script src="js/adminScripts.js"></script>
<script src="js/loginScripts.js"></script>
</body>
</html>
