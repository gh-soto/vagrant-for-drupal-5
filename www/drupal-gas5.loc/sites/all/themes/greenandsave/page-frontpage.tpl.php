<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language ?>" xml:lang="<?php print $language ?>">
<head>
    <!-- fp -->
  <title><?php print $head_title ?></title>
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <?php print $head ?>
  <?php print $styles ?>
  <?php print $scripts ?>
</head>

<body>

<div id="wrapper">

  <div id="topnav">
    <?php if ($topnav): print $topnav; endif; ?>
  </div><?php //end topnav ?>

  <div id="header">
    <div id="portal"><div class="portal_sitefp">
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
  

  <?php if ($help): print $help; endif; ?>
  <?php if ($messages): print $messages; endif; ?>

  <div id="sitefp_main">
    <div id="sitefp_mini">
      <?php if ($fp_mini): print $fp_mini; endif; ?>
    </div>
    <div id="sitefp_ss">        
        <?php // slideshow ?>
        <?php if ($sitefp_ss): print $sitefp_ss; endif; ?>
    </div>
    <span class="clear"></span>
    <div id="rightsidebar">
     <?php if ($rightsidebar): print $rightsidebar; endif; ?>
    </div>

    <div id="sitefp_lowerblocks"><?php // q and a ?>
      <?php if ($sitefp_mainblock): print $sitefp_mainblock; endif; ?>
    </div>
    
</div><?php // end fp main ?>

  <div id="lowad">
    <?php if ($lowad): print $lowad; endif; ?>
  </div>
  <span class="clear"></span>
  
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
