<div class="blogposts form">
<?php echo $this->Form->create('Blogpost');?>
	<fieldset>
		<legend><?php __('Edit Blogpost'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('blog_id',array('type'=>'hidden'));
		echo $this->Form->input('author',array('type'=>'hidden'));
		echo $this->Form->input('content', array('class' => 'useTinyMCE','after'=>'<a href="#" id="doTinyMCE">TinyMCE Editor</a>'));
		echo $this->Form->input('visible');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Blogpost.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Blogpost.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Blogposts', true), array('action' => 'index'));?></li>
	</ul>
</div>