<?php

class AppController extends Controller {
	//var $components = array('Auth', 'Session');
		
		
	var $components = array(
		'Auth' => array(
			'authorize' => 'controller',
			'allowedActions' => array('fetch','post')
		),
		'Session',
		'Email'
	);

		
	var $rootID = 0;
	var $Html;
	
	function beforeFilter() {
		App::import('Helper', 'Html');
		App::import('Vendor', 'mustache');
		
		$this->loadModel('Setting');
		$this->loadModel('User');
		
		$this->Html = new HtmlHelper();
		$this->Mustache = new Mustache();
		$this->User = new User();
		$this->Setting = new Setting();
		$this->set('SettingModel', $this->Setting);
		$this->set('UserModel', new User());
		$this->set('Mustache', $this->Mustache);
		
		App::import('Helper', 'CMS');
		$this->CMS = new CMSHelper();
		
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginError = 'Invalid e-mail / password combination.  Please try again';
		$this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'index');
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
		$this->Auth->allow('display');
		$this->Auth->authorize = 'controller';
		
		$root_user = $this->User->find('first', array('conditions'=>array('username'=>'admin')));
		
		if (!is_array($root_user) || !array_key_exists('User', $root_user)) {
			$this->User->create();
			
			$admin_user = array(
				'User' => array(
					'username' => 'admin',
					'password' => 'admin',
					'email' => 'admin@localhost.com',
					'displayname' => 'Admin'
				)
			);
			
			
			if (!$this->User->save($this->Auth->hashPasswords($admin_user))) {
				$this->Session->setFlash(__('Admin user could not be added.', true));
				//print '<h1>Created</h1>';
				//pr($this->User->id);
			}
		} else {
			//print "***";
			//pr($root_user);
		}
		
		//exit(0);
		
		//$this->Auth->allow('*');
		
		if (preg_match('/^\/admin/i', $this->here)) {
			$u = $this->Auth->user();
			$logged_user = $this->User->read(null, $u['User']['id']);
			$this->set('logged_user', $logged_user);
		} else {
			$this->Auth->allow('*');
		}
		
		
		
		$this->loadModel('Page');
		
		$root = $this->Page->find('first', array('conditions'=>array('Page.root'=>1)));
		
		if (!is_array($root) || !isset($root['Page'])) {
			$root_data = array(
				'Page' => array(
					'root' => 1,
					'page_id' => 0,
					'title' => 'Home Page',
					'url' => '',
					'copy' => '',
					'visible' => 1
				)
			);
			
			$this->Page->create();
			$this->Page->save($root_data);
			$this->rootID = $this->Page->getInsertID();
		} else {
			$this->rootID = $root['Page']['id'];
		}
	
	}
	
	function isAuthorized() {
		$u = $this->Auth->user();
		if (isset($u['User'])) {
			return true;
		} else {
			return false;
		}
	}
}


?>