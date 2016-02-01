 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

 <?php if ($teaser): ?>
<div class="content">
<?php
$mypath = drupal_get_path_alias($_GET['q']);
$pt = explode("/", $mypath);
// $pt[0];
if($node->comment_count > 0){
  $msgs = $node->comment_count.' Comments';
}else{
  $msgs = 'Be the first to comment';
}

$output = '<div style="clear:both;float:left;display:inline;width:100px;margin-left:60px;">'.$node->content['fivestar_widget']['#value'].'</div>
  <div style="clear:both" >
        <center><img src="http://www.greenandsave.com'.base_path().$node->field_ca_cartoonimage[0]['filepath'].'" alt="" width="400" height="200" />
          <br />'.$node->field_ca_cartoonimage[0]['title'].'
        </center>
  </div>
  <div class="links">
    <a href="'.base_path().$node->path.'" title="'.$node->title.'" >'.$msgs.'</a> | <a href="'.base_path().$node->path.'" title"Read more">Read more</a>
  </div>';
        $teaserbody = $output;
        $tehbody = $teaserbody;

print $tehbody;
?>
<span class="clear"></span>

</div>
<?php else: ?>
<div class="content">

<?php
$mypath = drupal_get_path_alias($_GET['q']);
$pt = explode("/", $mypath);
// $pt[0];
if($node->comment_count > 0){
  $msgs = $node->comment_count.' Comments';
}else{
  $msgs = 'Be the first to comment';
}
if(($pt[0] == 'news' && $pt[1] == '') || ($pt[0] == 'media' && $pt[1] == 'cartoon' && $pt[2] == '')){
    $counter = count($node->field_ca_cartoonimage);
    if($counter >= 2){    
        if(preg_match('/##slideshow##/', $node->content['body']['#value'])){
          drupal_add_js('misc/js/easySlider.js');
          drupal_add_css('misc/js/easySlider.css');
          
          drupal_add_js("$(document).ready(function(){
                $('.enode .eslideshow .item-list').easySlider({
                  prevId: 'prevBtn', 
                  nextId: 'nextBtn',
                  prevText: '',
                  nextText: '', 
                  loop: true
                });
            });", 'inline', 'header');
          $output = '<div class="cartoonslider">
          <div style="float:right;display:inline;margin-right:50px;height:35px;"><a href="'.base_path().$node->path.'" title="'.$node->title.'" >'.$msgs.'</a></div>
          <div style="float:left;display:inline;width:110px;margin-left:60px;height:35px;">'.$node->content['fivestar_widget']['#value'].'</div><br /><br /><br />
          <div  class="enode"><div class="eslideshow"><div class="item-list"><ul>';    
          
          for($i=0; $i < $counter; $i++){
          $output .= '<li><div class=\'view-item\'><img src="http://www.greenandsave.com'.base_path().$node->field_ca_cartoonimage[$i]['filepath'].'" alt="slide '.$i.'" title="slide '.$i.'"  class="image image-preview " width="400" height="200" /><br /><center>'.$node->field_ca_cartoonimage[$i]['title'].'  ('.($i+1).'/'.$counter.')</center></div></li>';
          }
          $output .= '</ul></div></div></div></div>';
          $teaserbody = $output;
        $tehbody = $teaserbody;
        }else{
          $teaserbody = $node->content['body']['#value'];
          $tehbody = $teaserbody;

        }
    }elseif($counter == 1){
        $output = '<div style="float:right;display:inline;margin-right:50px;"><a href="'.base_path().$node->path.'" title="'.$node->title.'" >'.$msgs.'</a></div>
        <div style="float:left;display:inline;width:100px;margin-left:60px;">'.$node->content['fivestar_widget']['#value'].'</div>
        <center><img src="http://www.greenandsave.com'.base_path().$node->field_ca_cartoonimage[0]['filepath'].'" alt="" width="400" height="200" /><br />'.$node->field_ca_cartoonimage[0]['title'].'</center>';
        $teaserbody = $output;
        $tehbody = $teaserbody;
        }
}else{       
$counter = count($node->field_ca_cartoonimage);
if($counter >= 2){    
    if(preg_match('/##slideshow##/', $node->content['body']['#value'])){
      drupal_add_js('misc/js/easySlider.js');
      drupal_add_css('misc/js/easySlider.css');
      
      drupal_add_js("$(document).ready(function(){	
            $('.enode .eslideshow .item-list').easySlider({
              prevId: 'prevBtn', 
              nextId: 'nextBtn', 
              prevText: '',
              nextText: '', 
              loop: true
            });
        });", 'inline', 'header');
      $output = '<div class="cartoonslider"><div style="float:left;display:inline;width:100px;">'.$node->content['fivestar_widget']['#value'].'</div><div style="clear:both;" class="enode"><div class="eslideshow"><div class="item-list"><ul>';    
      
      for($i=0; $i < $counter; $i++){
      $output .= '<li><div class=\'view-item\'><img src="http://www.greenandsave.com'.base_path().$node->field_ca_cartoonimage[$i]['filepath'].'" alt="slide '.$i.'" title="slide '.$i.'"  class="image image-preview " width="400" height="200" /><br /><center>'.$node->field_ca_cartoonimage[$i]['title'].'  ('.($i+1).'/'.$counter.')</center></div></li>';
      }
      $output .= '</ul></div></div></div></div>';
      $tehbody = str_replace('##slideshow##', $output, $node->content['body']['#value']);
      
    }else{
      $tehbody = $node->content['body']['#value'];
    }
}elseif($counter == 1){
    $output = '<div style="float:left;display:inline;width:100px;">'.$node->content['fivestar_widget']['#value'].'</div><div style="clear:both;" class=\'view-item\'><div class=\'view-field\'><center><img src="http://www.greenandsave.com'.base_path().$node->field_ca_cartoonimage[0]['filepath'].'" alt="" width="400" height="200" /><br />'.$node->field_ca_cartoonimage[0]['title'].'</center></div></div>';
    if(preg_match('/##slideshow##/', $node->content['body']['#value'])){
      $tehbody = str_replace('##slideshow##', $output, $node->content['body']['#value']);
    }
}else{
  print $node->content['body']['#value'];
}

}
print $tehbody;
?>
<span class="clear"></span>



</div>
<?php endif; ?>

</div>