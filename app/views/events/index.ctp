<div class="events index">
	<h2><?php __('Events');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('organizer');?></th>
			<th><?php echo $this->Paginator->sort('address');?></th>
			<th><?php echo $this->Paginator->sort('cost');?></th>
			<th><?php echo $this->Paginator->sort('When','date');?></th>
			<th><?php echo $this->Paginator->sort('Enrollment','enrolled');?></th>
			<!--<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>-->
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($events as $event):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $event['Event']['id']; ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($event['Event']['title'], true), array('action'=>'view',$event['Event']['id'])); ?>&nbsp;</td>
		<td><?php echo $event['Event']['organizer']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['address'] . ', ' . $event['Event']['city']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['cost']; ?>&nbsp;</td>
		<td><?php echo CMSHelper::fmtDate($event['Event']['date']); ?>&nbsp;</td>
		<td><?php echo $event['Event']['enrolled']; ?> of <?php echo $event['Event']['capacity'] == 0 ? '&#8734;' : $event['Event']['capacity']; ?></td>
		<!--<td><?php echo CMSHelper::fmtDate($event['Event']['created'], array('fmt'=>'n/j/Y g:i a')); ?>&nbsp;</td>
		<td><?php echo CMSHelper::fmtDate($event['Event']['modified'], array('fmt'=>'n/j/Y g:i a')); ?>&nbsp;</td>-->
		<td>
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $event['Event']['id']), array('class'=>'btnView')); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $event['Event']['id']), array('class'=>'btnEdit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $event['Event']['id']), array('class'=>'btnDelete'), sprintf(__('Are you sure you want to delete "%s"?', true), $event['Event']['title'])); ?>
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
		<li><?php echo $this->Html->link(__('New Event', true), array('action' => 'add')); ?></li>
	</ul>
</div>