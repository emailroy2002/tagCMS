<tag:include:partial filename="header.php" />

<div>
    <small><i><tag:site_description></tag:site_description></i></small>
</div>

<div>
    <h3 style="line-height:12px;margin-bottom:8px">
        <tag:category_title text="limit|25, strip|html"></tag:category_title>
    </h3>
    <small><i><tag:category_description text="limit|10, strip|html"></tag:category_description></i></small>
</div>

<div style="float:left;width:70%">

    <tag:articles id="articles" class="items" scope="global" <?php //pagination="1" ?> filter="where|id=2,cat_id=6">
        <tag:article class="item">
            <div><tag:ctr/>  <a href="<tag:url />"><tag:title text="limit|10"></tag:title></a></div>
            <div><tag:description  text="limit|10, paragraph|100, strip|text"/></div>                               
            <small class="light"><i>Date Published :  <tag:date_published date_format="l jS \of F Y h:i:s A" /></i></small>                            
        </tag:article>
    </tag:articles>   
    <tag:pagination for="articles" /> 
</div>

<div style="float:left">
    <div class="widget">
        <tag:include:partial filename="sidebar.php" />        
    </div>                
</div>

<div style="clear:both"></div>

<tag:include:partial filename="footer.php" />

