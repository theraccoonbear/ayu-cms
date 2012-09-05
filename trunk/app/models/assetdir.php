<?php

class Assetdir extends AppModel {
	var $name = 'Assetdir';
  
	var $displayField = 'name';
	
	var $displayName = 'Folder';
	
	var $order = 'Upper(Assetdir.name)';
	
	var $belongsTo =  array(
		'Parent' => array(
			'className' => 'Assetdir', 
      'foreignKey'   => 'assetdir_id'
		)
	);
	
	var $hasMany =  array(
		'Children' => array( 
			'className' => 'Assetdir', 
			'foreignKey'   => 'assetdir_id'
		),
		'Asset' => array(
			'className' => 'Asset',
			'foreignKey' => 'assetdir_id'
		)
	);
	
	function loadAsset($asset) {
		$a = new stdClass();
		$a->name = $asset['Asset']['name'];
		$a->id = $asset['Asset']['id'];
		$a->type = $asset['Asset']['type'];
		$a->size = $asset['Asset']['size'];
		$a->description = $asset['Asset']['description'];
		$a->filename = $asset['Asset']['filename'];
		return $a;
	}

  function loadDir($dir, $filter = false) {
		$dirs = $this->find('all', array('conditions'=>array('Assetdir.assetdir_id'=>$dir['Assetdir']['id']), 'recursive'=>0));
		$assets = $this->Asset->find('all', array('conditions'=>array('Asset.assetdir_id'=>$dir['Assetdir']['id']), 'recursive'=>0));
		
		$node = new stdClass();
		$node->id = $dir['Assetdir']['id'];
		$node->name = $dir['Assetdir']['name'];
		$node->description = $dir['Assetdir']['description'];
		$node->folders = array();
		$node->assets = array();
		
		foreach ($dirs as $dir) {
		  $node->folders[] = $this->loadDir($dir);	
		}
		
		foreach ($assets as $asset ) {
			if ($filter == false) {
				$node->assets[] = $this->loadAsset($asset);
			} else {
				if (preg_match($filter, $asset['Asset']['type'])) {
					$node->assets[] = $this->loadAsset($asset);
				}
			}
		}
		
		return $node;
	}

  function loadAll($filter = false) {
		$dirs = $this->find('all', array('conditions'=>array('Assetdir.assetdir_id'=>0), 'recursive'=>0));
		$assets = $this->Asset->find('all', array('conditions'=>array('Asset.assetdir_id'=>0), 'recursive'=>0));
		
		$root = new stdClass();
		$root->name = 'root';
		$root->id = 0;
		$root->folders = array();
		$root->assets = array();
		
		foreach ($dirs as $dir) {
		  $root->folders[] = $this->loadDir($dir, $filter);	
		}
		
		foreach ($assets as $asset ) {
			if ($filter == false) {
				$root->assets[] = $this->loadAsset($asset);
			} else {
				if (preg_match($filter, $asset['Asset']['type'])) {
					$root->assets[] = $this->loadAsset($asset);
				}
			}
		}
		
		return $root;
	} 

	
} // Section class


?>
