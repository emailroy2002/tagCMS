<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title><tag:page_title /> | <tag:site_title /></title>    
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="imagetoolbar" content="no" />
    <meta name="revisit-after" content="15 days" />
    <meta name="description" content="<tag:meta_description text='limit|80, strip|html, strip|linebreaks'/>" />
    <meta name="keywords" content="<tag:meta_keywords />" />
    <meta name="language" content="<tag:current_lang />" />    
    <link rel="stylesheet" href="<tag:base_url />themes/default/stylesheet/style.css" />    
    <link rel="stylesheet" href="<tag:base_url />themes/default/stylesheet/skeleton.css" />
    
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<tag:base_url />themes/default/javascript/menu.js"></script>
    
    <!-- TinyMCE -->    
    <script type="text/javascript" src="<tag:base_url />themes/default/javascript/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
    tinymce.init({
        selector: "textarea",
                plugins: [
                        "bbcode advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "table contextmenu directionality emoticons template textcolor paste  textcolor colorpicker textpattern"
                ],        
     });
    </script>    
    <!-- Syntax Highlighter -->
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/src/shCore.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushBash.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushCpp.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushCSharp.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushCss.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushDelphi.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushDiff.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushGroovy.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushJava.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushJScript.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushPhp.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushPlain.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushPython.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushRuby.js"></script>	
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushSql.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushVb.js"></script>
	<script type="text/javascript" src="<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/shBrushXml.js"></script>
	<link type="text/css" rel="stylesheet" href="<tag:base_url />themes/default/javascript/syntaxhighlighter/styles/shCore.css"/>
	<link type="text/css" rel="stylesheet" href="<tag:base_url />themes/default/javascript/syntaxhighlighter/styles/shThemeDefault.css"/>
	<script type="text/javascript">       
    		SyntaxHighlighter.config.clipboardSwf = '<tag:base_url />themes/default/javascript/syntaxhighlighter/scripts/clipboard.swf';
    		SyntaxHighlighter.all();
	</script>
</head>
<body>

<div>
    <a href="<tag:base_url />"><tag:site_title /></a>
    <div><small><i><tag:site_description></tag:site_description></i></small>    
</div>




<tag:categories type="ul" id="nav" multilevel="true" parent="main">                
    <tag:category type="li">               
         <a href="<tag:url/>"><tag:name text="limit|50"></tag:name>
           <tag:has_children>&#187;</tag:has_children>
         </a>        
    </tag:category>
</tag:categories>

