<?php
class Blog extends AppModel {
	var $name = 'Blog';
	var $displayField = 'title';
	var $recursive = 2;

	var $hasMany = array(
		'Blogpost' => array(
			'className' => 'Blogpost',
			'foreignKey' => 'blog_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	var $validate = array(
		'title' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This blog name is already in use.'
			)
		)
	);

}
