<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_model extends CI_Model {    
    
    protected $limit;
    function __construct($table='') {        
    	parent::__construct();             
        $this->table = $table;
        $this->load->helper('inflector');
    }
        
        
    //Table Modifier
    function from($table = null) {
        if (isset($table)) {
            $this->table = $table;
            return $this;                
        }
    }
    
        
    function all() {                
        return $this->db->get($this->table)->result();  
    }    
    
    function select($query) {
        if ($query != null) {
            $this->db->select($query);        
        }
        return $this;   
    }
    
    function where($field, $value = null) {
        if (is_array($field)) {
            $conditions_array = $field;
            $this->db->where($conditions_array);
        } else {
            $this->db->where($field, $value);    
        }        
        return $this;    
    }
    
    
    
    /** get_where - returns array results from a table 
     *  @conditions - array('id'=> id)
     *  @limit      - @limt
     *  @offset     - @offset
     */
    function get() {
        return $this->db->get($this->table)->result();         
    }
    
    function get_row() {
        return $this->db->get($this->table)->row();         
    }


    /** 
     * @condtions_array -  arrray('id'=> 200, 'slug'=> 'test') 
    **/
    
    function get_where($conditions_array, $limit = null, $offset = null) {
        return $this->db->get_where($this->table, $conditions_array, $limit, $offset)->result();
    }
    
    function or_where ($conditon, $id) {
        $this->db->or_where($conditon, $id);
        return $this;
    }    
     

    function count() {
        return $this->count_all_results();
    }
    
    function count_all_results($table = null) {
        if (isset($table)) {
            return $this->db->count_all_results($table);    
        } else {
            $this->db->from($this->table);
            return $this->db->count_all_results();
        }
    }
    
        
        
    function parent_id($id) {
        if ($id == null) {
            $result = $this->db->where('parent_id is null', $id, false);    
        } else {
            $result = $this->db->where('parent_id', $id);
        }
        return $this;
    }
    
    function limit($n, $offset = null) {
        $this->db->limit($n, $offset);
        return $this;
    }    
    


    function get_by_slug($slug) {
        $result = $this->db->get_where($this->table, array($this->table.'.slug'=> $slug))->row();
        return $result;
    }
    
    function get_by_id($id) {        
        $result = $this->db->get_where($this->table, array($this->table.'.id'=> $id))->row();
        return $result;
    }    
    

    
    function order_by($field, $sort_order) {
        $this->db->order_by($field, $sort_order);
        return $this;    
    }
    
    function having($field, $value) {
        if (is_array($value)) {
            $this->db->having($value); 
        } else {
            $this->db->or_having($field, $value);    
        }
        
        return $this;    
    }
    
    function like($field, $value = null) {
        
        if (is_array($field)) {
            $this->db->like($field); 
        } else {
            if ($field != null) {
                $this->db->like($field, $value);    
            }
        }        
        return $this;    
    }
     
        
    function or_having($field, $value) {
        $this->db->or_having($field, $value);
        return $this;    
    }
    
    function join($table, $conditon) {        
        $this->db->join($table , $conditon);
        return $this;
    }
    
    
    function prepend($array) {
        $this->to_prepend = to_object($array);
        return $this;
    }
    
    function options($table = null, $field_name = "name", $index = 'id') {
         $table = ($table == null)? $this->table : $table;
         if (isset($this->to_prepend)) {
            $options =  $this->db->from($table)->get()->result();
            array_unshift($options,  $this->to_prepend);
         } else{
            $options = $this->db->from($table)->get()->result();   
         }
         foreach ($options as $key => $val) {
            $result[$val->{$index}] = $val->{$field_name};
         }
         return $result;        
    }
        
    function save() {
        $this->db->trans_start();   
        $this->input->post();    
        //@todo : save must be automated
            
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
    
    function delete($array = null) {
        if ($array != null) {            
            $this->db->trans_start();
            $delete = $this->db->delete($this->table, $array);
                         
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array(
                    'stat'=> false                               
                );             
            } else {
                if ($this->db->affected_rows() == 1) {
                    $this->db->trans_commit();
                    $this->db->trans_complete();
                    return array(     
                        'stat'=> true
                    );                      
                } else {
                    return array(     
                        'stat'  => false
                       
                    );                    
                }          
            }
        }
        return $this;         
    }     
}