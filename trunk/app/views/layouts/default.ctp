<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('RevCMS'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('bo.style');
		echo $this->Html->css('flick/jquery-ui-1.8.17.custom');

		echo $scripts_for_layout;
		
		echo $this->Html->script('jquery-1.7.1.min');
		echo $this->Html->script('jquery-ui-1.8.17.custom.min');
		echo $this->Html->script('tinymce/jscripts/tiny_mce/jquery.tinymce');
		echo $this->Html->script('asset-picker');
		echo $this->Html->script('bo');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<ul class="topNav">
				<li><h1>RevCMS</h1></li>
				<li><?php echo $this->Html->link(__('Content', true), array('controller'=>'pages','action'=>'index')); ?></li>
				<li><?php echo $this->Html->link(__('Assets', true), array('controller'=>'assetdirs','action'=>'view',0)); ?></li>
				<li><?php echo $this->Html->link(__('Blogs', true), array('controller'=>'blogs','action'=>'index')); ?></li>
				<li><?php echo $this->Html->link(__('Events', true), array('controller'=>'events','action'=>'view',0)); ?></li>
				<li><?php echo $this->Html->link(__('Forms', true), array('controller'=>'submissions','action'=>'index')); ?></li>
				<li><?php echo $this->Html->link(__('Snips', true), array('controller'=>'settings','action'=>'index')); ?></li>
				<li><?php echo $this->Html->link(__('Users', true), array('controller'=>'users','action'=>'index')); ?></li>
			</ul><!-- /.topNav -->
			
			<?php
			
			if (isset($logged_user)) {
			
			?><div style="float: right;">
				Logged in as <em><?php echo $this->Html->link(__($logged_user['User']['username'], true), array('controller'=>'users','action'=>'edit',$logged_user['User']['id'])); ?></em>
				[ <?php echo $this->Html->link(__('Logout', true), array('controller'=>'users','action'=>'logout')); ?> ]
			</div><?php
			
			}
			
			?>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>


      <?php //pr($this); ?>

      <?php
			
			if ($this->params['action'] != 'add' && $this->params['action'] != 'edit') {
				
			}
			
			echo $content_for_layout;
			
			?>

		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt'=> __('CakePHP: the rapid development php framework', true), 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
	<?php
	
	/*echo $this->element('sql_dump'); /* */
	
	?>
<script type="text/javascript" defer="defer">
  $(function() {
		var using_TinyMCE = false;
		var $code_editors = $('textarea.useTinyMCE');
		var $tinymce_toggler = $('#doTinyMCE');
		
		var tinymce_on = function() {
			$code_editors.tinymce({
				script_url : '/js/tinymce/jscripts/tiny_mce/tiny_mce.js',
				external_image_list_url : "/assets/mce_cache.js",
				theme : "advanced",
				content_css : "css/style.css",
				height: '400px',
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left"
			});
			$tinymce_toggler.html('Disable TinyMCE Editor');
			using_TinyMCE = true;
		};
		
		var tinymce_off = function() {
			$code_editors.tinymce().remove();
			$tinymce_toggler.html('TinyMCE Editor');
			using_TinyMCE = false;
		};
		
		
		
		$tinymce_toggler.click(function(e) {
			if (using_TinyMCE) {
				tinymce_off();
			} else {
				tinymce_on();
			}
			e.preventDefault();
			return false;
		});
		
<?php

if ($UserModel->getPref($logged_user, 'use_tiny_mce')) {
	echo "    tinymce_on();\n";
}

?>
		
	});		
</script>
</body>
</html>