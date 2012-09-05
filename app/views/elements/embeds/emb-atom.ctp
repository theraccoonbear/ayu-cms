<?php
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";

$this->layout = 'atom';


$req_fields = array(
  'blog_id' => array('default' => false),
	'blog_name' => array('default' => false),
	'root' => array('default' => '/blog/')
);

$p = CMSHelper::embedParams($params, $req_fields);

if (!isset($this->Blogpost)) {
	ClassRegistry::init('Blogpost');
	$this->Blogpost = new Blogpost();
}

if (!isset($this->Blog)) {
	ClassRegistry::init('Blog');
	$this->Blog = new Blog();
}

$blog_cond = array();
if ($p['blog_name'] !== false) {
	$blog_cond['Blog.title'] = $p['blog_name'];
} else {
	$blog_cond['Blog.id'] = $p['blog_id'];
}
$blog = $this->Blog->find('first', array('conditions'=>$blog_cond));
$blog_updated = 0;
$blog_author = array('username'=>'Unknown','email'=>'unknown@hostname.com');
foreach ($blog['Blogpost'] as $idx => $post) {
	$bp = $post;
	$create_date = strtotime($bp['created']);
	$mod_date = strtotime($bp['modified']);
	$post_date = max($create_date, $mod_date);
	if ($post_date > $blog_updated) {
		$blog_updated = $post_date;
		$blog_author = $bp['Author'];
	}
}

$blog_updated = gmdate("Y-m-d\TH:i:s\Z", $blog_updated);

?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title><?php echo $blog['Blog']['title']; ?></title>
	<link href="<?php echo $p['root']; ?>" type="text/html" />
	<link href="<?php echo 'http://' . $_SERVER['SERVER_NAME'] . $this->here; ?>" type="application/atom+xml" rel="self" />
	<id>http://<?php echo $_SERVER['SERVER_NAME'] . $p['root']; ?></id>
	<updated><?php echo $blog_updated; ?></updated>
	<author>
		<name><?php echo $blog_author['username']; ?></name>
		<email><?php echo $blog_author['email']; ?></email>
	</author>
<?php



$conditions = array();

if ($p['blog_name'] !== false) {
	
	$conditions['Blog.title'] = $p['blog_name'];
	//$posts = $this->Blogpost->find('all', array('conditions'=>$conditions, 'recursive'=>2));
	foreach ($blog['Blogpost'] as $idx => $post) {
		$bp = $post; //['Blogpost'];
		$bp['__permalink'] = $this->Blogpost->permaLink($p['root'], $bp);
		$create_date = strtotime($bp['created']);
		$mod_date = strtotime($bp['modified']);
		$updated_date = max($create_date, $mod_date);
		$updated = gmdate("Y-m-d\TH:i:s\Z", $updated_date);
		
		$content = CMSHelper::textTrunc($bp['content'], 150);
		if (strlen($content) < strlen($bp['content'])) { $content .= '&hellip;'; }
		?>
	<entry>
		<title><?php echo $bp['title']; ?></title>
		<link href="<?php echo $bp['__permalink']; ?>" />
		<id><?php echo $bp['__permalink']; ?></id>
		<updated><?php echo $updated; ?></updated>
		<summary type="html"><![CDATA[<?php echo $content; ?>]]></summary>
		<author>
			<name><?php echo $bp['Author']['username']; ?></name>
			<email><?php echo $bp['Author']['email']; ?></email>
		</author>
	</entry>
<?php
		
	}
} elseif ($p['blog_id'] !== false) {
	
} else {
	
}

?>
</feed>