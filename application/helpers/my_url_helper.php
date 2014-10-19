<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/** create an anchor link *
 * @id - id of the Item
*/ 
function get_link($id, $type) {
    $ci =& get_instance();
    return $ci->path->get_link($id, $type);
}

/** Get the current slug **/
function slug() {
    return end(uri_to_array());
}

function is_article() {
    $ci =& get_instance();
    return $ci->article->is_article();
}


function is_category() {
   $ci =& get_instance();
   return $ci->category->is_category();
}

function get_category($categories_array) {
    $ci =& get_instance();
    return $ci->category->get_category($categories_array);
}

function get_categories($parent_id = null, $formatted = false) {
    $ci =& get_instance();
    return $ci->category->get_categories($parent_id, $formatted);    
}


function uri_to_array($uri = null) {
    $ci =& get_instance();
    return $ci->article->uri_to_array($uri);
}


function is_page() {
    return null;
}

/** ###############################################
*                     ADMIN AREA
*  ################################################*/ 
function admin_url($page = null) {
    return site_url('admin/'.$page);
}

