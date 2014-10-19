<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends MY_Public_controller {
    
    protected $resource = 'articles';
    
    function __construct() {	    
    	parent::__construct();
        $this->load->library('form_validation');
    }
      
         
    public function index()	{
        return $this->output(Theme::init());  
    }
    
    
    public function test() {
        echo "haler";
    }
    
    public function create() {    
       
        $this->set_validation();
        
        if ($this->form_validation->run()==FALSE) {
            
            $fields['simple'] = array( 'label' => 'Simple form generator',
                                       'fields' => $this->fields->prepare(TABLE_ITEM_LOCATION)->render()
                                    );                                    
                                                
            
            $fields['article'] = array( 'label' => 'Article',
                                        'fields' => $this->fields->prepare('item_description, item_business_info, items', 
                                                array(
                                                    'contact_name',
                                                    'cat_id'=> array(                                                                    
                                                                    'label'=> 'Select Categories',
                                                                    'values'=> form_dropdown('cat_id', $this->model
                                                                                    ->order_by('name', 'ASC')                                                                    
                                                                                    ->options(TABLE_CATEGORIES)
                                                                                )
                                                                    ),                                                                                            
                                                    'title'                                                    
                                                ))->render()
                                    );
                                    
                                    
         
            $fields['location'] = array('label'=> 'Location',            
                                        'fields'=> $this->fields->prepare(TABLE_ITEM_LOCATION,
                                                        array(
                                                            'country_code' => array(
                                                                                'label'     => 'Select Country',
                                                                                'values'    => form_dropdown('country_code', $this->model->options(TABLE_COUNTRIES, 'name', 'code'))
                                                                                ),
                                                            'region_id'     => array(
                                                                                'label'     => 'Select Region', 
                                                                                'values'    => form_dropdown('region_id', $this->model->options(TABLE_REGIONS)
                                                                            )
                                                                )
                                                        ))->render());
                                        
                                        
            $fields['validation'] = array('label'=> 'Accept Agreement',            
                                          'fields'=> array(                                                        
                                                        array('label'=> 'Accept Agreement', 'html'=> form_checkbox('newsletter', 'accept', TRUE)),
                                                        array('label'=> 'Subscribe to Newsletter', 'html'=> form_checkbox('newsletter', 'accept', FALSE)),
                                                        array('label'=> 'option', 'html'=> form_checkbox('newsletter', 'accept', TRUE))                    
                                                    )
                                        );
                                                 
            $data = array('fields'  =>  $fields,
                          'validation_errors' => validation_errors(), 
                          'languages'=> $this->get_languages()
                          );
            $this->view(__FUNCTION__, $data);
            
        } else {
             //save newly created stuff
             echo "submitted!!!";
             //redirect to read
        }            
    }        
    
    public function read($category = null) {  
        
       /*
        if (is_category()) {            
            $this->index();            
        } else if (is_article()) {            
            $data = $this->article->get_item();                        
            if ($data) {
                $this->view(__FUNCTION__,  $data);    
            } else {
                show_error('ARTICLE IS NOT FOUND, PLEASE TRY AGAIN SOON.', 404 );    
            }      
        } elseif (is_page()){            
                   
        } else {
            if ($category == null) {                
                $this->index(); //No category found, call index to  List All Items
            } else {
                show_error('INVALID URL, PAGE OR CATEGORY NOT FOUND, PLEASE TRY AGAIN SOON.', 404 );    
            }             
        };*/
        if ($category == null) {  
            $this->index();
        } else {
            $this->index();
        }             
        
         
    }
    
    
    public function update() {
        echo "update";
    }
    
    public function delete() {
        
    }
    
    
    private function set_validation() {
        $validation_rules = array(
           array(
                 'field'   => 'title', 
                 'label'   => 'Article Title', 
                 'rules'   => 'trim|required'
              ));
        return $this->form_validation->set_rules($validation_rules);        
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */