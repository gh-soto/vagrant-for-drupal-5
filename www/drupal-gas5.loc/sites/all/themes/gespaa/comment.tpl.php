<div class="comment <?php print ($comment->new) ? 'comment-new' : '' ?>"> 
  <?php if ($comment->new) : ?> 
  <a id="new"></a> <span class="new"><?php print $new ?></span> 
  <?php endif; ?> 
  <span class="commentmeta"><cite><?php print $author ?> Says:</cite><br /> 
  <small class="commentmetadata"><?php print $date ?></small></span>
  <div class="commentbody"><?php print $content ?></div> 
  <?php if ($picture) : ?> 
  <br class="clear" /> 
  <?php endif; ?> 
  <p class="postmetadata"><?php print $links ?> &#187;</p> 
</div>