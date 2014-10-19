<div id="form" style="height: 800px;line-height:20px; text-align:left; padding:30px">
    <p>Edit Article</p>
        <div>url : <?php echo $anchor_link;  ?></div>  
    <form id="edit_file" name="edit_file">
    
        <div>
            <input name="form_title" id="form_title" type="text" placeholder="Enter title" class="validate[required]" value="<?php echo $title ?>"/>
        </div>
        
        <div>
            <input name="form_slug" id="form_slug" type="text" placeholder="Enter Slug" class="validate[required]" value="<?php echo $slug ?>"/>
        </div>
        
        <textarea name="form_description" id="form_description"  placeholder="Enter Description" style="border:1px solid #ccc; width:30%"><?php echo $description?></textarea>
       
        
        <input id="form_save" type="button" value="Save"/>        
    </form>
    
    
</div>




     