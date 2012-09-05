<?php


$page['Page']['copy'] = $this->element('embeds');


  if ($this->layout == 'atom') {
		header('Content-Type: application/atom+xml'); 
		echo $page['Page']['copy'];
	} else {
	?>
	
	<style type="text/css">
		<?php echo $page['Page']['style']; ?>
	</style>
	<?php
	
	echo $page['Page']['copy'];
	
  }

?>