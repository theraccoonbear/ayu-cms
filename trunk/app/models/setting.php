<?php
class Setting extends AppModel {
	var $name = 'Setting';
	var $displayField = 'name';
	var $validate = array(
		'type' => array(
			'inlist' => array(
				'rule' => array('inList',array('STRING','TEXT','BOOLEAN','INTEGER','FLOAT')),
				'message' => 'Please select a valid setting type',
				'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)		
		),
		'name'=>array(
			'unique'=>array(
				'rule'=>'isUnique',
				'message'=>'This setting name is already in use'
			)
		)
	);
	
	function getByName($name = null, $default = false) {
		return $this->find('first', array('conditions'=>array('name'=>$name)));
	} 
	
	function getSetting($name) {
		$setting = $this->getByName($name);
		$ret_val = false;
		if (isset($setting['Setting'])) {
			$ret_val = $setting['Setting']['value'];
		}
		
		return $ret_val;
	}
	
}
