<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class template extends MY_model {
    
    protected $categories;
       
    function __construct() {	    
    	parent::__construct();
    }
    
    
    //TODO: get the theme folder dynamically
    public function current_folder() {
        
    }    

    
    public function files() {        
        //@todo: Add dynamic scanning of themes based on the selected settings of folder
        $dir = scandir('./themes/default/views');
        foreach ($dir as $item) {
           if (!($item == '.' || $item == '..')) {
                if (!is_dir('./themes/default/' . $item)){
                    $ext = explode (".", $item);
                    if ($ext[1] == 'php') {
                         $options[$item] = $item;
                    }
                }
           };        
        }    
        return $options;     
    }

}