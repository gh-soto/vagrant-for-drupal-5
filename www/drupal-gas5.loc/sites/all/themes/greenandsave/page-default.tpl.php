
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
<?php if($nav_class == 'gctc' || $nav_class == 'eco_academy' || $nav_class == 'homecheckup' || $nav_class == 'hec_checkup'){ ?>
  <style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/doubleline-drop.css";</style>
  <style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/doubleline-drop.css";</style>
<?php }else{ ?>
  <style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/navigation-drop.css";</style>
<style type="text/css" media="all">@import "<?php print base_path().path_to_theme() ?>/portal-drop.css";</style>
<?php } ?>
</head>

<body><?php
  	    /* $mypath = drupal_get_path_alias($_GET['q']);
		$mypath = explode("/", $mypath);
		$path_class = $mypath[0];
        print_r($mypath);
        */
		if($portal_class == 'green_news' || $portal_class == 'green_article'){
		  $newswrapper = '_news';
		}else{
		  $newswrapper = '';
		  }
        ?>
<div id="wrapper<?php print $newswrapper; ?>">

  <?php if($topad){
   ?><div id="tga"><?php print $topad; ?> </div><?php
  }
  ?>


<?php if($nav_class == 'gctc' || $nav_class == 'eco_academy' || $nav_class == 'homecheckup' || $nav_class == 'hec_checkup'){ }else{ ?>


  <div id="topnav">
    <?php if ($topnav): print $topnav; endif; ?>
  </div><?php //end topnav ?>

<?php }
if($nav_class == 'gctc' || $nav_class == 'eco_academy'){ ?>
  <div id="header_gctc">
		<div class="hand" ></div>
    <div id="logo"><?php
        if ($logo) {
              print '<a href="'.base_path().'gctc" title="HOME"><img src="'. base_path().path_to_theme().'/img/gctc/greencareerlogo.jpg" hspace="20" alt="HOME" id="logo" /></a>';
            } ?>
    </div>
  </div><?php //end header ?>

<?php }elseif($nav_class == 'homecheckup' || $nav_class == 'hec_checkup'){
  $mypath = drupal_get_path_alias($_GET['q']);
  $mypath = explode("/", $mypath);
  if($mypath[1] == 'services' && $mypath[2] == 'home'){
	$homecheckup_logo = '-serviceshome';
    $link_logo = '4456';
  }elseif($mypath[1] == 'services' && $mypath[2] == 'commercial'){
	$homecheckup_logo = '-servicescommercial';
    $link_logo = '4456';
  }elseif($mypath[1] == 'services' && $mypath[2] == 'b2b'){
	$homecheckup_logo = '-servicesb2b';
    $link_logo = '4456';
  }elseif($mypath[1] == 'services'){
	$homecheckup_logo = '-services';
    $link_logo = '4456';
  }else{
	$homecheckup_logo = '';
	$link_logo = '3471';
  }
?>	
  <div id="header_gctc">
		<div class="hand" ></div>
    <div id="logo"><?php
        if ($logo) {
              print '<a href="'.base_path().'node/'.$link_logo.'" title="HOME"><img src="'. base_path().path_to_theme().'/img/homecheckup-logo'.$homecheckup_logo.'.png" hspace="20" alt="HOME" id="logo" /></a>';
            } ?>
    </div>
  </div><?php //end header 	
}elseif($portal_class == 'green_news' || $portal_class == 'green_article'){ ?>
    <div id="header_news">
    <div id="portal"><div class="portal_<?php print $portal_class; ?>">
      <?php if ($portal): print $portal; endif; ?>
      <div id="portalnav"><?php if ($portalnav): print $portalnav; endif; ?></div>
      </div>
    </div>
  
  <div id="logo"><?php
        if ($logo) {
              print '<a href="'.base_path().'news" title="HOME"><img src="/files/news/newslogo.png" alt="HOME" id="logo" /></a>';
            } ?>
    </div>
  </div><?php //end header ?>
<?php }else{ ?>  
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
<?php } ?>

<?php if($nav_class == 'gctc' || $nav_class == 'eco_academy'|| $nav_class == 'eco-academy' || $nav_class == 'homecheckup' || $nav_class == 'hec_checkup'){ $navigation = 'doubleline'; }else{ $navigation = 'navigation'; } ?>
  
  <div id="<?php print $navigation; ?>"><div class="navigation_bg_<?php print $nav_class; ?>">
    <?php
    if($nav_class == "greenoffice" || $nav_class == "green_office"|| $nav_class == "green-office"){
      if ($go_nav): print $go_nav; endif;
    } //elseif($nav_class == 'ec' || $nav_class == 'eco_academy'){
      //if ($ec_nav): print $ec_nav; endif;
    elseif($nav_class == 'gghs' || $nav_class == 'gctc' || $nav_class == 'eco_academy' || $nav_class == 'homecheckup' || $nav_class == 'hec_checkup' || $nav_class == 'green_news'
		   || $nav_class == "media" || $nav_class == 'audio' || $nav_class == 'green_article' || $nav_class == 'eco-academy' || $nav_class == 'hec-checkup' || $nav_class == 'green-news'
		   || $nav_class == 'green-article'){
      if ($oth_nav): print $oth_nav; endif;
    }else{
      if ($hr_nav): print $hr_nav; endif;
    }  ?>
    </div>
  </div>

  <span class="clear"></span>
  <div id="rightsidebar">
     <?php
	 if($nav_class == 'homecheckup' || $nav_class == 'hec_checkup' || $nav_class == 'hec-checkup' || $nav_class == 'audio' || $nav_class == 'media'){
		if($othsidebar){ print $othsidebar; }
	 }else{
	 if ($rightsidebar){ print $rightsidebar; }
	 } ?>
    </div>
  
  <?php
  if( $breadcrumb && ($nav_class == 'gctc' || $nav_class == 'eco_academy' || $nav_class == 'eco-academy' || $nav_class == 'store' || $nav_class == 'homecheckup' || $nav_class == 'hec_checkup'|| $nav_class == 'hec-checkup' )){
    print '<div class="nobreadcrumb"></div>';
  }else{
    print $breadcrumb; 
  }  ?>
    <?php if($amazonsearch){ print $amazonsearch; } ?>
    <?php if ($help): print $help; endif; ?>
    <?php if ($messages): print $messages; endif; ?>

  <div id="main">
    <?php if ($tabs): print $tabs; endif; ?>
    <?php if (isset($tabs2)): print $tabs2; endif; ?>

    <?php if ($title): print '<h1'. ($nav_class ? ' class="'.$nav_class.'"' : '') .'>'. $title .'</h1>'; endif; ?>
	
    <?php $current = taxonomy_get_term(arg(2)); ?>
	  <?php if ($current): ?>
		<div class="taxonomy-description">
		  <?php echo $current->description; ?>
		</div>
	<?php endif; ?>
	
    <?php print $content ?>

    <?php print $feed_icons ?>
  </div><?php // end fp main ?>

<?php if($nav_class == 'gctc' || $nav_class == 'homecheckup' || $nav_class == 'hec_checkup'){ ?>
  <div id="lowad-gctc">
    <?php if ($lowad): print $lowad; endif; ?>
  </div>
<?php }else{ ?>
  <div id="lowad">
    <?php if ($lowad): print $lowad; endif; ?>
  </div>
<?php } ?>
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
