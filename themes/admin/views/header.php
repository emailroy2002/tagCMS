<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript" src="<?php echo base_url('themes/admin/javascript/jquery-1.8.2.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('themes/admin/javascript/jtree/jstree.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('themes/admin/javascript/tinymce/tinymce.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('themes/admin/javascript/validation/languages/jquery.validationEngine-en.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('themes/admin/javascript/validation/jquery.validationEngine.js') ?>"></script>

<div>
    <h3><a href="<?php echo site_url('admin/') ?>" target="_self">AS INVENT</a></h3>
</div>

<div class="navigation">
    <ul>       
        <li class="nav_item">Article Management
            <ul>
                <li><a href="<?php echo site_url('admin/')?>" target="_self">Articles</a></li>
                <li><a id="add_main_category" href="<?php echo site_url('admin/categories/add_main_category')?>" target="_self">Add Main Category</a></li>
            </ul>
        </li>         
        <li class="nav_item">User Management
            <ul>
                <li><a href="<?php echo site_url('admin/users')?>" target="_self">Users</a></li>
                <li><a href="<?php echo site_url('admin/users/add_user')?>" target="_self">Add User</a></li>
                <li><a href="<?php echo site_url('admin/users/search_user')?>" target="_self">Search User</a></li>
            </ul>
        </li>
        <li class="nav_item">User Role Management
            <ul>
                <li><a href="<?php echo site_url('admin/users/roles')?>" target="_self">Roles</a></li>
                <li><a href="<?php echo site_url('admin/users/add_roles')?>" target="_self">Add Roles</a></li>                                
                <li><a href="<?php echo site_url('admin/users/permissions')?>" target="_self">Permissions</a></li>
            </ul>
        </li>                        
        <li>Resource Management
            <ul>
                <li><a href="<?php echo site_url('admin/manage_resources/')?>">Resources</a></li>
                <li><a href="<?php echo site_url('admin/manage_resources/add_resource')?>">Add Resource</a></li>
                <li><a href="<?php echo site_url('admin/manage_resources/add_section')?>">Add Section</a></li>
            </ul>
        </li>
        <li>Settings
            <ul>
                <li><a href="<?php echo site_url('admin/settings/')?>">Settings</a></li>            
                <li><a href="<?php echo site_url('admin/settings/add_settings')?>">Add New Setting</a></li>
                <li><a href="<?php echo site_url('admin/settings/search')?>">Search Setting</a></li>
            </ul>
        </li>
    </ul>
    
</div>
