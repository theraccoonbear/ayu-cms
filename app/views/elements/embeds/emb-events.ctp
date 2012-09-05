<?php

if (!isset($this->Event)) {
	ClassRegistry::init('Event');
	$this->Event = new Event();
}

$show_event = false;
//if (isset($request['id'])) {
if (count($url_params) > 0) {
	$show_event = $url_params[0]; //$request['id'];
}

$target = isset($params['target']) ? $params['target'] : '#target';
$index_tmpl = isset($params['index_tmpl']) ? $params['index_tmpl'] : '#index_tmpl';
$entry_tmpl = isset($params['entry_tmpl']) ? $params['entry_tmpl'] : '#entry_tmpl';

$entry_template = CMSHelper::getTemplate($entry_tmpl, $page['Page']['copy']);

echo $this->Html->css('fullcalendar');
echo $this->Html->css('fullcalendar.print');
echo $this->Html->css('/fancybox/jquery.fancybox-1.3.4');
echo $this->Html->script('fullcalendar.min');
echo $this->Html->script('/fancybox/jquery.fancybox-1.3.4.pack');
echo $this->Html->script('/fancybox/jquery.easing-1.3.pack');

$this->set('head_attributes', 'profile="http://microformats.org/profile/hcalendar"');

?>

<div class="semanticEvents vcalendar">
<?php

$events = $this->Event->find('all');
$js_events = array();

foreach ($events as $event) {
	$ev = $event['Event'];
	unset($ev['modified']);
	unset($ev['created']);
	$ev['start'] = $ev['date'];
	$ev['gmt'] = gmdate("Ymd\THi00\Z", strtotime($ev['start']));
	$ev['close_script'] = '</script>';
	
	$ev = CMSHelper::urlEncodeRecords($ev);
	$ev['__permalink'] = 'http://' . $_SERVER['SERVER_NAME'] . $current_path . $ev['id'] . '/' . CMSHelper::urlifyTitle($ev['title']);
	$ev['long_date'] = date('D. M. j, Y @ h:i a', strtotime($ev['start']));
	$ev['short_date'] = date('M. j', strtotime($ev['start']));
	$ev['availability'] =  $ev['capacity'] == 0 ? 'Open': ($ev['capacity'] == $ev['enrolled'] ? 'Full' : (($ev['capacity'] - $ev['enrolled']) . ' of ' . $ev['capacity'] . ' openings'));
	
	if ($show_event !== false && $show_event == $ev['id']) {
		$ev['utctime'] = date('c', strtotime($ev['date']));
		$show_event = $ev;
	}
	
	$js_events[] = $ev;

	if (strtotime($ev['date']) >= time()) {
	?>
	<span class="vevent">
		<span class="summary"><?php echo $ev['title']; ?></span>
		on <span class="dtstart"><?php echo date('c', strtotime($ev['date'])); ?></span> 
		at <span class="location"><?php echo $ev['address'] . ', ' . $ev['city'] . ', WI'; ?></span>.
		<span class="description"><?php echo $ev['description']; ?></span>
		<span class="organizer"><?php echo $ev['organizer']; ?></span>
		<span class="contact"><?php echo $ev['contact']; ?></span>
	</span>
	<?php
	} // future?
}

?>

</div>

<?php

echo $this->Html->script('events');

if ($show_event != false) { // show entry
	?><h3><a href="<?php echo $current_path; ?>">&lt;&lt; Back to the events calendar</a></h3>
	<?php
	echo $Mustache->render($entry_template, $show_event);
} else { // show calendar
	?>
<script type="text/javascript">
  eventsController.ready = function() {
		eventsController.addEvents(<?php echo json_encode($js_events); ?>);
		eventsController.init('<?php echo $target; ?>', '<?php echo $entry_tmpl; ?>');
	};
</script>
<?php
} // calendar -or- entry?
?>