<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboards extends MY_Admin_controller {
    
    protected  $resource = 'dashboards';
    
    function __construct() {	    
    	parent::__construct();
        $this->load->library('form_validation');
    }
        
	public function index($method = null)	{        
	    $data = array('js_method_action'=> $method);        
	    $this->view(singular($this->resource), $data);        	    
	}
    
	public function test()	{
	    $this->view(__FUNCTION__, null);	    
	}    
}