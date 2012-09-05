<div class="events form">
<?php echo $this->Form->create('Event');?>
	<fieldset>
		<legend><?php __('Add Event'); ?></legend>
	<?php
		echo $this->Form->input('date', array('type'=>'hidden','id'=>'actualDate'));
		echo $this->Form->input('the_date', array('label'=>'Date','id'=>'the_date'));
		echo $this->Form->input('the_time', array('type'=>'datetime','label'=>'Time','dateFormat'=>'NONE','minYear'=>date('Y'),'maxYear'=>(date('Y') + 1),'interval'=>15,'class'=>'ui-widget ui-widget-content ui-helper-clearfix ui-corner-all','id'=>'the_time'));
		echo $this->Form->input('title');
		echo $this->Form->input('organizer', array('value'=>'Jeff Fitzgerald'));
		echo $this->Form->input('contact', array('value'=>'jeffitz007.revolution@gmail.com'));
		echo $this->Form->input('address', array('value'=>'2330 Atwood Ave'));
		echo $this->Form->input('city',array('value'=>'Madison'));
		echo $this->Form->input('capacity',array('after'=>'Maximum number that can sign up.  Enter zero for unlimited','value'=>0));
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

		<li><?php echo $this->Html->link(__('Back to Events List', true), array('action' => 'index'));?></li>
	</ul>
</div>