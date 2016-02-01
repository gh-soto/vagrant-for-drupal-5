<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php
$mypath = drupal_get_path_alias($_GET['q']);
$mypath = explode("/", $mypath);
?>
<?php  if($node->field_gn_puboptions[0]['value'] == 'p' && $mypath[0] == 'news' && $mypath[1] == ''){}else{ ?><?php if ($page == 0): ?>
<img src="<?php print base_path().$node->field_gn_caption_image[0]['filepath'] ?>" hspace="4" vspace="4" height="25%" align="left" width="25%" alt="<?php print $title ?>" align="left" />
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>
  <? } ?>

 <?php if ($teaser): ?>
<div class="content">
<?php
$mypath = drupal_get_path_alias($_GET['q']);
$mypath = explode("/", $mypath);

if($node->field_gn_puboptions[0]['value'] == 'p' && $mypath[0] == 'news' && $mypath[1] == '' ){ //front page slideshow view ?>

  <a href="<?php print drupal_get_path_alias('node/'.$node->nid) ?>" title="<?php print check_plain($node->title) ?>">
  <center><img src="<?php print base_path().$node->field_gn_caption_image[0]['filepath'] ?>" /></center>
  <h3><?php print check_plain($node->title) ?></h3></a>
  <?php print $node->teaser; ?>  <a href="<?php print drupal_get_path_alias('node/'.$node->nid) ?>" title="<?php print check_plain($node->title) ?>">full story</a>
 
<?php

}else{
print $node->teaser;
}
?>

</div>
<?php else: ?>
<div class="content">
ssss
<div class="spacer6">
<?php if(check_plain($node->field_gn_display_fivestar[0]['value']) == 'n'){
    }else{ ?>
    <div class="socialmedia">
      
      <ul><li><?php print $node->content['sharethis_sharethis_this']['#value'] ?></li>
        <li><a href="<?php print base_path().'send/send/'.$node->nid; ?>" title="Send to a friend"><img src="/files/green_news/gn_email.jpg" alt="Email this article" align="bottom" />  E-mail</a></li>
        <li><a href="/rss"><img src="/files/green_news/gn_rss.jpg" alt="RSS Feeds" align="bottom" />  RSS</a></li>
      </ul> 
      
    </div>
    <br />
    <div class="gn_author"><?php print check_plain($node->field_gn_author[0]['value'] ) ?><br />
    Posted on <?php print date("l jS F Y", $node->created) ?>
    </div>
    <?php } ?>
  <?php if($node->field_gn_caption_image[0]['filepath']){ ?>
  <div class="gn_block">
    <center><img class="gn_img" src="<?php print base_path().$node->field_gn_caption_image[0]['filepath'] ?>" alt="" /></center>
    <br />
    <?php print $node->field_gn_caption_image[0]['title'] ?><br />
    <?php
    }
    if(check_plain($node->field_gn_display_fivestar[0]['value']) == 'n'){
    }else{
      print $node->content['fivestar_widget']['#value'];
    } ?><br />
    <table><tr><td>
  <!--<script type="text/javascript"
	src="http://d.yimg.com/ds/badge2.js"
	badgetype="square">
	<?php print 'http://www.greenandsave.com'.base_path().$node->path; ?>
  </script>-->
  </td><td>
  <script type="text/javascript" src="http://www.reddit.com/button.js?t=1"></script></td><td>
  <script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script></td></tr></table>
<?php
global $user;
if($user->uid == '1'){
  print $node->links['statistics_counter']['title'];
  print '<br />';
}
?>   
<?php
if (arg(0) == 'node' && is_numeric(arg(1)) && is_null(arg(2))) {
  $nid = (int)arg(1);
  $terms = taxonomy_node_get_terms($nid);

  foreach($terms as $term){
    $sql = "SELECT n.title, n.nid FROM {node} n INNER JOIN {term_node} tn ON n.nid = tn.nid WHERE tn.tid = $term->tid AND n.nid != $nid AND n.status=1 ORDER BY n.created DESC LIMIT 5";  
    $result = db_query(db_rewrite_sql($sql));
    if (db_num_rows($result)) {
      $output .= '<div class="gn_related">More '.$term->name.'</div><ul>';
      while ($anode = db_fetch_object($result)) {
        $output .= "<li>".l($anode->title, "node/$anode->nid")."</li>";
      }  
      $output.="</ul>";
    }
  }

  print $output;
}
?>
<br />



<?php if($node->field_gn_caption_image[0]['filepath']){ ?></div>

<?php } ?>
    <div style="clear:left;"></div><?php print $node->content['body']['#value']; ?>
    <?php if($node->content['files']['#value']) print $node->content['files']['#value']; ?>
</div>


</div>
<?php endif; ?>


<?php  if($node->field_gn_puboptions[0]['value'] == 'p' && $mypath[0] == 'news' && $mypath[1] == ''){}else{ ?>
  <div class="clear-block clear">
    <div class="meta">

        <?php if ($links){ ?>
      <div class="links"><?php print $links; ?></div>
    <?php } ?>
    </div>

  </div>
  <? } ?>

</div>
