<div class="blogposts index">
	<h2><?php __('Blogposts');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('author');?></th>
			<th><?php echo $this->Paginator->sort('content');?></th>
			<th><?php echo $this->Paginator->sort('visible');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($blogposts as $blogpost):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $blogpost['Blogpost']['id']; ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__(CMSHelper::textTrunc($blogpost['Blogpost']['title'], 50) . '&hellip;', true), array('action'=>'view',$blogpost['Blogpost']['id']), array('escape'=>false)); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($blogpost['Author']['username'], true), array('controller'=>'users','action'=>'view',$blogpost['Author']['id'])); ?>&nbsp;</td>
		<td><?php echo CMSHelper::textTrunc($blogpost['Blogpost']['content'], 50) . '&hellip;'; ?>&nbsp;</td>
		<td><?php echo $blogpost['Blogpost']['visible'] == 1 ? 'Yes' : 'No'; ?>&nbsp;</td>
		<td><?php echo $blogpost['Blogpost']['created']; ?>&nbsp;</td>
		<td><?php echo $blogpost['Blogpost']['modified']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $blogpost['Blogpost']['id']), array('class'=>'btnView')); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $blogpost['Blogpost']['id']), array('class'=>'btnEdit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $blogpost['Blogpost']['id']), array('class'=>'btnDelete'), sprintf(__('Are you sure you want to delete "%s"?', true), $blogpost['Blogpost']['title'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Blogpost', true), array('action' => 'add')); ?></li>
	</ul>
</div>