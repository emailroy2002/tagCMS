<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Model {
    
    protected $categories;    
    protected $table = TABLE_USERS;
           
	function __construct()	{	   
		parent::__construct($this->table);          
	} 
 
    /** ######################### USER ACTIONS ######################### **/
    function profile_url($id, $text, $attributes = null) {
         return anchor(site_url("admin/users/profile/$id"), $text, $attributes);        
    }
    
    function profile_edit_url($id, $text, $attributes = null) {
         return anchor(site_url("admin/users/edit_profile/$id"), $text, $attributes);        
    }
    
    function profile_delete_url($id, $text, $attributes = null) {
         return anchor(site_url("admin/users/delete/$id"), $text, $attributes);        
    }
    
    
    
    /** ######################### USER ROLE ACTIONS ######################### **/
    function role_url($id, $text, $attributes = null) {
        return anchor(site_url("admin/users/role/$id"), $text, $attributes);
    }    
                
    function role_edit_url($id, $text, $attributes = null) {
        return anchor(site_url("admin/users/edit_role/$id"), $text, $attributes);
    }    
    
    function role_delete_url($id, $text, $attributes = null) {
         return anchor(site_url("admin/users/delete_role/$id"), $text, $attributes);        
    }
    
    
    
    /** ######################### CONTROLLERS  ######################### **/            
    function add_user() {
        $this->db->trans_start();   
        $user_data = array(
            'username'      => $this->input->post('username'),
            'first_name'    => $this->input->post('first_name'),
            'last_name'     => $this->input->post('last_name'),
            'password'      => $this->input->post('password'),
            'email'         => $this->input->post('email')
        );
        $this->db->insert(TABLE_USERS, $user_data);
        $id = $this->db->insert_id();
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'stat'=> false,
                'id' => null           
            );             
        } else {            
            $this->db->trans_commit();
            $this->db->trans_complete();
            return array(
                'stat'=> true,
                'id' => $id           
            );            
        }
    }
    
    function update_user_info($uid) {
        $this->db->trans_start();        
        $user_data = array();
        foreach ($this->input->post() as $key => $value ) {            
            $fields = $this->db->field_data(trim($this->table));
            foreach($fields as $field) {
                if ($field->name == $key) {
                    if ($value) {
                        $user_data[$key] = $value;    
                    }
                }
            }
        }
        $this->db->update($this->table, $user_data);
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'stat'=> false,
                'id' => null           
            );             
        } else {            
            $this->db->trans_commit();
            $this->db->trans_complete();
            return array(
                'stat'=> true,
                'id' => $uid           
            );            
        }        
    }
    
    function add_role() {
        $this->db->trans_start();   
        $roles_data = array(
            'title'      => $this->input->post('role_title'),
        );
        $this->db->insert(TABLE_ROLES, $roles_data);
        $id = $this->db->insert_id();
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'stat'=> false,
                'id' => null           
            );             
        } else {            
            $this->db->trans_commit();
            $this->db->trans_complete();
            return array(
                'stat'=> true,
                'id' => $id           
            );            
        }        
    }   
    
    function add_permission() {
        
    }
}