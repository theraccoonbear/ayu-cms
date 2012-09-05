<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	//Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	
	//Router::connect('/:promo', array('controller' => 'promos', 'action' => 'process'), array('promo' => '(?!('.implode('|', $controllers).')\W+)[a-zA-Z\-_]+/?$'));
	//
	//Router::connect(
	//	'/admin/:action',
	//	array('controller' => 'pages', 'action' => 'index')
	//);
	
	Router::connect(
	  '/admin',
		array('controller'=>'pages', 'action'=>'index')
	);
	
	Router::connect(
		'/admin/pages',
		array('controller' => 'pages', 'action' => 'index')
	);
	
	Router::connect(
		'/admin/pages/:action/*',
		array('controller' => 'pages', 'action' => 'action')
	);
	
	Router::connect(
		'/admin/folders/:action/*',
		array('controller' => 'assetdirs', 'action'=>'action')
	);	
	
	Router::connect(
		'/admin/assets/:action/*',
		array('controller' => 'assets', 'action'=>'action')
	);
	
	Router::connectNamed(array('width'));
	Router::connect(
		'/admin/assets/:action/:id/*',
		array('controller' => 'assets', 'action'=>'action')
	);
	
	Router::connect(
		'/admin/events/:action/*',
		array('controller' => 'events', 'action'=>'action')
	);
	
	Router::connect(
		'/admin/snips/:action/*',
		array('controller' => 'settings', 'action'=>'action')
	);
	
	Router::connect(
		'/admin/blog/:action/*',
		array('controller' => 'blogs', 'action'=>'action')
	);
	
	Router::connect(
		'/admin/posts/:action/*',
		array('controller' => 'blogposts', 'action'=>'action')
	);
	
	Router::connect(
		'/admin/users/:action/*',
		array('controller' => 'users', 'action' => 'action')
	);
	
	Router::connect(
		'/admin/forms/:action/*',
		array('controller' => 'submissions', 'action' => 'action')
	);
	
	//
	//Router::connect(
	//	'/admin/pages/*',
	//	array('controller' => 'pages', 'action'=>'index')
	//);
	//
	//Router::connect(
	//	'/admin/pages/*',
	//	array('controller' => 'pages', 'action'=>'index')
	//);
	
	Router::connect(
		'/*',
		array('controller' => 'pages', 'action' => 'display')
	);
	
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
//	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
