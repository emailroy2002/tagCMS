`Welcome to the tagCMS wiki!`

<pre>

At TagCMS, We want to change the we see write dynamic html for a Content Management System.

**Before:**
`<?php $articles = $this->db->get('table_articles'); ?>`
`<div>Articles</div>`
`<?php foreach ($articles as $article): ?>`
  `<div><?php echo "$article->name"; ?></div>`
`<?php endforeach;>`

**Using TAGCMS**
`<tag:articles>`
    `<tag:article>`
        `<div>`
           `<h3><tag:title/></h3>-`
           `<div><tag:description/></div>`
           `<small><tag:date_published/></small>`
        `</div>`
    `</tag:article>`
`</tag:Articles>`



