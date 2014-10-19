<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_model { 
    
	function __construct()	{	   
		parent::__construct();
        $this->load->helper('form');
	}  
    
}