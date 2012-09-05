<div class="blogposts view">
<h2><?php  __('Blogpost');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogpost['Blogpost']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogpost['Blogpost']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Author'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link(__($blogpost['Author']['username'], true), array('controller'=>'users','action'=>'view',$blogpost['Author']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Content'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo preg_replace("/\n/", "<p/>\n", $blogpost['Blogpost']['content']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Visible'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogpost['Blogpost']['visible'] == 1 ? 'Yes' : 'No'; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogpost['Blogpost']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blogpost['Blogpost']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>

<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Blogpost', true), array('action' => 'edit', $blogpost['Blogpost']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Blogpost', true), array('action' => 'delete', $blogpost['Blogpost']['id']), null, sprintf(__('Are you sure you want to delete "%s"?', true), $blogpost['Blogpost']['title'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Blogposts', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
