if (!(typeof tinymce === 'undefined')) {    

    var interval_limit = 10;
    var resizing;
    
    function tinyMCE_resize() {            
        var h =  ($('.content').height() + $('#form_description_ifr').height()) + 800
        $('#tree').css('height', h + 'px');
        $('#container').css('height', h + 'px');
        $('#side_panel').css('height', h + 'px');
                        
        interval_limit = interval_limit  - 1;            
        if (interval_limit <= 0 ) {
            clearInterval(resizing); //stop resizing after limited tries
        }
                            
        if (h > $('#form_description_ifr').height()) {                            
            clearInterval(resizing); //stop resizing after height is taller than form description height        
        }
    }
    
    function getStats(id) {
        var tx = tinymce.get('form_description').getContent({format: 'html'});
                    
        return {
            chars: tx.length,
            words: tx.split(/[\w\u2019\'-]+/).length
        };
    }
            
    function adjust_content_area(editor) {
        editor.on('ResizeEditor', function (e) {            
            resizing = setInterval(function(){
                tinyMCE_resize();
            }, 10);                                         
        });
              
        editor.on('change', function(e) {
            resizing = setInterval(function(){
                tinyMCE_resize();
            }, 10);                                                              
        });   
        
        editor.on('load', function(e) {
            resizing = setInterval(function(){
                tinyMCE_resize();                    
            }, 10);
        });
        
                                            
    }         
    
    tinymce.init({
            mode : "exact",               
            width: '100%',           
            theme: "modern",
            skin: "light",
            resize: false,                
            resizable:'false',             
            format: "text",
            selector: ".tinyMCE_simple",
            plugins: [
                    "charactercount_category code autoresize bbcode autolink link preview ",
            ],                        
            toolbar1: "newdocument preview code",
            menubar: false,
            toolbar_items_size: 'small',
            force_br_newlines : true,
            force_p_newlines : false,                
            forced_root_block : "", 
            autoresize_bottom_margin: 3,
            setup: 'adjust_content_area',                
            object_resizing : false       
    });
    
    
    
    
    tinymce.init({               
            theme: "modern",
            width: '800px',
            height: '300px',
            resize: 'false',
            skin: "light",
            resize: false,                
            selector: ".tinyMCE",               
            plugins: [
                    "charactercount autoresize advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace  visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons template textcolor paste  textcolor colorpicker textpattern"
            ],    
            toolbar1: " newdocument  | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",        
            menubar: false,
            toolbar_items_size: 'small',        
            style_formats: [
                    {title: 'Bold text', inline: 'b'},
                    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                    {title: 'Example 1', inline: 'span', classes: 'example1'},
                    {title: 'Example 2', inline: 'span', classes: 'example2'},
                    {title: 'Table styles'},
                    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],
    
            templates: [
                    {title: 'Test template 1', content: 'Test 1'},
                    {title: 'Test template 2', content: 'Test 2'}
            ],
            
            protect: [
                /\<\/?(if|endif)\>/g, // Protect <if> & </endif>
                /\<xsl\:[^>]+\>/g, // Protect <xsl:...>
                /<\?php.*?\?>/g // Protect php code
            ],
            setup: 'adjust_content_area',
            force_br_newlines : true,
            force_p_newlines : false,             
    });

}

