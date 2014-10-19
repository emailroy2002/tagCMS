
<h3>List of Roles</h3>

<table>
<?php foreach ($roles as $role) : @$ctr++?>
    <tr>
        <td><?php echo $ctr ." ". $role->title ?></td>
        <td><?php echo user::role_url($role->id, "show")?></td>
        <td><?php echo user::role_edit_url($role->id, "edit")?></td>
        <td><?php echo user::role_delete_url($role->id, "delete")?></td>
    </tr>
<?php endforeach; ?>
</table>

<?php echo $page_links ?>
