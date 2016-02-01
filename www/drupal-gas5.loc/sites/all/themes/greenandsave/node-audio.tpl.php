 <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

<?php if ($teaser): ?>
<div class="content">
<?php
for($x = 0;$x <= 30; $x++){
  if($node->audio_images[$x]['filepath']){
    $coverimg = $node->audio_images[$x]['filepath'];
    break;
  }
}

  $mypath = drupal_get_path_alias($_GET['q']);
  $mypath = explode("/", $mypath);
  
if($mypath[0] == 'news' && $mypath[1] == ''){ //for news frontpage
  
  if($coverimg){ ?>
    <img src="<?php print base_path(); ?>files/mini-podcast-icon.jpg" align="left" hspace="10" />
    <a href="<?php print base_path().$node->path; ?>"><?php print $node->title; ?></a>
    <span class="clear"></span>
   
  <?php }
  
}else{

  if($coverimg){ ?>
  <img src="<?php print base_path().$coverimg ?>" width="160" height="160" hspace="4" vspace="4" align="left">
  <?php } ?>
     <h2> <a href="<?php print base_path().$node->path; ?>"><?php print $node->title; ?></a></h2>
      <?php print $node->content['body']['#value'] ?>
  <ul>Play Count: <?php print $node->audio_fileinfo['play_count'] ?>
  <?php
  if( $node->audio_fileinfo['filesize'] > 0){
    $pre = ($node->audio_fileinfo['filesize']/1048576*100000)/100000;
    $audiofilesize = round($pre, 2);
  }else{
    $audiofilesize = 'unknown ';
  } ?>
  <br />Filesize: <?php print $audiofilesize; ?>MB
  <br />Format/Sample Rate: <?php print $node->audio_fileinfo['channel_mode'] ?> <?php print $node->audio_fileinfo['fileformat'] ?>/<?php print $node->audio_fileinfo['sample_rate'] ?> Hz<br />
  </ul>
  <?php print $node->content['field_audio_clips']['#value'] ?>
  <span class="clear"></span>
  
<?php }
// end news frontpage
?>
</div>


<?php else: ?>



<div class="content">
<?php
for($x = 0;$x <= 20; $x++){
  if($node->audio_images[$x]['filepath']){
    $coverimg = $node->audio_images[$x]['filepath'];
    break;
  }
}
?>

<div class="gghs_bg"><?php
if($coverimg){ ?>
<img src="<?php print base_path().$coverimg ?>" width="160" height="160" hspace="4" vspace="4" align="left">
<?php } ?>
<div class='audio-node block'>
    <ul class="audio-info">
      <li><object type="application/x-shockwave-flash" data="/sites/all/modules/audio/players/1pixelout.swf" width="290" height="24">
      <param name="movie" value="/sites/all/modules/audio/players/1pixelout.swf" />
      <param name="menu" value="false" />
      <param name="quality" value="high" />
      <param name="wmode" value="transparent" />
  <param name="FlashVars" value="soundFile=<?php print base_path(); ?>audio/play/<?php print $node->nid; ?>" />
  <embed src="/sites/all/modules/audio/players/1pixelout.swf" flashvars="soundFile=<?php print base_path(); ?>audio/play/<?php print $node->nid ?>" width="290" height="24" />
</object></li></ul></div></div>
<ul>
<br /><?php print $node->content['body']['#value'] ?>
<br />Play Count: <?php print $node->audio_fileinfo['play_count'] ?>
<br /><a href="<?php print $node->url_download ?>">Download</a>
<?php
if( $node->audio_fileinfo['filesize'] > 0){
  $pre = ($node->audio_fileinfo['filesize']/1048576*100000)/100000;
  $audiofilesize = round($pre, 2);
}else{
  $audiofilesize = 'unknown ';
} ?>
<br />Filesize: <?php print $audiofilesize; ?>MB
<br />Format/Sample Rate: <?php print $node->audio_fileinfo['channel_mode'] ?> <?php print $node->audio_fileinfo['fileformat'] ?>/<?php print $node->audio_fileinfo['sample_rate'] ?> Hz<br />
</ul>
<?php print $node->content['field_audio_clips']['#value'] ?>
<p>
</div>
  <?php endif; ?>
  </div>