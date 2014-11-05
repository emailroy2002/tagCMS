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
        
        <div>
            <p>Order: 
                <select name="order">
                    <option value="ASC" <?php echo (strtolower($order) == 'asc')? "selected": null ?>>ASCENDING</option>
                    <option value="DESC" <?php echo (strtolower($order) == 'desc')? "selected": null ?>>DESCENDING</option>
                </select>            
            </p>
        </div>
        
        <hr/>
        
        <div id="permission">
            <h4>User Role Permissions</h4>
            <b>Website:</b>
            <select>
                <option>Public</option>
                <option>Users</option>
            </select>
            
             <?php //todo: only users will see this ?>                   
            <div id="user_access_role">                
                <?php foreach ($roles as $role) : @$ctr++?>
                    <input type="checkbox" name="user_role_access_ids" value="<?php echo $role->title ?>" /><?php echo $role->title ?><br/>                    
                <?php endforeach; ?>
            </div>
        </div>
        
        
        <div id="permission">            
            <p><b>Administration Area:</b></p>                   
            <div id="user_access_role">                
                <?php foreach ($roles as $role) : @$ctr++?>
                    <input type="checkbox" name="admin_role_access_ids" value="<?php echo $role->title ?>" /><?php echo $role->title ?><br/>                    
                <?php endforeach; ?>
            </div>
        </div>        
        
        
                    
    </div>
</form>