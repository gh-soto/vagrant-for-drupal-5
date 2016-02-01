 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

 <?php if ($teaser): ?>
<div class="content">
   <?php print $content ?>

</div>
<?php else: ?>
<div class="content">

<div id="innerblock">
<?php if ($node->iid) $image = node_load($node->iid); ?>
<div class="innerimg"><img src="<?php print base_path().$image->images['preview']; ?>" alt="<?php print check_plain($node->title) ?>"></div>
<div class="innercaption">
<?php print check_markup($node->field_caption[0]['value']) ?></div>
<center>
<?php print $node->links['share__'.$node->nid]['title'] ?>
<a href="<?php print base_path() ?><?php print $node->links['comment_add']['href'] ?>" title="Add comment"><?php print $node->links['comment_add']['title'] ?></a>
</center>
</div>

<?php print $node->content['body']['#value'] ?>

</div>
<?php endif; ?>

</div>