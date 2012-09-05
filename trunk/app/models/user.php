<?php
class User extends AppModel {
	var $name = 'User';
	var $displayField = 'username';
	
	var $preferences = array(
	  'use_tiny_mce' => array(
		  'default' => true,
			'type' => 'boolean',
			'label' => 'TinyMCE',
			'description' => 'Use the TinyMCE editor for content'
		)
	);
	
	var $hasMany = array(
	  'Blogposts' => array(
		  'classname'=>'Blogpost',
			'foreignKey'=>'author'
		)
	);
	
	var $validate = array(
		'email' => array(
			  'isEmail' => array(
					'rule' => array('email', true),
					'message' => 'Please supply a valid email address.'
				),
				'isUnique' => array(
				  'rule' => 'isUnique',
					'message' => 'This email address is already associated with an account.'
				)
		),
		'username' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This username has already been taken.'
			)
		)
	);
	
	function afterFind($results, $primary) {
		foreach ($results as $idx => $user) {
			if (isset($user['User']) && isset($user['User']['preferences'])) {
				$prefs =  (array)json_decode($user['User']['preferences']);
				$npref = array();
				
				foreach ($prefs as $id => $pref) {
					$pref_type = strtolower(isset($this->preferences[$id]) && isset($this->preferences[$id]['type']) ? $this->preferences[$id]['type']  : 'string');
					switch ($pref_type) {
						case 'boolean':
							$npref[$id] = ($pref == true);
						default:
							$npref[$id] = $pref;
							break;
					}
					
				}
				$results[$idx]['User']['prefs'] = $npref;
			}
		}
		
		return $results;
	}
	
	function getPref($user, $pref_name) {
		if (isset($user['User']) && isset($user['User']['prefs']) && isset($user['User']['prefs'][$pref_name])) {
			return $user['User']['prefs'][$pref_name];
		} else {
			if (isset($this->preferences[$pref_name])) {
				return isset($this->preferences[$pref_name]['default']) ? $this->preferences[$pref_name]['default'] : false;
			} else {
				false;
			}
		}
	} 

	
}
