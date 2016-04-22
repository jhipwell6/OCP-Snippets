<?php

/***************************************************************************************************************
*********************************************** HELPER FUNCTIONS ***********************************************
***************************************************************************************************************/
/*
 * _in_array()
 * alias for php in_array()
 * params = $needle (string), $haystack (array)
 * return true || false (bool)
 */
function _in_array( $needle, $haystack ) {
	if(empty($haystack))
		return false;
	
	foreach(array_values($haystack) as $v)
		$new_haystack[$v] = 1;
		
	if (isset($new_haystack[$needle])) {
		return true;
	} else {
		return false;
	}
}

/*
 * _print_r()
 * similar to php print_r()
 * params = $arr (array)
 * prints array
 */
function _print_r( $arr ) {
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}
 
/*
 * trim_excerpt()
 * return ''
 */
function trim_excerpt( $text ) {
	return rtrim($text,'[&hellip;]');
}
add_filter('get_the_excerpt', 'trim_excerpt');

/*
 * _get_url_type()
 * params = $url (string)
 * return 'youtube' || 'vimeo' || 'file'
 */
function _get_url_type( $url ) {
    if (strpos($url, 'youtube') > 0 || strpos($url, 'youtu') > 0) {
        return 'youtube';
    } elseif (strpos($url, 'vimeo') > 0) {
        return 'vimeo';
    } else {
		return 'file';
    }
}

/*
 * _get_video_id()
 * params = $url (string)
 * return id
 */
function _get_video_id( $url ) {
	$type = _get_url_type($url);
	if($type == 'file') {
		return false;
	} elseif($type == 'youtube') {
		
		if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $values)) {
			$id = $values[1];
		} elseif(preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $values)) {
			$id = $values[1];
		} elseif(preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $values)) {
			$id = $values[1];
		} elseif(preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $values)) {
			$id = $values[1];
		} else if(preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $url, $values)) {
			$id = $values[1];
		}
		return $id;
		
	} else {
		if (preg_match("/https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/", $url, $values)) {
			$id = $values[3];
		}
		return $id;
	}
}



/***************************************************************************************************************
********************************************** TEMPLATE FUNCTIONS **********************************************
***************************************************************************************************************/

/*
 * _get_link_target()
 * params = $url (string)
 * return '' || target="_blank"
 */
function _get_link_target( $url ) {
	$extension = pathinfo($url, PATHINFO_EXTENSION);
	$is_doc = array('doc','docx','pdf','ppt','pptx','xls','xlsx','zip');
	$host = home_url();
	if(_in_array($extension, $is_doc) || strpos($url, $host) === false) {
		$target = ' target="_blank"';
	} else {
		$target = '';
	}
	return $target;
}

/*
 * _the_link_target()
 * params = $url (string)
 * see get_link_target()
 * echo '' || target="_blank"
 */
function _the_link_target( $url ) {
	echo _get_link_target($url);
}

/*
 * _time_ago()
 * return time
 */
function _time_ago( $time ) {
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");

	$now = time();
	$difference = $now - $time;
	$tense = "ago";

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
		$periods[$j].= "s";
	}

	return "$difference $periods[$j] $tense";
}

 
/*
 * _get_conditional_field()
 * params = $name (string), $before (string), $after (string), $sub (bool), $op (bool)
 * dependency = advanced-custom-fields-pro
 * return $before . $value . $after
 */
function _get_conditional_field( $name, $before, $after, $sub = false, $opt = false ) {
	if($sub) {
		$value = $opt !== false ? get_sub_field($name, 'option') : get_sub_field($name);
	} else {
		$value = $opt !== false ? get_field($name, 'option') : get_field($name);
	}
	$before = sprintf( $before, $value );
	$after = sprintf( $after, $value );
	if($value)
		return $before . $value . $after;
}

/*
 * _the_conditional_field()
 * params = $name (string), $before (string), $after (string), $sub (bool), $op (bool)
 * dependency = advanced-custom-fields-pro
 * echo $before . $value . $after
 */
