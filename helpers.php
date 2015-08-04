<?php
function link_target($url) {
	$extension = pathinfo($url, PATHINFO_EXTENSION);
	$is_doc = array('doc','docx','pdf','ppt','pptx','xls','xlsx','zip');
	$host = home_url();
	if(in_array($extension, $is_doc) || strpos($url, $host) === false) {
		$target = ' target="_blank"';
	} else {
		$target = '';
	}
	return $target;
}

function get_col_x($arr, $size) {
	$sz = $size == '' ? 'sm' : $size;
	$col = '';
	$count = count($arr);
	switch($count) {
		case 1:
			$col = 'col-'.$sz.'-12';
			break;
		case 2:
			$col = 'col-'.$sz.'-6';
			break;
		case 3:
			$col = 'col-'.$sz.'-4';
			break;
		case 4:
			$col = 'col-'.$sz.'-3';
			break;
		case 5:
			$col = 'col-'.$sz.'-3';
			break;
		case 6:
			$col = 'col-'.$sz.'-4';
			break;
		case 7:
		case 8:
			$col = 'col-'.$sz.'-2';
			break;
		case 9:
			$col = 'col-'.$sz.'-4';
			break;
		case 10:
		case 11:
		case 12:
			$col = 'col-'.$sz.'-2';
			break;
		default:
			$col = 'col-'.$sz.'-2';
			break;
	}
	return $col;
}

function ocp_url_type($url) {
    if (strpos($url, 'youtube') > 0 || strpos($url, 'youtu') > 0) {
        return 'youtube';
    } elseif (strpos($url, 'vimeo') > 0) {
        return 'vimeo';
    } else {
		return 'file';
    }
}

function ocp_video_id($url) {
	$type = ocp_url_type($url);
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


/* FIELDS */
function the_conditional_field($name, $before, $after, $sub = false) {
	$value = $sub !== false ? get_sub_field($name) : get_field($name);
	if($value)
		echo $before . $value . $after;
}

function the_image($name = '', $format = 'url', $sub = false) {
	$value = $sub !== false ? get_sub_field($name) : get_field($name);
	if($value) {
		echo $value[$format];
	}
}

function the_video($name = '', $format = 'id', $sub = false) {
	$url = $sub !== false ? get_sub_field($name) : get_field($name);
	$type = ocp_url_type($url);
	$id = ocp_video_id($url);
	if($format == 'embed') {
		$embed = $type == 'vimeo' ? 'http://player.vimeo.com/video/'.$id : 'http://www.youtube.com/embed/'.$id.'?rel=0&amp;showinfo=0';
		echo $embed;
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
		echo $image;
	} elseif($format == 'url') {
		echo $url;
	} else {
		echo $id;
	}
}

function the_background($name, $option = false) {
	$repeater = $option == false ? get_field($name) : get_field($name, 'option');
	$row = $repeater[0];
	if($row['color'] !== '') {
		$value = $row['color'];
		$style = 'background:none;background-color:'.$value.';';
	}
	if($row['image'] !== '') {
		$value = $row['image'];
		$style = 'background-image:url('.$value.');';
	}
	echo $style;
}

