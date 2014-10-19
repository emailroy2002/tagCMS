<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends MY_model {    
    protected $categories;    
    protected $table = TABLE_ROLES;
    
	function __construct()	{	   
		parent::__construct($this->table);          
	} 
 }    