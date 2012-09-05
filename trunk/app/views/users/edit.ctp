<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php __('Edit User'); ?></legend>
	<?php
	
		echo $this->Form->input('id');
		echo $this->Form->input('displayname', array('label'=>'Display Name'));
		echo $this->Form->input('email',array('after'=>$this->Html->link('Gravatar?', 'http://www.gravatar.com', array('target'=>'_blank'))));
		echo $this->Form->input('password',array('value'=>'','after'=>'Leave blank if not changing password'));
		echo $this->Form->input('passwordconfirm',array('value'=>'','label'=>'Confirm Password','after'=>'Leave blank if not changing password','type'=>'password'));
		
	?>
	</fieldset>
	<fieldset>
		<legend>Preferences</legend>
		<?php
		  
			$u = new User();
			foreach ($u->preferences as $id  => $pref) {
				
				$req_fields = array(
					'label' => array('default' => $id),
					'after' => array('default' => ''),
					'type' => array('default' => 'string'),
					'description' => array('default' => '')
				);
				
				$p = CMSHelper::embedParams($pref, $req_fields);
				
				$io = array('label'=>$p['label'],'after'=>$p['description'],'name'=>'data[User][__pref_' . $id . ']');
				
				if (isset($user['User']['prefs'][$id])) {
					$io['value'] = $user['User']['prefs'][$id];
				}
				
				switch ($p['type']) {
					case 'string':
					case 'radio':
						break;
					case 'boolean':
						$io['type'] = 'radio';
						$io['options'] = array(1=>'Yes',0=>'No');
						break;
					default:
						break;
				}
				
				echo $this->Form->input($id, $io);
			}
			
		?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('User.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
	</ul>
</div>