<form method="post" name="Preview" action="<?php echo isset($page['Page']['path']) ? $page['Page']['path'] : '/'; ?>" id="PreviewForm" target="PreviewWindow">
  <input type="hidden" id="previewCopy" name="copy" />
	<input type="hidden" id="previewStyle" name="style" />
</form>
<script type="text/javascript">
  $(function() {
		var $prev_copy = $('#previewCopy');
		var $prev_style = $('#previewStyle');
		
		$('#previewPage').click(function(e) {
			var copy = $('#PageCopy').val();
			var style = $('#PageStyle').val();
			$prev_copy.val(copy);
			$prev_style.val(style);
			
			window.open('', 'PreviewWindow');
			document.getElementById('PreviewForm').submit();
			
			e.preventDefault();
		});
		
		
		var $styles = $('#PageStyle');
		
		$('#toggleStyle').click(function(e) {
			if ($styles.is(':hidden')) {
				$styles.show(250);
			} else {
				$styles.hide(250);
			}
			e.preventDefault();
		});
		
	});
</script>
<div class="pages form">
<?php echo $this->Form->create('Page');?>
	<fieldset>
		<legend><?php __('Edit Page'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('page_id',array('type'=>'hidden'));
		echo $this->Form->input('root',array('type'=>'hidden'));
		echo $this->Form->input('order',array('type'=>'hidden'));
		echo $this->Form->input('title');
		echo $this->Form->input('template', array('type'=>'select','options'=>$templates));
		if ($this->Form->value('Page.root') != 1) {
			echo $this->Form->input('url',array('after'=>'alphanumeric and dashes only'));
		}
		echo $this->Form->input('copy',array('after'=>'<a href="#" id="previewPage">Preview Changes</a> | <a href="#" id="doTinyMCE">TinyMCE Editor</a>','class'=>'codeEdit useTinyMCE'));
		echo $this->Form->input('style',array('label'=>'<a href="#" id="toggleStyle">Styles</a>','after'=>'Define any CSS styles here.  Do not add &lt;style&gt;&lt;/style&gt; tags.','style'=>'display: none;','class'=>'codeEdit'));
		echo $this->Form->input('background_id',array('type'=>'hidden'));
		echo $this->element('asset-picker', array('title'=>'Background Image','deselect'=>true,'assets'=>$assets,'target'=>'#PageBackgroundId','selected'=>$this->Form->value('Page.background_id')));
		if ($this->Form->value('Page.root') != 1) {
			echo $this->Form->input('visible');
		}
		echo $this->Form->input('terminal',array('after'=>'<br/><span class="note">Whether or not this page is a terminal node.  If checked, routing will<br/>not search for child pages and will use additional path information as parameters.</span>'));
		echo $this->Form->input('show_title',array('label'=>'Show the title at the top of the page?'));
		echo $this->Form->input('show_crumbs',array('label'=>'Show breadcrumb links below the title?'));
	  
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel Edit', true), array('controller' => 'pages', 'action' => 'view', $page['Page']['id'])); ?> </li>
		<?php if ($page['Page']['root'] != 1) { ?>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Page.id')), null, sprintf(__('Are you sure you want to delete "%s"?', true), $this->Form->value('Page.title'))); ?></li>
		<li><?php echo $this->Html->link(__('Back to Parent', true), array('controller' => 'pages', 'action' => 'view', $page['Page']['page_id'])); ?> </li>
		<?php } ?>
		<li><?php echo $this->Html->link(__('View Index', true), array('action' => 'index',)); ?> </li>
	</ul>
</div>