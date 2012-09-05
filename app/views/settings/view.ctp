<div class="settings view">
<h2><?php  __('Snip');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $setting['Setting']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $setting['Setting']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Value'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<pre style="width: 600px;"><?php
			  echo htmlentities($setting['Setting']['value']);
			?>
			</pre>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $setting['Setting']['type']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Setting', true), array('action' => 'edit', $setting['Setting']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Setting', true), array('action' => 'delete', $setting['Setting']['id']), null, sprintf(__('Are you sure you want to delete "%s"?', true), $setting['Setting']['name'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Settings', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
