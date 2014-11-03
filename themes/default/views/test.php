<tag:include:partial filename="header.php" />

<div>
    <h3 style="line-height:12px;margin-bottom:8px">
        <tag:category_title text="limit|100, strip|html"></tag:category_title>
    </h3>
    <small><i><tag:category_description text="limit|100, strip|html"></tag:category_description></i></small>
</div>

<div style="float:left;width:70%">

<tag:articles id="articles" class="items" scope="global" parent="blog">
    <tag:article class="item">
        <div><tag:ctr/>  <a href="<tag:url />"><tag:title text="limit|80, strip|html"></tag:title></a></div>
        <div><tag:description  text="limit|500"/></div>                               
        <small class="light"><i>Date Published :  <tag:date_published date_format="l jS \of F Y h:i:s A" /></i></small>                            
    </tag:article>
</tag:articles>   
<tag:pagination for="articles" /> 
        

<tag:include:partial filename="footer.php" />