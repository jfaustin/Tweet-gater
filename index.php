<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><!-- InstanceBegin template="/Templates/php.fixed.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!--
    Membership and regular participation in the UNL Web Developer Network
    is required to use the UNL templates. Visit the WDN site at 
    http://wdn.unl.edu/. Click the WDN Registry link to log in and
    register your unl.edu site.
    All UNL template code is the property of the UNL Web Developer Network.
    The code seen in a source code view is not, and may not be used as, a 
    template. You may not use this code, a reverse-engineered version of 
    this code, or its associated visual presentation in whole or in part to
    create a derivative work.
    This message may not be removed from any pages based on the UNL site template.
    
    $Id: php.fixed.dwt.php 1390 2010-11-18 15:24:33Z bbieber2 $
-->
<link rel="stylesheet" type="text/css" media="screen" href="/wdn/templates_3.0/css/all.css" />
<link rel="stylesheet" type="text/css" media="print" href="/wdn/templates_3.0/css/print.css" />
<script type="text/javascript" src="/wdn/templates_3.0/scripts/all.js"></script>
<?php virtual('/wdn/templates_3.0/includes/browserspecifics.html'); ?>
<?php virtual('/wdn/templates_3.0/includes/metanfavico.html'); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>UNL | on Twitter</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->


<meta property="og:title" content="UNL | on Twitter" />
<meta property="og:type" content="university" />
<meta property="og:url" content="http://ucommwiedel.unl.edu/Tweet-gater" />
<meta property="og:image" content="http://ucommwiedel.unl.edu/Tweet-gater/images/icon.jpg"/>
<meta property="og:site_name" content="University of Nebraska-Lincoln" />
<meta property="fb:admins" content="511000653" />
<meta property="og:description"
          content="With everything going on here at UNL, why not have an
          easier way of knowing what's happening? Here's a collection of
          official UNL Twitter feeds to help keep you in the know." />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

<link rel="stylesheet" type="text/css" media="screen" href="sharedcode/twitter.css" />
<!--[if lte IE 8]>
        <link rel="stylesheet" type="text/css" media="screen" href="sharedcode/ie8-and-down.css" />
<![endif]-->


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php 
require_once 'inc/Tweetgater/Display.php';
$page = 1;

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
}
?>





