<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fields extends MY_model {
    
    protected $table;
    
    function __construct($table = null) {	    
    	parent::__construct($this->table);
    }    
    
        
    function initialize($var = null) {
        $this->fields = null;        
    }
    
    
    public function define() {
        print_r ($this->fields);
    }     
    
    function is_field() {
        $fields = $this->db->field_data(trim($table));        
        foreach($fields as $field) {              
            if ($input_field != null) {
                
            }
        }
    }
    /** 
     * @table  - name of the table 
     * @fields - (optional) fields you want to be edited
           */
    function prepare_unsorted($table_array, $fields_allowed = null) {
        $this->fields = null;        
        $tables = explode(',', $table_array);
                
        foreach ($tables as $table) {
            $fields = $this->db->field_data(trim($table));        
            foreach($fields as $field) {              
                if ($fields_allowed != null) {                    
                    if (key_exists($field->name, $fields_allowed)) {
                        //field has options available
                        $this->fields[] = array(
                            'name'          => $field->name,
                            'type'          => $field->type,
                            'max_length'    => $field->max_length,
                            'label'         => $fields_allowed[$field->name],
                            'option'        => $fields_allowed[$field->name]
                        );                       
                    } else if(in_array($field->name,  $fields_allowed)) {
                        $this->fields[] = array(
                            'name'          =>  $field->name,
                            'type'          => $field->type,
                            'max_length'    => $field->max_length
                        );
                    }
                } else {
                    if (!in_array($field->name, isset($this->excluded_fields)? $this->excluded_fields : array() )) {   
                        $this->fields[] = array(
                            'name'          =>  $field->name,
                            'type'          => $field->type,
                            'max_length'    => $field->max_length
                        );
                    } else {
                        //echo $field->name . " is not included | ";
                    }                    
                }
            }
        }
        
        return $this;
    }
    
    function filter($array) {
        $this->filter_array = $array;
        return $this;
    }
    
    function prepare($table_array = null, $fields_allowed = null) {
        $this->fields = null;        
        if ($fields_allowed == null) {            
            $this->prepare_unsorted($table_array,  $fields_allowed);            
        } else {
            
            $fields = $this->prepare_field_keys($fields_allowed); //field to sort
                        
            $tables = explode(',', $table_array);
            foreach ($tables as $table) {
                $table_fields = $this->db->field_data(trim($table));
                foreach ($table_fields as $key => $val) {                    
                     $field_list[trim($table).".".trim($val->name)] = trim($val->name);
                     $field_list[trim($val->name)] = $val;
                } 
            }              

            if (isset($this->filter_array)) {
                $field_values = $this->user->from(TABLE_USERS)->where($this->filter_array)->get_row();    
            }
            
            
            foreach ($fields as $field) {                
                //check field is not excluded field list
                if (!in_array(trim($field), isset($this->excluded_fields)? $this->excluded_fields : array() )) {                    
                    if (isset($field_list[$field]->name)) {
                        if (key_exists($field_list[$field]->name, $fields_allowed)) {
                            //field has options available
                            if (is_array($fields_allowed[$field_list[$field]->name])) {
                                $this->fields[] = array(
                                    'name'  =>  $field_list[$field]->name,
                                    'type'  => $field_list[$field]->type,
                                    'max_length'=> $field_list[$field]->max_length,
                                    //'type'  =>  $fields_allowed[$field_list[$field]->name]['type']
                                    'label' =>  $fields_allowed[$field_list[$field]->name]['label'],
                                    'option' => $fields_allowed[$field_list[$field]->name],
                                    'value' => isset($field_values->{$field_list[$field]->name})? $field_values->{$field_list[$field]->name} : '',
                                );                          
                            } 
                        } else if (in_array($field, $field_list)) {
                            $this->fields[] = array(
                                'name'  =>  $field_list[$field]->name,
                                'type'  => $field_list[$field]->type,
                                'max_length'=> $field_list[$field]->max_length,
                                'value' => isset($field_values->{$field_list[$field]->name})? $field_values->{$field_list[$field]->name} : ''                                
                            );
                       }           
                    }
                } else {
                    //echo "$field is excluded!!!";
                }
            }         
        }
        return $this;
    }
    


    function prepare_field_keys($fields_allowed) { 
        if (is_array($fields_allowed) || $fields_allowed != null) {
            foreach (array_keys($fields_allowed) as $key => $val) {           
                if (is_array($fields_allowed[$val])) {
                    $array[] = $val;
                } else {
                    $array[] = $fields_allowed[$val];
                }
            }    
            return $array;              
        }
    }   
    
    /**
        params - field_names (id, cat_id, title) 
    */
    public function exclude ($field_names) {     
        $fields = explode (",", $field_names);
        $this->excluded_fields = array_map('trim', $fields);
        return $this;
    }
         
       
    /**
     *  &render the fields prepared in HTML format 
    */
    function render() {
        if (isset($this->fields)) {
            $fields = to_object($this->fields);
            foreach ($fields as $field) {
                if (isset($field->option->values)) {                            
                    $form_fields[] = array(
                                        'label'=> (isset($field->option->label))? $field->option->label : humanize(singular($field->name)),
                                        'html'=>  isset($field->option->values)? $field->option->values : null, 
                                    );
                } else {              
                    switch ($field->type) {
                    case 'varchar':
                        $data = array(
                            'name'        => $field->name,
                            'id'          => $field->name,
                            'value'       => '',
                            'class'       => $field->name,
                            'maxlength'   => $field->max_length,
                            'size'        => '20',
                            'value'         => $field->value
                        );                                    
                        $form_fields[] = array('html'=> form_input($data), 'label'=> humanize(singular($field->name)));                                       
                        break;
                    case 'int':
                            $data = array(
                              'name'        => $field->name,
                              'id'          => $field->name,
                              'value'       => '',
                              'maxlength'   => $field->max_length,
                              'size'        => '20'                      
                            );                          
                            $form_fields[] = array('html'=> form_input($data), 'label'=> humanize( singular($field->name)));                    
                        break;      
                    case 'text':
                    case 'mediumtext':
                    case 'longtext':
                    case 'tinytext':
                            $data = array(
                              'name'        => $field->name,
                              'id'          => $field->name,
                              'value'       => '',
                              'maxlength'   => $field->max_length,
                              'size'        => '50'                      
                            );                
                            $form_fields[] = array('html'=> form_input($data), 'label'=> humanize( singular($field->name)));
                        break;                    
                    case 'date':
                    case 'datetime':                
                    case 'timestamp':                    
                    
                        //echo "date"." " . $field->name  ."<BR>";
                        break;
                    } 
                }           
            }
            //return to_object($form_fields);
            return $form_fields;
            
        } else {
            return array();
        }
    }
    
    

}
?>