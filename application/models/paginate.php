<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginate extends MY_model {
    
    function __construct($table = null) {        
    	parent::__construct();             
        $this->table = $table;
        $this->load->library('pagination');        
    }
    
    public function filter ($conditions_array = null) {
        $this->conditions_array = $conditions_array; 
        return $this;
    }
        
    public function initialize() {
        $page_row_limit = $this->setting->general_row_limit();
        $total_rows = $this->model->from($this->table)->like(isset($this->conditions_array)? $this->conditions_array : null)->count();
        $config['base_url'] =  site_url(Article::uri_to_array()) ."/page/";
        $config['first_url'] = site_url(Article::uri_to_array());
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $page_row_limit;        
        $config['use_page_numbers'] = TRUE;        
        if ( strstr(uri_string(), 'page') == false) {                          
            $page = 1;
            $config['cur_page'] = 1;            
        } else {
            $page = $this->uri->segment($this->uri->total_segments());    
            $config["uri_segment"] = $this->uri->total_segments();
        }
        
        //PAGE NUMBERING OFFSETS
        if ($total_rows > 0) {
        	if ($page) {
        		$page_limit = $page * $page_row_limit;
        		$start = (int) $page_limit - $page_row_limit;
        	} else {
        		$start = 0;
                $page_limit = $page_row_limit;
        	}            
        } else {
               $start = 0; 
        }
                                
        $this->pagination->initialize($config);
        
        //$data = $this->model->from($this->table)->get_where(isset($this->conditions_array)? $this->conditions_array : null, $this->setting->general_row_limit(), $start);
       
        $data = $this->model->from($this->table)
                    ->like(isset($this->conditions_array)? $this->conditions_array : null) 
                    ->limit($page_row_limit, $start)
                    ->get();
                
        $links = array(            
            'data' => $data,   
            'links'=>   $this->pagination->create_links()
        );
        
        return $links;     
    }   
    
} 