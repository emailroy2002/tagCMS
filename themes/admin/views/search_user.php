<form name="search" method="POST">
<input type="text" name="search"/>
<select name="field">
    <option value="username">Username</option>
    <option value="email">Email</option>
    <option value="first_name">First Name</option>
    <option value="last_name">Last Name</option>    
</select>
<input type="submit" value="Search" />
</form>



<?php if(isset($users)): ?>
<div id="search_result">
    <table>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user->uid ?></td>
            <td><?php echo $user->first_name ?></td>
            <td><?php echo $user->last_name ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php echo $page_links ?>

<?php endif; ?>

