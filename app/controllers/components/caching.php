<?php

class CachingComponent extends Object {
	
	private function cachePath($cache_id) {
		return  TMP . 'pagecache' . DS . md5($cache_id) . '.cache';
	}
	
	function getCachedPage($cache_id) {
		return false;
		
		$file = $this->cachePath($cache_id);
		
		if (file_exists($file)) {
			return file_get_contents($file);
		} else {
			return false;
		}
	}
	
	function setCachedPage($cache_id, $contents) {
		return; 
		$file = $this->cachePath($cache_id);
		file_put_contents($file, $contents);
	}
	
	function killCache($cache_id) {
		$file = $this->cachePath($cache_id);
		unlink($file);
	}
}

?>