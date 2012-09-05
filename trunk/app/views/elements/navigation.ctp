<?php

$int_options = array(
  'navBefore'   => '<ul class="navigation">',
	'navAfter'    => '</ul><!-- /.navigation -->',
	'navEntry'    => '<li><a href="/{{url}}/">{{title}}</a></li>'
);

echo $int_options['navBefore'];


foreach ($options['items'] as $page) {
	$entry = $int_options['navEntry'];
	$offset = 0;
	$match_count = 0;
	while(preg_match('/\{\{([^\}]+?)\}\}/i', $int_options['navEntry'], $matches, PREG_OFFSET_CAPTURE, $offset)) {
		$match_count++;

		$match_start = $matches[0][1];
		$match_length = strlen($matches[0][0]);

    $field = $matches[1][0];
    $entry = preg_replace('/\{\{' . $field . '\}\}/i', $page['Page'][$field], $entry);
		$offset = $match_start + $match_length;
	}
	echo $entry;
}

echo $int_options['navAfter'];
?>