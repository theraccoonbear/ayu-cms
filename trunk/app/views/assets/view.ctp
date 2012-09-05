<!-- Begin Current Asset -->
<div class="content contentFill">
    <div class="row">
        <div class="span5">
            <h3 class="blue padTop10 padBot10 padLeft10">Current Asset</h3>
        </div><!-- end span 5 -->
		<div class="span9 pull-right">
			<div class="actions">
				<h3><?php __('Actions'); ?></h3>
				<ul>
					<li><?php echo $this->Html->link(__('Back to Folder', true), array('controller'=>'assetdirs','action' => 'view',$asset['Asset']['assetdir_id'])); ?> </li>
					<li><?php echo $this->Html->link(__('Edit Asset', true), array('action' => 'edit',$asset['Asset']['id'])); ?> </li>
				</ul>
			</div>
			
			<?php
			
			$parts = preg_split('/\//', $asset['Asset']['type']);
			$major = $parts[0];
			$minor = $parts[1];
			$icon_type = '';
			if ($major == 'video') {
				$icon_type = 'video ';
			} else if ($major == 'image') {
				$icon_type = 'image ';
			} else if ($major == 'audio') {
				$icon_type = 'audio ';
			} else if ($major == 'text') {
				if (preg_match('/html/', $minor)) {
					$icon_type = 'html ';
				} else {
					$icon_type = 'text ';
				}
			}
			
			?>
		</div><!-- end span9 -->
    </div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th><?php __('Name'); ?></th>
					<th><?php __('URL'); ?></th>
					<th><?php __('Type'); ?></th>
					<th><?php __('Size'); ?></th>
					<th style="text-align: center;"><?php __('Meta'); ?></th>
				</tr>
				<tr>
					<td><span class="<?php echo $icon_type; ?>icon"></span>&nbsp;<?php echo $asset['Asset']['name']; ?></td>
					<td><?php echo $this->Html->link($this->Html->url(array('action'=>'fetch', $asset['Asset']['id'])), array('action'=>'fetch',$asset['Asset']['id'])); ?></td>
					<td><a href="http://www.abbreviations.com/X/bs.aspx?st=<?php echo $asset['Asset']['type']; ?>&SE=2" target="_blank"><?php echo $asset['Asset']['type']; ?></a></td>
					<td><?php echo CMSHelper::fmtFilesize($asset['Asset']['size'], 2);?></td>
					<td style="text-align:center;"><?php echo $asset['Asset']['width'] > 0 && $asset['Asset']['height'] > 0 ? $asset['Asset']['width'] . ' x ' . $asset['Asset']['height'] : '&#8212;';?></td>
				</tr>
				<tr>
					<th colspan="5"><?php __('Preview'); ?></th>
				</tr><tr>
					<td colspan="5" style="text-align: center;">
			<?php
						
						$preview_width = min(400, $asset['Asset']['width']);
						
						$asset_url = $this->Html->url(array('controller'=>'assets','action'=>'fetch',$asset['Asset']['id']));
						
						if (preg_match('/^image\//i', $asset['Asset']['type'])) {
							
							$thumb_url = $this->Html->url(array('controller'=>'assets','action'=>'fetch',$asset['Asset']['id'], 'width' => $preview_width));
							
							?>
							<a href="<?php echo $asset_url; ?>" target="_blank"><img src="<?php echo $thumb_url; ?>" alt="<?php echo __($asset['Asset']['name'], true); ?>" id="previewImage" /></a>
							<?php 
						} else if (preg_match('/text/i', $asset['Asset']['type'])) {
							?>
							<iframe src="<?php echo $asset_url; ?>" width="800" height="400"></iframe>
							<?php
						}
						
						?>
					</td>
				</tr>
			</table>
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
<!-- End Current Asset -->


<!-- Start of Pages -->
<div class="content contentFill">
  <div class="row">
		<div class="span5">
			<h3 class="blue padTop10 padBot10 padLeft10">Bound Pages</h3>
    </div><!-- end span 5 -->
  </div><!-- end row -->
  <div class="row padLeft20">
    <div class="span-full margin0">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th><?php __('Page ID'); ?></th>
					<th><?php __('Title'); ?></th>
				</tr>
<?php

if (!empty($asset['Page'])) {
	$added = array();
	?>
	
		<?php foreach ($asset['Page'] as $page) {
		  $added[$page['id']] = true;
		?>
		<tr>
			<td style="width: 150px;"><?php echo __($page['id'], true); ?></td>
			<td><?php echo $this->Html->link(__($page['title'], true), array('controller'=>'pages','action'=>'view',$page['id'])); ?></td>
		</tr>
		<?php } // foreach ?>
<?php } // empty? ?>
			</table>
	</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
<!-- End of Pages-->