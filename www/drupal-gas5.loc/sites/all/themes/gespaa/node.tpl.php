<div class="entry<?php print ($sticky) ? " sticky" : ""; ?>"> 
  <?php if ($page == 0): ?> 
  <h3 class="entrytitle"><a href="<?php print $node_url ?>" rel="bookmark" title="Permanent Link to <?php print $title ?>"><?php print $title ?></a></h3> 
  <?php endif; ?> 
  <div class="entrybody">
   <strong><?php print $submitted ?></strong> 
   <?php print $content ?>
   <?php if ($links): ?> 
   <p class="comments_link"><img src="http://thehostblog.com/sites/thehostblog-com.bryght.net/files/theme_editor/kubrick_customized/file.gif" title="file" alt="*" />&nbsp;Filed Under: <?php print $terms ?><br />
<img src="http://thehostblog.com/sites/thehostblog-com.bryght.net/files/theme_editor/kubrick_customized/comments.gif" title="file" alt="*" />&nbsp;<?php print $links ?> &#187;</p> 
   <?php endif; ?>
  </div>  
</div>