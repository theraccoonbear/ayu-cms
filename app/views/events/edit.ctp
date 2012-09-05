<div class="events form">
<?php echo $this->Form->create('Event');?>
	<fieldset>
		<legend><?php __('Edit Event'); ?></legend>
	<?php
	
	  echo $this->Form->input('date', array('type'=>'hidden','id'=>'actualDate'));
		echo $this->Form->input('the_date', array('label'=>'Date','id'=>'the_date'));
		echo $this->Form->input('the_time', array('type'=>'datetime','label'=>'Time','dateFormat'=>'NONE','minYear'=>date('Y'),'maxYear'=>(date('Y') + 1),'interval'=>15,'class'=>'ui-widget ui-widget-content ui-helper-clearfix ui-corner-all','id'=>'the_time'));
		//echo $this->Form->input('date', array('label'=>'When','dateFormat'=>'MDY','minYear'=>date('Y'),'maxYear'=>(date('Y') + 1),'interval'=>15));
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('organizer');
		echo $this->Form->input('contact');
		echo $this->Form->input('address');
		echo $this->Form->input('city');
		echo $this->Form->input('capacity',array('after'=>'Maximum number that can sign up.  Enter zero for unlimited'));
		echo $this->Form->input('enrolled',array('after'=>'How many are currently signed up'));
		echo $this->Form->input('cost');
		echo $this->Form->input('payment');
		echo $this->Form->input('description');
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel Edit', true), array('action' => 'view', $this->Form->value('Event.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Delete Event', true), array('action' => 'delete', $this->Form->value('Event.id')), null, sprintf(__('Are you sure you want to delete "%s"?', true), $this->Form->value('Event.title'))); ?></li>
		<li><?php echo $this->Html->link(__('Back to Events List', true), array('action' => 'index'));?></li>
	</ul>
</div>

<script type="text/javascript">
	$(function() {
		(function() {
			var $actual_date = $('#actualDate');
			var $event_date = $('#the_date')
			var $hour = $('#the_timeHour');
			var $mins = $('#the_timeMinute');
			var $ampm = $('#the_timeMeridian');
			
			var date = new Date($actual_date.val());
			
			$event_date.datepicker('setDate', date);
			
			var hour = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
			var mins = date.getMinutes() == '45' ? 4 : (date.getMinutes() == '15' ? 2 : (date.getMinutes() == '30' ? 3 : 1));
			var ampm = date.getHours() > 12 ? 2 : 1;
			
			$hour.find(':nth-child(' + hour + ')').attr('selected', 'selected');
			$mins.find(':nth-child(' + mins + ')').attr('selected', 'selected');
			$ampm.find(':nth-child(' + ampm + ')').attr('selected', 'selected');
		})()
	});
</script>