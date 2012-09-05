<div class="page-header row">
    <div class="span6">
          <h1><?php  __('Edit Asset');?></h1>
    </div><!-- end span6 -->
    <div class="span10 padTop5">
        <!--<button class="btn blue pull-right">New Slide</button>-->
    </div><!-- end span10 -->
</div><!-- end page header -->
<div class="content contentFill">
	<div class="row">
		<div class="span5">
			<h3 class="blue padTop10 padBot10 padLeft10">Edit Asset</h3>
		</div><!-- end span 5 -->
		<div class="span9 pull-right">
			<div class="actions">
				<h3><?php __('Actions'); ?></h3>
				<ul>
					<li><?php echo $this->Html->link(__('Cancel Edit', true), array('action' => 'view', $this->Form->value('Asset.id')), array('title' => 'Cancel', 'escape'=>false));?></li>
				<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Asset.id')), array('title' => 'Delete', 'escape'=>false), sprintf(__('Are you sure you want to delete &quot;%s&quot;?', true), $this->Form->value('Asset.name'))); ?></li>
				</ul>
			</div>
		</div><!-- end span9 -->
	</div><!-- end row -->
    <div class="row padLeft20">
        <div class="span-full margin0">
			<?php echo $this->Form->create('Asset',array('enctype'=>'multipart/form-data'));?>
				<fieldset>
					<legend><!--<?php __('Edit Asset'); ?>--></legend>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('assetdir_id',array('type'=>'hidden'));
					echo $this->Form->input('name');
					echo $this->Form->input('description',array('id'=>'descriptionField'));
					echo $this->Form->input('type', array('id'=>'typeField'));
					
					
					
					
					if (preg_match('/^text\//i', $this->Form->value('Asset.type'))) {
						echo '<div class="input text">';
						echo '<label>Method</label> ';
						echo '<span style="float: left;">';
						echo '<a href="#" id="methodUploadFile">Upload New File</a> |  <a href="#" id="methodEditFile">Edit Current File</a>';
						echo '</span>';
						echo '</div><br/>';

						echo '<div id="fileEditor" class="fileMethod" style="display: none;">';
					  //echo $this->Form->input('new_content', array('class'=>'contentEditArea','type'=>'textarea','style'=>'font-family:courier new,courier,monospace;','rows'=>'15','cols'=>'150'));
						echo '<textarea class="contentEditArea" style="font-family:courier new,courier,monospace;" rows="15" cols="150" name="data[Asset][new_content]">' . htmlentities($content) . '</textarea>';
						echo '</div>';
					}
					
					echo $this->Form->input('fileChangeMethod', array('type'=>'hidden','value'=>'upload','id'=>'fileChangeMethod'));
					echo '<div id="fileUploader" class="fileMethod">';
					echo $this->Form->input('new_file', array('type'=>'file'));
					echo '</div>';
					
					
					
				?>
				</fieldset>
			<?php echo $this->Form->end(__('Submit', true));?>
		</div><!-- end span-full -->
	</div><!-- end row -->
</div><!-- end content -->

<script type="text/javascript">
  var i_know_mime = false;

	$('#typeField').focus(function(e) {
		if (!i_know_mime) {
			if (confirm("Are you sure you know what you're doing editing MIME types?  If not, click No/Cancel.")) {
				i_know_mime = true;
			} else {
				$('#descriptionField').focus();
			}
		}
	});
</script>