<!-- InstanceEndEditable -->
</head>
<body class="fixed">
<p class="skipnav"> <a class="skipnav" href="#maincontent">Skip Navigation</a> </p>
<div id="wdn_wrapper">
    <div id="header"> <a href="http://www.unl.edu/" title="UNL website"><img src="/wdn/templates_3.0/images/logo.png" alt="UNL graphic identifier" id="logo" /></a>
        <h1>University of Nebraska&ndash;Lincoln</h1>
        <?php virtual('/wdn/templates_3.0/includes/wdnTools.html'); ?>
    </div>
    <div id="wdn_navigation_bar">
        <div id="breadcrumbs">
            <!-- WDN: see glossary item 'breadcrumbs' -->
            <!-- InstanceBeginEditable name="breadcrumbs" -->
            <div id="fb-root"></div>
			<script>(function(d, s, id) {
  				var js, fjs = d.getElementsByTagName(s)[0];
  				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>

            <ul>
                <li><a href="http://www.unl.edu/" title="University of Nebraska&ndash;Lincoln">UNL</a></li>
                <li>on Twitter</li>
            </ul>
            
            <!-- InstanceEndEditable --></div>
        <div id="wdn_navigation_wrapper">
            <div id="navigation"><!-- InstanceBeginEditable name="navlinks" -->
                <?php include 'sharedcode/navigation.html'; ?>
                <!-- InstanceEndEditable --></div>
        </div>
    </div>
    <div id="wdn_content_wrapper">
        <div id="titlegraphic"><!-- InstanceBeginEditable name="titlegraphic" -->
            <h1>UNL on Twitter</h1>
            
            <!-- InstanceEndEditable --></div>
        <div id="pagetitle"><!-- InstanceBeginEditable name="pagetitle" --> <!-- InstanceEndEditable --></div>
        <div id="maincontent">
            <!--THIS IS THE MAIN CONTENT AREA; WDN: see glossary item 'main content area' -->
            <!-- InstanceBeginEditable name="maincontentarea" -->
           
		<div class="boxheader">
                 	<div class="blurb">
                    	<div class="cloudHeader">
            				<h3>Stay Connected</h3>
                        </div>
                <p>With everything going on here at UNL, why not have an easier way of knowing what's happening? Here's a collection of official UNL Twitter feeds to help keep you in the know.</p>
                </div>
                
                <div class="theTab">
                
                <ul class="socialbuttons">
                    <li class="twitter">
                    <a class="tooltip" href="https://twitter.com/#!/unlnews" title="get more news @UNLNews">Twitter</a>
                    </li>
                    <li class="facebook"><a class="tooltip" href="https://www.facebook.com/unl.edu?sk=wall" title="like UNL on Facebook">Facebook</a></li>
                    <li class="youtube"><a class="tooltip" href="http://www.youtube.com/unl" title="watch our videos on YouTube">YouTube</a></li>
                   </ul>
                <h3 class="followUs">Follow us</h3>
				</div>
			
            <div class="share_bar">
            <p style="float:left;">Share</p>
			<ul class="like_bar">
         	
            <li>
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://ucommwiedel.unl.edu/Tweet-gater" data-text="Check out the UNL twitter site!" data-via="UNLFeed">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>

			<li>
            <div class="fb-like" data-href="http://ucommwiedel.unl.edu/Tweet-gater" data-send="false" data-layout="button_count" data-width="110" data-show-faces="true" data-font="arial"></div></li>
			
            <li>
			<div class="g-plusone" data-size="medium"></div>

    <script type="text/javascript">
      window.___gcfg = {
        lang: 'en-US'
      };

      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script></li>
				
            </ul>
            </div>
         
  
         </div>

           
           <div class="right">
           		<?php include 'right.html'; ?>
                
                
           </div>
           
           <!-- - - - - - Tab Content - - - - - - -->
          
         <div class="tweetBox">
           <ul class="wdn_tabs">
   				<li><a href="#timeline">Tweets</a></li>
   				<li class="last"><a href="#search">#UNL</a></li>
                <li style="visibility:hidden;"><a href="#accounts"></a></li>
			</ul>
            <div class="wdn_tabs_content">
                <div id="timeline">
    				<?php echo Tweetgater_Display::timeline($page); ?>
                </div>
                <div id="search">
                    <?php echo Tweetgater_Display::search('#unl', $page); ?>
                </div> 
                <div id="accounts">
                    <?php echo Tweetgater_Display::accounts(); ?>
                </div>             
            </div>
         </div>

           
            <!-- InstanceEndEditable -->
            <div class="clear"></div>
            <?php virtual('/wdn/templates_3.0/includes/noscript.html'); ?>
            <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
        </div>
        <div id="footer">
            <div id="footer_floater"></div>
            <div class="footer_col">
                <?php virtual('/wdn/templates_3.0/includes/feedback.html'); ?>
            </div>
            <div class="footer_col"><!-- InstanceBeginEditable name="leftcollinks" -->
                <?php include 'sharedcode/relatedLinks.html'; ?>
                <!-- InstanceEndEditable --></div>
            <div class="footer_col"><!-- InstanceBeginEditable name="contactinfo" -->
                <?php include 'sharedcode/footerContactInfo.html'; ?>
                <!-- InstanceEndEditable --></div>
            <div class="footer_col">
                <?php virtual('/wdn/templates_3.0/includes/socialmediashare.html'); ?>
            </div>
            <!-- InstanceBeginEditable name="optionalfooter" --> <!-- InstanceEndEditable -->
            <div id="wdn_copyright"><!-- InstanceBeginEditable name="footercontent" -->
                <?php include 'sharedcode/footer.html'; ?>
                <!-- InstanceEndEditable -->
                <?php virtual('/wdn/templates_3.0/includes/wdn.html'); ?>
                | <a href="http://validator.unl.edu/check/referer">W3C</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS</a> <a href="http://www.unl.edu/" title="UNL Home" id="wdn_unl_wordmark"><img src="/wdn/templates_3.0/css/footer/images/wordmark.png" alt="UNL's wordmark" /></a> </div>
        </div>
    </div>
    <div id="wdn_wrapper_footer"> </div>
</div>
</body>
<!-- InstanceEnd --></html>
