<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php if ($page == 0): ?>
<img src="<?php print base_path().$node->field_mm_video_preview_img[0]['filepath'] ?>" hspace="4" vspace="4" align="left" alt="<?php print $title ?>" class="mma" />
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

 <?php if ($teaser): ?>
<div class="content">
<?php print check_plain($node->field_mm_teasertxt[0]['value']); ?>


</div>
<?php else: ?>
<div class="content">

<?php
if($node->content['field_mm_video_embed']['#value']){
  print "<center>".$node->content['field_mm_video_embed']['#value']."</center>";
}elseif($node->field_mm_video_embed_oth[0]['value']){
  print "<center>".$node->field_mm_video_embed_oth[0]['value']."</center>";
} ?>

<div class="mediablock"><?php print $node->content['fivestar_widget']['#value'] ?></div>
<?php if($node->field_mm_video_preview_img[0]['filepath']){
?><img src="<?php print base_path().$node->field_mm_video_preview_img[0]['filepath']; ?>" alt="<?php print $node->field_mm_video_preview_img[0]['alt'] ?>" hspace="8" vspace="8" align="left" /> <?php }
print $node->content['body']['#value'] ?>
<br />
<?php if($node->content['files']['#value']){
  print $node->content['files']['#value'];
} ?>
</div>
<?php endif; ?>

  <div class="clear-block clear">
    <div class="meta">
    <?php if ($taxonomy): ?>
      <div class="terms"><?php print $terms ?></div>
    <?php endif;?>
        <?php if ($links): ?>
      <div class="links"><?php print $links; ?></div>
    <?php endif; ?>
    </div>

  </div>

</div>
