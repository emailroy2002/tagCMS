<h3>Create!</h3>


<?php echo (@$validation_errors) ?>

<?php echo form_open(current_url()); ?>
    
    <?php foreach($fields as $field): ?>
    
        <?php echo form_fieldset( isset($field['label'])? $field['label'] : '', array('name'=> isset($field['label'])? $field['label'] : '')); ?>
                
        <?php foreach ($field['fields'] as $field): ?>
            <div>
                <label><?php echo $field['label']?></label> 
                <span class="field"><?php echo  $field['html']?></span>
            </div>
        <?php endforeach;?>        
        
        <?php echo form_fieldset_close() ?> 
        
    <?php endforeach ?>
    
    <?php echo form_submit('save', 'Save'); ?>
    
<?php echo form_close() ?> 