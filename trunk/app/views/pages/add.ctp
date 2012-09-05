<div class="pages form">
<?php echo $this->Form->create('Page',array('url'=>$this->Html->url(array('controller'=>'pages','action'=>'add',$parent))));?>
	<fieldset>
		<legend><?php __('Add Page'); ?></legend>
	<?php
		echo $this->Form->input('page_id', array('type'=>'hidden','value'=>$parent));
		echo $this->Form->input('title');
		echo $this->Form->input('template', array('type'=>'select','options'=>$templates));
		
		echo $this->Form->input('root',array('type'=>'hidden','value'=>0));
		echo $this->Form->input('url',array('after'=>'alphanumeric and dashes only'));
		echo $this->Form->input('copy',array('class'=>'codeEdit useTinyMCE'));
		echo $this->Form->input('style',array('after'=>'Define any CSS styles here.  Do not add &lt;style&gt;&lt;/style&gt; tags.','class'=>'codeEdit'));
		echo $this->Form->input('background_id',array('type'=>'hidden','value'=>0));
		echo $this->element('asset-picker', array('title'=>'Background Image','deselect'=>true,'assets'=>$assets,'target'=>'#PageBackgroundId','selected'=>0));
		echo $this->Form->input('visible',array('checked'=>'checked'));
		echo $this->Form->input('terminal',array('after'=>'<br/><span class="note">Whether or not this page is a terminal node.  If checked, routing will<br/>not search for child pages and will use additional path information as parameters.</span>'));
		echo $this->Form->input('show_title',array('checked'=>'checked','label'=>'Show the title at the top of the page?'));
		echo $this->Form->input('show_crumbs',array('checked'=>'checked','label'=>'Show breadcrumb links below the title?'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Back to Parent', true), array('action' => 'view', $parent));?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var $url = $('#PageUrl');
		var $title = $('#PageTitle');
		var init_url = $url.attr('value');
		
		$('#PageTitle').focus(function() {
			$title.keyup(function() {
				if ($url.attr('value') == init_url) {
					var title = $title.attr('value');
					var new_url = title.toLowerCase().replace(/ /g, '-');
					$url.attr('value', new_url);
					init_url = new_url;
				}
			});
		});
	});
</script>