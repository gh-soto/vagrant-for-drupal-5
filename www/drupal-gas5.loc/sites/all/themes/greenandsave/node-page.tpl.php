<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php if($is_front || $node->promote){ }else{ ?>
<?php if ($page == 0): ?>
<img src="<?php print base_path().$node->field_image_caption[0]['filepath'] ?>" hspace="4" vspace="4" height="40%" align="left" width="40%" alt="<?php print $title ?>" align="left" />

  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>
<?php } ?>

 <?php if ($teaser): ?>
<div class="content">
<?php
if($node->promote == 1){ //front page slideshow view ?>
<div class="slideshow" style="background:url('<?php print base_path().$node->field_slideshow_page_teaser[0]['filepath'] ?>') top left no-repeat;">
  <div class="slideshow_txt_bg"><div class="slideshow_teaser">
    <a href="<?php print drupal_get_path_alias('node/'.$node->nid) ?>" title="<?php print check_plain($node->title) ?>"><?php print check_plain($node->title) ?></a><br />
    <?php print check_plain($node->field_page_teaser[0]['value']) ?>
  </div>
</div>
</div> <?php

}else{
print $node->content['field_page_teaser']['#value'];
}
?>


</div>
<?php else: ?>
<div class="content">
<?php 
$checkinner = check_plain($node->field_display_inner_block[0]['value']);
if($checkinner == 'v'){
  $innerblockclass = "innerblock";
}elseif($checkinner == 'h'){
  $innerblockclass = "innerblock_h";
}elseif($checkinner == 'n'){
  $innerblockclass = "innerblock_n";
}elseif($checkinner == ''){
  $innerblockclass = "innerblock";
}else{
  $innerblockclass = "innerblock";
}?>

<div id="<?php print $innerblockclass; ?>">
<?php if ($node->iid) $image = node_load($node->iid); ?>
<div class="innerimg"><img src="<?php print base_path().$image->images['preview']; ?>" alt="<?php print check_plain($node->title) ?>"></div>
<div class="innercaption">
<?php print check_markup($node->field_caption[0]['value']) ?></div>
<center>
<?php print $node->links['share__'.$node->nid]['title'] ?>
</center>
</div>

<?php print $node->content['body']['#value'] ?>
  ssss

<?php print $node->content['files']['#value'] ?>



</div>
<?php endif; ?>



<?php if($is_front || $node->promote){ }else{ ?>
  <div class="clear-block clear">
    <div class="meta">
    <?php if ($taxonomy): ?>
      <div class="terms"><?php print $terms ?></div>
    <?php endif;?>
    </div>

  </div>
<?php } ?>

</div>
