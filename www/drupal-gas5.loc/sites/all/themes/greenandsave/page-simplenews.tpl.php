
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language ?>" xml:lang="<?php print $language ?>">
<head>
<!-- newsletter -->
  <title><?php print $head_title ?></title>
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <?php print $head ?>
  <?php print $styles ?>
  <?php print $scripts ?>
</head>

<body><?php
  	    /* $mypath = drupal_get_path_alias($_GET['q']);
		$mypath = explode("/", $mypath);
		$path_class = $mypath[0];
        print_r($mypath);
        */
		
        ?>
<div id="wrapper">
  <div id="topad">
    <?php if ($left_topad): print $left_topad; endif; ?>
	<?php if ($right_topad): print $right_topad; endif; ?>
    </div><?php //end topad ?>

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

<?php // no menu ?>

  <span class="clear"></span>
  <div id="rightsidebar">
     <?php
	 if ($rightsidebar){ print $rightsidebar; }
    ?>
    </div>
  
    <?php if ($help): print $help; endif; ?>
    <?php if ($messages): print $messages; endif; ?>

  <div id="main">
    <?php if ($tabs): print $tabs; endif; ?>
    <?php if (isset($tabs2)): print $tabs2; endif; ?>

    <?php if ($title): print '<h1'. ($nav_class ? ' class="'.$nav_class.'"' : '') .'>'. $title .'</h1>'; endif; ?>
	
    <?php print $content ?>

    <?php print $feed_icons ?>
  </div><?php // end main ?>

  <div id="lowad">
    <?php if ($lowad): print $lowad; endif; ?>
  </div>

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
