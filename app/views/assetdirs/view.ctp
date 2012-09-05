<div id="assetPreview"><img alt="AssetPreview" src="/img/spinner.gif" /></div>

<!-- Begin Current Folder -->
<div class="content contentFill">
    <div class="row">
        <div class="span3">
            <h3 class="blue padTop10 padBot10 padLeft10">Current Folder</h3>
        </div><!-- end span 5 -->
		<div class="span11 pull-right">
			<?php
			
			if ($id != 0) {
				?>
				<ul>
					<li><?php echo $this->Html->link(__('Delete Folder', true), array('action' => 'delete', $id), null, sprintf(__('Are you sure you want to delete "%s"?', true), $name)); ?></li>
					<li><?php echo $this->Html->link(__('Back to Parent Folder', true), array('action' => 'view', $parent_id)); ?> </li>
				</ul>
	<?php
	
			}
			
			?>
		</div><!-- end span9 -->
    </div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th><?php __('Name'); ?></th>
					<th><?php __('Description'); ?></th>
				</tr>
				<tr>
					<td><?php echo $name; ?></td>
					<td><?php echo $description; ?></td>
				</tr>
			</table>
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
<!-- End Current Folder -->


<!-- Begin Child Folders -->
<div class="content contentFill">
    <div class="row">
        <div class="span5">
            <h3 class="blue padTop10 padBot10 padLeft10"><?php __('Folders');?></h3>
        </div><!-- end span 5 -->
		<div class="span9 pull-right">
			
			<div class="actions">
				<ul>
					<li><?php echo $this->Html->link(__('Add Folder', true), array('action' => 'add', $id)); ?></li>
				</ul>
			</div>
			
		</div><!-- end span9 -->
  </div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php __('Name'); ?></th>
				<th><?php __('Created'); ?></th>
				<th><?php __('Modified'); ?></th>
				<th><?php __('Actions'); ?></th>
			</tr>
			<?php if (!empty($assetdirs)) {?>
			<?php
				$i = 0;
				foreach ($assetdirs as $dir) {
					$d = $dir['Assetdir'];
					$class = null;
					if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
					}
				?>
				<tr<?php echo $class;?>>
					<td><span class="folder icon"></span>&nbsp;<?php echo $this->Html->link(__($d['name'], true), array('action' => 'view', $d['id'])); ?></td>
					<td><?php echo $d['created'];?></td>
					<td><?php echo $d['modified'];?></td>
					<td>
						<div class="editBox">
							<?php echo $this->Html->link(__('View', true), array('action' => 'view', $d['id']), array('class' => 'btnView', 'title' => 'View')); ?>
							<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $d['id']), array('class' => 'btnEdit', 'title' => 'Edit')); ?> 
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $d['id']), array('class' => 'btnDelete', 'title' => 'Delete'), sprintf(__('Are you sure you want to delete "%s"?', true), $d['name'])); ?>
						</div><!-- end editBox -->
					</td>
				</tr>
			<?php } ?>
			
		<?php
		  }
		?>
		</table>
		
		
		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('Add Folder', true), array('action' => 'add', $id)); ?></li>
			</ul>
		</div>
		

			
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
<!-- End Child Folders -->

<div class="clearfix"></div>

<!-- Begin Assets  -->
<div class="content contentFill">
    <div class="row">
        <div class="span5">
            <h3 class="blue padTop10 padBot10 padLeft10"><?php __('Assets');?></h3>
        </div><!-- end span 5 -->
		<div class="span9 pull-right">
			<div class="actions">
				<ul>
					<li><?php echo $this->Html->link(__('Add Asset', true), array('controller'=>'assets','action' => 'add', $id)); ?></li>
				</ul>
			</div>
				
		</div><!-- end span9 -->
    </div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
			
			<table cellpadding = "0" cellspacing = "0">
			<tr>
				<th><?php __('Name'); ?></th>
				<th><?php __('Type'); ?></th>
				<th><?php __('Size'); ?></th>
				<th><?php __('Meta'); ?></th>
				<th><?php __('Created'); ?></th>
				<th><?php __('Modified'); ?></th>
				<th><?php __('Actions'); ?></th>
			</tr>
			<?php if (!empty($assets)) {?>
			<?php
				$i = 0;
				foreach ($assets as $asset) {
					$a = $asset['Asset'];
					$class = null;
					if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
					}
					
					$parts = preg_split('/\//', $a['type']);
					$major = $parts[0];
					$minor = $parts[1];
					$icon_type = '';
					$assetLink = $major == 'image' ? 'assetLink' : '';
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
				<tr<?php echo $class;?>>
					<td>
						<table class="details">
							<tr>
								<td rowspan="2" style="width: 18px;">
									<span class="<?php echo $icon_type; ?>icon"></span>
								</td><td>
									<?php
									
									echo $this->Html->link(__($a['name'], true), array('controller'=>'assets','action' => 'view', $a['id']), array('class'=>$assetLink,'rel'=>$this->Html->url(array('controller'=>'assets','action'=>'fetch',$a['id'],'width'=>'150'))));
									
									?>
								</td>
							</tr><tr>
							  <td>
					  <?php
						if (strlen(trim($a['description'])) > 0) {
						  ?><span class="description"><?php echo $a['description']; ?></span><?php
						}
						?>
								</td>
							</tr>
						</table>
					</td>
					<td><a href="http://www.abbreviations.com/X/bs.aspx?st=<?php echo $a['type']; ?>&SE=2" target="_blank"><?php echo $a['type']; ?></a></td>
					<td><?php echo CMSHelper::fmtFilesize($a['size'], 2);?></td>
					<td style="text-align:center;"><?php echo $a['width'] > 0 && $a['height'] > 0 ? $a['width'] . ' x ' . $a['height'] : '&#8212;';?></td>
					<td><?php echo $a['created'];?></td>
					<td><?php echo $a['modified'];?></td>
					<td>
						<div class="editBox">
							<?php echo $this->Html->link(__('View', true), array('controller'=>'assets','action' => 'view', $a['id']), array('class' => 'btnView', 'title' => 'View')); ?>
							<?php echo $this->Html->link(__('Edit', true), array('controller'=>'assets','action' => 'edit', $a['id']), array('class' => 'btnEdit', 'title' => 'Edit')); ?> 
							<?php echo $this->Html->link(__('Delete', true), array('controller'=>'assets','action' => 'delete', $a['id']), array('class' => 'btnDelete', 'title' => 'Delete'), sprintf(__('Are you sure you want to delete "%s"?', true), $a['name'])); ?>
						</div><!-- end editBox -->
					</td>
				</tr>
			<?php
				}
			}
			
			
			?>
			</table>
			
		
		
		
			<div class="marginBot15 clearfix">
				<div class="actions">
					<ul>
						<li><?php echo $this->Html->link(__('Add Asset', true), array('controller'=>'assets','action' => 'add', $id)); ?></li>
					</ul>
				</div>
			</div>
			
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
<!-- End Assets -->