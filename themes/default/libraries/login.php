<?php 
class Login extends MY_Model { 
    
    function __construct() {	    
    	parent::__construct();
        $this->load->library('form_validation');
        

    }
        
    function index() {     
         echo "i am ready to see you login!";
        if ($this->input->post('login')) {
            echo "yahoo!!";    
        } else {
            echo "i am ready to see you login!";
        }        
    }
    
 
    
    function authenticate () {
        echo "User Login Authentication";
        exit();
        $this->view(__FUNCTION__);
    }
}