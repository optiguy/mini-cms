<?php require_once 'inc/bootstrap.php'; //Load the entire system ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
		<!-- TODO : Make dynamic header where you can inject and overwrite stuff -->
        <title>Mini CMS</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 75px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <?php require_once('inc/_menu.php'); //Include our menu ?>
    <?php echo show_messages() //Show flash messages - See inc/functions.php ?>
    <div class="container">
		<?php
			$page = (isset($_GET['page'])) ? $_GET['page'] : '' ; //Make sure $page is set to avoid a PHP Notice (Shorthanded if)
			set_page($page); //Request the page - See inc/functions.php
		?>
	</div>
    <?php require_once('inc/_footer.php'); //Include our footer?>
    <?php if(DEBUG){require_once 'inc/debug.php';} ?>
    </body>
</html>