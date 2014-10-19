<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_resources extends MY_Admin_controller {
    
    protected $resource = 'manage_resource';
    
    function __construct() {	    
        parent::__construct();        
        $this->load->library('form_validation');
        $this->load->model('setting');
        $this->load->model('paginate');
        $this->load->model('search');
        $this->load->model('user');       
        $this->load->library('pagination');   
    }
    
    public function index() {
        $pagination = $this->paginate->from(TABLE_RESOURCES)->initialize();
        $data = array(
            'resources' =>  $pagination['data'],
            'page_links' => $pagination['links'],           
            'languages'=> $this->get_languages()            
        );        
   	    $this->view(singular($this->resource), $data);
    }
    
    public function show($id) {
        $data = array(
            'resource'      => $this->manage_resource->select(self::resources_fields())->where('id', $id)->get_row(),
            'edit_link'     => manage_resource::edit($id, "edit"),
            'delete_link'   => manage_resource::drop($id, "delete")
        );
        $this->view(singular($this->resource)."_".__FUNCTION__, $data);        
    }
    
    
    public function edit() {
        
    }
    
    public function delete() {
        
    }
    
    

    
    public function add_resource() {
        //validation of user
        $this->set_resource_validation();
        
        //run Code Igniter Validation        
        if ($this->form_validation->run() == FALSE) {
            
            $fields[] = array('fields'=> array(
                                            array('label'=> 'Resource', 'html'=> form_input('name')),
                                        ));          
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                          );
                          
            $this->view(__FUNCTION__, $data);
        } else {      
            //Create User Information and add to Users Table
            $res = $this->manage_resource->add_new();
            
            if ($res['stat'] == true) {
                //echo $res['id'];
                redirect(current_url(), 'refresh');   
            }
        }       
    } 
    
    public function add_section() {
        
        //validation of user
        $this->set_section_validation();
        
        //run Code Igniter Validation        
        if ($this->form_validation->run() == FALSE) {
            
            
            $fields[] = array('label'=> 'Accept Agreement',            
                                          'fields'=> array(                                                        
                                                        array('label'=> 'Parent Resource', 'html'=> form_dropdown('region_id', $this->model->options(TABLE_RESOURCES)) ),                                                        
                                                        array('label'=> 'Set User Default', 'html'=> form_checkbox('default', 'accept', TRUE))                    
                                                    )
                                        );
                                        
                                                    
            $fields[] = array('fields'=> array(
                                            array('label'=> 'Section', 'html'=> form_input('section')),
                                        ));          
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                          );
                          
            $this->view(__FUNCTION__, $data);
        } else {      
            //Create User Information and add to Users Table
            $res = $this->manage_resource->add_new();
            
            if ($res['stat'] == true) {
                //echo $res['id'];
                redirect(current_url(), 'refresh');   
            }
        } 
        
                
    }
    
    
    private function set_resource_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'name', 
                 'label'   => 'Resource Name', 
                 'rules'   => 'trim|required'
              ),
        );        
        return $this->form_validation->set_rules($validation_rules);
    } 
    
    private function set_section_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'section', 
                 'label'   => 'Section', 
                 'rules'   => 'trim|required'
              ),
        );        
        return $this->form_validation->set_rules($validation_rules);
    }
    
    private function resources_fields() {
        
    }

}