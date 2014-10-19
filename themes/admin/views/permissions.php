<h3>User Role Permission</h3>

<?php echo (@$validation_errors) ?>


<form name="user_role_permission" method="POST">
<table>
<?php foreach ($roles as $role) : @$ctr++?>
    <tr valign="top">
        <td>
            <h3>Role</h3>
            <?php echo $ctr ." ". $role->title ?>
        </td>
        <td>
            <h3>Resources</h3>
            <?php foreach ($resources as $resource) : ?>
                <div><input type="checkbox" name="permission[<?php echo $role->id."_".$resource->id; ?>]" /><?php echo $resource->name;?></div>
            <?php endforeach; ?>
        
        </td>      
    </tr>
<?php endforeach; ?>
</table>

<input name="save" type="submit" value="Save" />

<?php echo $page_links ?>
</form>