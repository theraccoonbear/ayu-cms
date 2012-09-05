
<?php

//pr($page);

?>
<div class="pages view">
<h2><?php  __('Page');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $page['Page']['id']; ?>
			&nbsp;
		</dd>
		<?php if ($page['Page']['root'] != 1) { ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Parent Page'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link(__($page['ParentPage']['title'], true), array('action'=>'view',$page['ParentPage']['id'])); ?>
			&nbsp;
		</dd>
		<?php } // root? ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $page['Page']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $page['Page']['url']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Path'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<a href="<?php echo $page['Page']['path']; ?>" target="_blank"><?php echo $page['Page']['path']; ?></a>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Background'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
			
			if ($page['Page']['background_id'] > 0) {
				echo $this->Html->link(__($page['BackgroundAsset']['name'], true), array('controller'=>'assets','action'=>'view',$page['Page']['background_id']));
			} else {
				echo '&#8212;';
			}
			
			?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Visible'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $page['Page']['visible'] == 1 ? 'Yes' : 'No'; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Terminal'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $page['Page']['terminal'] == 1 ? 'Yes' : 'No'; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo CMSHelper::fmtDate($page['Page']['created']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo CMSHelper::fmtDate($page['Page']['modified']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Preview'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		  <span id="previewActions"><a href="#" class="show">Show</a><a href="#" class="hide" style="display: none;">Hide</a></span>
			<iframe src="<?php echo $page['Page']['path']; ?>" width="800" height="480" style="display:none;" id="previewFrame"></iframe>
			&nbsp;
			<script type="text/javascript">
			  $(document).ready(function(e) {
					$('#previewActions a').click(function(e) {
						if ($(this).hasClass('hide')) {
						  if ($('#previewFrame').is(':visible')) {
								$('#previewFrame').hide();
								$('#previewActions a.hide').hide();
								$('#previewActions a.show').show();
							}
						} else {
							if (!$('#previewFrame').is(':visible')) {
								$('#previewFrame').show();
								$('#previewActions a.show').hide();
								$('#previewActions a.hide').show();
							}
						}
						e.preventDefault();
					});
				});
			</script>
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Page', true), array('action' => 'edit', $page['Page']['id'])); ?> </li>
		<?php if ($page['Page']['root'] != 1) { ?>
		<li><?php echo $this->Html->link(__('Delete Page', true), array('action' => 'delete', $page['Page']['id']), null, sprintf(__('Are you sure you want to delete "%s"?', true), $page['Page']['title'])); ?> </li>
		<li><?php echo $this->Html->link(__('Back to Parent', true), array('controller' => 'pages', 'action' => 'view', $page['Page']['page_id'])); ?> </li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('View Index', true), array('action' => 'index',)); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Child Pages');?></h3>
	<?php
	
	if (!empty($page['ChildPages'])) {
	
	?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Order'); ?></th>
		<th><?php __('Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('URL'); ?></th>
		<th><?php __('Visible'); ?></th>
		<th><?php __('Terminal'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($page['ChildPages'] as $cp) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
		  <td>
				<?php echo $this->Html->link(__('Up', true), array('controller' => 'pages', 'action' => 'moveup', $cp['id']), array('class'=>'btnUp')); ?>
				<?php echo $this->Html->link(__('Down', true), array('controller' => 'pages', 'action' => 'movedown', $cp['id']), array('class'=>'btnDown')); ?>
			</td>
			<td><?php echo $cp['id'];?></td>
			<td><?php echo $cp['title'];?></td>
			<td><?php echo $this->Html->link(__($cp['url'], true), array('action'=>'view',$cp['id'])) ;?></td>
			<td><?php echo $cp['visible'] == 1 ? 'Yes' : 'No';?></td>
			<td><?php echo $cp['terminal'] == 1 ? 'Yes' : 'No';?></td>
			<td><?php echo CMSHelper::fmtDate($cp['created']);?></td>
			<td><?php echo CMSHelper::fmtDate($cp['modified']);?></td>
			<td>
				<?php echo $this->Html->link(__('View', true), array('controller' => 'pages', 'action' => 'view', $cp['id']), array('class'=>'btnView')); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'pages', 'action' => 'edit', $cp['id']), array('class'=>'btnEdit')); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'pages', 'action' => 'delete', $cp['id']), array('class'=>'btnDelete'), sprintf(__('Are you sure you want to delete "%s"?', true), $cp['title'])); ?>
			</td>
		</tr>
	<?php } // foreach ?>
	</table>
<?php } // children? ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Page', true), array('controller' => 'pages', 'action' => 'add', $page['Page']['id']));?> </li>
		</ul>
	</div>
</div>
