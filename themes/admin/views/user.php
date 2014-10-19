<h3>List of User</h3>

<table>
<?php foreach ($users as $user) : @$ctr++?>
    <tr>
        <td><?php echo $ctr ." ". $user->first_name ?></td>
        <td><?php echo user::profile_url($user->uid, "show")?></td>
        <td><?php echo user::profile_edit_url($user->uid, "edit")?></td>
        <td><?php echo user::profile_delete_url($user->uid, "delete")?></td>
    </tr>
<?php endforeach; ?>
</table>

<?php echo $page_links ?>
