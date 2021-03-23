<?php
    //Needs to run Old School Query
    set_include_path(implode(PATH_SEPARATOR, array(
        realpath('../php/'),
        get_include_path(),
    )));
    
    // $clientLibraryPath = '/php/zend/';
    // $oldPath = set_include_path(get_include_path() . PATH_SEPARATOR . $clientLibraryPath);
        
    require('lib/config.php');
    require('lib/common.php');
    require('lib/front.php');

    include ("mod/".Front::getMod().".php");
?>
<!doctype html>  
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo Front::getTitle() ?> </title>
  <meta name="author" content="Gridr">
	<meta name="description" content=""/>
	<meta name="keywords" content=""/>
  <meta name="viewport" content="width=980">
  <link rel="stylesheet" href="<?php echo Config::loc ?>/site/css/style.css">
  <script src="<?php echo Config::loc ?>/site/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body>  		
		<!-- Main Header -->
    <?  Front::getHeader(); ?>	 			 
		<!-- End Main Header -->
    <? include ("view/".Front::getView().".inc"); ?>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="<?php echo Config::loc ?>/site/js/libs/jquery-1.7.2.min.js"><\/script>')</script>
	<script type="text/javascript" src="<?php echo Config::loc ?>/site/js/libs/jquery-ui-1.8.17.min.js"></script>
	<script type="text/javascript" src="<?php echo Config::loc ?>/site/js/scripts.js"></script>

   <script>
   var _gaq = [['_setAccount', ''], ['_trackPageview']];
   (function(d, t) {
    var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    g.async = true;
    g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g, s);
   })(document, 'script');
  </script>
</body>
</html>