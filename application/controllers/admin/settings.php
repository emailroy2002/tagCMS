<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Admin_controller {
   
    protected $resource = 'settings';   
  
    
	function __construct()	{	   
		parent::__construct();          
	} 

    function index() {
        //echo $this->setting->general_row_limit();
        
        $pagination = $this->paginate->from(TABLE_SETTINGS)->initialize();         
        $data = array(
            'settings' =>  $pagination['data'],
            'page_links' => $pagination['links'],           
            'languages'=> $this->get_languages()            
        );        
   	    $this->view(singular($this->resource), $data);        
    }
    

    
    public function add_settings() {
        //validation of user
        $this->set_settings_validation();
        
        //run Code Igniter Validation        
        if ($this->form_validation->run() == FALSE) {
            
            $fields[] = array(            
                            'fields'=> array(                                                        
                                    array('label'=> 'Setting Name', 'html'=> form_input('settings')),
                                    array('label'=> 'Value', 'html'=> form_input('value'))
                                )
                            );  
                                     
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                          );
                          
            $this->view(__FUNCTION__, $data);
        } else {      
            //Create User Information and add to Users Table
            $res = $this->setting->add_setting();
            
            if ($res['stat'] == true) {
                //echo $res['id'];
                redirect(current_url(), 'refresh');   
            }
        }
    }
    
    public function set_settings_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'settings', 
                 'label'   => 'Name of Setting', 
                 'rules'   => 'trim|required'
              ),
            array(
                 'field'   => 'value', 
                 'label'   => 'Value', 
                 'rules'   => 'trim|required'
              ),
        );        
        return $this->form_validation->set_rules($validation_rules);           
    }
    
    
        
}