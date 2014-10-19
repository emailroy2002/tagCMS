<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Article extends MY_model {
    
    protected $table = TABLE_ITEMS;
    protected $array = array();
    
	function __construct()	{	   
		parent::__construct($this->table);          
	}
    

    /*clean uri for pagination segments*/
    function uri_to_array($uri = null) {        
        if ($uri != null) {            
            if (is_array($uri)) {
                $uri = implode($uri);
            }                        
            $categories = explode ("page", $uri);            
        } else {            
            $categories = explode ("page", uri_string());                
        }
                
        $uri_array = explode ("/", $categories[0]);
        return (array_filter($uri_array));
    }
    
    function uri_category() {
        $categories = array_slice(uri_to_array(), 0, -1);
        return $categories;
    }
    
    
    function is_article() {
        $this->categories = self::uri_category();    
        foreach ($this->categories as $slug) {            
            $parent_id = (isset($cat->id))? $cat->id : null;              
            $cat = $this->model->from(TABLE_CATEGORIES)->parent_id($parent_id)->get_by_slug($slug);            
            $cat_array[] = $cat;                               
        }        
        $index = isset($cat_array)? count($cat_array) - 1 : null; //array index of arrays
        $category_id =  (isset($cat_array[$index]->id))? $cat_array[$index]->id : null;
        if ($category_id) {
            return true;
        } else {
            return null;
        }
    }
    
        
    
    function get_item() {
        $this->categories = self::uri_category();        
        foreach ($this->categories as $slug) {            
            $parent_id = (isset($cat->id))? $cat->id : null;              
            $cat = $this->model->from(TABLE_CATEGORIES)->parent_id($parent_id)->get_by_slug($slug);            
            $cat_array[] = $cat;                               
        }        
        $index = isset($cat_array)? count($cat_array) - 1 : null; //array index of arrays
        $category_id =  (isset($cat_array[$index]->id))? $cat_array[$index]->id : null;
        if ($category_id) {            
            $this->db->select(TABLE_ITEMS .".*");
            $this->db->select(TABLE_ITEM_DESCRIPTION .".*");
            $this->db->select(TABLE_CATEGORIES .".slug as cat_slug,".TABLE_CATEGORIES .".name as cat_name, ". TABLE_CATEGORIES .".description as cat_desc" );       
                  
            $this->model->join(TABLE_CATEGORIES, "items.cat_id = ".TABLE_CATEGORIES.".id");                        
            $this->model->join(TABLE_ITEM_DESCRIPTION, "items.id = item_description.id");
            
            $this->model->where(TABLE_ITEMS.'.status', 'published');
            $this->model->where(TABLE_ITEMS.'.cat_id', $category_id);
            $this->model->where(TABLE_ITEMS.'.slug', slug());            
            $this->model->order_by(TABLE_ITEMS.'.sequence','ASC');
               
            return $this->model->from(TABLE_ITEMS)->get();
                        
        } else {
            
            return null;
        }
    }

    
    function pagination($per_page, $offset = null) {
        $this->model->limit($per_page, $offset);
        return $this;
    }
    
    
    function recurse_categories($parent_id = null) {
        $categories = $this->model->from(TABLE_CATEGORIES)->where('parent_id', $parent_id)->get();                        
        foreach ($categories as $category) {                        
            $this->array[$category->id] = array_merge(to_array($category));
            self::recurse_categories($category->id);            
        }       
    }
    
    
    function query_items($recursive) {        
        $cat_array = $this->category->get_category_array($recursive);
        $this->db->select(TABLE_ITEMS .".*");
        $this->db->select(TABLE_ITEM_DESCRIPTION .".*");
        $this->db->select(TABLE_CATEGORIES .".slug as cat_slug,".TABLE_CATEGORIES .".name as cat_name, ". TABLE_CATEGORIES .".description as cat_desc" );       
              
        $this->model->join(TABLE_CATEGORIES, "items.cat_id = ".TABLE_CATEGORIES.".id");                        
        $this->model->join(TABLE_ITEM_DESCRIPTION, "items.id = item_description.id");
        $this->model->where(TABLE_ITEMS.'.status', 'published');        
        //$this->model->order_by(TABLE_ITEMS.'.date_published','DESC');
        $this->model->order_by(TABLE_ITEMS.'.sequence','desc'); //@todo : make this dynamic based on folder
        $ctr = 0; 
        
        if (sizeof($cat_array) > 0) {    
            foreach ($cat_array as $cat) { 
                $ctr ++;
                if (isset($cat->id)) {
                    if ($ctr == 1) {
                        $this->model->where(TABLE_ITEMS.'.cat_id', $cat->id);
                    } else {
                        $this->model->or_where(TABLE_ITEMS.'.cat_id', $cat->id);    
                    }                  
                }
            } 
        }
       
    }
    
    
    function count($recursive = true) {  
        self::query_items($recursive);        
        $this->model->from(TABLE_ITEMS);
        return $this->model->count_all_results() ;     
    }
    
    
    
    function get_items($per_page = null, $offset = null, $recursive = true) {
        
 
        self::query_items($recursive);        
        isset($per_page)? $this->model->limit($per_page, $offset) : null; 
        return $this->model->from(TABLE_ITEMS)->get();         
    }
      
    
}
?>