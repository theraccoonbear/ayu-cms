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
			<h3 class="blue padTop10 padBot10 padLeft10">Add Asset</h3>
		</div><!-- end span 5 -->
		<div class="span9 pull-right">
			<ul class="actionsList padTop20 pull-right">
				<li><?php echo $this->Html->link(__('Back to Folder', true), array('controller'=>'assetdirs','action' => 'view', $asset_dir), array('title' => 'Back', 'escape'=>false));?></li>
			</ul>
		</div><!-- end span9 -->
	</div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
				
				
				
			<?php echo $this->Form->create('Asset',array('enctype'=>'multipart/form-data','url'=>$this->Html->url(array('controller'=>'assets','action'=>'add',$asset_dir))));?>
				<fieldset>
					<legend><!--<?php __('Add Asset'); ?>--></legend>
				<?php
					echo $this->Form->input('assetdir_id', array('type'=>'hidden','value'=>$asset_dir));
					echo $this->Form->input('file', array('type'=>'file'));
					echo $this->Form->input('description',array('type'=>'textarea'));
				?>
				</fieldset>
			<?php echo $this->Form->end(__('Submit', true));?>
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->
