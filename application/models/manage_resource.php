<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage_resource extends MY_Model {
    
         
    protected $table = TABLE_RESOURCES;
           
    function __construct() {	  
        echo $this->table ."|";
    	parent::__construct($this->table);
    }    
             
    
    function show($id, $text, $attributes = null) {  
         return anchor(site_url("admin/manage_resources/show/$id"), $text, $attributes);        
    }
    
    function edit($id, $text, $attributes = null) {
         return anchor(site_url("admin/manage_resources/edit/$id"), $text, $attributes);        
    }
    
    function drop($id, $text, $attributes = null) {       
         return anchor(site_url("admin/manage_resources/delete/$id"), $text, $attributes);                 
    }
        
        
            
    function add_new() {
        $this->db->trans_start();   
        $resource_data = array(
            'name'      => $this->input->post('name'),
        );
        $this->db->insert(TABLE_RESOURCES, $resource_data);
        $id = $this->db->insert_id();
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'stat'=> true,
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
}