 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

 <?php if ($teaser): ?>
 
 <div class="content">
<div id="questionwrapper">
  <div id="questionlinkto">Answer to: <a href="<?php print drupal_get_path_alias('node/'.$node->nid) ?>">
  <?php print check_plain($node->title) ?></a></div>
  <div id="questiondate"><?php print date('F j, Y \a\t g:i a', $node->created) ?> :: Comments to date: <?php print $node->comment_count ?></div>
  </div>
</div>

<?php else: ?>

<div class="content">
<div id="questionbody">
<div id="questionwrapper"><div id="questionquestion"><?php print $node->question ?></div>
<div id="questionexpert">The Expert Answer:</div>
<div id="questionanswer"><?php print $node->answer ?></div>
<div id="questiondate"><?php print date('F j, Y \a\t g:i a', $node->created) ?> :: Comments to date: <?php print $node->comment_count ?></div></div></div>
</div>

<?php endif; ?>

</div>