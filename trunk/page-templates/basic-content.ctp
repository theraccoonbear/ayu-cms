<?php


header('Content-type: text/html; charset=UTF-8') ;

?><!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

	<head<?php if (isset($head_attributes)) { echo ' ' . $head_attributes; }?>>
		<?php echo $html->charset('utf-8'); ?>
		<title>
			  <?php
				
				if (isset($special_title)) {
					echo $special_title . ' &#8212; ';
				} elseif (isset($page['Page'])) {
					echo $page['Page']['title'] . ' &#8212; ';
				} 
				?> Revolution Cycles, Madison, WI
		</title>
		
		<meta charset="utf-8">
		<meta name="description" content="">
	
		<!-- Mobile viewport optimized: h5bp.com/viewport -->
		<meta name="viewport" content="width=device-width">
	
		<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
		<?php
		
		echo $this->Html->meta('icon');
		echo $this->Html->css('reset');
		echo $this->Html->css('style');
		echo $this->Html->css('gorun');
		echo $this->Html->css('kalendae');
		echo $this->Html->script('jquery-1.7.1.min');
		echo $this->Html->script('base');
		echo $this->Html->script('date.format');
		echo $this->Html->script('mustache');
		
		?>
		
		<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->
	
		<!-- All JavaScript at the bottom, except this Modernizr build.
				 Modernizr enables HTML5 elements & feature detects for optimal performance.
				 Create your own custom Modernizr build: www.modernizr.com/download/ -->
		<script src="js/libs/modernizr-2.5.3.min.js"></script>
		
		<link href="/feeds" type="application/atom+xml" rel="alternate" title="Sitewide ATOM Feed" />
		<?php
		if (isset($meta_description)) {
			?><meta name="description" content="<?php echo CMSHelper::textTrunc($meta_description, 150); ?>"><?php
		}
		
		

		//echo $scripts_for_layout;
	?>
		<?php
		
		if (isset($append_to_head)) {
			echo $append_to_head;
		}
	
	?>
	</head>
	<body>
		<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
			chromium.org/developers/how-tos/chrome-frame-getting-started -->
		<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	
		<header>
			<h1>&&&			
			<?php
				if (isset($page['Page'])) {
					echo $page['Page']['title'];
				} else {
				  echo 'Running Stuff';
				}
			?>
			</h1>
		</header>
	
	
	  <div role="main" id="main">
		
			<div id="nav">
				<?php
				
				if (isset($nav_items)) {
					//echo $this->element('navigation', array('options'=>array('items'=>$nav_items)));
				}
				?>
			</div><!-- /#nav-->
		
		
		  <div id="contentWrapper">
			<?php
			
				if (isset($debug_text)) { pr($debug_text); }
				
				echo $content_for_layout;
						
		  ?>
			</div><!-- /#contentWrapper -->
			
			<?php  
				/*
				echo '<div style="background-color: #fff; width: 100%; padding: 10px;">';
				
				echo $this->element('sql_dump');
				
				echo '</div>';
				/* */
			?>
			
			<footer>
				<div class="printable">
					<span class="print">Get your own training program at http://www.theraccoonshare.com/gorun</span>
				</div><!-- /.printable -->
			</footer>
		</div><!-- /#main -->
		
	<!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

  <!-- scripts concatenated and minified via build script -->
  <script src="js/plugins.js"></script>
	<script src="js/libs/kalendae.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/libs/dateFormat.js" type="text/javascript"></script>
	<script src="js/libs/base64.js" type="text/javascript"></script>
  <!-- end scripts -->

	
	<?php
	
	$ga_account = $SettingModel->getSetting('ga-account');
	
	if ($ga_account !== false) {
	
	?>
  <!-- Asynchronous Google Analytics snippet. -->
  <script type="text/javascript">
	
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<?php echo $ga_account; ?>']);
		_gaq.push(['_trackPageview']);
	
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	
	</script>
	
	<?php
	
	} else { // ga account?
			echo "<!-- No Google Analytics Setup.  Create a \"string\" setting named \"ga-account\" to activate tracking code -->\n";
	}
	
	?>
	</body>
</html>