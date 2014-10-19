<h3>Manage Resources</h3>

<table>
<?php foreach ($resources as $resource) : @$ctr++?>
    <tr>
        <td><?php echo $ctr ." ". $resource->name ?></td>       
        <td><?php echo manage_resource::show($resource->id, "show")?></td>
        <td><?php echo manage_resource::edit($resource->id, "edit")?></td>
        <td><?php echo manage_resource::drop($resource->id, "delete")?></td>
    </tr>
<?php endforeach; ?>
</table>

<?php echo $page_links ?>