<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Path extends MY_model {    
    protected $categories;    
    function __construct() {	    
    	parent::__construct();
    }    
    
    /** get article anchor path **/         
    function article_path($id, $type){
        $this->model->join('item_description', "items.id = item_description.id");                
        $item = $this->model->from(TABLE_ITEMS)->get_by_id($id);                        
        $this->article_categories($item->cat_id);    
        if ($type == 'link') {
            return anchor($this->construct_path($this->categories)."/".$item->slug, $item->title , $attr = null);
        } else if ($type == 'url') {
            return site_url($this->construct_path($this->categories)."/".$item->slug);
        } else {
            return null;
        }      
    }
    
    /** get category anchor path **/          
    function get_category_link($cat_id, $title = null, $type = 'link') {
        $this->categories = null;         
        $this->article_categories($cat_id);
        if ($type == 'link') {
            return anchor($this->construct_path($this->categories)."/", $title , $attr = null);
        } else if ($type == 'url') {
            return site_url($this->construct_path($this->categories));
        } else {
            return null;
        }            
    }
    
    /** GET ANCHOR LINK OF AN ARTILCE
     * @id = id of the article, items
     * @return = full web url path of the article
     * @type    = link  , string
     */
    function get_link($id, $type = 'link'){       
        $this->categories = null;
        return $this->article_path($id, $type); 
    }
    
    
        
    /** get all categories and store it in array */        
    function article_categories($cat_id) {        
        $category = $this->model->from(TABLE_CATEGORIES)->get_by_id($cat_id);        
        if (isset($category->parent_id)) {
            $this->categories[] = $category->slug;
            $this->article_categories($category->parent_id); 
        } else {
            $this->categories[] = $category->slug;
        }
    }       
    
    /** construct array path for category uri **/
    function construct_path($categories) {
        $arr = array_reverse($categories);
        return implode("/", $arr);    
    }
    

        

}