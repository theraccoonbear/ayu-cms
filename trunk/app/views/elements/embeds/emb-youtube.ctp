<?php
$video_id = isset($params['video_id']) ? $params['video_id'] : false;
$width = isset($params['width']) ? $params['width'] : '560';
$height = isset($params['height']) ? $params['height'] : '315';
$start_at = isset($params['start_at']) ? $params['start_at'] : false;
$auto_play = isset($params['auto_play']) && $params['auto_play'] == 'true'? '&autoplay=1' : '';

if ($start_at === false) {
	$start_at = '';
} else {
	if (preg_match('/^(\d+):(\d+)$/i', $start_at, $matches)) {
		$start_at = '#' . $matches[1] . 'm' .  $matches[2] . 's';
	} elseif (preg_match('/^#?\d+m\d+s$/i', $start_at)) {
		$start_at = preg_match('/#/', $start_at) ? $start_at : '#' . $start_at;
	} else {
		$start_at = '';
	}
}

if ($video_id == false) {
	echo "<!-- Youtube embed failed. No video ID specified in embed tag -->\n";
} else {
?><iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="http://www.youtube.com/embed/<?php echo $video_id; ?><?php echo $start_at; ?>" frameborder="0" allowfullscreen></iframe>
<?php
}