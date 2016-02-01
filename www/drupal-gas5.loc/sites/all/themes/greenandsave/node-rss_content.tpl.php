<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">


<?php if ($page == 0){ ?>
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php
  /*
$string = $node->pages[0];
$teasercontent = preg_replace("/(\<div(/?[^\>]+)\>)|(\<\/div\>)/", '', $string);
 print $teasercontent;

  */
  print $content;

}elseif($teaser){


 print $node->pages[0];

}else{
  print $content;

}
?>



</div>
