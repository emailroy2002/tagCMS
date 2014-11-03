<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Article extends MY_model {
    
    protected $table = TABLE_ITEMS;
    protected $array = array();
    protected $order = 'asc'; 
    
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

        if (sizeof($this->categories) > 0) {           
            foreach ($this->categories as $slug) {            
                //$parent_id = (isset($cat->id))? $cat->id : null;              
                //$cat = $this->model->from(TABLE_CATEGORIES)->parent_id($parent_id)->get_by_slug($slug);
                $cat = $this->model->from(TABLE_CATEGORIES)->where(array('slug'=>$slug))->get_row();
                $cat_array[] = $cat;                               
            }
         } else {
            //@todo: get $slug where parent is a menu
            $slug = $this->uri->segment(1);
            $item = $this->model->from(TABLE_ITEMS)->where(array('slug'=>$slug))->get_row();           
            
            $cat = $this->model->from(TABLE_CATEGORIES)->where(array('id'=>$item->cat_id, 'type'=> 'menu'))->get_row();
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
    
    //set item ordering
    function set_order($order) {
        $this->order = $order;
    }
    
    
    //get order of item
    function order() {
        return $this->order; 
    }
            
    
    function get_item() {
        $this->categories = self::uri_category();        
        if (sizeof($this->categories) > 0) {           
            foreach ($this->categories as $slug) {            
                //$parent_id = (isset($cat->id))? $cat->id : null;              
                //$cat = $this->model->from(TABLE_CATEGORIES)->parent_id($parent_id)->get_by_slug($slug);
                $cat = $this->model->from(TABLE_CATEGORIES)->where(array('slug'=>$slug))->get_row();
                $cat_array[] = $cat;                               
            }
         } else {
            //@todo: get $slug where parent is a menu
            $slug = $this->uri->segment(1);
            $item = $this->model->from(TABLE_ITEMS)->where(array('slug'=>$slug))->get_row();           
            if (isset($item->id)) {
                $cat = $this->model->from(TABLE_CATEGORIES)->where(array('id'=>$item->cat_id, 'type'=> 'menu'))->get_row();
                $cat_array[] = $cat;                
            }
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
   
    
    function query_items($cat_id = null, $recursive) {
        if (!isset($cat_id)) {
            $this->cat_array = $this->category->get_category_array($recursive);
            $this->db->select(TABLE_ITEMS .".*");
            $this->db->select(TABLE_ITEM_DESCRIPTION .".*");
            $this->db->select(TABLE_CATEGORIES .".slug as cat_slug,".TABLE_CATEGORIES .".name as cat_name, ". TABLE_CATEGORIES .".description as cat_desc" );       
                  
            $this->model->join(TABLE_CATEGORIES, "items.cat_id = ".TABLE_CATEGORIES.".id");                        
            $this->model->join(TABLE_ITEM_DESCRIPTION, "items.id = item_description.id");
            $this->model->where(TABLE_ITEMS.'.status', 'published');
                    
            //$this->model->order_by(TABLE_ITEMS.'.date_published','DESC');
            $this->model->order_by(TABLE_ITEMS.'.sequence', $this->order()); //@todo : make this dynamic based on folder
            
            $ctr = 0; 
            
            if (sizeof($this->cat_array ) > 0) {    
                foreach ($this->cat_array  as $cat) { 
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
        } else {
            $this->model->where(TABLE_ITEMS.'.cat_id', $cat_id);
        }
    }
    
    
    function count($cat_array = null, $recursive = true) {  
        self::query_items($cat_array, $recursive);        
        $this->model->from(TABLE_ITEMS);
        return $this->model->count_all_results() ;     
    }
    
    
    
    function get_items($cat_array = null, $per_page = null, $offset = null, $recursive = true) {
        self::query_items($cat_array, $recursive);                
        isset($per_page)? $this->model->limit($per_page, $offset) : null; 
        return $this->model->from(TABLE_ITEMS)->get();         
    }
      
    
}
?>