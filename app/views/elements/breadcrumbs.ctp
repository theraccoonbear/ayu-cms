<?php

$int_options = array(
  'crumbBefore'    => '<div class="breadcrumbs">',
	'crumbAfter'     => '</div><!-- /.breadcrumbs-->',
	'crumbEntry'     => '<span class="{{classes}}"><a href="{{url}}/">{{title}}</a></span>',
	'crumbDelimiter' => ' <span class="delimiter">&gt;</span> '
);

echo $int_options['crumbBefore'];
$entries = array();
$base_url = '';

foreach ($breadcrumbs as $key => $page) {
	if ($page['Page']['root'] != 1) {
		$entry = $int_options['crumbEntry'];
		$offset = 0;
		$match_count = 0;
		$page['Page']['classes'] = 'crumb';
		$page['Page']['classes'] .= $key == 0 ? ' first' : '';
		$page['Page']['classes'] .= $key == count($breadcrumbs) - 1? ' last' : '';
		$base_url .= '/' . $page['Page']['url'];
		$page['Page']['url'] = $base_url;
		
		
		while(preg_match('/\{\{([^\}]+?)\}\}/i', $int_options['crumbEntry'], $matches, PREG_OFFSET_CAPTURE, $offset)) {
			$match_count++;
	
			$match_start = $matches[0][1];
			$match_length = strlen($matches[0][0]);
	
			$field = $matches[1][0];
			$entry = preg_replace('/\{\{' . $field . '\}\}/i', $page['Page'][$field], $entry);
			$offset = $match_start + $match_length;
		}
		$entries[] = $entry;
	}
} 

echo join($int_options['crumbDelimiter'], $entries);

echo $int_options['crumbAfter'];

?>