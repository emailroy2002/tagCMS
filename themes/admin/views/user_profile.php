


<div id="user_profile" class="block">
    <h3>Profile</h3>


    <table cellpadding="0" cellspacing="0">
        <tr>
            <td  style="border-top:1px solid #ccc; padding:20px"><?php echo $edit_link ?></td>
            <td  style="border-top:1px solid #ccc; padding:20px"><?php echo $delete_link ?></td>
        </tr>
    </table>
     
    <p>User Details </p>     
    <table cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #ccc;">
        <tr>
            <td style="border-top:1px solid #ccc; width:120px">First Name </td>
            <td  style="border-top:1px solid #ccc; padding:20px"><?php echo $user->first_name ?></td>
        </tr>
        <tr>
            <td style="border-top:1px solid #ccc; width:120px">Last Name </td>
            <td style="border-top:1px solid #ccc; padding:20px"><?php echo $user->last_name ?></td>
        </tr>
        <tr>
            <td style="border-top:1px solid #ccc; width:120px">User Name </td>
            <td style="border-top:1px solid #ccc; padding:20px"><?php echo $user->username ?></td>
        </tr>
    </table>

     
</div>