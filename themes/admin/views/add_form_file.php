<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="form" style="line-height:20px; text-align:left; padding:30px">
    <p>Add New Article </p>
    
    <form id="add_file" name="add_category">
    
        <div>
            <input name="form_title" id="form_title" type="text" placeholder="Enter title" class="validate[required]"/>
        </div>
        
        <div>
            <input name="form_slug" id="form_slug" type="text" placeholder="Enter Slug" class="validate[required]"/>
        </div>
        
        <textarea name="form_description" id="form_description" class="tinyMCE"  placeholder="Enter Description" style="border:1px solid #ccc; width:30%"></textarea>
       
        
        <input id="form_save" type="button" value="Save"/>        
    </form>
</div>

