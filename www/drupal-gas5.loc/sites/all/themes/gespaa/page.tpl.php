<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language ?>" xml:lang="<?php print $language ?>">
<head>
<title><?php print $head_title ?></title>
<meta http-equiv="Content-Style-Type" content="text/css" />
<?php print $head ?><?php print $styles ?>
</head>
<body <?php print theme("onload_attribute"); ?>> 
<div id="outerwrap"><div id="wrap">
    <?php if (is_array($primary_links)) : ?> 
    <div id="navdiv"><ul id="nav"> 
      <?php foreach ($primary_links as $link): ?> 
      <li><?php print $link?></li> 
      <?php endforeach; ?> 
    </ul></div> 
    <?php endif; ?> 
  <div id="header"> 
    <div id="headerimg"> 
      <h1><a href="<?php print url() ?>" title="<?php print($site_name) ?>"><?php print($site_name) ?></a></h1> 
      <div class="slogan"><?php print($site_slogan) ?></div> 
    </div>  
  </div> 
  <br /> 
  <div id="maincontent" class="narrowcolumn"> 
    <div class="navigation"> <?php print $breadcrumb ?> </div>
    <?php if ($tabs != ""): ?> 
    <?php print $tabs ?> 
    <?php endif; ?> 
    <?php if ($messages != ""): ?> 
    <div id="message"><?php print $messages ?></div> 
    <?php endif; ?> 
    <?php if ($mission != ""): ?> 
    <div id="mission"><?php print $mission ?></div> 
    <?php endif; ?> 
    <?php if ($title != ""): ?> 
    <h2 class="page-title"><?php print $title ?></h2> 
    <?php endif; ?>  
    <?php if ($help != ""): ?> 
    <p id="help"><?php print $help ?></p> 
    <?php endif; ?> 
    <!-- start main content --> 
    <?php print($content) ?> 
    <!-- end main content --> 
  </div> 
  <div id="sidebar"> 
    <?php if ($search_box): ?> 
    <form action="<?php print $search_url ?>" method="post" id="searchform"> 
      <input class="form-text" type="text" size="15" value="" name="edit[keys]" id="s" /> 
      <input class="form-submit" type="submit" value="<?php print $search_button_text ?>" id="searchsubmit" /> 
    </form> 
    <?php endif; ?> 
    <?php print $sidebar_left ?> <?php print $sidebar_right ?> </div> 
  <br /> 
  <div id="footer"> 
    <p> 
      <?php if ($footer_message) : ?> 
      <?php print $footer_message;?><br /> 
      <?php endif; ?>  
      <a href="/node/feed/">RSS</a> <strong>|</strong> Using the <a href="http://www.ifelse.co.uk/gespaa">Gespaa theme</a> designed by <a href="http://www.ifelse.co.uk">Phu Ly</a>, ported to Drupal by Jarrod Piccioni of <a href="http://goodbasic.com/">GoodBasic</a>. </p> 
  </div> 
</div></div> 
<?php print $closure;?> 
</body>
</html>