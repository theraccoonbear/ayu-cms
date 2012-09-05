<?php
class AssetsController extends AppController {
  var $helpers = array('Form', 'Html', 'CMS');
	var $name = 'Assets';

  function beforeFilter() {
		parent::beforeFilter();
		$this->set('breadcrumb_anchor', $this->Html->link(__('Assets', true), array('controller'=>'assetdirs','action'=>'index')));
	}

	function index() {
		$this->Asset->recursive = 0;
		$this->set('assets', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid asset', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('asset', $this->Asset->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			
			
			if (!file_exists($this->data['Asset']['file']['tmp_name'])) {
				$this->Session->setFlash(__('Asset upload failed.  Max upload: ' . ini_get('upload_max_filesize'), true));
				$this->redirect(array('controller'=>'assetdirs','action'=>'view',0));
			}
			
			$src_file = urldecode($this->data['Asset']['file']['name']);
			$src_path = $this->data['Asset']['file']['tmp_name'];
			
			
			$this->data['Asset']['name'] = $src_file;
			$this->data['Asset']['size'] = $this->data['Asset']['file']['size'];
			$this->data['Asset']['type'] = $this->data['Asset']['file']['type'];
			
			if (preg_match('/^image\//i', $this->data['Asset']['type'])) {
				$dimensions = getimagesize($src_path);
				if (count($dimensions) >= 2) {
					$this->data['Asset']['width'] = $dimensions[0];
					$this->data['Asset']['height'] = $dimensions[1];
				}
			}
			
			$this->Asset->create();
			if ($this->Asset->save($this->data)) {
				
				$asset_id = $this->Asset->getLastInsertID();
				$dest_file = 'Asset-' . sprintf('%05d', $this->Asset->id) . '_' . preg_replace('/[^-A-Za-z0-9.]+/i', '-', $src_file);
				$this->data['Asset']['id'] = $asset_id;
				$this->data['Asset']['filename'] = $dest_file;
				$this->Asset->save($this->data);
				
				$dest_file = ASSET_ROOT . ASSET_UPLOAD_DIR . '/' . $dest_file;
				
				move_uploaded_file($src_path, $dest_file);
				
				if (file_exists($dest_file)) {
					$this->Session->setFlash(__('The asset has been saved', true));
				} else {
					$this->Asset->delete($id, true);
					//$this->Session->setFlash(__('Error copying uploaded asset.  Check asset upload directory permissions', true));
					$this->Session->setFlash($dest_file);
				}
				$this->redirect(array('controller'=>'assetdirs','action' => 'view',$this->data['Asset']['assetdir_id']));
			} else {
				$this->Session->setFlash(__('The asset could not be saved. Please, try again.', true));
			}
		} else {
			if (isset($id)) {
				$asset_dir = $id;
			} else {
				$this->Session->setFlash('No folder specified for asset');
				$this->redirect(array('controller'=>'assetdirs','action'=>'view',0));
			}
			$this->set(compact('asset_dir'));
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid asset', true));
			$this->redirect($this->referer());
		}
		if (!empty($this->data)) {
			$asset_file = $this->Asset->absPath($this->data['Asset']['id']);
			
			$cur_asset = $this->Asset->read(null, $id);
			
			$message = 'Asset updated';
			
			if ($this->data['Asset']['fileChangeMethod'] == 'edit') {
				file_put_contents($asset_file, $this->data['Asset']['new_content']);
			} else {
				$src_path = $this->data['Asset']['new_file']['tmp_name'];
				if (file_exists($src_path)) {
					$this->data['Asset']['type'] = $this->data['Asset']['new_file']['type'];
					$this->data['Asset']['name'] = urldecode($this->data['Asset']['new_file']['name']);
					$dest_file = ASSET_ROOT . ASSET_UPLOAD_DIR . '/' . $cur_asset['Asset']['filename'];;
					if (file_exists($dest_file)) {
						unlink($dest_file);
					}
					
					if (preg_match('/^image\//i', $this->data['Asset']['type'])) {
						$dimensions = getimagesize($src_path);
						
						if (count($dimensions) >= 2) {
							$this->data['Asset']['width'] = $dimensions[0];
							$this->data['Asset']['height'] = $dimensions[1];
						}
					}
					
					move_uploaded_file($src_path, $dest_file);
					$message = 'Asset replaced';
				}
			}
			
			$this->data['Asset']['size'] = filesize($asset_file);
			
			
			
			if ($this->Asset->save($this->data)) {
				$this->Session->setFlash(__($message, true));
				$this->redirect(array('action' => 'view', $this->data['Asset']['id']));
			} else {
				$this->Session->setFlash(__('The asset could not be saved. Please, try again.', true));
			}
		}
		$content = '';
		if (empty($this->data)) {
			$this->data = $this->Asset->read(null, $id);
			$content = file_get_contents($this->Asset->absPath($id));
			$this->data['Asset']['new_content'] = $content;
		}
		
		$this->set('content', $content);
		$assetdirs = $this->Asset->Assetdir->find('list');
		$this->set(compact('assetdirs'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for asset', true));
			$this->redirect(array('action'=>'index'));
		}
		
		$a = $this->Asset->read(null, $id);
		
		$bound_count = count($a['Page']);
		
		if ($bound_count > 0) {
			$this->Session->setFlash(__('An asset cannot be deleted until it is no longer bound to a page or other object', true));
			$this->redirect($this->referer());
		} else {
			$asset_path = $this->Asset->absPath($id); //ASSET_ROOT . ASSET_UPLOAD_DIR . '/' . $a['Asset']['filename'];
			if ($this->Asset->delete($id, true)) {
				if (file_exists($asset_path)) {
					unlink($asset_path);
				}
				
				$this->Session->setFlash(__('Asset deleted', true));
				$this->redirect(array('controller'=>'assetdirs','action'=>'view',$a['Asset']['assetdir_id']));
			}
			$this->Session->setFlash(__('Asset was not deleted', true));
			$this->redirect(array('action' => 'index'));
		}
	}

  function fetch($id = null) {
		if (!$id) { 
			$this->redirect('/');
		}
		
		
		$asset = $this->Asset->read(null, $id);
		$path = $this->Asset->absPath($id);
		
		$p = $this->params['named'];
		
		$nw = 0;
		$nh = 0;
		$thumb = false;
		
		if (file_exists($path)) {
			$this->layout = null;
			
			if (strpos($asset['Asset']['type'], 'image') !== false) {
				$cw = $asset['Asset']['width'];
				$ch = $asset['Asset']['height'];
				
				if (isset($p['width']) && $p['width'] >= 0) {
					$nw = $p['width'];
					$nh = ($nw * $ch) / $cw;
				} else if (isset($p['height']) && $p['height'] >= 0) {
					$nh = $p['height'];
					$nw = ($cw * $nh) / $ch;
				}
				
				if ($nw > 0 && $nh > 0) {
					$thumb = CMSHelper::imageResize($path, $nw, $nh, $asset['Asset']['type']);
				  
					
					if (is_string($thumb)) {
				    pr($thumb);exit(0);
					} else {
						$this->header('Content-Type: ' . $asset['Asset']['type']);
						if (preg_match('/^image\/p?jpe?g$/i', $asset['Asset']['type'])) {
							imagejpeg($thumb);
						} else if (preg_match('/^image\/gif$/i', $asset['Asset']['type'])) {
							imagegif($thumb);
						} else if (preg_match('/^image\/png$/i', $asset['Asset']['type'])) {
							imagepng($thumb);
						}	else {
							$this->header('Content-Length: ' . filesize($path));
							readfile($path);
						}
					}
				} else {
					$this->header('Content-Type: ' . $asset['Asset']['type']);
					$this->header('Content-Length: ' . filesize($path));
					readfile($path);
				}
			} else {
				$this->header('Content-Type: ' . $asset['Asset']['type']);
				$this->header('Content-Length: ' . filesize($path));
				readfile($path);
			}
		} else {
			$this->Session->setFlash(__('Asset not found.', true));
		}
	} 

}
