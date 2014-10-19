<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form id="update_category" name="add_category">

    <div id="form" style="line-height:20px; text-align:left; padding:10px; width:65%; float:left">        
        <h3>Edit category </h3>
        <div>url : <?php echo $anchor_link;  ?></div>  
        <div><input name="form_title" id="form_title" value="<?php echo $name ?>" type="text" placeholder="Enter title" class="validate[required]"/></div>
        <div><input name="form_slug" id="form_slug" value="<?php echo $slug ?>"  type="text" placeholder="Enter Slug" class="validate[required]"/></div>
        <div><textarea rows="1" cols="1" name="form_description" id="form_description" placeholder="Enter Description" class="tinyMCE_simple" style="border:1px solid #ccc; width:30%"><?php echo $description ?></textarea></div>
        
        <div>View : <?php echo form_dropdown('template_view', $this->template->files(), $template_view); ?></div>
        <div>Single Article View: <?php echo form_dropdown('template_single_view', $this->template->files(), $template_single_view ); ?></div>
        
        <input id="form_save" type="button" value="Save"/>
    </div>
    
    <div id="side_panel" class="panel">    
        <div>
            Front Page :  <input name="is_frontpage" id="is_frontpage" type="checkbox" <?php echo ($is_frontpage == true) ? 'checked' : ''; ?> />
        </div>    
        <div>
            Article/Page : <input name="items_per_page" id="items_per_page" type="text" value="<?php echo $items_per_page ?>" size="3"/>
        </div>
    </div>
</form>