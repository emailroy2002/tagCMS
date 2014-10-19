<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_model {
    
    protected $categories, $categories_array, $cat_ids_array, $array;
   
    protected $table = TABLE_CATEGORIES;
   
	function __construct()	{	   
		parent::__construct($this->table);  
	}    
    
    /** is_category - check whether the uri string is a valid category  
     * @return  = true or false
     */
    function is_category($array = array()) {
        $segments = $this->article->uri_to_array();        
        foreach ($segments as $slug) {            
            $this->model->from(TABLE_CATEGORIES);            
            (isset($category->id) != null)? $this->model->parent_id($category->id) : $this->model->parent_id(null);            
            $category = $this->model->from(TABLE_CATEGORIES)->get_by_slug($slug);
            $array[] = isset($category->id)? $category->id : null;       
        }   
        return (count(array_filter($array)) == count($array))? true : false;
    }
    
    
    function get_category($category_array) {
        $cat_id_array = $this->get_uri_categories($category_array);
        if (isset($cat_id_array)) {
            $data = array('is_valid' => (count(array_filter($cat_id_array)) == count($cat_id_array))? true : false,        
                          'id'   => $cat_id_array[count($cat_id_array) -1]);
        }
        return ($data)? to_object($data) : null;                       
    } 
    
        
    function current_category($uri = null) { 
        $segment = null;
        $segments = null;
        
        if (!isset($uri)) {
            if (slug()) {
                $uri = implode("/", uri_to_array());    
            } else {
                /** Link a category to front page **/
                $frontpage = $this->model->from(TABLE_CATEGORIES)->where(array('is_frontpage' => true))->limit(1)->get_row();
                if (isset($frontpage->id)) {
                    $uri_array = $this->path->get_category_link($frontpage->id, null, 'url');
                    $uri = implode("/", uri_to_array($uri_array));    
                } 
            }          
        } else {
            $uri = implode("/", uri_to_array($uri));
            
        }
       
        $segments = explode("/", $uri);
        
        
        if (!is_category()) {
            $segments = array_slice($segments, 0, -1);
        }        
        
        foreach ($segments as $segment) {  @$ctr ++;          
            $category = $this->model->from(TABLE_CATEGORIES)->get_by_slug($segment);
            if ($segment == slug()) {
                if (isset($category)) {
                    return to_object($category);    
                }
            } else if ($ctr == count($segments)) {
                return to_object($category);
            }
        }
    }  
        
        
    function parent_category($parent_id){
         $cat = $this->model->from(TABLE_CATEGORIES)->where('id', $parent_id)->get_row();
         $this->categories_array[] = $cat;         
         if (isset($cat->parent_id)) {
            $this->parent_categories($cat->parent_id);   
         }
    }
        
        
    function get_categories($parent_id = null , $formatted = false) {        
        return ($formatted == true)?   self::formatted_categories($parent_id)  :self::categories($parent_id);
    }        
    
    
    function categories($id = null) {
        $categories = $this->model->from(TABLE_CATEGORIES)->order_by('sequence', 'ASC')->where(isset($id)? 'id' : 'parent_id', $id)->get();        
        foreach ($categories as $category) {
                $subc = array("subcategories"=> self::sub_categories($category->id) );            
                $cat[$category->id] = array_merge(to_array($category),$subc);            
        }
        return (isset($cat))? ($cat) : null;
    }
            
    function sub_categories($parent_id = null) {
        $categories_array = array();        
        $categories = $this->model->from(TABLE_CATEGORIES)->order_by('sequence', 'ASC')->where('parent_id', $parent_id)->get();                        
        foreach ($categories as $category) {
            $subc = array("subcategories"=> self::sub_categories($category->id) );            
            $categories_array[$category->id] = array_merge(to_array($category),$subc);            
        }        
        return $categories_array;        
    }
    
    //recurse 
    function recurse_categories($parent_id = null) {
        $categories = $this->model->from(TABLE_CATEGORIES)->where('parent_id', $parent_id)->get();                        
        foreach ($categories as $category) {                        
            $this->array[$category->id] = array_merge(to_array($category));
            self::recurse_categories($category->id);            
        }       
    }
    
    
    //return as flatten categories array
    function get_recursive_categories($parent_id = null) {
        self::recurse_categories($parent_id);        
        return $this->array;
    }
    
    function get_category_array($recursive) {    
        
        //Get the selected category front page dynamically via "is_frontpage" 
        $parent = Theme::selected_category(); 
               
        //$parent = $this->category->current_category();
        
        if (isset($parent->id)) {
            $cat = to_object(flatten_array(to_array($this->model->from(TABLE_CATEGORIES)->where('id', $parent->id)->get())));                     
            $cat_array[] = to_array($cat);
           
            //retrievie subcategories
            if ($recursive == true) {
                if (isset($cat->id)) {
                    $sub_cat_array[] = $this->category->get_recursive_categories($cat->id);
                    if (isset($sub_cat_array[0])) {
                        foreach ($sub_cat_array[0] as $sub_cat) {
                            $sub_cat_array_list = flatten_array( to_array($sub_cat));                     
                            array_push($cat_array, $sub_cat_array_list);
                        }                    
                    }
                }
            }
            return to_object($cat_array);            
        }
    }
    
    /** Formatted categories 
    function formatted_categories($id = null) {
        $categories_output = "<ul>";
        $categories = $this->model->from(TABLE_CATEGORIES)->where(isset($id)? 'id' : 'parent_id', $id)->get();
        foreach ($categories as $category) {
            $categories_output .= "<li>". $category->name ."</li>";
            $categories_output .= self::get_formatted_subcategories($category->id);            
        }
        $categories_output .= "</ul>";
        return ($categories_output);  
    }     
    
    
    function get_formatted_subcategories($parent_id = null) {
        $categories_output = "<ul>";        
        $categories = $this->model->from(TABLE_CATEGORIES)->where('parent_id', $parent_id)->get();                        
        foreach ($categories as $category) {                                    
            $categories_output .= "<li>". $category->name ."</li>";            
            $categories_output .= self::get_formatted_subcategories($category->id);
        }  
        $categories_output .= "</ul>";
        return $categories_output;       
    }    **/
    
    
    
    
    function get_subcategories($cat_id) {
        $this->sub_categories($cat_id);        
        return  $this->categories_array;
        
    }
    
  
    function get_uri_categories($uri_category) {
        $cat = (isset($uri_category))? $uri_category : $this->current_category();        
        if (isset($cat)) {   
            if ($cat->parent_id >= 1) {          
                $this->categories_array[] = $cat;
                if (isset($cat->id)) {
                    $this->sub_categories($cat->id);    
                }               
            } 
            return $this->categories_array;
        }
    } 
    
    //make an array of category ids (for delete and enumartion purposes only)
    public function get_category_ids($parent_id) {
        $this->category->category_ids($parent_id);
        return $this->cat_ids_array;
    }  
        
    public function category_ids($parent_id) {
        $items = $this->category->sub_categories($parent_id); 
        foreach ($items as $item) {
            $this->cat_ids_array[] = $item['id'];                        
            foreach ($item['subcategories'] as $subcat_item) {
                $this->cat_ids_array[] = $subcat_item['id'];   
                $this->sub_cat_ids($subcat_item['subcategories']);             
            }            
        }
    }    
    
    public function sub_cat_ids($items) {
        foreach ($items as $item) {
            $this->cat_ids_array[] = $item['id'];                        
            foreach ($item['subcategories'] as $subcat_item) {
                $this->cat_ids_array[] = $subcat_item['id'];   
                $this->sub_cat_ids($subcat_item['subcategories']);             
            }            
        }        
    } 

}