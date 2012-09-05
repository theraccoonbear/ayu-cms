<?php

$req_fields = array(
  'search' => array('default' => 'kitty cats'),
	'api_key' => array('default' => false),
	'api_secret' => array('default' => false),
	'photo_template' => array('default' => false),
	'target' => array('default' => '#defaultFlickrTarget'),
	'user_id' => array('default' => false)
);

$p = CMSHelper::embedParams($params, $req_fields);

$photo_tmpl = CMSHelper::getTemplate($p['photo_template'], false);

if (!$p['api_key'] || !$p['api_secret']) {
  ?><h2>No Flickr API Key/Secret Specified</h2><?php
} else { // end of no key/secret

?>
<div id="defaultFlickrTarget"></div>
<?php

echo $this->Html->css('/fancybox/jquery.fancybox-1.3.4');
echo $this->Html->script('/fancybox/jquery.fancybox-1.3.4.pack');
echo $this->Html->script('/fancybox/jquery.easing-1.3.pack');
echo $this->Html->script('flickr');

?>
<script type="text/javascript">
  flickrController.ready = function() {
		flickrController.init({
			'api_key': '<?php echo $p['api_key']; ?>',
			'api_secret': '<?php echo $p['api_secret']; ?>',
			'target': '<?php echo $p['target']; ?>',
		  'photo_tmpl': "<?php
				$photo_tmpl = str_replace('"', '\\"', $photo_tmpl);
				$photo_tmpl = preg_replace("/[\r\n]+/", "\\\n", $photo_tmpl);
				echo $photo_tmpl;
				
			?>"
		});
		flickrController.search('<?php echo $p['search']; ?>');
	};
</script>


<?php

} // api key/secret?

?>