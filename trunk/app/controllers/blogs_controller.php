<?php
class BlogsController extends AppController {

	var $name = 'Blogs';

	function index() {
		$this->Blog->recursive = 1;
		$this->set('blogs', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid blog: ' . $id, true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('blog', $this->Blog->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Blog->create();
			if ($this->Blog->save($this->data)) {
				$this->Session->setFlash(__('The blog has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The blog could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid blog', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Blog->save($this->data)) {
				$this->Session->setFlash(__('The blog has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The blog could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Blog->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for blog', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Blog->delete($id)) {
			$this->Session->setFlash(__('Blog deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Blog was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
