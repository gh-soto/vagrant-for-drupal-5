<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php if ($page == 0): ?>
<img src="<?php print base_path().$node->field_image_caption[0]['filepath'] ?>" hspace="4" vspace="4" height="25%" align="left" width="25%" alt="<?php print $title ?>" align="left" />
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

<?php if ($teaser): ?>
<div class="content">

<?php print $teaser ?>
</div>
<?php else: ?>
<div class="content">
<div class="spacer6">
<div class="ga_author"><?php print check_plain($node->field_author_txt[0]['value']) ?><br />
Posted on <?php print date("l jS F Y", $node->created) ?></div>
  <div style="margin-left:8px;margin-right:5px;color:#ccc;font-size:10px;float:right;display:block;width:300px;height:auto;">
    <img src="<?php print base_path().$node->field_image_caption[0]['filepath'] ?>" alt="" hspace="4" vspace="4" />
    <?php print $node->field_image_caption[0]['alt'] ?><br />
    
<?php print $node->content['fivestar_widget']['#value'] ?>
<?php print $node->links['share__'.$node->nid]['title'] ?>
</div>
  <?php print $node->content['body']['#value'] ?>
</div>



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
