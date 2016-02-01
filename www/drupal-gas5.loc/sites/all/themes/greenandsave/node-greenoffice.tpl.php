<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php
$mypath = drupal_get_path_alias($_GET['q']);
$mypath = explode("/", $mypath);

if(($is_front || $node->promote ) && ($mypath[0] == 'greenoffice' && $mypath[1] == '')){ }else{ ?>
<?php if ($page == 0):
  if($node->field_greenoffice_teaser_img[0]['filepath']){ ?>
       <img src="<?php print base_path().$node->field_greenoffice_teaser_img[0]['filepath'] ?>" hspace="4" vspace="4" height="15%" align="left" width="15%" alt="<?php print $title ?>" align="left" />
  <?php }else{ ?>
      <img src="<?php print base_path()?>files/teaser_default.jpg" hspace="4" vspace="4" height="15%" align="left" width="15%" alt="<?php print $title ?>" align="left" />
 <?php  } ?>
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>
<?php } ?>



<?php if ($teaser): ?>
<div class="content">
<?php
$mypath = drupal_get_path_alias($_GET['q']);
$mypath = explode("/", $mypath);

if($node->promote > 0 && $mypath[0] == 'greenoffice' && $mypath[1] == '' ){ //front page slideshow view ?>
<div class="slideshow" style="background:url('<?php print base_path().$node->field_greenoffice_teaser_img[0]['filepath'] ?>') top left no-repeat;">
  <div class="slideshow_txt_bg"><div class="slideshow_teaser">
    <a href="<?php print drupal_get_path_alias('node/'.$node->nid) ?>" title="<?php print check_plain($node->title) ?>"><?php print check_plain($node->title) ?></a><br />
    <?php print check_plain($node->field_go_teaser_txt[0]['value']) ?>
  </div>
</div>
</div> <?php

}else{
 print check_plain($node->field_go_teaser_txt[0]['value']);
}
?>

</div>
<?php else: ?>
<div class="content">

<?php 
$checkinner = check_plain($node->field_display_inner_block[0]['value']);
$nf = count($node->field_related_greenoffice_conte);
//$share = share_popup_load(4, 'share_sharethis');
//$output = $share->render($node->nid); 
//$sharethis = $output;
$sharethis = '';
$showimage = '';    
if($node->field_related_greenoffice_conte[0]['nid'] > 0 || $node->field_greenoffice_teaser_img[0]['filepath']){
  $innerblockclass = "innerblock";
  $block_sharethis = $sharethis;
  $showimage = '<img src="'.base_path().$node->field_greenoffice_teaser_img[0]['filepath'].'" alt="'.check_plain($node->title).'">';
}elseif($checkinner == 'v' || $node->field_greenoffice_teaser_img[0]['filepath']){
  $innerblockclass = "innerblock";
  $block_sharethis = $sharethis;
  $showimage = '<img src="'.base_path().$node->field_greenoffice_teaser_img[0]['filepath'].'" alt="'.check_plain($node->title).'">';
}elseif($checkinner == 'h' || $node->field_greenoffice_teaser_img[0]['filepath']){
  $innerblockclass = "innerblock_h";
  $showimage = '<img src="'.base_path().$node->field_greenoffice_teaser_img[0]['filepath'].'" alt="'.check_plain($node->title).'">';
}elseif($checkinner == 'n'){
  $innerblockclass = "innerblock_n";
  $lower_sharethis = $sharethis;
}elseif($checkinner == ''){
  $innerblockclass = "innerblock_n";
  $lower_sharethis = $sharethis;
}else{
  $innerblockclass = "innerblock";
}

if(check_plain($node->field_go_select_list[0]['value']) == 'cl'){
 
  $related_title = 'Check List:';
}elseif(check_plain($node->field_go_select_list[0]['value']) == 'ac'){
    $related_title = 'Also Consider:';
}

if($node->field_related_greenoffice_conte[0]['nid'] > 0){
  
  $output = '<ul><b>'.$related_title.'</b>';
    for($i = 0;$i < $nf;$i++){
      $nt = node_load($node->field_related_greenoffice_conte[$i]['nid']);
      $output .= '<li><a href="'.base_path().drupal_get_path_alias('node/'.$nt->nid).'">'.$nt->title.'</a></li>';
    }
  $output .= '</ul>';
}else{
  $output = '';
    
}
if($innerblockclass == "innerblock_n"){}else{ ?>
  <div id="<?php print $innerblockclass; ?>">
    <div class="innerimg"><?php print $showimage; ?></div>
    <div class="innercaption"><?php print check_markup($node->field_greenoffice_teaser_img[0]['title']) ?></div>
  <?php
    print $output;
    print '<center>'.$block_sharethis.'</center>.';
    ?></div><?php
 } ?>

<?php print $node->content['body']['#value'];
print '<p>'.$lower_sharethis;
?>


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

