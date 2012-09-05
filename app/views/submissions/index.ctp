<div class="submissions index">
	<h2><?php __('Submissions');?></h2>
	<div class="filters">
	  <label for="filterByForm">Form</label> <select id="filterByForm">
			<option value="">** All (<?php echo $submits; ?>) **</option>
			<?php
			
			foreach ($forms as $form => $qty) {
				$sel = $form == $formFilter ? ' selected' : '';
				?><option value="<?php echo $form; ?>"<?php echo $sel; ?>><?php echo $form . ' (' . $qty . ')'; ?></option><?php
			}
				
			?>
			
			
		</select>
	</div>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('form');?></th>
			<th><?php echo $this->Paginator->sort('index1');?></th>
			<th><?php echo $this->Paginator->sort('index2');?></th>
			<th><?php echo $this->Paginator->sort('index3');?></th>
			<th><?php echo $this->Paginator->sort('contents');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($submissions as $submission):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
		$data = json_decode($submission['Submission']['contents']);
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->link(__($submission['Submission']['form'], true), array('action' => 'view', $submission['Submission']['id'])); ?>&nbsp;</td>
		<td><?php echo $submission['Submission']['index1']; ?>&nbsp;</td>
		<td><?php echo $submission['Submission']['index2']; ?>&nbsp;</td>
		<td><?php echo $submission['Submission']['index3']; ?>&nbsp;</td>
		<td><?php echo count((array)$data) . ' Field' . (count((array)$data) == 1 ? '' : 's'); ?>&nbsp;</td>
		<td><?php echo $submission['Submission']['created']; ?>&nbsp;</td>
		<td><?php echo $submission['Submission']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $submission['Submission']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $submission['Submission']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $submission['Submission']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $submission['Submission']['id'])); ?>
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
