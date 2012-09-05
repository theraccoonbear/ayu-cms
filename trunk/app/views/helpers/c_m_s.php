<?php

class CMSHelper extends AppHelper {
	var $helpers = array('Html');
  
	static function indent($json) {
		 $result      = '';
		 $pos         = 0;
		 $strLen      = strlen($json);
		 $indentStr   = '  ';
		 $newLine     = "\n";
		 $prevChar    = '';
		 $outOfQuotes = true;
 
		 for ($i=0; $i<=$strLen; $i++) {
 
				 // Grab the next character in the string.
				 $char = substr($json, $i, 1);
 
				 // Are we inside a quoted string?
				 if ($char == '"' && $prevChar != '\\') {
						 $outOfQuotes = !$outOfQuotes;
				 
				 // If this character is the end of an element, 
				 // output a new line and indent the next line.
				 } else if(($char == '}' || $char == ']') && $outOfQuotes) {
						 $result .= $newLine;
						 $pos --;
						 for ($j=0; $j<$pos; $j++) {
								 $result .= $indentStr;
						 }
				 }
				 
				 // Add the character to the result string.
				 $result .= $char;
 
				 // If the last character was the beginning of an element, 
				 // output a new line and indent the next line.
				 if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
						 $result .= $newLine;
						 if ($char == '{' || $char == '[') {
								 $pos ++;
						 }
						 
						 for ($j = 0; $j < $pos; $j++) {
								 $result .= $indentStr;
						 }
				 }
				 
				 $prevChar = $char;
		 }
 
		 return $result;
  } // indent()
	
	static function fmtFilesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor] . 'B';
	}
	
	static function _fmtDate($date) {
		$date = strtotime($date);
		
		//return $date . ' ' . date(DATE_TIME_FORMAT, $date);
		if ($date < 100000) {
			return '';
		} else {
			return date(DATE_TIME_FORMAT, $date);
		}
	} // _fmtDate()
	
	static function imageResize($src, $width, $height, $mime_type){
		if(!list($w, $h) = getimagesize($src)) {  pr("Unsupported picture type!"); exit(0); }
		
		if(!list($mime_major, $mime_minor) = split('/', $mime_type)) { pr("Invalid MIME type: $mime_type"); exit(0); }
		
		if (preg_match('/jpe?g/', $mime_minor)) {
			$img = imagecreatefromjpeg($src);
			$type = 'jpeg';
		} elseif (preg_match('/gif/', $mime_minor)) {
			$img = imagecreatefromgif($src);
			$type = 'gif';
		} elseif (preg_match('/png/', $mime_minor)) {
			$img = imagecreatefrompng($src);
			$type = 'png';
		} else {
			pr("Unsupported image type: $mime_type"); exit(0);
		}
		
		$ratio = min($width/$w, $height/$h);
		$width = floor($w * $ratio);
		$height = floor($h * $ratio);
		$x = 0;
	
		$new = imagecreatetruecolor($width, $height);
	
		if($type == "gif" || $type == "png"){
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}
		imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

		return $new;
	} // imageResize()

  static function fmtDate($date = false, $options = array()) {
		$date = $date === false ? time() : $date;
		$ts_date = preg_match('/^\d+$/', $date) ? $date : strtotime($date);
		$fmt = isset($options['fmt']) ? $options['fmt'] : 'D. M j<\s\u\p>S</\s\u\p>, Y @ g:i a';
		$timespan = isset($options['timespan']) ? $options['timespan'] : false;
		
		$formatted = '';
		
		if ($ts_date < 100000) {
			$formatted = '&#8212;';
		} else {
			$formatted = date($fmt, $ts_date);
			if ($timespan) {
				$formatted .= ' (' . CMSHelper::duration(time(), $ts_date) . ' away)'; 
			}
		}
		
		return $formatted;
	} // fmtDate()
	
	static function duration($start, $end=null) {
		$end = is_null($end) ? time() : $end;
	 
		$seconds = $end - $start;
		
		$days = floor($seconds/60/60/24);
		$hours = $seconds/60/60%24;
		$mins = $seconds/60%60;
		$secs = $seconds%60;
		
		$duration='';
		if($days>0) $duration .= "$days day" . ($days == 1 ? '' : 's') . " ";
		if($hours>0) $duration .= "$hours hour" . ($hours == 1 ? '' : 's') . " ";
		if($mins>0) $duration .= "$mins minute" . ($mins == 1 ? '' : 's') . " ";
		if($secs>0) $duration .= "$secs second" . ($secs == 1 ? '' : 's') . " ";
		
		$duration = trim($duration);
		if($duration==null) $duration = '0 seconds';
		
		if (preg_match('/\d+[^\d]+\d/', $duration)) {
			$duration = preg_replace('/(\d+\s[^\d]+)$/', ' &amp; \1', $duration);
		}
		
		return $duration;
	 }
	 
	static function textTrunc($string, $your_desired_width = 100) {
		$parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
		$parts_count = count($parts);
	
		$length = 0;
		$last_part = 0;
		for (; $last_part < $parts_count; ++$last_part) {
			$length += strlen($parts[$last_part]);
			if ($length > $your_desired_width) { break; }
		}
	
		return trim(implode(array_slice($parts, 0, $last_part)));
	}

  static function urlEncodeRecords($records) {
		foreach ($records as $field => $val) {
			if (is_array($val)) {
				$records[$field] = CMSHelper::urlEncodeRecords($val);
			} else {
				$records['__' . $field . '_escaped'] = urlencode($val);
			}
		}
		return $records;
	}
	
	static function urlifyTitle($title) {
		return preg_replace('/[^A-Za-z0-9-]+/i', '-', strtolower($title));
	}
	
	static function getTemplate($template_id, $text = '') {
		$entry_template = false;
		
		if ($template_id !== false && preg_match('/^\#/', $template_id)) {
			$template_id = str_replace('#', '', $template_id);
			
			
			if (strpos($text, $template_id) > 0) {
				if (preg_match("/<script[^>]+id=\"$template_id\"[^>]*>(.+?)<\/script>/is", $text, $matches)) {
					$entry_template = $matches[1];
				}
			}
		} else {
			App::import('Model', 'Setting');
			$settings = new Setting();
			
			$s = $settings->getByName($template_id);
			if (isset($s['Setting'])) {
				$entry_template = $s['Setting']['value'];
			}
		}
		return $entry_template;
	}
	
	static function embedParams($passed, $req) {
		$ret = $passed;
		foreach ($req as $fld => $p) {
			if (!array_key_exists($fld, $ret)) {
				$ret[$fld] = array_key_exists('default', $p) ? $p['default'] : false;
				if (isset($p['isa'])) {
					if (strtolower($p['isa']) == 'boolean') {
						$ret[$fld] = !(strtolower($ret[$fld] == 'false' || $ret[$fld] == false));
					}
				} 
			}
		}
		
		return $ret;
	}
	
	static function fdataCipher($fdata, $decrypt = false) {
		if ($decrypt) {
			return json_decode(Security::cipher(base64_decode($fdata), Configure::read('Security.salt')));
			//return base64_encode(Security::cipher(json_encode($fdata), Configure::read('Security.salt')));
		} else {
			return base64_encode(Security::cipher(json_encode($fdata), Configure::read('Security.salt')));
		}
	}

}
?>
