<?php

class TemplatesComponent extends Object {
    
    private $controller = false;
    private $settings = array();
    
    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = $settings;
    }
    
    function renderTemplate($page) {
        
        $tmpl_idx = $page['Page']['template'];
        
        $tmpls = $this->controller->Page->getPageTemplates();
        
        $m = $this->controller->Mustache;
        
	//$page['Page']['meta-description'] = 'HEY HOW!';
	
	
	
	$vv = $this->controller->viewVars;
	$View = ClassRegistry::init('View');
	$View->viewVars = $vv;
	
	
	$tmpl_params = array(
	  'Page' => $page['Page'],
	  'Site' => array(
	    'ga-account' => $this->controller->Setting->getSetting('ga-account')
	  ),
	  'ViewVars' => $vv
	);
	
        $tmpl = file_get_contents(ROOT . '/page-templates/' . $tmpls[$tmpl_idx]);
	
	
	$output = $m->render($tmpl, $tmpl_params);
	
	if (isset($vv['_EMBEDS'])) {
	  foreach ($vv['_EMBEDS'] as $e) {
	    $embed = 'embeds/emb-' . $e['type'];
	    if (file_exists(ROOT . '/app/views/elements/' . $embed . '.ctp')) {
	      $embed_markup = $View->element($embed, array('params'=>$e['params'],'request'=>$vv['_PARAMS'],'page'=>&$page));
	      //print "***** $embed_markup *****\n";
	      $output = str_replace($e['key'], $embed_markup, $output);
	    } else {
	      $output = str_replace($e['key'], "<!-- Embed failed for type: \"{$e['type']}\".  Unknown embed type. -->\n", $output);
	    }
	  }
	}
	
	print $output;
        exit(0);
        
    }
}

?>