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
			<h3 class="blue padTop10 padBot10 padLeft10">Add Folder</h3>
		</div><!-- end span 5 -->
		<div class="span9 pull-right">
			<ul class="actionsList padTop10 pull-right">
				<li><?php echo $this->Html->link(__('Back to Parent', true), array('action' => 'view', $assetdir_id), array('title' => 'Back to Parent', 'escape'=>false));?></li>
			</ul>
		</div><!-- end span9 -->
	</div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
			<?php echo $this->Form->create('Assetdir',array('url'=>$this->Html->url(array('controller'=>'assetdirs','action'=>'add',$assetdir_id))));?>
				<fieldset>
					<legend><!--<?php __('Add Folder'); ?>--></legend>
				<?php
					echo $this->Form->input('assetdir_id',array('type'=>'hidden','value'=>$assetdir_id));
					echo $this->Form->input('name');
					echo $this->Form->input('description', array('type'=>'textarea'));
					//echo $this->Form->input('Assetdir');
				?>
				</fieldset>
			<?php echo $this->Form->end(__('Submit', true));?>
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->