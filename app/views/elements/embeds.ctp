<?php

$op = $page['Page']['copy'];

if (isset($_EMBEDS)) {
  foreach ($_EMBEDS as $e) {
		$embed = 'embeds/emb-' . $e['type'];
		if (file_exists(ROOT . '/app/views/elements/' . $embed . '.ctp')) {
			$embed_markup = ''; //"<!-- Successful embed for type: \"{$e['type']}\" -->\n";
			$embed_markup .= $this->element($embed, array('params'=>$e['params'],'request'=>$_PARAMS,'page'=>&$page));
			$op = str_replace($e['key'], $embed_markup, $op);
		} else {
			$op = str_replace($e['key'], "<!-- Embed failed for type: \"{$e['type']}\".  Unknown embed type. -->\n", $op);
		}
	}
}

echo $op;

?>