<?php
class SubmissionsController extends AppController {

	var $name = 'Submissions';

	function index() {
		$this->Submission->recursive = 0;
		
		//pr($this); exit(0);
		$cond = array();
		$formFilter = '';
		if (isset($this->params['named']['formFilter']) ) {
			$formFilter = $this->params['named']['formFilter'];
			if (strlen($formFilter) > 0) {
				$cond = array('Submission.form' => $formFilter);
			}
		}
		
		
		$submissions = $this->Submission->find('all');
		
		$form_names = array();
		
		foreach ($submissions as $sub) {
			$s = $sub['Submission'];
			$form_names[$s['form']] = isset($form_names[$s['form']]) ? $form_names[$s['form']] + 1 : 1;
		}
		
		$this->set('formFilter', $formFilter);
		$this->set('submits', count($submissions));
		$this->set('forms', $form_names);
		$this->set('submissions', $this->paginate('Submission', $cond));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid submission', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('submission', $this->Submission->read(null, $id));
	}

	function add() {
		$this->redirect($this->referer());
		
		//if (!empty($this->data)) {
		//	//pr($this->data); exit(0);
		//	$this->Submission->create();
		//	if ($this->Submission->save($this->data)) {
		//		$this->Session->setFlash(__('The submission has been saved', true));
		//		$this->redirect(array('action' => 'index'));
		//	} else {
		//		$this->Session->setFlash(__('The submission could not be saved. Please, try again.', true));
		//	}
		//}
	}

	function edit($id = null) {
		$this->redirect($this->referer());
		
		//if (!$id && empty($this->data)) {
		//	$this->Session->setFlash(__('Invalid submission', true));
		//	$this->redirect(array('action' => 'index'));
		//}
		//if (!empty($this->data)) {
		//	if ($this->Submission->save($this->data)) {
		//		$this->Session->setFlash(__('The submission has been saved', true));
		//		$this->redirect(array('action' => 'index'));
		//	} else {
		//		$this->Session->setFlash(__('The submission could not be saved. Please, try again.', true));
		//	}
		//}
		//if (empty($this->data)) {
		//	$this->data = $this->Submission->read(null, $id);
		//}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for submission', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Submission->delete($id)) {
			$this->Session->setFlash(__('Submission deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Submission was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function sendTo($form, $to, $from, $subject = 'Form Submited', $tmpl = false) {
		$this->Email->from = $from;
		$this->Email->to = $to;
		$this->Email->subject = '[Form] : ' . $form['form'];
		$this->Email->sendAs = 'html';
		$message = '';
		
		if ($tmpl !== false) {
			$template = CMSHelper::getTemplate($tmpl);
		  $message = $this->Mustache->render($template, $form);
		} else {
			
			$message .= "{$form['form']} submitted on " . date('l jS \of F Y h:i:s A', time()) . "\n";
			$message .= "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
			
			foreach ($form['fields'] as $fname => $fvalue) {
				$message .= "  {$fname}: {$fvalue}\n";
			}
		}
		$this->Email->send($message);
	}
	
	function post($ajax = true) {
		$o = new stdClass();
		
		$p = $this->params['form'];
		
		$data = array(
			'Submission'=>array(
				'form' => $p['form'],
				'index1' => isset($p['indices'][0]) && isset($p['fields'][$p['indices'][0]]) ? $p['fields'][$p['indices'][0]] : '',
				'index2' => isset($p['indices'][1]) && isset($p['fields'][$p['indices'][1]]) ? $p['fields'][$p['indices'][1]] : '',
				'index3' => isset($p['indices'][2]) && isset($p['fields'][$p['indices'][2]]) ? $p['fields'][$p['indices'][2]] : '',
				'contents' => json_encode($p['fields'])
				)
			);
		
		if (isset($p['__fdata'])) {
			$p['__fdata'] = CMSHelper::fdataCipher($p['__fdata'], true); //Security::cipher(base64_decode($p['fdata']), Configure::read('Security.salt'));
		} else {
			$p['__fdata'] = array();
		}
		
		$response = new stdClass();
		$response->success = false;
		$response->fdata = $p['__fdata'];
		//$response->p = $p;
		//$response->debug = array();
		
		//pr($p); exit(0);
		
		if (isset($p['__fdata']->sendto)) {
			foreach ($p['__fdata']->sendto as $idx => $recip) {
				$this->sendTo($p, $recip, '"Revolution Cycles" <test@revolutioncycles.net>', 'Form Submitted', $p['__fdata']->email_template);
				//$response->debug[] = "Sent to {$recip}";
			}
		}
		
		if (!empty($data) && $p['__fdata']->save != 'false') {
			$this->Submission->create();
			if ($this->Submission->save($data)) {
				$response->success = true;
			} else {
				$response->data = print_r($data, true);
				$response->validationErrors = $this->validationErrors;
			}
		}
		
		$this->header('Content-Type: application/x-json');
		$this->layout = null;
		$this->set('response', $response);
	}
}
