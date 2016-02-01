<?php
  if ( is_numeric(arg(1)) ){  /* for node/NID */
    $nid = arg(1);
  }elseif( is_numeric(arg(2)) ){ /* for comment/reply/NID */
    $nid = arg(2);
  }
$node = node_load(array('nid' => $nid));
$drupaltype = $node->type;


$path = $_GET['q'];
$path = $_SERVER['REQUEST_URI'];
$pather = explode("/", $path);
$pather2  = $pather[1];
$pather3 = $pather[2];
$pather = $pather[0];


  if ($is_front || $path == '' || $path == '/') {
    include 'page-frontpage.tpl.php'; 
    return;
  }
  
  if ($path == '/greenoffice' || $pather == 'greenoffice' ) {
    include 'page-go_frontpage.tpl.php'; 
    return;
  }

  if ($path == '/media' || $pather == 'media' ) {
    include 'page-vi_frontpage.tpl.php'; 
    return;
  }
  
  if ($path == '/news' || $pather == 'news' ) {
    include 'page-gn_frontpage.tpl.php'; 
    return;
  }
  
  if ($path == '/homeremodeling' || $pather == 'homeremodeling' ) {
    include 'page-hr_frontpage.tpl.php'; 
    return;
  }
  
  if($node->type == 'blog' || $drupaltype == 'blog' || $pather == 'month') {
    include 'page-blog.tpl.php'; 
    return;
  }
  
  if($pather == 'newsletter' || $drupaltype == 'simplenews') {
    include 'page-simplenews.tpl.php'; 
    return;
  }  
  if ($path == '/green-news/climate-weather' || $pather3 == '1946' || $pather == 'green-news' ) {
    include 'page-default.tpl.php'; 
    return;
  
}
if($node->type == 'astorepage' || $pather == 'astorepage' || $node->type == 'static' || $node->type == 'subscription'){
    include 'page-forum.tpl.php'; 
    return;
  }

  
  include 'page-default.tpl.php'; /*if none of the above applies, load the page-default.tpl.php */
  return;
?>
