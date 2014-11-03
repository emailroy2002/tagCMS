<tag:include:partial filename="header.php" />


<div style="float:left; width:70% !important">
    <tag:article:page>
            <h3><tag:title text="strip|html"></tag:title></h3>
            <div><tag:description /></div>
    </tag:article:page>
</div>


<div style="float:left;">
    <div class="widget">
        <div style="float:right">
            <h3>Articles</h3>
            <tag:articles pagination="0">
                <tag:article>
                    <a href="<tag:url/>"><tag:title text="limit|20"></tag:title></a>
                </tag:article>
            </tag:articles>
        </div>                
    </div>                
</div>

<div style="clear:both"></div>

<tag:include:partial filename="footer.php" />