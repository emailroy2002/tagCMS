<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Model {  

    protected $table = TABLE_SETTINGS;
    function __construct($table = null) {	    
    	parent::__construct($this->table);
    }    
                    
    public function general_row_limit() {
        $row = $this->setting->from($this->table)->where('name', 'general_row_limit')->get_row();
        return ($row->value);
    }
    
    
    public function add_setting() {
        $this->db->trans_start();   
        $settings_data = array(
            'name'      => $this->input->post('settings'),
            'value'    => $this->input->post('value')
        );
        $this->db->insert($this->table, $settings_data);
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