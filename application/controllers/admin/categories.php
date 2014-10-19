<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends MY_Admin_controller {
    
    protected $resource = 'categories';
    
    function __construct() {	    
    	parent::__construct();        
        $this->load->library('form_validation');       
    }
    
    
    function add_main_category() {
        $this->view(__FUNCTION__, $data);                  
    }
    
    
    
    public function test() {
        $a = $this->category->get_category_ids(1);        
        print_r ( $a);     
    }
    
    
        
	public function index()	{
	 
        echo $this->path->categories;
        
        echo "=============";
       
        $data = get_categories();
        
        echo "<pre>";
        
        print_r ($data);
        
        
        $this->view(__FUNCTION__, $data);      
	}
    
    public function create() {
        $this->set_validation();
        
        if ($this->form_validation->run()==FALSE) {
            
            $fields['simple'] = array('label' => 'Enter Category Name and Description',
                                      'fields' => $this->fields->exclude('id,sequence')->prepare(TABLE_CATEGORIES, 
                                                    array(
                                                        'parent_id'=> array('label'     => 'Parent Category',
                                                                            'values'    => form_dropdown('parent_id', 
                                                                                                $this->model->from(TABLE_CATEGORIES)
                                                                                                ->prepend(array('id' => null, 'name'=> 'Parent'))
                                                                                                ->order_by('name', 'DESC')
                                                                                                ->options())
                                                                            ),
                                                        'name',
                                                        'description'
                                                    ))->render()
                                    );   
            
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                      );                                            
            $this->view(__FUNCTION__, $data);
        } else {   
            $this->model->save();
            echo "Save this category <br>";
            //@todo: add flash data
            echo "redirect to category index ";
            
        }
    }        
    
    public function read($id) {
        $this->view(__FUNCTION__, '');
    }
    
    public function update($id) {
        $this->view(__FUNCTION__, '');
    }
    
    public function delete() {
        $this->view(__FUNCTION__, '');
    }
    
    public function set_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'name', 
                 'label'   => 'Category name', 
                 'rules'   => 'required'
              ));
        return $this->form_validation->set_rules($validation_rules);        
    }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */