<div class="blogs view">
<h2><?php  __('Blog');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $blog['Blog']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Blog', true), array('action' => 'edit', $blog['Blog']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Blog', true), array('action' => 'delete', $blog['Blog']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $blog['Blog']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Blogs', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Blogposts');?></h3>
	<?php if (!empty($blog['Blogpost'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Title'); ?></th>
		<th><?php __('Author'); ?></th>
		<th><?php __('Content'); ?></th>
		<th><?php __('Visible'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Blog Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($blog['Blogpost'] as $blogpost):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $this->Html->link(__($blogpost['title'], true), array('controller'=>'blogposts','action'=>'view',$blogpost['id']));?></td>
			<td><?php echo $this->Html->link(__($blogpost['Author']['username'], true), array('controller'=>'users','action'=>'view',$blogpost['Author']['id']));?></td>
			<td><?php
			
			$sample = CMSHelper::textTrunc($blogpost['content'], 150);
			echo $sample . (strlen($sample) < strlen($blogpost['content']) ? '&hellip;' : '');
			
			?></td>
			<td><?php echo $blogpost['visible'] == 1 ? 'Yes' : 'No';?></td>
			<td><?php echo $blogpost['created'];?></td>
			<td><?php echo $blogpost['modified'];?></td>
			<td><?php echo $blogpost['blog_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'blogposts', 'action' => 'view', $blogpost['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'blogposts', 'action' => 'edit', $blogpost['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'blogposts', 'action' => 'delete', $blogpost['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $blogpost['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'blogposts', 'action' => 'add', $blog['Blog']['id']));?> </li>
		</ul>
	</div>
</div>
