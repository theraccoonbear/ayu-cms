<div class="submissions form">
<?php echo $this->Form->create('Submission');?>
	<fieldset>
		<legend><?php __('Edit Submission'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('form');
		echo $this->Form->input('index1');
		echo $this->Form->input('index2');
		echo $this->Form->input('index3');
		echo $this->Form->input('contents');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Submission.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Submission.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Submissions', true), array('action' => 'index'));?></li>
	</ul>
</div>