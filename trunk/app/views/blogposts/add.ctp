<div class="blogposts form">
<?php echo $this->Form->create('Blogpost',array('url' => array($blog_id)));?>
	<fieldset>
		<legend><?php __('Add Blogpost'); ?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('blog_id', array('value'=>$blog_id,'type'=>'hidden'));
		echo $this->Form->input('author', array('type'=>'hidden','value'=>$logged_user['User']['id']));
		echo $this->Form->input('content', array('class' => 'useTinyMCE','after'=>'<a href="#" id="doTinyMCE">TinyMCE Editor</a>'));
		echo $this->Form->input('visible',array('checked'=>'checked'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Posts', true), array('controller'=>'blogs','action' => 'view', $blog_id));?></li>
	</ul>
</div>