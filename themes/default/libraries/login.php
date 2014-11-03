<?php 
class Login extends CI_Controller { 
    
    function __construct() {	    
    	parent::__construct();
        $this->load->library('form_validation');
    }
        
    function index() { 
        if ($this->input->post('login')) {              
            redirect(base_url(), 'refresh');  
        }     
    }
}