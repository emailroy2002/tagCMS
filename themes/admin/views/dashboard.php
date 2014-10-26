<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="container" role="main">
	<div id="tree"></div>
	<div id="data">
		<div class="content code" style="display:none;"><textarea id="code" readonly="readonly"></textarea></div>
		<div class="content folder" style="display:none;"></div>
		<div class="content image" style="display:none; position:relative;"><img src="" alt="" style="display:block; position:absolute; left:50%; top:50%; padding:0; max-height:90%; max-width:90%;" /></div>
		<div class="content default" style="text-align:center;">Select a file from the tree.</div>
	</div>
</div>

<script type="text/javascript">
$(function () {    
    var character_limit_category = 100;
    var character_limit_article = 300000;
        
    tinyMCE_resize();
        
	$(window).resize(function () {
        tinyMCE_resize();
	}).resize();   
    
    function character_limiter(string, character_limit) {
        character_limit = character_limit;
        if( string.length > character_limit){
            return string.substr(0, character_limit) + "...";
        } else {
            return string;
        }
    }
        
    function create_form_main_category() {
        $.ajax({
            uniq_param : (new Date()).getTime(),
            cache: false,
            type: 'POST',                            
            url :  '<?php echo site_url('admin/nodes/add_form_category')?>',
            data : { 'type' : 'default', 'id' : '#' },
            beforeSend: function() {
                $('#data .default').html("loading form");
            } 
        }).done(function (d) {
            $('#data .default').html(d);               
            $("#add_category").validationEngine('attach');
            
            $( "#form_save" ).bind( "click", function() {
                var res = $("#add_category").validationEngine('validate');                                                
                if (res == true) {
                    //inst.create_node(obj, { type : "default", text : $('#form_title').val() }, "last", function (new_node) {
                    var title = character_limiter($('#form_title').val(), 20);
                                            
                    $("#tree").jstree('create_node', '#', {'id' : 'myId', 'text' : '<span class="root">'+title+'</span>'}, 'last', function (new_node) {
                	   setTimeout(function () {
                	       $('#tree').jstree('open_node', '#'+new_node); 
                           inst.resize_content_holder();
                           return false;                                                              
                        }, 100); 
                    });
                } else {
                    setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                }
            });        
        }); 
    }

    
    function create_form_add_category(node, inst, obj) {
        $.ajax({
            uniq_param : (new Date()).getTime(),
            cache: false,
            type: 'POST',                            
            url :  '<?php echo site_url('admin/nodes/add_form_category')?>',
            data : { 'type' : node.type, 'id' : node.id },
            beforeSend: function() {
                $('#data .default').html("loading form");
            }                    
        })                                          
        .done(function (d) {
            $('#data .default').html(d)               
            $("#add_category").validationEngine('attach');
                                                                                                                     
            $( "#form_save" ).bind( "click", function() {
                var res = $("#add_category").validationEngine('validate');                                                
                if (res == true) {
                    var title = character_limiter($('#form_title').val(), 20);
                    
                    inst.create_node(obj, { type : "default", text : title }, "last", function (new_node) {
                	   setTimeout(function () {
                	       $('#tree').jstree('open_node', '#'+new_node.parent); 
                            resize_content_holder();
                           return false;                                                              
                        }, 100); 
                    });
                } else {
                    setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                }
            });                                     
        }).fail(function () {
            console.log('error creating category form');	
        });
    }             
    
       
    
    function create_form_add_file(node, inst, obj) {
        $.ajax({
            uniq_param : (new Date()).getTime(),
            cache: false,
            type: 'GET',                            
            url :  '<?php echo site_url('admin/nodes/add_form_file')?>',
            data : { 'type' : node.type, 'id' : node.id, 'text' : node.text },
            beforeSend: function() {
                $('#data .default').html("loading form");
            }                          
        })                                          
        .done(function (d) {
            $('#data .default').html(d);              
           
            $("#add_file").validationEngine('attach');                                                                                                    
            $( "#form_save" ).bind( "click", function() {
                var res = $("#add_file").validationEngine('validate');                                                
                if (res == true) {
                    var title = character_limiter($('#form_title').val(), 20);
                    
                    inst.create_node(obj, { type : "file", text : title }, "last", function (new_node) { //@todo : make this dynamic based on folder
                        setTimeout(function () {
                            $('#tree').jstree('open_node', '#'+new_node.parent);                                                           
                        }, 100);
                    });
                } else {
                  setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                }
            });                                     
        }).fail(function () {
            console.log('error creating file form');	
        });                                     
    }
    
    
    
        
    
    function create_form_edit_category(node, inst, obj) {
        $.ajax({
            uniq_param : (new Date()).getTime(),
            cache: false,
            type: 'POST',                            
            url :  '<?php echo site_url('admin/nodes/edit_form_category')?>',
            data : { 'type' : obj.type, 'id' : obj.id, 'text' : obj.text },
            beforeSend: function() {
                tinymce.remove('textarea');
                $('#data .default').html("loading form");                
            }                          
        })                                                   
        .done(function (d) {
            $('#data .default').html(d);               
            $("#update_category").validationEngine('attach');
            setTimeout(function () {
                tinyMCE_resize();      
            }, 100);
            $("#form_save").bind("click", function() {                
                var stat = getStats('#form_description_ifr')
                if (stat.chars > character_limit_category) {     
                    $(".failed").remove();
				    $('#data').prepend("<div class='failed'>Character count exceeds limit of "+ character_limit_category  +".</div>");
                    setTimeout(function () {  
                        $('.failed').fadeTo('slow', 0).hide('slow', function(){ $(this).remove() }); 
                    }, 3500);                                            
                } else {
                    var res = $("#update_category").validationEngine('validate');                                                
                    if (res == true) {
                        if (obj.parent == '#') {
                            var title = '<span class="root">'+ character_limiter($('#form_title').val(), 20)+'</span>';    
                        } else {
                            var title = character_limiter($('#form_title').val(), 20);
                        }                      
                         
                        $("#tree").jstree('set_text', obj, title);                        
                        $('#tree').trigger('rename_node', { "node" : obj, "text" : tinymce.get('form_description').getContent(), "old" : obj.text });
                    } else {
                        setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                    }
                }
            });                       
        })
        .fail(function () {
            console.log('error creating edit category form');	
        });
    }     
         

    function create_form_edit_file(node, inst, obj) {
        $.ajax({
            uniq_param : (new Date()).getTime(),
            cache: false,
            type: 'POST',                            
            url :  '<?php echo site_url('admin/nodes/edit_form_file')?>',
            data : { 'type' : node.type, 'id' : node.selected.join(':'), 'text' : node.text },
            beforeSend: function() {
                tinymce.remove('textarea');
                $('#data .default').html("loading form");
            }                          
        })                                          
        .done(function (d) {
            $('#data .default').html(d);
            $("#edit_file").validationEngine('attach');
                        
            setTimeout(function () {
                tinyMCE_resize();                           
            }, 100);
                                                                                                                      
            $("#form_save").bind("click", function() {
                var stat = getStats('#form_description_ifr');
                if (stat.chars > character_limit_article) {
                    $(".failed").remove();
				    $('#data').prepend("<div class='failed'>Character count exceeds limit of "+ character_limit_article  +".</div>");
                    setTimeout(function () {  
                        $('.failed').fadeTo('slow', 0).hide('slow', function(){ $(this).remove() }); 
                    }, 3500);                                        
                } else {
                    var res = $("#edit_file").validationEngine('validate');                                                
                    if (res == true) {
                        $("#tree").jstree('set_text', obj, character_limiter($('#form_title').val(), 20) );
                        $('#tree').trigger('rename_node', { "node" : obj, "text" : tinymce.get('form_description').getContent(), "old" : obj.text });
                        //$('#tree').trigger('rename_node', { "node" : obj, "text" : $('#form_title').val(), "old" : obj.text });
                        //$('#'+obj.id+ " a.jstree-anchor-node").click();
                    } else {
                      setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                    }                    
                }
            });                                                     
        }).fail(function () {
            console.log('error creating file form');	
        });                                    
    }
                         
	$('#tree')
		.jstree({
			'core' : {
				'data' : {
					'url' : '<?php echo site_url('admin/nodes/get_node')?>',       
					'data' : function (node) {
					   
						return { 'id' : node.id };
					}
				},
				'check_callback' : function(o, n, p, i, m) {				    
					if(m && m.dnd && m.pos !== 'i') { 
					 //drag action
					 return true; 
                    }
					if(o === "move_node" || o === "copy_node") {
						if(this.get_node(n).parent === this.get_node(p).id) { 
						    console.log ("drop on the same tree");
                            return true; 
                        }
					} else {					 
					   return true;
					}
				},
				'themes' : {
					'responsive' : false,
					'variant' : 'small',
					'stripes' : true
				}
                
			},
			'sort' : function(a, b) {
				return this.get_type(a) === this.get_type(b) ? (this.get_text(a) > this.get_text(b) ? 1 : -1) : (this.get_type(a) >= this.get_type(b) ? 1 : -1);
			},
			'contextmenu' : {
				'items' : function(node) {
					var tmp = $.jstree.defaults.contextmenu.items();
					delete tmp.create.action;
					tmp.create.label = "New";
					tmp.create.submenu = {
						"create_category" : {
							"separator_after"	: true,
							"label"				: "Create Category",
							"action"			: function (data) {                                      
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);
                                    create_form_add_category(node, inst, obj);
							}
						},
						"create_file" : {
							"label"				: "File",
							"action"			: function (data) {
								var inst = $.jstree.reference(data.reference),
									obj = inst.get_node(data.reference);
                                    create_form_add_file(node, inst, obj);
							}
						}
					};
					if(this.get_type(node) === "file") {
						delete tmp.create;
					}
					return tmp;
				}
			},
			'types' : {
                'default' : { 'icon' : 'folder' },
			     'file' : { 'valid_children' : [], 'icon' : 'file' }
			},
            
            <?php if ($js_method_action == 'add_main_category') { ?>
                'plugins' : ['dnd','crrm','types','contextmenu','unique', "wholerow"],			 
            <?php }  else { ?>
                'plugins' : ['state','dnd','crrm','types','contextmenu','unique', "wholerow"],
            <?php } ?>
                       
		})
        .on("loaded.jstree", function(){
           
            <?php if ($js_method_action == 'add_main_category') { ?>
                setTimeout(function () {
                    $('#add_main_category').click();
                }, 100);         
            <?php } ?>     
            

            $('#add_main_category').live('click',function(e) {
                e.preventDefault();
                create_form_main_category();
                return false;
            });
    
            
                            
            $('#tree').bind("hover_node.jstree", function(e, data){
                var inst = $.jstree.reference(data.node.id),
                obj = inst.get_node(data.node.id);
                    
                //set visibility of buttons
                if (data.node.type == 'default' || data.node.type == 'folder') {
                    $('#tree #'+data.node.id+">.items-hover-icons").css('visibility', 'visible');    
                }                        
                 
                //attach create form button event
                $('#tree #'+data.node.id+">.items-hover-icons .jstree-create").off('click').on("click", function(d){                                
                    create_form_add_category(data.node, inst, obj);
                    return false;             
                });
                
                //attach create article file button event
                $('#tree #'+data.node.id+">.items-hover-icons .file-create").off('click').on("click", function(d){                                
                    create_form_add_file(data.node, inst, obj);
                    return false;             
                });       
                                
            }).bind("dehover_node.jstree", function(e, data){
                $('#tree #'+data.node.id+">.items-hover-icons").css('visibility', 'hidden');
                                                      
            });
                             
        })
        /*
        .on("move_node.jstree copy_node.jstree", function (o, n, p, i, m) { 
            console.log("Node was transferred");                 
            console.log(n);
        })*/
		.on('delete_node.jstree', function (e, data) {
		    if (data.node.type == 'default') {
				$.post('<?php echo site_url('admin/nodes/delete_folder')?>', { 'id' : data.node.id })
					.fail(function () {
						data.instance.refresh();
                    });				        
		    } else if (data.node.type == 'file') {
				$.post('<?php echo site_url('admin/nodes/delete_file')?>', { 'id' : data.node.id })
					.fail(function () {
						data.instance.refresh();
                    });	
		    }

		})
		.on('create_node.jstree', function (e, data) {
		    
            if (data.node.type === "default") {
                
                $.ajax({
                    uniq_param: (new Date()).getTime(),
                    cache: false,
                    type: 'POST',                            
                    url :  '<?php echo site_url('admin/nodes/create_category')?>',
                    data : $('form#add_category :input').serialize()+"&form_description="+encodeURIComponent(tinymce.get('form_description').getContent())+'&type='+data.node.type+"&parent_id="+data.node.parent
                })                                                   
				.done(function (d) {  
				    $('#data').prepend("<div class='success'>Saved Successfully</div>");                             
                    setTimeout(function () { $('.success').fadeTo('slow', 0) }, 3500);
                  
				    data.instance.set_id(data.node, d.id);
                    $('#'+data.node.id+ " a.jstree-anchor-node").click();
                    
                   	if(data && data.selected && data.selected.length) {
                        var inst = $.jstree.reference(data.selected),
                        obj = inst.get_node(data.selected);                               
                        create_form_edit_category(data, inst, obj);                                
                        return false;
                     } 
                                                            
				})
				.fail(function () {
					data.instance.refresh();
				});
                
            } else if (data.node.type === "file"){
                
                $.ajax({
                    'uniq_param' : (new Date()).getTime(),
                    cache: false,
                    type: 'POST',                            
                    url :  '<?php echo site_url('admin/nodes/create_file')?>',
                    data : $('form#add_file :input').serialize()+"&form_description="+encodeURIComponent(tinymce.get('form_description').getContent())+'&type='+data.node.type+"&cat_id="+data.node.parent
                })                                                   
				.done(function (d) {                            
                    data.instance.set_id(data.node, d.id);                                                      
                    $('#'+data.node.id+ " a.jstree-anchor-node").click();                 
				})
				.fail(function () {
					data.instance.refresh();
				});
                
            }                          
		})
		.on('rename_node.jstree', function (e, data) {		    
            if (data.node.type === "default" || data.node.type === "folder") {                
                $.ajax({
                    'uniq_param' : (new Date()).getTime(),
                    cache: false,
                    type: 'POST',                            
                    url :  '<?php echo site_url('admin/nodes/rename_category')?>',
                    //data : $('form#update_category :input').serialize()+'&id='+data.node.id+"&text="+data.text
                    data : $('form#update_category :input').serialize()+'&id='+data.node.id+"&text="+encodeURIComponent(tinymce.get('form_description').getContent())                  
                })                                                   
                .done(function (d) {                       
                    $('#data').prepend("<div class='success'>Category Updated Successfully</div>");                                           
                    setTimeout(function () {
                        $('.success').fadeTo('slow', 0).hide('slow', function(){
                            $(this).remove();
                        });                           
                    }, 3500);
                    $('#'+data.node.id+ ">a.jstree-anchor-node").click();                                       
                })
                .fail(function () {
                	data.instance.refresh();
                });
                
            } else if (data.node.type == 'file') {
                
                $.ajax({
                    'uniq_param' : (new Date()).getTime(),
                    cache: false,
                    type: 'POST',                            
                    url :  '<?php echo site_url('admin/nodes/rename_file')?>',
                    //data : $('form#edit_file :input').serialize()+'&id='+data.node.id+"&text="+data.text
                    data : $('form#edit_file :input').serialize()+'&id='+data.node.id+"&text="+encodeURIComponent(tinymce.get('form_description').getContent())
                })                                                   
                .done(function (d) {                       
                    $('#data').prepend("<div class='success'>Article Updated Successfully</div>");
                    $('#'+data.node.id+ ">a.jstree-anchor-node").click();                              
                    setTimeout(function () { 
                        $('.success').fadeTo('slow', 0).hide('slow', function(){
                            $(this).remove();  
                        });   
                    }, 3500);                                       
                })
                .fail(function () {
                	data.instance.refresh();
                });
                                        
            }
            
		})
		.on('move_node.jstree', function (e, data) {
		    
            //console.log(data)
            if (data.node.type === "default" || data.node.type === "folder") {
                
                $.ajax({
                    'uniq_param' : (new Date()).getTime(),
                    cache: false,
                    type: 'POST',                            
                    url :  '<?php echo site_url('admin/nodes/move_category')?>',
                    data : 'id='+data.node.id+'&title='+data.node.text+'&new_parent='+encodeURIComponent(data.node.parent)+'&new_position='+data.position+'&old_position='+data.old_position
                })                                                   
                .done(function (d) { 
                    $('.success').fadeTo('slow', 0);   
                    $('#data').prepend("<div class='success'>Category Moved Successfully</div>");                             
                    setTimeout(function () { $('.success').fadeTo('slow', 0).hide('slow');   }, 3500);                 
                    
                    if (d.parent_id == null) {
                        var title = "<span class='root'>"+ d.title +"</span>";    
                    } else {
                        var title = d.title;
                    }
                    
                    $("#tree").jstree('set_text', data.node, title, 20);                                       
                })
                .fail(function () {
                	data.instance.refresh();
                });               
            } else if (data.node.type == 'file') {
                console.log(data);
                $.ajax({
                    'uniq_param' : (new Date()).getTime(),
                    cache: false,
                    type: 'POST',                            
                    url :  '<?php echo site_url('admin/nodes/move_file')?>',
                    data : 'id='+data.node.id+'&new_parent='+encodeURIComponent(data.node.parent)+'&new_position='+data.position+'&old_position='+data.old_position
                })                                                   
                .done(function (d) { 
                    $('.success').fadeTo('slow', 0);   
                    $('#data').prepend("<div class='success'>Article Moved Successfully</div>");                             
                    setTimeout(function () { $('.success').fadeTo('slow', 0).hide('slow');   }, 3500);                                       
                })
                .fail(function () {
                	data.instance.refresh();
                });                          
                
            }                       
		})
		.on('copy_node.jstree', function (e, data) {
			$.get('?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
				.done(function (d) {
					//data.instance.load_node(data.parent);
					data.instance.refresh();
				})
				.fail(function () {
					data.instance.refresh();
				});
		})               
		.on('changed.jstree', function (e, data) {
			if(data && data.selected && data.selected.length) {
			   
               //console.log (data.node.type);
               var inst = $.jstree.reference(data.selected),
               obj = inst.get_node(data.selected);
               
               if (data.node.type == "default" || data.node.type == "folder") {                                                               
                    create_form_edit_category(data, inst, obj);                         
               }  else if (data.node.type == 'file') {
                    create_form_edit_file(data, inst, obj);                                          
               } else {
                    console.log('file type selected is not supported');
               }
           

			}
			else {
				$('#data .content').hide();
				$('#data .default').html('Select a file from the tree.').show();
			}
		});
});
</script>