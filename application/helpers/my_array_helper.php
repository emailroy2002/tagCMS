<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function to_boolean($value) {
   return filter_var($value, FILTER_VALIDATE_BOOLEAN);    
}


function to_array($d) {
	if (is_object($d)) {	$d = get_object_vars($d);		}
	if (is_array($d)) {			return array_map(__FUNCTION__, $d);		}
	else {	return $d;		}
}

function to_object($d) {
	if (is_array($d)) { return (object) array_map(__FUNCTION__, $d);}
	else { return $d; }
}

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

function flatten_array($array) {
    $result = array();
    foreach ($array as $key => $value)    {
        if (is_array($value))    {
            $result = array_merge($result, flatten_array($value, $key));
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}


function flatten_object($array) {
    foreach ($array  as $item);
    return (isset($item)) ? $item : null;
}


function shorten($string, $start, $end) {
    if (strlen($string) > $end) {
        return htmlentities(substr($string, $start, $end)) ."...";    
    } else {
        return $string;
    }
        
}