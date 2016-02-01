
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language ?>" xml:lang="<?php print $language ?>">
<head>
  <title><?php print $head_title ?></title>
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <?php print $head ?>
  <?php print $styles ?>
  <?php print $scripts ?>
</head>

<body>

<div id="wrapper">

      <?php if($topad){
   ?><div id="tga"><?php print $topad; ?> </div><?php
  }
  ?>

  <div id="topnav">
    <?php if ($topnav): print $topnav; endif; ?>
  </div><?php //end topnav ?>

  <div id="header">
    <div id="portal"><div class="portal_<?php print $portal_class; ?>">
      <?php if ($portal): print $portal; endif; ?>
      <div id="portalnav"><?php if ($portalnav): print $portalnav; endif; ?></div>
      </div>
    </div>
    <div id="logo"><?php
        if ($logo) {
              print '<a href="'.base_path().'" title="HOME"><img src="'. check_url($logo) .'" alt="HOME" id="logo" /></a>';
            } ?>
    </div>
  </div><?php //end header ?>
  <div id="navigation"><div class="navigation_bg_<?php print $nav_class; ?>">
    <?php if ($storenavigation): $storenavigation = preg_replace_callback('!<a.*?href="([^"]+)".*?>!', 'nofollow_link_replace', $storenavigation);
        print $storenavigation;  endif;
      ?>
      </div>
  </div>
    <?php if($amazonsearch){ print '<div class="asearch">'.$amazonsearch.'</div>'; } ?>
<?php
  if( ($breadcrumb) && ($nav_class == 'gctc' || $nav_class == 'eco_academy' || $nav_class == 'store')){
    print '<div class="nobreadcrumb"></div>';
  }else{
    print $breadcrumb; 
  } ?>
    <?php if ($help): print $help; endif; ?>
    <?php if ($messages): print $messages; endif; ?>

  <div id="forum">
    <?php if ($tabs): print $tabs; endif; ?>
    <?php if (isset($tabs2)): print $tabs2; endif; ?>

    <?php if ($title): print '<h1'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h1>'; endif; ?>

    <?php print $content ?>

    <span class="clear"></span>
    <?php print $feed_icons ?>
  </div><?php // end fp main ?>

  <div id="lowad">
    <?php if ($lowad): print $lowad; endif; ?>
  </div>

  <div id="footer">
    <?php if ($footer): print $footer; endif; ?>
    <div class="footermsg"><?php if ($footer_message): print $footer_message; endif; ?></div>
  </div><?php //end footer ?>
</div><?php //end wrapper ?>
<!--  <script language="JavaScript">-->
<!--    GA_googleFetchAds();-->
<!--  </script>-->
  <?php print $closure ?>
</body></html>
