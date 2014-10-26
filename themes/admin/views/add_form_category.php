<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="form" style="line-height:20px; text-align:left; padding:10px; width:65%; float:left">
    <h3>Add Category</h3>
    <form id="add_category" name="add_category">
    
    
        <input name="form_title" id="form_title" type="text" placeholder="Enter title" class="validate[required]"/>
        <input name="form_slug" id="form_slug" type="text" placeholder="Enter Slug" class="validate[required]"/>
        <textarea name="form_description" id="form_description" placeholder="Enter Description" class="tinyMCE_simple" style="border:1px solid #ccc; width:30%"></textarea>
        
        <div>View : <?php echo form_dropdown('template_view', $this->template->files(), $this->input->post('template')); ?></div>
        <div>Single Article View: <?php echo form_dropdown('template_single_view', $this->template->files(), ($this->input->post('template'))? $this->input->post('template') :  'blog_view.php'   ); ?></div>        
        
        <input id="form_save" type="button" value="Save"/>
    </form>
    
</div>


<div id="side_panel" class="panel">

    <div id="page-options">    
        <div>
            Front Page :  <input id="is_frontpage" name="is_frontpage" type="checkbox" />
        </div>    
        <div>
            Article/Page : <input id="items_per_page" name="per_page" type="text" size="2"/>
        </div>
    </div>
    
    <div id="meta-options">
        <div>
        
        </div>    
    </div>
    
    
</div>

     