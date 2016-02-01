
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language ?>" xml:lang="<?php print $language ?>">
<head>
<!-- dd -->
  <title><?php print $head_title ?></title>
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <?php print $head ?>
  <?php print $styles ?>
  <?php print $scripts ?>
<?php if($nav_class == 'gctc'){ ?>
  <style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/doubleline-drop.css";</style>
  <style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/doubleline-drop.css";</style>
<?php }else{ ?>
  <style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/navigation-drop.css";</style>
<style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/portal-drop.css";</style>
<?php } ?>
</head>

<body>
<div id="wrapper">
  <div id="topnav">
    <?php if ($topnav): print $topnav; endif; ?>
  </div><?php //end topnav ?>
  <div id="header">
    <div id="portal"><div class="portal_cart">
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


  <span class="clear"></span>
  <div id="rightsidebar">
     <?php if ($rightsidebar): print $rightsidebar; endif; ?>
    </div>
  <?php if($nav_class == 'gctc'){ }else{ ?>
  <?php if ($breadcrumb)  print $breadcrumb; else print '<div class="nobreadcrumb"></div>'; } ?>

    <?php if ($help): print $help; endif; ?>
    <?php if ($messages): print $messages; endif; ?>

  <div id="main">
    <?php if ($tabs): print $tabs; endif; ?>
    <?php if (isset($tabs2)): print $tabs2; endif; ?>

    <?php if ($title): print '<h1'. ($nav_class ? ' class="'.$nav_class.'"' : '') .'>'. $title .'</h1>'; endif; ?>

    <?php print $content ?>

    <?php print $feed_icons ?>
  </div><?php // end fp main ?>


  <div class="clear"></div>
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
