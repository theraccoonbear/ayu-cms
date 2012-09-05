<div class="page-header row">
    <div class="span6">
    </div><!-- end span6 -->
    <div class="span10 padTop5">
        <!--<button class="btn blue pull-right">New Slide</button>-->
    </div><!-- end span10 -->
</div><!-- end page header -->
<div class="content contentFill">
	<div class="row">
		<div class="span5">
			<h3 class="blue padTop10 padBot10 padLeft10">Edit Folder</h3>
		</div><!-- end span 5 -->
		<div class="span9 pull-right">
			<ul class="actionsList padTop20 pull-right">
				<li><?php echo $this->Html->link($this->Html->image('btnIcon-back.png', array('alt' => 'Back')) . __(' Cancel Edit', true), array('action' => 'view', $this->Form->value('Assetdir.id')), array('class' => 'btn pull-right', 'title' => 'Cancel', 'escape'=>false));?></li>
				<li><?php echo $this->Html->link($this->Html->image('btnIcon-delete.png', array('alt' => 'Delete')) . __('Delete', true), array('action' => 'delete', $this->Form->value('Assetdir.id')), array('class' => 'btn pull-right', 'title' => 'Delete', 'escape'=>false), sprintf(__('Are you sure you want to delete "%s"?', true), $this->Form->value('Assetdir.name'))); ?></li>
			</ul>
		</div><!-- end span9 -->
	</div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0"><?php echo $this->Form->create('Node');?>
			<?php echo $this->Form->create('Assetdir');?>
				<fieldset>
					<legend><!--<?php __('Edit Folder'); ?>--></legend>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('assetdir_id',array('type'=>'hidden'));
					echo $this->Form->input('name');
					echo $this->Form->input('description',array('type'=>'textarea'));
				?>
				</fieldset>
			<?php echo $this->Form->end(__('Submit', true));?>
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
