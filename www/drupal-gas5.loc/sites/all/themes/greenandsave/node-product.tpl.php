 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

 <?php if ($teaser): ?>

<div class="p_teaser"><div class="p_image_teaser"><a href="<?php print base_path().'node/'.$node->nid ?>" title="<?php print check_plain($node->title) ?>"><?php print $node->content['field_image_cache']['#value'] ?></a></div>

<div class="p_5star_teaser"><?php print $node->content['fivestar_widget']['#value'] ?></div>

<div class="p_title_teaser"><a href="<?php print base_path() ?>node/<?php print $node->nid ?>"><?php print check_plain($node->title) ?></a></div>

<div class="p_price_teaser">$<?php print $node->sell_price ?></div>

<div class="p_cart_teaser"><?php print $node->content['add_to_cart']['#value'] ?></div>
</div>


<?php else: ?>

<div id="topwrap">

<div id="topleft">

<div class="p_ufo">
<script src="<?php print base_path() ?>misc/ufo.js" type="text/javascript"></script>
<div id="submission_view">
<script type="text/javascript">
var F1 = {
movie:"<?php print base_path() ?>misc/zoom.swf",
width:"300",
height:"300",
id:"flashzoom",
quality:"high",
bgcolor:"#FFFFFF",
majorversion:"6", build:"0",
flashvars:"picture=<?php print base_path().$node->field_image_zoom[0]['filepath'] ?>"
};
UFO.create(F1, "submission_view");
</script></div>Click to zoom and drag to move
</div>

<div class="p_gallery">
<?php 
if ( count($node->field_product_gallery) ){
$last = count($node->field_product_gallery) - 1;
for($y = 0;$y <= $last;$y++){ 
 print $node->field_product_gallery[$y]['view'];
}  ?>
<br />more views <?php } ?>
</div></div><!--endzoom-->
<div id="topright">
<h2><?php print check_plain($node->title) ?></h2>

<?php print $node->content['fivestar_widget']['#value'] ?>


<div class="p_reviews">
<?php 
if ($node->comment_count == '0') {
    $reviews = "Be the first to review this product";
 }elseif($node->comment_count == '1') {
    $reviews = $node->comment_count.' Review';
 }elseif($node->comment_count >= '2') {
    $reviews = $node->comment_count.' Reviews';
}
print $reviews;
?><br /><a href="<?php print base_path().$node->links['comment_add']['href'] ?>">Add Your Review</a>
</div>
<div class="p_price">$<?php print $node->sell_price ?></div>
<div class="p_add2cart"><?php print $node->content['add_to_cart']['#value'] ?></div>
<div class="p_border"></div>
<div class="p_shortdesc"><h2>Quick Overview</h2><?php 
print $node->content['field_quick_overview']['#value'] ?></div>

</div><!--endtopright-->

</div><!--endtopwrap-->
<div id="btmwrap">
<div class="p_description">
<h2 class="p_desc">Product Description</h2>
<?php print $node->content['body']['#value'] ?>
</div>
</div>
<?php if ($node->comment_count > 0) { ?>
<div class="comment-bar"><div class="comment_desc">Customer Reviews</div></div>
<?php } ?>



<?php endif; ?>

</div>