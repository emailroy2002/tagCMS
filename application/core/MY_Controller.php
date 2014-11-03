<?php
class MY_controller extends CI_Controller {
        
    function __construct($type, $theme_path) {
        parent::__construct();        
        $this->load->library('parser');
        date_default_timezone_set('UTC');
        $this->type =   $type;
        $this->theme_path = $theme_path;
    } 
    
    function output($data = null, $type = 'text/html') {
        $this->output        
        ->cache(0)
        ->set_content_type($type)
        ->set_output($data);
    }
    
    function ajax($filename, $data) {
         $this->view($filename, $data, false);
    }
        
    function view($filename, $data = true, $assets = true, $output_buffer = false) {        
        $theme_path = $this->theme_path;
        
        if ($this->type == 'admin') {
            $theme_folder = 'light';            
        } else {
            $theme_folder = 'basic';
        }     
        
        $application_header = 'header';
        $application_index  = 'index';
        $application_footer = 'footer';
        
      
        if ($assets == false) { // ajax requests (turn off  assets)
            $final_data = array (            
                'header'    =>  null,
                'yield'     =>  $this->load->view($filename, $data, true),
                'footer'    =>  null,
            );  
        } else {
            $final_data = array (
                'header'    =>  $this->load->view($application_header, '', true),
                'yield'     =>  $this->load->view($filename, $data, true),
                'footer'    =>  $this->load->view($application_footer, '', true),
            );              
        }
        
        if ($this->type == 'admin') {
            $this->load->view($application_index, $final_data, $output_buffer);    
        } else {
            $this->load->view($filename, $final_data, $output_buffer);
        }       
    }
    
    function get_languages() {        
        return $this->model->from(TABLE_LANGUAGES)->get();
    }
}



class MY_Public_controller extends MY_Controller{
	function __construct() {
        parent::__construct('public', 'public/themes');
        $this->load->add_package_path(APPPATH.'/../themes/default/', true);        
        if (file_exists(APPPATH."models/".singular($this->resource).".php")  ) {                        
            $this->load->model(singular($this->resource));
        }        
        if (file_exists(APPPATH."models/".singular($this->router->class).".php")  ) {
            $this->load->model(singular($this->router->class), 'model');
        }
        //Get the additional libraries 
        if ($this->uri->segment(1)) { 
            $class  =   $this->uri->segment(1);
            $method =   $this->uri->segment(2);        
            if (file_exists(APPPATH.'/../themes/default/libraries/'.$class.".php")) { 
                $this->load->library($class);                        
                $method = str_replace('-', '_', $method); 
                if ( method_exists($class, $method) ) {
                    $this->$class->$method();
                } else if ( method_exists($class, 'index') ) {
                    $this->$class->index();
                }
            }
            $this->load->remove_package_path(APPPATH.'/../themes/default/libraries/', TRUE);            
        }
    }   
}

class MY_Admin_controller extends MY_Controller {
	function __construct() {
        parent::__construct('admin', 'admin/themes');                
        $this->load->add_package_path(APPPATH.'/../themes/admin/', true);
        
        if (file_exists(APPPATH."models/".singular($this->resource).".php")) {                        
            $this->load->model(singular($this->resource));
        }
        
        if (file_exists(APPPATH."models/".singular($this->router->class).".php")  ) {
            $this->load->model(singular($this->router->class), 'model');                      
        }          
              
      
    }      
}