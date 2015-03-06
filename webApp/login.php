<?php

    /*
        Login to the Modor Search Site
        @Author : Kevin Oyugi
    */

    # start the session
    ob_start();
    error_reporting(E_ALL);
    
    # read any flash message before unset
    session_start();
    
    # imports
    require_once("WebAppService.php");
    require_once 'Klogger.php';

    $flash = isset($_SESSION['flash-message']) ? $_SESSION['flash-message'] : null;
    $token = isset($_SESSION['token']) ? $_SESSION['token'] : null;
    $nextLocation = isset($_SESSION['referrer']) ? $_SESSION['referrer'] : null;

    # if we have post data its a login attempt
    if(isset($_POST['token']) && $_POST['token'] == $_SESSION['token']){
        # capture input
        # input parameters from the user, not to be trusted
        # they will be stripped and validated throughly
        # form username
        $username = htmlspecialchars(stripslashes((trim($_POST['username']))));
        # form password
        $password = htmlspecialchars(stripslashes((trim($_POST['password']))));
        # valid?
        if(!isset($username) || !isset($password) || strlen($password) == 0){
            $flash = array("type" => "notice", "msg" => "Invalid E-Mail or Password");
        }
        # authenticate
        else{
            # init DB
            $log = new KLogger ( "/tmp/app.log" , KLogger::DEBUG );  
            $pgConfig =  'host=localhost port=5432 user=postgres password=password dbname=db';                             
            $log->LogInfo("initDB($pgConfig)");
            AbstractDBService::init($pgConfig); 
            ## query db for each of the info
            # instantiate the awesome WebAppService
            $service  = new WebAppService();
            # TODO have a check for service
            # get User :: takes an input param of username from the form
            $user = $service->getUser($username);
            # if 0 records have been returned  
            if (isset($user) && intval($user) == 0) {
                $flash = array("type" => "notice", "msg" => "Invalid Username or Password");
            }
            # else record has been returned do login
            else {
                # set values from db
                $userID   = $user['user-id'];   # userid from db
                $userName = $user['username'];  # username from db
                $passWord = $user['password'];  # password from db
                $salt     = $user['salt'];      # salt from db
                $activeflags = $user['active-flags']; # active flags status from db
                $userFK = $user['usertype-fk'];
                $pintries = $user['password-tries'];
                $aclFlags = $user['acl-flags']; # acl tries
                # if the card is active do check if passwords match
                if (isset($activeflags)  && $activeflags == 1) {
                    # check for pin tries
                    if (isset($pintries) && $pintries == 4) {
                        $flash = array("type" => "notice", "msg" => "Account locked. Too many incorrect password attempts. Please Contact Modor Support");
                    }
                    # if pin tries is less than 4, proceed
                    else{
                        # hash the user password
                        $hashedPassword = strtoupper(hash('sha512', $password.$salt));
                        # if form password == db password? continue
                        if (isset($passWord) && $passWord == $hashedPassword) {
                            # set session variables
                            $_SESSION['authData'] = $token;
                            $_SESSION['userFK'] = $userFK;
                            $_SESSION['user-id'] = $userID;
                            $_SESSION['username'] = $userName;
                            # on successful login, do AclFlags check
                            if (isset($aclFlags) && $aclFlags == 1) {
                                # redirect to change password
                                header("Location: change.php");
                                $_SESSION['flash-message'] = array("type" => "notice", "msg" => "Please change your password!");
                            }
                            else{
                                #  redirect to login page
                                $_SESSION['flash-message'] = array("type" => "notice", "msg" => "Welcome Back");  
                                
                                # now here we check, if the next page to go to is null, we send them to the login page
                                $next = $nextLocation == null ? "index.php" : $nextLocation;
                                header("Location: $next");
                            }
                        }
                        # password mismatch, show message to the user
                        # implement password tries login
                        else{
                            # do password tries increment
                            $passwordTry = $service->incrementPasswordTries($username);
                            if (isset($passwordTry) && $passwordTry == true) {
                                $flash = array("type" => "notice", "msg" => "Invalid Password. Attempt ".print_r(intval($passwordTry),true)."/4");
                            }
                            else{
                                $flash = array("type" => "notice", "msg" => "Oops, service error. Please try again");
                            }
                        }
                    }
                }
                # show if active flags == 0, meaning account has been deactivated
                else{
                    $flash = array("type" => "notice", "msg" => "Your account has been deactivated, please contact Modor Support");
                }
            }
        }
    }
    
    # generate the token
    $token = md5(uniqid(rand(), TRUE));
    $_SESSION['token'] = $token;
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Modor &middot; Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
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

    <div class="container">
      <?php
        # flash messages
        if(isset($flash) && is_array($flash)){
            ?>
            <div class="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Notice</strong> <?php echo($flash['msg']); ?>
            </div>
            <?php
        }
      ?>
      <form class="form-signin" action="login.php" method="POST">
        <h2 class="form-signin-heading">Please Sign In</h2>
        <table><tr><td>
            <img src="img/" style="width:30%"/>
            </td><td>
            <img src="img/" style="width:80%"/>
        </td></tr></table>
        <p>&nbsp;</p>
        <input type="text" name="username" class="input-block-level" placeholder="Username">
        <input type="password" name="password" class="input-block-level" placeholder="Password">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <input type="hidden" name="token" value="<?php echo($_SESSION['token']) ?>"/>
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
        <hr>
        <p>&copy; Modor Mauritian <?php echo date("Y") ?></p>
      </form>

     
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
