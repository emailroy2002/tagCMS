<?php echo (@$validation_errors) ?>

<?php echo form_open(current_url()); ?>

    
    <?php foreach ($languages as $lang) : ?>
    
        <?php foreach($fields as $field): ?>
        
            <?php echo isset($field['label'])?  form_fieldset($field['label'], array('name'=> $field['label'])) : null; ?>
                    
            <?php foreach ($field['fields'] as $field): ?>
                <div>
                    <label><?php echo $field['label']?></label> 
                    <span class="field"><?php echo  $field['html']?></span>
                </div>
            <?php endforeach;?>        
            
            <?php echo form_fieldset_close() ?> 
            
        <?php endforeach ?>
        
    <?php endforeach ?>
        
    <?php echo form_submit('save', 'Save'); ?>
    
<?php echo form_close() ?>