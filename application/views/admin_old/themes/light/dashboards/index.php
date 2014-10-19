<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Title</title>
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="<?php echo  base_url('themes/admin/stylesheet/jtree/themes/default/style.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo  base_url('themes/admin/stylesheet/validation/validationEngine.jquery.css') ?>" type="text/css"/>
         
		<style>
		html, body { background:#ebebeb; font-size:10px; font-family:Verdana; margin:0; padding:0; }
		#container { min-width:320px; margin:0px auto 0 auto; background:white; border-radius:0px; padding:0px; overflow:hidden; }
        #container .success { width:100%; background-color:#7DFD6F; text-align:center}
        
		#tree { float:left; min-width:260px; border-right:1px solid silver; overflow:auto; padding:0px 0; }        
		#data { margin-left:260px; }
		#data textarea { margin:0; padding:0; height:100%; width:100%; border:0; background:white; display:block; line-height:18px; resize:none; }
		#data, #code { font: normal normal normal 12px/18px 'Consolas', monospace !important; }        
        #tree .root { font-size:12pt; font-weight:bold }        
        #tree .items-float-right {  float: right;}
        #tree .items-margin-right { margin-right:20px}
        #tree .items-hover-icons { visibility: hidden }        
		#tree .folder { background:url('<?php echo  base_url('themes/admin/stylesheet/jtree/themes/default/file_sprite.png') ?>') right bottom no-repeat; }
		#tree .file { background:url('http://localhost/Dropbox/_core/0.1/themes/admin/stylesheet/jtree/themes/default/file_sprite.png') 0 0 no-repeat; }
		#tree .file-pdf { background:url('http://localhost/Dropbox/_core/0.1/themes/admin/stylesheet/jtree/themes/default/file_sprite.png') -18px 0 no-repeat; }
		#tree .file-as { background-position: -36px 0 }
		#tree .file-c { background:url('http://localhost/Dropbox/_core/0.1/themes/admin/stylesheet/jtree/themes/default/file_sprite.png') -54px 0 no-repeat;}
		#tree .file-iso { background-position: -108px -0px }
		#tree .file-htm, #tree .file-html, #tree .file-xml, #tree .file-xsl { background-position: -126px -0px }
		#tree .file-cf { background-position: -162px -0px }
		#tree .file-cpp { background-position: -216px -0px }
		#tree .file-cs { background-position: -236px -0px }
		#tree .file-sql { background-position: -272px -0px }
		#tree .file-xls, #tree .file-xlsx { background-position: -362px -0px }
		#tree .file-h { background-position: -488px -0px }
		#tree .file-crt, #tree .file-pem, #tree .file-cer { background-position: -452px -18px }
		#tree .file-php { background-position: -108px -18px }
		#tree .file-jpg, #tree .file-jpeg, #tree .file-png, #tree .file-gif, #tree .file-bmp { background-position: -126px -18px }
		#tree .file-ppt, #tree .file-pptx { background-position: -144px -18px }
		#tree .file-rb { background-position: -180px -18px }
		#tree .file-text, #tree .file-txt, #tree .file-md, #tree .file-log, #tree .file-htaccess { background-position: -254px -18px }
		#tree .file-doc, #tree .file-docx { background-position: -362px -18px }
		#tree .file-zip, #tree .file-gz, #tree .file-tar, #tree .file-rar { background-position: -416px -18px }
		#tree .file-js { background-position: -434px -18px }
		#tree .file-css { background-position: -144px -0px }
		#tree .file-fla { background-position: -398px -0px }
		</style>
	</head>
	<body>
        
        
		<div id="container" role="main">
			<div id="tree"></div>
			<div id="data">
				<div class="content code" style="display:none;"><textarea id="code" readonly="readonly"></textarea></div>
				<div class="content folder" style="display:none;"></div>
				<div class="content image" style="display:none; position:relative;"><img src="" alt="" style="display:block; position:absolute; left:50%; top:50%; padding:0; max-height:90%; max-width:90%;" /></div>
				<div class="content default" style="text-align:center;">Select a file from the tree.</div>
			</div>
		</div>
		<?php /* <script src="<?php echo base_url('themes/admin/javascript/jquery.js') ?>"></script> */ ?>      
        
        <script src="<?php echo base_url('themes/admin/javascript/jquery-1.8.2.min.js') ?>"></script>
		<script src="<?php echo base_url('themes/admin/javascript/jtree/jstree.js') ?>"></script>
        

        <script src="<?php echo base_url('themes/admin/javascript/validation/languages/jquery.validationEngine-en.js') ?>"></script>
        <script src="<?php echo base_url('themes/admin/javascript/validation/jquery.validationEngine.js') ?>"></script>
  
        
        
		<script>
		$(function () {
		   
			$(window).resize(function () {
				var h = Math.max($(window).height() - 0, 420);
				$('#container, #data, #tree, #data .content').height(h).filter('.default').css('lineHeight', h + 'px');
			}).resize();
            
            
            function create_form_add_category(node, inst, obj) {
                $.ajax({
                    uniq_param : (new Date()).getTime(),
                    cache: false,
                    type: 'GET',                            
                    url :  '<?php echo site_url('admin/nodes/add_form_category')?>',
                    data : { 'type' : node.type, 'id' : node.id, 'text' : node.text },
                    beforeSend: function() {
                        $('#data .default').html("loading form");
                    }                    
                })                                          
                .done(function (d) {
                    $('#data .default').html(d);               
                    $("#add_category").validationEngine('attach');                                                                                                            
                    $( "#form_save" ).bind( "click", function() {
                        var res = $("#add_category").validationEngine('validate');                                                
                        if (res == true) {
                            inst.create_node(obj, { type : "default", text : $('#form_title').val() }, "last", function (new_node) {
                        	   setTimeout(function () {
                        	       $('#tree').jstree('open_node', '#'+new_node.parent); 
                                   return false;                                                              
                                },0); 
                            });
                        } else {
                          setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                        }
                    });                                     
                }).fail(function () {
                    console.log('error creating category form');	
                });
            }             
            
            
            function create_form_edit_category(node, inst, obj) {
                $.ajax({
                    uniq_param : (new Date()).getTime(),
                    cache: false,
                    type: 'GET',                            
                    url :  '<?php echo site_url('admin/nodes/edit_form_category')?>',
                    data : { 'type' : obj.type, 'id' : obj.id, 'text' : obj.text },
                    beforeSend: function() {
                        $('#data .default').html("loading form");
                    }                          
                })                                                   
                .done(function (d) {
                    $('#data .default').html(d);               
                    $("#update_category").validationEngine('attach');                                                           
                    $("#form_save").bind("click", function() {
                        var res = $("#update_category").validationEngine('validate');                                                
                        if (res == true) {
                            $("#tree").jstree('set_text', obj, $('#form_title').val());
                            $('#tree').trigger('rename_node', { "node" : obj, "text" : $('#form_title').val(), "old" : obj.text });
                        } else {
                          setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                        }
                    });                                  
                })
                .fail(function () {
                    console.log('error creating edit category form');	
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
                            inst.create_node(obj, { type : "file", text : $('#form_title').val() }, "last", function (new_node) {
                        	   setTimeout(function () {
                        	       $('#tree').jstree('open_node', '#'+new_node.parent);                                                           
                                },0);
                            });
                        } else {
                          setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
                        }
                    });                                     
                }).fail(function () {
                    console.log('error creating file form');	
                });
                                                        
            }


            function create_form_edit_file(node, inst, obj) {
                
                $.ajax({
                    uniq_param : (new Date()).getTime(),
                    cache: false,
                    type: 'GET',                            
                    url :  '<?php echo site_url('admin/nodes/edit_form_file')?>',
                    data : { 'type' : node.type, 'id' : node.selected.join(':'), 'text' : node.text },
                    beforeSend: function() {
                        $('#data .default').html("loading form");
                    }                          
                })                                          
                .done(function (d) {
                    $('#data .default').html(d);               
                    $("#edit_file").validationEngine('attach');
                    
                    $('#data .default').html(d);               
                    $("#edit_file").validationEngine('attach');         
                                                                      
                    $("#form_save").bind("click", function() {
                        
                        var res = $("#edit_file").validationEngine('validate');                                                
                        if (res == true) {
                            $("#tree").jstree('set_text', obj, $('#form_title').val());
                            $('#tree').trigger('rename_node', { "node" : obj, "text" : $('#form_title').val(), "old" : obj.text });
                        } else {
                          setTimeout(function () { $('.formError').fadeTo('slow', 1500) }, 1000);
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
							'url' : 'nodes/get_node',       
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
							 //console.log ("node was transfered to other tree");
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
					'plugins' : ['state','dnd','crrm','types','contextmenu','unique', "wholerow"],
                               
				})
                .on("loaded.jstree", function(){
                    
                    $('#tree').bind("hover_node.jstree", function(e, data){
                        var inst = $.jstree.reference(data.node.id),
                        obj = inst.get_node(data.node.id);
                            
                        if (data.node.type == 'default' || data.node.type == 'folder') {
                            $('#tree #'+data.node.id+">.items-hover-icons").css('visibility', 'visible');    
                        }                        
                         
                        
                        $('#tree #'+data.node.id+">.items-hover-icons .jstree-create").off('click').on("click", function(d){                                
                            create_form_add_category(data.node, inst, obj);
                            return false;             
                        });
                        
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
    					$.get('<?php echo site_url('admin/nodes/delete_folder')?>', { 'id' : data.node.id })
    						.fail(function () {
    							data.instance.refresh();
                            });				        
				    } else if (data.node.type == 'file') {
    					$.get('<?php echo site_url('admin/nodes/delete_file')?>', { 'id' : data.node.id })
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
                            type: 'GET',                            
                            url :  '<?php echo site_url('admin/nodes/create_category')?>',
                            data : $('form#add_category :input').serialize()+'&type='+data.node.type+"&parent_id="+data.node.parent
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
                            type: 'GET',                            
                            url :  '<?php echo site_url('admin/nodes/create_file')?>',
                            data : $('form#add_file :input').serialize()+'&type='+data.node.type+"&cat_id="+data.node.parent
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
				    console.log(data)
                    if (data.node.type === "default" || data.node.type === "folder") {
                        
                        $.ajax({
                            'uniq_param' : (new Date()).getTime(),
                            cache: false,
                            type: 'GET',                            
                            url :  '<?php echo site_url('admin/nodes/rename_category')?>',
                            data : $('form#update_category :input').serialize()+'&id='+data.node.id+"&text="+data.text
                        })                                                   
                        .done(function (d) {                       
                            $('#data').prepend("<div class='success'>Category Updated Successfully</div>");                             
                            setTimeout(function () { $('.success').fadeTo('slow', 0).hide('slow');   }, 3500);                                       
                        })
                        .fail(function () {
                        	data.instance.refresh();
                        });
                        
                    } else if (data.node.type == 'file') {
                        
                        $.ajax({
                            'uniq_param' : (new Date()).getTime(),
                            cache: false,
                            type: 'GET',                            
                            url :  '<?php echo site_url('admin/nodes/rename_file')?>',
                            data : $('form#edit_file :input').serialize()+'&id='+data.node.id+"&text="+data.text
                        })                                                   
                        .done(function (d) {                       
                            $('#data').prepend("<div class='success'>Category Updated Successfully</div>");                             
                            setTimeout(function () { $('.success').fadeTo('slow', 0).hide('slow');   }, 3500);                                       
                        })
                        .fail(function () {
                        	data.instance.refresh();
                        });
                                                
                    }
                    
				})
				.on('move_node.jstree', function (e, data) {
				    console.log(data)
                    if (data.node.type === "default" || data.node.type === "folder") {
                        $.ajax({
                            'uniq_param' : (new Date()).getTime(),
                            cache: false,
                            type: 'GET',                            
                            url :  '<?php echo site_url('admin/nodes/move_category')?>',
                            data : 'id='+data.node.id+'&new_parent='+encodeURIComponent(data.node.parent)+'&new_position='+data.position+'&old_position='+data.old_position
                        })                                                   
                        .done(function (d) { 
                            $('.success').fadeTo('slow', 0);   
                            $('#data').prepend("<div class='success'>Moved Successfully</div>");                             
                            setTimeout(function () { $('.success').fadeTo('slow', 0).hide('slow');   }, 3500);                                       
                        })
                        .fail(function () {
                        	data.instance.refresh();
                        });               
                    } else if (data.node.type == 'file') {
                        
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
       
      
	</body>
</html>