<?php
class Event extends AppModel {
	var $name = 'Event';
	var $displayField = 'title';
	var $order = 'Event.date';
	
	var $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'message' => 'This field cannot be left blank.  Use TBA if you are uncertain.'
		),
		'organizer' => array(
			'rule' => 'notEmpty',
			'message' => 'This field cannot be left blank.  Use TBA if you are uncertain.'
		),
		'contact' => array(
			'rule' => 'notEmpty',
			'message' => 'This field cannot be left blank.  Use TBA if you are uncertain.'
		),
		'address' => array(
			'rule' => 'notEmpty',
			'message' => 'This field cannot be left blank.  Use TBA if you are uncertain.'
		),
		'cost' => array(
			'rule' => 'notEmpty',
			'message' => 'This field cannot be left blank.  Use TBA if you are uncertain.'
		)
	);
}
