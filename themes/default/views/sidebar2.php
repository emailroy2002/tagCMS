    <h3>Categories</h3>
    <div>
        <tag:categories id="categories" class="main_menu"  multilevel="true" >
            <tag:category type="li">               
                 <div><a href="<tag:url />"><tag:name text="limit|10"/></a></div>                    
            </tag:category>
        </tag:categories>
    </div>
    
    <?php /*
    <div style="border-top:1px solid #ccc;">
        <h3>ARCHIVES</h3>
        <tag:categories id="categories" class="categories" root="archives" scope="global" multilevel="true"  >
            <tag:category type="li">
                <tag:link/>                      
            </tag:category>
        </tag:categories>
    </div> 
    
    */
    ?>
    
    <?php if (slug()) : ?>
    <h3>Articles</h3>
    
    <tag:articles id="articles" filter="where|id=2,cat_id=6">
    <tag:article >
        <div><tag:url/></div>       
    </tag:article>
    </tag:articles>
    
    
    <?php endif; ?>