<?php
class AssetdirsController extends AppController {
	var $name = 'Assetdirs';
	
	var $helpers = array('Form', 'Html', 'CMS');
	
  function beforeFilter() {
		parent::beforeFilter();
		$this->layout = 'default';
		//$this->set('breadcrumb_anchor', $this->Html->link(__('Assets', true), array('controller'=>'assetdirs','action'=>'index')));
	}

	function index() {
		$this->Assetdir->recursive = 0;
		$this->set('assetdirs', $this->paginate());
		$this->redirect(array('action'=>'view',0));
	}

  function view($id = null) {
		if ($id == null) {
			$this->Session->setFlash(__('Invalid Folder', true));
			$this->redirect(array('action' => 'view', 0));
		}
		
		$parent_id = -1;
		$name = '';
		$description = '';
		
		if ($id == 0) {
			$parent_id = 0;
			$name = 'Root';
			$description = 'The top level folder';
		} else {
			$ad = $this->Assetdir->read(null, $id);
			if (!isset($ad)) {
				$this->Session->setFlash(__('Invalid Folder', true));
				$this->redirect(array('action'=>'view',0));
			}
			$ad = $ad['Assetdir'];
			$parent_id = $ad['assetdir_id'];
			$name = $ad['name'];
			$description = $ad['description'];
		}
		$assetdirs = $this->Assetdir->find('all', array('conditions'=>array('Assetdir.assetdir_id'=>$id), 'recursive' => 0));
		$this->loadModel('Assets');
		$assets = $this->Assetdir->Asset->find('all', array('conditions'=>array('Asset.assetdir_id'=>$id), 'recursive' => 0));
		$this->set(compact('assetdirs', 'assets', 'parent_id', 'name', 'description', 'id'));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Assetdir->create();
			if ($this->Assetdir->save($this->data)) {
				$this->Session->setFlash(__('The folder has been saved', true));
				$this->redirect(array('action' => 'view', $this->data['Assetdir']['assetdir_id']));
			} else {
				$this->Session->setFlash(__('The folder could not be saved. Please, try again.', true));
			}
		}
		$assetdir_id = $id; //$this->params['named']['Assetdir'];
		
		$assetdirs = $this->Assetdir->find('list', array('conditions'=>array('assetdir_id'=>$assetdir_id)));
		$this->set(compact('assetdirs','assetdir_id'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid assetdir', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Assetdir->save($this->data)) {
				$this->Session->setFlash(__('The folder has been saved', true));
				$this->redirect(array('action' => 'view',$this->data['Assetdir']['assetdir_id']));
			} else {
				$this->Session->setFlash(__('The folder could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Assetdir->read(null, $id);
		}
		$assetdirs = $this->Assetdir->find('list');
		$this->set(compact('assetdirs'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for folder', true));
			$this->redirect(array('controller'=>'sections','action'=>'index'));
		}
		
		$parent_dir = $this->Assetdir->read(null, $id);
		$parent_id = $parent_dir['Assetdir']['assetdir_id'];
		
		if (count($parent_dir['Children']) > 0 || count($parent_dir['Asset']) > 0) {
			pr($parent_dir); exit(0);
			$this->Session->setFlash(__('A folder cannot be removed until all of its child folders and assets are removed.', true));
			$this->redirect($this->referer());
		} else {
			if ($this->Assetdir->delete($id)) {
				$this->Session->setFlash(__('Folder deleted', true));
				$this->redirect(array('action'=>'view',$parent_id));
			}
			$this->Session->setFlash(__('Folder was not deleted', true));
			$this->redirect(array('action' => 'view', $parent_id));
		}
	}
	
}
