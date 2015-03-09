<?php	
	
	/*
	 This is the profile page. A recommended person is displayed here.
	 This page fills the users info on this page.
	 Next to this page is the people you may know.
	 We call the Graph API to display the people that the profile may know.
	 The selection criteria for people you may know are :
	 1. Friends
	 2. Tags


	@Author : Kevin Oyugi

	*/ 

	# start the session
	ob_start();
	error_reporting(E_ALL);
	session_start();
	

	# imports
	require_once("WebAppService.php");
	require_once 'Klogger.php';
	
	# flash messages
	$flash = isset($_SESSION['flash-message']) ? $_SESSION['flash-message'] : null;

	# logged in?
	if(!isset($_SESSION['authData'])){
		$_SESSION['flash-message'] = array("type" => "notice", "msg" => "Please Login to continue");
		header("Location: login.php");
	}
	
	# if so continue
	$phone = (isset($_GET['phone'])) ? stripslashes(strip_tags($_GET['phone'])) : null;
	if($phone == null){
		$_SESSION['flash-message'] = array("type" => "notice", "msg" => "Please search to begin");
		header("Location: index.php");
	}
		
	## query db for each of the info
	# instantiate the awesome webAppService
	$service  = new WebAppService();

	$modTerm = $phone;

	// graphAPI credentials
	$username = "87bf83fc";
	$password = "f4a1112a";

	// call the graphAPI
	# search by phone number
	$findByPhone = $service->search('http://127.0.0.1:3000/api/people/phone/'.$modTerm.'?username='.$username.'&password='.$password);
	// var_dump($findByPhone);

	$data = $findByPhone;

	$dataArr = (json_decode($data, true));
	
	
	# generate the token
	$token = md5(uniqid(rand(), TRUE));
	$_SESSION['token'] = $token;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Modor &middot; Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/datepicker3.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">
    <link href="less/datepicker.less" rel="stylesheet">

    <script src="js/jquery-1.9.1.js"></script>

    <script>
    $(function() {
      $( "#from" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function( selectedDate ) {
          $( "#to" ).datepicker( "option", "minDate", selectedDate );
        }
      });
      $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
        }
      });
    });
    </script>

    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 70%;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="/ico/favicon.png">
  </head>

  <body>

    <div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li><a href="index.php">Home</a></li>
		  <li class="active"><a href="index.php">Profile</a></li>
          <li><a href="logout.php">Logout</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <h3 class="muted">Modor Profile</h3>
		<!-- <img src="img/" style="width:10%"/> -->
      </div>

      <hr>
      <div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
		  <ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab">Personal Profile</a></li>
			<li><a href="#tab2" data-toggle="tab">People You May Know <span class="badge badge-success"><?php echo(sizeof($cardsInfo));?></span></a></li>			
		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab1">
			    <?php 
					if(!isset($dataArr[0]) || sizeof($dataArr[0]) == 0){
						?>
							<div class="alert">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <strong>Sorry!</strong>Sorry, could not load personal information
							</div>
						<?php
					}
					else{
				?>
				<h4> (<?php echo($dataArr[0]['name']." - ".$dataArr[0]['phone']) ?>)</h4>
				  <table class="table table-bordered table-striped">
					<thead>
					  <tr>
						<th>Attribute</th>
						<th>Detail</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
						<td>
						  Name
						</td>
						<td>
						  <?php echo($dataArr[0]['name']) ?>
						</td>
					  </tr>
					  <td>
					  	  Gender
						</td>
						<td>
						   <?php if(($dataArr[0]['gender']) == 'female'){ ?>
							<span class="badge badge-success">Female</span>
						   <?php }else{ ?>
							<span class="badge badge-important">Male</span>
						   <?php } ?>
						</td>
						<td>
					  <tr>
						<td>
						  Company
						</td>
						<td>
						  <?php echo($dataArr[0]['company']); ?>
						</td>
					  </tr>
					  <tr>
						<td>
						  Email
						</td>
						<td>
						  <?php echo($dataArr[0]['email']); ?>
						</td>
					  </tr>
					  <tr>
						<td>
						  Address
						</td>
						<td>
						  <?php echo($dataArr[0]['address']) ?>
						</td>
					  </tr>
					  <tr>
						<td>
						  Friends
						</td>
						<td>

						  <?php 
						  		$friends = $dataArr[0]['friends'];
						  		foreach ($friends as $pal) {
						  			echo($pal['name'].', ');
						  		}
						  ?>
						  		
						  
						</td>
					  </tr>
					  
					</tbody>
				  </table>
				  <?php } ?>
			</div>
			<div class="tab-pane" id="tab2">
				
			    <?php 
					if(!isset($cardsInfo) || sizeof($cardsInfo) == 0){
						?>
							<div class="alert">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <strong>Sorry!</strong> Coming Soon.
							</div>
						<?php
					}
					else{
						# loop and print all cards
						foreach($cardsInfo as $card){
				?>
				<h4><?php echo($cardTypes["".($card['type'])]." (".$card['card-no'].")"); ?></h4>
				
				<?php 
					}
				}
				?>
			</div>
			
			
		  </div>
		  
	</div>

      <hr>      

      <div class="footer">
        <p>&copy; Modor Mauritian <?php echo date("Y") ?></p>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/transition.js"></script>
    <script src="js/alert.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/dropdown.js"></script>
    <script src="js/scrollspy.js"></script>
    <script src="js/tab.js"></script>
    <script src="js/tooltip.js"></script>
    <script src="js/popover.js"></script>
    <script src="js/button.js"></script>
    <script src="js/collapse.js"></script>
    <script src="js/carousel.js"></script>
    <script src="js/typeahead.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>


  </body>
</html>
