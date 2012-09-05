<div class="settings index">
	<h2><?php __('Snips');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('value');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($settings as $setting):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $setting['Setting']['id']; ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__($setting['Setting']['name'], true), array('action'=>'view',$setting['Setting']['id'])); ?>&nbsp;</td>
		<td>
			<pre style="width: 400px; overflow: hidden;"><?php
				$escaped = htmlentities($setting['Setting']['value']);
				$shortened = CMSHelper::textTrunc($escaped, 250);
				echo $shortened;
				echo strlen($shortened) < strlen($escaped) ? '&hellip;' : '';
			?>
		  </pre>&nbsp;
		</td>
		<td><?php echo $setting['Setting']['type']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $setting['Setting']['id']), array('class'=>'btnView')); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $setting['Setting']['id']), array('class'=>'btnEdit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $setting['Setting']['id']), array('class'=>'btnDelete'), sprintf(__('Are you sure you want to delete "%s"?', true), $setting['Setting']['name'])); ?>
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
		<li><?php echo $this->Html->link(__('New Setting', true), array('action' => 'add')); ?></li>
	</ul>
</div>