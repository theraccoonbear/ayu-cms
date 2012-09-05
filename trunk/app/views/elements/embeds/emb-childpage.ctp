<?php

if (!isset($this->Page)) {
	ClassRegistry::init('Page');
	$this->Page = new Page();
}

if (!isset($child_pages)) {
	$child_pages = $this->Page->find('all', array('conditions'=> array('Page.page_id' => $page['Page']['id'])));
}

//if (!isset($child_idx) || !is_int($child_idx)) {
if ($session->check('child.idx')) {
	$child_idx = $session->read('child.idx');
	if ($child_idx < 0) {
		$child_idx = 0;
	}
} else {
	$child_idx = 0;
}

$cpage = $child_pages[$child_idx];

pr($cpage['Page']);

$child_idx++;
$session->write('child.idx', $child_idx);


//pr($next_child);

//pr($this->_child_pages);


?>