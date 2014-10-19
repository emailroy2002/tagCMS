    <h1>Articles</h1>
    
	<div id="body">        
        <div id="list">
            <?php foreach ($items as $item) : ; ?>
                <h4><?php echo @$ctr = $ctr + 1 ?> - <?php echo get_link($item->id) ?></h4>
                <small><?php echo $item->cat_id ." ". $item->status ?></small>
                <div><?php echo $item->description ?></div>
            <hr />
            <?php endforeach;?>
        </div>
	</div>

  

    