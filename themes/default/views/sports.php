<tag:include:partial filename="header.php" />

<div>generated from "SPORTS TEMPLATE" </div>
<div>
    <h3 style="line-height:12px;margin-bottom:8px"><tag:category_title></tag:category_title></h3>
    <small><i><tag:category_description></tag:category_description></i></small>
</div>

<div style="float:left;width:70%">



    
        <tag:categories id="sports_lists" class="listings" multilevel="false" root="news/sports">
            <tag:category type="li">
                <tag:link/>                      
            </tag:category>
        </tag:categories>
        
        
        <!--
    <tag:articles id="articles" class="items" scope="global"  filter="where|id=2,cat_id=6">
        <tag:article class="item">
            <div><tag:ctr/> <tag:url /></div>
          
            <div><tag:description text="paragraph|2,capitalize|first"/></div>                   
            <small class="light"><i>Date Published :  <tag:date_published date_format="l jS \of F Y h:i:s A" /></i></small>                            
        </tag:article>
    </tag:articles>   
    <tag:pagination for="articles" /> 
    -->
    
</div>

<div style="float:left">
    <div class="widget">
        <tag:include:partial filename="sidebar.php" />        
    </div>                
</div>

<div style="clear:both"></div>

<tag:include:partial filename="footer.php" />

