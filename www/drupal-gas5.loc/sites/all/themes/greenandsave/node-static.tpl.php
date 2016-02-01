 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

 <?php if ($teaser): ?>
<div class="content">
 <?php print $content; ?>


</div>
<?php else: ?>
<div class="content">
 <?php print $content; ?>

</div>
<?php endif; ?>

</div>