<?php
class PagesController extends AppController {
  var $helpers = array('Form', 'Html');
	var $components = array('Caching','Templates');
	
	var $name = 'Pages';

  function beforeFilter() {
		parent::beforeFilter();
		$this->set('rootID', $this->rootID);
	} 

	function index() {
		$this->layout = 'default';
		$root = $this->Page->getStructureFrom($this->rootID, false, true);
		$this->set('root', $root);
	}

	function view($id = null) {
		if (!isset($id)) {
			$this->Session->setFlash(__('Invalid page', true));
			$this->redirect(array('action'=>'view',0));
		}
		
		$page = $this->Page->read(null, $id);
		if (!isset($page['Page']['path'])) { $page['Page']['path'] = '/'; }
		$children = $this->Page->find('all', array('conditions'=>array('Page.page_id' => $id)));
		$breadcrumbs = $this->Page->getBreadcrumbs($page['Page']['id']);
		
		$this->set('page', $page);
		$this->set('children', $children);
		$this->set('breadcrumbs', $breadcrumbs);
	} // view()

	function add($parent = null) {
		$this->layout = 'default';
		if (!empty($this->data)) {
			$this->Page->create();
			if ($this->Page->save($this->data)) {
				$this->Session->setFlash(__('The page has been saved', true));
				$this->redirect(array('action'=>'view',$this->data['Page']['page_id']));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true));
			}
		} /*else {
			$pages = $this->Page->find('all', array('conditions'=>array('Page.page_id'=>0)));
		}*/
		
		$this->loadModel('Assetdir');
		$assets = $this->Assetdir->loadAll('/^image\//i');
		$templates = $this->Page->getPageTemplates();
		$this->set(compact('parent','assets','templates'));
	}

	function edit($id = null) {
	  
	  
		$this->layout = 'default';
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid page', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Page->save($this->data)) {
				$this->Session->setFlash(__('The page has been saved', true));
				$this->redirect(array('action' => 'view', $this->data['Page']['id']));
			} else {
				$this->set('page', $this->data);
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Page->read(null, $id);
			$this->set('page', $this->data);
		}
		
		$this->loadModel('Assetdir');
		$assets = $this->Assetdir->loadAll('/^image\//i');
		$templates = $this->Page->getPageTemplates();
		$this->set(compact('assets','templates'));
		
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for page', true));
			$this->redirect(array('action'=>'index'));
		}
		
		$page = $this->Page->read(null, $id);
		
		if ($this->Page->delete($id)) {
			$this->Session->setFlash(__('Page deleted', true));
			$this->redirect(array('action'=>'view', $page['Page']['page_id']));
		}
		$this->Session->setFlash(__('Page was not deleted', true));
		$this->redirect(array('action'=>'view', $page['Page']['page_id']));
	}
	
	function badPath($path) {
		$this->set('request_path', $path);
		$this->render('404');
	}
	
	function handleEmbeds(&$page) {
		$offset = 0;
		$match_count = 0;
		$embeds = array();
		
		$to_strip = array();
		
		while (preg_match('/\[\[embed:([A-Za-z-]+)\s*?([^\]]+?)?\]\]/is', $page['Page']['copy'], $matches, PREG_OFFSET_CAPTURE, $offset)) {
			$match_count++;
			$match_start = $matches[0][1];
			$match_length = strlen($matches[0][0]);
			
			$to_strip = $matches[0][0];
			
			$debug = $matches;
			$embed_type = $matches[1][0];
			$attributes = isset($matches[2]) ? $matches[2][0] : '';
			
			$element = simplexml_load_string('<element ' . $attributes . ' />');
			$params = array();
			foreach ($element->attributes() as $name => $value) {
				$params[$name] = (string)$value;
			}
			$element = array(
				'key' => 'emb' . md5(microtime() + rand(1000, 9999)),
				'type' => $embed_type,
				'params' => $params,
				'strip' => $to_strip
			);
			
			$embeds[] = $element;
			$offset = $match_start + $match_length;
		}
		
		foreach ($embeds as $e) {
			$page['Page']['copy'] = str_replace($e['strip'], $e['key'], $page['Page']['copy']);
		}
		
		
		$this->set('_EMBEDS', $embeds);
		$this->set('_PARAMS', $this->params['named']);
		
		return $embeds;
	} // handleEmbeds()
	
	function display($id = null) {
		$this->Page->getPageTemplates();
		
		
		$good_path = true;
		
		$cache = $this->Caching->getCachedPage($this->here);
		
		if ($cache === false) {
			$path = split('/', substr($this->here, 1));
			
			if ($path[count($path)-1] == '') {
				array_pop($path);
			}
			
			$breadcrumbs = array();
			
			$the_page = array();
			$url_params = array();
			
			$nav_items = $this->Page->getStructureFrom($this->rootID, 1);
			$this->set('nav_items', $nav_items['ChildPages']);
			
			if (count($path) > 0) {
				$root_node = $this->Page->find('first', array('conditions'=>array('Page.page_id'=>$this->rootID,'Page.url'=>$path[0]),'recursive'=>0));
				if (strpos($path[0], ':') > 0 || $path[0] == '') {
					$the_page = $breadcrumbs[count($breadcrumbs) - 1];
				} else {
					if (!isset($root_node['Page'])) {
						$this->badPath($path);
						$good_path = false;
					} else {
					
						$breadcrumbs[] = $root_node;
						
						$parent_node = $root_node;
						
						for ($i = 1; $i < count($path); $i++) {
							if (strpos($path[$i], ':') > 0 || $path[$i] == ''  || $parent_node['Page']['terminal'] == 1) {
								$url_params = array_slice($path, $i);
								$the_page = $breadcrumbs[count($breadcrumbs) - 1];
								break;
							} else {
								$node = $this->Page->find('first', array('conditions'=>array('Page.page_id'=>$parent_node['Page']['id'],'Page.url'=>$path[$i]), 'recursive'=>0));
								if (!isset($node['Page'])) {
									$this->badPath($path);
									$good_path = false;
									break;
								} else {
									$breadcrumbs[] = $node;
									$parent_node = $node;
								}
							}
						}
						
						
						$the_page = $breadcrumbs[count($breadcrumbs) - 1];
					}
				}
			} else {
				$the_page = $this->Page->read(null, $this->rootID);
			}
			
			$nurl_params = array();
			foreach ($url_params as $idx => $param) {
				if (strpos($param, ':') > 0) {
					$parts = preg_split('/:/', $param, 2);
					$nurl_params[$parts[0]] = $parts[1];
				}
			}
			
			$current_path = '/';
			foreach ($breadcrumbs as $idx => $crumb) {
				$current_path .= $crumb['Page']['url'] . '/';
			}
			$this->Session->delete('child.idx');
			$this->set('current_path', $current_path);
			$this->set('breadcrumbs', $breadcrumbs);
			$this->set('url_params', $url_params);
			$this->set('nurl_params', $nurl_params);
			$this->set('session', $this->Session);
			
			if (isset($the_page) && isset($the_page['Page'])) {
				if (isset($this->params['form'])) {
					$p = $this->params['form'];
					$the_page['Page']['copy'] = isset($p['copy']) ? $p['copy'] : $the_page['Page']['copy'];
					$the_page['Page']['style'] = isset($p['style']) ? $p['style'] : $the_page['Page']['style'];
				}
				
				$embeds = $this->handleEmbeds($the_page);
				
			}
	
			//$this->set('page', $the_page);
			//$this->set('Site', array('ga_account'=>$this->Setting->getSetting('ga_account')));
			//$this->layout = '/page-templates/' . $this->Page;
			$this->Templates->renderTemplate($the_page);
			//$this->render('display', $the_page['Page']['template']);
			//$this->Caching->setCachedPage($this->here, $this->render());
		} else {
			echo $cache; exit(0);
		}
	} // display()
	
	function moveup($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Page', true));
		} else {
			$this->Page->manual_recursion_block = true;
			if ($this->Page->moveup($id)) {
				$this->Session->setFlash(__('Page moved up', true));
			} else {
				$this->Session->setFlash(__('Page move failed: ' . implode(', ', $this->Page->errors), true));
			}
			$this->Page->manual_recursion_block = false;
		}
		
		$this->redirect($this->referer());
	}
	
	function movedown($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Page', true));
		} else {
			$this->Page->manual_recursion_block = true;
			if ($this->Page->movedown($id)) {
				$this->Session->setFlash(__('Page moved down', true));
			} else {
				$this->Session->setFlash(__('Page move failed: '  . implode(', ', $this->Page->errors), true));
			}
			$this->Page->manual_recursion_block = false;
		}
		
		$this->redirect($this->referer());
	}
	
}
