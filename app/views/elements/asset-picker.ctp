<?php

$filter_rgx = isset($filter) ? $filter : '//';
$selected_id = isset($selected) ? $selected : '-1';
$asset_picker_idx = Configure::read('AssetPickerID') + 1;
Configure::write('AssetPickerID', $asset_picker_idx);
$allow_deselect = isset($deselect) && $deselect == true ? true : false;


$js_picker_selector = '#AssetPicker' . $asset_picker_idx . ' ul.assetList';
$target_selector = (isset($target) ? $target : '');

//pr($assets);

$prev_base = $this->Html->url(array('controller'=>'assets','action'=>'fetch'));

?>

<div class="assetPicker"  id="AssetPicker<?php echo $asset_picker_idx; ?>" style="border: 1px solid #000; padding: 5px;">
	<h3><?php echo isset($title) ? __($title, true) : 'Asset Picker'; ?></h3>
	<?php if ($allow_deselect) { ?>
	  <a href="#" class="deselect">Deselect</a>
	<?php } ?>
	<span class="selectedAsset"></span>
	<ul class="assetList" >
	</ul><!-- /.assetList -->
	
	<script type="text/javascript">
	<!--
	  var prev_base = '<?php echo $prev_base; ?>';
	
		buildAssetPicker(
										 '<?php echo $js_picker_selector; ?>',
										 <?php echo json_encode($assets); ?>,
										 <?php echo $selected_id; ?>,
										 '<?php echo $target_selector; ?>',
										 '<?php echo $this->Html->url(array('controller'=>'assets','action'=>'view')); ?>'
										);
	// -->
	</script>
	
</div><!-- /.assetPicker -->

