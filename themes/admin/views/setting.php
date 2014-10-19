<h3>List of Settings</h3>

<table cellpadding="0" cellspacing="0">
<?php foreach ($settings as $setting) : @$ctr++?>
    <tr>
        <td  style="border-top:1px solid #ccc; padding:20px"><?php echo $ctr ." ". $setting->name ?></td>
        <td  style="border-top:1px solid #ccc; padding:20px"> <?php echo $setting->value ?></td>
    </tr>
<?php endforeach; ?>
</table>

<?php echo $page_links ?>