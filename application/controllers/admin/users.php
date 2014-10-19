<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Admin_controller {
    
    protected $resource = 'users';
    
    function __construct() {	    
        parent::__construct();
    }
    
    public function index() {
        $pagination = $this->paginate->from(TABLE_USERS)->initialize();         
        $data = array(
            'users' =>  $pagination['data'],
            'page_links' => $pagination['links'],           
            'languages'=> $this->get_languages()            
        );        
   	    $this->view(singular($this->resource), $data);
    }
    
    
    public function profile ($uid = null) {
        $data = array(
            'user'         =>  $this->user->select(self::user_entry_fields())->where('uid', $uid)->get_row(),
            'edit_link'     => user::profile_edit_url($uid, "edit"),
            'delete_link'   => user::profile_delete_url($uid, "delete")
        );
        $this->view(singular($this->resource)."_".__FUNCTION__, $data);
    }
    

    //ROLES
    public function roles($id = null) {
        $pagination = $this->paginate->from(TABLE_ROLES)->initialize();
        $data = array(
            'roles' =>  $pagination['data'],
            'edit_link'     => user::role_edit_url($id, "edit"),
            'delete_link'   => user::role_delete_url($id, "delete"),            
            'page_links' => $pagination['links'],
            'validation_errors' => validation_errors(), 
            'languages'=> $this->get_languages()            
        );
        $this->view(singular($this->resource)."_".__FUNCTION__, $data);
    }
    
    
    public function search_user($search = null) { 
        
        $this->set_user_search_validation();
        
        if ($this->form_validation->run() == FALSE) { 
            $this->view(__FUNCTION__);                   
        } else {    
            
            //Search User 
            $field = $this->input->post('field');
            $search = $this->input->post('search');
            
            $pagination = $this->paginate->from(TABLE_USERS)->filter(array("$field"=>$search))->initialize();
            $data = array(
                'users' =>  $pagination['data'],
                'page_links' => $pagination['links'],
                'validation_errors' => validation_errors(), 
                'languages'=> $this->get_languages()            
            );
            $this->view(__FUNCTION__, $data);
        }
    }
    
    
    public function permissions() {
        
        $this->set_permission_validation();
        
        if ($this->form_validation->run() == FALSE) { 
            $roles = $this->paginate->from(TABLE_ROLES)->initialize();
            $resources = $this->model->from(TABLE_RESOURCES)->get();        
            $data = array(
                'roles' =>  $roles['data'],
                'resources' => $resources,
                'page_links' => $roles['links'],
                'validation_errors' => validation_errors(), 
                'languages'=> $this->get_languages()            
            );
            $this->view(__FUNCTION__, $data);
        } else {            
            $res = $this->user->add_permission();
            if ($res['stat'] == true) {
                redirect(current_url(), 'refresh');   
            }            
        }
    }
    
        
    public function add_roles() {
        //validation of user
        $this->set_role_validation();
        
        //run Code Igniter Validation        
        if ($this->form_validation->run() == FALSE) {
            
            $fields[] = array('fields'=> array(
                                            array('label'=> 'Role', 'html'=> form_input('role_title')),
                                        ));          
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                          );
                          
            $this->view(__FUNCTION__, $data);
        } else {      
            //Create User Information and add to Users Table
            $res = $this->user->add_role();
            
            if ($res['stat'] == true) {
                redirect(current_url(), 'refresh');   
            }
        }       
    }
    

    public function add_user() {
        //validation of user
        $this->set_user_validation();
        
        //run Code Igniter Validation        
        if ($this->form_validation->run() == FALSE) {
            
            $fields[] = array('fields' => $this->fields->prepare(TABLE_USERS, self::user_entry_fields())->render());
                            
            $fields[] = array(            
                            'fields'=> array(                                                        
                                    array('label'=> 'Confirm Password', 'html'=> form_input('confirm_password'))
                                )
                            );  
                                     
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                          );
                          
            $this->view(__FUNCTION__, $data);
        } else {      
            //Create User Information and add to Users Table
            $res = $this->user->add_user();
            
            if ($res['stat'] == true) {          
                redirect(admin_url($this->resource), 'refresh');
            }
        }
    }
    
    public function edit_profile($uid) {

        //validation of user
        $this->set_user_validation();
        
        //run Code Igniter Validation        
        if ($this->form_validation->run() == FALSE) {
            
            $fields[] = array('fields' => $this->fields->filter(array('uid'=>$uid))->prepare(TABLE_USERS, self::user_entry_fields())->render());
                            
            $fields[] = array(            
                            'fields'=> array(                                                        
                                    array('label'=> 'Confirm Password', 'html'=> form_input('confirm_password'))
                                )
                            );  
                                     
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                          );
                          
            $this->view(singular($this->resource)."_".__FUNCTION__, $data);            
        } else {      
            //Create User Information and add to Users Table
            $res = $this->user->update_user_info($uid);
            
            if ($res['stat'] == true) {
                redirect(admin_url($this->resource), 'refresh');
            }
        }        
    }
    
    public function delete($id) {        
        $res = $this->user->delete(array('uid'=> $id));
        if ($res['stat'] == true) {
            redirect(admin_url($this->resource), 'refresh');  
        } else {
            echo "ERROR DELETING....";
        };
    }
    
    private function user_entry_fields() {
        $fields = array (
                        'first_name', 
                        'last_name',                         
                        'username',
                        'email',
                        'password',
                    );
        return $fields;
    }
    
    
    private function set_user_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'first_name', 
                 'label'   => 'First Name', 
                 'rules'   => 'trim|required'
              ),           
            array(
                 'field'   => 'last_name', 
                 'label'   => 'Last Name', 
                 'rules'   => 'trim|required'
              ),
            array(
                 'field'   => 'username', 
                 'label'   => 'Username', 
                 'rules'   => 'trim|required'
              ),                 
        );        
        return $this->form_validation->set_rules($validation_rules);        
    }
    
    
    private function set_user_search_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'search', 
                 'label'   => 'Search field', 
                 'rules'   => 'trim|required'
              ),
 
        );        
        return $this->form_validation->set_rules($validation_rules);           
    }
    
    private function set_permission_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'save', 
                 'label'   => 'Search field', 
                 'rules'   => 'trim|required'
              ),           
        );        
        return $this->form_validation->set_rules($validation_rules);           
    }    
    
    
    private function set_role_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'role_title', 
                 'label'   => 'Role field', 
                 'rules'   => 'trim|required'
              ),
 
        );        
        return $this->form_validation->set_rules($validation_rules);        
    }  
}