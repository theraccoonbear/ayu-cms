<?php
class BlogpostsController extends AppController {

	var $name = 'Blogposts';

	function index() {
		App::import('Helper', 'CMS');
		$this->CMS = new CMSHelper();
		
		$this->redirect(array('controller'=>'blogs','action'=>'index'));
		
		$this->Blogpost->recursive = 0;
		$this->set('blogposts', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('blogpost', $this->Blogpost->read(null, $id));
	}

	function add($id = null) {
		if ($id == null) {
			$this->Session->setFlash('No blog specified');
			$this->redirect($this->referer());
		}
		
		$this->set('blog_id', $id);
		
		if (!empty($this->data)) {
			$this->Blogpost->create();
			if ($this->Blogpost->save($this->data)) {
				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('controller'=>'blogs','action' => 'view', $this->data['Blogpost']['blog_id']));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Post', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Blogpost->save($this->data)) {
				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('controller'=>'blogs','action' => 'view', $this->data['Blogpost']['blog_id']));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Blogpost->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for post', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Blogpost->delete($id)) {
			$this->Session->setFlash(__('post deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('post was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
