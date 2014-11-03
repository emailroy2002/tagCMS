<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme {   
  
    private static $key; 
    public static $content, $partial_content, $article, $category, $current_category;
        
    public static $ctr = 0;    
    
    function __construct($table='') {        
    	parent::__construct();
    }
    
    function init() {
        self::select_template();
        self::parse_template(); 
        self::tag_manager(); 
        //Re-Run tag manager twice to get the tags running on dynamic data
        self::parse_template();         
        self::tag_manager(); 
        return (self::$content);
    }
        
    function get_partial_content($filename) {       
        $file = "./themes/default/views/$filename";
        ob_start(); //Init the output buffering
        include($file); //Include (and compiles) the given file
        return ob_get_clean(); //Get the buffer and erase it         
   }
        
    function select_template() {
        $ci =& get_instance();  
        if (slug()) { 
            //determine template using current URI string
            $ci->current_category = $ci->category->current_category();
            if (is_category()) {
                if (isset($ci->current_category->id)) {
                    self::$content = self::get_partial_content($ci->current_category->template_view);    
                } else {
                    show_error('SORRY! CATEGORY URL IS NOT VALID.', 404);
                }                               
            } else if (is_article()) {
                if (isset($ci->current_category)) {
                    self::$content = self::get_partial_content($ci->current_category->template_single_view);    
                } else {                  
                    //@todo: get $slug where parent is a item category type is a 'menu'
                    $slug   = slug();
                    $item   = $this->model->from(TABLE_ITEMS)->where(array('slug'=> $slug))->get_row();           
                    $cat    = $this->model->from(TABLE_CATEGORIES)->where(array('id'=>$item->cat_id, 'type'=> 'menu'))->get_row();
                    if (isset($cat)) {
                        $ci->current_category = $cat;
                        self::$content = self::get_partial_content($cat->template_single_view);    
                    } else {
                        show_error('PAGE IS INVALID.', 404);
                    }              
                }                                       
            } else {
                show_error('SORRY! URL, PAGE OR CATEGORY NOT FOUND, PLEASE TRY AGAIN SOON.', 404);                
            }
        } else {
            $ci->frontpage = $this->model->from(TABLE_CATEGORIES)->where(array('is_frontpage'=>true) )->get_row();
            if (isset($ci->frontpage->id)) {
                $uri_path = $this->path->get_category_link($ci->frontpage->id, null, 'segments');                                                                
                $ci->current_category = $ci->category->current_category($uri_path);
                self::$content = self::get_partial_content($ci->frontpage->template_view);
            } else {
                //todo : make dynamic
                self::$content = self::get_partial_content('blog.php');
            }           
        }
    }
    
    
    public function selected_category() {
        $ci =& get_instance(); 
        if (isset($ci->current_category)) {
            return  $ci->current_category;    
        } else {
            return null;
        }
    }
        
    /** parse_template - RECURSE INCLUDED TEMPLATE **/
    function parse_template() {                        
        $method = array(
            'include:partial'      => 'include_tag_manager', //function name
        );         
        foreach (array_keys($method) as $key) {
            self::$content = preg_replace_callback(
                    array(
                        "@<tag:".$key."[^>]*?>.*?</tag:$key>@siu",
                        "@<tag:".$key."[^>]*?/>.*?@siu"
                    ),               
                    "self::$method[$key]", 
                    self::$content,
                    -1, $count
            );                       
        }
        /** recurse the template  to assure that all partial files are parsed propelry **/ 
        if  ($count > 0) self::parse_template();        
    }    
    
    
    
    /**
     * @key     - string $key 
     * @string  - string replacment text
    */
    function tag_replace($key, $replacement) {
       $tags = Theme::extract_tags(self::$content, array('tag:'.$key), true, true);       
       foreach ($tags as $tag) {       
            self::$content = self::replace(self::$content, $replacement , $key, false, 1);
       }
       return self::$content;
    }        
    
    
    
    function tag_manager() {      
        $ci =& get_instance();        
        $class = __CLASS__;
        //SETTINGS FOR URL, THEME AND ETC
        self::tag_replace("base_url", base_url());
        self::tag_replace("site_url", site_url());
        self::tag_replace("site_title","AS INVENT");
        self::tag_replace("site_description","Inspired Ideas");
        self::tag_replace("year", date('Y'));
        
        $category = isset($ci->current_category)? $ci->current_category : null;
        
        if (is_object($category) && (count(get_object_vars($category)) > 0) ) {
            
            if (is_category()) {
                //CATEGORY META INFORMATION 
                self::tag_replace("page_title", $category->name);
                self::tag_replace("meta_description", strip_tags($category->description));                
                //for page
                self::tag_replace("category_title", $category->name);
                self::tag_replace("category_description", $category->description);                
            } else {                
                //ITEM META INFORMATION
                $item = flatten_object($ci->article->get_item());
                if (isset($item->id)) {
                    self::tag_replace("page_title", $item->title ." - ". $category->name . " - ".  "INSPIRED IDEAS");
                    self::tag_replace("meta_description", strip_tags($item->description) );               
                    self::tag_replace("meta_keywords", "");                      
                } else {
                    self::tag_replace("page_title",$category->name . " - ".  "INSPIRED IDEAS");
                    self::tag_replace("meta_description", "" );               
                    self::tag_replace("meta_keywords", "");                    
                }                          
            }         
        } else {             
            //todo: get from settings if available
            if (isset($ci->frontpage->id)) {
                self::tag_replace("category_title", $ci->frontpage->name);
                self::tag_replace("category_description", $ci->frontpage->description);                
            } else {
                //no front page link set
                self::tag_replace("category_title", "AS INVENT");
                self::tag_replace("category_description", "inspired ideas");
                
                //@todo - GET GENERAL SITE INFORMATION dynamicall            
                self::tag_replace("page_title", "INSPIRED IDEAS");
                self::tag_replace("meta_description", "Information technology company located in Qatar");               
                self::tag_replace("meta_keywords", "Information technology company located in Qatar");                                
            }            
        }
        //TAG FOR ARTICLES, CATEGORIES AND PAGINATION
        $keys = array ('categories', 'articles', 'article:page', 'pagination');
                
        //keys to method name
        $method = array(
            'categories'        => 'categories_tag_manager',            
            'articles'          => 'articles_tag_manager',
            'article:page'      => 'article_tag_manager',
            'pagination'        => 'pagination_tag_manager'
        );
        
        foreach ($keys as $key) {
            self::$content = preg_replace_callback(
                    array(
                        "@<tag:".$key."[^>]*?>.*?</tag:$key>@siu",
                        "@<tag:".$key."[^>]*?/>.*?@siu"
                    ),
                    "$class::$method[$key]", 
                    self::$content,
                    -1, $count
            );                       
        }
        
        //user information
        self::tag_replace("username", "Administrator");
        return (self::$content);
    }

    
    
    
    function pagination_tag_manager($matches) {
        $rows = null;            
        $ci =& get_instance();             
        foreach ($matches as $match) {
            $pages = Theme::extract_tags($match, array('tag:pagination'));
            foreach ($pages as $page) {
                if (isset($page['attributes']['for'])) {
                    $id_for =  $page['attributes']['for'];            
                    $pagination_links = isset($ci->pagination_link[$id_for])? $ci->pagination_link[$id_for] : null;
                    self::$content = self::replace($match, $pagination_links , 'pagination');
                }                
            }
        }
        return self::$content;
    }
  
  
    function include_tag_manager($matches) {        
        $rows = null;            
        $ci =& get_instance();
        foreach ($matches as $match) {            
            $includes = Theme::extract_tags($match, array('tag:include:partial'));
            foreach ($includes as $include) {                
                $partial_content =  self::get_partial_content ($include['attributes']['filename']);
                self::$content = self::replace($match, $partial_content , 'include:partial');
            }
        }
        return self::$content;
    }
  

    
    function subcategories_tag_manager($items) {
        $ci =& get_instance();        
        $ci->temp_tag = $ci->subcategory_tag;
        
        if (sizeof($items) > 0) {
            $ci->rows .= '<ul>';
            
            foreach ($items as $sub_item) {
               $ci->rows .= "<li class='$sub_item[slug]'>";
                                       
               foreach ($ci->keys as $sub_key) {
                    if (isset($sub_item[$sub_key])) {
                        $ci->temp_tag = self::replace($ci->temp_tag, $sub_item[$sub_key], $sub_key);
                    }                                        
               }
               
                //get category link for //url and //link
                $link = $ci->path->get_category_link($sub_item['id'], $sub_item['name'], 'link');
                $url = $ci->path->get_category_link($sub_item['id'], $sub_item['name'], 'url');
                               
                $ci->temp_tag = self::replace($ci->temp_tag, $link, 'link');                                  
                $ci->temp_tag = self::replace($ci->temp_tag, $url, 'url');
                
               
                //Add marker if there are any subcategories
                $children = Theme::extract_tags($ci->temp_tag, array('tag:has_children'));
                foreach ($children as $child) {
                    if (sizeof($sub_item['subcategories']) > 0) {
                        $ci->temp_tag = self::replace($ci->temp_tag, $child['contents'], 'has_children', true);      
                    } else {
                        $ci->temp_tag = self::replace($ci->temp_tag, " ", 'has_children', true);
                    }                                    
                }               
               
               $ci->rows .= $ci->temp_tag;
               
               //get 3rd level subcategories
               if (is_array($sub_item['subcategories'])){
                    self::subcategories_tag_manager($sub_item['subcategories']);                                             
               }
               
            
               $ci->rows .= "</li>";
           }
           $ci->rows .= '</ul>';
           
                      
           $ci->subcategories .=   $ci->rows;
           $ci->rows = null;
       }     
    }
    
    
     
    function categories_tag_manager($matches) {
        $ctr = 0;
        $rows = null;  
        $ci =& get_instance();
        
        $root_category = null;
        
        foreach ($matches as $match) {
            $categories = Theme::extract_tags($match, array('tag:categories'));
            //main tag
            $ci->category_tag = $categories;
                                                        
            foreach ($categories as $category) { 
                /** Start Parent Attributes **/
                
                //Determine  MultiLevel Category Menu  (True, False)
                $multilevel = (isset($category['attributes']['multilevel']))? to_boolean($category['attributes']['multilevel']) : null;
                $scope = (isset($category['attributes']['scope']))? $category['attributes']['scope'] : null; 
                               
                
                //type and class for parent tag
                $type   = (isset($category['attributes']['type']))? $category['attributes']['type'] : 'div';
                $id     =  (isset($category['attributes']['id']))? $category['attributes']['id'] : '';                                                
                $class  = (isset($category['attributes']['class']))? $category['attributes']['class'] : '';
                
                //Determine the Root category
                $root_slug = (isset($category['attributes']['root']))? $category['attributes']['root'] : null;
		
		//Determenie parent slug (this will not show the top level tags of said slug)
                $parent_slug = (isset($category['attributes']['parent']))? $category['attributes']['parent'] : null;
                if (isset($root_slug)) {
                    $root_category_slug = $root_slug;
                } else {
                    $root_category_slug = $parent_slug;
                }              
                
                if (isset($root_category_slug)) {                    
                    $root_category = $ci->model->from(TABLE_CATEGORIES)->where(array('slug'=> $root_category_slug))->get_row();
                    if (isset($root_category->id)) {
                        $items = $ci->category->get_categories($root_category->id);        
                    }                       
                } else {                          
                   $items = $ci->category->get_categories();
                }  
                

                
                //GET SUB ITEMS ONLY without the PARENT LINK
                if (isset($parent_slug)) {
                    if (isset($root_category->id)) {
                        $items = $ci->category->sub_categories($root_category->id);    
                    }
                }
                
                if (isset($items)) {
                    
                    if (count($items) > 0) {
                        
                        $first_item = array_shift(array_slice($items, 0, 1));  
                                              
                        $field_keys = array_keys(to_array($first_item));
                        
                        $ci->field_keys = $field_keys;                                    
                                    
                                  
                        $category_tag_details = Theme::extract_tags($category['contents'], array('tag:category'));                      
                                                                                           
                        foreach ($category_tag_details as $tag_details) {
                            
                            /** start [parent html tag] **/                                                        
                            $rows = "\n<$type id='$id' class='$class'>" . PHP_EOL;
                                                                
                            foreach ($items as $item) {
                                
                                /** Start Children Attributes **/
                                $details_type = (isset($tag_details['attributes']['type']))? $tag_details['attributes']['type'] : 'div';                            
                                $details_class = (isset($tag_details['attributes']['class']))? $tag_details['attributes']['class'] : $item['slug'];
                                
                                
                                
                                /** start [children html tags] **/
                              
                                $rows .= "\t<$details_type class='$details_class'>";  
                                                                                                                                                        
                                //initiate the keys that were successfully replaced by function 'replace'                                   
                                $ci->keys = array(); 
                                $ci->subcategories = null; //reset subcategory array
                                $ci->main_tag = $tag_details['contents'];
                                $ci->subcategory_tag = $tag_details['contents']; //pass the subcategory for TAG reference
                                
                                //prepare link tag for main category                                                                  
                                foreach ($ci->field_keys as $key) {
                                    if (is_array($item[$key])) {
                                        if ($multilevel == true || strtolower($scope) == 'global') {
                                            //get subcategores (2nd level)
                                            self::subcategories_tag_manager($item[$key]);
                                        }
                                    }  else {
                                        //get the main categories                                       
                                        $ci->main_tag = self::replace($ci->main_tag, $item[$key], $key, true);
                                    }
                                } 
                                    
                                $link = $ci->path->get_category_link($item['id'], $item['name'], 'link');
                                $url = $ci->path->get_category_link($item['id'], $item['name'], 'url');                                    
                                                                  
                                $content = self::replace($ci->main_tag, $link, 'link', true);                                  
                                $content = self::replace($content, $url, 'url', true);
                                
                                //Add marker if there are any subcategories
                                $children = Theme::extract_tags($tag_details['contents'], array('tag:has_children'));
                                foreach ($children as $child) {
                                    if (sizeof($ci->subcategories) > 0) {
                                        $content = self::replace($content, $child['contents'], 'has_children', true);      
                                    } else {
                                        $content = self::replace($content, " ", 'has_children', true);
                                    }                                    
                                }
                                
                                
                                $rows .= $content;
                                
                                //$rows .= sizeof($ci->subcategories)." ". $ci->subcategories; //add the subcategories
                                
                                
                                
                                $rows .= $ci->subcategories; //add the subcategories
                                
                                $rows .= "</$details_type>" . PHP_EOL;
                                /** [end] children tag **/
                                                            
                            }
                            
                            $rows .= "\n</$type>";
                            /** [end] parent tag **/                            
                        } 
                        
                    } else {
                        $rows = "<p class='error'>No Categories Found</p>";
                    }
                    
                } else {
                    $rows = "<p class='error'>No Categories Found</p>";
                }
                        
            }
             
            return $rows;
        }         
        
    } 
    
  
    function article_tag_manager($matches) {
        $rows = null;            
        $ci =& get_instance();                   
        foreach ($matches as $match) {
            $articles = Theme::extract_tags( $match, array('tag:article:page'));                            
            foreach ($articles as $article) {  
                
                $items = $ci->article->get_item();
                                
                if (isset($items[0])) {                    
                    $field_keys = array_keys(to_array($items[0]));                            
                    $content = '';
                    foreach ($items as $item) { 
                        $content = $article['contents'];                        
                        foreach ($field_keys as $key) {                                
                            $content = self::replace($content, $item->{$key}, $key);
                        }
                       // $rows .= get_link($item->id);
                        $rows .= $content;                            
                    }                    
                } else {
                    //show_error('SORRY! PAGE IS NOT FOUND.', 404);
                    return "<div class='error'>sorry, page not found</div>";
                }          
            } 
            
              
            
            $parser = new JBBCode\Parser();
            $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
            //$parser->addBBCode("php", '<pre id="PHP" class="brush: php;">'.htmlspecialchars('{param}').'</pre>');             
            $parser->addBBCode("php", ('<pre class=brush:php>&#60;&#63;php'.  htmlspecialchars('{param}') .'&#63;&#62;</pre>'));        
            $parser->addBBCode("c++", '<pre id="C++" class="brush: c++;">'.htmlspecialchars('{param}').'</pre>');                
            $parser->parse($rows);            
            $text = $parser->getAsHTML();
            return $text;
        
                    
            //return $rows;       
        }
    }            
    
    
    function articles_tag_manager ($matches) { 
            $ctr = 0;
            $rows = null;            
            $ci =& get_instance();
            $ci->load->library('pagination');
                               
            foreach ($matches as $match) {
              
                $articles = Theme::extract_tags( $match, array('tag:articles'));
                                            
                foreach ($articles as $article) { 
                    
                    //type and class for parent tag
                    $type   = (isset($article['attributes']['type']))? $article['attributes']['type'] : 'div';
                    $id     =  (isset($article['attributes']['id']))? $article['attributes']['id'] : '';                                                
                    $class  = (isset($article['attributes']['class']))? $article['attributes']['class'] : '';
                    $scope = (isset($article['attributes']['scope']))? $article['attributes']['scope'] : null;
                                        
                    //store current category for original before override (used for pagination)
                    if (isset($ci->current_category)) {
                        $current_category = $ci->current_category;
                        $ci->article->set_order($current_category->order);
                        //echo $ci->current_category->name ." ". $ci->current_category->order."<Br>";
                    }
                    
                    //overide current category if there is a tag "PARENT"                            
                    $parent = (isset($article['attributes']['parent']))? $article['attributes']['parent'] : null;                        
                    if (isset($parent)) {
                        //tag has parent, override process below                        
                        $parent = $ci->model->from(TABLE_CATEGORIES)->where(array('slug'=> $parent))->get_row();                        
                        if (isset($parent->id)) {
                            $ci->current_category = $parent;                           
                        }
                        $recursion_type = ($scope == 'global') ?  true :  false;                    
                        $total_rows = $ci->article->count($ci->current_category->id, $recursion_type);
                                            
                    } else {
                        //no parent                                                
                        $recursion_type = ($scope == 'global') ?  true :  false;                    
                        $total_rows = $ci->article->count(null, $recursion_type);                        
                    }
                    
                    if (isset($article['attributes']['pagination'])) {
                        if (($article['attributes']['pagination']) > 0) {
                            $page_row_limit = (isset($article['attributes']['pagination']))? $article['attributes']['pagination'] : null;    
                        } else {
                            $page_row_limit = 0;
                        }
                    } else {                        
                         if (isset($ci->current_category->items_per_page)){
                            if ($ci->current_category->items_per_page > 0) {
                                //$page_row_limit = $ci->current_category->items_per_page;
                                $page_row_limit = $current_category->items_per_page;
                            } else {
                                $page_row_limit = 0;
                            }
                        } else {
                            $page_row_limit = $ci->setting->general_row_limit();
                        }
                   }                        
                
                
                  if ($page_row_limit >= 1)  {    
                        //CONFIGURATION FOR PAGE LINKS
                        $config['base_url'] = site_url(Article::uri_to_array()) ."/page/";
                        $config['first_url'] = site_url(Article::uri_to_array());
                        $config['total_rows'] = $total_rows;
                        $config['use_page_numbers'] = TRUE;                       	
                        $config['per_page'] = $page_row_limit;
                        $config['num_links'] = 15;    
                                                                       
                        if ( strstr(uri_string(), 'page') == false) {                          
                            $page = 1;
                            $config['cur_page'] = 1;            
                        } else {
                            $page = $ci->uri->segment($ci->uri->total_segments());    
                            $config["uri_segment"] = $ci->uri->total_segments();
                        }
                        $ci->pagination->initialize($config);
			
                        //PAGE NUMBERING OFFSETS
                        if ($total_rows > 0) {
                    		if ($page) {
                    			$page_limit = $page * $page_row_limit;
                    			$start = (int) $page_limit - $page_row_limit;
                    		} else {
                    			$start = 0;
                                $page_limit = $page_row_limit;
                    		}            
                        } else {
                               $start = 0; 
                        }
                        
                        //create the pagination links
                        $page_boundary = ceil($total_rows /  $page_row_limit);
                        if ($page <= ceil($page_boundary)) {
                            $ci->pagination_link[$id] =  $ci->pagination->create_links();    
                        } 
                          
                        //get all items paginatied
                        $items = $ci->article->get_items( null, (isset($page_row_limit))? $page_row_limit : 1, ($start <= 0) ? 0 : $start, $recursion_type);
                        
                    } else {
		              	//Row limit is null
                        if (isset($parent)) {
                            //get item with current cateogry parent id
                            $items = $ci->article->get_items($ci->current_category->id);
                        } else {
                            //get all items witouth pagination
                            $items = $ci->article->get_items();    
                        }
                    }

                    
                    if (count($items) > 0) {
                                                
                        $field_keys = array_keys(to_array($items[0]));
                                                
                        $article_details = Theme::extract_tags($article['contents'], array('tag:article'));                     
                                                                                           
                        foreach ($article_details as $details) {
                            
                            /** start [parent tag] **/                                                        
                            $rows = "\n<$type id='$id' class='$class'>" . PHP_EOL;
                                                                
                            foreach ($items as $item) { 

                                $details_type = (isset($details['attributes']['type']))? $details['attributes']['type'] : 'div';                            
                                $details_class = (isset($details['attributes']['class']))? $details['attributes']['class'] : '';
                                $anchor_text_limit = (isset($details['attributes']['anchor_text_limit']))? $details['attributes']['anchor_text_limit'] : '';
                                $content = $details['contents'];
                                
                                /** start [children tags] **/
                                $rows .= "\t<$details_type class='$details_class'>";
                                
                                $content = self::replace($content, get_link($item->id, 'url'), 'url');
                                $content = self::replace($content, get_link($item->id, 'link', $anchor_text_limit), 'link');
                                $content = self::replace($content, @$ctr += 1, 'ctr');
                                
                                foreach ($field_keys as $key) {
                                    $content = self::replace($content, $item->{$key}, $key, false, 1);    
                                }
                                                        
                                $rows .= $content;
                                
                                $rows .= "</$details_type>" . PHP_EOL;
                                /** [end] children tag **/
                                                            
                            }
                            
                            $rows .= "\n</$type>";
                            /** [end] parent tag **/                            
                        } 
                    } else {
                        $rows = "<p class='error'>No items found</p>";
                    }
                            
            } 
            
            $parser = new JBBCode\Parser();
            $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
            //$parser->addBBCode("php", '<pre id="PHP" class="brush: php;">'.htmlspecialchars('{param}').'</pre>');             
            $parser->addBBCode("php", ('<pre class=brush:php>&#60;&#63;php'.  htmlspecialchars('{param}') .'&#63;&#62;</pre>'));        
            $parser->addBBCode("c++", '<pre id="C++" class="brush: c++;">'.htmlspecialchars('{param}').'</pre>');                
            $parser->parse($rows);            
            $text = $parser->getAsHTML();
            return $text;
                        
            //return $rows;
        }         
    }
    


    //iterate = (all = -1) , (just once = 1)
    function replace($string, $replacement, $key, $recall = false, $iterate = -1) {
        $ci =& get_instance();
       
        $ci->replacement = $replacement;
        
        //format attributes [date, text]     
        $attr = Theme::extract_tags($string, array("tag:$key"));        
        if (isset($attr[0])) {
        
            if (isset($attr[0]['attributes']['date_format'])) { 
                $date = strtotime($ci->replacement);               
                $ci->replacement = date($attr[0]['attributes']['date_format'], $date);
            } 
            
            if (isset($attr[0]['attributes']['text'])) { 
                $text_limits = $attr[0]['attributes']['text'];
                self::clean_text($text_limits);
            }
        }
                                            
        $output = preg_replace(
                array(
                    "@<tag:".$key."[^>]*?>.*?</tag:$key>@siu",
                    "@<tag:".$key."[^>]*?/>@siu"
                ), 
                $ci->replacement, 
                $string,
                $iterate,
                $count                
        );
        
        if ($recall == true && $count > 0) {
            $ci->keys[] = $key;
        } else {
           
        }              
            
        return $output; 
    }
    
    
    function clean_text($text_limits){
        $ci =& get_instance();
        $ci->load->helper('text');        
        if ($text_limits) {
            $limits = explode (",", $text_limits);
                                                    
            foreach ($limits as $limit) {
                
                $txt_limit = explode("|", $limit);  
                                      
                $case = trim($txt_limit[0]);
                $value = trim($txt_limit[1]);      
                
                if ($case == 'limit') {
		    
                    //$text = character_limiter( strip_tags($ci->replacement), $value);
                    $text  = shorten( strip_tags($ci->replacement), 0, $value);
                    $ci->replacement = strip_bbcode($text);
                }
                              
                if ($case == 'paragraph') {
		    
                    $text = null;
                    $result = preg_split('/(?<=[.?!;:])\s+/', $ci->replacement, -1, PREG_SPLIT_NO_EMPTY);                            
                    for($i=0; $i < $value ; $i++) {
                        if (isset($result[$i])) {
                            $text .= $result[$i];    
                        }
                    }
                    if (sizeof($result) > $value) {
                        $ci->replacement = $text ."....";    
                    } else {
                        $ci->replacement = $text;
                    }                            
                }                        
                
                if ($case == 'strip') {
		    
                    if ($value == 'html') {
                        $ci->replacement = strip_tags($ci->replacement);
			
                    } else if ($value == 'linebreak' || $value == 'linebreaks'){
                        $ci->replacement = preg_replace( "/\r|\n/", " ", $ci->replacement );
			
                    }
                }
		
		
            }    
                                                       
        }        
    }
            
    
    /**
     * extract_tags()
     * Extract specific HTML tags and their attributes from a string.
     *
     * You can either specify one tag, an array of tag names, or a regular expression that matches the tag name(s). 
     * If multiple tags are specified you must also set the $selfclosing parameter and it must be the same for 
     * all specified tags (so you can't extract both normal and self-closing tags in one go).
     * 
     * The function returns a numerically indexed array of extracted tags. Each entry is an associative array
     * with these keys :
     *  tag_name    - the name of the extracted tag, e.g. "a" or "img".
     *  offset      - the numberic offset of the first character of the tag within the HTML source.
     *  contents    - the inner HTML of the tag. This is always empty for self-closing tags.
     *  attributes  - a name -> value array of the tag's attributes, or an empty array if the tag has none.
     *  full_tag    - the entire matched tag, e.g. '<a href="http://example.com">example.com</a>'. This key 
     *                will only be present if you set $return_the_entire_tag to true.      
     *
     * @param string $html The HTML code to search for tags.
     * @param string|array $tag The tag(s) to extract.                           
     * @param bool $selfclosing Whether the tag is self-closing or not. Setting it to null will force the script to try and make an educated guess. 
     * @param bool $return_the_entire_tag Return the entire matched tag in 'full_tag' key of the results array.  
     * @param string $charset The character set of the HTML code. Defaults to ISO-8859-1.
     *
     * @return array An array of extracted tags, or an empty array if no matching tags were found. 
     */
    function extract_tags( $html, $tag, $selfclosing = null, $return_the_entire_tag = false, $charset = 'ISO-8859-1' ){
         
        if ( is_array($tag) ){
            $tag = implode('|', $tag);
        }
         
        //If the user didn't specify if $tag is a self-closing tag we try to auto-detect it
        //by checking against a list of known self-closing tags.
        $selfclosing_tags = array( 'area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta', 'col', 'param' );
        
        
        //the ftl tags that needs to be auto closed 
        $ftl_tags = array(
                        'tag:site_title', 'tag:meta_title', 'tag:meta_description', 'tag:meta_keywords',
                        'tag:include:partial', 'tag:pagination', 
                        'tag:link','tag:url', 'tag:id', 'tag:cat_id', 
                        'tag:title', 'tag:description', 'tag:date_published'
                         );
        
        $selfclosing_tags =array_merge($selfclosing_tags, $ftl_tags);
        

        if ( is_null($selfclosing) ){
            $selfclosing = in_array( $tag, $selfclosing_tags );
        }
         
        //The regexp is different for normal and self-closing tags because I can't figure out 
        //how to make a sufficiently robust unified one.
        if ( $selfclosing ){
            $tag_pattern = 
                '@<(?P<tag>'.$tag.')           # <tag
                (?P<attributes>\s[^>]+)?       # attributes, if any
                \s*/?>                   # /> or just >, being lenient here 
                @xsi';
        } else {
            $tag_pattern = 
                '@<(?P<tag>'.$tag.')           # <tag
                (?P<attributes>\s[^>]+)?       # attributes, if any
                \s*>                 # >
                (?P<contents>.*?)         # tag contents
                </(?P=tag)>               # the closing </tag>
                @xsi';
        }
         
        $attribute_pattern = 
            '@
            (?P<name>\w+)                         # attribute name
            \s*=\s*
            (
                (?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)    # a quoted value
                |                           # or
                (?P<value_unquoted>[^\s"\']+?)(?:\s+|$)           # an unquoted value (terminated by whitespace or EOF) 
            )
            @xsi';
     
        //Find all tags 
        if ( !preg_match_all($tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ){
            //Return an empty array if we didn't find anything
            return array();
        }
         
        $tags = array();
        foreach ($matches as $match){
             
            //Parse tag attributes, if any
            $attributes = array();
            if ( !empty($match['attributes'][0]) ){ 
                 
                if ( preg_match_all( $attribute_pattern, $match['attributes'][0], $attribute_data, PREG_SET_ORDER ) ){
                    //Turn the attribute data into a name->value array
                    foreach($attribute_data as $attr){
                        if( !empty($attr['value_quoted']) ){
                            $value = $attr['value_quoted'];
                        } else if( !empty($attr['value_unquoted']) ){
                            $value = $attr['value_unquoted'];
                        } else {
                            $value = '';
                        }
                         
                        //Passing the value through html_entity_decode is handy when you want
                        //to extract link URLs or something like that. You might want to remove
                        //or modify this call if it doesn't fit your situation.
                        $value = html_entity_decode( $value, ENT_QUOTES, $charset );
                         
                        $attributes[$attr['name']] = $value;
                    }
                }
                 
            }
             
            $tag = array(
                'tag_name' => $match['tag'][0],
                'offset' => $match[0][1], 
                'contents' => !empty($match['contents'])?$match['contents'][0]:'', //empty for self-closing tags
                'attributes' => $attributes, 
            );
            
            if ( $return_the_entire_tag ){
                $tag['full_tag'] = $match[0][0];            
            }
              
            $tags[] = $tag;
        }
         
        return $tags;
    }
}    