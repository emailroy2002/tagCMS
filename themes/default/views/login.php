<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<tag:include:partial filename="header.php" />

<div>
    <h3 style="line-height:12px;margin-bottom:8px"><tag:category_title></tag:category_title></h3>
   <tag:category_description></tag:category_description>
</div>


<div style="float:left; width:70% !important">
    <div id="form" style="height: 800px;line-height:20px; text-align:left;padding-left:22px">
    
        <form id="login" name="login" action="<tag:base_url/>login/" method="post" >
            <div>
                <input name="username" id="username" type="text" placeholder="Enter username" class="validate[required]"/>
            </div>
            
            <div>
                <input name="password" id="password" type="text" placeholder="Enter password" class="validate[required]"/>
            </div>
            <input id="form_save" name="login" type="submit" value="Save"/>
        </form>
        
    </div>
</div>


<div style="float:left">
    <div class="widget">    
       
    </div>
</div>

<div style="clear:both"></div>

<tag:include:partial filename="footer.php" />
     