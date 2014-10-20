<?php 
class Login extends MY_Model { 
    
    function __construct() {	    
    	parent::__construct();
        $this->load->library('form_validation');
        

    }
        
    function index() { 
        if ($this->input->post('login')) {
            echo "Login Authentication!!";    
        }     
    }
}