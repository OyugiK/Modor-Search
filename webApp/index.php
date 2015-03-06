<?php
	/*
		This is the Web Implementation based on the Graph API
		Here we give users the option to search for people based on a certain criteria
		Because we are not querying directly from the database, we call the API to provide the data we require
		This page will only display search results



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

		# now we must bring back the user to this page after they login successfully,
		# to do this, we store this location in the session.
		$_SESSION['crm_referrer'] = $_SERVER['REQUEST_URI'];
		header("Location: login.php");
	}


	# clear session sure token
	$_SESSION['secure-token'] = null;

	# if so continue
	$term = (isset($_GET['term'])) ? stripslashes(strip_tags($_GET['term'])) : null;

	$modTerm = rawurlencode($term);	

	#instantiate the class
	$service = new WebAppService();

	//$data = $service->search('http://127.0.0.1:3000/api/people/name/Chloe%20Hamphrey');

	// example  of data

	// $data = '[{"_id":"54f957469c86ce3f985b222c","picture":"http://placehold.it/32x32",
	// "company":"Celgra","phone":"889-590-3598","address":"27735, Columbia, Stanton Streets",
	// "date":"2009-11-12T11:14:00.000Z","guid":"dc8d1cf3-e12f-4eb4-8c33-9fd354ea931f",
	// "friends":[{"id":1,"name":"Alexandra Youmans"},
	// {"id":2,"name":"Jasmine Miln"},{"id":3,"name":"Kylie Timmons"}],"id":5000,
	// "name":"Chloe Hamphrey","gender":"female",
	//   "age":22,"registered":"2009-04-18T02:17:02 -02:00","email":"chloe@celgra.com"}]';	


	# search by phone number
	$findByPhone = $service->search('http://127.0.0.1:3000/api/people/phone/'.$modTerm);
	# search by name
	$findByName = $service->search('http://127.0.0.1:3000/api/people/name/'.$modTerm);
	# search by address
	$findByAddress = $service->search('http://127.0.0.1:3000/api/people/address/'.$modTerm);
	# search company
	$findByCompany = $service->search('http://127.0.0.1:3000/api/people/company/'.$modTerm);
	# search by company
	$findByFriends = $service->search('http://127.0.0.1:3000/api/people/pals/'.$modTerm);


	switch (false) {
		case ($findByPhone == '{"status":"404 Person Doesnt Exist"}'):
			$data = $findByPhone;			
			break;
		case ($findByName == '{"status":"404 Person Doesnt Exist"}'):
			$data = $findByName;				
			# code...
			break;
		case ($findByCompany == '{"status":"404 Person Doesnt Exist"}'):
			$data = $findByCompany;
			break;	
		case ($findByAddress == '{"status":"404 Person Doesnt Exist"}'):
			$data = $findByAddress;
			break;		
		case ($findByFriends == '{"status":"404 Person Doesnt Exist"}'):
			$data = $findByFriends;					
			break;		
		default:
			# code...
			break;
	}


	$dataArr = (json_decode($data, true));

	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Modor &middot; Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
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
          <li class="active"><a href="index.php">Home</a></li>          
          <li><a href="createMerchantReport.php">About</a></li>          
          <li><a href="#">Contact</a></li>
        </ul>
        <h3 class="muted">Modor Search</h3>        
      </div>
      <hr>
      <!-- <img src="" style="width:10%"/> -->
	  
	  <?php
			# flash msg show if any
			if(isset($flash))
{		?>
			<div class="alert">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Notice</strong> <?php echo($flash['msg']) ?>
			</div>
		<?php
			}
		?>

      <div class="jumbotron">
        <h2>Search To Start</h2>
        <form class="form-signin" action="index.php">        
			<input type="text" name="term" class="input-block-level" placeholder="Name (John Doe) or Mobile No (268xx) or Company (Craft Silicon) or Address (50606 Kinshasa)">
			<button class="btn btn-large btn-primary" type="submit">Go!</button>
      </form>
      </div>

      
	  <?php
			# term
			if(isset($term)){
	  ?>
	  <hr>
	  <h4>Search Results (<?php echo($term) ?>)</h4>
		<?php
					
			if (!isset($dataArr[0])) {
		?>
		<div class="alert">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Sorry!</strong> This person is not registered for Modor. Ensure Mobile Numbers, Address, Company are accurate.
			</div>
		
		<?php

			}
			# empty resutls
			elseif(!isset($dataArr[0]) || sizeof($dataArr[0]) == 0){
		?>
			<div class="alert">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <strong>Sorry!</strong> Person doesnt exist. Ensure you have entered the correct information. 
			</div>
		<?php
			}
			else{


		?>
		<table class="table table-bordered table-striped">
			<thead>
			  <tr>
				<th>Name</th>
				<th>Phone</th>
				<th>Company</th>
				<th>Email</th>
				<th>Address</th>						
			  </tr>
			</thead>			
			<tbody>
				<?php
					# loop and print
					foreach($dataArr as $result){
				?>
			  <tr>
				<td>
				  <?php echo($dataArr[0]['name']); ?>
				</td>
				<td>
				  <?php echo($dataArr[0]['phone']); ?>
				</td>
				<td>
				  <?php echo($dataArr[0]['company']); ?>
				</td>
				<td>
				  <?php echo($dataArr[0]['email']); ?>
				</td>
				<td>
				  <?php echo($dataArr[0]['address']); ?>
				</td>				
				<td>
					<a href="profile.php?phone=<?php
												echo($dataArr[0]['phone']); 
												?>">View Profile</a>
				</td>						
			  </tr>
			  <?php
				# end of loop
				}
			?>
			</tbody>
		  </table>
	   <?php
				}
			}
		?>
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
  </body>
</html>