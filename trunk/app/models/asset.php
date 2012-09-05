<?php

class Asset extends AppModel {
	var $name = 'Asset';
  
	var $displayField = 'name';
	
	var $recursive = 2;
	
	var $order = 'Upper(Asset.name)';
	
	var $belongsTo = array(
		'Assetdir' => array(
			'className' => 'Assetdir',
			'foreignKey' => 'assetdir_id'
		)
	);
	
	var $hasMany = array(
	  'Page' => array(
			'className' => 'Page',
			'foreignKey' => 'background_id'
		)
	);
	
	function absPath($id = null) {
		if ($id) {
			$asset = $this->read(null, $id);
			
			if (isset($asset['Asset'])) {
				return ASSET_ROOT . ASSET_UPLOAD_DIR . '/' . $asset['Asset']['filename'];	
			}
		}
		return '';
	}
	
	function webPath($id = null) {
		if ($id) {
			$asset = $this->read(null, $id);
			
			if (isset($asset['Asset'])) {
				return '/' . ASSET_UPLOAD_DIR . '/' . $asset['Asset']['filename'];	
			}
		}
		
		return '';
	}
	
	function updateCache() {
		$assets = $this->Assetdir->loadAll();
		file_put_contents(ASSET_ROOT . ASSET_UPLOAD_DIR . '/cache.js', json_encode($assets));
		file_put_contents(ASSET_ROOT . ASSET_UPLOAD_DIR . '/mce_cache.js', $this->ForTinyMCE());
	}
	
	function afterSave($created = false) {
		//touch('/' . ASSET_UPLOAD_DIR . '/mod-ts.tmp');
		$this->updateCache();
	}
	
	function afterDelete() {
		//touch('/' . ASSET_UPLOAD_DIR . '/mod-ts.tmp');
		$this->updateCache();
	}
	
	function ForTinyMCE($asset_dir = false, $recursive = false) {
		$cnd = array();
		
		if ($asset_dir !== false) {
			$cnd['assetdir_id'] = $asset_dir;
		}
		
		$assets = $this->find('all', array('conditions'=>$cnd));
		
		$saar = array();
		
		$mce_list = array();
		
		foreach ($assets as $idx => $asset) {
			$a = $asset['Asset'];
			$mce_list[] = '  ["' . utf8_encode($a['name']) . '", "' . utf8_encode($this->webPath($a['id'])) . "\"]";
			//$na = array($a['name'], $this->webPath($a['id']));
			//$saar[] = $na;
		}
		
		//var tinyMCEImageList = new Array(
		//				// Name, URL
		//				["Logo 1", "logo.jpg"],
		//				["Logo 2 Over", "logo_over.jpg"]
		//);
		
		//return 'var tinyMCEImageList = ' . json_encode($saar) . ';';
		return "var tinyMCEImageList = new Array(\n" . join(",\n", $mce_list) . "\n);";
	}
	
} // Asset class

?>
