<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">


  <div class="content">
    <?php print $content ?>
    <?php
    GLOBAL $user;
    if($user->uid == '1'){
      print '<br />pg count: '.$node->pages_count.'<br />';
    }
    ?>
    <?php

$type = $_GET['offer'];
$link  = $_GET['nid'];

 $mm = $_GET['date'].' Eco Academy email open rate ';
$ruir = $link;
 db_query("INSERT INTO {emailopenrates} (type, message, referer, hostname, timestamp) VALUES ('%s', '%s', '%s', '%s', %d)",
          $type, $mm, $ruir, $_SERVER['REMOTE_ADDR'], time());

?>
  </div>
</div>
