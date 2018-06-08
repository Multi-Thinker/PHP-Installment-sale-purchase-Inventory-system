<!DOCTYPE html>
<html lang="en">
    <!--
        **********************************************************************************************************
        Copyright (c) 2017 
        ********************************************************************************************************** 
        -->
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--[if IE]>
        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
        <![endif]-->
        <meta name="keywords" content="""" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>EdPerks</title>
        <!-- Bootstrap -->
        <!-- Favicon -->
        <link rel="shortcut icon" href="http://preview.yrthemes.com/thanks-giving/assets/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="http://preview.yrthemes.com/thanks-giving/assets/img/favicon.ico" type="image/x-icon">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/color.css" rel="stylesheet" id="colors">
        <link href="assets/css/responsive.css" rel="stylesheet">
		<link href="assets/css/logsin.css" rel="stylesheet" >
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    <body>
		<!-- //***preloader Start***// -->
<?php include "preload.php"; ?>
		<!-- //***preloader End***// -->
        <!-- //***Header-section Start***// -->
        <!-- start here !-->
        <main class="bootstrap">
<form class="login" action="login.php">
  
  <fieldset class="form-container">
    
    <legend class="legend">Login</legend>
    
    <div class="input">
        <input type="email" placeholder="Email" required />
      <span><i class="fa fa-envelope-o"></i></span>
    </div>
    
    <div class="input">
        <input type="password" placeholder="Password" required />
      <span><i class="fa fa-lock"></i></span>

       
    </div>
    <div class="input">
        <a href="signup.php">Don't have account? Signup now</a>
       <a href="javascript:void(0);" class="pull-right">Forgot Password?</a>
    </div>
    <button type="submit" class="submit-btn"><i class="fa fa-long-arrow-right"></i></button>
   
  </fieldset>
  
  <div class="feedback">
    login successful <br />
    redirecting...
  </div>
  
</form>
        </main>


        <div class="copyright-section">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <p><i class="fa fa-copyright" aria-hidden="true"></i> 2017 <a href="index.php"><span class="itg-color"> EdPerks</span></a></p>
            </div>
        </div>
        <!-- //***Footer-Section End***// -->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="assets/js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/plugins/owl-carousel/js/owl.carousel.min.js"></script>
        <script src="assets/plugins/megamenu/js/hover-dropdown-menu.js"></script>
        <script src="assets/plugins/megamenu/js/jquery.hover-dropdown-menu-addon.js"></script>
        <script src="assets/plugins/preloader/js/anime.min.js"></script>
        <script src="assets/js/main.js"></script>
        <script>
            $( ".input" ).focusin(function() {
          $( this ).find( "span" ).animate({"opacity":"0"}, 200);
        });

        $( ".input" ).focusout(function() {
          $( this ).find( "span" ).animate({"opacity":"1"}, 300);
        });

        $(".login").submit(function(){
          $(this).find(".submit i").removeAttr('class').addClass("fa fa-check").css({"color":"#fff"});
          $(".submit").css({"background":"#2ecc71", "border-color":"#2ecc71"});
          $(".feedback").show().animate({"opacity":"1", "bottom":"-80px"}, 400);
          $("input").css({"border-color":"#2ecc71"});
          return false;
        });
        </script>
    </body>
</html>