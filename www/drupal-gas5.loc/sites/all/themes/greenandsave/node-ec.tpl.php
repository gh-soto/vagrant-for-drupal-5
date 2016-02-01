 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

 <?php if ($teaser): ?>
<div class="content">
<div id="ec-content_header">
<?php if ($node->iid) $image = node_load($node->iid); ?>
<img src="<?php print base_path().$image->images['preview']; ?>" border="0" alt="">
<div class="header_text">
<div class="header_title"><h1><div class="header_title_text"><?php print check_markup($node->title) ?></div></h1></div>
<div class="header_caption"><div class="header_caption_text"><?php print check_markup($node->field_caption[0]['value']) ?></div></div>

</div></div>

<div class="content">
  <div class="spacer6"><?php print $node->body ?></div>  
</div>

</div>
<?php else: ?>
<div class="content">

<div id="ec-content_header">
<?php if ($node->iid) $image = node_load($node->iid); ?>
<img src="<?php print base_path().$node->field_image_caption[0]['filepath'] ?>" border="0" alt="">
<div class="header_text">
<div class="header_title"><h1><div class="header_title_text"><?php print check_markup($node->title) ?></div></h1></div>
<div class="header_caption"><div class="header_caption_text"><?php print check_markup($node->field_caption[0]['value']) ?></div></div>

</div></div>

<div class="eccontent">
  <div class="spacer6"><div class="spacer20"></div><?php print $node->body ?><div class="spacer20"></div></div>  
</div>

</div>
<?php endif; ?>

</div>