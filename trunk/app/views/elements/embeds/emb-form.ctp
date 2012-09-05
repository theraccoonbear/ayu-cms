<?php

$req_fields = array(
  'id' => array('default' => 'SampleForm'),
	'end' => array('default' => false),
	'redirect' => array('default' => '/'),
	'index1' => array('default' => false),
	'index2' => array('default' => false),
	'index3' => array('default' => false),
	'email_template' => array('default' => false),
	'submit_handler' => array('default' => 'forms_controller.defaultSubmitHandler'),
	'response_handler' => array('default' => 'forms_controller.defaultResponseHandler'),
	'save' => array('default' => true, 'isa' => 'boolean')
);



$p = CMSHelper::embedParams($params, $req_fields);

$indices = array();
if ($p['index1'] != false) { $indices[] = $p['index1']; }
if ($p['index2'] != false) { $indices[] = $p['index2']; }
if ($p['index3'] != false) { $indices[] = $p['index3']; }

if ($p['end'] == true) {
  echo "</form>\n";
} else {
?>
<script type="text/javascript">

var forms_controller = {};

$(function() {

	forms_controller = {
		formName: '<?php echo $p['id']; ?>',
		indices: <?php echo json_encode($indices); ?>,
		post_to: '<?php echo $this->Html->url(array('controller'=>'submissions','action'=>'post')); ?>',
		redirect: '<?php echo $p['redirect']; ?>',
		defaultResponseHandler: function(data, status, xhr) {
			if (data.success) {
				window.location = forms_controller.redirect;
			} else {
				alert("There was a problem submitting your form!");
			}
		},
		doSubmission: function(data) {
			$.post(forms_controller.post_to, data, <?php echo $p['response_handler']; ?>, 'json')
		},
		defaultSubmitHandler: function(e) {
			var to_send = {
				form: forms_controller.formName,
				fields: {},
				indices: forms_controller.indices,
				__fdata: $('#__fdata').val()
			};
			var no_id = 0;
			var $fields = $('#' + forms_controller.formName).find('input,select,textarea')
			var fld_cnt = $fields.length;
			$fields.each(function(idx, val) {
				var id = $(val).attr('id');
				
				var value = $(val).val();
				if (typeof id == 'undefined') {
					id = 'noID' + (no_id++);
				}
				if (id != '__fdata') {
					to_send.fields[id] = value;
				}
				if (!--fld_cnt) {
					forms_controller.doSubmission(to_send);
				}
			});
			
			return false;
		} // defaultSubmitHandler()
	}; // forms_controller
	
	
	
	$('form.embForm').submit(function(e) {
		e.preventDefault();
		return <?php echo $p['submit_handler']; ?>(e);
	});
});
</script>
<form method="post" action="<?php echo $this->Html->url(array('controller'=>'submissions','action'=>'post')); ?>" id="<?php echo $p['id']; ?>" name="<?php echo $p['id']; ?>" class="embForm">
<?php
$fdata = array();

if (isset($p['sendto'])) {
  $fdata['sendto'] = split(',', $p['sendto']);
}

$fdata['save'] = $p['save'];
$fdata['email_template'] = $p['email_template'];

?><input type="hidden" name="__fdata" id="__fdata" value="<?php echo CMSHelper::fdataCipher($fdata); /*base64_encode(Security::cipher(json_encode($fdata), Configure::read('Security.salt')));*/ ?>" /><?php

} // open or close form

?>