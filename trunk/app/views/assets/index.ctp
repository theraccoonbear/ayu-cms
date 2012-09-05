<div class="page-header row">
    <div class="span6">
          <h1><?php __('Assets');?></h1>
    </div><!-- end span6 -->
    <div class="span10 padTop5">
        <button class="btn blue pull-right">New Folder</button> <button class="btn blue pull-right">New Asset</button>
    </div><!-- end span10 -->
</div><!-- end page header -->
<div class="content contentFill">
    <div class="row">
        <div class="span5">
            <h3 class="blue padTop10 padBot10 padLeft10"><?php __('Assets');?></h3>
        </div><!-- end span 5 -->
    </div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
			<table cellpadding="0" cellspacing="0">
			<tr>
					<th><?php echo $this->Paginator->sort('id');?></th>
					<th><?php echo $this->Paginator->sort('assetdir_id');?></th>
					<th><?php echo $this->Paginator->sort('name');?></th>
					<th><?php echo $this->Paginator->sort('description');?></th>
					<th><?php echo $this->Paginator->sort('created');?></th>
					<th><?php echo $this->Paginator->sort('modified');?></th>
					<th><?php __('Actions');?></th>
			</tr>
			<?php
			$i = 0;
			foreach ($assets as $asset):
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
			?>
			<tr<?php echo $class;?>>
				<td><?php echo $asset['Asset']['id']; ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($asset['Assetdir']['name'], array('controller' => 'assetdirs', 'action' => 'view', $asset['Assetdir']['id'])); ?>
				</td>
				<td><?php echo $asset['Asset']['name']; ?>&nbsp;</td>
				<td><?php echo $asset['Asset']['description']; ?>&nbsp;</td>
				<td><?php echo $asset['Asset']['created']; ?>&nbsp;</td>
				<td><?php echo $asset['Asset']['modified']; ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link(__('View', true), array('action' => 'view', $asset['Asset']['id'])); ?>
					<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $asset['Asset']['id'])); ?>
					<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $asset['Asset']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $asset['Asset']['id'])); ?>
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
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Asset', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Assetdirs', true), array('controller' => 'assetdirs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Assetdir', true), array('controller' => 'assetdirs', 'action' => 'add')); ?> </li>
	</ul>
</div>