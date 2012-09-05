<?php

$conditions = array();

$req_fields = array(
  'target' => array('default' => '#target'),
	'index_tmpl' => array('default' => '#index_tmpl'),
	'entry_tmpl' => array('default' => '#entry_tmpl'),
	'max_teaser_len' => array('default' => 300),
	'per_page' => array('default' => 5)
);

$p = CMSHelper::embedParams($params, $req_fields);
$p['cur_page'] = array_key_exists('page', $nurl_params) ? $nurl_params['page'] : 0;


if (isset($params['blog_title'])) {
	$conditions['Blog.title'] = $params['blog_title'];
} elseif (isset($params['blog_id'])) {
	$conditions['blog_id'] = $params['blog_id'];
}

$options = array('conditions' => $conditions);


//$options['limit'] = $p['per_page'];
//$options['offset'] = $p['cur_page'] * $p['per_page'];
//if ($p['per_page'] !== false) {
//	$options['limit'] = $p['per_page'];
//}

$limit = $p['per_page'];
$offset = $p['cur_page'] * $p['per_page'];

$append_to_head = '';

if (!isset($this->Blogpost)) {
	ClassRegistry::init('Blogpost');
	$this->Blogpost = new Blogpost();
}

$entry_template = CMSHelper::getTemplate($p['entry_tmpl'], $page['Page']['copy']);
$index_template = CMSHelper::getTemplate($p['index_tmpl'], $page['Page']['copy']);

$blogposts = $this->Blogpost->find('all', $options);
$js_posts = array();

$to_show = false;
$post_id = false;
if (count($url_params) > 0) {
	$post_id = $url_params[0];
}

$post_cnt = 0;
foreach ($blogposts as $idx => $post) {
	
	
	$bp = $post['Blogpost'];

	$bp['posted'] = CMSHelper::fmtDate(max(strtotime($bp['created']), strtotime($bp['modified'])));
	unset($post['Author']['password']);
	$bp['Author'] = $post['Author'];
	$bp['__permalink'] = $this->Blogpost->permaLink($current_path, $bp);
	$bp['teaser'] = CMSHelper::textTrunc($bp['content'], $p['max_teaser_len']) . '&hellip;';
	
	$bp = CMSHelper::urlEncodeRecords($bp);
	if ($post_cnt >= $offset && $post_cnt < $offset + $limit) {
		$js_posts[] = $bp;
	}
	
	
	//if (isset($request['id']) && $request['id'] == $bp['id']) {
	if (isset($post_id) && $post_id == $bp['id']) {
		$to_show = $bp;
	}
	
	$post_cnt++;
}


//echo $this->Html->script('blog');

if ($to_show == false) {
	$tdata = new stdClass();
	$tdata->posts = $js_posts;
	
	if ($p['cur_page'] > 0) {
		$tdata->prev_page = $page['Page']['path'] . '/page:' . ($p['cur_page'] - 1);
	}
	
	if ($p['cur_page'] < ceil(count($blogposts) / $p['per_page']) - 1) {
		$tdata->next_page = $page['Page']['path'] . '/page:' . ($p['cur_page'] + 1);
	}
	
	echo $Mustache->render($index_template, $tdata);
} else {
	$this->set('special_title', $to_show['title']);
	$this->set('meta_description', $to_show['content']);
	echo $Mustache->render($entry_template, $to_show);
}

$append_to_head .= "<link id=\"linkimage\" type=\"image/jpeg\" rel=\"image_src\" href=\"/images/trashed-bike.jpg\" />\n";
$this->set('append_to_head', $append_to_head);

?>