<?php

require_once('javascript/js.php');
require_once('tmpl.js.php');

$js = <<<__JS
{$tmpl}

var my_tmpl = '<h1><%= name %></h1>';
var dat = {'name':'Don'};
print(process(my_tmpl, dat));


__JS;

js::run($js);


?>