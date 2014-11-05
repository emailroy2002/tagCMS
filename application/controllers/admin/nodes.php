<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nodes extends MY_Admin_controller {
    
    protected $resource = 'nodes';

    function __construct() {	    
    	parent::__construct();        
        $this->load->library('form_validation');
               
       $this->load->model('setting');
          $this->load->helper('text');  
    }
    
 
	/**
	 * Nodes::get_node()
	 * 
	 * @return
	 */
	public function get_node()	{	   
	   	$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : null;                   
        $categories = $this->category->sub_categories($node);        
        $rslt = array();
        
        $this->db->select(TABLE_ITEMS .".*");
        $this->db->select(TABLE_ITEM_DESCRIPTION .".*");
        $this->db->select(TABLE_CATEGORIES .".slug as cat_slug,".TABLE_CATEGORIES .".name as cat_name, ". TABLE_CATEGORIES .".description as cat_desc" );
        $this->model->join(TABLE_CATEGORIES, "items.cat_id = ".TABLE_CATEGORIES.".id");
        $this->model->join(TABLE_ITEM_DESCRIPTION, "items.id = item_description.id");        
        $this->model->order_by(TABLE_ITEMS.'.sequence','asc'); //@todo : make this dynamic based on folder
        
        $items = $this->model->from(TABLE_ITEMS)->get_where(array('cat_id' => $node ));
                                    
        foreach ($categories as $c) {
            $item_count = $this->model->from(TABLE_ITEMS)->get_where(array('cat_id' => $c['id']));
            $has_category = (count($c['subcategories']) > 0) ? true : false;            
            $has_items = (count($item_count) > 0)? true : false;            
            $has_children = ($has_category == true || $has_items == true) ? true : false;            
            if (!isset($c['parent_id'])) {
                $rslt[] = array('id'=> $c['id'], 'text'=> "<span class='root'>". shorten($c['name'], 0, 20) . "</span>", 'type' => 'folder', 'children'=> $has_children);    
            } else {
                $rslt[] = array('id'=> $c['id'], 'text' => shorten($c['name'], 0, 20), 'type' => 'folder', 'children'=> $has_children);
            }
        }
        
        foreach ($items as $item) {
            $rslt[] = array('id'=> $item->id, 'parent'=> $node ,'text' => shorten($item->title, 0, 20) , 'type' => 'file');
        }  
   
        if (isset($rslt)) {
            $this->output($rslt);    
        }       
	}



    /** ################## REFRESH SEQUENCE ########################### **/
    
   /**
    * Nodes::refresh_category_sequence()
    * 
    * @param mixed $parent_id
    * @return
    */
   public function refresh_category_sequence($parent_id) {
        $this->db->trans_start();
        $ctr = 0;
        $this->db->where('parent_id', $parent_id);
        $this->db->order_by('sequence', 'ASC'); 
        $items = $this->db->get(TABLE_CATEGORIES)->result();        
        foreach ($items as $item) {           
            //echo $item->id ." | " . $ctr ."<Br>";
            $this->db->where('id', $item->id);            
            $data = array('sequence' => $ctr);              
            $this->db->update(TABLE_CATEGORIES, $data);
            $ctr = $ctr + 1;            
        }    
        if ($this->db->trans_status() === FALSE) {        
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();            
        }        
        $this->db->trans_complete();                
    }
    
    
    /**
     * Nodes::refresh_file_sequence()
     * 
     * @param mixed $cat_id
     * @return
     */
    public function refresh_file_sequence($cat_id) {
        $this->db->trans_start();
        $ctr = 0;
        $this->db->where('cat_id', $cat_id);
        $this->db->order_by('sequence', 'ASC'); 
        $items = $this->db->get(TABLE_ITEMS)->result();        
        foreach ($items as $item) {           
            //echo $item->id ." | " . $ctr ."<Br>";
            $this->db->where('id', $item->id);            
            $data = array('sequence' => $ctr);              
            $this->db->update(TABLE_ITEMS, $data);
            $ctr = $ctr + 1;            
        }    
        if ($this->db->trans_status() === FALSE) {        
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();            
        }        
        $this->db->trans_complete();                
    } 
    
    
    /**  ################ CREATE  ####################### **/
    
    /**
     * Nodes::create_category()
     * 
     * @return
     */
    public function create_category() {  
        
        if ($this->input->post('parent_id')) {
            $parent = $this->input->post('parent_id');
            $parent_id  = ($parent == '#')? null : $parent;
        } else {
            $parent_id  = null;
        };  

                         
        $this->db->trans_start();
        
        $this->refresh_category_sequence($parent_id);
        $sequence_id = $this->model->from(TABLE_CATEGORIES)->where(array('parent_id'=> $parent_id))->count_all_results();
        
        
        $data = array(
           'parent_id' => $parent_id,
           'slug' => $this->input->post('form_slug'),              
           'name' => $this->input->post('form_title'),
           'description' => $this->input->post('form_description'),
           'template_view'  => $this->input->post('template_view'),
           'template_single_view' => $this->input->post('template_single_view'),
           'is_frontpage'=>  $this->input->post('is_frontpage'),
           'items_per_page' => $this->input->post('items_per_page'),
           'type'   => 'category',
           'sequence' => $sequence_id,
           'order'  => $this->input->post('order'),
           'date_added' => CURRENT_DATE,
           'status' => "published"
        );        
        $this->db->insert(TABLE_CATEGORIES, $data);
        $insert_id = $this->db->insert_id();
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }   else   {            
            $this->db->trans_commit();
            $this->output( array('id'=> $insert_id, 'type'=> 'default'));
        }
        $this->db->trans_complete();       
    }   
    
    
  /**
    * Nodes::create_file()
    * 
    * @return
    */
   public function create_file() {        
        $this->db->trans_start();        
        $sequence_id = $this->model->from(TABLE_ITEMS)->where(array('cat_id'=> $this->input->post('cat_id')))->count_all_results();        
        $data = array(
           'cat_id' => $this->input->post('cat_id'),
           'slug' => $this->input->post('form_slug'),              
           'date_published' => CURRENT_DATE,           
           'date_added' => CURRENT_DATE,
           'sequence' => $sequence_id,
           'status' => "published"
        );        
        $this->db->insert(TABLE_ITEMS, $data);
        $insert_id = $this->db->insert_id();
        $data = array(            
           'id' => $insert_id,
           'title' => $this->input->post('form_title'),              
           'description' => $this->input->post('form_description'),                     
        );
        $this->db->insert(TABLE_ITEM_DESCRIPTION, $data);
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            $this->output( array('id'=> $insert_id, 'type'=> 'file'));
        }        
        $this->db->trans_complete();
    }     
    
    /**  ################ EDIT  #######################  **/
    
    /**
     * Nodes::rename_category()
     * 
     * @return
     */
    public function rename_category() {
        $update_id = $this->input->post('id');
        
        //reset frontpage for category to none 
        if ($this->input->post('is_frontpage') == 'on') {
            $this->db->update(TABLE_CATEGORIES, array('is_frontpage'=> false));
        } 
                
        $this->db->trans_start();       
        $data = array(
           'slug' => $this->input->post('form_slug'),              
           'name' => $this->input->post('form_title'),
           'description' => $this->input->post('text'),
           'template_view'  => $this->input->post('template_view'),
           'template_single_view' => $this->input->post('template_single_view'),
           'is_frontpage'=>  ($this->input->post('is_frontpage') == 'on') ? true : false,
           'order'  => $this->input->post('order'),
           'items_per_page' => $this->input->post('items_per_page'),                     
           
           'status' => "published"
        );     
        $this->db->where('id', $update_id);
        $this->db->update(TABLE_CATEGORIES, $data);
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }   else   {            
            $this->db->trans_commit();
            $this->output( array('id'=> $update_id, 'type'=> 'default'));
        }
        $this->db->trans_complete();        
    }
    
    
    
    /**
     * Nodes::rename_file()
     * 
     * @return
     */
    public function rename_file() {
        $update_id = $this->input->post('id');
        $this->db->trans_start();
        $data = array(
            'slug' => $this->input->post('form_slug'),            
            'status' => "published"
        );
        $this->db->where('id', $update_id);
        $this->db->update(TABLE_ITEMS, $data);        
        
        $data = array(                         
           'title' => $this->input->post('form_title'),
           //'description' => $this->input->post('form_description')
           'description' => $this->input->post('text'),
        );     
        $this->db->where('id', $update_id);
        $this->db->update(TABLE_ITEM_DESCRIPTION, $data);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        }   else   {            
            $this->db->trans_commit();
            $this->output( array('id'=> $update_id, 'type'=> 'file'));
        }
        $this->db->trans_complete();
    }    
    
    
    
    /**
     * Nodes::edit_form_category()
     * 
     * @return
     */
    public function edit_form_category() {        
        $id = $this->input->post('id');
        $text = $this->input->post('text');
        $path = $this->path->get_category_link($id, $text, 'url');
        $data = array('id' => $id, 
                      'text'=> $text, 
                      'anchor_link'=> anchor($path, $path, array('target' => '_blank', 'class' => 'category_link')),
                      'roles' => $this->model->from(TABLE_ROLES)->all()
        );        
        
        /** generation of KEYS to be fetch on view file */
        $categories = $this->model->from(TABLE_CATEGORIES)->get_where( array('id'=>$id) );        
        foreach ($categories as $category) {
            $keys = array_keys(to_array($category));
            foreach ($keys as $key) {
                $data[$key] = $category->{$key};
            }
        }        
        $this->ajax(__FUNCTION__, $data);        
    }     

    
    /**
     * Nodes::edit_form_file()
     * 
     * @return
     */
    public function edit_form_file() {
        $id = $this->input->post('id');
        $text = $this->input->post('text');
        $path = $this->path->get_link($id, 'url');
        $data = array('id' => $id, 
                      'text'=> $text, 
                      'anchor_link'=> anchor($path, $path, array('target' => '_blank', 'class' => 'article_link'))
        );
        
        $this->db->select(TABLE_ITEMS .".*");
        $this->db->select(TABLE_ITEM_DESCRIPTION .".*");
        $this->db->select(TABLE_CATEGORIES .".slug as cat_slug,".TABLE_CATEGORIES .".name as cat_name, ". TABLE_CATEGORIES .".description as cat_desc" );
        $this->model->join(TABLE_CATEGORIES, "items.cat_id = ".TABLE_CATEGORIES.".id");
        $this->model->join(TABLE_ITEM_DESCRIPTION, "items.id = item_description.id");
        $this->model->order_by(TABLE_ITEMS.'.date_modified','ASC');
        $items = $this->model->from(TABLE_ITEMS)->get_where(array('items.id' => $id ));
                
        /** generation of KEYS to be fetch on view file */
        //$categories = $this->model->from(TABLE_CATEGORIES)->get_where( array('id'=>$id) );        
        foreach ($items as $item) {
            $keys = array_keys(to_array($item));
            foreach ($keys as $key) {
                $data[$key] = $item->{$key};
            }
        }
        
        $this->ajax(__FUNCTION__, $data);
    }
    
    
        
    
    /**  ################ DELETE  #######################  **/
    
    
    /**
     * Nodes::delete_folder()
     * 
     * @return
     */
    public function delete_folder() {
        $this->db->trans_start();                
        $this->db->query('SET foreign_key_checks = 0');
                 
        //delete subcategories
        $ids = $this->category->get_category_ids($this->input->post('id'));
        if (isset($ids)) {
            $deletion_array = array_reverse($ids);      
            foreach ($deletion_array as $cat_id) {
               $this->db->delete(TABLE_CATEGORIES, array('id' => $cat_id));
               
                //delete items        
                $items = $this->db->get_where(TABLE_ITEMS, array('cat_id'=> $cat_id) )->result();                
                foreach ($items as $item) {            
                    $this->db->delete(TABLE_ITEM_DESCRIPTION, array('id' => $item->id));
                    $this->db->delete(TABLE_ITEMS, array('id' => $item->id));    
                }
                       
            }
        }        
        //delete parent
        $this->db->delete(TABLE_CATEGORIES, array('id' => $this->input->post('id')));
        //delete items        
        $items = $this->db->get_where(TABLE_ITEMS, array('cat_id'=> $this->input->post('id')) )->result();                
        foreach ($items as $item) {            
            $this->db->delete(TABLE_ITEM_DESCRIPTION, array('id' => $item->id));
            $this->db->delete(TABLE_ITEMS, array('id' => $item->id));    
        }   
            
        $this->db->query('SET foreign_key_checks = 1');        
                        
        if ($this->db->trans_status() === FALSE)        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
        $this->db->trans_complete();
                    
    }
    
        
        
    /**
     * Nodes::delete_file()
     * 
     * @return
     */
    public function delete_file() {
        $this->db->trans_start();                
        $this->db->query('SET foreign_key_checks = 0');                
        //delete items
        $this->db->delete(TABLE_ITEM_DESCRIPTION, array('id' => $this->input->post('id')));
        $this->db->delete(TABLE_ITEMS, array('id' => $this->input->post('id')));                
        $this->db->query('SET foreign_key_checks = 1');        
                       
        if ($this->db->trans_status() === FALSE)        {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
        
        $this->db->trans_complete();             
    } 
    
            

    /** ################## FORMS REQUEST ########################**/
    
    
    /**
     * Nodes::add_form_category()
     * 
     * @return
     */
    public function add_form_category() {
        $data = array('roles' => $this->model->from(TABLE_ROLES)->all());
        $this->ajax(__FUNCTION__, $data);
    }
    
    
    /**
     * Nodes::add_form_file()
     * 
     * @return
     */
    public function add_form_file() {
        $data = null;
        $this->ajax(__FUNCTION__, $data);        
    }
    
    
    
        
    

    
        
    

    
    /** ############## MOVE #################### **/
        
    /**
     * Nodes::move_category()
     * 
     * @return
     */
    public function move_category() {
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        
        $parent_id = ($this->input->post('new_parent') != '#')? $this->input->post('new_parent') : null;
        $new_sequence_id = ($this->input->post('new_position'))? $this->input->post('new_position') : 0;
        $old_sequence_id = ($this->input->post('old_position'))? $this->input->post('old_position') : 0;        
        $this->refresh_category_sequence($parent_id);             
        //update the node transferred  
        $this->db->trans_start();
        $data = array(
                   'parent_id' => $parent_id,
                   'sequence' => $new_sequence_id                   
                   );               
        $this->db->where('id', $id);
        $this->db->update(TABLE_CATEGORIES, $data); 
             
        if ($this->db->trans_status() === FALSE) {        
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();            
        }        
        $this->db->trans_complete();  
        
        
        $this->db->where('id !=', $id);
        $this->db->where('parent_id', $parent_id);
        $this->db->where('sequence >=', $new_sequence_id);
        $this->db->order_by('sequence', 'ASC');        
        $items = $this->db->get(TABLE_CATEGORIES)->result();
        $ctr = $new_sequence_id;    
        if ($new_sequence_id > $old_sequence_id) {
            foreach ($items as $item) {
                $ctr = $ctr - 1;
                $data = array('sequence' => $ctr);               
                $this->db->where('id', $item->id);            
                $this->db->update(TABLE_CATEGORIES, $data);
            }
        } else {
            foreach ($items as $item) {   
                $ctr = $ctr + 1;     
                $data = array('sequence' => $ctr);               
                $this->db->where('id', $item->id);            
                $this->db->update(TABLE_CATEGORIES, $data);
            }
        }           
        $this->output( array('id'           => $this->input->post('id'), 
                            'parent_id'     => $parent_id,
                            'title'         => strip_tags($title), 
                            'type'          => 'default'));
    }
    
    
    /**
     * Nodes::move_file()
     * 
     * @return
     */
    public function move_file() {
        $id = $this->input->post('id');
        $parent_id = ($this->input->post('new_parent') != '#')? $this->input->post('new_parent') : null;
        $new_sequence_id = ($this->input->post('new_position'))? $this->input->post('new_position') : 0;
        $old_sequence_id = ($this->input->post('old_position'))? $this->input->post('old_position') : 0;        
        $this->refresh_file_sequence($parent_id);
        
        //update the node transferred
        $folder_count = $this->model->from(TABLE_CATEGORIES)->where(array('parent_id'=> $parent_id))->count_all_results();                
        $new_sequence_id = $new_sequence_id - $folder_count;
        
        $this->db->trans_start();
        $data = array(
                   'cat_id' => $parent_id,
                   'sequence' => $new_sequence_id                   
                   );               
        $this->db->where('id', $id);
        $this->db->update(TABLE_ITEMS, $data); 
             
        if ($this->db->trans_status() === FALSE) {        
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();            
        }        
        $this->db->trans_complete();
        
        $this->db->where('id !=', $id);
        $this->db->where('cat_id', $parent_id);
        $this->db->where('sequence >=', $new_sequence_id);
        $this->db->order_by('sequence', 'ASC');        
        $items = $this->db->get(TABLE_ITEMS)->result();
        $ctr = $new_sequence_id;    
        if ($new_sequence_id > $old_sequence_id) {
            foreach ($items as $item) {                
                $data = array('sequence' => $ctr);               
                $this->db->where('id', $item->id);            
                $this->db->update(TABLE_ITEMS, $data);
                $ctr = $ctr - 1;
            }
        } else {
            foreach ($items as $item) {   
                $ctr = $ctr + 1;     
                $data = array('sequence' => $ctr);               
                $this->db->where('id', $item->id);            
                $this->db->update(TABLE_ITEMS, $data);
            }
        }           
        $this->output( array('id'=> $this->input->post('id'), 'type'=> 'file'));
    }
    
   
   
    
    /**
     * Nodes::output()
     * 
     * @param mixed $rslt
     * @return
     */
    public function output($rslt) {
  		header('Content-Type: application/json; charset=utf8');
		echo json_encode($rslt);
    }
    
    
    
}