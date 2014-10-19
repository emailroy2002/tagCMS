<div id="form" style="height: 800px;line-height:20px; text-align:left; padding:30px">
    
    <h3>Edit category </h3>
    <div>url : <?php echo $anchor_link;  ?></div>  
    
    <form id="update_category" name="add_category">
        <div><input name="form_title" id="form_title" value="<?php echo $name ?>" type="text" placeholder="Enter title" class="validate[required]"/></div>
        <div><input name="form_slug" id="form_slug" value="<?php echo $slug ?>"  type="text" placeholder="Enter Slug" class="validate[required]"/></div>
        <div><textarea name="form_description" id="form_description" placeholder="Enter Description" style="border:1px solid #ccc; width:30%"><?php echo $description ?></textarea></div>
        
        <div>View : <?php echo form_dropdown('template_view', $this->template->files(), $template_view); ?></div>
        <div>Single Article View: <?php echo form_dropdown('template_single_view', $this->template->files(), $template_single_view ); ?></div>
        
        <input id="form_save" type="button" value="Save"/>
    </form>
    
</div>




     