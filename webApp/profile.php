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
		
	# instantiate the awesome webAppService
	$service  = new WebAppService();

	# grab the phone no. from URL
	$modTerm = $phone;

	// graphAPI credentials
	$username = "87bf83fc";
	$password = "f4a1112a";

	# relations
	$peopleYouMayKnow = array();

	// call the graphAPI
	# search by phone number
	$findByPhone = $service->search('http://127.0.0.1:3000/api/people/phone/'.$modTerm.'?username='.$username.'&password='.$password);
	# assign findbyphone to var
	$data = $findByPhone;
	# decode the json to array
	$profile = (json_decode($data, true));
	
	# grab the details of the first person the the
	# search results

	# Profile Name
	$varName = $profile[0]['name'];
	# url encode	
	$name =  rawurlencode($varName);
	# Profile Phone
	$msisdn = $profile[0]['phone'];
	# Profile Company
	$company =$profile[0]['company'];
	// var_dump($company);
	# Profile Tags
	$tags = $profile[0]['tags'];	
	# Profile About
	$about = $profile[0]['about'];
	# Profile Friends
	$friends = $profile[0]['friends'];
	# Profile Address
	$profAddress = $profile[0]['address'];
	# url encode
	$address = rawurlencode($profAddress);

	

	### Search for people you may know
	### This is a deep search 
	### we use the recommended profile details to deep search for likely people they may know

	# search by phone number
	# we look for related people
	$relatedByPhone = $service->search('http://127.0.0.1:3000/api/people/phone/'.$msisdn.'?username='.$username.'&password='.$password);
	# search by name
	$relatedByName = $service->search('http://127.0.0.1:3000/api/people/name/'.$name.'?username='.$username.'&password='.$password);		
	# search by address
	$relatedByAddress = $service->search('http://127.0.0.1:3000/api/people/address/'.$address.'?username='.$username.'&password='.$password);
	#search company
	$relatedByCompany = $service->search('http://127.0.0.1:3000/api/people/company/'.$company.'?username='.$username.'&password='.$password);


	# cascade through the successful api calls
	#### people you may know algorithm
	#1. get the phone number from the url
	#2. run thet get by phone api with the phone number
	#3. display the profile
	#3. put the profile details to the profile variables
	#4. run each profile varible with a matching api end point
	#5. IF any of the end points responds with {"status":"404 Person Doesnt Exist"} = TRUE i.e no records avialable
	#6. MOVE to the next API. Until we find one the responds with {"status":"404 Person Doesnt Exist"} = TRUE i.e {"status":"200 OK"} (records are available to parse).
	#7. Still MOVE to the next case until we exhaust all the cases and the we BREAK/STOP
	#8. Process the result to array of the successful case and LOOP 
	#9. Display results




	switch (false) {
		case ($relatedByPhone == '{"status":"404 Person Doesnt Exist"}'):			
			$data = $relatedByPhone;	
			$dataArr = (json_decode($data, true));	
			$byPhone = $dataArr;
			
			
		case ($relatedByName == '{"status":"404 Person Doesnt Exist"}'):
			$data = $relatedByName;	
			// var_dump($data);
			$dataArr = (json_decode($data, true));	
			$byName = $dataArr;
			
					
		case ($relatedByAddress == '{"status":"404 Person Doesnt Exist"}'):
			$data = $relatedByAddress;	
			$dataArr = (json_decode($data, true));	
			$byAddresss = $dataArr;
						

		case ($relatedByCompany == '{"status":"404 Person Doesnt Exist"}'):
			$byCompany = array();
			$data = $relatedByCompany;				
			$dataArr = (json_decode($data, true));	
			for ($i=0; $i<count($dataArr); $i++){
			$record = $dataArr[$i];		
			array_push($byCompany, $record);	

			}			
			break;
		default:
			# code...
			break;
	}
	
	
	

	# search by company
	foreach ($friends as $pal) {	
		# url encode the name
		$friendsName = rawurlencode($pal['name']);
		# get the friends
		$relatedByFriends = $service->search('http://127.0.0.1:3000/api/people/pals/'.$friendsName.'?username='.$username.'&password='.$password);
		# convert only successfulls searches		
		if($relatedByPhone !== '{"status":"404 Person Doesnt Exist"}'){
			$data = $relatedByFriends;
			$dataArr = (json_decode($data, true));			
			$byPals = $dataArr;					
		}
		else{
			break;
		}
		
	}
	
	// # search by tags
	// foreach ($tags as $key) {
	// 	# url encode tags
	// 	$tag = rawurlencode($key['name']);
	// 	# run the api call to related people with same tags
	// 	$relatedByTags = $service->search('http://127.0.0.1:3000/api/people/tags/'.$tag.'?username='.$username.'&password='.$password);
	// 	# convert only successfulls searches		
	// 	if($relatedByTags !== '{"status":"404 Person Doesnt Exist"}'){
	// 		$data = $relatedByFriends;
	// 		$dataArr = (json_decode($data, true));			
	// 		$byTags = $dataArr;
	// 	}
	// 	else{
	// 		break;
	// 	}

	// 	# code...
	// }

	# we then push the arrays into a Multi Dimensional Array and Loop to get results
	// array_push($peopleYouMayKnow, $byPals, $byPhone, $byCompany, $byName, $byAddresss);

	$results = $byPals + $byPhone + $byCompany + $byName + $byAddresss;


	
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
			<li><a href="#tab2" data-toggle="tab">People You May Know <span class="badge badge-success"><?php echo(sizeof($results));?></span></a></li>			
		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab1">
			    <?php 
					if(!isset($profile[0]) || sizeof($profile[0]) == 0){
						?>
							<div class="alert">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <strong>Sorry!</strong>Sorry, could not load personal information
							</div>
						<?php
					}
					else{
				?>
				<h4> (<?php echo($profile[0]['name']." - ".$profile[0]['phone']) ?>)</h4>
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
						  <?php echo($profile[0]['name']) ?>
						</td>
					  </tr>
					  <td>
					  	  Gender
						</td>
						<td>
						   <?php if(($profile[0]['gender']) == 'female'){ ?>
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
						  <?php echo($profile[0]['company']); ?>
						</td>
					  </tr>
					  <tr>
						<td>
						  Email
						</td>
						<td>
						  <?php echo($profile[0]['email']); ?>
						</td>
					  </tr>
					  <tr>
						<td>
						  Address
						</td>
						<td>
						  <?php echo($profile[0]['address']) ?>
						</td>
					  </tr>
					  <tr>
						<td>
						  Friends
						</td>
						<td>

						  <?php 
						  		$friends = $profile[0]['friends'];
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
					if(!isset($results) || sizeof($results) == 0){
						?>
							<div class="alert">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  <strong>Sorry!</strong> Opps this person doesnt seeem to have any people they may know.
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
					foreach($results as $result){
				?>
			  <tr>
				<td>
				  <?php echo($result['name']); ?>
				</td>
				<td>
				  <?php echo($result['phone']); ?>
				</td>
				<td>
				  <?php echo($result['company']); ?>
				</td>
				<td>
				  <?php echo($result['email']); ?>
				</td>
				<td>
				  <?php echo($result['address']); ?>
				</td>				
				<td>
					<a href="profile.php?phone=<?php
												echo($result['phone']); 
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
				
				?>
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
