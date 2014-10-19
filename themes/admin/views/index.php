<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin panel</title>    
	<meta charset="utf-8" />
    <meta name="description" content="Admin Panel" /> 
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />    
    <meta name="robots" content="index, no-follow" />    
    <base href="<?php echo base_url('themes/admin/stylesheet/jtree/themes/default/') ?>"/>
    <link rel="stylesheet" href="<?php echo  base_url('themes/admin/stylesheet/style.css') ?>" />
    <link rel="stylesheet" href="<?php echo  base_url('themes/admin/stylesheet/jtree/themes/default/style.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo  base_url('themes/admin/stylesheet/jtree/themes/default/jstree.css') ?>" />
    <link rel="stylesheet" href="<?php echo  base_url('themes/admin/stylesheet/validation/validationEngine.jquery.css') ?>" type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url('themes/admin/javascript/tinymce/tinymce.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('themes/admin/javascript/tinymce/tinymce.init.js' ) ?>"></script>
</head>
<body>
    <?php echo $header ?>
    <?php echo $yield ?>
    <?php echo $footer ?>
</body>
</html>