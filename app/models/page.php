<?php
class Page extends AppModel {
	var $name = 'Page';
	var $displayField = 'title';
	var $primaryKey = 'id';
	
	var $actsAs = array('Ordered' => array('field'=>'order','foreign_key'=>'page_id'));
	
	var $order = array("Page.order" => "asc");
	
	var $errors = array();

	var $belongsTo = array(
		'ParentPage' => array(
			'className' => 'Page',
			'foreignKey' => 'page_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BackgroundAsset' => array(
			'className' => 'Asset',
			'foreignKey' => 'background_id'
		)
	);

	var $hasMany = array(
		'ChildPages' => array(
			'className' => 'Page',
			'foreignKey' => 'page_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'order',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	var $validate = array(
		'url' => array(
			'rule' => array('checkURL'),
			'message' => 'Only letters, numbers, and dashes are allowed in a URL and the URL must be unique amongst its siblings'
		)
	);
	
	var $indent = 0;
	var $call_depth = 0;
	var $rtc = array();
	var $manual_recursion_block = false;
	var $block_recursion = false;
	var $in_breadcrumb = false;
	var $page_templates = false;
	
	var $ppage = false;
	
	function checkURL($data) {
		$is_good = true;
		
		$is_good = $is_good && preg_match('/^[A-Za-z0-9-]*$/i', $data['url']);
		if (!$is_good) {
			$this->invalidate('url', 'Only letters, numbers, and dashes are allowed in a URL.');
			return true;
		}
		
		$ppage = new Page();
		
		$parent = $ppage->read(null, $this->data['Page']['page_id']);
		
		$edit_id = isset($this->data['Page']) && isset($this->data['Page']['id']) ? $this->data['Page']['id'] : -1;
		
		if (isset($parent['ChildPages'])) {
			foreach ($parent['ChildPages'] as $key => $page) {
				$is_good = $is_good && ($data['url'] != $page['url'] || ($edit_id == $page['id']));
				if (!$is_good) {
					$this->invalidate('url', 'This page has a sibling page called "' . $page['title'] . '" that is already using the URL "' . $data['url'] . '".');
					return true;
				}
			}
		}
		
		return $is_good;
	} // checkURL()
	
	function getPath($id) {
		$breadcrumbs = $this->getBreadcrumbs($id);
		$page_path = '';
			
		foreach ($breadcrumbs as $crumb) {
			if (strlen($crumb['Page']['url']) > 0) {
				$page_path .= '/' . $crumb['Page']['url'];
			}
		}
		
		return $page_path;
	} // getPath()
	
	function getBreadcrumbs($id) {
		//if ($this->ppage === false) {
		//	$this->ppage = new Page();
		//}
		
		//$page = $this->ppage->read(null, $id);
		$page = $this->read(null, $id);
		
		$crumbs = array($page);
		
		if (isset($page['ParentPage']) && $page['Page']['root'] != 1) {
			$crumbs = array_merge($this->getBreadcrumbs($page['ParentPage']['id']), $crumbs);
		}
		
		return $crumbs;
	} // getBreadcrumbs()
	
	function getStructureFrom($id, $recursion_cap = false, $include_hidden = false) {
		$this->call_depth++;
		
		
		$obj = $this->read(null, $id);
		$obj['ChildPages'] = array();
		
		$conditions = array();
		$conditions['Page.page_id'] = $id;
		if (!$include_hidden) {
			$conditions['Page.visible'] = 1;
		}
		
		
		$nodes = $this->find('all', array('conditions'=>$conditions,'recursive'=>1));
		
		for ($i = 0; $i < count($nodes); $i++) {
			$node = $nodes[$i];
			if (($recursion_cap === false || $recursion_cap >= $this->call_depth) && isset($node['Page'])) {
				$obj['ChildPages'][] = $this->getStructureFrom($node['Page']['id'], $recursion_cap, $include_hidden);
			}
		}
		$this->call_depth--;
		return $obj;
	} // getStructureFrom()
	
	function read($fields = null, $id = null) {
		if (array_key_exists($id, $this->rtc)) {
			$page = $this->rtc[$id];
		} else {
			$page = parent::read($fields, $id);
			$this->rtc[$id] = $page;
		}
		return $page;
	} // read()
	
	function create() {
		$this->block_recursion = true;
		$ret_val = parent::create();
		$this->block_recursion = false;
		return $ret_val;
	} // create()
	
	function save($data) {
		$this->block_recursion = true;
		$ret_val = parent::save($data);
		$this->block_recursion = false;
		return $ret_val;
	} // save()
	
	function getPageTemplates($refresh = false) {
		if ($this->page_templates === false || $refresh !== false) {
			$tmpl_dir = ROOT . "/page-templates";
			$dir_hdl = opendir($tmpl_dir);
			while (false !== ($file = readdir($dir_hdl))) {
				//if (preg_match('/\.ctp$/', $file)) {
				if (preg_match('/\.html$/', $file)) {
					$this->page_templates[] = $file;
				}
			}
		}
		return $this->page_templates;
	}
	
	function afterFind($results) {
		if ($this->block_recursion || $this->manual_recursion_block) { return $results; }
		$this->block_recursion = true;
		
		if (!array_key_exists(0, $results)) {
			$results = array($results);
		}
		
		foreach ($results as $key => $page) {
			if (isset($page['Page'])) {
				$results[$key]['Page']['path'] = '';
				if (isset($page['Page']['id']) && $page['Page']['id'] > 0) {
					$results[$key]['Page']['path'] = $this->getPath($page['Page']['id']);
				}
			}
		}
		
		$this->block_recursion = false;
		return $results;
	} // afterFind()

}
