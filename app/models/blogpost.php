<?php
class Blogpost extends AppModel {
	var $name = 'Blogpost';
	var $displayField = 'title';
	var $recursive = 2;
	var $order = 'Blogpost.created DESC';
	
	var $belongsTo = array(
		'Author' => array(
			'className' => 'User',
			'foreignKey' => 'author'
		),
		'Blog' => array(
			'className' => 'Blog',
			'foreignKey' => 'blog_id'
		)
	);
	
	var $validate = array(
		'content' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter some content'
			)
		),
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please add a title'
			)
		)
	);
	
	function permaLink($base, $post) {
		return 'http://' . $_SERVER['SERVER_NAME'] . $base . $post['id'] . '/' . CMSHelper::urlifyTitle($post['title']);
	}
}
