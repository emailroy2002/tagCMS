<div id="form" style="height: 800px;line-height:20px; text-align:left;padding-left:22px">
    <h3>Add Category</h3>
    <form id="add_category" name="add_category">
        <input name="form_title" id="form_title" type="text" placeholder="Enter title" class="validate[required]"/>
        <input name="form_slug" id="form_slug" type="text" placeholder="Enter Slug" class="validate[required]"/>
        <textarea name="form_description" id="form_description" placeholder="Enter Description" style="border:1px solid #ccc; width:30%"></textarea>
        
        <div>View : <?php echo form_dropdown('template_view', $this->template->files(), $this->input->get('template')); ?></div>
        <div>Single Article View: <?php echo form_dropdown('template_single_view', $this->template->files(), ($this->input->get('template'))? $this->input->get('template') :  'blog_view.php'   ); ?></div>        
        
        <input id="form_save" type="button" value="Save"/>
    </form>
    
</div>




     