<script type="text/javascript">
  $(function() {
		var $input_fields = $('.input_field');
		var $shown = $('#string_input');
		var $true_value = $('#true_value');
		
		$('#inputPicker').change(function(e) {
			$input_fields.hide();
			var show_id = '#' + $(this).val().toLowerCase() + '_input';
			$shown = $(show_id);
			$shown.show();
			handle_change(e);
		});
		
		var handle_change = function(e) {
			$true_value.attr('value',$shown.val());
		};
		
		$('.input_field').change(handle_change);
		$('#inputPicker').change();
	});
</script>
<div class="settings form">
<?php echo $this->Form->create('Setting');?>
	<fieldset>
		<legend><?php __('Edit Snip'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		
			$options = array('STRING'=>'String','TEXT'=>'Text','BOOLEAN'=>'Boolean','INTEGER'=>'Integer','FLOAT'=>'Decimal');
			echo $this->Form->input('type', array('type'=>'select','options'=>$options,'id'=>'inputPicker'));
			
			?><div id="inputTypes" class="input" style="height: 200px;"><?php
				echo $this->Form->input('value',array('label'=>'Value','type'=>'textarea','style'=>'display:none;','div'=>false,'id'=>'true_value','value'=>$this->Form->value('Setting.value')));
				echo $this->Form->input('string_value', array('div'=>false,'label'=>false,'type'=>'text','id'=>'string_input','class'=>'input_field','value'=>$this->Form->value('Setting.value')));
				echo $this->Form->input('text_value', array('div'=>false,'label'=>false,'type'=>'textarea','id'=>'text_input','class'=>'input_field codeEdit','style'=>'display:none;','value'=>$this->Form->value('Setting.value')));
				echo $this->Form->input('boolean_value', array('div'=>false,'label'=>false,'type'=>'select','id'=>'boolean_input','options'=>array('true'=>'True','false'=>'False'),'class'=>'input_field','style'=>'display:none;','value'=>$this->Form->value('Setting.value')));
				echo $this->Form->input('integer_value', array('div'=>false,'label'=>false,'type'=>'text','id'=>'integer_input','class'=>'input_field','style'=>'display:none;','value'=>$this->Form->value('Setting.value')));
				echo $this->Form->input('float_value', array('div'=>false,'label'=>false,'type'=>'text','id'=>'float_input','class'=>'input_field','style'=>'display:none;','value'=>$this->Form->value('Setting.value')));
			?></div><?php
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Setting.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Setting.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Settings', true), array('action' => 'index'));?></li>
	</ul>
</div>