function _the_conditional_field( $name, $before, $after, $sub = false, $opt = false ) {
	echo _get_conditional_field( $name, $before, $after, $sub, $opt );
}

/*
 * _get_image()
 * params = $name (string), $format (string), $sub (bool), $opt (bool)
 * dependency = advanced-custom-fields-pro
 * return $value[$format]
 */
function _get_image( $name = '', $format = 'url', $sub = false, $opt = false ) {
	if($sub) {
		$value = $opt !== false ? get_sub_field($name, 'option') : get_sub_field($name);
	} else {
		$value = $opt !== false ? get_field($name, 'option') : get_field($name);
	}
	if($value) {
		return $value[$format];
	}
}

/*
 * _the_image()
 * params = $name (string), $format (string), $sub (bool), $opt (bool)
 * dependency = advanced-custom-fields-pro
 * see _get_image()
 * echo $value[$format]
 */
function _the_image( $name = '', $format = 'url', $sub = false, $opt = false ) {
	echo _get_image( $name, $format, $sub, $opt );
}

/*
 * _get_video()
 * params = $name (string), $format (string), $sub (bool), $opt (bool)
 * dependency = advanced-custom-fields-pro
 * return embed || image || url || id
 */
function _get_video( $name = '', $format = 'id', $sub = false, $opt = false ) {
	if($sub) {
		$url = $opt !== false ? get_sub_field($name, 'option') : get_sub_field($name);
	} else {
		$url = $opt !== false ? get_field($name, 'option') : get_field($name);
	}
	$type = _get_url_type($url);
	$id = _get_video_id($url);
	if($format == 'embed') {
		$embed = $type == 'vimeo' ? 'http://player.vimeo.com/video/'.$id : 'http://www.youtube.com/embed/'.$id.'?rel=0&amp;showinfo=0';
		return $embed;
	} elseif($format == 'image') {
		switch($type) {
			case 'youtube':
				$image = 'http://img.youtube.com/vi/'.$id.'/mqdefault.jpg';
				break;
			case 'vimeo':
				$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));  
				$image = $hash[0]['thumbnail_large'];
				break;
		}
		return $image;
	} elseif($format == 'url') {
		return $url;
	} else {
		return $id;
	}
}

/*
 * _the_video()
 * params = $name (string), $format (string), $sub (bool), $opt (bool)
 * dependency = advanced-custom-fields-pro
 * echo embed || image || url || id
 */
function _the_video( $name = '', $format = 'id', $sub = false, $opt = false ) {
	echo _get_video( $name, $format, $sub, $opt );
}

/*
 * _get_text_area()
 * params = $name (string), $before (string), $sep (string), $after (string), $sub (bool), $opt (bool), $glue (string)
 * dependency = advanced-custom-fields-pro
 * return $before . $first . $sep . $remaining . $after;
 */
function _get_text_area( $name = '', $before = '', $sep = '', $after = '', $sub = false, $opt = false, $glue = '<br />' ) {
	if($sub) {
		$value = $opt !== false ? get_sub_field($name, 'option') : get_sub_field($name);
	} else {
		$value = $opt !== false ? get_field($name, 'option') : get_field($name);
	}
	
	$value = explode($glue, $value);
	
	$first = $value[0];
	unset($value[0]);
	
	$remaining = implode($glue, $value);
	
	return $before . $first . $sep . $remaining . $after;
}

/*
 * _the_text_area()
 * params = $name (string), $before (string), $sep (string), $after (string), $sub (bool), $opt (bool), $glue (string)
 * dependency = advanced-custom-fields-pro
 * echo $before . $first . $sep . $remaining . $after;
 */
function _the_text_area( $name = '', $before = '', $sep = '', $after = '', $sub = false, $opt = false, $glue = '<br />' ) {
	echo _get_text_area( $name, $before, $sep, $after, $sub, $opt, $glue );
}